<?php
/**
 * Form fields for the custom front-end password reset page.
 *
 * Designed for use with the Foundation 6.x framework.
 *
 * @package AppThemes
 * @since 1.1.0
 */

?>

<form action="<?php echo appthemes_get_password_reset_url( 'login_post' ); ?>" method="post" class="login-form password-reset-form" name="resetpassform" id="login-form">

	<fieldset>

		<label>
			<?php _e( 'New password', APP_TD ); ?>
			<input type="password" id="pass1" name="pass1" class="text required" autocomplete="off" data-pw="<?php echo esc_attr( wp_generate_password( 18 ) ); ?>" aria-describedby="pass-strength-result" />
		</label>

		<label><?php _e( 'Confirm new password', APP_TD ); ?>
			<input type="password" name="pass2" value="" autocomplete="off" />
		</label>

		<div class="strength-meter">
			<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', APP_TD ); ?></div>
		</div>

		<p class="description indicator-hint"><?php echo wp_get_password_hint(); ?></p>

		<?php
		/**
		 * Fires following the user password reset fields.
		 *
		 * @since 1.0.0
		 *
		 */
		do_action( 'resetpass_form', get_user_by( 'login', $_GET['login'] ) );
		?>

		<input type="submit" name="resetpass" id="resetpass" class="button expanded" value="<?php esc_attr_e( 'Reset Password', APP_TD ); ?>" />

	</fieldset>

	<input type="hidden" id="user_login" value="<?php echo esc_attr( $_GET['login'] ); ?>" autocomplete="off" />

</form>

<!-- autofocus the field -->
<script>try{document.getElementById('pass1').focus();}catch(e){}</script>
