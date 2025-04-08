<?php
/**
 * User Profile views
 *
 * @package ThemeFramework\Views
 */

/**
 * User Profile view page
 */
class APP_User_Profile extends APP_View_Page {

	function __construct() {
		parent::__construct( 'edit-profile.php', __( 'Edit Profile', APP_TD ) );
		add_action( 'init', array( $this, 'update' ) );
	}

	static function get_id() {
		return parent::_get_page_id( 'edit-profile.php' );
	}

	function update() {
		if ( ! isset( $_POST['action'] ) || 'app-edit-profile' != $_POST['action'] ) {
			return;
		}

		check_admin_referer( 'app-edit-profile' );

		require_once ABSPATH . '/wp-admin/includes/user.php';

		$r = edit_user( $_POST['user_id'] );

		if ( is_wp_error( $r ) ) {
			$this->errors = $r;
			foreach ( $this->errors->get_error_codes() as $error ) {
				appthemes_add_notice( $error, $this->errors->get_error_message( $error ), 'error' );
			}
		} else {
			do_action( 'personal_options_update', $_POST['user_id'] );

			appthemes_add_notice( 'updated-profile', __( 'Your profile has been updated.', APP_TD ), 'success' );

			$redirect_url = add_query_arg( array( 'updated' => 'true' ) );
			$redirect_url = esc_url_raw( $redirect_url );

			wp_redirect( $redirect_url );
			exit();
		}
	}

	function template_redirect() {
		// Prevent non-logged-in users from accessing the edit-profile.php page
		appthemes_auth_redirect_login();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	function enqueue_scripts() {
		wp_enqueue_script(
			'app-user-profile',
			APP_THEME_FRAMEWORK_URI . '/js/profile.js',
			array( 'user-profile' ),
			APP_THEME_FRAMEWORK_VER
		);
	}

}
