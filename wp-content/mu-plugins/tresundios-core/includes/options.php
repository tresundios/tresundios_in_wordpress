<?php
/**
 * Per-site options (currency, tax note, regions, contact details).
 *
 * Every site stores one array in the `tresundios_settings` option. Defaults come from the
 * site's region, so `wp tresundios setup --region=india` is all a fresh site needs.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

const TRESUNDIOS_OPTION = 'tresundios_settings';

/**
 * Values shared by both sites.
 *
 * @return array<string, mixed>
 */
function tresundios_shared_defaults() {
	return array(
		'legal_name'     => 'Tresundios Software Products and Services Private Limited',
		'brand_name'     => 'Tresundios',
		'phone'          => '+91 98408 55590',
		'whatsapp'       => '+919840855590',
		// Both sites send enquiries to the same inbox; the subject says which site it came from.
		'enquiry_to'     => 'navis@tresundios.com',
		'booking_url'    => 'https://calendar.google.com/calendar/appointments/schedules/AcZssZ2GnvxGc6tvSy7rdz_-gh0JcEbZTitpzowP02UMh5_0KQVD-Ub3LToyQQm3baN4L1kc3a8hgTvC?gv=true',
		'street_address' => '4/5 (Old 9/5), School VII Street, Jafferkhanpet',
		'locality'       => 'Chennai',
		'region_name'    => 'Tamil Nadu',
		'postal_code'    => '600083',
		'country'        => 'IN',
		'geo_lat'        => '',
		'geo_lng'        => '',
		'gbp_url'        => '',
		'map_embed_url'  => '',
		'cin'            => 'U62099TN2024PTC174739',
		'gstin'          => '33AALCT3887E1Z2',
		'pan'            => 'AALCT3887E',
		'dpiit'          => 'DIPP197035',
		'dpiit_valid'    => '2034-11-17',
		'incorporated'   => '2024-11-18',
		'same_as'        => array( 'https://www.linkedin.com/company/tresundios/' ),
	);
}

/**
 * Region-specific defaults.
 *
 * @param string $region `global` (tresundios.com) or `india` (tresundios.in).
 * @return array<string, mixed>
 */
function tresundios_region_defaults( $region ) {
	if ( 'india' === $region ) {
		return array(
			'region'        => 'india',
			'site_label'    => 'tresundios.in',
			'hreflang'      => 'en-IN',
			'currency'      => 'INR',
			'tax_note'      => 'excl. GST',
			'contact_email' => 'navis@tresundios.in',
			'city_label'    => 'Chennai',
			'area_served'   => array( 'Chennai', 'Tamil Nadu', 'India' ),
		);
	}

	return array(
		'region'        => 'global',
		'site_label'    => 'tresundios.com',
		'hreflang'      => 'en',
		'currency'      => 'USD',
		'tax_note'      => '',
		'contact_email' => 'navis@tresundios.com',
		'city_label'    => '',
		'area_served'   => array( 'United Kingdom', 'Kuwait', 'United Arab Emirates', 'India', 'Worldwide' ),
	);
}

/**
 * All settings for the current site, merged over defaults.
 *
 * @return array<string, mixed>
 */
function tresundios_settings() {
	$saved  = get_option( TRESUNDIOS_OPTION, array() );
	$saved  = is_array( $saved ) ? $saved : array();
	$region = isset( $saved['region'] ) ? $saved['region'] : 'global';

	return array_merge( tresundios_shared_defaults(), tresundios_region_defaults( $region ), $saved );
}

/**
 * One setting for the current site.
 *
 * @param string $key      Setting key.
 * @param mixed  $fallback Value when the key is unknown.
 * @return mixed
 */
function tresundios_get( $key, $fallback = '' ) {
	$all = tresundios_settings();
	return array_key_exists( $key, $all ) ? $all[ $key ] : $fallback;
}

/**
 * Whether the current site is the India site.
 *
 * @return bool
 */
function tresundios_is_india() {
	return 'india' === tresundios_get( 'region' );
}

/**
 * Format an amount in the site currency: $1,080 or ₹1,50,000 (Indian grouping).
 *
 * @param float|int|string $amount   Amount.
 * @param string           $currency ISO code; defaults to the site currency.
 * @return string
 */
function tresundios_format_price( $amount, $currency = '' ) {
	$currency = $currency ? $currency : tresundios_get( 'currency' );
	$n        = (int) round( (float) $amount );

	if ( 'INR' === $currency ) {
		$s    = (string) abs( $n );
		$last = substr( $s, -3 );
		$rest = substr( $s, 0, -3 );
		if ( '' !== $rest ) {
			$rest = preg_replace( '/\B(?=(\d{2})+(?!\d))/', ',', $rest );
			$last = $rest . ',' . $last;
		}
		return '₹' . $last;
	}

	return '$' . number_format( $n );
}

/**
 * Sanitise a settings array coming from the admin form or the CLI.
 *
 * @param mixed $input Raw input.
 * @return array<string, mixed>
 */
function tresundios_sanitize_settings( $input ) {
	$input = is_array( $input ) ? $input : array();
	$out   = array();

	$text_keys = array( 'legal_name', 'brand_name', 'phone', 'whatsapp', 'street_address', 'locality', 'region_name', 'postal_code', 'country', 'geo_lat', 'geo_lng', 'cin', 'gstin', 'pan', 'dpiit', 'dpiit_valid', 'incorporated', 'site_label', 'hreflang', 'tax_note', 'city_label' );
	foreach ( $text_keys as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$out[ $key ] = sanitize_text_field( $input[ $key ] );
		}
	}

	foreach ( array( 'enquiry_to', 'contact_email' ) as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$out[ $key ] = sanitize_email( $input[ $key ] );
		}
	}

	foreach ( array( 'booking_url', 'gbp_url', 'map_embed_url' ) as $key ) {
		if ( isset( $input[ $key ] ) ) {
			$out[ $key ] = esc_url_raw( $input[ $key ] );
		}
	}

	if ( isset( $input['region'] ) ) {
		$out['region'] = in_array( $input['region'], array( 'global', 'india' ), true ) ? $input['region'] : 'global';
	}
	if ( isset( $input['currency'] ) ) {
		$out['currency'] = in_array( $input['currency'], array( 'USD', 'INR' ), true ) ? $input['currency'] : 'USD';
	}

	// Lists arrive as arrays (CLI) or one-per-line text (admin form).
	foreach ( array( 'area_served', 'same_as' ) as $key ) {
		if ( ! isset( $input[ $key ] ) ) {
			continue;
		}
		$list = is_array( $input[ $key ] ) ? $input[ $key ] : preg_split( '/\R/', (string) $input[ $key ] );
		$list = array_map( 'same_as' === $key ? 'esc_url_raw' : 'sanitize_text_field', array_map( 'trim', $list ) );

		$out[ $key ] = array_values( array_filter( $list ) );
	}

	return $out;
}

add_action(
	'init',
	static function () {
		register_setting(
			'tresundios',
			TRESUNDIOS_OPTION,
			array(
				'type'              => 'object',
				'sanitize_callback' => 'tresundios_sanitize_settings',
				'default'           => array(),
				'show_in_rest'      => false,
			)
		);
	}
);

/**
 * Front-end locale per region: en_IN on .in, en_GB on .com (the copy uses British/Indian spelling).
 * Drives <html lang> and og:locale. A filter, because WordPress only accepts WPLANG values that have
 * language packs installed; the admin keeps its own locale.
 */
add_filter(
	'locale',
	static function ( $locale ) {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return $locale;
		}
		$saved = get_option( TRESUNDIOS_OPTION, array() );
		return isset( $saved['region'] ) && 'india' === $saved['region'] ? 'en_IN' : 'en_GB';
	}
);
