<?php
/**
 * Form fields for the custom front-end registration page.
 *
 * Designed for use with the Foundation 6.x framework.
 *
 * @package AppThemes
 * @since 1.1.0
 */

?>

<form action="<?php echo appthemes_get_registration_url( 'login_post' ); ?>" method="post" class="login-form register-form" name="registerform" id="login-form">

	<fieldset>

		<label>
			<?php _e( 'Username', APP_TD ); ?>
			<input type="text" name="user_login" tabindex="1" class="required" id="user_login" value="<?php if ( isset( $_POST['user_login'] ) ) echo esc_attr( wp_unslash( $_POST['user_login'] ) ); ?>" />
		</label>

		<label>
			<?php _e( 'Email', APP_TD ); ?>
			<input type="email" name="user_email" tabindex="2" class="required" id="user_email" value="<?php if ( isset( $_POST['user_email'] ) ) echo esc_attr( wp_unslash( $_POST['user_email'] ) ); ?>" />
		</label>

		<?php if ( apply_filters( 'show_password_fields_on_registration', true ) ) : ?>

			<div class="user-pass1-wrap manage-password">

				<label for="pass1"><?php _e( 'Password', APP_TD ); ?></label>

				<?php $initial_password = isset( $_POST['pass1'] ) ? stripslashes( $_POST['pass1'] ) : wp_generate_password( 18 ); ?>

				<input tabindex="3" type="password" id="pass1" name="pass1" class="text required" autocomplete="off" data-pw="<?php echo esc_attr( $initial_password ); ?>" aria-describedby="pass-strength-result" />
				<input type="text" style="display:none" name="pass2" id="pass2" autocomplete="off" />

				<button type="button" class="wp-hide-pw hide-if-no-js" data-start-masked="<?php echo (int) isset( $_POST['pass1'] ); ?>" data-toggle="0" aria-label="<?php esc_attr_e( 'Hide password', APP_TD ); ?>">
					<span class="dashicons dashicons-hidden"></span>
					<span class="text"><?php _e( 'Hide', APP_TD ); ?></span>
				</button>

			</div>

			<div class="strength-meter">
				<div id="pass-strength-result" class="hide-if-no-js"><?php _e( 'Strength indicator', APP_TD ); ?></div>
			</div>

		<?php endif; ?>

		<?php
		/**
		 * Fires following the core fields in the user registration form.
		 *
		 * @since 1.0.0
		 */
		do_action( 'register_form' );
		?>

		<input type="submit" name="register" id="register" class="button expanded" value="<?php _e( 'Register', APP_TD ); ?>" />

		<p id="nav" class="text-center">
			<a href="<?php echo esc_url( wp_login_url() ); ?>"><?php _e( 'Log in', APP_TD ); ?></a> |
			<a href="<?php echo esc_url( appthemes_get_password_recovery_url() ); ?>"><?php _e( 'Lost your password?', APP_TD ); ?></a>
		</p>

	</fieldset>

	<?php echo APP_Login::redirect_field(); ?>

</form>

<!-- autofocus the field -->
<script type="text/javascript">try{document.getElementById('user_login').focus();}catch(e){}</script>
