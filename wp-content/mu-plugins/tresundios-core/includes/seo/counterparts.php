<?php
/**
 * Counterpart pages on the other Tresundios site (.com ⇄ .in), matched by the `translation_key` field, never by slug.
 *
 * Keys: "page:home", "service:logo-brand-identity", "case:tef", ... on singular content; archives and
 * terms use "archive:{post_type}" and "term:{taxonomy}:{slug}" (same structure on both sites).
 *
 * The two sites are separate WordPress installs. Each publishes its key => URL map at
 * /wp-json/tresundios/v1/pairs; the other site fetches it over HTTP and caches it
 * (TRESUNDIOS_PAIRS_TTL, refreshed early with `wp tresundios pairs refresh`). The counterpart is
 * set with the TRESUNDIOS_COUNTERPART_URL and TRESUNDIOS_COUNTERPART_REGION constants.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

const TRESUNDIOS_PAIRS_TTL       = 6 * HOUR_IN_SECONDS;
const TRESUNDIOS_PAIRS_RETRY     = 10 * MINUTE_IN_SECONDS;
const TRESUNDIOS_PAIRS_TRANSIENT = 'tresundios_counterpart_pairs';
const TRESUNDIOS_PAIRS_LAST_GOOD = 'tresundios_counterpart_pairs_last';

/**
 * This site and its counterpart (when configured), keyed by home URL.
 *
 * @return array<string, array{region: string, hreflang: string, local: bool}>
 */
function tresundios_sites() {
	static $sites = null;
	if ( null !== $sites ) {
		return $sites;
	}
	$sites = array(
		home_url( '/' ) => array(
			'region'   => tresundios_get( 'region' ),
			'hreflang' => tresundios_get( 'hreflang' ),
			'local'    => true,
		),
	);
	$url   = tresundios_counterpart_url();
	if ( $url ) {
		$region        = defined( 'TRESUNDIOS_COUNTERPART_REGION' ) && TRESUNDIOS_COUNTERPART_REGION
			? TRESUNDIOS_COUNTERPART_REGION
			: ( tresundios_is_india() ? 'global' : 'india' );
		$defaults      = tresundios_region_defaults( $region );
		$sites[ $url ] = array(
			'region'   => $region,
			'hreflang' => $defaults['hreflang'],
			'local'    => false,
		);
	}
	return $sites;
}

/**
 * Home URL of the other site, with a trailing slash, or ''.
 *
 * @return string
 */
function tresundios_counterpart_url() {
	$url = defined( 'TRESUNDIOS_COUNTERPART_URL' ) ? (string) TRESUNDIOS_COUNTERPART_URL : '';
	return $url ? trailingslashit( $url ) : '';
}

/**
 * Pairing key for the current request, or '' when the page has no counterpart concept (search, 404...).
 *
 * @return string
 */
function tresundios_current_pair_key() {
	if ( is_front_page() ) {
		return 'page:home';
	}
	if ( is_home() ) {
		return 'page:blog';
	}
	if ( is_singular() ) {
		return (string) get_post_meta( get_queried_object_id(), 'translation_key', true );
	}
	if ( is_post_type_archive() ) {
		$type = get_query_var( 'post_type' );
		return 'archive:' . ( is_array( $type ) ? reset( $type ) : $type );
	}
	if ( is_tax() ) {
		$term = get_queried_object();
		return $term instanceof WP_Term ? 'term:' . $term->taxonomy . ':' . $term->slug : '';
	}
	return '';
}

/**
 * Pairing key for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function tresundios_post_pair_key( $post_id ) {
	if ( (int) get_option( 'page_on_front' ) === $post_id ) {
		return 'page:home';
	}
	return (string) get_post_meta( $post_id, 'translation_key', true );
}

/**
 * Every pairing key on this site with its published URL.
 *
 * @return array<string, string>
 */
function tresundios_local_pairs() {
	$cache_key = 'tr_pairs_' . (int) get_option( 'tresundios_pairs_ver', 1 );
	$cached    = get_transient( $cache_key );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$out = array( 'page:home' => home_url( '/' ) );

	$ids = get_posts(
		array(
			'post_type'      => 'any',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => 'translation_key', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'no_found_rows'  => true,
		)
	);
	foreach ( $ids as $id ) {
		$key = tresundios_post_pair_key( $id );
		if ( $key && ! isset( $out[ $key ] ) ) {
			$out[ $key ] = (string) get_permalink( $id );
		}
	}

	foreach ( get_post_types(
		array(
			'public'      => true,
			'has_archive' => true,
		)
	) as $type ) {
		$link = get_post_type_archive_link( $type );
		if ( $link ) {
			$out[ 'archive:' . $type ] = $link;
		}
	}

	foreach ( get_terms(
		array(
			'taxonomy'   => array( 'work_type', 'resource_type' ),
			'hide_empty' => true,
		)
	) as $term ) {
		$link = get_term_link( $term );
		if ( ! is_wp_error( $link ) ) {
			$out[ 'term:' . $term->taxonomy . ':' . $term->slug ] = $link;
		}
	}

	set_transient( $cache_key, $out, DAY_IN_SECONDS );
	return $out;
}

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'tresundios/v1',
			'/pairs',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'callback'            => static function () {
					return rest_ensure_response( tresundios_local_pairs() );
				},
			)
		);
	}
);

/**
 * The counterpart's key => URL map (cached; the last good copy is used while the other site is unreachable).
 *
 * @param bool $force Skip the cache.
 * @return array<string, string>
 */
function tresundios_remote_pairs( $force = false ) {
	$base = tresundios_counterpart_url();
	if ( ! $base ) {
		return array();
	}
	$cached = $force ? false : get_transient( TRESUNDIOS_PAIRS_TRANSIENT );
	if ( is_array( $cached ) ) {
		return $cached;
	}

	$response = wp_remote_get( $base . 'wp-json/tresundios/v1/pairs', array( 'timeout' => 5 ) );
	$data     = 200 === wp_remote_retrieve_response_code( $response )
		? json_decode( wp_remote_retrieve_body( $response ), true )
		: null;

	if ( is_array( $data ) ) {
		$pairs = array();
		foreach ( $data as $key => $url ) {
			if ( is_string( $key ) && is_string( $url ) && 0 === strpos( $url, 'http' ) ) {
				$pairs[ $key ] = $url;
			}
		}
		set_transient( TRESUNDIOS_PAIRS_TRANSIENT, $pairs, TRESUNDIOS_PAIRS_TTL );
		update_option( TRESUNDIOS_PAIRS_LAST_GOOD, $pairs, false );
		return $pairs;
	}

	$last = get_option( TRESUNDIOS_PAIRS_LAST_GOOD, array() );
	$last = is_array( $last ) ? $last : array();
	set_transient( TRESUNDIOS_PAIRS_TRANSIENT, $last, TRESUNDIOS_PAIRS_RETRY );
	return $last;
}

/**
 * Map of hreflang => URL for every site that has this key (current site included).
 *
 * @param string $key Pairing key.
 * @return array<string, string>
 */
function tresundios_alternates( $key ) {
	if ( '' === $key ) {
		return array();
	}
	$out = array();
	foreach ( tresundios_sites() as $site ) {
		$pairs = $site['local'] ? tresundios_local_pairs() : tresundios_remote_pairs();
		if ( ! empty( $pairs[ $key ] ) ) {
			$out[ $site['hreflang'] ] = $pairs[ $key ];
		}
	}
	return $out;
}

/**
 * Any content change may add or remove a counterpart: bump this site's cache version.
 */
function tresundios_bump_pairs_version() {
	update_option( 'tresundios_pairs_ver', (int) get_option( 'tresundios_pairs_ver', 1 ) + 1 );
}
add_action( 'save_post', 'tresundios_bump_pairs_version' );
add_action( 'deleted_post', 'tresundios_bump_pairs_version' );
add_action( 'edited_term', 'tresundios_bump_pairs_version' );
foreach ( array( 'added_post_meta', 'updated_post_meta', 'deleted_post_meta' ) as $tresundios_hook ) {
	add_action(
		$tresundios_hook,
		static function ( $meta_id, $post_id, $meta_key ) {
			if ( 'translation_key' === $meta_key ) {
				tresundios_bump_pairs_version();
			}
		},
		10,
		3
	);
}
