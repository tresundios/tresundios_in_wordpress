<?php
/**
 * WP-CLI: `wp tresundios <command>`.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tresundios site tooling.
 */
class Tresundios_CLI {

	/**
	 * Configure the current site: region options, default terms, timezone and date format.
	 *
	 * Re-running keeps any values already changed in Settings → Tresundios, except the region.
	 *
	 * ## OPTIONS
	 *
	 * --region=<region>
	 * : Site region.
	 * ---
	 * options:
	 *   - global
	 *   - india
	 * ---
	 *
	 * [--reset]
	 * : Overwrite saved settings with the region defaults.
	 *
	 * ## EXAMPLES
	 *
	 *     wp tresundios setup --region=india
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function setup( $args, $assoc_args ) {
		$region = $assoc_args['region'];
		$saved  = WP_CLI\Utils\get_flag_value( $assoc_args, 'reset' ) ? array() : (array) get_option( TRESUNDIOS_OPTION, array() );

		// Region-specific defaults win only when switching region or resetting.
		$switching = isset( $saved['region'] ) && $saved['region'] !== $region;
		$base      = array_merge( tresundios_shared_defaults(), tresundios_region_defaults( $region ) );
		$settings  = $switching ? array_merge( $saved, tresundios_region_defaults( $region ) ) : array_merge( $base, $saved );

		$settings['region'] = $region;
		update_option( TRESUNDIOS_OPTION, tresundios_sanitize_settings( $settings ) );

		update_option( 'timezone_string', 'Asia/Kolkata' );
		update_option( 'date_format', 'j M Y' );
		update_option( 'blogdescription', 'india' === $region ? 'Digital marketing, design, events, websites and apps in Chennai' : 'Digital marketing, design, events, websites and apps for growing businesses' );

		tresundios_register_content_types();
		tresundios_ensure_terms();

		WP_CLI::success( sprintf( 'Configured %s as region "%s" (%s).', home_url(), $region, tresundios_get( 'currency' ) ) );
	}

	/**
	 * Import content from an original static site into the current site. Safe to re-run.
	 *
	 * ## OPTIONS
	 *
	 * --source=<path>
	 * : Folder with the static site (index.html, assets/).
	 *
	 * [--only=<parts>]
	 * : Comma-separated subset: services, pricing, cases, pages (pages needs services and pricing first).
	 *
	 * ## EXAMPLES
	 *
	 *     wp tresundios import --source=/import/tresundios-in
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function import( $args, $assoc_args ) {
		// Guard against importing the .in source into the .com install or vice versa.
		$source_india = '-in' === substr( untrailingslashit( $assoc_args['source'] ), -3 );
		if ( tresundios_is_india() !== $source_india ) {
			WP_CLI::error( sprintf( '%s does not belong to %s (region "%s").', $assoc_args['source'], home_url(), tresundios_get( 'region' ) ) );
		}
		require_once TRESUNDIOS_CORE_DIR . '/includes/import/class-tresundios-importer.php';

		$parts    = isset( $assoc_args['only'] ) ? array_map( 'trim', explode( ',', $assoc_args['only'] ) ) : array( 'services', 'pricing', 'cases', 'pages' );
		$importer = new Tresundios_Importer( $assoc_args['source'] );

		tresundios_ensure_terms();
		try {
			if ( in_array( 'services', $parts, true ) ) {
				$importer->services();
			}
			if ( in_array( 'pricing', $parts, true ) ) {
				$importer->pricing();
			}
			if ( in_array( 'cases', $parts, true ) ) {
				$importer->case_studies();
			}
			if ( in_array( 'pages', $parts, true ) ) {
				$importer->service_pages();
				$importer->resources();
				$importer->pages();
			}
		} catch ( RuntimeException $e ) {
			WP_CLI::error( $e->getMessage() );
		}

		foreach ( $importer->log as $line ) {
			WP_CLI::log( $line );
		}
		if ( $importer->review ) {
			WP_CLI::log( "\nNeeds review:" );
			foreach ( array_unique( $importer->review ) as $line ) {
				WP_CLI::log( '  - ' . $line );
			}
		}
		WP_CLI::success( sprintf( '%d records processed for %s.', count( $importer->log ), home_url() ) );
	}

	/**
	 * Show or refresh the counterpart site's pairing map used for hreflang.
	 *
	 * ## OPTIONS
	 *
	 * <action>
	 * : refresh (fetch now) or list (keys on both sites).
	 * ---
	 * options:
	 *   - refresh
	 *   - list
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     wp tresundios pairs refresh
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function pairs( $args, $assoc_args ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		if ( ! tresundios_counterpart_url() ) {
			WP_CLI::error( 'TRESUNDIOS_COUNTERPART_URL is not set.' );
		}
		$remote = tresundios_remote_pairs( 'refresh' === $args[0] );
		if ( 'list' === $args[0] ) {
			$local = tresundios_local_pairs();
			$rows  = array();
			foreach ( array_unique( array_merge( array_keys( $local ), array_keys( $remote ) ) ) as $key ) {
				$rows[] = array(
					'key'         => $key,
					'here'        => isset( $local[ $key ] ) ? $local[ $key ] : '',
					'counterpart' => isset( $remote[ $key ] ) ? $remote[ $key ] : '',
				);
			}
			WP_CLI\Utils\format_items( 'table', $rows, array( 'key', 'here', 'counterpart' ) );
			return;
		}
		if ( ! $remote ) {
			WP_CLI::warning( sprintf( 'No pairs from %s (unreachable or no content yet).', tresundios_counterpart_url() ) );
			return;
		}
		WP_CLI::success( sprintf( '%d pairs from %s.', count( $remote ), tresundios_counterpart_url() ) );
	}

	/**
	 * Print the current site's Tresundios settings.
	 *
	 * [--format=<format>]
	 * : table, json or yaml.
	 * ---
	 * default: yaml
	 * ---
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function settings( $args, $assoc_args ) {
		$rows = array();
		foreach ( tresundios_settings() as $key => $value ) {
			$rows[] = array(
				'key'   => $key,
				'value' => is_array( $value ) ? implode( ', ', $value ) : $value,
			);
		}
		WP_CLI\Utils\format_items( $assoc_args['format'], $rows, array( 'key', 'value' ) );
	}

	/**
	 * Apply or export the Rank Math settings in config/rank-math.json.
	 *
	 * ## OPTIONS
	 *
	 * <action>
	 * : import or export.
	 * ---
	 * options:
	 *   - import
	 *   - export
	 * ---
	 *
	 * [--file=<path>]
	 * : Settings file. Default: /config/rank-math.json (mounted from the repo).
	 *
	 * ## EXAMPLES
	 *
	 *     wp tresundios rankmath import
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function rankmath( $args, $assoc_args ) {
		$file    = isset( $assoc_args['file'] ) ? $assoc_args['file'] : '/config/rank-math.json';
		$options = array(
			'general' => 'rank-math-options-general',
			'titles'  => 'rank-math-options-titles',
			'sitemap' => 'rank-math-options-sitemap',
		);

		if ( 'export' === $args[0] ) {
			$out = array( 'modules' => array_values( (array) get_option( 'rank_math_modules', array() ) ) );
			foreach ( $options as $key => $name ) {
				$out[ $key ] = get_option( $name, array() );
			}
			WP_CLI::line( (string) wp_json_encode( $out, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
			return;
		}

		$json = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( ! is_array( $json ) ) {
			WP_CLI::error( 'Cannot read ' . $file );
		}
		$tokens = array(
			'{site_name}' => get_bloginfo( 'name' ),
			'{site_desc}' => get_bloginfo( 'description' ),
			'{logo}'      => get_theme_file_uri( 'assets/img/logo-light.png' ),
		);
		update_option( 'rank_math_modules', $json['modules'] );
		foreach ( $options as $key => $name ) {
			$current = (array) get_option( $name, array() );
			foreach ( $json[ $key ] as $opt => $value ) {
				$current[ $opt ] = is_string( $value ) ? strtr( $value, $tokens ) : $value;
			}
			update_option( $name, $current );
		}
		update_option( 'rank_math_wizard_completed', true );
		update_option( 'rank_math_registration_skip', true );
		flush_rewrite_rules( false );
		WP_CLI::success( 'Rank Math settings applied to ' . home_url() );
	}

	/**
	 * Print and check the JSON-LD on a URL.
	 *
	 * Checks: exactly one JSON-LD @graph, no duplicate @id, no duplicate page-level types,
	 * required properties per type, and that every @id reference resolves inside the graph.
	 *
	 * ## OPTIONS
	 *
	 * <url>
	 * : Page to check.
	 *
	 * [--report-only]
	 * : Only print the report, not the JSON.
	 *
	 * ## EXAMPLES
	 *
	 *     wp tresundios schema-check http://tresundios.in.test/services/devops/
	 *
	 * @subcommand schema-check
	 *
	 * @param array<int, string>    $args       Positional arguments.
	 * @param array<string, string> $assoc_args Flags.
	 */
	public function schema_check( $args, $assoc_args ) {
		$res = wp_remote_get(
			$args[0],
			array(
				'timeout'   => 20,
				'sslverify' => false,
			)
		);
		if ( is_wp_error( $res ) ) {
			WP_CLI::error( $res->get_error_message() );
		}
		preg_match_all( '#<script[^>]+type=["\']application/ld\+json["\'][^>]*>(.*?)</script>#s', wp_remote_retrieve_body( $res ), $m );

		$errors = array();
		$warns  = array();
		if ( 1 !== count( $m[1] ) ) {
			$errors[] = sprintf( 'Expected exactly one JSON-LD block, found %d.', count( $m[1] ) );
		}
		if ( ! $m[1] ) {
			WP_CLI::error( implode( ' ', $errors ) );
		}
		$data = json_decode( $m[1][0], true );
		if ( ! is_array( $data ) || empty( $data['@graph'] ) ) {
			WP_CLI::error( 'JSON-LD is not valid JSON or has no @graph.' );
		}
		if ( ! isset( $assoc_args['report-only'] ) ) {
			WP_CLI::line( (string) wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
		}

		$required  = array(
			'ProfessionalService' => array( 'name', 'url', 'address', 'telephone', 'logo' ),
			'WebSite'             => array( 'name', 'url' ),
			'WebPage'             => array( 'url', 'name' ),
			'Service'             => array( 'name', 'provider', 'url' ),
			'FAQPage'             => array( 'mainEntity' ),
			'CreativeWork'        => array( 'name', 'creator' ),
			'Article'             => array( 'headline', 'author', 'publisher', 'datePublished' ),
			'Event'               => array( 'name', 'startDate', 'location' ),
			'BreadcrumbList'      => array( 'itemListElement' ),
		);
		$singleton = array( 'ProfessionalService', 'Organization', 'WebSite', 'WebPage', 'BreadcrumbList', 'FAQPage', 'Service', 'Article', 'CreativeWork' );
		$ids       = array();
		$types     = array();
		$refs      = array();

		$walk = static function ( $node ) use ( &$walk, &$refs ) {
			if ( ! is_array( $node ) ) {
				return;
			}
			if ( isset( $node['@id'] ) && 1 === count( $node ) ) {
				$refs[] = $node['@id'];
			}
			foreach ( $node as $child ) {
				$walk( $child );
			}
		};

		foreach ( $data['@graph'] as $entity ) {
			$id = isset( $entity['@id'] ) ? $entity['@id'] : '';
			if ( $id && isset( $ids[ $id ] ) ) {
				$errors[] = 'Duplicate @id ' . $id;
			}
			$ids[ $id ] = true;
			foreach ( (array) ( isset( $entity['@type'] ) ? $entity['@type'] : array() ) as $type ) {
				$types[ $type ] = isset( $types[ $type ] ) ? $types[ $type ] + 1 : 1;
				foreach ( isset( $required[ $type ] ) ? $required[ $type ] : array() as $prop ) {
					if ( empty( $entity[ $prop ] ) ) {
						$errors[] = sprintf( '%s is missing "%s".', $type, $prop );
					}
				}
			}
			if ( isset( $entity['offers'] ) ) {
				foreach ( $entity['offers'] as $offer ) {
					if ( ! isset( $offer['price'], $offer['priceCurrency'] ) ) {
						$errors[] = 'Offer without price or priceCurrency: ' . ( isset( $offer['name'] ) ? $offer['name'] : '?' );
					}
				}
			}
			if ( in_array( 'FAQPage', (array) $entity['@type'], true ) ) {
				foreach ( $entity['mainEntity'] as $q ) {
					if ( empty( $q['name'] ) || empty( $q['acceptedAnswer']['text'] ) ) {
						$errors[] = 'FAQ question without name or answer text.';
					}
				}
			}
			foreach ( $entity as $key => $value ) {
				if ( '@id' !== $key ) {
					$walk( $value );
				}
			}
		}
		foreach ( $types as $type => $n ) {
			if ( $n > 1 && in_array( $type, $singleton, true ) ) {
				$errors[] = sprintf( '%s appears %d times.', $type, $n );
			}
		}
		foreach ( array_unique( $refs ) as $ref ) {
			if ( ! isset( $ids[ $ref ] ) ) {
				$warns[] = 'Reference not found in graph: ' . $ref;
			}
		}

		WP_CLI::log(
			"\nTypes: " . implode(
				', ',
				array_map(
					static function ( $t, $n ) {
						return $n > 1 ? "$t ×$n" : $t;
					},
					array_keys( $types ),
					$types
				)
			)
		); // phpcs:ignore Universal.FunctionDeclarations.NoLongClosures.ExceedsRecommended
		foreach ( $warns as $w ) {
			WP_CLI::warning( $w );
		}
		if ( $errors ) {
			WP_CLI::error( implode( "\n", $errors ) );
		}
		WP_CLI::success( 'Schema OK: one @graph, ' . count( $data['@graph'] ) . ' entities, no duplicates.' );
	}
}
