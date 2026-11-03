<?php
/**
 * Resources: hub section cards, or resource cards (.in).
 *
 * @package Tresundios\Theme\In
 *
 * @var array{sections: array<int, array<string, mixed>>, items: array<int, array<string, mixed>>} $data Data.
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( $data['sections'] ) :
	?>
	<div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
		<?php foreach ( $data['sections'] as $s ) : ?>
			<div class="col">
				<a class="hub-card glass spot" href="<?php echo esc_url( $s['url'] ); ?>">
					<span class="mini-title"><?php echo $s['count'] ? esc_html( sprintf( /* translators: %d: number of items. */ _n( '%d item', '%d items', $s['count'], 'tresundios' ), $s['count'] ) ) : esc_html__( 'Explore', 'tresundios' ); ?></span>
					<h3><?php echo esc_html( $s['title'] ); ?></h3>
					<p><?php echo esc_html( $s['text'] ); ?></p>
					<span class="more"><?php esc_html_e( 'Open', 'tresundios' ); ?> <i aria-hidden="true">→</i></span>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return;
endif;

if ( ! $data['items'] ) :
	?>
	<p class="todo-empty"><?php esc_html_e( 'New resources are on the way. Meanwhile, browse our case studies or book a free call.', 'tresundios' ); ?></p>
	<?php
	return;
endif;
?>
<div class="work-grid res-grid">
	<?php foreach ( $data['items'] as $r ) : ?>
		<a class="proj res-card glass spot" href="<?php echo esc_url( $r['url'] ); ?>">
			<div class="body">
				<div class="meta"><span class="cat"><?php echo esc_html( $r['type'] ); ?></span>
				<?php
				if ( $r['reading'] ) :
					?>
					<span><?php echo esc_html( $r['reading'] ); ?></span><?php endif; ?>
					<?php
					if ( $r['download'] ) :
						?>
					<span><?php esc_html_e( 'PDF', 'tresundios' ); ?></span><?php endif; ?></div>
				<h3><?php echo esc_html( $r['title'] ); ?></h3>
				<p><?php echo esc_html( $r['excerpt'] ); ?></p>
				<span class="more"><?php esc_html_e( 'Read', 'tresundios' ); ?> <i aria-hidden="true">→</i></span>
			</div>
		</a>
	<?php endforeach; ?>
</div>
