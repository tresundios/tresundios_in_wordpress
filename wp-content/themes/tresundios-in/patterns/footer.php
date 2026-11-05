<?php
/**
 * Title: Footer
 * Slug: tresundios/footer
 * Categories: tresundios
 * Inserter: no
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

$tresundios_s = function_exists( 'tresundios_settings' ) ? tresundios_settings() : array();
$tresundios_u = static function ( $path ) {
	return esc_url( home_url( $path ) );
};
?>
<!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"foot glass","layout":{"type":"default"}} -->
<div class="wp-block-group foot glass"><!-- wp:group {"className":"row g-4","layout":{"type":"default"}} -->
<div class="wp-block-group row g-4"><!-- wp:html -->
	<div class="col-md-6 col-lg-4">
		<a href="<?php echo $tresundios_u( '/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" aria-label="<?php esc_attr_e( 'Tresundios home', 'tresundios' ); ?>"><img class="logo" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/logo-light.webp' ) ); ?>" alt="Tresundios" width="176" height="30" loading="lazy"></a>
		<p class="mt-3"><?php esc_html_e( 'Websites, e-commerce stores and digital growth systems that turn attention into enquiries, bookings and sales for Indian SMEs.', 'tresundios' ); ?></p>
		<p class="foot-product mt-3"><?php esc_html_e( 'Our products:', 'tresundios' ); ?> <a href="https://www.dentalpin.in/"><?php esc_html_e( 'DentalPin India', 'tresundios' ); ?></a></p>
	</div>
	<nav class="col-6 col-md-3 col-lg-2" aria-label="<?php esc_attr_e( 'Services', 'tresundios' ); ?>"><h2 class="foot-h"><?php esc_html_e( 'Services', 'tresundios' ); ?></h2><ul>
		<li><a href="<?php echo $tresundios_u( '/services/#websites-ecommerce' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Websites &amp; e-commerce', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/services/#performance-marketing' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Performance marketing', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/services/#brand-creative' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Brand &amp; creative', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/services/#social-content' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Social &amp; content growth', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/services/#apps-automation' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Apps &amp; automation', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/packages/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Packages', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/pricing/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Detailed price list', 'tresundios' ); ?></a></li>
	</ul></nav>
	<nav class="col-6 col-md-3 col-lg-2" aria-label="<?php esc_attr_e( 'Company', 'tresundios' ); ?>"><h2 class="foot-h"><?php esc_html_e( 'Company', 'tresundios' ); ?></h2><ul>
		<li><a href="<?php echo $tresundios_u( '/products/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Products', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/work/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Work', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/resources/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Resources', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/events/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Events (legacy)', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/faq/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'FAQ', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/process/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Process', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/about/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'About', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/blog/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Blog', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/contact/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Contact', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/privacy-policy/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Privacy', 'tresundios' ); ?></a></li>
		<li><a href="<?php echo $tresundios_u( '/terms/' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"><?php esc_html_e( 'Terms', 'tresundios' ); ?></a></li>
	</ul></nav>
<!-- /wp:html -->

<!-- wp:group {"className":"col-md-12 col-lg-4","layout":{"type":"default"}} -->
<div class="wp-block-group col-md-12 col-lg-4"><!-- wp:html --><h2 class="foot-h"><?php esc_html_e( 'Reach us', 'tresundios' ); ?></h2><!-- /wp:html -->

<!-- wp:tresundios/contact-details {"variant":"footer"} /-->

<!-- wp:html -->
<?php tresundios_lead_buttons( 'footer' ); ?>
<!-- /wp:html --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="legal"><span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php esc_html_e( 'Tresundios Software Products and Services Pvt. Ltd.', 'tresundios' ); ?></span><span>CIN <?php echo esc_html( isset( $tresundios_s['cin'] ) ? $tresundios_s['cin'] : '' ); ?> · DPIIT <?php echo esc_html( isset( $tresundios_s['dpiit'] ) ? $tresundios_s['dpiit'] : '' ); ?></span></div>
<!-- /wp:html --></div>
<!-- /wp:group -->
