<?php
/**
 * Custom fields, registered with register_post_meta so they are typed and available in the REST API / editor.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions: post type => [ meta key => definition ].
 * Definition keys: type (string|number|integer|boolean|array), items (for arrays), description.
 *
 * @return array<string, array<string, array<string, mixed>>>
 */
function tresundios_meta_fields() {
	$string_list = array(
		'type'  => 'array',
		'items' => array( 'type' => 'string' ),
	);
	$faq_items   = array(
		'type'        => 'array',
		'items'       => array(
			'type'       => 'object',
			'properties' => array(
				'question' => array( 'type' => 'string' ),
				'answer'   => array( 'type' => 'string' ),
			),
		),
		'description' => 'Visible FAQs; also the source of FAQPage schema.',
	);
	$results     = array(
		'type'        => 'array',
		'items'       => array(
			'type'       => 'object',
			'properties' => array(
				'label' => array( 'type' => 'string' ),
				'text'  => array( 'type' => 'string' ),
			),
		),
		'description' => 'Results / metrics as label + sentence. Never invent numbers.',
	);

	// Shared by every content type that has a counterpart on the other site.
	$shared = array(
		'translation_key' => array(
			'type'        => 'string',
			'description' => 'Same value on both sites pairs the pages for hreflang.',
		),
		'faq_items'       => $faq_items,
	);

	return array(
		'page'            => $shared,
		'post'            => $shared,
		'service'         => array_merge(
			$shared,
			array(
				'price'        => array(
					'type'        => 'number',
					'description' => 'Starting price in the site currency.',
				),
				'currency'     => array(
					'type'        => 'string',
					'description' => 'ISO code; empty = site currency.',
				),
				'billing_type' => array( 'type' => 'string' ),
				'price_note'   => array( 'type' => 'string' ),
				'duration'     => array( 'type' => 'string' ),
				'deliverables' => $string_list,
				'h1'           => array(
					'type'        => 'string',
					'description' => 'Page heading; the post title stays short for menus and cards.',
				),
				'nav_label'    => array(
					'type'        => 'string',
					'description' => 'Short name for menus and cards.',
				),
				'icon'         => array( 'type' => 'string' ),
			)
		),
		'case_study'      => array_merge(
			$shared,
			array(
				'client'     => array( 'type' => 'string' ),
				'industry'   => array( 'type' => 'string' ),
				'location'   => array(
					'type'        => 'string',
					'description' => 'Market, e.g. United Kingdom.',
				),
				'duration'   => array( 'type' => 'string' ),
				'challenge'  => array( 'type' => 'string' ),
				'solution'   => array( 'type' => 'string' ),
				'results'    => $results,
				'tech_stack' => $string_list,
				'live_url'   => array( 'type' => 'string' ),
				'legacy_url' => array(
					'type'        => 'string',
					'description' => 'Old URL to 301 to this page.',
				),
				'featured'   => array( 'type' => 'boolean' ),
				'focal'      => array(
					'type'        => 'string',
					'description' => 'CSS object-position for the cover crop.',
				),
				'card_tag'   => array( 'type' => 'string' ),
				'card_cat'   => array( 'type' => 'string' ),
				'card_meta'  => $string_list,
				'card_title' => array( 'type' => 'string' ),
			)
		),
		'event_portfolio' => array_merge(
			$shared,
			array(
				'client'         => array( 'type' => 'string' ),
				'industry'       => array( 'type' => 'string' ),
				'location'       => array(
					'type'        => 'string',
					'description' => 'Venue and city. Event schema is only output when this and the date are set.',
				),
				'event_date'     => array(
					'type'        => 'string',
					'description' => 'Y-m-d',
				),
				'event_end_date' => array( 'type' => 'string' ),
				'results'        => $results,
			)
		),
		'resource'        => array_merge(
			$shared,
			array(
				'download_url' => array(
					'type'        => 'string',
					'description' => 'PDF or file to download (whitepapers, templates).',
				),
				'reading_time' => array( 'type' => 'string' ),
				'service_slug' => array(
					'type'        => 'string',
					'description' => 'Related service, for links and cards.',
				),
			)
		),
		'price_item'      => array(
			'translation_key'   => $shared['translation_key'],
			'price'             => array( 'type' => 'number' ),
			'billing_type'      => array(
				'type'        => 'string',
				'description' => 'one-time | monthly | per-project | starting-from | custom',
			),
			'per'               => array(
				'type'        => 'string',
				'description' => 'Billing period after a starting-from price.',
			),
			'price_note'        => array( 'type' => 'string' ),
			'recurring_price'   => array( 'type' => 'number' ),
			'recurring_billing' => array( 'type' => 'string' ),
			'percent'           => array( 'type' => 'number' ),
			'percent_of'        => array( 'type' => 'string' ),
			'min_price'         => array( 'type' => 'number' ),
			'features'          => $string_list,
			'badge'             => array( 'type' => 'string' ),
			'enquiry_service'   => array(
				'type'        => 'string',
				'description' => 'Enquiry-form option the CTA preselects.',
			),
			'service_slug'      => array(
				'type'        => 'string',
				'description' => 'Service page this price belongs to (for teasers and Offer schema).',
			),
		),
	);
}

/**
 * Sanitise a meta value according to its definition.
 *
 * @param array<string, mixed> $def   Field definition.
 * @param mixed                $value Raw value.
 * @return mixed
 */
function tresundios_sanitize_meta_value( $def, $value ) {
	switch ( $def['type'] ) {
		case 'number':
			return is_numeric( $value ) ? (float) $value : 0;
		case 'integer':
			return (int) $value;
		case 'boolean':
			return (bool) $value;
		case 'array':
			if ( ! is_array( $value ) ) {
				return array();
			}
			$item = $def['items'];
			$out  = array();
			foreach ( $value as $row ) {
				if ( 'object' === $item['type'] ) {
					$clean = array();
					foreach ( array_keys( $item['properties'] ) as $prop ) {
						$clean[ $prop ] = isset( $row[ $prop ] ) ? wp_kses_post( $row[ $prop ] ) : '';
					}
					$out[] = $clean;
				} else {
					$out[] = sanitize_text_field( $row );
				}
			}
			return $out;
		default:
			return sanitize_textarea_field( (string) $value );
	}
}

/**
 * Register every field.
 */
function tresundios_register_meta() {
	foreach ( tresundios_meta_fields() as $post_type => $fields ) {
		foreach ( $fields as $key => $def ) {
			$args = array(
				'type'              => $def['type'],
				'single'            => true,
				'description'       => isset( $def['description'] ) ? $def['description'] : '',
				'sanitize_callback' => static function ( $value ) use ( $def ) {
					return tresundios_sanitize_meta_value( $def, $value );
				},
				'auth_callback'     => static function ( $allowed, $meta_key, $post_id ) {
					return current_user_can( 'edit_post', $post_id );
				},
				'show_in_rest'      => true,
			);

			if ( 'array' === $def['type'] ) {
				$args['default']      = array();
				$args['show_in_rest'] = array( 'schema' => array( 'items' => $def['items'] ) );
			}

			register_post_meta( $post_type, $key, $args );
		}
	}
}
add_action( 'init', 'tresundios_register_meta', 20 );
