<?php
/**
 * Page and service copy for the content import.
 *
 * Built from the original sites' copy. FAQs and the longer service text are drafts written for this
 * migration and are listed as "needs review" by the importer. No client names, metrics or prices are
 * invented: prices come from the site's own pricing entries through {price:com-id|in-id} tokens.
 *
 * Tokens (replaced per site):
 *   {in}        " in Chennai" on .in, "" on .com
 *   {where}     "in Chennai and across Tamil Nadu" / "in the UK, Kuwait, the Gulf and worldwide"
 *   {clients}   "Chennai businesses" / "growing businesses worldwide"
 *   {cur}       INR / USD
 *   {tax}       " (excl. GST)" on .in, "" on .com
 *   {price:a|b} formatted price of pricing entry a (.com) or b (.in), e.g. "Starting from ₹8,000 per month"
 *   {from:a|b}  just the amount, e.g. "₹8,000"
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

$tresundios_general_faq = array(
	array( 'How quickly do you reply to enquiries?', 'We reply to every enquiry within 24 hours. You can also book a free 30-minute call directly from the Book a free call button.' ),
	array( 'Do you work with small businesses and startups?', 'Yes. Tresundios was set up for SMEs: restaurants, cafés, salons, startups, e-commerce brands, education consultancies and healthcare companies. We design for SME budgets and timelines from day one.' ),
);

return array(

	/*
	 * Services: slug => definition. Children are listed under their parent in Tresundios_Importer::service_tree().
	 * h1:        page heading ({in} adds the city on .in)
	 * answer:    40–60 word direct answer shown first on the page
	 * items:     deliverables
	 * prices:    pricing entries (com-id|in-id) shown on this page and used for its Offer schema
	 * work:      work_type for related case studies
	 */
	'services' => array(
		'digital-marketing'                 => array(
			'h1'     => 'Digital Marketing Services{in}',
			'answer' => 'Tresundios plans, posts, promotes and reports on your social media, local SEO and paid ads, so small and medium businesses {where} get big-brand reach without agency overheads. Packages start at {from:pkg-starter|starter} a month{tax}, with a clear monthly report and one point of contact.',
			'items'  => array( 'Social media marketing, organic and paid', 'Social media optimisation', 'Local SEO and Google Business Profile', 'Meta and Google Ads setup and management', 'WhatsApp and email marketing', 'Campaign analytics and monthly reach reports' ),
			'prices' => array(),
			'work'   => 'marketing',
			'faq'    => array(
				array( 'How much do your digital marketing packages cost?', 'Our Starter package is {price:pkg-starter|starter}, Growth is {price:pkg-growth|growth} and Lead Engine is {price:pkg-lead-engine|lead-engine}{tax}. Ad spend is billed separately at actual cost.' ),
				array( 'Which social platforms do you manage?', 'We work across the leading platforms, including Instagram, Facebook and LinkedIn. Packages cover one to three platforms depending on the plan.' ),
				array( 'Do I have to sign a long contract?', 'Marketing packages are billed monthly. We recommend at least three months so the content calendar and local SEO have time to show results.' ),
				array( 'How do you report results?', 'You get a performance report every month (every two weeks on Lead Engine) that shows reach, engagement and enquiries, plus what we will change next.' ),
			),
		),
		'social-media-marketing'            => array(
			'h1'     => 'Social Media Marketing{in}',
			'answer' => 'We plan, design and post your social media every month, organic and paid, so customers see a consistent, on-brand presence. Plans start at {price:dm-social-8|social-8}{tax} for 8 posts and 4 stories, with reels, ads setup and ads management available as you grow.',
			'items'  => array( 'Monthly content calendar', 'Designed posts, stories and reels', 'Captions and hashtags written for your audience', 'Scheduling and posting', 'Meta or Google Ads campaigns when you are ready to promote' ),
			'prices' => array( 'dm-social-8|social-8', 'dm-social-12|social-12', 'dm-ads-setup|ads-setup', 'dm-ads-mgmt|ads-management' ),
			'work'   => 'marketing',
			'faq'    => array(
				array( 'What is included in the monthly social media plan?', 'The entry plan includes 8 posts and 4 stories a month. The larger plan adds more posts, 8 stories and 2 reels. We plan, design, write and post everything for your approval.' ),
				array( 'Do you run paid ads as well?', 'Yes. Ads setup is {price:dm-ads-setup|ads-setup}{tax} and ongoing ads management is {price:dm-ads-mgmt|ads-management}, or 12% of ad spend. Ad spend itself is paid directly to the platform.' ),
				array( 'Do I approve posts before they go live?', 'Yes. You see the content calendar and designs before anything is published.' ),
				array( 'Can you post in more than one language?', 'Tell us the languages your customers use and we will confirm what we can produce for your plan.' ),
			),
		),
		'social-media-optimisation'         => array(
			'h1'     => 'Social Media Optimisation{in}',
			'answer' => 'Social media optimisation makes your profiles, content and messaging channels work harder: a clear strategy, complete profiles, WhatsApp and email set up to capture enquiries. It starts with a marketing strategy and 90-day plan at {price:dm-strategy|marketing-plan}{tax}, so every post after that has a clear purpose and a way for customers to reach you.',
			'items'  => array( 'Audience and competitor review', '90-day channel and content plan', 'Profile and bio optimisation', 'WhatsApp Business setup for enquiries', 'Email marketing campaigns' ),
			'prices' => array( 'dm-strategy|marketing-plan', 'dm-whatsapp|whatsapp-setup', 'dm-email|email-campaigns' ),
			'work'   => 'marketing',
			'faq'    => array(
				array( 'What is the difference between social media marketing and optimisation?', 'Marketing is the ongoing posting and promotion. Optimisation is the groundwork that makes it convert: strategy, complete profiles, clear calls to action and channels such as WhatsApp ready to take enquiries.' ),
				array( 'What do I get in the 90-day plan?', 'An audience and competitor review and a channel and content plan for the next three months, built around your goals.' ),
				array( 'Can you set up WhatsApp marketing?', 'Yes. WhatsApp marketing setup is {price:dm-whatsapp|whatsapp-setup}{tax} and gets your business ready to market and take enquiries on WhatsApp.' ),
				array( 'Do you write email campaigns too?', 'Yes. Four designed and written email campaigns a month cost {price:dm-email|email-campaigns}{tax}.' ),
			),
		),
		'local-seo-google-business-profile' => array(
			'h1'     => 'Local SEO & Google Business Profile{in}',
			'answer' => 'Local SEO helps nearby customers find you on Google Search and Maps. We set up and optimise your Google Business Profile, fix your listings and keep your local visibility growing every month. Setup is {price:dm-gbp-setup|google-business-seo}{tax}; ongoing local SEO is {price:dm-local-seo|local-seo}.',
			'items'  => array( 'Google Business Profile setup and optimisation', 'Categories, services, photos and opening hours', 'Consistent name, address and phone across listings', 'Regular Google posts and review responses', 'Monthly local visibility report' ),
			'prices' => array( 'dm-gbp-setup|google-business-seo', 'dm-local-seo|local-seo' ),
			'work'   => 'marketing',
			'faq'    => array(
				array( 'What is a Google Business Profile?', 'It is the free listing that shows your business on Google Maps and in local search results, with your address, hours, photos and reviews.' ),
				array( 'How long does local SEO take to work?', 'Profile fixes can show quickly, but local rankings usually build over a few months of consistent updates, reviews and listings work.' ),
				array( 'Do you need access to my Google account?', 'We ask you to add us as a manager on your Business Profile, so you always stay the owner.' ),
				array( 'How much does local SEO cost?', 'One-time setup is {price:dm-gbp-setup|google-business-seo} and ongoing local SEO is {price:dm-local-seo|local-seo}{tax}.' ),
			),
		),
		'campaign-analytics-reporting'      => array(
			'h1'     => 'Campaign Analytics & Reach Reports',
			'answer' => 'We turn your social, search and ads data into a plain-language monthly report: what worked, what did not, and what to do next, followed by a strategy call where we agree the changes for next month. The monthly performance report and strategy call costs {price:dm-report|monthly-report}{tax}.',
			'items'  => array( 'Reach, engagement and enquiry tracking', 'Ads performance and cost per lead', 'Plain-language monthly report', 'Strategy call with next steps' ),
			'prices' => array( 'dm-report|monthly-report' ),
			'work'   => 'marketing',
			'faq'    => array(
				array( 'Which numbers do you report on?', 'Reach, engagement, followers, clicks, enquiries and, for paid campaigns, spend and cost per result.' ),
				array( 'Can you report on campaigns run by another agency?', 'Yes, as long as we have read access to the accounts.' ),
				array( 'Is reporting included in your marketing packages?', 'Yes. Every marketing package includes a performance report; it is also available on its own.' ),
				array( 'What happens on the strategy call?', 'We walk through the report with you and agree the changes for the next month.' ),
			),
		),
		'graphic-design'                    => array(
			'h1'     => 'Graphic Design Services{in}',
			'answer' => 'The right mix of colour, shape and words defines your brand. Tresundios designs logos, brand identities, illustrations, print and social graphics for {clients}, from a first logo at {from:ds-logo-basic|basic-logo}{tax} to a full brand identity kit, and gets it right the first time.',
			'items'  => array( 'Logo and brand identity', 'Art and illustration', 'Print design: flyers, posters, menus and packaging', 'Social media and marketing graphics', 'Business cards, letterheads and presentation decks' ),
			'prices' => array(),
			'work'   => 'branding',
			'faq'    => array(
				array( 'How much does a logo cost?', 'A basic logo with 2 concepts and 2 revisions is {price:ds-logo-basic|basic-logo}. A professional logo with brand colours and fonts is {price:ds-logo-pro|professional-logo}, and a full brand identity kit is {price:ds-brand-kit|brand-identity}{tax}.' ),
				array( 'What files do I receive?', 'Print-ready and web-ready files for every design, including vector logo files you can hand to any printer or agency.' ),
				array( 'How many revisions are included?', 'The basic logo includes 2 revisions. For other work, revision rounds are agreed in the proposal before we start.' ),
				array( 'Can you design for print and packaging?', 'Yes. We design flyers, posters, menus, labels and packaging, print-ready. Print production is billed separately at actual cost.' ),
			),
		),
		'logo-brand-identity'               => array(
			'h1'     => 'Logo & Brand Identity Design{in}',
			'answer' => 'A logo and brand identity give every touchpoint the same look, from your shopfront to Instagram. We design logos, colour palettes, typography and brand kits for new and growing businesses. A basic logo starts at {from:ds-logo-basic|basic-logo}{tax}; a full brand identity kit is {from:ds-brand-kit|brand-identity}.',
			'items'  => array( 'Logo concepts and revisions', 'Brand colours and typefaces', 'Logo variations for light and dark backgrounds', 'Brand guidelines', 'Files for print, web and social media' ),
			'prices' => array( 'ds-logo-basic|basic-logo', 'ds-logo-pro|professional-logo', 'ds-brand-kit|brand-identity' ),
			'work'   => 'branding',
			'faq'    => array(
				array( 'How long does a logo take?', 'Timelines depend on the package and how quickly feedback comes back; we agree dates in the proposal before starting.' ),
				array( 'What is in the full brand identity kit?', 'A complete visual identity: logo system, colours, typography, core brand assets and guidelines you can hand to any printer, designer or agency.' ),
				array( 'Do I own the logo?', 'Yes. Once the project is paid, you own the final logo and brand files.' ),
				array( 'Can you refresh an existing logo?', 'Yes. Tell us what you want to keep and we will propose a refresh rather than a full redesign.' ),
			),
		),
		'art-illustration'                  => array(
			'h1'     => 'Art & Illustration{in}',
			'answer' => 'Custom illustration gives your brand, packaging and social media a look nobody else has. We create illustrations, icons and artwork that match your brand style, for print and digital use. Every illustration project is quoted after we understand the brief, the style and where the artwork will be used.',
			'items'  => array( 'Custom illustrations and artwork', 'Icons and spot illustrations', 'Illustrated social media content', 'Artwork for packaging and print' ),
			'prices' => array(),
			'work'   => 'branding',
			'faq'    => array(
				array( 'How is illustration priced?', 'Illustration is quoted per project, based on the number of pieces, the level of detail and how the artwork will be used.' ),
				array( 'Can illustrations match my existing brand?', 'Yes. We work from your brand colours and style so new artwork fits what you already have.' ),
				array( 'What file formats do I get?', 'Vector files where the style allows, plus high-resolution files for print and web.' ),
				array( 'Can I use the artwork on products I sell?', 'Usage rights are agreed in the proposal. Tell us where the artwork will appear so we can quote correctly.' ),
			),
		),
		'print-design'                      => array(
			'h1'     => 'Print Design: Flyers, Posters & Packaging{in}',
			'answer' => 'We design print-ready flyers, posters, menus, labels, packaging and business stationery that stand out on the shelf and the street. Flyer, poster and menu design starts at {from:ds-print|print-design}{tax}, and product labels or packaging start at {from:ds-packaging|packaging-design}. Printing is billed at actual cost.',
			'items'  => array( 'Flyers, posters and menus', 'Product labels and packaging', 'Business cards, letterheads and WhatsApp catalogues', 'Print-ready artwork with bleed and colour checks' ),
			'prices' => array( 'ds-print|print-design', 'ds-packaging|packaging-design', 'ds-stationery|business-stationery' ),
			'work'   => 'branding',
			'faq'    => array(
				array( 'Do you handle printing?', 'We prepare print-ready files and can coordinate with a printer. Print production is billed separately at actual cost.' ),
				array( 'How much does a flyer or menu design cost?', 'Flyer, poster or menu design is {price:ds-print|print-design}{tax}, depending on size and content.' ),
				array( 'Can you design packaging for a new product?', 'Yes. Product label or packaging design is {price:ds-packaging|packaging-design}{tax}.' ),
				array( 'What is in the stationery set?', 'A business card, a letterhead and a WhatsApp catalogue designed to look consistent, for {price:ds-stationery|business-stationery}{tax}.' ),
			),
		),
		'social-marketing-graphics'         => array(
			'h1'     => 'Social Media & Marketing Graphics',
			'answer' => 'Ready-to-post graphics keep your social media and campaigns on brand without hiring an in-house designer. We design social media creatives, ad graphics and presentation decks to your brand. Ten social media creatives cost {price:ds-social-10|social-creatives}{tax}; a presentation deck of up to 15 slides is {price:ds-deck|presentation-deck}.',
			'items'  => array( 'Social media creatives and templates', 'Ad graphics for Meta and Google', 'Presentation and pitch decks', 'Campaign banners and email graphics' ),
			'prices' => array( 'ds-social-10|social-creatives', 'ds-deck|presentation-deck' ),
			'work'   => 'branding',
			'faq'    => array(
				array( 'Do you provide editable templates?', 'Yes, we can deliver templates your team can update. Tell us which tool you use.' ),
				array( 'How many designs are in a set?', 'The standard set is 10 social media creatives. Larger sets are quoted on request.' ),
				array( 'Can you design a pitch deck?', 'Yes. A presentation deck of up to 15 slides is {price:ds-deck|presentation-deck}{tax}.' ),
				array( 'Do you write the copy as well?', 'We can write short headlines and captions for the designs; longer copy is quoted separately.' ),
			),
		),
		'event-management'                  => array(
			'h1'     => 'Event Management{in}',
			'answer' => 'Tresundios plans and runs brand launches, corporate events, parties, surprise celebrations and sports events of every size, end to end and with a personal touch, then promotes them online. Event management starts at {from:ev-intimate|intimate-event}{tax} for up to 50 guests, with day-of coordination available.',
			'items'  => array( 'Brand launches and corporate events', 'Parties and surprise celebrations', 'Sports events and community drives', 'Vendor management and day-of coordination', 'Event promotion and coverage' ),
			'prices' => array(),
			'work'   => 'event',
			'faq'    => array(
				array( 'How much does event management cost?', 'Intimate events for up to 50 guests are {price:ev-intimate|intimate-event}, corporate events for up to 150 guests are {price:ev-corporate|corporate-event} and brand launches are {price:ev-launch|brand-launch-event}{tax}. Large events are 12% of the total event budget. Vendor costs are billed at actual cost.' ),
				array( 'Do you only coordinate on the day?', 'We can. Day-of coordination is {price:ev-day-of|day-of-coordination}{tax} if you have already planned the event.' ),
				array( 'Can you promote the event online?', 'Yes. We combine event management with social media promotion and coverage before, during and after the event.' ),
				array( 'How early should I contact you?', 'As early as possible, especially for venues and large events. Book a free call and we will tell you what is realistic for your date.' ),
			),
		),
		'brand-launches-corporate-events'   => array(
			'h1'     => 'Brand Launch & Corporate Event Management{in}',
			'answer' => 'A launch or corporate event is your brand in real life. We plan conferences, team events, client evenings and brand launches end to end: venue, vendors, schedule and promotion. Corporate event management for up to 150 guests is {price:ev-corporate|corporate-event}{tax}; brand launch events are {price:ev-launch|brand-launch-event}.',
			'items'  => array( 'Event concept and run-of-show', 'Venue and vendor coordination', 'Branding, signage and stage design', 'Guest management', 'Launch promotion on social media' ),
			'prices' => array( 'ev-corporate|corporate-event', 'ev-launch|brand-launch-event', 'ev-large|large-events' ),
			'work'   => 'event',
			'faq'    => array(
				array( 'What does a brand launch event include?', 'Planning built around your brand, audience and message: concept, venue, vendors, branding, guest management and launch promotion.' ),
				array( 'How are large events priced?', 'Large events are priced at 12% of the total event budget, with a minimum of {from:ev-large|large-events}.' ),
				array( 'Are vendor costs included?', 'No. Venue, catering, production and other vendor costs are billed separately at actual cost.' ),
				array( 'Can you design the event branding too?', 'Yes. Our design team can produce invitations, signage, backdrops and social graphics for the event.' ),
			),
		),
		'parties-surprise-celebrations'     => array(
			'h1'     => 'Party & Surprise Celebration Planning{in}',
			'answer' => 'Birthdays, anniversaries and surprise celebrations planned with a personal touch, so you can enjoy the day. We handle the theme, décor, vendors and timing for intimate gatherings and bigger parties, and keep surprises secret until the moment. Intimate event management for up to 50 guests is {price:ev-intimate|intimate-event}{tax}.',
			'items'  => array( 'Theme and décor planning', 'Venue and vendor coordination', 'Surprise planning and timing', 'Day-of coordination' ),
			'prices' => array( 'ev-intimate|intimate-event', 'ev-day-of|day-of-coordination' ),
			'work'   => 'event',
			'faq'    => array(
				array( 'Can you plan a surprise without the guest of honour finding out?', 'Yes. We coordinate directly with you and the vendors so the plan stays a surprise.' ),
				array( 'What does intimate event management include?', 'Planning and coordination for gatherings of up to 50 guests, done with a personal touch.' ),
				array( 'Can you just run the day?', 'Yes. Day-of coordination is {price:ev-day-of|day-of-coordination}{tax}.' ),
				array( 'Are décor and catering included in the price?', 'No. Vendor costs such as décor and catering are billed separately at actual cost.' ),
			),
		),
		'sports-community-events'           => array(
			'h1'     => 'Sports Events & Community Drives{in}',
			'answer' => 'Walkathons, tournaments and community drives need planning, registrations and steady promotion to fill up. We organise sports events and community drives end to end and can add a participant app. We built the TEF Kuwait Walkathon app for a 30-day community walking challenge. Pricing is quoted per event.',
			'items'  => array( 'Event planning and logistics', 'Registrations and participant communication', 'Promotion and community outreach', 'Optional participant app with leaderboards' ),
			'prices' => array(),
			'work'   => 'mobile-app',
			'faq'    => array(
				array( 'Can you build an app for our event?', 'Yes. We built a Flutter app with step tracking and live leaderboards for the TEF Kuwait Walkathon, a 30-day walking challenge.' ),
				array( 'How are sports events priced?', 'They are quoted per event based on size, duration and what you need us to manage. Large events are priced at 12% of the total event budget.' ),
				array( 'Do you handle registrations?', 'Yes, through a simple online registration or, for larger events, a dedicated app.' ),
				array( 'Can you promote the event?', 'Yes. We run social media promotion and coverage before, during and after the event.' ),
			),
		),
		'event-promotion-coverage'          => array(
			'h1'     => 'Event Promotion & Coverage',
			'answer' => 'An event only works if people know about it. We promote your event online before the day, cover it live on social media and turn it into content afterwards, so one event keeps working for your brand. Event promotion and coverage is quoted per event or added to an event management package.',
			'items'  => array( 'Pre-event social media campaign', 'Event graphics and invitations', 'Live social coverage on the day', 'Photo and video highlights after the event' ),
			'prices' => array(),
			'work'   => 'event',
			'faq'    => array(
				array( 'Can you promote an event you are not managing?', 'Yes. Promotion and coverage can be booked on its own.' ),
				array( 'Do you provide photography and video?', 'Yes. Coverage can include photography and short-form video for social media.' ),
				array( 'How far ahead should promotion start?', 'Ideally a few weeks before the event, longer for ticketed or large events.' ),
				array( 'Can you run paid ads for the event?', 'Yes. Ads setup and management are available; ad spend is billed at actual cost.' ),
			),
		),
		'websites-and-apps'                 => array(
			'h1'     => 'Website & App Development{in}',
			'answer' => 'Tresundios builds fast, secure, mobile-first websites, e-commerce stores, Flutter apps for iOS and Android and custom software that turn the attention you earn into enquiries and sales. Websites start at {from:wb-landing|landing-page}{tax} for a one-page site, and we look after hosting and updates after launch.',
			'items'  => array( 'Business websites and landing pages', 'E-commerce stores', 'Flutter apps for iOS and Android', 'Custom web applications, portals and APIs', 'Hosting, care and updates' ),
			'prices' => array(),
			'work'   => 'web',
			'faq'    => array(
				array( 'How much does a website cost?', 'A one-page landing page is {price:wb-landing|landing-page}, a 5–7 page business website is {price:wb-business|business-website} and an e-commerce website with up to 50 products is {price:wb-ecom|ecommerce-website}{tax}. Larger builds are quoted after we understand your requirements.' ),
				array( 'How long does a website take?', 'Our recent projects took from 6 weeks for a redesign to 3 months for an e-commerce store and 5 months for a larger web platform. You get a timeline in the proposal.' ),
				array( 'Which technologies do you use?', 'WordPress, Laravel and PHP for websites, Flutter and Dart for mobile apps, and custom code where a project needs it.' ),
				array( 'Can I update the website myself?', 'Yes. Our professional websites include a CMS so you can edit content yourself.' ),
			),
		),
		'websites-and-ecommerce'            => array(
			'h1'     => 'Website & E-commerce Development{in}',
			'answer' => 'We design and build mobile-friendly business websites, booking sites and online stores with secure checkout, set up to be found on Google. A one-page landing page is {price:wb-landing|landing-page}{tax}, a business website is {price:wb-business|business-website} and an e-commerce store with up to 50 products is {price:wb-ecom|ecommerce-website}.',
			'items'  => array( 'Landing pages and business websites', 'Booking and appointment websites', 'E-commerce stores with payment gateways', 'CMS so you can edit content', 'Basic on-page SEO and analytics setup' ),
			'prices' => array( 'wb-landing|landing-page', 'wb-business|business-website', 'wb-professional|professional-website', 'wb-booking|booking-website', 'wb-ecom|ecommerce-website', 'wb-ecom-adv|advanced-ecommerce' ),
			'work'   => 'e-commerce',
			'faq'    => array(
				array( 'Which payment gateways can you integrate?', 'We integrate the payment gateway that suits your market and bank; tell us your preferred provider and we will confirm.' ),
				array( 'Can customers book appointments online?', 'Yes. A booking or appointment website is {price:wb-booking|booking-website}{tax}, suited to clinics, salons and consultants.' ),
				array( 'How many products can my store have?', 'The standard e-commerce website covers up to 50 products. Advanced e-commerce for 50 to 200 products with integrations is {price:wb-ecom-adv|advanced-ecommerce}{tax}.' ),
				array( 'Are hosting and domain included?', 'Hosting and domains are billed separately at actual cost. Our website care plan covers updates, security and support.' ),
			),
		),
		'flutter-app-development'           => array(
			'h1'     => 'Flutter App Development for iOS & Android',
			'answer' => 'Flutter lets us build one app that runs on both iOS and Android, which keeps cost and timelines down for SMEs. We build Flutter MVPs and production apps, like the TEF Kuwait Walkathon app with step tracking and live leaderboards. A Flutter app MVP starts at {from:wb-flutter|flutter-mvp}{tax}.',
			'items'  => array( 'Product scoping and MVP planning', 'UI design for iOS and Android', 'Flutter app development', 'Backend and API integration', 'App Store and Google Play release' ),
			'prices' => array( 'wb-flutter|flutter-mvp' ),
			'work'   => 'mobile-app',
			'faq'    => array(
				array( 'Why Flutter instead of two native apps?', 'One Flutter codebase works on iOS and Android, so you pay for one build and get updates on both platforms at the same time.' ),
				array( 'How much does a Flutter app cost?', 'A Flutter app MVP is {price:wb-flutter|flutter-mvp}{tax}. The final quote depends on features and integrations.' ),
				array( 'How long does an app take?', 'The TEF Kuwait Walkathon app took 4 months. We agree the scope and timeline in the proposal.' ),
				array( 'Do you publish the app to the stores?', 'Yes. We prepare the store listings and handle submission to the App Store and Google Play.' ),
			),
		),
		'custom-software-apis'              => array(
			'h1'     => 'Custom Software & API Development',
			'answer' => 'When off-the-shelf tools do not fit, we build custom web applications, portals and APIs around your workflow, from student application systems to product catalogues. Custom web applications and portals start at {from:wb-custom|custom-web-app}{tax} and get a fixed quote after a discovery session where we map your requirements and workflow.',
			'items'  => array( 'Requirements and workflow mapping', 'Custom web applications and portals', 'APIs and third-party integrations', 'Admin dashboards and reporting' ),
			'prices' => array( 'wb-custom|custom-web-app' ),
			'work'   => 'web',
			'faq'    => array(
				array( 'What kind of custom software do you build?', 'Web applications, client and student portals, internal tools, APIs and integrations between the systems you already use.' ),
				array( 'How is custom software priced?', 'Custom web applications and portals are {price:wb-custom|custom-web-app}{tax}. We send a fixed proposal after a discovery session.' ),
				array( 'Who owns the code?', 'Ownership and licensing are set out in the proposal; for bespoke builds the client normally owns the delivered code.' ),
				array( 'Do you provide support after launch?', 'Yes. Ongoing support and updates can be arranged after launch.' ),
			),
		),
		'hosting-and-care'                  => array(
			'h1'     => 'Website Hosting, Care & Updates',
			'answer' => 'A website needs updates, backups and security fixes to stay fast and safe. Our website care plan keeps your site maintained, patched and supported every month, so problems are fixed before customers notice. Website care costs {price:wb-care|website-care}{tax}, or a lower yearly price.',
			'items'  => array( 'Software and plugin updates', 'Security monitoring and backups', 'Small content changes', 'Uptime checks and support' ),
			'prices' => array( 'wb-care|website-care' ),
			'work'   => 'web',
			'faq'    => array(
				array( 'What does the care plan cost?', 'Website care is {price:wb-care|website-care}{tax}. It is also available as a yearly plan.' ),
				array( 'Can you look after a website you did not build?', 'Usually yes. We review the site first and tell you if anything needs fixing before we take it on.' ),
				array( 'Is hosting included?', 'Hosting is billed separately at actual cost. We can set up and manage hosting for you.' ),
				array( 'How quickly do you respond to problems?', 'Urgent issues are handled first; response times are set out in the care plan.' ),
			),
		),
		'product-photography'               => array(
			'h1'     => 'Product Photography{in}',
			'answer' => 'Clean, consistent product photos sell more, on your store, catalogue and social media. We shoot product, lifestyle and on-location photography and short product videos, with editing included, at our studio or at your premises. A shoot of 10 products with 2 angles each is {price:ph-shoot-10|product-shoot-10}{tax}, and larger catalogue shoots are available.',
			'items'  => array( 'Product shoots with consistent lighting', 'Lifestyle and styled shoots', 'On-location half-day and full-day shoots', 'Product videos and reels', 'Edited images ready for web and print' ),
			'prices' => array(),
			'work'   => 'e-commerce',
			'faq'    => array(
				array( 'How much does product photography cost?', 'A shoot of 10 products with 2 angles each is {price:ph-shoot-10|product-shoot-10}, 25 products is {price:ph-shoot-25|product-shoot-25} and a lifestyle shoot of 10 images is {price:ph-lifestyle|lifestyle-shoot}{tax}.' ),
				array( 'Can you shoot at our premises?', 'Yes. An on-location half-day shoot is {price:ph-half-day|half-day-shoot} and a full day with basic editing is {price:ph-full-day|full-day-shoot}{tax}.' ),
				array( 'Do you shoot video?', 'Yes. A 30 to 45 second product video or reel is {price:ph-video|product-video}{tax}.' ),
				array( 'Are the photos edited?', 'Yes. Images are edited and delivered ready for your website, marketplace listings and social media. Additional edited images are {price:ph-extra-image|additional-image} each.' ),
			),
		),
		'devops'                            => array(
			'h1'     => 'DevOps & Cloud Services',
			'answer' => 'Tresundios sets up and runs the infrastructure behind your website and apps: cloud servers, CI/CD pipelines, Docker containers, monitoring and backups. You release faster and stop worrying about downtime. DevOps work starts at {from:devops-monitoring|devops-monitoring}{tax} for monitoring and alerts, with a managed retainer from {from:devops-retainer|devops-retainer} a month.',
			'items'  => array( 'CI/CD pipeline setup', 'Docker containerisation and Kubernetes', 'Cloud server setup, hardening and migration', 'Infrastructure as code with Terraform', 'Monitoring, logging, backups and disaster recovery', 'Cloud cost optimisation' ),
			'prices' => array(),
			'work'   => 'web',
			'faq'    => array(
				array( 'Which cloud providers do you work with?', 'AWS, Google Cloud, DigitalOcean and Hetzner. We recommend the one that fits your budget and stack.' ),
				array( 'How much does a CI/CD pipeline cost?', 'CI/CD pipeline setup is {price:devops-cicd|devops-cicd}{tax}, covering build, test and deploy stages for staging and production.' ),
				array( 'Can you move my website off shared hosting?', 'Yes. Migration to the cloud is {price:devops-migration|devops-migration}{tax}, with a dry run, data transfer and a rollback plan.' ),
				array( 'Do you offer ongoing DevOps support?', 'Yes. The managed DevOps retainer is {price:devops-retainer|devops-retainer}{tax} and includes patching, monitoring, incident response and up to 8 hours of changes a month.' ),
			),
		),
	),

	/*
	 * Pages: slug => [ title, lede (excerpt), sections, faq ]. Sections: pattern slugs or "prose:<html>", "map", "faq".
	 */
	'pages'    => array(
		'pricing'        => array(
			'title'    => 'Pricing',
			'lede'     => 'Transparent prices in {cur}{tax} for digital marketing, design, product photography, events, websites, apps and DevOps. Pick individual services or a bundled package; custom quotes follow a free call.',
			'sections' => array( 'pricing', 'faq', 'cta-book-call' ),
			'faq'      => array(
				array( 'Are your prices fixed?', 'Prices for individual services and packages are fixed as listed. Website, app and event prices are starting prices; the final quote depends on scope, features, content and integrations.' ),
				array( 'What is not included in the price?', 'Ad spend, third-party tools, hosting, domains, print production and event vendor costs are billed separately at actual cost.' ),
				array( 'Which currency do you bill in?', 'This site lists prices in {cur}{tax}.' ),
				array( 'Can I combine services?', 'Yes. Launch packages combine brand, website, photography and marketing in one plan, or we can quote any combination after a free call.' ),
				array( 'How do I get a quote?', 'Click Get a Quote on any price, send an enquiry or book a free call. We reply within 24 hours.' ),
			),
		),
		'process'        => array(
			'title'    => 'How we work',
			'lede'     => 'From first call to launch day: discover, plan and design, build and produce, launch and grow, with one team and one point of contact throughout.',
			'sections' => array( 'process', 'faq', 'cta-book-call' ),
			'faq'      => array(
				array( 'What happens on the free consultation?', 'We learn about your business, audience, competitors and budget. You leave the call with clear next steps, whether or not you hire us.' ),
				array( 'Will I see designs before you build?', 'Yes. After the proposal is approved we share brand, content and UI designs for your approval before anything is built.' ),
				array( 'How will I know how the project is going?', 'You get weekly updates during the build and a single point of contact throughout.' ),
				array( 'What happens after launch?', 'We track reach and conversions and keep improving, with monthly reports.' ),
			),
		),
		'about'          => array(
			'title'    => 'About Tresundios',
			'lede'     => 'Tresundios Software Products and Services Private Limited is a DPIIT-recognised startup, incorporated in Chennai on 18 November 2024, building marketing, design, events and technology for small and medium businesses.',
			'sections' => array( 'about', 'testimonial', 'trust', 'cta-book-call' ),
		),
		'contact'        => array(
			'title'    => 'Contact',
			'lede'     => 'Book a free consultation, send a project enquiry or visit our Chennai office. We reply within 24 hours.',
			'sections' => array( 'contact', 'map', 'faq' ),
			'faq'      => $tresundios_general_faq,
		),
		'blog'           => array(
			'title'    => 'Blog',
			'lede'     => 'Articles on marketing, design, events, websites, apps and DevOps for growing businesses{in}.',
			'sections' => array(),
		),
		'privacy-policy' => array(
			'title'    => 'Privacy policy',
			'lede'     => 'How Tresundios collects and uses personal information on this website.',
			'sections' => array(
				'prose:<!-- TODO: content needed --><p class="todo">TODO: legal review needed. This draft only describes what the website itself does; have it reviewed against the Digital Personal Data Protection Act, 2023 (and UK GDPR for .com visitors) before launch.</p><h2>Who we are</h2><p>{legal_name}, {address}. Contact: {email}.</p><h2>What we collect</h2><p>When you send a project enquiry we receive your name, business name, email or phone number, the service and budget you choose and your message. We use this only to reply to your enquiry and prepare a quote.</p><h2>Booking a call</h2><p>The Book a free call button opens Google Calendar appointment scheduling, which is operated by Google under its own privacy policy.</p><h2>Maps</h2><p>The map on our contact page loads from Google Maps only when you choose to show it.</p><h2>Cookies and analytics</h2><!-- TODO: content needed --><p class="todo">TODO: list any analytics or tracking tools used on the live site.</p><h2>Your rights</h2><!-- TODO: content needed --><p class="todo">TODO: retention period, access, correction and deletion requests.</p>',
			),
		),
		'terms'          => array(
			'title'    => 'Terms',
			'lede'     => 'Terms for using this website and working with Tresundios.',
			'sections' => array(
				'prose:<!-- TODO: content needed --><p class="todo">TODO: legal review needed. Add your terms of service (quotes and proposals, payment terms, revisions, intellectual property, cancellations, liability and governing law).</p><h2>Prices</h2><p>Prices on this website are in {cur}{tax}. Website, app and event prices are starting prices; the final quotation depends on scope. Ad spend, third-party tools, hosting, domains, print production and event vendor costs are billed separately at actual cost.</p><h2>Company details</h2><p>{legal_name}. CIN {cin}. GSTIN {gstin}.</p>',
			),
		),
	),
);
