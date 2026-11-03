<?php
/**
 * Title: Trust & compliance
 * Slug: tresundios/trust
 * Categories: tresundios
 * Description: DPIIT, MCA and GST registration details.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_s = function_exists( 'tresundios_settings' ) ? tresundios_settings() : array(
	'dpiit' => '',
	'cin'   => '',
	'gstin' => '',
	'pan'   => '',
);
?>
<!-- wp:group {"tagName":"section","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Trust &amp; compliance</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Registered, certified, accountable.</h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --><!-- wp:html -->
<div class="row g-4 row-cols-1 row-cols-md-3">
	<div class="col"><div class="cred glass"><span class="eyebrow">Startup India</span><h3>DPIIT recognised</h3><dl><dt>Certificate</dt><dd><?php echo esc_html( $tresundios_s['dpiit'] ); ?></dd><dt>Valid until</dt><dd>17 Nov 2034</dd></dl></div></div>
	<div class="col"><div class="cred glass"><span class="eyebrow">MCA</span><h3>Private limited company</h3><dl><dt>CIN</dt><dd><?php echo esc_html( $tresundios_s['cin'] ); ?></dd><dt>Incorporated</dt><dd>18 Nov 2024</dd></dl></div></div>
	<div class="col"><div class="cred glass"><span class="eyebrow">Tax</span><h3>GST registered</h3><dl><dt>GSTIN</dt><dd><?php echo esc_html( $tresundios_s['gstin'] ); ?></dd><dt>PAN</dt><dd><?php echo esc_html( $tresundios_s['pan'] ); ?></dd></dl></div></div>
</div>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
