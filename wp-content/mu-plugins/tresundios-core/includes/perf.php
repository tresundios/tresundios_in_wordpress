<?php
/**
 * Front-end weight: remove emoji, oEmbed discovery and other head extras, drop jQuery Migrate,
 * load block CSS per block, and generate WebP for uploaded JPEG/PNG images.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		// Emoji scripts and styles.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
		remove_action( 'wp_footer', 'print_emoji_detection_script' );
		add_filter( 'emoji_svg_url', '__return_false' );

		// oEmbed discovery and host JS, RSD, WLW, generator, shortlink.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'wp_head', 'feed_links_extra', 3 );
	}
);

add_filter(
	'wp_resource_hints',
	static function ( $urls, $relation ) {
		// The emoji CDN prefetch is no longer needed.
		return 'dns-prefetch' === $relation ? array_filter(
			$urls,
			static function ( $u ) {
				return false === strpos( is_array( $u ) ? $u['href'] : $u, 's.w.org' );
			}
		) : $urls;
	},
	10,
	2
);

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_deregister_script( 'wp-embed' );
		wp_dequeue_style( 'classic-theme-styles' );
	},
	100
);

// jQuery Migrate is never needed (no theme or plugin code uses jQuery on the front end).
add_action(
	'wp_default_scripts',
	static function ( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
		}
	}
);

// Only the CSS of blocks actually on the page.
add_filter( 'should_load_separate_core_block_assets', '__return_true' );

// Uploaded JPEG and PNG images are served as WebP at every size (AVIF is skipped: no fallback for older Safari).
add_filter(
	'image_editor_output_format',
	static function ( $formats ) {
		$formats['image/jpeg'] = 'image/webp';
		$formats['image/png']  = 'image/webp';
		return $formats;
	}
);
add_filter(
	'wp_editor_set_quality',
	static function ( $quality, $mime ) {
		return 'image/webp' === $mime ? 72 : $quality;
	},
	10,
	2
);
