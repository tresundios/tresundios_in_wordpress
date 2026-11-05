<?php
/**
 * Title: Hero with trust facts
 * Slug: tresundios/hero
 * Categories: tresundios
 * Description: Home hero: positioning, audit CTA, WhatsApp/email/phone and verifiable trust facts.
 *
 * Stats rule: only facts Tresundios can verify (registration, dates, published prices, scope).
 * No satisfaction scores, savings percentages or client counts until they are measured.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"container tr-sec","layout":{"type":"default"}} -->
<div class="wp-block-group container tr-sec"><!-- wp:group {"className":"row hero align-items-center g-5","layout":{"type":"default"}} -->
<div class="wp-block-group row hero align-items-center g-5"><!-- wp:group {"className":"col-lg-7 reveal","layout":{"type":"default"}} -->
<div class="wp-block-group col-lg-7 reveal"><!-- wp:html -->
<span class="eyebrow d-inline-flex align-items-center gap-2"><img class="icon-badge" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/favicon.webp' ) ); ?>" alt="" width="22" height="22">Chennai-based digital studio for ambitious SMEs</span>
<h1 class="mt-3">Websites, apps and digital growth systems <span class="grad">for ambitious SMEs.</span></h1>
<!-- /wp:html -->

<!-- wp:paragraph {"className":"lede mt-4"} -->
<p class="lede mt-4">Tresundios builds high-converting websites, e-commerce stores and mobile apps—then connects them with branding, content and performance marketing to turn attention into revenue.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"mt-4 pt-1"} -->
<div class="wp-block-buttons mt-4 pt-1"><!-- wp:button {"className":"has-arrow"} -->
<div class="wp-block-button has-arrow"><a class="wp-block-button__link wp-element-button" href="#audit" data-track="cta_click" data-track-location="hero">Get a free website &amp; growth audit </a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-ghost"} -->
<div class="wp-block-button is-style-ghost"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/work/' ) ); ?>">See our work</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:html -->
<?php tresundios_lead_buttons( 'hero' ); ?>
<ul class="d-flex flex-wrap gap-2 mt-4 list-unstyled trust-chips" aria-label="Company facts"><li class="chip">Chennai-based</li><li class="chip">DPIIT-recognised startup</li><li class="chip">Established November 2024</li></ul>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<div class="col-lg-5">
	<div class="hero-stack mx-auto" aria-hidden="true">
		<img class="hero-anchor" src="<?php echo esc_url( get_theme_file_uri( 'assets/img/icon.webp' ) ); ?>" alt="" width="132" height="132" fetchpriority="high">
		<div class="glass float a spot">
			<div class="dots"><i></i><i></i><i></i></div>
			<div class="mini-title">Enquiry sources · tracked</div>
			<div class="kpi" style="font-size:26px">WhatsApp · Call · Form</div>
			<div class="bars"><span style="height:40%"></span><span style="height:55%"></span><span style="height:48%"></span><span style="height:62%"></span><span style="height:70%"></span><span style="height:78%"></span><span style="height:90%"></span></div>
		</div>
		<div class="glass float b spot">
			<div class="mini-title">Brand kit</div>
			<div class="d-flex gap-2 mt-3"><span class="swatch" style="background:#c74634"></span><span class="swatch" style="background:#666666"></span><span class="swatch" style="background:#ffffff"></span><span class="swatch" style="background:#000000"></span></div>
			<div class="display-face mt-3" style="font-size:22px;font-weight:600">Aa Bb</div>
		</div>
		<div class="glass float c spot">
			<div class="mini-title">Store checkout</div>
			<div class="kpi" style="font-size:26px">Mobile-first</div>
			<div class="text-muted-tr" style="font-size:13px">Speed · tracking · UX review</div>
			<div class="meter"><div style="width:85%"></div></div>
		</div>
	</div>
</div>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="stats glass row g-0 row-cols-2 row-cols-md-4">
	<li class="stat col"><div class="n">5</div><div class="l">integrated services, one studio</div></li>
	<li class="stat col"><div class="n">₹34,999</div><div class="l">websites from, published pricing (excl. GST)</div></li>
	<li class="stat col"><div class="n">24h</div><div class="l">reply to every enquiry</div></li>
	<li class="stat col"><div class="n">2034</div><div class="l">DPIIT certificate valid until</div></li>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group -->
