<?php
/**
 * Project enquiry form handler.
 *
 * POST to admin-post.php?action=tresundios_enquiry. Works without JavaScript (redirects back with a
 * status) and with it (JSON when the request asks for it). Protection: nonce, honeypot, minimum fill
 * time, and per-IP rate limiting. Mail goes to the `enquiry_to` setting via wp_mail (SMTP configured
 * separately); the subject names the site so .com and .in enquiries are easy to tell apart.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

const TRESUNDIOS_ENQUIRY_MIN_GAP  = 20;  // Seconds between enquiries from one IP.
const TRESUNDIOS_ENQUIRY_HOURLY   = 5;   // Max enquiries per IP per hour.
const TRESUNDIOS_ENQUIRY_MIN_FILL = 3;   // Seconds a human needs at least to fill the form.

/**
 * Service options for the form.
 *
 * @return array<int, string>
 */
function tresundios_enquiry_services() {
	return array( 'Free website & growth audit', 'Websites & E-commerce', 'Performance Marketing', 'Brand & Creative', 'Social & Content Growth', 'Apps & Digital Automation', 'Event Management', 'Multiple Services' );
}

/**
 * Old service names (still stored on pricing items and in ?service= links) mapped to the 2026 services.
 *
 * @return array<string, string>
 */
function tresundios_enquiry_service_aliases() {
	return array(
		'Website / App'       => 'Websites & E-commerce',
		'Digital Marketing'   => 'Performance Marketing',
		'Graphic Design'      => 'Brand & Creative',
		'Product Photography' => 'Brand & Creative',
		'DevOps & Cloud'      => 'Apps & Digital Automation',
	);
}

/**
 * Normalise a service name: map legacy names, keep only known options.
 *
 * @param string $service Raw name.
 * @return string Known option or ''.
 */
function tresundios_enquiry_normalise_service( $service ) {
	$aliases = tresundios_enquiry_service_aliases();
	$service = isset( $aliases[ $service ] ) ? $aliases[ $service ] : $service;
	return in_array( $service, tresundios_enquiry_services(), true ) ? $service : '';
}

/**
 * Budget options in the site currency (kept from the original sites).
 *
 * @return array<int, string>
 */
function tresundios_enquiry_budgets() {
	if ( 'INR' === tresundios_get( 'currency' ) ) {
		return array( 'Not sure yet', 'Under ₹25,000', '₹25,000–₹75,000', '₹75,000–₹2,00,000', '₹2,00,000+' );
	}
	return array( 'Not sure yet', 'Under $285', '$285–$850', '$850–$2,275', '$2,275+' );
}

/**
 * Read one POST field as single-line or multi-line text.
 *
 * @param string $key       Field name.
 * @param int    $max       Max length.
 * @param bool   $multiline Keep newlines.
 * @return string
 */
function tresundios_enquiry_field( $key, $max, $multiline = false ) {
	// Nonce is verified in tresundios_handle_enquiry() before any field is read.
	$raw = isset( $_POST[ $key ] ) && is_string( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$val = $multiline ? sanitize_textarea_field( $raw ) : sanitize_text_field( $raw );
	return mb_substr( trim( $val ), 0, $max );
}

/**
 * Finish the request as JSON or as a redirect back to the form.
 *
 * @param bool   $ok      Success.
 * @param string $message Message for the visitor.
 * @param int    $status  HTTP status for JSON.
 */
function tresundios_enquiry_respond( $ok, $message, $status = 200 ) {
	$wants_json = isset( $_SERVER['HTTP_ACCEPT'] ) && false !== strpos( sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) ), 'application/json' );
	if ( $wants_json ) {
		wp_send_json(
			array(
				'ok'      => $ok,
				'message' => $message,
			),
			$status
		);
	}

	$back = wp_get_referer();
	$back = $back ? $back : home_url( '/contact/' );
	$back = remove_query_arg( array( 'enquiry', 'enquiry_msg' ), $back );
	$back = add_query_arg(
		array(
			'enquiry'     => $ok ? 'sent' : 'error',
			'enquiry_msg' => rawurlencode( $message ),
		),
		strtok( $back, '#' )
	) . '#enquiry';
	wp_safe_redirect( $back, 303 );
	exit;
}

/**
 * Handle a submission.
 */
function tresundios_handle_enquiry() {
	if ( ! isset( $_POST['_tr_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_tr_nonce'] ) ), 'tresundios_enquiry' ) ) {
		tresundios_enquiry_respond( false, __( 'Your session expired. Please reload the page and send your enquiry again.', 'tresundios' ), 403 );
	}

	// Honeypot and fill-time trap: pretend success so bots move on.
	$started = (int) tresundios_enquiry_field( '_tr_t', 20 );
	if ( '' !== tresundios_enquiry_field( 'website', 200 ) || ( $started && time() - $started < TRESUNDIOS_ENQUIRY_MIN_FILL ) ) {
		tresundios_enquiry_respond( true, __( 'Thank you. Your enquiry has been sent. We reply within 24 hours.', 'tresundios' ) );
	}

	$name     = tresundios_enquiry_field( 'name', 120 );
	$business = tresundios_enquiry_field( 'biz', 160 );
	$contact  = tresundios_enquiry_field( 'contact', 160 );
	$service  = tresundios_enquiry_field( 'svc', 60 );
	$budget   = tresundios_enquiry_field( 'budget', 60 );
	$message  = tresundios_enquiry_field( 'msg', 5000, true );

	if ( '' === $name || '' === $contact ) {
		tresundios_enquiry_respond( false, __( 'Please add your name and an email or phone number so we can reply.', 'tresundios' ), 422 );
	}
	$service = tresundios_enquiry_normalise_service( $service );
	$service = '' !== $service ? $service : '-';
	$budget  = in_array( $budget, tresundios_enquiry_budgets(), true ) ? $budget : 'Not sure yet';

	$ip_raw = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	$ip_key = 'tresundios_enq_' . substr( hash( 'sha256', $ip_raw . wp_salt( 'nonce' ) ), 0, 32 );
	$hits   = get_transient( $ip_key );
	$hits   = is_array( $hits ) ? array_filter(
		$hits,
		static function ( $t ) {
			return $t > time() - HOUR_IN_SECONDS;
		}
	) : array();
	if ( ( $hits && time() - max( $hits ) < TRESUNDIOS_ENQUIRY_MIN_GAP ) || count( $hits ) >= TRESUNDIOS_ENQUIRY_HOURLY ) {
		tresundios_enquiry_respond( false, __( 'Please wait a little before sending another enquiry.', 'tresundios' ), 429 );
	}

	$site    = tresundios_get( 'site_label' );
	$subject = sprintf( '[%s] New enquiry from %s', $site, $name );
	$body    = implode(
		"\n",
		array(
			sprintf( 'New project enquiry from %s (%s)', $site, home_url( '/' ) ),
			'',
			'Name: ' . $name,
			'Business: ' . ( '' !== $business ? $business : '-' ),
			'Contact: ' . $contact,
			'Service: ' . $service,
			sprintf( 'Budget (%s): %s', tresundios_get( 'currency' ), $budget ),
			'',
			'Message:',
			'' !== $message ? $message : '-',
			'',
			'---',
			'Page: ' . esc_url_raw( (string) wp_get_referer() ),
			'Sent: ' . gmdate( 'Y-m-d H:i' ) . ' UTC',
		)
	);

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $contact ) ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $contact . '>';
	}

	if ( ! wp_mail( tresundios_get( 'enquiry_to' ), $subject, $body, $headers ) ) {
		tresundios_enquiry_respond(
			false,
			sprintf(
				/* translators: 1: email address, 2: phone number. */
				__( 'We could not send your enquiry right now. Please email %1$s or WhatsApp %2$s.', 'tresundios' ),
				tresundios_get( 'contact_email' ),
				tresundios_get( 'phone' )
			),
			500
		);
	}

	$hits[] = time();
	set_transient( $ip_key, $hits, HOUR_IN_SECONDS );

	/* translators: %s: visitor name. */
	tresundios_enquiry_respond( true, sprintf( __( 'Thank you, %s. Your enquiry has been sent. We reply within 24 hours.', 'tresundios' ), $name ) );
}
add_action( 'admin_post_nopriv_tresundios_enquiry', 'tresundios_handle_enquiry' );
add_action( 'admin_post_tresundios_enquiry', 'tresundios_handle_enquiry' );

/**
 * Status shown above the form after a no-JS submission.
 *
 * @return array{ok: bool, message: string}|null
 */
function tresundios_enquiry_status() {
	// Display-only values from our own redirect.
	if ( empty( $_GET['enquiry'] ) || empty( $_GET['enquiry_msg'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return null;
	}
	return array(
		'ok'      => 'sent' === sanitize_key( $_GET['enquiry'] ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		'message' => sanitize_text_field( rawurldecode( wp_unslash( $_GET['enquiry_msg'] ) ) ), // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	);
}

/**
 * Hidden fields every enquiry form needs.
 */
function tresundios_enquiry_hidden_fields() {
	echo '<input type="hidden" name="action" value="tresundios_enquiry">';
	echo '<input type="hidden" name="_tr_t" value="' . esc_attr( (string) time() ) . '">';
	wp_nonce_field( 'tresundios_enquiry', '_tr_nonce', true );
}

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_register_script( 'tresundios-enquiry', TRESUNDIOS_CORE_URL . '/assets/enquiry.js', array(), TRESUNDIOS_CORE_VERSION, array( 'strategy' => 'defer' ) );
	}
);
