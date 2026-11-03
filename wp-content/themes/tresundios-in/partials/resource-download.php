<?php
/**
 * Download button for the current resource (.in).
 *
 * @package Tresundios\Theme\In
 *
 * @var string $data File URL.
 */

defined( 'ABSPATH' ) || exit;

if ( ! $data ) {
	return;
}
?>
<p class="res-download"><a class="btn-tr btn-tr-primary" href="<?php echo esc_url( $data ); ?>" download><span><?php esc_html_e( 'Download the PDF', 'tresundios' ); ?></span> <i class="arrow" aria-hidden="true">↓</i></a></p>
