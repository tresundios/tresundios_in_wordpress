<?php
/**
 * Tresundios India theme setup.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

define( 'TRESUNDIOS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

add_action(
	'after_setup_theme',
	static function () {
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		remove_theme_support( 'core-block-patterns' );
		add_editor_style( array( 'assets/css/base.css', 'assets/css/site.css', 'assets/css/products.css' ) );
		add_post_type_support( 'page', 'excerpt' );
	}
);

add_action(
	'init',
	static function () {
		register_block_pattern_category( 'tresundios', array( 'label' => __( 'Tresundios', 'tresundios' ) ) );
		register_block_style(
			'core/button',
			array(
				'name'  => 'ghost',
				'label' => __( 'Ghost', 'tresundios' ),
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'  => 'checks',
				'label' => __( 'Checks', 'tresundios' ),
			)
		);
	}
);

/**
 * Styles on every page; scripts only where they are used (all deferred, no jQuery).
 */
add_action(
	'wp_enqueue_scripts',
	static function () {
		$v = TRESUNDIOS_THEME_VERSION;
		// One minified stylesheet in production (npm run build); the two source files while developing.
		if ( 'local' !== wp_get_environment_type() && file_exists( get_theme_file_path( 'assets/css/site.min.css' ) ) ) {
			wp_enqueue_style( 'tresundios-site', get_theme_file_uri( 'assets/css/site.min.css' ), array(), $v . '.' . filemtime( get_theme_file_path( 'assets/css/site.min.css' ) ) );
		} else {
			wp_enqueue_style( 'tresundios-base', get_theme_file_uri( 'assets/css/base.css' ), array(), $v );
			wp_enqueue_style( 'tresundios-site', get_theme_file_uri( 'assets/css/site.css' ), array( 'tresundios-base' ), $v );
		}

		// Products styles: small, separate file so the prebuilt site.min.css does not need a rebuild.
		wp_enqueue_style( 'tresundios-products', get_theme_file_uri( 'assets/css/products.css' ), array( 'tresundios-site' ), $v . '.' . filemtime( get_theme_file_path( 'assets/css/products.css' ) ) );

		$defer = array(
			'strategy'  => 'defer',
			'in_footer' => true,
		);
		wp_enqueue_script( 'tresundios-site', get_theme_file_uri( 'assets/js/site.js' ), array(), $v, $defer );
		if ( is_front_page() || is_post_type_archive( 'case_study' ) ) {
			wp_enqueue_script( 'tresundios-work', get_theme_file_uri( 'assets/js/work.js' ), array(), $v, $defer );
		}
	}
);

/**
 * Preload only the two fonts used above the fold.
 */
add_action(
	'wp_head',
	static function () {
		foreach ( array( 'archivo-black', 'archivo' ) as $font ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
				esc_url( get_theme_file_uri( 'assets/fonts/' . $font . '.woff2' ) )
			);
		}
	},
	1
);

/**
 * Favicon from the theme when no Site Icon is set.
 */
add_action(
	'wp_head',
	static function () {
		if ( ! has_site_icon() ) {
			printf( '<link rel="icon" type="image/png" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/img/favicon.png' ) ) );
		}
		echo '<meta name="theme-color" content="#c74634">' . "\n";
	}
);

/**
 * Homepage: show the DentalPin India teaser right after the services section, for homepages built
 * before the teaser existed. Skipped when the page content already contains the teaser pattern.
 */
add_filter(
	'render_block_core/group',
	static function ( $html, $block ) {
		static $done = false;
		if ( $done || ! is_front_page() || 'services' !== ( $block['attrs']['anchor'] ?? '' ) ) {
			return $html;
		}
		$done = true;
		$page = get_post( (int) get_option( 'page_on_front' ) );
		if ( $page && false !== strpos( $page->post_content, 'tresundios/home-product-teaser' ) ) {
			return $html;
		}
		return $html . do_blocks( '<!-- wp:pattern {"slug":"tresundios/home-product-teaser"} /-->' );
	},
	10,
	2
);
