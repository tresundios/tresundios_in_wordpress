<?php
/**
 * Title: Service: Brand & Creative
 * Slug: tresundios/service-brand
 * Categories: tresundios
 * Description: Brand & Creative section: headline, description, deliverables, ideal client, audit CTA, links to Packages and Work. Copy lives in inc/content.php.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:html -->
<?php tresundios_render_service_section( 'brand-creative' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
