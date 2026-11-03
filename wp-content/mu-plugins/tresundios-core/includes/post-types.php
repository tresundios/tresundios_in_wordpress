<?php
/**
 * Custom post types and taxonomies.
 *
 * - service          /services/{parent}/{child}/  (hierarchical; archive = services hub)
 * - case_study       /work/{slug}/                 (taxonomy work_type)
 * - event_portfolio  /events/{slug}/
 * - price_item       not public; feeds the currency-aware pricing table (taxonomy price_group)
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Labels for a post type.
 *
 * @param string $singular Singular name.
 * @param string $plural   Plural name.
 * @return array<string, string>
 */
function tresundios_cpt_labels( $singular, $plural ) {
	return array(
		'name'               => $plural,
		'singular_name'      => $singular,
		/* translators: %s: post type singular name. */
		'add_new_item'       => sprintf( __( 'Add new %s', 'tresundios' ), $singular ),
		/* translators: %s: post type singular name. */
		'edit_item'          => sprintf( __( 'Edit %s', 'tresundios' ), $singular ),
		/* translators: %s: post type singular name. */
		'new_item'           => sprintf( __( 'New %s', 'tresundios' ), $singular ),
		/* translators: %s: post type singular name. */
		'view_item'          => sprintf( __( 'View %s', 'tresundios' ), $singular ),
		/* translators: %s: post type plural name. */
		'search_items'       => sprintf( __( 'Search %s', 'tresundios' ), $plural ),
		/* translators: %s: post type plural name. */
		'not_found'          => sprintf( __( 'No %s found', 'tresundios' ), strtolower( $plural ) ),
		/* translators: %s: post type plural name. */
		'all_items'          => sprintf( __( 'All %s', 'tresundios' ), $plural ),
		'menu_name'          => $plural,
		'parent_item_colon'  => $singular . ':',
		'not_found_in_trash' => __( 'Nothing in the bin', 'tresundios' ),
	);
}

/**
 * Register post types and taxonomies.
 */
function tresundios_register_content_types() {
	$common = array(
		'public'       => true,
		'show_in_rest' => true,
		'map_meta_cap' => true,
	);

	register_post_type(
		'service',
		array_merge(
			$common,
			array(
				'labels'        => tresundios_cpt_labels( __( 'Service', 'tresundios' ), __( 'Services', 'tresundios' ) ),
				'hierarchical'  => true,
				'has_archive'   => 'services',
				'rewrite'       => array(
					'slug'       => 'services',
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-megaphone',
				'menu_position' => 20,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
			)
		)
	);

	register_post_type(
		'case_study',
		array_merge(
			$common,
			array(
				'labels'        => tresundios_cpt_labels( __( 'Case study', 'tresundios' ), __( 'Case studies', 'tresundios' ) ),
				'has_archive'   => 'work',
				'rewrite'       => array(
					'slug'       => 'work',
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 21,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields', 'revisions' ),
			)
		)
	);

	register_post_type(
		'event_portfolio',
		array_merge(
			$common,
			array(
				'labels'        => tresundios_cpt_labels( __( 'Event', 'tresundios' ), __( 'Events', 'tresundios' ) ),
				'has_archive'   => 'events',
				'rewrite'       => array(
					'slug'       => 'events',
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-tickets-alt',
				'menu_position' => 22,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			)
		)
	);

	register_post_type(
		'resource',
		array_merge(
			$common,
			array(
				'labels'        => tresundios_cpt_labels( __( 'Resource', 'tresundios' ), __( 'Resources', 'tresundios' ) ),
				'has_archive'   => 'resources',
				'rewrite'       => array(
					'slug'       => 'resources',
					'with_front' => false,
				),
				'menu_icon'     => 'dashicons-media-document',
				'menu_position' => 22,
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'custom-fields', 'revisions' ),
			)
		)
	);

	// Pricing entries are data, not pages: editable in the admin, rendered by the pricing table block.
	register_post_type(
		'price_item',
		array(
			'labels'              => tresundios_cpt_labels( __( 'Price', 'tresundios' ), __( 'Pricing', 'tresundios' ) ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'map_meta_cap'        => true,
			'menu_icon'           => 'dashicons-tag',
			'menu_position'       => 23,
			'supports'            => array( 'title', 'excerpt', 'page-attributes', 'custom-fields' ),
		)
	);

	register_taxonomy(
		'work_type',
		array( 'case_study' ),
		array(
			'labels'            => array(
				'name'          => __( 'Work types', 'tresundios' ),
				'singular_name' => __( 'Work type', 'tresundios' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			// Kept off /work/ so it never collides with case-study slugs.
			'rewrite'           => array(
				'slug'       => 'work-type',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'resource_type',
		array( 'resource' ),
		array(
			'labels'            => array(
				'name'          => __( 'Resource types', 'tresundios' ),
				'singular_name' => __( 'Resource type', 'tresundios' ),
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'resources/type',
				'with_front' => false,
			),
		)
	);

	register_taxonomy(
		'price_group',
		array( 'price_item' ),
		array(
			'labels'            => array(
				'name'          => __( 'Price groups', 'tresundios' ),
				'singular_name' => __( 'Price group', 'tresundios' ),
			),
			'public'            => false,
			'show_ui'           => true,
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => false,
		)
	);
}
add_action( 'init', 'tresundios_register_content_types' );

/**
 * Default terms, created by `wp tresundios setup`. Slug => name (children keyed by parent slug).
 *
 * @return array<string, array<string, string|array<string, string>>>
 */
function tresundios_default_terms() {
	return array(
		'work_type'     => array(
			'web'        => 'Web',
			'e-commerce' => 'E-commerce',
			'mobile-app' => 'Mobile app',
			'branding'   => 'Branding',
			'marketing'  => 'Marketing',
			'event'      => 'Event',
		),
		'resource_type' => array(
			'guides'      => 'Guides',
			'whitepapers' => 'Whitepapers',
			'checklists'  => 'Checklists & templates',
		),
		'price_group'   => array(
			'a-la-carte'         => array(
				'name'     => 'À La Carte',
				'children' => array(
					'digital-marketing' => 'Digital Marketing',
					'design'            => 'Graphic Design & Branding',
					'photography'       => 'Product Photography',
					'web'               => 'Websites & Apps',
					'devops'            => 'DevOps & Cloud',
				),
			),
			'marketing-packages' => 'Marketing Packages',
			'launch-packages'    => 'Launch Packages',
			'events'             => 'Events',
		),
	);
}

/**
 * Create default terms if missing. Safe to run repeatedly.
 */
function tresundios_ensure_terms() {
	foreach ( tresundios_default_terms() as $taxonomy => $terms ) {
		$order = 0;
		foreach ( $terms as $slug => $def ) {
			$name     = is_array( $def ) ? $def['name'] : $def;
			$parent   = tresundios_ensure_term( $taxonomy, $slug, $name, 0, $order++ );
			$children = is_array( $def ) ? $def['children'] : array();
			foreach ( $children as $child_slug => $child_name ) {
				tresundios_ensure_term( $taxonomy, $child_slug, $child_name, $parent, $order++ );
			}
		}
	}
}

/**
 * Create or fetch one term.
 *
 * @param string $taxonomy Taxonomy.
 * @param string $slug     Term slug.
 * @param string $name     Term name.
 * @param int    $parent_id Parent term ID.
 * @param int    $order    Sort order stored in term meta.
 * @return int Term ID.
 */
function tresundios_ensure_term( $taxonomy, $slug, $name, $parent_id, $order ) {
	$term = get_term_by( 'slug', $slug, $taxonomy );
	if ( $term ) {
		$id = (int) $term->term_id;
	} else {
		$res = wp_insert_term(
			$name,
			$taxonomy,
			array(
				'slug'   => $slug,
				'parent' => $parent_id,
			)
		);
		$id  = is_wp_error( $res ) ? 0 : (int) $res['term_id'];
	}
	if ( $id ) {
		update_term_meta( $id, 'tresundios_order', $order );
	}
	return $id;
}

/**
 * /resources/type/{type}/ must win over the resource attachment rule (/resources/{post}/{attachment}/).
 */
add_action(
	'init',
	static function () {
		add_rewrite_rule( '^resources/type/([^/]+)/page/([0-9]+)/?$', 'index.php?resource_type=$matches[1]&paged=$matches[2]', 'top' );
		add_rewrite_rule( '^resources/type/([^/]+)/?$', 'index.php?resource_type=$matches[1]', 'top' );
	},
	20
);
