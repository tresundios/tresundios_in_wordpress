<?php
/**
 * Single case study body (.in).
 *
 * @package Tresundios\Theme\In
 *
 * @var array<string, mixed>|null $data Case study.
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

if ( ! $data ) {
	return;
}
?>
<article class="case-page">
	<div class="row g-4 align-items-center case-hero">
		<div class="col-md-6">
			<div class="thumb">
				<?php
				if ( $data['thumb_id'] ) {
					echo wp_get_attachment_image(
						$data['thumb_id'],
						'large',
						false,
						array(
							'loading'       => 'eager',
							'fetchpriority' => 'high',
							'sizes'         => '(min-width: 768px) 50vw, 100vw',
							'style'         => 'object-position:' . ( $data['focal'] ? $data['focal'] : '50% 50%' ),
						)
					);
				}
				?>
				<?php
				if ( $data['tag'] ) :
					?>
					<span class="mono-big"><?php echo esc_html( $data['tag'] ); ?></span><?php endif; ?>
			</div>
		</div>
		<div class="col-md-6">
			<?php
			if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
				rank_math_the_breadcrumbs(); }
			?>
			<div class="meta"><span class="cat"><?php echo esc_html( $data['industry'] ); ?></span></div>
			<h1><?php echo esc_html( $data['title'] ); ?></h1>
			<div class="row g-2 mt-2">
				<div class="col-6 col-sm-4"><div class="fact"><small><?php esc_html_e( 'Client', 'tresundios' ); ?></small><b><?php echo esc_html( $data['client'] ); ?></b></div></div>
				<div class="col-6 col-sm-4"><div class="fact"><small><?php esc_html_e( 'Duration', 'tresundios' ); ?></small><b><?php echo esc_html( $data['duration'] ); ?></b></div></div>
				<div class="col-12 col-sm-4"><div class="fact"><small><?php esc_html_e( 'Market', 'tresundios' ); ?></small><b><?php echo esc_html( $data['location'] ); ?></b></div></div>
			</div>
			<?php if ( $data['tech'] ) : ?>
				<ul class="stack list-unstyled" aria-label="<?php esc_attr_e( 'Technology', 'tresundios' ); ?>">
				<?php
				foreach ( $data['tech'] as $t ) :
					?>
					<li class="chip"><?php echo esc_html( $t ); ?></li><?php endforeach; ?></ul>
			<?php endif; ?>
		</div>
	</div>
	<?php
	// 2026 structure: client & industry → problem → what we built → services used → results → CTA.
	// Results with no measured number show the visible placeholder below; replace it in the case study's "results" field.
	$tresundios_placeholder = '[Add measurable result: e.g., 35% more enquiries within 3 months]';
	$tresundios_services    = $data['types'] ? $data['types'] : $data['tech'];
	$tresundios_has_number  = false;
	foreach ( $data['results'] as $r ) {
		if ( preg_match( '/\d/', ( $r['label'] ?? '' ) . ( $r['text'] ?? '' ) ) ) {
			$tresundios_has_number = true;
		}
	}
	?>
	<div class="row g-3 case-structure">
		<section class="col-md-6"><div class="case-block"><h2><span class="case-step" aria-hidden="true">01</span> <?php esc_html_e( 'Client & industry', 'tresundios' ); ?></h2><p><?php echo esc_html( trim( $data['client'] . ( $data['industry'] ? ' · ' . $data['industry'] : '' ) . ( $data['location'] ? ' · ' . $data['location'] : '' ), ' ·' ) ); ?></p></div></section>
		<section class="col-md-6"><div class="case-block"><h2><span class="case-step" aria-hidden="true">02</span> <?php esc_html_e( 'The problem', 'tresundios' ); ?></h2><p><?php echo esc_html( $data['challenge'] ? $data['challenge'] : '[Add the problem the client faced]' ); ?></p></div></section>
		<section class="col-md-6"><div class="case-block"><h2><span class="case-step" aria-hidden="true">03</span> <?php esc_html_e( 'What Tresundios built', 'tresundios' ); ?></h2><p><?php echo esc_html( $data['solution'] ? $data['solution'] : '[Add what we built]' ); ?></p></div></section>
		<section class="col-md-6"><div class="case-block"><h2><span class="case-step" aria-hidden="true">04</span> <?php esc_html_e( 'Services used', 'tresundios' ); ?></h2>
			<?php if ( $tresundios_services ) : ?>
				<ul class="stack list-unstyled"><?php foreach ( $tresundios_services as $t ) : ?><li class="chip"><?php echo esc_html( $t ); ?></li><?php endforeach; ?></ul>
			<?php else : ?>
				<p>[Add services used]</p>
			<?php endif; ?>
		</div></section>
	</div>
	<section aria-labelledby="results-title" class="mt-4">
		<h2 class="eyebrow" id="results-title"><span class="case-step" aria-hidden="true">05</span> <?php esc_html_e( 'Results / measurable outcome', 'tresundios' ); ?></h2>
		<div class="row g-3 mt-1">
			<?php foreach ( $data['results'] as $r ) : ?>
				<div class="col-md-4"><div class="result"><b><?php echo esc_html( $r['label'] ?? '' ); ?></b><?php echo esc_html( $r['text'] ?? '' ); ?></div></div>
			<?php endforeach; ?>
			<?php if ( ! $tresundios_has_number ) : ?>
				<div class="col-md-4"><div class="result result-placeholder"><b><?php echo esc_html( $tresundios_placeholder ); ?></b></div></div>
			<?php endif; ?>
		</div>
	</section>
	<div class="case-foot">
		<div class="d-flex flex-wrap gap-2">
			<?php if ( $data['live_url'] ) : ?>
				<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( $data['live_url'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit live site ↗', 'tresundios' ); ?><span class="visually-hidden"> <?php esc_html_e( '(opens in a new tab)', 'tresundios' ); ?></span></a>
			<?php endif; ?>
			<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ); ?>"><?php esc_html_e( 'All case studies', 'tresundios' ); ?></a>
		</div>
		<a class="btn-tr btn-tr-primary" href="#audit" data-track="cta_click" data-track-location="case-study"><?php esc_html_e( 'Get a free website & growth audit →', 'tresundios' ); ?></a>
	</div>
</article>
