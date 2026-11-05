<?php
/**
 * Title: Service grid
 * Slug: tresundios/services
 * Categories: tresundios
 * Description: Black band with the five integrated service cards, followed by the growth-system section.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","anchor":"services","className":"block tr-sec","layout":{"type":"default"}} -->
<section id="services" class="wp-block-group block tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">What we do</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Five services. One growth system.</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph -->
<p>We build websites, e-commerce stores and digital growth systems that turn attention into enquiries, bookings and sales.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><!-- wp:html -->
<div class="row g-4 row-cols-1 row-cols-md-2 row-cols-xl-3">
<?php
$tresundios_i = 0;
foreach ( tresundios_theme_services() as $tresundios_k => $tresundios_s ) :
	?>
	<div class="col">
		<article class="svc <?php echo in_array( $tresundios_i % 4, array( 0, 3 ), true ) ? 'red' : 'dark'; ?> glass spot">
			<div class="d-flex justify-content-between align-items-start gap-3">
				<div class="ic"><?php echo function_exists( 'tresundios_icon' ) ? tresundios_icon( $tresundios_s['icon'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></div>
				<span class="tag"><?php echo esc_html( $tresundios_s['tag'] ); ?></span>
			</div>
			<h3><a class="svc-link" href="<?php echo esc_url( home_url( '/services/#' . $tresundios_k ) ); ?>"><?php echo esc_html( $tresundios_s['title'] ); ?></a></h3>
			<p class="text-muted-tr"><?php echo esc_html( $tresundios_s['headline'] ); ?></p>
			<ul>
				<?php foreach ( array_slice( $tresundios_s['items'], 0, 4 ) as $tresundios_item ) : ?>
					<li><?php echo esc_html( $tresundios_item ); ?></li>
				<?php endforeach; ?>
			</ul>
		</article>
	</div>
	<?php
	++$tresundios_i;
endforeach;
?>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"className":"mt-4"} -->
<div class="wp-block-buttons mt-4"><!-- wp:button {"className":"is-style-ghost has-arrow"} -->
<div class="wp-block-button is-style-ghost has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/services/' ) ); ?>">Explore all services </a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/packages/' ) ); ?>">View packages</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"tresundios/growth-system"} /-->
