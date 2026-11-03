<?php
/**
 * One-time content migrations, run once per site on admin load (or WP-CLI) and tracked by option.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Run pending migrations.
 */
function tresundios_run_migrations() {
	$done = (int) get_option( 'tresundios_migration', 0 );
	if ( $done < 1 && tresundios_migration_pmitn() ) {
		update_option( 'tresundios_migration', 1 );
	}
}
add_action( 'admin_init', 'tresundios_run_migrations' );
add_action( 'cli_init', static function () {
	add_action( 'init', 'tresundios_run_migrations', 99 );
} );

/**
 * Migration 1: add the PMI Tamil Nadu case study, put it first and Maria Beauty Care second.
 *
 * @return bool Whether it completed.
 */
function tresundios_migration_pmitn() {
	if ( ! post_type_exists( 'case_study' ) ) {
		return false;
	}

	$slug     = 'pmitn-prison-ministry-website';
	$existing = get_posts(
		array(
			'post_type'      => 'case_study',
			'name'           => $slug,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	$args = array(
		'post_type'    => 'case_study',
		'post_name'    => $slug,
		'post_status'  => 'publish',
		'post_title'   => 'PMI Tamil Nadu, prison ministry & community website',
		'post_excerpt' => 'A compassionate nonprofit website for Prison Ministry India – Tamil Nadu, sharing its outreach, activities and ways to get involved.',
		'post_content' => '',
		'menu_order'   => 1,
	);
	if ( $existing ) {
		$args['ID'] = $existing[0];
	}
	$id = wp_insert_post( wp_slash( $args ), true );
	if ( is_wp_error( $id ) ) {
		return false;
	}

	$meta = array(
		'translation_key' => 'case:pmitn',
		'client'          => 'Prison Ministry India – Tamil Nadu (PMITN)',
		'industry'        => 'Web Development',
		'location'        => 'Tamil Nadu, India',
		'duration'        => '',
		'challenge'       => 'PMITN needed a compassionate, informative and easy-to-navigate website to present its prison outreach work, publish activity updates, explain its mission and invite people to volunteer or contribute. Information about prisons, prisoners, prayer and diocesan programmes also had to be accessible to supporters across Tamil Nadu.',
		'solution'        => 'We developed a responsive WordPress website with dedicated pages for PMITN’s story, activities, prison information, prisoner support, prayer resources, volunteering and contributions. The organisation can share event updates, diocesan programmes and community initiatives, with clear pathways for visitors to get involved: mission-focused pages, activity and event updates, prayer, prison-information and prisoner-support sections, volunteer and contribution pages, and a mobile-friendly design.',
		'results'         => array(
			array(
				'label' => 'Visibility',
				'text'  => 'A professional nonprofit website that clearly communicates PMITN’s mission of hope, rehabilitation and spiritual guidance for incarcerated individuals.',
			),
			array(
				'label' => 'Engagement',
				'text'  => 'Structured activity updates, volunteer information and contribution options make it easier for supporters to take part in the ministry’s work.',
			),
			array(
				'label' => 'Trust',
				'text'  => 'A clean, accessible design reinforces PMITN’s credibility as a nonprofit committed to prisoner welfare, rehabilitation and community reintegration.',
			),
		),
		'tech_stack'      => array( 'WordPress', 'PHP', 'HTML', 'CSS', 'JavaScript', 'Responsive Design' ),
		'live_url'        => 'https://www.pmitn.org/',
		'legacy_url'      => '',
		'featured'        => true,
		'focal'           => '50% 35%',
		'card_tag'        => 'Nonprofit',
		'card_cat'        => 'Website',
		'card_meta'       => array( 'Tamil Nadu' ),
		'card_title'      => 'PMI Tamil Nadu, prison ministry website',
	);
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}
	wp_set_object_terms( $id, array( 'web' ), 'work_type' );

	if ( ! has_post_thumbnail( $id ) ) {
		$img = tresundios_migration_image( TRESUNDIOS_CORE_DIR . '/assets/img/work/pmitn.jpg', 'Prison Ministry India Tamil Nadu website home page: birds flying free from broken chains at sunrise', $id );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}

	// Reorder: PMITN, Maria Beauty Care, then the rest in their current order. Only PMITN is featured.
	$others = get_posts(
		array(
			'post_type'      => 'case_study',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'post__not_in'   => array( $id ),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'DESC',
			),
		)
	);
	usort(
		$others,
		static function ( $a, $b ) {
			return (int) ( 0 !== strpos( $a->post_name, 'maria' ) ) - (int) ( 0 !== strpos( $b->post_name, 'maria' ) );
		}
	);
	foreach ( $others as $i => $p ) {
		wp_update_post(
			array(
				'ID'         => $p->ID,
				'menu_order' => $i + 2,
			)
		);
		update_post_meta( $p->ID, 'featured', false );
	}
	return true;
}

/**
 * Sideload a bundled image into the media library.
 *
 * @param string $path Absolute file path.
 * @param string $alt Alt text.
 * @param int    $parent_id Parent post.
 * @return int Attachment ID, 0 on failure.
 */
function tresundios_migration_image( $path, $alt, $parent_id ) {
	if ( ! is_readable( $path ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$tmp = wp_tempnam( basename( $path ) );
	copy( $path, $tmp );
	$id = media_handle_sideload(
		array(
			'name'     => basename( $path ),
			'tmp_name' => $tmp,
		),
		$parent_id
	);
	if ( is_wp_error( $id ) ) {
		wp_delete_file( $tmp );
		return 0;
	}
	update_post_meta( $id, '_wp_attachment_image_alt', $alt );
	return (int) $id;
}
