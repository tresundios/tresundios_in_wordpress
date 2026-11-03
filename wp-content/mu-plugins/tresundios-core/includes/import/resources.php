<?php
/**
 * Resources: type descriptions, Glossary and FAQ pages, and starter drafts.
 *
 * Every resource is imported as a DRAFT for review; nothing here goes live until an editor publishes it.
 * Whitepapers are outlines with TODO markers: they need Tresundios' own data and point of view.
 * Bodies use simple HTML (h2, h3, p, ul, ol) that the importer converts to core blocks.
 * Tokens are the same as content.php.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

return array(
	'types'     => array(
		'guides'      => 'Practical, step-by-step guides for small businesses on marketing, design, websites and apps.',
		'whitepapers' => 'In-depth papers on growing a small business with digital marketing and technology.',
		'checklists'  => 'Checklists and templates you can use before a launch, a shoot or an event.',
	),

	'pages'     => array(
		'faq'      => array(
			'title'    => 'Frequently asked questions',
			'lede'     => 'Answers about our services, pricing, process and timelines, grouped by topic. Each answer links to the page it comes from.',
			'sections' => array( 'faq-hub', 'cta-book-call' ),
		),
		'glossary' => array(
			'title'    => 'Glossary',
			'lede'     => 'Plain-language definitions of the marketing, design, web and DevOps terms we use.',
			'sections' => array(
				'prose:<dl class="glossary">'
				. '<dt id="api">API</dt><dd>An application programming interface: a defined way for two pieces of software to exchange data, for example your website and a payment gateway.</dd>'
				. '<dt id="brand-identity">Brand identity</dt><dd>The visual system that makes a business recognisable: logo, colours, typefaces and how they are used.</dd>'
				. '<dt id="ci-cd">CI/CD</dt><dd>Continuous integration and continuous delivery: automatically testing and deploying code every time it changes, instead of releasing by hand.</dd>'
				. '<dt id="cms">CMS</dt><dd>A content management system, such as WordPress, that lets you edit website pages without writing code.</dd>'
				. '<dt id="core-web-vitals">Core Web Vitals</dt><dd>Google’s measures of page experience: how fast the main content loads (LCP), how quickly the page responds to input (INP) and how stable the layout is (CLS).</dd>'
				. '<dt id="cost-per-lead">Cost per lead</dt><dd>Ad spend divided by the number of enquiries the ads produced.</dd>'
				. '<dt id="docker">Docker</dt><dd>A tool that packages an application with everything it needs into a container, so it runs the same on a laptop and on a server.</dd>'
				. '<dt id="engagement-rate">Engagement rate</dt><dd>The share of people who interacted with a post (likes, comments, shares, saves) out of those who saw it.</dd>'
				. '<dt id="flutter">Flutter</dt><dd>Google’s framework for building one app that runs on both iOS and Android from a single codebase.</dd>'
				. '<dt id="google-business-profile">Google Business Profile</dt><dd>The free Google listing that shows your business on Maps and in local search, with address, hours, photos and reviews.</dd>'
				. '<dt id="infrastructure-as-code">Infrastructure as code</dt><dd>Describing servers, networks and cloud services in files (for example with Terraform) so they can be reviewed and rebuilt automatically.</dd>'
				. '<dt id="landing-page">Landing page</dt><dd>A single, focused web page built for one offer or campaign, with one clear call to action.</dd>'
				. '<dt id="local-seo">Local SEO</dt><dd>Work that helps a business appear in search results for nearby customers, such as “near me” searches and Google Maps.</dd>'
				. '<dt id="mvp">MVP</dt><dd>A minimum viable product: the first working version of an app with only the core features, used to test the idea with real users.</dd>'
				. '<dt id="nap">NAP</dt><dd>Name, address and phone number. Keeping them identical everywhere online helps local search rankings.</dd>'
				. '<dt id="reach">Reach and impressions</dt><dd>Reach is how many different people saw your content; impressions is how many times it was shown in total.</dd>'
				. '<dt id="responsive-design">Responsive design</dt><dd>A website layout that adapts to phones, tablets and desktops.</dd>'
				. '<dt id="social-media-optimisation">Social media optimisation</dt><dd>Improving profiles, content and calls to action so social media brings in enquiries, not just followers.</dd>'
				. '</dl>',
				'cta-book-call',
			),
		),
	),

	'resources' => array(
		array(
			'slug'    => 'how-much-does-a-website-cost',
			'type'    => 'guides',
			'service' => 'websites-and-ecommerce',
			'title'   => 'How much does a website cost? A guide for small businesses',
			'excerpt' => 'What drives the price of a small-business website, what each type of site costs with us{in}, and the running costs to budget for.',
			'reading' => '5 min read',
			'body'    => '<p>A small-business website can cost anything from a one-page landing page to a custom web application. The price depends on how many pages you need, whether you sell online, which integrations you need and who writes the content.</p>'
				. '<h2>Typical prices</h2><ul><li>One-page landing page: {price:wb-landing|landing-page}</li><li>Business website, 5 to 7 pages: {price:wb-business|business-website}</li><li>Professional website, 8 to 12 pages with CMS and SEO setup: {price:wb-professional|professional-website}</li><li>Booking or appointment website: {price:wb-booking|booking-website}</li><li>E-commerce website, up to 50 products: {price:wb-ecom|ecommerce-website}</li></ul><p>Prices are in {cur}{tax}.</p>'
				. '<h2>What changes the price</h2><ul><li>Number of pages and unique layouts</li><li>Online payments, bookings or member areas</li><li>Integrations with tools you already use</li><li>Copywriting, photography and translations</li></ul>'
				. '<h2>Running costs</h2><p>Budget for a domain, hosting and ongoing care. Our website care plan is {price:wb-care|website-care}{tax}.</p>'
				. '<h2>How to get an accurate quote</h2><ol><li>List the pages and features you need.</li><li>Collect two or three websites you like.</li><li>Book a free call: we send a fixed proposal before any work starts.</li></ol>',
		),
		array(
			'slug'    => 'google-business-profile-setup-guide',
			'type'    => 'guides',
			'service' => 'local-seo-google-business-profile',
			'title'   => 'How to set up and optimise your Google Business Profile',
			'excerpt' => 'A step-by-step guide to claiming, completing and keeping your Google Business Profile up to date, so nearby customers find you.',
			'reading' => '6 min read',
			'body'    => '<p>Your Google Business Profile is often the first thing a nearby customer sees. A complete, accurate profile helps you appear in local search and on Google Maps.</p>'
				. '<h2>1. Claim and verify your profile</h2><p>Search for your business on Google Maps. If a listing exists, claim it; otherwise create one. Google will ask you to verify that you run the business.</p>'
				. '<h2>2. Get the basics exactly right</h2><ul><li>Use your real business name, with no extra keywords.</li><li>Choose the most specific primary category.</li><li>Keep your name, address and phone number identical to your website and other listings.</li><li>Add opening hours, including holiday hours.</li></ul>'
				. '<h2>3. Add services, photos and a description</h2><p>List each service, upload real photos of your premises, team and products, and write a short description of what you do and who you serve.</p>'
				. '<h2>4. Keep it active</h2><ul><li>Post updates and offers regularly.</li><li>Ask happy customers for reviews and reply to every review.</li><li>Answer questions in the Q&amp;A section.</li></ul>'
				. '<h2>Want us to do it?</h2><p>Google Business Profile and local SEO setup is {price:dm-gbp-setup|google-business-seo}{tax}.</p>',
		),
		array(
			'slug'    => 'flutter-or-native-app',
			'type'    => 'guides',
			'service' => 'flutter-app-development',
			'title'   => 'Flutter or native? Choosing how to build your first app',
			'excerpt' => 'When a single Flutter codebase makes sense for a small business, when native apps are worth it, and what to decide before you start.',
			'reading' => '5 min read',
			'body'    => '<p>Most small businesses need their app on both iPhone and Android. You can build two native apps or one cross-platform app with Flutter.</p>'
				. '<h2>Why Flutter suits most first apps</h2><ul><li>One codebase for iOS and Android, so one build and one set of updates.</li><li>Consistent design on both platforms.</li><li>Good performance for typical business apps: bookings, catalogues, loyalty, tracking.</li></ul>'
				. '<h2>When native may be better</h2><ul><li>Heavy use of the newest platform-specific features.</li><li>Very graphics-intensive apps such as advanced games.</li></ul>'
				. '<h2>Decide this before you start</h2><ol><li>The one job the first version must do well.</li><li>Who will use it and on which devices.</li><li>Which systems it must connect to.</li></ol>'
				. '<p>We built the TEF Kuwait Walkathon app in Flutter, with step tracking and live leaderboards. A Flutter app MVP is {price:wb-flutter|flutter-mvp}{tax}.</p>',
		),
		array(
			'slug'    => 'digital-presence-playbook-for-smes',
			'type'    => 'whitepapers',
			'service' => 'digital-marketing',
			'title'   => 'Digital presence for SMEs: a practical playbook',
			'excerpt' => 'How small and medium businesses can build a brand, website and marketing engine that compete with bigger players on an SME budget.',
			'reading' => '',
			'body'    => '<p class="todo">TODO: content needed. Outline for review; add Tresundios’ own findings, examples and data before publishing, then attach the PDF in the Download field.</p>'
				. '<h2>1. Why digital presence matters for SMEs</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>2. The foundations: brand, website, Google Business Profile</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>3. Content and social media that bring enquiries</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>4. Paid ads: when to start and how much to spend</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>5. Measuring what matters</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>6. A 12-month roadmap</h2><p class="todo">TODO: content needed.</p>',
		),
		array(
			'slug'    => '90-day-marketing-plan-for-new-businesses',
			'type'    => 'whitepapers',
			'service' => 'social-media-optimisation',
			'title'   => 'From launch to leads: a 90-day marketing plan for new businesses',
			'excerpt' => 'A week-by-week plan for the first three months after launch: brand, local search, content and the first paid campaigns.',
			'reading' => '',
			'body'    => '<p class="todo">TODO: content needed. Outline for review; add the detailed plan and examples before publishing, then attach the PDF in the Download field.</p>'
				. '<h2>Days 1–30: foundations</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>Days 31–60: consistent content and local search</h2><p class="todo">TODO: content needed.</p>'
				. '<h2>Days 61–90: first paid campaigns and reporting</h2><p class="todo">TODO: content needed.</p>',
		),
		array(
			'slug'    => 'website-launch-checklist',
			'type'    => 'checklists',
			'service' => 'websites-and-ecommerce',
			'title'   => 'Website launch checklist',
			'excerpt' => 'Everything to check before your new website goes live: content, SEO, speed, forms, analytics and security.',
			'reading' => '3 min read',
			'body'    => '<p>Use this checklist in the week before launch.</p>'
				. '<h2>Content</h2><ul><li>Every page proofread, with no placeholder text</li><li>Contact details identical to your Google Business Profile</li><li>Privacy policy and terms published</li></ul>'
				. '<h2>Search</h2><ul><li>Unique title and meta description on every page</li><li>One H1 per page</li><li>XML sitemap submitted in Google Search Console</li><li>Redirects from old URLs to new pages</li></ul>'
				. '<h2>Speed and mobile</h2><ul><li>Images compressed and sized</li><li>Pages tested on a phone</li><li>Core Web Vitals checked with PageSpeed Insights</li></ul>'
				. '<h2>Forms and tracking</h2><ul><li>Every form tested end to end</li><li>Analytics installed and receiving data</li></ul>'
				. '<h2>Security</h2><ul><li>SSL certificate active on every page</li><li>Backups scheduled and tested</li><li>Admin accounts use strong passwords</li></ul>',
		),
		array(
			'slug'    => 'event-planning-checklist',
			'type'    => 'checklists',
			'service' => 'event-management',
			'title'   => 'Event planning checklist',
			'excerpt' => 'A timeline checklist for brand launches, corporate events and celebrations, from eight weeks out to the day after.',
			'reading' => '3 min read',
			'body'    => '<h2>Eight weeks before</h2><ul><li>Goal, audience, guest count and budget agreed</li><li>Date and venue shortlisted</li></ul>'
				. '<h2>Six weeks before</h2><ul><li>Venue booked</li><li>Vendors booked: catering, décor, sound and lighting, photography</li><li>Invitations and event graphics designed</li></ul>'
				. '<h2>Four weeks before</h2><ul><li>Invitations sent and RSVPs tracked</li><li>Social media promotion started</li></ul>'
				. '<h2>One week before</h2><ul><li>Run-of-show shared with every vendor</li><li>Final guest list and seating</li></ul>'
				. '<h2>On the day and after</h2><ul><li>Day-of coordinator on site</li><li>Photos and videos captured for social media</li><li>Thank-you message and highlights posted the next day</li></ul>',
		),
	),
);
