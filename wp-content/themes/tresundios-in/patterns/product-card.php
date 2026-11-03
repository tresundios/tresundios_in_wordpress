<?php
/**
 * Title: Product card (DentalPin India)
 * Slug: tresundios/product-card
 * Categories: tresundios
 * Description: Product card: logo, one-line description, three benefits, demo button and learn-more link. Duplicate it for new products (HealtheKit, ...).
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_dp = function_exists( 'tresundios_dentalpin' ) ? tresundios_dentalpin() : array( 'demo' => 'https://www.dentalpin.in/demo.html' );
?>
<!-- wp:group {"tagName":"article","className":"product-card glass","layout":{"type":"default"}} -->
<article class="wp-block-group product-card glass"><!-- wp:group {"className":"product-logo","layout":{"type":"default"}} -->
<div class="wp-block-group product-logo"><!-- wp:paragraph {"className":"product-logo-text"} -->
<p class="product-logo-text"><?php esc_html_e( 'DentalPin', 'tresundios' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php /* TODO: replace the text logo with the DentalPin India logo (core/image, alt="DentalPin India"). */ ?>

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'DentalPin India', 'tresundios' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-muted-tr"} -->
<p class="text-muted-tr"><?php esc_html_e( 'Dental practice software for Indian clinics, from solo practices to clinic chains.', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-checks"} -->
<ul class="wp-block-list is-style-checks"><!-- wp:list-item -->
<li><?php esc_html_e( 'GST billing and WhatsApp appointment reminders built in', 'tresundios' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Interface in 7 Indian languages, with an AI assistant', 'tresundios' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php esc_html_e( 'Open-source core at ₹0 per chair or dentist', 'tresundios' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tresundios_dp['demo'] ); ?>"><?php esc_html_e( 'Try the live demo', 'tresundios' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/products/dentalpin/' ) ); ?>"><?php esc_html_e( 'Learn more', 'tresundios' ); ?><span class="screen-reader-text"> <?php esc_html_e( 'about DentalPin India', 'tresundios' ); ?></span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></article>
<!-- /wp:group -->
