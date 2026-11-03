<?php
/**
 * Title: DentalPin India product page
 * Slug: tresundios/product-dentalpin
 * Categories: tresundios
 * Description: /products/dentalpin/ sales page: hero, audiences, features, free vs paid table, why Tresundios, FAQ and CTA band.
 * Inserter: no
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_dp  = function_exists( 'tresundios_dentalpin' ) ? tresundios_dentalpin() : array(
	'url'     => 'https://www.dentalpin.in/',
	'demo'    => 'https://www.dentalpin.in/demo.html',
	'pricing' => 'https://www.dentalpin.in/pricing.html',
	'partner' => 'https://www.dentalpin.in/contact.html#partners',
);
$tresundios_faq = function_exists( 'tresundios_dentalpin_faq' ) ? tresundios_dentalpin_faq() : array();
$tresundios_tax = esc_html__( 'excl. GST', 'tresundios' );

$tresundios_audiences = array(
	array( __( 'Solo dentist', 'tresundios' ), __( 'Appointments, patient records, GST invoices and reminders in one place, without a separate receptionist tool.', 'tresundios' ) ),
	array( __( 'Multi-chair clinic', 'tresundios' ), __( 'Several dentists and chairs on one schedule, with front-desk staff working in the language they prefer.', 'tresundios' ) ),
	array( __( 'Clinic chain', 'tresundios' ), __( 'Run more than one branch on the same software, self-hosted or on managed hosting in India.', 'tresundios' ) ),
);
$tresundios_features  = array(
	array( __( 'AI assistant', 'tresundios' ), __( 'Help with everyday tasks inside the software, so staff spend less time on admin.', 'tresundios' ) ),
	array( __( 'GST billing', 'tresundios' ), __( 'Raise GST-ready invoices for treatments directly from the patient record.', 'tresundios' ) ),
	array( __( 'WhatsApp reminders', 'tresundios' ), __( 'Appointment reminders go to patients on WhatsApp, the app they already use.', 'tresundios' ) ),
	array( __( '7 Indian languages', 'tresundios' ), __( 'Your team can use the interface in 7 Indian languages, not only English.', 'tresundios' ) ),
	array( __( 'Self-hosted or managed', 'tresundios' ), __( 'Run it on your own server, or let Tresundios host and maintain it in an India-region data centre.', 'tresundios' ) ),
	array( __( 'Open-source core', 'tresundios' ), __( 'The core is source-available under BSL 1.1, so you can see exactly what runs your clinic.', 'tresundios' ) ),
);
// Prices as published on https://www.dentalpin.in/pricing.html (per clinic location, excl. 18% GST).
// Tresundios services have no published price yet: they are quoted after a call.
$tresundios_quote = __( 'Quote after a free call', 'tresundios' );
$tresundios_paid  = array(
	array( __( 'Additional clinic branch', 'tresundios' ), __( 'DentalPin add-on', 'tresundios' ), __( '₹999 a month', 'tresundios' ), true ),
	array( __( 'WhatsApp reminders add-on', 'tresundios' ), __( 'DentalPin add-on', 'tresundios' ), __( '₹499 a month + Meta message charges at cost', 'tresundios' ), true ),
	array( __( 'AI assistant', 'tresundios' ), __( 'DentalPin add-on (or included in Pro)', 'tresundios' ), __( '₹699 add-on', 'tresundios' ), true ),
	array( __( 'Installation and setup', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
	array( __( 'Managed hosting (India region)', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
	array( __( 'Data migration', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
	array( __( 'Training', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
	array( __( 'Support plans', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
	array( __( 'Customisation', 'tresundios' ), __( 'Tresundios', 'tresundios' ), $tresundios_quote, false ),
);
?>
<!-- wp:group {"tagName":"section","className":"page-head tr-sec dp-hero","layout":{"type":"default"}} -->
<section class="wp-block-group page-head tr-sec dp-hero"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'DentalPin India — a Tresundios product', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'DentalPin India — dental clinic software made for Indian practices', 'tresundios' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"lede"} -->
<p class="lede"><?php esc_html_e( 'Appointments, GST billing, WhatsApp reminders and an AI assistant, in 7 Indian languages. The core software is free; we set it up, host it and support it for you.', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tresundios_dp['demo'] ); ?>"><?php esc_html_e( 'Try the live demo', 'tresundios' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="#book-a-call"><?php esc_html_e( 'Book a call', 'tresundios' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"dp-endorse"} -->
<p class="dp-endorse"><?php esc_html_e( 'Exclusive India licensee: Tresundios Software Products and Services Pvt. Ltd.', 'tresundios' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"who-its-for","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="who-its-for" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Who it’s for', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"dp-grid dp-grid-3","layout":{"type":"default"}} -->
<div class="wp-block-group dp-grid dp-grid-3">
<?php foreach ( $tresundios_audiences as $tresundios_item ) : ?>
<!-- wp:group {"className":"dp-card glass","layout":{"type":"default"}} -->
<div class="wp-block-group dp-card glass"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $tresundios_item[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $tresundios_item[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group -->

<!-- wp:html -->
<!-- TODO: add real testimonial -->
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"features","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="features" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Features', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"dp-grid dp-grid-3","layout":{"type":"default"}} -->
<div class="wp-block-group dp-grid dp-grid-3">
<?php foreach ( $tresundios_features as $tresundios_item ) : ?>
<!-- wp:group {"className":"dp-card dp-feature glass","layout":{"type":"default"}} -->
<div class="wp-block-group dp-card dp-feature glass"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $tresundios_item[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $tresundios_item[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"free-vs-paid","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="free-vs-paid" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What’s free and what Tresundios provides', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'The core software costs nothing. Add-ons are priced per clinic location and billed monthly; Tresundios services are quoted after a free call. All prices are in INR and exclude 18% GST.', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:table {"hasFixedLayout":true,"className":"dp-table"} -->
<figure class="wp-block-table dp-table"><table class="has-fixed-layout"><thead><tr><th><?php esc_html_e( 'What you get', 'tresundios' ); ?></th><th><?php esc_html_e( 'Provided by', 'tresundios' ); ?></th><th><?php esc_html_e( 'Price (INR)', 'tresundios' ); ?></th></tr></thead><tbody><tr><td><?php esc_html_e( 'DentalPin India core software', 'tresundios' ); ?></td><td><?php esc_html_e( 'Free (open source)', 'tresundios' ); ?></td><td><?php esc_html_e( '₹0 per chair or dentist', 'tresundios' ); ?></td></tr>
<?php foreach ( $tresundios_paid as $tresundios_item ) : ?><tr><td><?php echo esc_html( $tresundios_item[0] ); ?></td><td><?php echo esc_html( $tresundios_item[1] ); ?></td><td><?php echo esc_html( $tresundios_item[2] ); ?><?php echo $tresundios_item[3] ? ' ' . $tresundios_tax : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?></td></tr><?php endforeach; ?></tbody></table></figure>
<!-- /wp:table -->

<!-- wp:paragraph {"className":"dp-table-note"} -->
<p class="dp-table-note"><?php esc_html_e( 'Plans and add-ons can be switched on or off any month. See the full', 'tresundios' ); ?> <a href="<?php echo esc_url( $tresundios_dp['pricing'] ); ?>"><?php esc_html_e( 'DentalPin India pricing', 'tresundios' ); ?></a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"why-tresundios","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="why-tresundios" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Why Tresundios', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dp-why"} -->
<p class="dp-why"><?php esc_html_e( 'Tresundios is the exclusive India licensee of', 'tresundios' ); ?> <a href="<?php echo esc_url( $tresundios_dp['url'] ); ?>"><?php esc_html_e( 'DentalPin India dental clinic software', 'tresundios' ); ?></a>. <?php esc_html_e( 'Setup, hosting and support are done by our own engineers in Chennai, the same team behind our', 'tresundios' ); ?> <a href="<?php echo esc_url( home_url( '/services/websites-and-apps/' ) ); ?>"><?php esc_html_e( 'websites and apps', 'tresundios' ); ?></a> <?php esc_html_e( 'and', 'tresundios' ); ?> <a href="<?php echo esc_url( home_url( '/services/devops/' ) ); ?>"><?php esc_html_e( 'DevOps and cloud', 'tresundios' ); ?></a> <?php esc_html_e( 'services. Managed hosting runs in an India-region data centre, and you deal with one company for the software, the server and the support.', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<!-- TODO: add real testimonial -->
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"faq","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="faq" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Frequently asked questions', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"faq","layout":{"type":"default"}} -->
<div class="wp-block-group faq">
<?php foreach ( $tresundios_faq as $tresundios_qa ) : ?>
<!-- wp:details -->
<details class="wp-block-details"><summary><?php echo esc_html( $tresundios_qa['question'] ); ?></summary><!-- wp:paragraph -->
<p><?php echo esc_html( $tresundios_qa['answer'] ); ?></p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"block tight tr-sec cta-band","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec cta-band"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"cta-pane glass","layout":{"type":"default"}} -->
<div class="wp-block-group cta-pane glass"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'DentalPin India', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'See it running in your clinic', 'tresundios' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"text-muted-tr"} -->
<p class="text-muted-tr"><?php esc_html_e( 'Try the demo, or book a free call and we will walk you through setup, hosting and migration.', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"is-style-ghost has-arrow"} -->
<div class="wp-block-button is-style-ghost has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tresundios_dp['demo'] ); ?>"><?php esc_html_e( 'Try the live demo', 'tresundios' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="#book-a-call"><?php esc_html_e( 'Book a call', 'tresundios' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $tresundios_dp['partner'] ); ?>"><?php esc_html_e( 'Become a partner', 'tresundios' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
