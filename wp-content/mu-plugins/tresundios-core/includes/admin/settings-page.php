<?php
/**
 * Settings → Tresundios: per-site options.
 *
 * @package Tresundios\Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field list for the settings screen: key => [ label, type, help ].
 *
 * @return array<string, array<string, array{0: string, 1: string, 2?: string}>>
 */
function tresundios_settings_fields() {
	return array(
		__( 'Region & currency', 'tresundios' ) => array(
			'region'      => array( __( 'Region', 'tresundios' ), 'select:global=Global (tresundios.com),india=India (tresundios.in)' ),
			'currency'    => array( __( 'Currency', 'tresundios' ), 'select:USD=USD,INR=INR' ),
			'tax_note'    => array( __( 'Tax note after prices', 'tresundios' ), 'text', __( 'e.g. "excl. GST". Leave empty on .com.', 'tresundios' ) ),
			'hreflang'    => array( __( 'hreflang code', 'tresundios' ), 'text', __( '"en" on .com, "en-IN" on .in.', 'tresundios' ) ),
			'site_label'  => array( __( 'Site label in enquiry emails', 'tresundios' ), 'text' ),
			'city_label'  => array( __( 'City used in page headings', 'tresundios' ), 'text' ),
			'area_served' => array( __( 'Areas served (one per line)', 'tresundios' ), 'lines' ),
		),
		__( 'Contact', 'tresundios' )           => array(
			'contact_email' => array( __( 'Email shown on this site', 'tresundios' ), 'email' ),
			'enquiry_to'    => array( __( 'Enquiries are sent to', 'tresundios' ), 'email' ),
			'phone'         => array( __( 'Phone', 'tresundios' ), 'text' ),
			'whatsapp'      => array( __( 'WhatsApp number (digits, with country code)', 'tresundios' ), 'text' ),
			'booking_url'   => array( __( 'Book-a-call URL (Google Calendar)', 'tresundios' ), 'url' ),
		),
		__( 'Business (NAP must match Google Business Profile exactly)', 'tresundios' ) => array(
			'legal_name'     => array( __( 'Legal name', 'tresundios' ), 'text' ),
			'street_address' => array( __( 'Street address', 'tresundios' ), 'text' ),
			'locality'       => array( __( 'City', 'tresundios' ), 'text' ),
			'region_name'    => array( __( 'State', 'tresundios' ), 'text' ),
			'postal_code'    => array( __( 'PIN code', 'tresundios' ), 'text' ),
			'country'        => array( __( 'Country (ISO code)', 'tresundios' ), 'text' ),
			'geo_lat'        => array( __( 'Latitude', 'tresundios' ), 'text' ),
			'geo_lng'        => array( __( 'Longitude', 'tresundios' ), 'text' ),
			'gbp_url'        => array( __( 'Google Business Profile URL', 'tresundios' ), 'url' ),
			'map_embed_url'  => array( __( 'Google Maps embed URL (for /contact/)', 'tresundios' ), 'url' ),
			'same_as'        => array( __( 'Social profiles (one URL per line)', 'tresundios' ), 'lines' ),
		),
		__( 'Registration', 'tresundios' )      => array(
			'cin'          => array( 'CIN', 'text' ),
			'gstin'        => array( 'GSTIN', 'text' ),
			'pan'          => array( 'PAN', 'text' ),
			'dpiit'        => array( __( 'DPIIT certificate', 'tresundios' ), 'text' ),
			'dpiit_valid'  => array( __( 'DPIIT valid until (Y-m-d)', 'tresundios' ), 'text' ),
			'incorporated' => array( __( 'Incorporated (Y-m-d)', 'tresundios' ), 'text' ),
		),
	);
}

add_action(
	'admin_menu',
	static function () {
		add_options_page(
			__( 'Tresundios settings', 'tresundios' ),
			__( 'Tresundios', 'tresundios' ),
			'manage_options',
			'tresundios',
			'tresundios_render_settings_page'
		);
	}
);

/**
 * Render the settings screen.
 */
function tresundios_render_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$values = tresundios_settings();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Tresundios settings', 'tresundios' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'tresundios' ); ?>
			<?php foreach ( tresundios_settings_fields() as $section => $fields ) : ?>
				<h2><?php echo esc_html( $section ); ?></h2>
				<table class="form-table" role="presentation">
					<?php
					foreach ( $fields as $key => $field ) {
						tresundios_render_settings_field( $key, $field, isset( $values[ $key ] ) ? $values[ $key ] : '' );
					}
					?>
				</table>
			<?php endforeach; ?>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * Render one settings row.
 *
 * @param string             $key   Setting key.
 * @param array<int, string> $field Label, type and optional help text.
 * @param mixed              $value Current value.
 */
function tresundios_render_settings_field( $key, $field, $value ) {
	$id   = 'tresundios-' . $key;
	$name = TRESUNDIOS_OPTION . '[' . $key . ']';
	$type = $field[1];
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field[0] ); ?></label></th>
		<td>
			<?php if ( 0 === strpos( $type, 'select:' ) ) : ?>
				<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>">
					<?php
					foreach ( explode( ',', substr( $type, 7 ) ) as $pair ) {
						list( $opt, $label ) = explode( '=', $pair, 2 );
						printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $opt ), selected( $value, $opt, false ), esc_html( $label ) );
					}
					?>
				</select>
			<?php elseif ( 'lines' === $type ) : ?>
				<textarea id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="4" class="large-text"><?php echo esc_textarea( implode( "\n", (array) $value ) ); ?></textarea>
			<?php else : ?>
				<input id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" type="<?php echo esc_attr( $type ); ?>" value="<?php echo esc_attr( (string) $value ); ?>" class="regular-text">
			<?php endif; ?>
			<?php if ( ! empty( $field[2] ) ) : ?>
				<p class="description"><?php echo esc_html( $field[2] ); ?></p>
			<?php endif; ?>
		</td>
	</tr>
	<?php
}
