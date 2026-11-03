<?php
/**
 * Title: Homepage product teaser (DentalPin India)
 * Slug: tresundios/home-product-teaser
 * Categories: tresundios
 * Description: Compact band between the services and pricing sections: one line and two CTAs.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_dp = function_exists( 'tresundios_dentalpin' ) ? tresundios_dentalpin() : array( 'demo' => 'https://www.dentalpin.in/demo.html' );
?>
<!-- wp:group {"tagName":"section","anchor":"our-product","className":"tr-sec product-teaser","layout":{"type":"default"}} -->
<section id="our-product" class="wp-block-group tr-sec product-teaser"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"product-teaser-inner glass","layout":{"type":"default"}} -->
<div class="wp-block-group product-teaser-inner glass"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:heading {"className":"product-teaser-title"} -->
<h2 class="wp-block-heading product-teaser-title"><?php esc_html_e( 'Our product: DentalPin India', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Dental clinic software for Indian practices: GST billing, WhatsApp reminders and 7 Indian languages, with a free open-source core.', 'tresundios' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tresundios_dp['demo'] ); ?>"><?php esc_html_e( 'Try the live demo', 'tresundios' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/products/dentalpin/' ) ); ?>"><?php esc_html_e( 'Learn more', 'tresundios' ); ?><span class="screen-reader-text"> <?php esc_html_e( 'about DentalPin India', 'tresundios' ); ?></span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
