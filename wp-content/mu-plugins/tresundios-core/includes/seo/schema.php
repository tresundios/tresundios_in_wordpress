<?php
/**
 * JSON-LD. One @graph per page: with Rank Math active, our entities are merged into its graph through
 * `rank_math/json_ld` (Rank Math keeps WebSite, WebPage and BreadcrumbList); without it we print the
 * whole graph ourselves.
 *
 * Entities: ProfessionalService (the organisation, replaces Rank Math's publisher, same @id),
 * Service + Offers (service pages), FAQPage (only where the FAQ is visible on the page),
 * CreativeWork (case studies), Article (resources), Event (only with a real date and location).
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stable @id for the organisation (matches Rank Math's publisher @id).
 *
 * @return string
 */
function tresundios_org_id() {
	return home_url( '/#organization' );
}

/**
 * The areaServed value as schema.org places.
 *
 * @return array<int, array<string, string>>
 */
function tresundios_area_served() {
	$types = array(
		'Chennai'    => 'City',
		'Tamil Nadu' => 'State',
	);
	$out   = array();
	foreach ( (array) tresundios_get( 'area_served' ) as $name ) {
		if ( 'Worldwide' === $name ) {
			continue;
		}
		$out[] = array(
			'@type' => isset( $types[ $name ] ) ? $types[ $name ] : 'Country',
			'name'  => $name,
		);
	}
	return $out;
}

/**
 * The organisation as a ProfessionalService (a LocalBusiness subtype).
 *
 * @return array<string, mixed>
 */
function tresundios_schema_org() {
	$s    = tresundios_settings();
	$logo = get_theme_file_uri( 'assets/img/logo-light.png' );
	$org  = array(
		'@type'        => 'ProfessionalService',
		'@id'          => tresundios_org_id(),
		'name'         => $s['brand_name'],
		'legalName'    => $s['legal_name'],
		'url'          => home_url( '/' ),
		'logo'         => array(
			'@type'  => 'ImageObject',
			'@id'    => home_url( '/#logo' ),
			'url'    => $logo,
			'width'  => 422,
			'height' => 72,
		),
		'image'        => $logo,
		'email'        => $s['contact_email'],
		'telephone'    => $s['phone'],
		'address'      => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $s['street_address'],
			'addressLocality' => $s['locality'],
			'addressRegion'   => $s['region_name'],
			'postalCode'      => $s['postal_code'],
			'addressCountry'  => $s['country'],
		),
		'areaServed'   => tresundios_area_served(),
		'taxID'        => $s['gstin'],
		'identifier'   => array(
			array(
				'@type'      => 'PropertyValue',
				'propertyID' => 'CIN',
				'value'      => $s['cin'],
			),
			array(
				'@type'      => 'PropertyValue',
				'propertyID' => 'GSTIN',
				'value'      => $s['gstin'],
			),
			array(
				'@type'      => 'PropertyValue',
				'propertyID' => 'DPIIT',
				'value'      => $s['dpiit'],
			),
		),
		'foundingDate' => $s['incorporated'],
		'sameAs'       => array_values( (array) $s['same_as'] ),
		'contactPoint' => array(
			'@type'             => 'ContactPoint',
			'contactType'       => 'sales',
			'email'             => $s['contact_email'],
			'telephone'         => $s['phone'],
			'areaServed'        => wp_list_pluck( tresundios_area_served(), 'name' ),
			'availableLanguage' => array( 'English', 'Tamil' ),
		),
	);
	if ( $s['geo_lat'] && $s['geo_lng'] ) {
		$org['geo'] = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => (float) $s['geo_lat'],
			'longitude' => (float) $s['geo_lng'],
		);
	}
	if ( $s['gbp_url'] ) {
		$org['hasMap'] = $s['gbp_url'];
	}
	// The other regional site is the same organisation.
	foreach ( tresundios_sites() as $url => $site ) {
		if ( ! $site['local'] ) {
			$org['sameAs'][] = $url;
		}
	}
	return $org;
}

/**
 * Offer for a pricing entry (null for percentage-based prices).
 *
 * @param array<string, mixed> $item Pricing entry.
 * @return array<string, mixed>|null
 */
function tresundios_schema_offer( $item ) {
	if ( null === $item['price'] ) {
		return null;
	}
	$currency = tresundios_get( 'currency' );
	$spec     = array(
		'@type'                 => 'UnitPriceSpecification',
		'price'                 => $item['price'],
		'priceCurrency'         => $currency,
		'valueAddedTaxIncluded' => false,
	);
	$period   = $item['from'] ? get_post_meta( $item['post_id'], 'per', true ) : $item['billing_type'];
	if ( 'monthly' === $period ) {
		$spec['unitCode'] = 'MON';
		$spec['unitText'] = 'month';
	}
	if ( $item['from'] ) {
		$spec['minPrice'] = $item['price'];
	}
	return array(
		'@type'              => 'Offer',
		'name'               => $item['name'],
		'description'        => $item['description'],
		'price'              => $item['price'],
		'priceCurrency'      => $currency,
		'priceSpecification' => $spec,
		'url'                => home_url( '/pricing/#price-' . $item['id'] ),
		'seller'             => array( '@id' => tresundios_org_id() ),
	);
}

/**
 * FAQPage from the page's FAQ field, only when the FAQ is visible in the rendered content.
 *
 * @param WP_Post $post Post.
 * @param string  $url  Page URL.
 * @return array<string, mixed>|null
 */
function tresundios_schema_faq( $post, $url ) {
	$items = (array) get_post_meta( $post->ID, 'faq_items', true );
	if ( ! $items || false === strpos( $post->post_content, '<!-- wp:details' ) ) {
		return null;
	}
	$visible = array();
	foreach ( $items as $qa ) {
		// Only questions that are actually on the page.
		if ( ! empty( $qa['question'] ) && false !== strpos( $post->post_content, esc_html( $qa['question'] ) ) ) {
			$visible[] = array(
				'@type'          => 'Question',
				'name'           => $qa['question'],
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $qa['answer'] ),
				),
			);
		}
	}
	return $visible ? array(
		'@type'      => 'FAQPage',
		'@id'        => $url . '#faq',
		'url'        => $url,
		'mainEntity' => $visible,
		'inLanguage' => tresundios_get( 'hreflang' ),
	) : null;
}

/**
 * Our entities for the current request (organisation first).
 *
 * @return array<string, array<string, mixed>> Keyed for merging into Rank Math's graph.
 */
function tresundios_schema_entities() {
	$out = array( 'publisher' => tresundios_schema_org() );
	if ( ! is_singular() ) {
		return $out;
	}

	$post  = get_queried_object();
	$url   = get_permalink( $post );
	$page  = array( '@id' => $url . '#webpage' );
	$thumb = get_the_post_thumbnail_url( $post, 'large' );

	switch ( $post->post_type ) {
		case 'service':
			$prices = tresundios_service_prices( $post->post_name );
			$offers = $prices ? array_values( array_filter( array_map( 'tresundios_schema_offer', $prices['items'] ) ) ) : array();
			$parent = $post->post_parent ? get_post( $post->post_parent ) : null;
			$h1     = get_post_meta( $post->ID, 'h1', true );

			$service = array(
				'@type'            => 'Service',
				'@id'              => $url . '#service',
				'name'             => $h1 ? $h1 : $post->post_title,
				'serviceType'      => $parent ? $parent->post_title : $post->post_title,
				'description'      => $post->post_excerpt ? $post->post_excerpt : wp_trim_words( wp_strip_all_tags( $post->post_content ), 50 ),
				'url'              => $url,
				'provider'         => array( '@id' => tresundios_org_id() ),
				'areaServed'       => tresundios_area_served(),
				'mainEntityOfPage' => $page,
			);
			if ( $offers ) {
				$service['offers'] = $offers;
			}
			$out['tr_service'] = $service;
			break;

		case 'case_study':
			$c                    = tresundios_case_study( $post );
			$out['tr_case_study'] = array_filter(
				array(
					'@type'            => 'CreativeWork',
					'@id'              => $url . '#work',
					'name'             => $c['title'],
					'headline'         => $c['title'],
					'description'      => $c['excerpt'],
					'url'              => $url,
					'image'            => $thumb ? $thumb : null,
					'creator'          => array( '@id' => tresundios_org_id() ),
					'about'            => $c['client'] ? array(
						'@type' => 'Organization',
						'name'  => $c['client'],
					) : null,
					'genre'            => $c['industry'],
					'keywords'         => implode( ', ', $c['tech'] ),
					'locationCreated'  => array(
						'@type'   => 'Place',
						'address' => array(
							'@type'           => 'PostalAddress',
							'addressLocality' => tresundios_get( 'locality' ),
							'addressCountry'  => 'IN',
						),
					),
					'mainEntityOfPage' => $page,
				)
			);
			break;

		case 'resource':
			$download = get_post_meta( $post->ID, 'download_url', true );
			$article  = array(
				'@type'            => 'Article',
				'@id'              => $url . '#article',
				'headline'         => $post->post_title,
				'description'      => $post->post_excerpt,
				'url'              => $url,
				'datePublished'    => get_post_time( 'c', true, $post ),
				'dateModified'     => get_post_modified_time( 'c', true, $post ),
				'author'           => array( '@id' => tresundios_org_id() ),
				'publisher'        => array( '@id' => tresundios_org_id() ),
				'inLanguage'       => tresundios_get( 'hreflang' ),
				'mainEntityOfPage' => $page,
			);
			if ( $thumb ) {
				$article['image'] = $thumb;
			}
			if ( $download ) {
				$article['associatedMedia'] = array(
					'@type'          => 'MediaObject',
					'contentUrl'     => $download,
					'encodingFormat' => 'application/pdf',
				);
			}
			$out['tr_article'] = $article;
			break;

		case 'event_portfolio':
			$date     = get_post_meta( $post->ID, 'event_date', true );
			$location = get_post_meta( $post->ID, 'location', true );
			if ( $date && $location ) {
				$end             = get_post_meta( $post->ID, 'event_end_date', true );
				$out['tr_event'] = array(
					'@type'               => 'Event',
					'@id'                 => $url . '#event',
					'name'                => $post->post_title,
					'description'         => $post->post_excerpt,
					'url'                 => $url,
					'startDate'           => $date,
					'endDate'             => $end ? $end : $date,
					'eventStatus'         => 'https://schema.org/EventScheduled',
					'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
					'location'            => array(
						'@type'   => 'Place',
						'name'    => $location,
						'address' => $location,
					),
					'organizer'           => array( '@id' => tresundios_org_id() ),
					'image'               => $thumb ? $thumb : null,
				);
				$out['tr_event'] = array_filter( $out['tr_event'] );
			}
			break;
	}

	$faq = tresundios_schema_faq( $post, $url );
	if ( $faq ) {
		$out['tr_faq'] = $faq;
	}
	return $out;
}

// Default rich snippets are off for our types (we provide them), but Rank Math must still add WebSite and WebPage.
add_filter( 'rank_math/schema/add_global_entities', '__return_true' );

/**
 * Merge into Rank Math's graph: replace its publisher, drop any entity type we provide ourselves.
 */
add_filter(
	'rank_math/json_ld',
	static function ( $data ) {
		$ours  = tresundios_schema_entities();
		$types = array();
		foreach ( $ours as $entity ) {
			$types = array_merge( $types, (array) $entity['@type'] );
		}
		$types = array_merge( $types, array( 'Organization', 'LocalBusiness', 'Article', 'BlogPosting', 'NewsArticle' ) );
		foreach ( $data as $key => $entity ) {
			$type = isset( $entity['@type'] ) ? (array) $entity['@type'] : array();
			if ( in_array( $key, array( 'publisher', 'place' ), true ) || array_intersect( $type, $types ) ) {
				if ( 'post' === get_post_type() && array_intersect( $type, array( 'Article', 'BlogPosting', 'NewsArticle' ) ) ) {
					continue; // Blog posts keep Rank Math's article schema.
				}
				unset( $data[ $key ] );
			}
		}
		return array_merge( $data, $ours );
	},
	99
);

/**
 * Fallback when Rank Math is not active: print a complete graph.
 */
add_action(
	'wp_head',
	static function () {
		if ( defined( 'RANK_MATH_VERSION' ) ) {
			return;
		}
		$graph   = array_values( tresundios_schema_entities() );
		$graph[] = array(
			'@type'      => 'WebSite',
			'@id'        => home_url( '/#website' ),
			'url'        => home_url( '/' ),
			'name'       => get_bloginfo( 'name' ),
			'publisher'  => array( '@id' => tresundios_org_id() ),
			'inLanguage' => tresundios_get( 'hreflang' ),
		);
		echo '<script type="application/ld+json">' . wp_json_encode(
			array(
				'@context' => 'https://schema.org',
				'@graph'   => $graph,
			),
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) . '</script>' . "\n";
	},
	20
);
