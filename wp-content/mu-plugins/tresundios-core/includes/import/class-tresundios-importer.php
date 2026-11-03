<?php
/**
 * Imports content from the original static sites. Idempotent: every record is matched by slug
 * (or source path for media) and updated in place on re-runs.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Static-site importer.
 */
class Tresundios_Importer {

	/**
	 * Source folder (contains index.html and assets/).
	 *
	 * @var string
	 */
	protected $src;

	/**
	 * Whether the source is the India site.
	 *
	 * @var bool
	 */
	protected $india;

	/**
	 * Log lines for the CLI.
	 *
	 * @var array<int, string>
	 */
	public $log = array();

	/**
	 * Items that need a human look after import.
	 *
	 * @var array<int, string>
	 */
	public $review = array();

	/**
	 * Price-group slug per source category.
	 *
	 * @var array<string, string>
	 */
	protected $group_map = array(
		'digital-marketing' => 'digital-marketing',
		'marketing'         => 'digital-marketing',
		'design'            => 'design',
		'branding'          => 'design',
		'photography'       => 'photography',
		'web'               => 'web',
		'websites'          => 'web',
		'events'            => 'events',
		'marketing-package' => 'marketing-packages',
		'launch-package'    => 'launch-packages',
	);

	/**
	 * Service page slug per price group (pricing entries feed service teasers and Offer schema).
	 *
	 * @var array<string, string>
	 */
	protected $service_map = array(
		'digital-marketing'  => 'digital-marketing',
		'design'             => 'graphic-design',
		'photography'        => 'product-photography',
		'web'                => 'websites-and-apps',
		'devops'             => 'devops',
		'events'             => 'event-management',
		'marketing-packages' => 'digital-marketing',
		'launch-packages'    => '',
	);

	/**
	 * Constructor.
	 *
	 * @param string $src Source folder.
	 */
	public function __construct( $src ) {
		$this->src   = rtrim( $src, '/' );
		$this->india = 'india' === tresundios_get( 'region' );
	}

	// ---------------------------------------------------------------------
	// Source helpers
	// ---------------------------------------------------------------------

	/**
	 * Read a source file.
	 *
	 * @param string $rel Relative path.
	 * @return string
	 * @throws RuntimeException When missing.
	 */
	protected function read( $rel ) {
		$path = $this->src . '/' . $rel;
		if ( ! is_readable( $path ) ) {
			throw new RuntimeException( 'Missing source file: ' . $path ); // phpcs:ignore WordPress.Security.EscapeOutput.ExceptionNotEscaped
		}
		return (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	}

	/**
	 * Parsed index.html.
	 *
	 * @return DOMXPath
	 */
	protected function dom() {
		static $cache = array();
		if ( ! isset( $cache[ $this->src ] ) ) {
			$doc = new DOMDocument();
			libxml_use_internal_errors( true );
			$doc->loadHTML( '<?xml encoding="utf-8"?>' . $this->read( 'index.html' ) );
			libxml_clear_errors();
			$cache[ $this->src ] = new DOMXPath( $doc );
		}
		return $cache[ $this->src ];
	}

	/**
	 * Text of the first node matching an XPath query.
	 *
	 * @param string       $query XPath.
	 * @param DOMNode|null $ctx   Context node.
	 * @return string
	 */
	protected function text( $query, $ctx = null ) {
		$n = $this->dom()->query( $query, $ctx );
		return $n && $n->length ? trim( preg_replace( '/\s+/u', ' ', $n->item( 0 )->textContent ) ) : ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOM API.
	}

	/**
	 * Return the balanced (...) / [...] / {...} block that starts at $start in $s.
	 *
	 * @param string $s     Source.
	 * @param int    $start Offset of the opening bracket.
	 * @return string Contents including brackets.
	 */
	public static function balanced( $s, $start ) {
		$open  = $s[ $start ];
		$close = array(
			'(' => ')',
			'[' => ']',
			'{' => '}',
		)[ $open ];
		$depth = 0;
		$len   = strlen( $s );
		for ( $i = $start; $i < $len; $i++ ) { // phpcs:ignore Generic.CodeAnalysis.JumbledIncrementer.Found -- the inner loop skips over string literals on purpose.
			$c = $s[ $i ];
			if ( '\'' === $c || '"' === $c ) {
				for ( $i++; $i < $len && $s[ $i ] !== $c; $i++ ) { // phpcs:ignore Generic.CodeAnalysis.JumbledIncrementer.Found -- scans to the end of a string literal.
					if ( '\\' === $s[ $i ] ) {
						++$i;
					}
				}
				continue;
			}
			if ( $c === $open ) {
				++$depth;
			} elseif ( $c === $close && 0 === --$depth ) {
				return substr( $s, $start, $i - $start + 1 );
			}
		}
		return '';
	}

	/**
	 * Convert a JavaScript literal (single quotes, bare keys, trailing commas, comments) to PHP data.
	 *
	 * @param string               $js   JS literal.
	 * @param array<string, mixed> $vars Identifier values (e.g. RED => '#c74634').
	 * @return mixed
	 */
	public static function js_value( $js, $vars = array() ) {
		$out = '';
		$len = strlen( $js );
		for ( $i = 0; $i < $len; $i++ ) { // phpcs:ignore Generic.CodeAnalysis.JumbledIncrementer.Found -- the inner loop skips over string literals on purpose.
			$c = $js[ $i ];
			if ( '/' === $c && $i + 1 < $len && '*' === $js[ $i + 1 ] ) {
				$end = strpos( $js, '*/', $i + 2 );
				$i   = false === $end ? $len : $end + 1;
				continue;
			}
			if ( '/' === $c && $i + 1 < $len && '/' === $js[ $i + 1 ] ) {
				$end = strpos( $js, "\n", $i );
				$i   = false === $end ? $len : $end;
				continue;
			}
			if ( '\'' === $c || '"' === $c ) {
				$str = '';
				for ( $i++; $i < $len && $js[ $i ] !== $c; $i++ ) { // phpcs:ignore Generic.CodeAnalysis.JumbledIncrementer.Found -- scans to the end of a string literal.
					if ( '\\' === $js[ $i ] ) {
						$next = $js[ ++$i ];
						if ( 'u' === $next ) {
							$str .= mb_chr( hexdec( substr( $js, $i + 1, 4 ) ) );
							$i   += 4;
						} else {
							$str .= 'n' === $next ? "\n" : $next;
						}
						continue;
					}
					$str .= $js[ $i ];
				}
				$out .= wp_json_encode( $str, JSON_UNESCAPED_UNICODE );
				continue;
			}
			if ( preg_match( '/[A-Za-z_$]/', $c ) ) {
				preg_match( '/[A-Za-z_$][\w$]*/', $js, $m, 0, $i );
				$word = $m[0];
				$i   += strlen( $word ) - 1;
				$rest = ltrim( substr( $js, $i + 1, 20 ) );
				if ( '' !== $rest && ':' === $rest[0] ) {
					$out .= wp_json_encode( $word );
				} elseif ( in_array( $word, array( 'true', 'false', 'null' ), true ) ) {
					$out .= $word;
				} else {
					$out .= wp_json_encode( isset( $vars[ $word ] ) ? $vars[ $word ] : null );
				}
				continue;
			}
			if ( ',' === $c && preg_match( '/^,\s*[\]}]/', substr( $js, $i, 40 ) ) ) {
				continue;
			}
			$out .= $c;
		}
		return json_decode( $out, true );
	}

	// ---------------------------------------------------------------------
	// Writers
	// ---------------------------------------------------------------------

	/**
	 * Create or update a post matched by type + slug.
	 *
	 * @param string               $type Post type.
	 * @param string               $slug Slug.
	 * @param array<string, mixed> $args wp_insert_post args.
	 * @param array<string, mixed> $meta Meta values.
	 * @return int Post ID.
	 */
	public function upsert( $type, $slug, $args, $meta = array() ) {
		$parent   = isset( $args['post_parent'] ) ? (int) $args['post_parent'] : 0;
		$existing = get_posts(
			array(
				'post_type'      => $type,
				'name'           => $slug,
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'post_parent'    => $parent,
				'fields'         => 'ids',
			)
		);

		$args = array_merge(
			array(
				'post_type'   => $type,
				'post_name'   => $slug,
				'post_status' => 'publish',
			),
			$args
		);
		if ( $existing ) {
			$args['ID'] = $existing[0];
		}

		$id = wp_insert_post( wp_slash( $args ), true );
		if ( is_wp_error( $id ) ) {
			WP_CLI::warning( $type . ' ' . $slug . ': ' . $id->get_error_message() );
			return 0;
		}
		foreach ( $meta as $key => $value ) {
			update_post_meta( $id, $key, $value );
		}
		$this->log[] = ( $existing ? 'updated ' : 'created ' ) . $type . ' ' . $slug;
		return (int) $id;
	}

	/**
	 * Import an image once; re-runs reuse the attachment (matched by source path and file hash).
	 *
	 * @param string $rel Relative path inside the source folder.
	 * @param string $alt Alt text.
	 * @param int    $parent_id Parent post.
	 * @return int Attachment ID.
	 */
	public function media( $rel, $alt, $parent_id = 0 ) {
		$path = $this->src . '/' . $rel;
		if ( ! is_readable( $path ) ) {
			$this->review[] = 'Missing image ' . $rel;
			return 0;
		}
		$key   = 'tresundios:' . $rel . ':' . md5_file( $path );
		$found = get_posts(
			array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'meta_key'    => '_tresundios_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'  => $key, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'      => 'ids',
			)
		);
		if ( $found ) {
			update_post_meta( $found[0], '_wp_attachment_image_alt', $alt );
			return (int) $found[0];
		}

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';

		$tmp = wp_tempnam( basename( $path ) );
		copy( $path, $tmp );
		$id = media_handle_sideload(
			array(
				'name'     => basename( $path ),
				'tmp_name' => $tmp,
			),
			$parent_id
		);
		if ( is_wp_error( $id ) ) {
			WP_CLI::warning( 'Image ' . $rel . ': ' . $id->get_error_message() );
			return 0;
		}
		update_post_meta( $id, '_tresundios_source', $key );
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		$this->log[] = 'imported image ' . $rel;
		return (int) $id;
	}

	/**
	 * Set a term's description.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @param string $slug     Term slug.
	 * @param string $desc     Description.
	 */
	protected function term_description( $taxonomy, $slug, $desc ) {
		$term = get_term_by( 'slug', $slug, $taxonomy );
		if ( $term ) {
			wp_update_term( $term->term_id, $taxonomy, array( 'description' => $desc ) );
		}
	}

	// ---------------------------------------------------------------------
	// Pricing
	// ---------------------------------------------------------------------

	/**
	 * Import pricing entries from assets/js/pricing.js plus the DevOps gigs.
	 */
	public function pricing() {
		$items = $this->india ? $this->pricing_items_in() : $this->pricing_items_com();
		$order = array();
		foreach ( $items as $it ) {
			$group           = $it['group'];
			$order[ $group ] = isset( $order[ $group ] ) ? $order[ $group ] + 1 : 1;
			$this->save_price( $it, $order[ $group ] );
		}

		$devops = require TRESUNDIOS_CORE_DIR . '/includes/import/devops.php';
		foreach ( $devops as $i => $gig ) {
			$this->save_price(
				array(
					'id'          => $gig['id'],
					'group'       => 'devops',
					'name'        => $gig['name'],
					'description' => $gig['description'],
					'price'       => $this->india ? $gig['inr'] : $gig['usd'],
					'billing'     => $gig['billing'],
					'per'         => isset( $gig['per'] ) ? $gig['per'] : '',
					'features'    => $gig['features'],
					'service'     => 'DevOps & Cloud',
				),
				$i + 1
			);
		}
		$this->review[] = 'DevOps & Cloud prices are new (written for this migration): please confirm them.';
	}

	/**
	 * Save one pricing entry.
	 *
	 * @param array<string, mixed> $it    Normalised item.
	 * @param int                  $order Sort order inside its group.
	 */
	protected function save_price( $it, $order ) {
		$id = $this->upsert(
			'price_item',
			$it['id'],
			array(
				'post_title'   => $it['name'],
				'post_excerpt' => $it['description'],
				'menu_order'   => $order,
			),
			array(
				'price'             => (float) ( isset( $it['price'] ) ? $it['price'] : 0 ),
				'billing_type'      => $it['billing'],
				'per'               => isset( $it['per'] ) ? $it['per'] : '',
				'price_note'        => isset( $it['note'] ) ? $it['note'] : '',
				'recurring_price'   => (float) ( isset( $it['recurring'] ) ? $it['recurring'] : 0 ),
				'recurring_billing' => isset( $it['recurring_billing'] ) ? $it['recurring_billing'] : '',
				'percent'           => (float) ( isset( $it['percent'] ) ? $it['percent'] : 0 ),
				'percent_of'        => isset( $it['percent_of'] ) ? $it['percent_of'] : '',
				'min_price'         => (float) ( isset( $it['min'] ) ? $it['min'] : 0 ),
				'features'          => isset( $it['features'] ) ? array_values( (array) $it['features'] ) : array(),
				'badge'             => isset( $it['badge'] ) ? (string) $it['badge'] : '',
				'enquiry_service'   => isset( $it['service'] ) ? $it['service'] : 'Multiple Services',
				'service_slug'      => isset( $this->service_map[ $it['group'] ] ) ? $this->service_map[ $it['group'] ] : '',
			)
		);
		if ( $id ) {
			wp_set_object_terms( $id, $it['group'], 'price_group' );
		}
	}

	/**
	 * Parse the .com pricing.js (helper calls: dm(...), ds(...), item(...)).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	protected function pricing_items_com() {
		$js      = $this->read( 'assets/js/pricing.js' );
		$helpers = array(
			'dm' => array( 'digital-marketing', 'Digital Marketing' ),
			'ds' => array( 'design', 'Graphic Design' ),
			'ph' => array( 'photography', 'Product Photography' ),
			'wb' => array( 'web', 'Website / App' ),
			'ev' => array( 'events', 'Event Management' ),
		);
		$items   = array();

		preg_match_all( '/\b(dm|ds|ph|wb|ev|item)\(\'/', $js, $calls, PREG_OFFSET_CAPTURE );
		foreach ( $calls[1] as $call ) {
			$fn   = $call[0];
			$args = self::js_value( '[' . substr( self::balanced( $js, $call[1] + strlen( $fn ) ), 1, -1 ) . ']' );
			if ( ! is_array( $args ) ) {
				continue;
			}
			if ( 'item' === $fn ) {
				// item(id, category, name, usdPrice, billingType, description, opts).
				list( $id, $cat, $name, $price, $billing, $desc ) = $args;
				$opts    = isset( $args[6] ) && is_array( $args[6] ) ? $args[6] : array();
				$group   = isset( $this->group_map[ $cat ] ) ? $this->group_map[ $cat ] : $cat;
				$service = isset( $opts['service'] ) ? $opts['service'] : 'Multiple Services';
			} else {
				list( $id, $name, $price, $billing, $desc ) = $args;
				$opts                                       = isset( $args[5] ) && is_array( $args[5] ) ? $args[5] : array();
				$group                                      = $helpers[ $fn ][0];
				$service                                    = $helpers[ $fn ][1];
			}
			if ( 'id' === $id ) {
				continue; // The helper definitions themselves.
			}

			$it = array(
				'id'          => $id,
				'group'       => $group,
				'name'        => $name,
				'description' => $desc,
				'price'       => $price,
				'billing'     => $billing,
				'per'         => isset( $opts['per'] ) ? $opts['per'] : '',
				'note'        => isset( $opts['note'] ) ? $opts['note'] : '',
				'features'    => isset( $opts['features'] ) ? $opts['features'] : array(),
				'badge'       => isset( $opts['badge'] ) ? $opts['badge'] : '',
				'service'     => $service,
			);
			if ( isset( $opts['recurring'] ) ) {
				$it['recurring']         = $opts['recurring']['usdPrice'];
				$it['recurring_billing'] = $opts['recurring']['billingType'];
			}
			if ( 'ev-large' === $id ) {
				preg_match( '/e\.percent = (\d+); e\.percentOf = \'([^\']+)\'; e\.minUsd = (\d+)/', $js, $pm );
				$it['percent']    = (float) $pm[1];
				$it['percent_of'] = $pm[2];
				$it['min']        = (float) $pm[3];
				$it['note']       = str_replace( '{min}', tresundios_format_price( $pm[3] ), $it['note'] );
			}
			$items[] = $it;
		}

		// Tab introductions ("price-lead" paragraphs, in tab order).
		preg_match_all( '/price-lead">([^<]+)</', $js, $leads );
		foreach ( array( 'a-la-carte', 'marketing-packages', 'launch-packages', 'events' ) as $i => $slug ) {
			if ( isset( $leads[1][ $i ] ) ) {
				$this->term_description( 'price_group', $slug, $leads[1][ $i ] );
			}
		}
		return $items;
	}

	/**
	 * Parse the .in pricing.js (one object literal).
	 *
	 * @return array<int, array<string, mixed>>
	 * @throws RuntimeException When pricing.js cannot be parsed.
	 */
	protected function pricing_items_in() {
		$js   = $this->read( 'assets/js/pricing.js' );
		$data = self::js_value( self::balanced( $js, strpos( $js, '{', strpos( $js, 'TRESUNDIOS_PRICING' ) ) ) );
		if ( ! is_array( $data ) ) {
			throw new RuntimeException( 'Could not parse tresundios-in pricing.js' );
		}

		$services = array(
			'marketing'   => 'Digital Marketing',
			'branding'    => 'Graphic Design',
			'photography' => 'Product Photography',
			'websites'    => 'Website / App',
			'events'      => 'Event Management',
		);
		foreach ( $data['categories'] as $cat ) {
			$this->term_description( 'price_group', $this->group_map[ $cat['id'] ], $cat['description'] );
		}
		$this->term_description( 'price_group', 'a-la-carte', $this->text( '//p[contains(@class,"pricing-intro")]' ) );

		$items = array();
		$all   = array_merge( $data['services'], $data['marketingPackages'], $data['launchPackages'] );
		foreach ( $all as $s ) {
			$cat   = $s['category'];
			$group = isset( $this->group_map[ $cat ] ) ? $this->group_map[ $cat ] : $cat;
			$it    = array(
				'id'          => $s['id'],
				'group'       => $group,
				'name'        => $s['name'],
				'description' => $s['description'],
				'price'       => $s['inrPrice'],
				'billing'     => $s['billingType'],
				// The .in renderer shows "per month" after starting-from marketing prices, otherwise "per project".
				'per'         => 'starting-from' === $s['billingType'] ? ( 'marketing' === $cat ? 'monthly' : 'per-project' ) : '',
				'note'        => isset( $s['priceNote'] ) ? $s['priceNote'] : '',
				'features'    => $s['features'],
				'badge'       => isset( $s['badge'] ) ? $s['badge'] : '',
				'service'     => isset( $services[ $cat ] ) ? $services[ $cat ] : ( 'marketing-packages' === $cat ? 'Digital Marketing' : 'Multiple Services' ),
			);
			if ( ! empty( $s['recurringPrice'] ) ) {
				$it['recurring']         = $s['recurringPrice'];
				$it['recurring_billing'] = $s['recurringBillingType'];
			}
			if ( 'large-events' === $s['id'] && preg_match( '/(\d+)% (.+)/', $s['priceLabel'], $pm ) ) {
				$it['percent']    = (float) $pm[1];
				$it['percent_of'] = $pm[2];
				$it['min']        = $s['inrPrice'];
			}
			$items[] = $it;
		}
		return $items;
	}

	// ---------------------------------------------------------------------
	// Services
	// ---------------------------------------------------------------------

	/**
	 * Service tree: slug => [ icon, children slugs in the order of the source bullets ].
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public static function service_tree() {
		return array(
			'digital-marketing'   => array(
				'icon'     => 'megaphone',
				'children' => array( 'social-media-marketing', 'social-media-optimisation', 'local-seo-google-business-profile', 'campaign-analytics-reporting' ),
			),
			'graphic-design'      => array(
				'icon'     => 'pen',
				'children' => array( 'logo-brand-identity', 'art-illustration', 'print-design', 'social-marketing-graphics' ),
			),
			'event-management'    => array(
				'icon'     => 'calendar',
				'children' => array( 'brand-launches-corporate-events', 'parties-surprise-celebrations', 'sports-community-events', 'event-promotion-coverage' ),
			),
			'websites-and-apps'   => array(
				'icon'     => 'code',
				'children' => array( 'websites-and-ecommerce', 'flutter-app-development', 'custom-software-apis', 'hosting-and-care' ),
			),
			'product-photography' => array(
				'icon'     => 'camera',
				'children' => array(),
			),
			'devops'              => array(
				'icon'     => 'cloud',
				'children' => array(),
			),
		);
	}

	/**
	 * Import the service tree: the four original service cards (title, summary, bullets → child services)
	 * plus Product Photography and DevOps & Cloud. Page bodies are written by the content import.
	 */
	public function services() {
		$cards = $this->dom()->query( '//article[contains(concat(" ",normalize-space(@class)," ")," svc ")]' );
		$tree  = self::service_tree();
		$slugs = array_keys( $tree );
		$extra = array(
			'product-photography' => array(
				'Product Photography',
				'Clean, consistent product, lifestyle and on-location photography for your store, catalogue, menus and social media.',
				'Capture',
			),
			'devops'              => array(
				'DevOps & Cloud',
				'Cloud servers, CI/CD pipelines, containers and monitoring that keep your website and apps fast, secure and easy to release.',
				'Run',
			),
		);

		$order = 0;
		foreach ( $slugs as $i => $slug ) {
			if ( isset( $extra[ $slug ] ) ) {
				list( $title, $summary, $tag ) = $extra[ $slug ];
				$bullets                       = array();
			} else {
				$card    = $cards->item( $i );
				$title   = $this->text( './/h3', $card );
				$summary = $this->text( './/p', $card );
				$tag     = $this->text( './/*[contains(@class,"tag")]', $card );
				$bullets = array();
				foreach ( $this->dom()->query( './/li', $card ) as $li ) {
					$bullets[] = trim( $li->textContent ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOM API.
				}
			}

			$parent = $this->upsert(
				'service',
				$slug,
				array(
					'post_title'   => $title,
					'post_excerpt' => $summary,
					'menu_order'   => ++$order,
				),
				array(
					'translation_key' => 'service:' . $slug,
					'icon'            => $tree[ $slug ]['icon'],
					'nav_label'       => $tag ? $tag : $title,
				)
			);

			foreach ( $tree[ $slug ]['children'] as $j => $child ) {
				$label = isset( $bullets[ $j ] ) ? $bullets[ $j ] : $child;
				$this->upsert(
					'service',
					$child,
					array(
						'post_title'  => $label,
						'post_parent' => $parent,
						'menu_order'  => $j + 1,
					),
					array(
						'translation_key' => 'service:' . $child,
						'nav_label'       => $label,
					)
				);
			}
		}
		$this->review[] = 'Product Photography and DevOps & Cloud service summaries are new copy: please confirm.';
	}

	// ---------------------------------------------------------------------
	// Pages and service bodies
	// ---------------------------------------------------------------------

	/**
	 * Copy definitions (includes/import/content.php).
	 *
	 * @return array<string, mixed>
	 */
	protected function content() {
		static $content = null;
		if ( null === $content ) {
			$content = require TRESUNDIOS_CORE_DIR . '/includes/import/content.php';
		}
		return $content;
	}

	/**
	 * Replace copy tokens with this site's values (see content.php).
	 *
	 * @param string $text Text with tokens.
	 * @param string $where Record name, for the review list when a price is missing.
	 * @return string
	 */
	public function fill( $text, $where = '' ) {
		$s    = tresundios_settings();
		$addr = sprintf( '%s, %s %s, %s, India', $s['street_address'], $s['locality'], substr( $s['postal_code'], 0, 3 ) . ' ' . substr( $s['postal_code'], 3 ), $s['region_name'] );
		$text = strtr(
			$text,
			array(
				'{in}'         => $this->india ? ' in Chennai' : '',
				'{where}'      => $this->india ? 'in Chennai and across Tamil Nadu' : 'in the UK, Kuwait, the Gulf and worldwide',
				'{clients}'    => $this->india ? 'Chennai businesses' : 'growing businesses worldwide',
				'{cur}'        => $s['currency'],
				'{tax}'        => $s['tax_note'] ? ' (' . $s['tax_note'] . ')' : '',
				'{legal_name}' => $s['legal_name'],
				'{address}'    => $addr,
				'{email}'      => $s['contact_email'],
				'{cin}'        => $s['cin'],
				'{gstin}'      => $s['gstin'],
			)
		);
		return preg_replace_callback(
			'/\{(price|from):([\w-]+)\|([\w-]+)\}/',
			function ( $m ) use ( $where ) {
				$post = get_page_by_path( $this->india ? $m[3] : $m[2], OBJECT, 'price_item' );
				if ( ! $post ) {
					$this->review[] = 'Missing price "' . $m[0] . '" in ' . $where;
					return '[price]';
				}
				$item = tresundios_price_item( $post );
				return 'from' === $m[1] ? $item['amount'] : lcfirst( $item['plain'] );
			},
			$text
		);
	}

	/**
	 * Escape text for block markup (keeps the ampersand entity form core blocks save).
	 *
	 * @param string $text Text.
	 * @return string
	 */
	protected static function t( $text ) {
		return esc_html( $text );
	}

	/**
	 * A section with a heading and the FAQ as core Details blocks.
	 *
	 * @param array<int, array{0: string, 1: string}> $faq     Question/answer pairs (already filled).
	 * @param string                                  $heading H2 text.
	 * @return string Block markup.
	 */
	protected function faq_section( $faq, $heading ) {
		$items = '';
		foreach ( $faq as $qa ) {
			$items .= "<!-- wp:details -->\n<details class=\"wp-block-details\"><summary>" . self::t( $qa[0] ) . "</summary><!-- wp:paragraph -->\n<p>" . self::t( $qa[1] ) . "</p>\n<!-- /wp:paragraph --></details>\n<!-- /wp:details -->\n\n";
		}
		return '<!-- wp:group {"tagName":"section","className":"block tr-sec faq-sec","layout":{"type":"default"}} -->
<section class="wp-block-group block tr-sec faq-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:group {"className":"sec-head","layout":{"type":"default"}} -->
<div class="wp-block-group sec-head"><!-- wp:group {"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">FAQ</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"className":"split"} -->
<h2 class="wp-block-heading split">' . self::t( $heading ) . '</h2>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"faq","layout":{"type":"default"}} -->
<div class="wp-block-group faq">' . $items . '</div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->';
	}

	/**
	 * A prose block (container + typography) wrapping inner block markup.
	 *
	 * @param string $inner Block markup.
	 * @return string
	 */
	protected static function prose( $inner ) {
		return "<!-- wp:group {\"className\":\"container prose\",\"layout\":{\"type\":\"default\"}} -->\n<div class=\"wp-block-group container prose\">" . $inner . "</div>\n<!-- /wp:group -->";
	}

	/**
	 * Fill and normalise an FAQ list.
	 *
	 * @param array<int, array{0: string, 1: string}> $faq   Raw pairs.
	 * @param string                                  $where Record name.
	 * @return array<int, array{0: string, 1: string}>
	 */
	protected function filled_faq( $faq, $where ) {
		return array_map(
			function ( $qa ) use ( $where ) {
				return array( $this->fill( $qa[0], $where ), $this->fill( $qa[1], $where ) );
			},
			$faq
		);
	}

	/**
	 * FAQ pairs in the faq_items meta format.
	 *
	 * @param array<int, array{0: string, 1: string}> $faq Pairs.
	 * @return array<int, array<string, string>>
	 */
	protected static function faq_meta( $faq ) {
		return array_map(
			static function ( $qa ) {
				return array(
					'question' => $qa[0],
					'answer'   => $qa[1],
				);
			},
			$faq
		);
	}

	/**
	 * Write every service page body (direct answer, deliverables, sub-services, pricing, process, related work, FAQ).
	 */
	public function service_pages() {
		$defs = $this->content()['services'];
		foreach ( self::service_tree() as $parent_slug => $node ) {
			foreach ( array_merge( array( $parent_slug ), $node['children'] ) as $slug ) {
				$post = get_page_by_path( $parent_slug === $slug ? $slug : $parent_slug . '/' . $slug, OBJECT, 'service' );
				if ( ! $post || ! isset( $defs[ $slug ] ) ) {
					$this->review[] = 'Service page missing: ' . $slug;
					continue;
				}
				$d = $defs[ $slug ];

				// Pricing entries that belong to this page (feeds the price list and Offer schema).
				foreach ( $d['prices'] as $pair ) {
					list( $com, $in ) = explode( '|', $pair );
					$item             = get_page_by_path( $this->india ? $in : $com, OBJECT, 'price_item' );
					if ( $item ) {
						update_post_meta( $item->ID, 'service_slug', $slug );
					}
				}

				$h1     = $this->fill( $d['h1'], $slug );
				$answer = $this->fill( $d['answer'], $slug );
				$words  = str_word_count( wp_strip_all_tags( $answer ) );
				if ( $words < 40 || $words > 60 ) {
					$this->review[] = sprintf( 'Direct answer on %s is %d words (target 40–60).', $slug, $words );
				}

				$list = '';
				foreach ( $d['items'] as $item ) {
					$list .= "<!-- wp:list-item -->\n<li>" . self::t( $item ) . "</li>\n<!-- /wp:list-item -->";
				}
				$body = "<!-- wp:paragraph {\"className\":\"answer\"} -->\n<p class=\"answer\">" . self::t( $answer ) . "</p>\n<!-- /wp:paragraph -->\n\n"
					. "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">What’s included</h2>\n<!-- /wp:heading -->\n\n"
					. "<!-- wp:list {\"className\":\"is-style-checks\"} -->\n<ul class=\"wp-block-list is-style-checks\">" . $list . "</ul>\n<!-- /wp:list -->";

				if ( $node['children'] && $parent_slug === $slug ) {
					$links = '';
					foreach ( get_children(
						array(
							'post_parent' => $post->ID,
							'post_type'   => 'service',
							'orderby'     => 'menu_order',
							'order'       => 'ASC',
						)
					) as $child ) {
						$label  = get_post_meta( $child->ID, 'nav_label', true );
						$links .= "<!-- wp:list-item -->\n<li><a href=\"" . esc_url( get_permalink( $child ) ) . '">' . self::t( $label ? $label : $child->post_title ) . "</a></li>\n<!-- /wp:list-item -->";
					}
					$body .= "\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . self::t( $post->post_title ) . " services</h2>\n<!-- /wp:heading -->\n\n<!-- wp:list {\"className\":\"is-style-checks\"} -->\n<ul class=\"wp-block-list is-style-checks\">" . $links . "</ul>\n<!-- /wp:list -->";
				}

				$body .= "\n\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Pricing</h2>\n<!-- /wp:heading -->\n\n<!-- wp:tresundios/price-teaser /-->";

				$faq     = $this->filled_faq( $d['faq'], $slug );
				$content = self::prose( $body )
					. "\n\n<!-- wp:pattern {\"slug\":\"tresundios/process\"} /-->\n\n"
					. '<!-- wp:group {"tagName":"section","className":"block tight tr-sec related-work","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec related-work"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:heading {"className":"split"} -->
<h2 class="wp-block-heading split">Recent work</h2>
<!-- /wp:heading -->

<!-- wp:tresundios/case-studies {"limit":3,"workType":"' . esc_attr( $d['work'] ) . '"} /--></div>
<!-- /wp:group --></section>
<!-- /wp:group -->' . "\n\n"
					. $this->faq_section( $faq, 'Questions about ' . str_replace( ' in Chennai', '', $h1 ) );

				wp_update_post(
					wp_slash(
						array(
							'ID'           => $post->ID,
							'post_content' => $content,
						)
					)
				);
				update_post_meta( $post->ID, 'h1', $h1 );
				update_post_meta( $post->ID, 'faq_items', self::faq_meta( $faq ) );
				update_post_meta( $post->ID, 'deliverables', $d['items'] );
				update_post_meta( $post->ID, '_tresundios_review', 'Drafted service copy and FAQs' );
				$this->log[] = 'wrote service page ' . $slug;
			}
		}
		$this->review[] = 'All service pages: direct answers and FAQs are drafts written for this migration from the original copy. Please review before launch.';
	}

	/**
	 * Create every page (home, pricing, process, about, contact, blog, privacy, terms).
	 */
	public function pages() {
		$order = 0;
		$extra = require TRESUNDIOS_CORE_DIR . '/includes/import/resources.php';
		foreach ( array_merge( $this->content()['pages'], $extra['pages'] ) as $slug => $p ) {
			$parts = array();
			$faq   = isset( $p['faq'] ) ? $this->filled_faq( $p['faq'], $slug ) : array();
			foreach ( $p['sections'] as $section ) {
				if ( 'faq' === $section ) {
					$parts[] = $this->faq_section( $faq, 'Frequently asked questions' );
				} elseif ( 'map' === $section ) {
					$parts[] = '<!-- wp:group {"tagName":"section","className":"block tight tr-sec","layout":{"type":"default"}} -->
<section class="wp-block-group block tight tr-sec"><!-- wp:group {"className":"container","layout":{"type":"default"}} -->
<div class="wp-block-group container"><!-- wp:tresundios/contact-details {"variant":"map"} /--></div>
<!-- /wp:group --></section>
<!-- /wp:group -->';
				} elseif ( 'faq-hub' === $section ) {
					$parts[] = self::prose( '<!-- wp:tresundios/faq-hub /-->' );
				} elseif ( 0 === strpos( $section, 'prose:' ) ) {
					$parts[] = self::prose( "<!-- wp:html -->\n" . $this->fill( substr( $section, 6 ), $slug ) . "\n<!-- /wp:html -->" );
				} else {
					$parts[] = '<!-- wp:pattern {"slug":"tresundios/' . $section . '"} /-->';
				}
			}

			$id = $this->upsert(
				'page',
				$slug,
				array(
					'post_title'   => $p['title'],
					'post_excerpt' => $this->fill( $p['lede'], $slug ),
					'post_content' => implode( "\n\n", $parts ),
					'menu_order'   => ++$order,
				),
				array(
					'translation_key' => 'page:' . $slug,
					'faq_items'       => self::faq_meta( $faq ),
				)
			);
			if ( $id && $faq ) {
				update_post_meta( $id, '_tresundios_review', 'Drafted FAQs' );
			}
			if ( 'blog' === $slug && $id ) {
				update_option( 'page_for_posts', $id );
			}
		}

		// Home: the original one-page layout, now a summary that links to each section page.
		$home = array( 'hero', 'marquee', 'services', 'pricing', 'work', 'process', 'about', 'testimonial', 'trust', 'contact' );
		$id   = $this->upsert(
			'page',
			'home',
			array(
				'post_title'   => 'Home',
				'post_excerpt' => $this->india
					? 'Tresundios helps small and medium businesses in Chennai grow with digital marketing, graphic design, event management, websites and apps. DPIIT-recognised startup.'
					: 'Tresundios helps businesses grow with digital marketing, graphic design, product photography, events, websites and apps, with transparent USD pricing. DPIIT-recognised startup in Chennai.',
				'post_content' => implode(
					"\n\n",
					array_map(
						static function ( $p ) {
							return '<!-- wp:pattern {"slug":"tresundios/' . $p . '"} /-->';
						},
						$home
					)
				),
			),
			array( 'translation_key' => 'page:home' )
		);
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $id );

		// WordPress install defaults, removed only while untouched.
		foreach ( array( array( 'sample-page', 'page' ), array( 'hello-world', 'post' ) ) as $default ) {
			$post = get_page_by_path( $default[0], OBJECT, $default[1] );
			if ( $post && $post->post_modified === $post->post_date ) {
				wp_delete_post( $post->ID, true );
				$this->log[] = 'removed default ' . $default[1] . ' ' . $default[0];
			}
		}

		$this->review[] = 'Privacy policy and Terms contain TODO placeholders and need legal review.';
		$this->review[] = 'Page FAQs (pricing, process, contact) are drafts: please review.';
	}

	/**
	 * Convert simple HTML (h2, h3, p, ul, ol) to core block markup.
	 *
	 * @param string $html HTML.
	 * @return string
	 */
	public static function html_to_blocks( $html ) {
		preg_match_all( '#<(h2|h3|p|ul|ol)([^>]*)>(.*?)</\1>#s', $html, $m, PREG_SET_ORDER );
		$out = array();
		foreach ( $m as $el ) {
			list( , $tag, $attrs, $inner ) = $el;
			$class                         = preg_match( '/class="([^"]+)"/', $attrs, $cm ) ? $cm[1] : '';
			if ( 'h2' === $tag || 'h3' === $tag ) {
				$level = 'h3' === $tag ? '{"level":3} ' : '';
				$out[] = '<!-- wp:heading ' . $level . "-->\n<" . $tag . ' class="wp-block-heading">' . $inner . '</' . $tag . ">\n<!-- /wp:heading -->";
			} elseif ( 'p' === $tag ) {
				$json  = $class ? '{"className":"' . $class . '"} ' : '';
				$cls   = $class ? ' class="' . $class . '"' : '';
				$out[] = '<!-- wp:paragraph ' . $json . "-->\n<p" . $cls . '>' . $inner . "</p>\n<!-- /wp:paragraph -->";
			} else {
				preg_match_all( '#<li>(.*?)</li>#s', $inner, $li );
				$items = '';
				foreach ( $li[1] as $item ) {
					$items .= "<!-- wp:list-item -->\n<li>" . $item . "</li>\n<!-- /wp:list-item -->";
				}
				$ordered = 'ol' === $tag;
				$out[]   = '<!-- wp:list ' . ( $ordered ? '{"ordered":true} ' : '' ) . "-->\n<" . $tag . ' class="wp-block-list">' . $items . '</' . $tag . ">\n<!-- /wp:list -->";
			}
		}
		return implode( "\n\n", $out );
	}

	/**
	 * Import the resource types and starter resources. New resources are created as drafts;
	 * re-runs update the text but never change a resource's status.
	 */
	public function resources() {
		$def = require TRESUNDIOS_CORE_DIR . '/includes/import/resources.php';
		foreach ( $def['types'] as $slug => $desc ) {
			$this->term_description( 'resource_type', $slug, $desc );
		}
		foreach ( $def['resources'] as $r ) {
			$existing = get_page_by_path( $r['slug'], OBJECT, 'resource' );
			$id       = $this->upsert(
				'resource',
				$r['slug'],
				array(
					'post_title'   => $r['title'],
					'post_excerpt' => $this->fill( $r['excerpt'], $r['slug'] ),
					'post_content' => self::prose( self::html_to_blocks( $this->fill( $r['body'], $r['slug'] ) ) ),
					'post_status'  => $existing ? $existing->post_status : 'draft',
				),
				array(
					'translation_key'    => 'resource:' . $r['slug'],
					'reading_time'       => $r['reading'],
					'service_slug'       => $r['service'],
					'_tresundios_review' => 'whitepapers' === $r['type'] ? 'Outline only: needs content and PDF' : 'Drafted resource',
				)
			);
			if ( $id ) {
				wp_set_object_terms( $id, $r['type'], 'resource_type' );
			}
		}
		$this->review[] = 'Resources: 3 guides and 2 checklists are drafts (unpublished) ready for review; 2 whitepapers are outlines with TODO sections and need your content and PDFs before publishing.';
		$this->review[] = 'Glossary and FAQ hub pages are published; glossary definitions are new copy, please review.';
	}

	// ---------------------------------------------------------------------
	// Case studies
	// ---------------------------------------------------------------------

	/**
	 * Import every case study (CASES in assets/js/main.js + card text in index.html) with its image.
	 */
	public function case_studies() {
		$js = $this->read( 'assets/js/main.js' );

		preg_match( '/var RED = [^;]+;/', $js, $line );
		preg_match_all( '/(\w+) = \'([^\']*)\'/', $line[0], $pairs );
		$vars  = array_combine( $pairs[1], $pairs[2] );
		$cases = self::js_value( self::balanced( $js, strpos( $js, '[', strpos( $js, 'var CASES' ) ) ), $vars );

		$focus = array();
		if ( preg_match( '/var FOCUS = (\{[^}]+\})/', $js, $fm ) ) {
			$focus = self::js_value( $fm[1] );
		}

		// Work types: from the .in filter keys (data-k); the .com reel has none, so both sites use one map.
		$kind  = array(
			'app'  => 'mobile-app',
			'ecom' => 'e-commerce',
			'web'  => 'web',
		);
		$types = array(
			'uptown'  => array( 'e-commerce', 'web' ),
			'tef'     => array( 'mobile-app' ),
			'legily'  => array( 'e-commerce', 'web' ),
			'maria'   => array( 'web' ),
			'beyon'   => array( 'web' ),
			'robomed' => array( 'web' ),
		);
		foreach ( $this->dom()->query( '//*[@data-k]' ) as $col ) {
			$a = $this->dom()->query( './/*[@data-case]', $col );
			if ( $a->length ) {
				$types[ $a->item( 0 )->getAttribute( 'data-case' ) ] = array_map(
					static function ( $k ) use ( $kind ) {
						return $kind[ $k ];
					},
					preg_split( '/\s+/', trim( $col->getAttribute( 'data-k' ) ) )
				);
			}
		}

		$alts = array(
			'uptown'  => 'Cup of masala chai tea on a wooden table',
			'tef'     => 'Person walking on a road between grass',
			'legily'  => 'Dropper serum bottle on a yellow background',
			'maria'   => 'Modern hair salon interior with chairs and mirrors',
			'beyon'   => 'People and bicycles on a street in Amsterdam',
			'robomed' => 'Operating room with surgical table and medical equipment',
		);

		foreach ( $cases as $i => $c ) {
			$card    = $this->dom()->query( '//a[@data-case="' . $c['id'] . '"][contains(@class,"proj")]' );
			$card    = $card->length ? $card->item( 0 ) : null;
			$excerpt = $card ? $this->text( './/p', $card ) : '';
			if ( '' === $excerpt ) {
				$excerpt = $c['problem'];
			}
			$slug = basename( wp_parse_url( $c['src'], PHP_URL_PATH ) );

			// Card labels as shown on this site ("Flutter app", "4 months", "Kuwait").
			$card_meta = array();
			if ( $card ) {
				foreach ( $this->dom()->query( './/*[contains(@class,"meta")]/span', $card ) as $span ) {
					$card_meta[] = trim( $span->textContent ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOM API.
				}
			} else {
				foreach ( $this->dom()->query( '//article[contains(@class,"feature")]//*[contains(@class,"meta")]/span' ) as $span ) {
					$card_meta[] = trim( preg_replace( '/^Featured · /u', '', $span->textContent ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- DOM API.
				}
			}
			$card_title = $card ? $this->text( './/h3', $card ) : $this->text( '//article[contains(@class,"feature")]//h3' );

			$id = $this->upsert(
				'case_study',
				$slug,
				array(
					'post_title'   => $c['title'],
					'post_excerpt' => $excerpt,
					'post_content' => '',
					'menu_order'   => $i + 1,
				),
				array(
					'translation_key' => 'case:' . $c['id'],
					'client'          => $c['client'],
					'industry'        => $c['cat'],
					'location'        => $c['market'],
					'duration'        => $c['dur'],
					'challenge'       => $c['problem'],
					'solution'        => $c['solution'],
					'results'         => array_map(
						static function ( $r ) {
							return array(
								'label' => $r[0],
								'text'  => $r[1],
							);
						},
						$c['results']
					),
					'tech_stack'      => $c['tech'],
					'live_url'        => isset( $c['live'] ) ? $c['live'] : '',
					'legacy_url'      => $c['src'],
					'featured'        => 0 === $i,
					'focal'           => isset( $focus[ $c['id'] ] ) ? $focus[ $c['id'] ] : '50% 50%',
					'card_tag'        => $c['tag'],
					'card_cat'        => isset( $card_meta[0] ) ? $card_meta[0] : $c['cat'],
					'card_meta'       => array_slice( $card_meta, 1 ),
					'card_title'      => $card_title ? $card_title : $c['title'],
				)
			);
			if ( ! $id ) {
				continue;
			}
			wp_set_object_terms( $id, isset( $types[ $c['id'] ] ) ? $types[ $c['id'] ] : array( 'web' ), 'work_type' );

			$img = $this->media( 'assets/img/work/' . $c['id'] . '.jpg', isset( $c['alt'] ) ? $c['alt'] : $alts[ $c['id'] ], $id );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
		}
		$this->review[] = 'Case-study images are Unsplash stock photos, not client work: replace with real screenshots when available.';
	}
}
