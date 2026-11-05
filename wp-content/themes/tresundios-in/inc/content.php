<?php
/**
 * 2026 positioning content: the five integrated services and the three packages.
 * Single source for the service, package and homepage patterns. Edit copy here.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

/**
 * The five integrated services, in display order.
 *
 * @return array<string, array<string, mixed>>
 */
function tresundios_theme_services() {
	return array(
		'websites-ecommerce'    => array(
			'icon'     => 'code',
			'tag'      => 'Core service',
			'title'    => 'Websites & E-commerce',
			'headline' => 'High-converting websites and online stores built to generate enquiries and sales.',
			'desc'     => 'Fast, mobile-first websites and stores with clear calls to action, enquiry and booking flows, and tracking built in from day one.',
			'items'    => array( 'Business websites', 'Landing pages for ads', 'WordPress websites', 'Shopify and WooCommerce stores', 'Booking and enquiry systems', 'Website speed optimisation', 'Website maintenance and support' ),
			'ideal'    => 'Clinics, schools, coaching centres, real-estate firms, local service businesses and D2C brands that need a website that brings in enquiries or orders.',
			'note'     => '',
		),
		'performance-marketing' => array(
			'icon'     => 'megaphone',
			'tag'      => 'Leads',
			'title'    => 'Performance Marketing',
			'headline' => 'Turn ad clicks into qualified enquiries with tracking-first campaigns.',
			'desc'     => 'Google and Meta campaigns that start with tracking, so you can see which ads create enquiries, calls and WhatsApp chats, not just clicks.',
			'items'    => array( 'Google Ads campaign setup', 'Meta Ads campaign setup', 'Landing pages for campaigns', 'GA4 and Google Tag Manager setup', 'WhatsApp and call tracking', 'Conversion tracking', 'Monthly performance reporting' ),
			'ideal'    => 'Local service businesses, clinics, schools, coaching centres and real-estate firms ready to invest in paid ads and wanting to measure the return.',
			'note'     => 'Advertising budget is paid directly by the client and is not included in the management fee.',
		),
		'brand-creative'        => array(
			'icon'     => 'pen',
			'tag'      => 'Brand',
			'title'    => 'Brand & Creative',
			'headline' => 'Make your business look credible, premium and consistent everywhere.',
			'desc'     => 'A clear identity and a ready-to-use creative kit, so your website, ads, social posts and print all look like the same trusted business.',
			'items'    => array( 'Logo and basic brand identity', 'Brand colour and typography system', 'Social-media creatives', 'Reels and short-form video assets', 'Brochures and print design', 'Packaging design', 'Presentation templates' ),
			'ideal'    => 'New businesses, D2C and product brands, and established SMEs whose look no longer matches the quality of their work.',
			'note'     => '',
		),
		'social-content'        => array(
			'icon'     => 'camera',
			'tag'      => 'Visibility',
			'title'    => 'Social & Content Growth',
			'headline' => 'Build visibility and trust with consistent, local-first content.',
			'desc'     => 'Planned, consistent content across Instagram, Facebook, Google and your website, so local customers find you and trust you before they call.',
			'items'    => array( 'Instagram and Facebook content calendars', 'Reels planning and editing support', 'Google Business Profile optimisation', 'Local SEO basics', 'Blog and website content support', 'Monthly content reporting' ),
			'ideal'    => 'Restaurants, clinics, salons, schools, coaching centres and local retailers that depend on nearby customers and word of mouth.',
			'note'     => '',
		),
		'apps-automation'       => array(
			'icon'     => 'cloud',
			'tag'      => 'Tech',
			'title'    => 'Apps & Digital Automation',
			'headline' => 'Automate repetitive work and connect your website with the tools you already use.',
			'desc'     => 'Mobile apps, web applications and integrations that remove manual follow-up and give you one clear view of enquiries and operations.',
			'items'    => array( 'Flutter mobile apps', 'Custom web applications', 'API integrations', 'WhatsApp automation', 'CRM integrations', 'Enquiry and lead-management workflows', 'Custom dashboards' ),
			'ideal'    => 'Growing SMEs with repetitive admin, scattered enquiries or a product idea that needs an app or customer portal.',
			'note'     => '',
		),
	);
}

/**
 * The three packages. Prices in INR, excluding GST (matches the rest of the site).
 *
 * @return array<string, array<string, mixed>>
 */
function tresundios_theme_packages() {
	return array(
		'launch'    => array(
			'name'    => 'Launch Package',
			'price'   => '₹34,999',
			'billing' => 'one-time',
			'extra'   => '',
			'for'     => 'New businesses that need a professional online presence.',
			'items'   => array( 'Up to 5-page WordPress website', 'Mobile-responsive design', 'Basic on-page SEO setup', 'Contact form', 'WhatsApp, email and phone enquiry options', 'Google Business Profile setup guidance', 'GA4 basic setup', '1 round of major revisions', '2 weeks post-launch support' ),
			'care'    => array(),
			'note'    => '',
			'outcome' => 'A credible, mobile-friendly website that makes your business easy to find and contact.',
			'popular' => false,
		),
		'lead'      => array(
			'name'    => 'Lead Growth Package',
			'price'   => '₹24,999 setup',
			'billing' => '+ ₹18,999 per month',
			'extra'   => '',
			'for'     => 'Clinics, schools, real-estate firms, coaching centres and local service businesses that need enquiries.',
			'items'   => array( 'One high-converting landing page or website revamp', 'Google Ads or Meta Ads campaign setup', 'GA4, Google Tag Manager and conversion tracking', 'WhatsApp, email and phone lead capture', 'Monthly creative assets', 'Monthly performance report', 'Monthly conversion-review call' ),
			'care'    => array(),
			'note'    => 'Google/Meta advertising budget is separate and paid directly by the client.',
			'outcome' => 'A tracking-ready lead-generation system that shows which campaigns create enquiries.',
			'popular' => true,
		),
		'ecommerce' => array(
			'name'    => 'E-commerce Growth Package',
			'price'   => '₹69,999',
			'billing' => 'one-time',
			'extra'   => 'Optional care plan: ₹14,999 per month',
			'for'     => 'D2C brands, retailers and product-based businesses.',
			'items'   => array( 'Shopify or WooCommerce store setup/improvement', 'Up to 20 product pages', 'Payment gateway integration', 'Shipping and tax configuration guidance', 'Product-page optimisation', 'GA4 and e-commerce tracking', 'Ad-ready creative templates', 'Checkout and mobile UX review' ),
			'care'    => array( 'Website updates', 'Product-page changes', 'Speed and security checks', 'Monthly analytics review', 'Conversion-rate recommendations' ),
			'note'    => '',
			'outcome' => 'A faster, clearer online store designed to reduce drop-offs and improve purchases.',
			'popular' => false,
		),
	);
}

/**
 * Echo one service as a full section (used by service-* patterns and services-overview).
 *
 * @param string $key Service key from tresundios_theme_services().
 */
function tresundios_render_service_section( $key ) {
	$all = tresundios_theme_services();
	if ( ! isset( $all[ $key ] ) ) {
		return;
	}
	$s = $all[ $key ];
	$n = array_search( $key, array_keys( $all ), true ) + 1;
	?>
	<article class="svc-detail glass" id="<?php echo esc_attr( $key ); ?>" aria-labelledby="<?php echo esc_attr( $key ); ?>-title">
		<div class="row g-4">
			<div class="col-lg-6">
				<p class="eyebrow"><?php echo esc_html( sprintf( '%02d · %s', $n, $s['title'] ) ); ?></p>
				<h2 id="<?php echo esc_attr( $key ); ?>-title"><?php echo esc_html( $s['headline'] ); ?></h2>
				<p class="lede mt-3"><?php echo esc_html( $s['desc'] ); ?></p>
				<dl class="pr mt-4"><dt><?php esc_html_e( 'Ideal for', 'tresundios' ); ?></dt><dd><?php echo esc_html( $s['ideal'] ); ?></dd></dl>
				<?php if ( $s['note'] ) : ?>
					<p class="note mt-3"><strong><?php esc_html_e( 'Please note:', 'tresundios' ); ?></strong> <?php echo esc_html( $s['note'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="col-lg-6">
				<h3 class="eyebrow"><?php esc_html_e( 'What you get', 'tresundios' ); ?></h3>
				<ul class="price-features svc-deliverables">
					<?php foreach ( $s['items'] as $item ) : ?>
						<li><?php echo esc_html( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="svc-detail-foot">
			<a class="btn-tr btn-tr-primary" href="#audit" data-track="cta_click" data-track-location="service-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Get a free website & growth audit', 'tresundios' ); ?> →</a>
			<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( home_url( '/packages/' ) ); ?>"><?php esc_html_e( 'See packages', 'tresundios' ); ?></a>
			<a class="btn-tr btn-tr-ghost" href="<?php echo esc_url( home_url( '/work/' ) ); ?>"><?php esc_html_e( 'See our work', 'tresundios' ); ?></a>
		</div>
	</article>
	<?php
}

/**
 * Echo one package card.
 *
 * @param string $key     Package key.
 * @param bool   $compact Homepage teaser: hide the full list after 5 items.
 */
function tresundios_render_package_card( $key, $compact = false ) {
	$all = tresundios_theme_packages();
	if ( ! isset( $all[ $key ] ) ) {
		return;
	}
	$p     = $all[ $key ];
	$items = $compact ? array_slice( $p['items'], 0, 5 ) : $p['items'];
	?>
	<article class="price-card pkg-card glass spot<?php echo $p['popular'] ? ' is-popular' : ''; ?>" id="package-<?php echo esc_attr( $key ); ?>" aria-labelledby="package-<?php echo esc_attr( $key ); ?>-title">
		<?php if ( $p['popular'] ) : ?>
			<span class="price-badge"><?php esc_html_e( 'For lead generation', 'tresundios' ); ?></span>
		<?php endif; ?>
		<h3 id="package-<?php echo esc_attr( $key ); ?>-title"><?php echo esc_html( $p['name'] ); ?></h3>
		<p class="price-description"><strong><?php esc_html_e( 'For:', 'tresundios' ); ?></strong> <?php echo esc_html( $p['for'] ); ?></p>
		<div class="price-amount"><?php echo esc_html( $p['price'] ); ?></div>
		<div class="price-billing"><?php echo esc_html( $p['billing'] ); ?> · <?php esc_html_e( 'excl. GST', 'tresundios' ); ?></div>
		<?php if ( $p['extra'] ) : ?>
			<div class="price-recurring"><?php echo esc_html( $p['extra'] ); ?></div>
		<?php endif; ?>
		<?php if ( $p['note'] ) : ?>
			<p class="price-extra"><?php echo esc_html( $p['note'] ); ?></p>
		<?php endif; ?>
		<h4 class="pkg-sub"><?php esc_html_e( 'Includes', 'tresundios' ); ?></h4>
		<ul class="price-features">
			<?php foreach ( $items as $item ) : ?>
				<li><?php echo esc_html( $item ); ?></li>
			<?php endforeach; ?>
			<?php if ( $compact && count( $p['items'] ) > 5 ) : ?>
				<li><a href="<?php echo esc_url( home_url( '/packages/#package-' . $key ) ); ?>"><?php echo esc_html( sprintf( /* translators: %d: number of items. */ __( '+ %d more', 'tresundios' ), count( $p['items'] ) - 5 ) ); ?></a></li>
			<?php endif; ?>
		</ul>
		<?php if ( $p['care'] && ! $compact ) : ?>
			<h4 class="pkg-sub"><?php esc_html_e( 'Care plan includes', 'tresundios' ); ?></h4>
			<ul class="price-features">
				<?php foreach ( $p['care'] as $item ) : ?>
					<li><?php echo esc_html( $item ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<p class="pkg-outcome"><strong><?php esc_html_e( 'Expected outcome:', 'tresundios' ); ?></strong> <?php echo esc_html( $p['outcome'] ); ?></p>
		<a class="btn-tr <?php echo $p['popular'] ? 'btn-tr-primary' : 'btn-tr-ghost'; ?> price-cta" href="#audit" data-track="cta_click" data-track-location="package-<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'Get a free website & growth audit', 'tresundios' ); ?><span class="visually-hidden">: <?php echo esc_html( $p['name'] ); ?></span> →</a>
	</article>
	<?php
}
