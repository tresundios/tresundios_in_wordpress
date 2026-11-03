<?php
/**
 * Title: Products overview
 * Slug: tresundios/products-overview
 * Categories: tresundios
 * Description: /products/ page body: intro and one product card per product.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"products","className":"block tr-sec pt-0","layout":{"type":"default"}} -->
<section id="products" class="wp-block-group block tr-sec pt-0"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow"><?php esc_html_e( 'Our products', 'tresundios' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Software products built and run by Tresundios', 'tresundios' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Alongside our services, we sell and support software for specific industries in India. Each product comes with setup, hosting and support from our own team in Chennai.', 'tresundios' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"product-grid","layout":{"type":"default"}} -->
<div class="wp-block-group product-grid"><!-- wp:pattern {"slug":"tresundios/product-card"} /-->
<?php /* Next product (e.g. HealtheKit): duplicate patterns/product-card.php and add it here. */ ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
