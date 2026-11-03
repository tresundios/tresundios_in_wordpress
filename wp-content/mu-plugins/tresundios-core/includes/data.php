<?php
/**
 * Read models: pricing entries, case studies and services, shaped for the theme block templates.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Billing labels shared by both sites.
 *
 * @param string $type Billing type.
 * @return string
 */
function tresundios_billing_label( $type ) {
	$labels = array(
		'one-time'      => __( 'one-time', 'tresundios' ),
		'monthly'       => __( 'per month', 'tresundios' ),
		'yearly'        => __( 'per year', 'tresundios' ),
		'per-project'   => __( 'per project', 'tresundios' ),
		'starting-from' => __( 'starting from', 'tresundios' ),
		'custom'        => __( 'custom quote', 'tresundios' ),
	);
	return isset( $labels[ $type ] ) ? $labels[ $type ] : '';
}

/**
 * One pricing entry as display parts.
 *
 * Keys: id, name, description, features, badge, enquiry_service, service_slug, group, group_name,
 * from (bool), amount (formatted), period (label), plus (recurring line), note, plain (one-line price),
 * price (number|null), billing_type.
 *
 * @param WP_Post $post price_item post.
 * @return array<string, mixed>
 */
function tresundios_price_item( $post ) {
	$m       = static function ( $key ) use ( $post ) {
		return get_post_meta( $post->ID, $key, true );
	};
	$billing = (string) $m( 'billing_type' );
	$percent = (float) $m( 'percent' );
	$from    = 'starting-from' === $billing;
	$price   = $m( 'price' );

	if ( $percent ) {
		$amount = rtrim( rtrim( number_format( $percent, 1 ), '0' ), '.' ) . '%';
		$period = (string) $m( 'percent_of' );
		$plain  = $amount . ' ' . $period;
	} else {
		$amount = tresundios_format_price( $price );
		$period = tresundios_billing_label( $from ? (string) $m( 'per' ) : $billing );
		$plain  = ( $from ? __( 'Starting from', 'tresundios' ) . ' ' : '' ) . $amount;
		if ( in_array( $from ? $m( 'per' ) : $billing, array( 'monthly', 'one-time', 'yearly' ), true ) ) {
			$plain .= ' ' . $period;
		}
	}

	$plus = '';
	if ( (float) $m( 'recurring_price' ) ) {
		$plus   = '+ ' . tresundios_format_price( $m( 'recurring_price' ) ) . ' ' . tresundios_billing_label( (string) $m( 'recurring_billing' ) );
		$plain .= ' ' . $plus;
	}

	$terms = get_the_terms( $post, 'price_group' );
	$group = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;

	return array(
		'id'              => $post->post_name,
		'post_id'         => $post->ID,
		'name'            => $post->post_title,
		'description'     => $post->post_excerpt,
		'features'        => (array) $m( 'features' ),
		'badge'           => (string) $m( 'badge' ),
		'enquiry_service' => (string) $m( 'enquiry_service' ),
		'service_slug'    => (string) $m( 'service_slug' ),
		'group'           => $group ? $group->slug : '',
		'group_name'      => $group ? $group->name : '',
		'from'            => $from,
		'amount'          => $amount,
		'period'          => $period,
		'plus'            => $plus,
		'note'            => (string) $m( 'price_note' ),
		'plain'           => $plain,
		'price'           => $percent ? null : (float) $price,
		'billing_type'    => $billing,
	);
}

/**
 * Pricing entries in a group (including child groups), ordered by menu_order.
 *
 * @param string $group_slug price_group slug; empty = all.
 * @param array  $extra      Extra WP_Query args.
 * @return array<int, array<string, mixed>>
 */
function tresundios_price_items( $group_slug = '', $extra = array() ) {
	$args = array_merge(
		array(
			'post_type'      => 'price_item',
			'post_status'    => 'publish',
			'posts_per_page' => 200, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page -- bounded catalogue (about 60 entries).
			'orderby'        => array(
				'menu_order' => 'ASC',
				'ID'         => 'ASC',
			),
			'no_found_rows'  => true,
		),
		$extra
	);
	if ( $group_slug ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'price_group',
				'field'    => 'slug',
				'terms'    => $group_slug,
			),
		);
	}
	return array_map( 'tresundios_price_item', get_posts( $args ) );
}

/**
 * Price groups as a tree for the pricing table: top-level groups with their children and items.
 *
 * @return array<int, array<string, mixed>>
 */
function tresundios_price_tree() {
	$order = static function ( $a, $b ) {
		return (int) get_term_meta( $a->term_id, 'tresundios_order', true ) <=> (int) get_term_meta( $b->term_id, 'tresundios_order', true );
	};
	$top   = get_terms(
		array(
			'taxonomy'   => 'price_group',
			'parent'     => 0,
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $top ) ) {
		return array();
	}
	usort( $top, $order );

	$tree = array();
	foreach ( $top as $term ) {
		$children = get_terms(
			array(
				'taxonomy'   => 'price_group',
				'parent'     => $term->term_id,
				'hide_empty' => false,
			)
		);
		$children = is_wp_error( $children ) ? array() : $children;
		usort( $children, $order );

		$kids = array();
		foreach ( $children as $child ) {
			$items = tresundios_price_items( $child->slug );
			if ( $items ) {
				$kids[] = array(
					'slug'        => $child->slug,
					'name'        => $child->name,
					'description' => $child->description,
					'items'       => $items,
				);
			}
		}

		$items = $kids ? array() : tresundios_price_items( $term->slug );
		if ( $kids || $items ) {
			$tree[] = array(
				'slug'        => $term->slug,
				'name'        => $term->name,
				'description' => $term->description,
				'children'    => $kids,
				'items'       => $items,
			);
		}
	}
	return $tree;
}

/**
 * All pricing entries for a service (and its sub-services), plus the cheapest numeric one.
 *
 * @param string $service_slug Service post slug.
 * @return array{best: array<string, mixed>, items: array<int, array<string, mixed>>}|null
 */
function tresundios_service_prices( $service_slug ) {
	$slugs = array( $service_slug );
	$svc   = get_posts(
		array(
			'post_type'      => 'service',
			'name'           => $service_slug,
			'posts_per_page' => 1,
		)
	);
	if ( $svc && ! $svc[0]->post_parent ) {
		foreach ( get_children(
			array(
				'post_parent' => $svc[0]->ID,
				'post_type'   => 'service',
			)
		) as $child ) {
			$slugs[] = $child->post_name;
		}
	}
	$items = tresundios_price_items(
		'',
		array(
			'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				array(
					'key'     => 'service_slug',
					'value'   => $slugs,
					'compare' => 'IN',
				),
			),
		)
	);
	$best  = null;
	foreach ( $items as $item ) {
		if ( null !== $item['price'] && ( null === $best || $item['price'] < $best['price'] ) ) {
			$best = $item;
		}
	}
	return $best ? array(
		'best'  => $best,
		'items' => $items,
	) : null;
}

/**
 * One case study shaped for cards and the single template.
 *
 * @param WP_Post|int $post Case study.
 * @return array<string, mixed>
 */
function tresundios_case_study( $post ) {
	$post  = get_post( $post );
	$m     = static function ( $key ) use ( $post ) {
		return get_post_meta( $post->ID, $key, true );
	};
	$types = get_the_terms( $post, 'work_type' );
	$types = $types && ! is_wp_error( $types ) ? $types : array();

	return array(
		'id'         => $post->ID,
		'slug'       => $post->post_name,
		'title'      => $post->post_title,
		'url'        => get_permalink( $post ),
		'excerpt'    => $post->post_excerpt,
		'client'     => (string) $m( 'client' ),
		'industry'   => (string) $m( 'industry' ),
		'location'   => (string) $m( 'location' ),
		'duration'   => (string) $m( 'duration' ),
		'challenge'  => (string) $m( 'challenge' ),
		'solution'   => (string) $m( 'solution' ),
		'results'    => (array) $m( 'results' ),
		'tech'       => (array) $m( 'tech_stack' ),
		'live_url'   => (string) $m( 'live_url' ),
		'featured'   => (bool) $m( 'featured' ),
		'focal'      => (string) $m( 'focal' ),
		'tag'        => (string) $m( 'card_tag' ),
		'card_cat'   => (string) $m( 'card_cat' ),
		'card_meta'  => (array) $m( 'card_meta' ),
		'card_title' => (string) $m( 'card_title' ) ? (string) $m( 'card_title' ) : $post->post_title,
		'types'      => wp_list_pluck( $types, 'name' ),
		'type_slugs' => wp_list_pluck( $types, 'slug' ),
		'thumb_id'   => (int) get_post_thumbnail_id( $post ),
	);
}

/**
 * Case studies for a grid or reel.
 *
 * @param array<string, mixed> $args limit, work_type, exclude (IDs).
 * @return array<int, array<string, mixed>>
 */
function tresundios_case_studies( $args = array() ) {
	$q = array(
		'post_type'      => 'case_study',
		'post_status'    => 'publish',
		'posts_per_page' => isset( $args['limit'] ) && $args['limit'] ? (int) $args['limit'] : 50,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
		'post__not_in'   => isset( $args['exclude'] ) ? (array) $args['exclude'] : array(),
	);
	if ( ! empty( $args['work_type'] ) ) {
		$q['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'work_type',
				'field'    => 'slug',
				'terms'    => (array) $args['work_type'],
			),
		);
	}
	return array_map( 'tresundios_case_study', get_posts( $q ) );
}

/**
 * Top-level services with their children, for grids and menus.
 *
 * @return array<int, array<string, mixed>>
 */
function tresundios_services() {
	$out = array();
	foreach ( get_posts(
		array(
			'post_type'      => 'service',
			'post_parent'    => 0,
			'posts_per_page' => 20,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'no_found_rows'  => true,
		)
	) as $i => $post ) {
		$children = get_posts(
			array(
				'post_type'      => 'service',
				'post_parent'    => $post->ID,
				'posts_per_page' => 20,
				'orderby'        => 'menu_order',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		$out[]    = array(
			'num'      => sprintf( '%02d', $i + 1 ),
			'title'    => $post->post_title,
			'url'      => get_permalink( $post ),
			'excerpt'  => $post->post_excerpt,
			'icon'     => (string) get_post_meta( $post->ID, 'icon', true ),
			'tag'      => (string) get_post_meta( $post->ID, 'nav_label', true ),
			'children' => array_map(
				static function ( $c ) {
					$label = get_post_meta( $c->ID, 'nav_label', true );
					return array(
						'title' => $label ? $label : $c->post_title,
						'url'   => get_permalink( $c ),
					);
				},
				$children
			),
		);
	}
	return $out;
}

/**
 * Inline SVG icons used by service cards (stroke icons from the original sites).
 *
 * @param string $name Icon name.
 * @return string SVG markup (static, safe).
 */
function tresundios_icon( $name ) {
	$paths = array(
		'megaphone' => '<path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1Z"/><path d="M15.5 8.5a5 5 0 0 1 0 7M18.5 5.5a9 9 0 0 1 0 13"/>',
		'pen'       => '<path d="M12 19l7-7 3 3-7 7-3-3Z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5Z"/><path d="M2 2l7.6 7.6"/><circle cx="11" cy="11" r="2"/>',
		'calendar'  => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/><path d="M12 14.5l1 2 2.2.3-1.6 1.5.4 2.2-2-1-2 1 .4-2.2-1.6-1.5 2.2-.3 1-2Z"/>',
		'code'      => '<rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 18v3"/><path d="M9 9l-2 2 2 2M15 9l2 2-2 2"/>',
		'camera'    => '<path d="M4 8h3l2-3h6l2 3h3a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/><circle cx="12" cy="13.5" r="3.5"/>',
		'cloud'     => '<path d="M7 18h10a4 4 0 0 0 .6-7.96A6 6 0 0 0 6.1 9.2 4.5 4.5 0 0 0 7 18Z"/><path d="M10 13.5l-1.5 1.5 1.5 1.5M14 13.5l1.5 1.5-1.5 1.5"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/**
 * "Get a quote" link for a pricing entry: the contact page with the plan prefilled.
 * On pages that contain the enquiry form, enquiry.js scrolls to it instead.
 *
 * @param array<string, mixed> $item Pricing entry from tresundios_price_item().
 * @return string
 */
function tresundios_quote_url( $item ) {
	return add_query_arg(
		array(
			'plan'    => rawurlencode( $item['name'] . ' (' . $item['plain'] . ')' ),
			'service' => rawurlencode( $item['enquiry_service'] ),
		),
		home_url( '/contact/' )
	) . '#enquiry';
}

/**
 * Published resources (guides, whitepapers, checklists) for cards.
 *
 * @param string $type  resource_type slug; empty = all.
 * @param int    $limit Max items (0 = all).
 * @return array<int, array<string, mixed>>
 */
function tresundios_resources( $type = '', $limit = 0 ) {
	$q = array(
		'post_type'      => 'resource',
		'post_status'    => 'publish',
		'posts_per_page' => $limit ? $limit : 50,
		'no_found_rows'  => true,
	);
	if ( $type ) {
		$q['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'resource_type',
				'field'    => 'slug',
				'terms'    => $type,
			),
		);
	}
	return array_map(
		static function ( $post ) {
			$types = get_the_terms( $post, 'resource_type' );
			$type  = $types && ! is_wp_error( $types ) ? $types[0] : null;
			return array(
				'title'    => $post->post_title,
				'url'      => get_permalink( $post ),
				'excerpt'  => $post->post_excerpt,
				'type'     => $type ? $type->name : '',
				'type_url' => $type ? get_term_link( $type ) : '',
				'reading'  => (string) get_post_meta( $post->ID, 'reading_time', true ),
				'download' => (string) get_post_meta( $post->ID, 'download_url', true ),
				'thumb_id' => (int) get_post_thumbnail_id( $post ),
			);
		},
		get_posts( $q )
	);
}

/**
 * Hub cards: resource sections that have something to show (empty types are hidden).
 *
 * @return array<int, array<string, string|int>>
 */
function tresundios_resource_sections() {
	$sections = array(
		array(
			'title' => __( 'Case studies', 'tresundios' ),
			'text'  => __( 'Websites, stores and apps we have shipped, with the challenge, what we built and the results.', 'tresundios' ),
			'url'   => get_post_type_archive_link( 'case_study' ),
			'count' => (int) wp_count_posts( 'case_study' )->publish,
		),
	);
	foreach ( get_terms(
		array(
			'taxonomy'   => 'resource_type',
			'hide_empty' => true,
		)
	) as $term ) {
		$sections[] = array(
			'title' => $term->name,
			'text'  => $term->description,
			'url'   => get_term_link( $term ),
			'count' => (int) $term->count,
		);
	}
	foreach ( array( 'faq', 'glossary', 'blog' ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$sections[] = array(
				'title' => $page->post_title,
				'text'  => $page->post_excerpt,
				'url'   => get_permalink( $page ),
				'count' => 0,
			);
		}
	}
	return $sections;
}

/**
 * Every visible FAQ on the site, grouped by the page it lives on (for the /faq/ hub).
 * The hub links back to the source page; FAQPage schema stays on the source page only.
 *
 * @return array<int, array<string, mixed>>
 */
function tresundios_faq_groups() {
	$groups = array();
	foreach ( get_posts(
		array(
			'post_type'      => array( 'page', 'service' ),
			'post_status'    => 'publish',
			'posts_per_page' => 100, // phpcs:ignore WordPress.WP.PostsPerPage.posts_per_page_posts_per_page -- bounded: pages and services.
			'orderby'        => array(
				'post_type'  => 'DESC',
				'menu_order' => 'ASC',
			),
			'meta_key'       => 'faq_items', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'no_found_rows'  => true,
		)
	) as $post ) {
		$items = (array) get_post_meta( $post->ID, 'faq_items', true );
		if ( ! $items || 'faq' === $post->post_name ) {
			continue;
		}
		$h1       = get_post_meta( $post->ID, 'h1', true );
		$groups[] = array(
			'title' => $h1 ? $h1 : $post->post_title,
			'url'   => get_permalink( $post ),
			'items' => $items,
		);
	}
	return $groups;
}
