<?php
/**
 * Robots.txt (search and AI crawlers allowed) and a dynamic /llms.txt per site.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Crawlers explicitly allowed (search engines and answer engines).
 *
 * @return array<int, string>
 */
function tresundios_allowed_bots() {
	return array( 'Googlebot', 'Bingbot', 'GPTBot', 'OAI-SearchBot', 'ChatGPT-User', 'ClaudeBot', 'Claude-User', 'PerplexityBot', 'Google-Extended', 'Applebot-Extended' );
}

add_filter(
	'robots_txt',
	static function ( $output, $is_public ) {
		if ( ! $is_public ) {
			return "User-agent: *\nDisallow: /\n";
		}
		$lines = array();
		foreach ( tresundios_allowed_bots() as $bot ) {
			$lines[] = 'User-agent: ' . $bot;
		}
		$lines[] = 'Allow: /';
		$lines[] = 'Disallow: /wp-admin/';
		$lines[] = 'Allow: /wp-admin/admin-ajax.php';
		$lines[] = '';
		$lines[] = 'User-agent: *';
		$lines[] = 'Allow: /';
		$lines[] = 'Disallow: /wp-admin/';
		$lines[] = 'Allow: /wp-admin/admin-ajax.php';
		$lines[] = 'Disallow: /?s=';
		$lines[] = '';
		$lines[] = 'Sitemap: ' . home_url( '/sitemap_index.xml' );
		$lines[] = '# AI-readable site summary: ' . home_url( '/llms.txt' );
		return implode( "\n", $lines ) . "\n";
	},
	100,
	2
);

add_action(
	'init',
	static function () {
		add_rewrite_rule( '^llms\.txt$', 'index.php?tresundios_llms=1', 'top' );
	}
);
add_filter(
	'query_vars',
	static function ( $vars ) {
		$vars[] = 'tresundios_llms';
		return $vars;
	}
);
// Never add a trailing slash to /llms.txt.
add_filter(
	'redirect_canonical',
	static function ( $redirect ) {
		return get_query_var( 'tresundios_llms' ) ? false : $redirect;
	}
);

/**
 * One "- [Title](url): summary" line.
 *
 * @param string $title Title.
 * @param string $url   URL.
 * @param string $text  Summary.
 * @return string
 */
function tresundios_llms_line( $title, $url, $text = '' ) {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) ) );
	return '- [' . html_entity_decode( $title, ENT_QUOTES, 'UTF-8' ) . '](' . $url . ')' . ( $text ? ': ' . wp_trim_words( $text, 30, '…' ) : '' );
}

/**
 * Build llms.txt (https://llmstxt.org) from live content.
 *
 * @return string
 */
function tresundios_llms_txt() {
	$s     = tresundios_settings();
	$india = tresundios_is_india();
	$out   = array();
	$out[] = '# ' . $s['brand_name'] . ( $india ? ' India' : '' );
	$out[] = '';
	$out[] = '> ' . $s['legal_name'] . ( $india
		? ' is a DPIIT-recognised digital studio in Chennai, India. It builds websites, e-commerce stores and digital growth systems for Indian SMEs: Websites & E-commerce, Performance Marketing, Brand & Creative, Social & Content Growth, and Apps & Digital Automation. '
		: ' is a DPIIT-recognised startup in Chennai, India, offering digital marketing, graphic design, event management, websites and apps, product photography and DevOps for small and medium businesses. ' )
		. ( $india ? 'This site serves India; prices are in INR excluding GST.' : 'This site serves international clients; prices are in USD.' );
	$out[] = '';
	$out[] = sprintf( 'Contact: %s · %s · %s, %s %s, %s, India. CIN %s, GSTIN %s.', $s['contact_email'], $s['phone'], $s['street_address'], $s['locality'], $s['postal_code'], $s['region_name'], $s['cin'], $s['gstin'] );
	foreach ( tresundios_sites() as $url => $site ) {
		if ( ! $site['local'] ) {
			$out[] = sprintf( 'Other region: %s (%s).', $url, 'india' === $site['region'] ? 'India, INR' : 'international, USD' );
		}
	}

	$out[] = '';
	$out[] = '## Services';
	foreach ( get_posts(
		array(
			'post_type'      => 'service',
			'posts_per_page' => 50,
			'orderby'        => array(
				'post_parent' => 'ASC',
				'menu_order'  => 'ASC',
			),
		)
	) as $p ) {
		$h1    = get_post_meta( $p->ID, 'h1', true );
		$out[] = tresundios_llms_line( $h1 ? $h1 : $p->post_title, get_permalink( $p ), $p->post_excerpt ? $p->post_excerpt : get_post_meta( $p->ID, 'h1', true ) );
	}

	$out[] = '';
	$out[] = '## Pricing';
	$out[] = tresundios_llms_line( 'Pricing', home_url( '/pricing/' ), sprintf( 'All prices in %s%s: à-la-carte services, marketing packages, launch packages, events and DevOps.', $s['currency'], $s['tax_note'] ? ' ' . $s['tax_note'] : '' ) );
	foreach ( tresundios_price_items( 'marketing-packages' ) as $item ) {
		$out[] = '- ' . $item['name'] . ' marketing package: ' . $item['plain'];
	}
	foreach ( tresundios_price_items( 'launch-packages' ) as $item ) {
		$out[] = '- ' . $item['name'] . ': ' . $item['plain'];
	}

	$out[] = '';
	$out[] = '## Work';
	foreach ( tresundios_case_studies() as $c ) {
		$out[] = tresundios_llms_line( $c['title'], $c['url'], $c['excerpt'] );
	}

	$resources = tresundios_resources();
	if ( $resources ) {
		$out[] = '';
		$out[] = '## Resources';
		foreach ( $resources as $r ) {
			$out[] = tresundios_llms_line( $r['title'], $r['url'], $r['excerpt'] );
		}
	}

	$out[] = '';
	$out[] = '## Company';
	foreach ( array( 'about', 'process', 'faq', 'glossary', 'contact' ) as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p && 'publish' === $p->post_status ) {
			$out[] = tresundios_llms_line( $p->post_title, get_permalink( $p ), $p->post_excerpt );
		}
	}
	$out[] = tresundios_llms_line( 'Book a free call', $s['booking_url'], 'Free 30-minute consultation via Google Calendar.' );

	return implode( "\n", $out ) . "\n";
}

add_action(
	'template_redirect',
	static function () {
		if ( ! get_query_var( 'tresundios_llms' ) ) {
			return;
		}
		status_header( 200 );
		header( 'Content-Type: text/plain; charset=utf-8' );
		header( 'X-Robots-Tag: noindex' );
		echo tresundios_llms_txt(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- plain text.
		exit;
	}
);
