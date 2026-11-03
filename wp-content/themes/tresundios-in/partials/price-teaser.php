<?php
/**
 * Prices for the current service (.in): starting price and every pricing entry linked to it.
 *
 * @package Tresundios\Theme\In
 *
 * @var array{best: array<string, mixed>, items: array<int, array<string, mixed>>}|null $data Prices.
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $data ) {
	return;
}
$best = $data['best'];
?>
<div class="price-teaser glass">
	<div>
		<span class="eyebrow"><?php esc_html_e( 'Pricing', 'tresundios' ); ?></span>
		<div class="price-amount"><?php echo esc_html( __( 'starting from', 'tresundios' ) . ' ' . $best['amount'] ); ?></div>
		<div class="price-billing"><?php echo esc_html( trim( $best['period'] . ' · ' . tresundios_get( 'tax_note' ), ' ·' ) ); ?></div>
	</div>
	<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( home_url( '/pricing/' ) ); ?>"><?php esc_html_e( 'See all prices →', 'tresundios' ); ?></a>
	<?php if ( count( $data['items'] ) > 1 ) : ?>
		<ul class="price-features price-list">
			<?php foreach ( $data['items'] as $item ) : ?>
				<li><span><?php echo esc_html( $item['name'] ); ?></span> <b><?php echo esc_html( $item['plain'] ); ?></b></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</div>
