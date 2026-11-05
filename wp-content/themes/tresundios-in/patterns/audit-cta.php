<?php
/**
 * Title: Free Website & Growth Audit
 * Slug: tresundios/audit-cta
 * Categories: tresundios
 * Description: Reusable audit section: what the audit covers, WhatsApp / email / phone buttons, contact details and the existing enquiry form.
 *
 * Used by: homepage (via tresundios/contact), /contact/, and every template that includes the
 * cta-book-call part. Only one copy per page (it carries id="audit" and the enquiry form).
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"audit","className":"block tight tr-sec audit-band","layout":{"type":"default"}} -->
<section id="audit" class="wp-block-group block tight tr-sec audit-band"><!-- wp:html -->
<span id="contact" class="anchor-alias" aria-hidden="true"></span>
<!-- /wp:html -->

<!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"row g-4","layout":{"type":"default"}} -->
<div class="wp-block-group row g-4"><!-- wp:group {"className":"col-lg-5","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-5"><!-- wp:group {"className":"cta-pane glass","layout":{"type":"default"}} -->
<div class="wp-block-group cta-pane glass"><!-- wp:html -->
<img class="watermark" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/icon.webp' ) ); ?>" alt="" width="200" height="200" loading="lazy">
<!-- /wp:html -->

<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Free, no obligation</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Get a free website &amp; growth audit</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-muted-tr"} -->
<p class="text-muted-tr">Share your website, store or social page. Within 24 hours we send you a short, practical review of:</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"is-style-checks audit-list"} -->
<ul class="wp-block-list is-style-checks audit-list"><!-- wp:list-item -->
<li>Mobile experience, speed and Core Web Vitals</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>How easy it is to enquire, call, WhatsApp or buy</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>GA4 and conversion tracking: what is and is not measured</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Google Business Profile and local search basics</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Three priority fixes, and which package (if any) fits</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:html -->
<?php tresundios_lead_buttons( 'audit' ); ?>
<!-- /wp:html -->

<!-- wp:tresundios/contact-details {"variant":"lines"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"col-lg-7","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-7"><!-- wp:tresundios/enquiry-form /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
