<?php
/**
 * Title: Hero with stats
 * Slug: tresundios/hero
 * Categories: tresundios
 * Description: Home hero: headline, floating brand cards and four stats.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"container tr-sec","layout":{"type":"default"}} -->
<div class="wp-block-group container tr-sec"><!-- wp:group {"className":"row hero align-items-center g-5","layout":{"type":"default"}} -->
<div class="wp-block-group row hero align-items-center g-5"><!-- wp:group {"className":"col-lg-7 reveal","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-7 reveal"><!-- wp:html -->
<span class="eyebrow d-inline-flex align-items-center gap-2"><img class="icon-badge" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/favicon.webp' ) ); ?>" alt="" width="22" height="22">Chennai · DPIIT-recognised startup · Est. Nov 2024</span>
<h1 class="mt-3">Small business.<br><span class="grad">Big-brand presence.</span></h1>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"lede mt-4"} -->
<p class="lede mt-4">Tresundios is a studio for growing businesses. We run your social media, design your brand, produce your events and build your website and apps, all under one roof and at prices that make sense for an SME budget.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-4 pt-1"} -->
<div class="wp-block-buttons mt-4 pt-1"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a free consultation </a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">See our work</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:html -->
<div class="d-flex flex-wrap gap-2 mt-4"><span class="chip"><b>6</b> service lines</span><span class="chip"><b>6</b> published case studies</span><span class="chip">India · UK · Kuwait</span></div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="col-lg-5">
	<div class="hero-stack mx-auto" aria-hidden="true">
		<img class="hero-anchor" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/icon.webp' ) ); ?>" alt="" width="132" height="132" fetchpriority="high">
		<div class="glass float a spot">
			<div class="dots"><i></i><i></i><i></i></div>
			<div class="mini-title">Instagram reach · last 6 weeks</div>
			<div class="kpi">48.2K<small>▲ campaign live</small></div>
			<div class="bars"><span style="height:28%"></span><span style="height:36%"></span><span style="height:33%"></span><span style="height:52%"></span><span style="height:64%"></span><span style="height:78%"></span><span style="height:96%"></span></div>
		</div>
		<div class="glass float b spot">
			<div class="mini-title">Brand kit</div>
			<div class="d-flex gap-2 mt-3"><span class="swatch" style="background:#c74634"></span><span class="swatch" style="background:#666666"></span><span class="swatch" style="background:#ffffff"></span><span class="swatch" style="background:#000000"></span></div>
			<div class="display-face mt-3" style="font-size:22px;font-weight:600">Aa Bb</div>
		</div>
		<div class="glass float c spot">
			<div class="mini-title">Launch event</div>
			<div class="kpi" style="font-size:26px">Sat · 7 PM</div>
			<div class="text-muted-tr" style="font-size:13px">RSVPs 212 / 250</div>
			<div class="meter"><div style="width:85%"></div></div>
		</div>
	</div>
</div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="stats glass row g-0 row-cols-2 row-cols-md-4">
	<li class="stat col"><div class="n">50%</div><div class="l">lower cost than enterprise agencies</div></li>
	<li class="stat col"><div class="n">24h</div><div class="l">response time on every enquiry</div></li>
	<li class="stat col"><div class="n">100%</div><div class="l">client satisfaction to date</div></li>
	<li class="stat col"><div class="n">2034</div><div class="l">DPIIT certificate valid until</div></li>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group -->
