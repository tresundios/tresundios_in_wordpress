<?php
/**
 * Title: Book-a-call CTA
 * Slug: tresundios/cta-book-call
 * Categories: tresundios
 * Description: Red call-to-action card with the Google Calendar booking link.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","className":"block tight tr-sec cta-band","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec cta-band"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"cta-pane glass","layout":{"type":"default"}} -->
<div class="wp-block-group cta-pane glass"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Let's talk</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ready to grow your business?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-muted-tr"} -->
<p class="text-muted-tr">A free 30-minute call to understand your business and goals. We reply to every enquiry within 24 hours.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"is-style-ghost has-arrow"} -->
<div class="wp-block-button is-style-ghost has-arrow"><a class="wp-block-button__link wp-element-button" href="#book-a-call">Book a free call </a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Send an enquiry</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
