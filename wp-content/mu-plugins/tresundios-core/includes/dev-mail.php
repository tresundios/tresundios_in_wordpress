<?php
/**
 * Local development only: route wp_mail through Mailpit. Production SMTP is configured separately.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

if ( 'local' === wp_get_environment_type() && defined( 'TRESUNDIOS_SMTP_HOST' ) ) {
	add_action(
		'phpmailer_init',
		static function ( $phpmailer ) {
			$phpmailer->isSMTP();
			$phpmailer->Host     = TRESUNDIOS_SMTP_HOST; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->Port     = defined( 'TRESUNDIOS_SMTP_PORT' ) ? TRESUNDIOS_SMTP_PORT : 1025; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$phpmailer->SMTPAuth = false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		}
	);
}
