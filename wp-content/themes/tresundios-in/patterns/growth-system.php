<?php
/**
 * Title: Growth system (websites that generate business)
 * Slug: tresundios/growth-system
 * Categories: tresundios
 * Description: Homepage technology & growth section: conversion, tracking and follow-up around the website.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_points = array(
	'Conversion-focused website design',
	'Landing pages for Google and Meta ads',
	'GA4 and conversion tracking setup',
	'WhatsApp, email and phone enquiry options',
	'Website speed and Core Web Vitals optimisation',
	'Conversion-rate optimisation',
	'E-commerce checkout optimisation',
	'Custom analytics dashboards',
);
?>
<!-- wp:group {"tagName":"section","anchor":"growth-system","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section id="growth-system" class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Technology &amp; growth</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Websites that generate business, not just traffic</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>We do not just build websites—we build the tracking, follow-up and conversion system around them.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:html -->
<ul class="growth-grid list-unstyled">
<?php foreach ( $tresundios_points as $tresundios_n => $tresundios_p ) : ?>
	<li class="glass"><span class="growth-n" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $tresundios_n + 1 ) ); ?></span><?php echo esc_html( $tresundios_p ); ?></li>
<?php endforeach; ?>
</ul>
<!-- /wp:html -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="#audit" data-track="cta_click" data-track-location="growth-system">Get a free website &amp; growth audit </a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
