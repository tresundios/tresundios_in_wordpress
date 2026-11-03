<?php
/**
 * Hreflang alternates between tresundios.com ("en", also x-default) and tresundios.in ("en-IN"),
 * in the <head> and in Rank Math's XML sitemaps. Only output when the page has a published counterpart.
 * Canonicals stay self-referencing (Rank Math); nothing here canonicalises one site to the other.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Alternates including x-default (the global site), or [] when there is no counterpart.
 *
 * @param string $key Pairing key.
 * @return array<string, string> hreflang => URL.
 */
function tresundios_hreflang_set( $key ) {
	$alts = tresundios_alternates( $key );
	if ( count( $alts ) < 2 ) {
		return array();
	}
	foreach ( tresundios_sites() as $site ) {
		if ( 'global' === $site['region'] && isset( $alts[ $site['hreflang'] ] ) ) {
			$alts['x-default'] = $alts[ $site['hreflang'] ];
		}
	}
	return $alts;
}

add_action(
	'wp_head',
	static function () {
		if ( is_paged() ) {
			return;
		}
		foreach ( tresundios_hreflang_set( tresundios_current_pair_key() ) as $lang => $url ) {
			printf( '<link rel="alternate" hreflang="%s" href="%s">' . "\n", esc_attr( $lang ), esc_url( $url ) );
		}
	},
	2
);

/**
 * Pairing key for a URL listed in the sitemap.
 *
 * @param string $loc URL.
 * @return string
 */
function tresundios_url_pair_key( $loc ) {
	if ( trailingslashit( $loc ) === trailingslashit( home_url( '/' ) ) ) {
		return 'page:home';
	}
	$post_id = url_to_postid( $loc );
	if ( ! $post_id ) {
		// url_to_postid() misses hierarchical custom post types such as /services/{parent}/{child}/.
		$path = trim( (string) wp_parse_url( $loc, PHP_URL_PATH ), '/' );
		foreach ( array( 'service', 'case_study', 'resource', 'event_portfolio' ) as $type ) {
			$obj  = get_post_type_object( $type );
			$base = $obj && ! empty( $obj->rewrite['slug'] ) ? $obj->rewrite['slug'] . '/' : '';
			if ( $base && 0 === strpos( $path . '/', $base ) && strlen( $path ) > strlen( $base ) - 1 ) {
				$post = get_page_by_path( substr( $path, strlen( $base ) ), OBJECT, $type );
				if ( $post ) {
					$post_id = $post->ID;
					break;
				}
			}
		}
	}
	if ( $post_id ) {
		return tresundios_post_pair_key( $post_id );
	}
	foreach ( array( 'service', 'case_study', 'resource', 'event_portfolio' ) as $type ) {
		if ( trailingslashit( $loc ) === trailingslashit( (string) get_post_type_archive_link( $type ) ) ) {
			return 'archive:' . $type;
		}
	}
	foreach ( array( 'work_type', 'resource_type' ) as $tax ) {
		foreach ( get_terms(
			array(
				'taxonomy'   => $tax,
				'hide_empty' => true,
			)
		) as $term ) {
			if ( trailingslashit( $loc ) === trailingslashit( (string) get_term_link( $term ) ) ) {
				return 'term:' . $tax . ':' . $term->slug;
			}
		}
	}
	return '';
}

add_filter(
	'rank_math/sitemap/url',
	static function ( $output, $url ) {
		$alts = empty( $url['loc'] ) ? array() : tresundios_hreflang_set( tresundios_url_pair_key( $url['loc'] ) );
		if ( ! $alts ) {
			return $output;
		}
		$links = '';
		foreach ( $alts as $lang => $href ) {
			$links .= "\t\t" . '<xhtml:link rel="alternate" hreflang="' . esc_attr( $lang ) . '" href="' . esc_url( $href ) . '" />' . "\n";
		}
		return str_replace( "\t</url>", $links . "\t</url>", $output );
	},
	10,
	2
);

// Declare the xhtml namespace on every sitemap that can carry alternates.
foreach ( array( 'post', 'page', 'service', 'case_study', 'resource', 'event_portfolio', 'work_type', 'resource_type' ) as $tresundios_type ) {
	add_filter(
		"rank_math/sitemap/{$tresundios_type}_urlset",
		static function ( $urlset ) {
			return false === strpos( $urlset, 'xmlns:xhtml' ) ? str_replace( '<urlset ', '<urlset xmlns:xhtml="http://www.w3.org/1999/xhtml" ', $urlset ) : $urlset;
		}
	);
}
