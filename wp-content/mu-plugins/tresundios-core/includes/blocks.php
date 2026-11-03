<?php
/**
 * Dynamic blocks. Data comes from this plugin; markup comes from the active theme.
 *
 * Each block renders `partials/{name}.php` from the active theme, falling back to
 * this plugin's `templates/{name}.php`. Templates receive `$attributes`, `$block` and `$data`.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Block definitions: name => [ title, description, attributes ].
 *
 * @return array<string, array<string, mixed>>
 */
function tresundios_block_definitions() {
	return array(
		'pricing-table'      => array(
			'title'       => __( 'Pricing table', 'tresundios' ),
			'description' => __( 'Currency-aware pricing from the Pricing entries.', 'tresundios' ),
			'attributes'  => array(
				'groups' => array(
					'type'    => 'array',
					'default' => array(),
					'items'   => array( 'type' => 'string' ),
				),
			),
		),
		'case-studies'       => array(
			'title'       => __( 'Case studies', 'tresundios' ),
			'description' => __( 'Case-study cards.', 'tresundios' ),
			'attributes'  => array(
				'layout'         => array(
					'type'    => 'string',
					'default' => 'grid',
				),
				'limit'          => array(
					'type'    => 'number',
					'default' => 0,
				),
				'workType'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'filters'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'excludeCurrent' => array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
		),
		'case-study-details' => array(
			'title'       => __( 'Case study details', 'tresundios' ),
			'description' => __( 'Facts, challenge, solution, results and links for the current case study.', 'tresundios' ),
			'attributes'  => array(),
		),
		'enquiry-form'       => array(
			'title'       => __( 'Enquiry form', 'tresundios' ),
			'description' => __( 'Project enquiry form, emailed with wp_mail.', 'tresundios' ),
			'attributes'  => array(),
		),
		'contact-details'    => array(
			'title'       => __( 'Contact details', 'tresundios' ),
			'description' => __( 'Phone, email and address from Settings → Tresundios.', 'tresundios' ),
			'attributes'  => array(
				'variant' => array(
					'type'    => 'string',
					'default' => 'lines',
				),
			),
		),
		'services-grid'      => array(
			'title'       => __( 'Services grid', 'tresundios' ),
			'description' => __( 'The top-level services with their sub-services.', 'tresundios' ),
			'attributes'  => array(),
		),
		'resources'          => array(
			'title'       => __( 'Resources', 'tresundios' ),
			'description' => __( 'Resource cards (guides, whitepapers, checklists), or the hub sections.', 'tresundios' ),
			'attributes'  => array(
				'type'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'limit' => array(
					'type'    => 'number',
					'default' => 0,
				),
				'hub'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
			),
		),
		'faq-hub'            => array(
			'title'       => __( 'FAQ hub', 'tresundios' ),
			'description' => __( 'All FAQs from service and page FAQ sections, grouped with links back.', 'tresundios' ),
			'attributes'  => array(),
		),
		'resource-download'  => array(
			'title'       => __( 'Resource download', 'tresundios' ),
			'description' => __( 'Download button for the current resource, when a file is set.', 'tresundios' ),
			'attributes'  => array(),
		),
		'price-teaser'       => array(
			'title'       => __( 'Price teaser', 'tresundios' ),
			'description' => __( 'Starting price for a service and a link to the pricing page.', 'tresundios' ),
			'attributes'  => array(
				'service' => array(
					'type'    => 'string',
					'default' => '',
				),
			),
		),
	);
}

/**
 * Locate a block template: theme first, then plugin fallback.
 *
 * @param string $name Template name.
 * @return string Path or empty string.
 */
function tresundios_locate_block_template( $name ) {
	$theme = locate_template( 'partials/' . $name . '.php' );
	if ( $theme ) {
		return $theme;
	}
	$own = TRESUNDIOS_CORE_DIR . '/templates/' . $name . '.php';
	return is_readable( $own ) ? $own : '';
}

/**
 * Render a block template to a string.
 *
 * @param string               $name       Template name.
 * @param array<string, mixed> $attributes Block attributes.
 * @param WP_Block|null        $block      Block instance.
 * @param mixed                $data       Prepared data.
 * @return string
 */
function tresundios_render_template( $name, $attributes = array(), $block = null, $data = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- $attributes, $block and $data are read by the included template.
	$file = tresundios_locate_block_template( $name );
	if ( ! $file ) {
		return '';
	}
	ob_start();
	include $file;
	return (string) ob_get_clean();
}

/**
 * Data for each block before its template runs.
 *
 * @param string               $name       Block name (without namespace).
 * @param array<string, mixed> $attributes Attributes.
 * @param WP_Block             $block      Block instance.
 * @return mixed
 */
function tresundios_block_data( $name, $attributes, $block ) {
	$post_id = isset( $block->context['postId'] ) ? (int) $block->context['postId'] : get_the_ID();

	switch ( $name ) {
		case 'pricing-table':
			$tree = tresundios_price_tree();
			if ( ! empty( $attributes['groups'] ) ) {
				$tree = array_values(
					array_filter(
						$tree,
						static function ( $g ) use ( $attributes ) {
							return in_array( $g['slug'], $attributes['groups'], true );
						}
					)
				);
			}
			return $tree;

		case 'case-studies':
			$args  = array(
				'limit'     => $attributes['limit'],
				'work_type' => $attributes['workType'] ? array_map( 'trim', explode( ',', $attributes['workType'] ) ) : array(),
				'exclude'   => $attributes['excludeCurrent'] && $post_id ? array( $post_id ) : array(),
			);
			$cases = tresundios_case_studies( $args );
			// No study of this type yet: show the latest work instead of an empty section.
			if ( ! $cases && $args['work_type'] ) {
				$args['work_type'] = array();
				$cases             = tresundios_case_studies( $args );
			}
			return $cases;

		case 'case-study-details':
			return $post_id && 'case_study' === get_post_type( $post_id ) ? tresundios_case_study( $post_id ) : null;

		case 'resources':
			if ( $attributes['hub'] ) {
				return array(
					'sections' => tresundios_resource_sections(),
					'items'    => array(),
				);
			}
			$type = $attributes['type'];
			if ( ! $type && is_tax( 'resource_type' ) ) {
				$type = get_queried_object()->slug;
			}
			return array(
				'sections' => array(),
				'items'    => tresundios_resources( $type, (int) $attributes['limit'] ),
			);

		case 'faq-hub':
			return tresundios_faq_groups();

		case 'resource-download':
			return $post_id ? (string) get_post_meta( $post_id, 'download_url', true ) : '';

		case 'services-grid':
			return tresundios_services();

		case 'price-teaser':
			$slug = $attributes['service'] ? $attributes['service'] : ( $post_id ? get_post_field( 'post_name', $post_id ) : '' );
			return $slug ? tresundios_service_prices( $slug ) : null;

		default:
			return tresundios_settings();
	}
}

add_action(
	'init',
	static function () {
		wp_register_script(
			'tresundios-blocks-editor',
			TRESUNDIOS_CORE_URL . '/assets/blocks-editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-server-side-render', 'wp-block-editor', 'wp-i18n' ),
			TRESUNDIOS_CORE_VERSION,
			true
		);

		$defs = tresundios_block_definitions();
		foreach ( $defs as $name => $def ) {
			register_block_type(
				'tresundios/' . $name,
				array(
					'api_version'     => 3,
					'title'           => $def['title'],
					'description'     => $def['description'],
					'category'        => 'widgets',
					'attributes'      => $def['attributes'],
					'uses_context'    => array( 'postId', 'postType' ),
					'supports'        => array(
						'html'      => false,
						'anchor'    => true,
						'className' => true,
					),
					'editor_script'   => 'tresundios-blocks-editor',
					'render_callback' => static function ( $attributes, $content, $block ) use ( $name ) {
						$data = tresundios_block_data( $name, $attributes, $block );
						return tresundios_render_template( $name, $attributes, $block, $data );
					},
				)
			);
		}

		wp_add_inline_script(
			'tresundios-blocks-editor',
			'window.tresundiosBlocks = ' . wp_json_encode( $defs ) . ';',
			'before'
		);
	}
);

/**
 * Replace the `#book-a-call` placeholder link anywhere in rendered blocks with the site's booking URL,
 * so editors can add a booking button without knowing the calendar address.
 */
add_filter(
	'render_block',
	static function ( $html ) {
		if ( false === strpos( $html, '#book-a-call' ) ) {
			return $html;
		}
		return str_replace( 'href="#book-a-call"', 'href="' . esc_url( tresundios_get( 'booking_url' ) ) . '" target="_blank" rel="noopener" data-book-call', $html );
	}
);

/**
 * Service pages: the H1 comes from the `h1` field (e.g. "Logo & Brand Identity Design in Chennai").
 */
add_filter(
	'render_block_core/post-title',
	static function ( $html, $block, $instance ) {
		$id = isset( $instance->context['postId'] ) ? (int) $instance->context['postId'] : 0;
		if ( ! $id || 'service' !== get_post_type( $id ) || empty( $block['attrs']['level'] ) || 1 !== (int) $block['attrs']['level'] ) {
			return $html;
		}
		$h1 = get_post_meta( $id, 'h1', true );
		return $h1 ? preg_replace( '#(<h1[^>]*>).*?(</h1>)#s', '${1}' . esc_html( $h1 ) . '${2}', $html ) : $html;
	},
	10,
	3
);
