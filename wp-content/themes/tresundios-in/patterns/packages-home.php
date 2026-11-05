<?php
/**
 * Title: Packages teaser (homepage)
 * Slug: tresundios/packages-home
 * Categories: tresundios
 * Description: The three packages in compact cards, linking to /packages/. Shown on the homepage in place of the pricing table.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"packages","className":"block tr-sec","layout":{"type":"default"}} -->
<section id="packages" class="wp-block-group block tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Packages</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Clear packages. Clear outcomes.</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>Three starting points for SMEs. All prices in INR, excluding GST. Not sure which fits? The free audit will tell you.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:html -->
<div class="price-grid pkg-grid">
<?php
foreach ( array_keys( tresundios_theme_packages() ) as $tresundios_k ) {
	tresundios_render_package_card( $tresundios_k, true );
}
?>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"is-style-ghost has-arrow"} -->
<div class="wp-block-button is-style-ghost has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/packages/' ) ); ?>">Compare packages in full </a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
