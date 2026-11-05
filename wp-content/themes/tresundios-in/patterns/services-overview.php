<?php
/**
 * Title: Services overview (all five)
 * Slug: tresundios/services-overview
 * Categories: tresundios
 * Description: The five integrated services as separate sections, for the /services/ page.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","className":"block tight tr-sec pt-0","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec pt-0"><!-- wp:group {"className":"container svc-stack","layout":{"type":"default"}} -->
<div class="wp-block-group container svc-stack"><!-- wp:html -->
<nav class="svc-jump" aria-label="<?php esc_attr_e( 'Services on this page', 'tresundios' ); ?>"><ul class="d-flex flex-wrap gap-2 list-unstyled">
<?php foreach ( tresundios_theme_services() as $tresundios_k => $tresundios_s ) : ?>
<li><a class="chip" href="#<?php echo esc_attr( $tresundios_k ); ?>"><?php echo esc_html( $tresundios_s['title'] ); ?></a></li>
<?php endforeach; ?>
</ul></nav>
<?php
foreach ( array_keys( tresundios_theme_services() ) as $tresundios_k ) {
	tresundios_render_service_section( $tresundios_k );
}
?>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
