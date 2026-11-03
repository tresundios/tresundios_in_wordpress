<?php
/**
 * Title: FAQ
 * Slug: tresundios/faq
 * Categories: tresundios
 * Description: Questions and answers using the core Details block (also output as FAQPage schema).
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","className":"block tr-sec faq-sec","layout":{"type":"default"}} -->
<section class="wp-block-group block tr-sec faq-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">FAQ</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Frequently asked questions</h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --><!-- wp:group {"className":"faq","layout":{"type":"default"}} -->
<div class="wp-block-group faq"><!-- wp:details -->
<details class="wp-block-details"><summary>Question</summary><!-- wp:paragraph {"placeholder":"Answer"} -->
<p>Answer.</p>
<!-- /wp:paragraph --></details>
<!-- /wp:details --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
