<?php
/**
 * Title: Packages (full)
 * Slug: tresundios/packages
 * Categories: tresundios
 * Description: Full Packages page body: all three packages with inclusions, notes, care plan and outcomes.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"packages","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section id="packages" class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:html -->
<div class="price-grid pkg-grid">
<?php
foreach ( array_keys( tresundios_theme_packages() ) as $tresundios_k ) {
	tresundios_render_package_card( $tresundios_k );
}
?>
</div>
<div class="note mt-4">
	<p class="mb-1"><strong>All prices are in INR and exclude GST.</strong> Google and Meta advertising budgets are always separate and paid directly by the client.</p>
	<p class="mb-0">Need something different? Individual services are listed on our <a href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>">detailed price list</a>, and you can see the results of past projects on <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>">our work</a>.</p>
</div>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
