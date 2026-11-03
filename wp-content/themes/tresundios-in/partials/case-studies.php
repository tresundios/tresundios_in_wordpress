<?php
/**
 * Case-study cards (.in). Layouts: "reel" (home: heading, filters, featured study + grid) and "grid" (archive / related).
 *
 * @package Tresundios\Theme\In
 *
 * @var array<string, mixed>             $attributes Block attributes.
 * @var array<int, array<string, mixed>> $data       Case studies.
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( empty( $data ) ) {
	return;
}

$tresundios_img = static function ( $c, $sizes ) {
	if ( ! $c['thumb_id'] ) {
		return '';
	}
	return wp_get_attachment_image(
		$c['thumb_id'],
		'large',
		false,
		array(
			'loading'  => 'lazy',
			'decoding' => 'async',
			'sizes'    => $sizes,
			'style'    => 'object-position:' . ( $c['focal'] ? $c['focal'] : '50% 50%' ),
		)
	);
};

$tresundios_filters = static function () {
	$types = get_terms(
		array(
			'taxonomy'   => 'work_type',
			'hide_empty' => true,
		)
	);
	$now   = is_tax( 'work_type' ) ? get_queried_object_id() : 0;
	?>
	<nav class="filters glass" aria-label="<?php esc_attr_e( 'Filter projects', 'tresundios' ); ?>" data-work-filters>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ); ?>" data-filter="all"<?php echo $now ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'All', 'tresundios' ); ?></a>
		<?php foreach ( is_wp_error( $types ) ? array() : $types as $t ) : ?>
			<a href="<?php echo esc_url( get_term_link( $t ) ); ?>" data-filter="<?php echo esc_attr( $t->slug ); ?>"<?php echo $now === $t->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $t->name ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php
};

$tresundios_grid = static function ( $items ) use ( $tresundios_img ) {
	$n = count( $items );
	?>
	<div class="row g-4" data-work-grid>
		<?php
		foreach ( $items as $i => $c ) :
			// Layout from the original: three per row, then two wide cards.
			$cls = $i < 3 || 1 === $n ? 'col-md-6 col-lg-4' : ( ( $i === $n - 1 && 0 === ( $n - 3 ) % 2 ) ? 'col-md-12 col-lg-6' : 'col-md-6 col-lg-6' );
			?>
			<div class="<?php echo esc_attr( $cls ); ?>" data-types="<?php echo esc_attr( implode( ' ', $c['type_slugs'] ) ); ?>">
				<a class="proj glass spot" href="<?php echo esc_url( $c['url'] ); ?>">
					<div class="thumb"><?php echo $tresundios_img( $c, '(min-width: 992px) 33vw, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php
					if ( $c['tag'] ) :
						?>
						<span class="mono-big"><?php echo esc_html( $c['tag'] ); ?></span><?php endif; ?></div>
					<div class="body"><div class="meta"><span class="cat"><?php echo esc_html( $c['card_cat'] ); ?></span>
					<?php
					if ( $c['duration'] ) :
						?>
						<span><?php echo esc_html( $c['duration'] ); ?></span><?php endif; ?></div>
						<h3><?php echo esc_html( $c['card_title'] ); ?></h3>
						<p><?php echo esc_html( $c['excerpt'] ); ?></p><span class="more"><?php esc_html_e( 'View case study →', 'tresundios' ); ?></span></div>
				</a>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
};

if ( 'reel' !== $attributes['layout'] ) {
	if ( $attributes['filters'] ) {
		echo '<div class="mb-4">';
		$tresundios_filters();
		echo '</div>';
	}
	$tresundios_grid( $data );
	return;
}

// Home: featured study, then the rest.
$tresundios_featured = $data[0];
foreach ( $data as $c ) {
	if ( $c['featured'] ) {
		$tresundios_featured = $c;
		break;
	}
}
$tresundios_rest = array_values(
	array_filter(
		$data,
		static function ( $c ) use ( $tresundios_featured ) {
			return $c['id'] !== $tresundios_featured['id'];
		}
	)
);
$tresundios_f    = $tresundios_featured;
?>
<div class="sec-head">
	<div><span class="eyebrow"><?php esc_html_e( 'Case studies · client work so far', 'tresundios' ); ?></span><h2><?php esc_html_e( 'Real businesses. Shipped results.', 'tresundios' ); ?></h2></div>
	<?php $tresundios_filters(); ?>
</div>

<article class="feature glass spot mb-4" data-types="<?php echo esc_attr( implode( ' ', $tresundios_f['type_slugs'] ) ); ?>">
	<div class="row g-4 g-lg-5 align-items-center">
		<div class="col-lg-6"><div class="thumb"><?php echo $tresundios_img( $tresundios_f, '(min-width: 992px) 50vw, 100vw' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php
		if ( $tresundios_f['tag'] ) :
			?>
			<span class="mono-big"><?php echo esc_html( $tresundios_f['tag'] ); ?></span><?php endif; ?></div></div>
		<div class="col-lg-6">
			<div class="meta"><span class="cat"><?php echo esc_html( sprintf( /* translators: %s: category. */ __( 'Featured · %s', 'tresundios' ), $tresundios_f['card_cat'] ) ); ?></span><span><?php echo esc_html( $tresundios_f['duration'] ); ?></span><span><?php echo esc_html( $tresundios_f['client'] ); ?></span></div>
			<h3><?php echo esc_html( $tresundios_f['card_title'] ); ?></h3>
			<dl class="pr">
				<div><dt><?php esc_html_e( 'Challenge', 'tresundios' ); ?></dt><dd><?php echo esc_html( $tresundios_f['challenge'] ); ?></dd></div>
				<div><dt><?php esc_html_e( 'What we built', 'tresundios' ); ?></dt><dd><?php echo esc_html( $tresundios_f['solution'] ); ?></dd></div>
				<?php if ( $tresundios_f['results'] ) : ?>
					<div><dt><?php esc_html_e( 'Outcome', 'tresundios' ); ?></dt><dd><?php echo esc_html( $tresundios_f['results'][0]['text'] ); ?></dd></div>
				<?php endif; ?>
			</dl>
			<ul class="stack list-unstyled">
			<?php
			foreach ( array_slice( $tresundios_f['tech'], 0, 4 ) as $t ) :
				?>
				<li class="chip"><?php echo esc_html( $t ); ?></li><?php endforeach; ?></ul>
			<div class="d-flex flex-wrap gap-2 mt-4">
				<a class="btn-tr btn-tr-primary" href="<?php echo esc_url( $tresundios_f['url'] ); ?>"><?php esc_html_e( 'Read case study →', 'tresundios' ); ?></a>
				<?php if ( $tresundios_f['live_url'] ) : ?>
					<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( $tresundios_f['live_url'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit live site ↗', 'tresundios' ); ?><span class="visually-hidden"> <?php esc_html_e( '(opens in a new tab)', 'tresundios' ); ?></span></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</article>
<?php
$tresundios_grid( $tresundios_rest );
