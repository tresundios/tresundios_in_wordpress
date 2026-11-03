<?php
/**
 * 301 redirects from the old static sites.
 *
 * - Old case-study pages (stored per case study in `legacy_url`, e.g. /case-studies/uptownindia-uk-tea-ecommerce) → /work/{slug}/.
 * - Old modal deep links (/#case-uptown): fragments never reach the server, so a tiny inline script on the
 *   home page sends them to the case-study page. Section anchors (/#services, /#pricing ...) still exist on
 *   the home page and keep working; they also have full pages now (/services/, /pricing/ ...).
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'template_redirect',
	static function () {
		if ( ! is_404() ) {
			return;
		}
		$path = isset( $_SERVER['REQUEST_URI'] ) ? untrailingslashit( (string) wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH ) ) : '';
		if ( '' === $path ) {
			return;
		}
		foreach ( get_posts(
			array(
				'post_type'      => 'case_study',
				'post_status'    => 'publish',
				'posts_per_page' => 100, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page -- bounded list.
				'fields'         => 'ids',
				'meta_key'       => 'legacy_url', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'no_found_rows'  => true,
			)
		) as $id ) {
			$old = untrailingslashit( (string) wp_parse_url( get_post_meta( $id, 'legacy_url', true ), PHP_URL_PATH ) );
			if ( $old && $old === $path ) {
				wp_safe_redirect( get_permalink( $id ), 301, 'Tresundios' );
				exit;
			}
		}
	},
	1
);

add_action(
	'wp_footer',
	static function () {
		if ( ! is_front_page() ) {
			return;
		}
		$map = array();
		foreach ( tresundios_case_studies() as $c ) {
			// Old modal ids were the case-study ids, kept in translation_key ("case:uptown" → #case-uptown).
			$key = substr( (string) get_post_meta( $c['id'], 'translation_key', true ), 5 );
			if ( $key ) {
				$map[ $key ] = $c['url'];
			}
		}
		wp_print_inline_script_tag( 'var m=/^#case-([a-z]+)$/.exec(location.hash),u=' . wp_json_encode( $map ) . ';if(m&&u[m[1]])location.replace(u[m[1]]);' );
	}
);
