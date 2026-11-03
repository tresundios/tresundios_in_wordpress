<?php
/**
 * Bootstrap: constants and includes.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

define( 'TRESUNDIOS_CORE_VERSION', '0.1.0' );
define( 'TRESUNDIOS_CORE_DIR', __DIR__ );
define( 'TRESUNDIOS_CORE_URL', WPMU_PLUGIN_URL . '/tresundios-core' );

require TRESUNDIOS_CORE_DIR . '/includes/options.php';
require TRESUNDIOS_CORE_DIR . '/includes/post-types.php';
require TRESUNDIOS_CORE_DIR . '/includes/meta.php';
require TRESUNDIOS_CORE_DIR . '/includes/dev-mail.php';
require TRESUNDIOS_CORE_DIR . '/includes/data.php';
require TRESUNDIOS_CORE_DIR . '/includes/blocks.php';
require TRESUNDIOS_CORE_DIR . '/includes/enquiry.php';
require TRESUNDIOS_CORE_DIR . '/includes/perf.php';
require TRESUNDIOS_CORE_DIR . '/includes/products.php';
require TRESUNDIOS_CORE_DIR . '/includes/seo/counterparts.php';
require TRESUNDIOS_CORE_DIR . '/includes/seo/hreflang.php';
require TRESUNDIOS_CORE_DIR . '/includes/seo/schema.php';
require TRESUNDIOS_CORE_DIR . '/includes/seo/robots-llms.php';
require TRESUNDIOS_CORE_DIR . '/includes/seo/redirects.php';

if ( is_admin() ) {
	require TRESUNDIOS_CORE_DIR . '/includes/admin/settings-page.php';
}

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require TRESUNDIOS_CORE_DIR . '/includes/cli/class-tresundios-cli.php';
	WP_CLI::add_command( 'tresundios', 'Tresundios_CLI' );
}
