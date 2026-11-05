<?php
/**
 * Tresundios India theme setup.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

define( 'TRESUNDIOS_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

require_once __DIR__ . '/inc/content.php';

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
 * WhatsApp / email / phone links built from the site settings (Settings → Tresundios), so the
 * numbers live in one place. Used by the lead-buttons, audit-cta and package patterns.
 *
 * @param string $context Short label added to the WhatsApp message and data-track-location (e.g. "hero").
 * @return array<string, array<string, string>>
 */
function tresundios_lead_links( $context = 'site' ) {
	$s     = function_exists( 'tresundios_settings' ) ? tresundios_settings() : array();
	$wa    = preg_replace( '/\D/', '', isset( $s['whatsapp'] ) ? $s['whatsapp'] : '' );
	$tel   = preg_replace( '/[^\d+]/', '', isset( $s['phone'] ) ? $s['phone'] : '' );
	$email = isset( $s['contact_email'] ) ? $s['contact_email'] : '';
	$msg   = rawurlencode( __( 'Hi Tresundios, I would like a free website & growth audit.', 'tresundios' ) );
	return array(
		'whatsapp' => array(
			'label' => __( 'WhatsApp us', 'tresundios' ),
			'url'   => $wa ? 'https://wa.me/' . $wa . '?text=' . $msg : '',
		),
		'email'    => array(
			'label' => __( 'Email us', 'tresundios' ),
			'url'   => $email ? 'mailto:' . $email . '?subject=' . rawurlencode( __( 'Free website & growth audit', 'tresundios' ) ) : '',
		),
		'phone'    => array(
			'label' => __( 'Call us', 'tresundios' ),
			'url'   => $tel ? 'tel:' . $tel : '',
		),
	);
}

/**
 * Echo the WhatsApp / email / phone button row. Each link carries data-track="lead_click",
 * data-channel and data-track-location for GA4 (see docs/ANALYTICS.md). No tracking code here.
 *
 * @param string $context Where the row sits (hero, audit, package-launch …).
 */
function tresundios_lead_buttons( $context = 'site' ) {
	echo '<div class="lead-btns" role="group" aria-label="' . esc_attr__( 'Contact options', 'tresundios' ) . '">';
	foreach ( tresundios_lead_links( $context ) as $channel => $l ) {
		if ( ! $l['url'] ) {
			continue;
		}
		$ext = 'whatsapp' === $channel ? ' target="_blank" rel="noopener"' : '';
		printf(
			'<a class="btn-tr btn-tr-ghost lead-btn lead-%1$s" href="%2$s"%3$s data-track="lead_click" data-channel="%1$s" data-track-location="%4$s">%5$s%6$s</a>',
			esc_attr( $channel ),
			esc_url( $l['url'], array( 'https', 'mailto', 'tel' ) ),
			$ext, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static.
			esc_attr( $context ),
			esc_html( $l['label'] ),
			$ext ? '<span class="visually-hidden"> ' . esc_html__( '(opens WhatsApp)', 'tresundios' ) . '</span>' : ''
		);
	}
	echo '</div>';
}

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
 * Homepage (2026 restructure) without editing the stored page: the front page only holds
 * pattern references, so on the front page only we
 *  - drop the DentalPin teaser (DentalPin stays reachable from the footer),
 *  - show the Packages teaser instead of the full pricing table (/pricing/ keeps the table).
 */
add_filter(
	'render_block_core/pattern',
	static function ( $html, $block ) {
		if ( ! is_front_page() ) {
			return $html;
		}
		$slug = $block['attrs']['slug'] ?? '';
		if ( 'tresundios/home-product-teaser' === $slug && ! apply_filters( 'tresundios_home_product_teaser', false ) ) {
			return '';
		}
		if ( 'tresundios/pricing' === $slug ) {
			return do_blocks( '<!-- wp:pattern {"slug":"tresundios/packages-home"} /-->' );
		}
		return $html;
	},
	10,
	2
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
		// 2026 restructure: DentalPin is reachable from the footer only; no homepage teaser.
		if ( ! apply_filters( 'tresundios_home_product_teaser', false ) ) {
			return $html;
		}
		$page = get_post( (int) get_option( 'page_on_front' ) );
		if ( $page && false !== strpos( $page->post_content, 'tresundios/home-product-teaser' ) ) {
			return $html;
		}
		return $html . do_blocks( '<!-- wp:pattern {"slug":"tresundios/home-product-teaser"} /-->' );
	},
	10,
	2
);

/**
 * Create the /packages/ page once (approved 2026 restructure), so the Packages menu link never 404s.
 * Runs on the first request after the theme update; skipped if a "packages" page already exists
 * (any status) or it has been created before. Content is two pattern references, editable in the editor.
 */
add_action(
	'init',
	static function () {
		if ( get_option( 'tresundios_packages_page_created' ) || wp_installing() ) {
			return;
		}
		$existing = get_page_by_path( 'packages', OBJECT, 'page' );
		if ( ! $existing ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'page',
					'post_status'  => 'publish',
					'post_name'    => 'packages',
					'post_title'   => 'Packages',
					'post_excerpt' => 'Three clear packages for Indian SMEs: Launch, Lead Growth and E-commerce Growth. Prices in INR, excluding GST.',
					'post_content' => "<!-- wp:pattern {\"slug\":\"tresundios/packages\"} /-->\n\n<!-- wp:pattern {\"slug\":\"tresundios/audit-cta\"} /-->",
					'menu_order'   => 2,
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				return;
			}
		}
		update_option( 'tresundios_packages_page_created', 1, false );
	},
	20
);
