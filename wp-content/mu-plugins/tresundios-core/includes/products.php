<?php
/**
 * Products sold by Tresundios (India site): DentalPin India.
 *
 * One source of truth for the product URLs and the DentalPin FAQ. The theme patterns render
 * the FAQ from tresundios_dentalpin_faq() and schema.php builds FAQPage from the same array.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * DentalPin India links.
 *
 * @return array<string, string>
 */
function tresundios_dentalpin() {
	return apply_filters(
		'tresundios_dentalpin',
		array(
			'name'    => 'DentalPin India',
			'url'     => 'https://www.dentalpin.in/',
			'demo'    => 'https://www.dentalpin.in/demo.html',
			'pricing' => 'https://www.dentalpin.in/pricing.html',
			'partner' => 'https://www.dentalpin.in/contact.html#partners',
			'path'    => 'products/dentalpin',
		)
	);
}

/**
 * Whether the current request is the /products/dentalpin/ page.
 *
 * @return bool
 */
function tresundios_is_dentalpin_page() {
	if ( ! is_page() ) {
		return false;
	}
	return tresundios_dentalpin()['path'] === get_page_uri( get_queried_object_id() );
}

/**
 * DentalPin FAQ (question => answer, plain text).
 *
 * @return array<int, array{question: string, answer: string}>
 */
function tresundios_dentalpin_faq() {
	return array(
		array(
			'question' => __( 'Is DentalPin India really free?', 'tresundios' ),
			'answer'   => __( 'Yes. The core software is open source (source-available under BSL 1.1) and costs ₹0 per chair or dentist. You can host it yourself. You pay only if you want Tresundios to set it up, host it, migrate your data, train your team or support you.', 'tresundios' ),
		),
		array(
			'question' => __( 'Where is my data hosted?', 'tresundios' ),
			'answer'   => __( 'Your choice. Self-host it on your own server, or let Tresundios run managed hosting for you in an India-region data centre.', 'tresundios' ),
		),
		array(
			'question' => __( 'Can you migrate from my current software?', 'tresundios' ),
			'answer'   => __( 'Yes. Data migration is a paid Tresundios service. Share an export from your current software and we will confirm what can be moved before we quote.', 'tresundios' ),
		),
		array(
			'question' => __( 'Do you offer training?', 'tresundios' ),
			'answer'   => __( 'Yes. We train dentists and front-desk staff on appointments, billing and reminders, online or at your clinic.', 'tresundios' ),
		),
		array(
			'question' => __( 'Is DentalPin India available outside India?', 'tresundios' ),
			'answer'   => __( 'No. Tresundios holds the exclusive licence for India, so our DentalPin services cover clinics in India only.', 'tresundios' ),
		),
	);
}
