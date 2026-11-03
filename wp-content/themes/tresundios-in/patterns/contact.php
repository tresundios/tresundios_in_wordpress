<?php
/**
 * Title: Contact section
 * Slug: tresundios/contact
 * Categories: tresundios
 * Description: Red contact card with details and the enquiry form.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"contact","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section id="contact" class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"row g-4","layout":{"type":"default"}} -->
<div class="wp-block-group row g-4"><!-- wp:group {"className":"col-lg-5","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-5"><!-- wp:group {"className":"cta-pane glass","layout":{"type":"default"}} -->
<div class="wp-block-group cta-pane glass"><!-- wp:html -->
<img class="watermark" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/icon.webp' ) ); ?>" alt="" width="200" height="200" loading="lazy">
<!-- /wp:html -->

<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Let's talk</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ready to grow your business?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-muted-tr"} -->
<p class="text-muted-tr">Book a free consultation and get a custom quote. We reply within 24 hours.</p>
<!-- /wp:paragraph -->

<!-- wp:tresundios/contact-details {"variant":"lines"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"col-lg-7","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-7"><!-- wp:tresundios/enquiry-form /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
