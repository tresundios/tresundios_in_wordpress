<?php
/**
 * Project enquiry form (.in): labelled fields, honeypot, nonce. Works without JavaScript.
 *
 * @package Tresundios\Theme\In
 */

defined( 'ABSPATH' ) || exit;

// Included from tresundios_render_template(), so these variables are function-scoped, not globals.
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound, WordPress.WP.GlobalVariablesOverride.Prohibited

wp_enqueue_script( 'tresundios-enquiry' );

$tresundios_status = tresundios_enquiry_status();
$tresundios_plan   = isset( $_GET['plan'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['plan'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
$tresundios_svc    = isset( $_GET['service'] ) ? sanitize_text_field( rawurldecode( wp_unslash( $_GET['service'] ) ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
/* translators: %s: plan name and price. */
$tresundios_plan_msg = __( 'I’m interested in: %s.', 'tresundios' );
?>
<form class="enquiry glass" id="enquiry" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-enquiry
	data-msg-required="<?php esc_attr_e( 'Please add your name and an email or phone number so we can reply.', 'tresundios' ); ?>"
	data-msg-sending="<?php esc_attr_e( 'Sending…', 'tresundios' ); ?>"
	data-msg-failed="<?php echo esc_attr( sprintf( /* translators: 1: email, 2: phone. */ __( 'Could not reach the server. Please email %1$s or WhatsApp %2$s.', 'tresundios' ), tresundios_get( 'contact_email' ), tresundios_get( 'phone' ) ) ); ?>"
	data-msg-plan="<?php echo esc_attr( $tresundios_plan_msg ); ?>">
	<p class="eyebrow"><?php esc_html_e( 'Project enquiry', 'tresundios' ); ?></p>
	<?php tresundios_enquiry_hidden_fields(); ?>
	<div class="row g-3 mt-1">
		<div class="col-sm-6"><label class="form-label" for="f-name"><?php esc_html_e( 'Your name', 'tresundios' ); ?></label><input class="form-control" id="f-name" name="name" required autocomplete="name" placeholder="Priya Raman"></div>
		<div class="col-sm-6"><label class="form-label" for="f-biz"><?php esc_html_e( 'Business', 'tresundios' ); ?></label><input class="form-control" id="f-biz" name="biz" autocomplete="organization" placeholder="Café Kaapi, Adyar"></div>
		<div class="col-sm-6"><label class="form-label" for="f-mail"><?php esc_html_e( 'Email or phone', 'tresundios' ); ?></label><input class="form-control" id="f-mail" name="contact" required autocomplete="email" placeholder="you@business.com"></div>
		<div class="col-sm-6"><label class="form-label" for="f-svc"><?php esc_html_e( 'I need help with', 'tresundios' ); ?></label>
			<select class="form-select" id="f-svc" name="svc">
			<?php
			foreach ( tresundios_enquiry_services() as $tresundios_opt ) :
				?>
				<option<?php selected( $tresundios_svc, $tresundios_opt ); ?>><?php echo esc_html( $tresundios_opt ); ?></option><?php endforeach; ?></select></div>
		<div class="col-sm-6"><label class="form-label" for="f-budget"><?php esc_html_e( 'Project budget', 'tresundios' ); ?></label>
			<select class="form-select" id="f-budget" name="budget">
			<?php
			foreach ( tresundios_enquiry_budgets() as $tresundios_opt ) :
				?>
				<option><?php echo esc_html( $tresundios_opt ); ?></option><?php endforeach; ?></select></div>
		<div class="col-12"><label class="form-label" for="f-msg"><?php esc_html_e( 'Tell us about it', 'tresundios' ); ?></label><textarea class="form-control" id="f-msg" name="msg" placeholder="We're opening a second outlet in March and need launch posts, a menu redesign and a small opening event."><?php echo $tresundios_plan ? esc_textarea( sprintf( $tresundios_plan_msg, $tresundios_plan ) ) : ''; ?></textarea></div>
		<div class="d-none" aria-hidden="true"><label><?php esc_html_e( 'Website', 'tresundios' ); ?> <input name="website" tabindex="-1" autocomplete="off"></label></div>
		<div class="col-12"><button class="btn-tr btn-tr-primary" type="submit"><?php esc_html_e( 'Send my enquiry →', 'tresundios' ); ?></button></div>
		<div class="col-12"><div class="note<?php echo $tresundios_status && ! $tresundios_status['ok'] ? ' is-error' : ''; ?>" data-enquiry-out role="status" aria-live="polite"<?php echo $tresundios_status ? '' : ' hidden'; ?>><?php echo $tresundios_status ? esc_html( $tresundios_status['message'] ) : ''; ?></div></div>
	</div>
</form>
