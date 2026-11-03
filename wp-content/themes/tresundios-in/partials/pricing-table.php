<?php
/**
 * Pricing table (.in): tabs per price group; à-la-carte shows each category as its own section.
 * Every panel is in the HTML; JS only switches tabs (all panels show when JS is off).
 *
 * @package Tresundios\Theme\In
 *
 * @var array<int, array<string, mixed>> $data Price tree from tresundios_price_tree().
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( empty( $data ) ) {
	return;
}
$tresundios_uid = wp_unique_id( 'pr' );

$tresundios_card = static function ( $item ) {
	$popular = '' !== $item['badge'];
	$pct     = null === $item['price'];
	$amount  = $pct ? $item['amount'] . ' ' . $item['period'] : ( $item['from'] ? __( 'starting from', 'tresundios' ) . ' ' : '' ) . $item['amount'];
	$billing = $pct ? __( 'custom quote', 'tresundios' ) : $item['period'];
	?>
	<article class="price-card glass spot<?php echo $popular ? ' is-popular' : ''; ?>" id="price-<?php echo esc_attr( $item['id'] ); ?>">
		<?php if ( $popular ) : ?>
			<span class="price-badge"><?php echo esc_html( $item['badge'] ); ?></span>
		<?php endif; ?>
		<h3><?php echo esc_html( $item['name'] ); ?></h3>
		<p class="price-description"><?php echo esc_html( $item['description'] ); ?></p>
		<div class="price-amount"><?php echo esc_html( $amount ); ?></div>
		<div class="price-billing"><?php echo esc_html( $billing ); ?></div>
		<?php if ( $item['plus'] ) : ?>
			<div class="price-recurring"><?php echo esc_html( $item['plus'] ); ?></div>
		<?php endif; ?>
		<?php if ( $item['note'] ) : ?>
			<p class="price-extra"><?php echo esc_html( $item['note'] ); ?></p>
		<?php endif; ?>
		<ul class="price-features">
			<?php foreach ( $item['features'] as $feature ) : ?>
				<li><?php echo esc_html( $feature ); ?></li>
			<?php endforeach; ?>
		</ul>
		<a class="btn-tr <?php echo $popular ? 'btn-tr-primary' : 'btn-tr-ghost'; ?> price-cta" href="<?php echo esc_url( tresundios_quote_url( $item ) ); ?>" data-plan="<?php echo esc_attr( $item['name'] . ' (' . $item['plain'] . ')' ); ?>" data-service="<?php echo esc_attr( $item['enquiry_service'] ); ?>"><?php esc_html_e( 'Get a Quote', 'tresundios' ); ?><span class="visually-hidden">: <?php echo esc_html( $item['name'] ); ?></span> →</a>
	</article>
	<?php
};
?>
<div class="tr-pricing">
	<noscript><style>.tr-pricing [role="tabpanel"][hidden]{display:block}.tr-pricing [role="tablist"]{display:none}</style></noscript>
	<?php if ( $data[0]['description'] ) : ?>
		<p class="pricing-intro"><?php echo esc_html( $data[0]['description'] ); ?></p>
	<?php endif; ?>
	<div class="pricing-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Pricing categories', 'tresundios' ); ?>">
		<?php foreach ( $data as $i => $g ) : ?>
			<button class="pricing-tab" id="<?php echo esc_attr( $tresundios_uid . '-tab-' . $g['slug'] ); ?>" type="button" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $tresundios_uid . '-panel-' . $g['slug'] ); ?>" tabindex="<?php echo 0 === $i ? '0' : '-1'; ?>"><?php echo esc_html( $g['name'] ); ?></button>
		<?php endforeach; ?>
	</div>
	<?php foreach ( $data as $i => $g ) : ?>
		<div id="<?php echo esc_attr( $tresundios_uid . '-panel-' . $g['slug'] ); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr( $tresundios_uid . '-tab-' . $g['slug'] ); ?>" tabindex="0"<?php echo 0 === $i ? '' : ' hidden'; ?>>
			<?php if ( $g['children'] ) : ?>
				<?php foreach ( $g['children'] as $c ) : ?>
					<section class="price-group" aria-labelledby="<?php echo esc_attr( $tresundios_uid . '-group-' . $c['slug'] ); ?>">
						<h3 id="<?php echo esc_attr( $tresundios_uid . '-group-' . $c['slug'] ); ?>"><?php echo esc_html( $c['name'] ); ?></h3>
						<?php if ( $c['description'] ) : ?>
							<p class="price-group-description"><?php echo esc_html( $c['description'] ); ?></p>
						<?php endif; ?>
						<div class="price-grid">
							<?php
							foreach ( $c['items'] as $item ) {
								$tresundios_card( $item );
							}
							?>
						</div>
					</section>
				<?php endforeach; ?>
			<?php else : ?>
				<?php if ( $g['description'] && 'events' === $g['slug'] ) : ?>
					<p class="price-group-description"><?php echo esc_html( $g['description'] ); ?></p>
				<?php endif; ?>
				<div class="price-grid">
					<?php
					foreach ( $g['items'] as $item ) {
						$tresundios_card( $item );
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	<div class="pricing-notes">
		<p><?php esc_html_e( 'Website, app and event prices are starting prices. Final quotation depends on scope, features, content, integrations and event requirements.', 'tresundios' ); ?></p>
		<p><?php esc_html_e( 'Ad spend, third-party tools, hosting, domains, print production and event vendor costs are billed separately at actual cost. GST is applicable as per Indian tax regulations.', 'tresundios' ); ?></p>
	</div>
</div>
