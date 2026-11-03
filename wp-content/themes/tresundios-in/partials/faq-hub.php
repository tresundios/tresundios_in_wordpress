<?php
/**
 * FAQ hub (.in): every FAQ grouped by its page, with a link back. No FAQPage schema here (it stays on the source pages).
 *
 * @package Tresundios\Theme\In
 *
 * @var array<int, array<string, mixed>> $data FAQ groups.
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $data ) {
	return;
}
?>
<nav class="faq-index" aria-label="<?php esc_attr_e( 'FAQ topics', 'tresundios' ); ?>">
	<ul class="d-flex flex-wrap gap-2 list-unstyled">
		<?php foreach ( $data as $i => $g ) : ?>
			<li><a class="chip" href="#faq-<?php echo (int) $i; ?>"><?php echo esc_html( $g['title'] ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php foreach ( $data as $i => $g ) : ?>
	<section class="faq-group" id="faq-<?php echo (int) $i; ?>" aria-labelledby="faq-h-<?php echo (int) $i; ?>">
		<h2 id="faq-h-<?php echo (int) $i; ?>"><a href="<?php echo esc_url( $g['url'] ); ?>"><?php echo esc_html( $g['title'] ); ?></a></h2>
		<div class="faq">
			<?php foreach ( $g['items'] as $qa ) : ?>
				<details class="wp-block-details"><summary><?php echo esc_html( $qa['question'] ); ?></summary><p><?php echo wp_kses_post( $qa['answer'] ); ?></p></details>
			<?php endforeach; ?>
		</div>
	</section>
<?php endforeach; ?>
