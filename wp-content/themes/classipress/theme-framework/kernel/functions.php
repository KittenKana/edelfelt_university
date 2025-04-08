<?php
/**
 * Theme Framework API
 *
 * @package ThemeFramework\Functions
 */

/**
 * Loads the language-specific translation .mo file.
 * Looks in WP_LANG_DIR . '/themes/' first otherwise
 * uses the .mo in theme's get_template_directory().
 *
 * @since 1.0.0
 *
 * @return void
 */
function appthemes_load_textdomain() {

	load_theme_textdomain( APP_TD, get_template_directory() );
}

/**
 * Checks if a file is located in template directory.
 *
 * @since 1.0.0
 * @param string $file A path to file
 *
 * @return bool True if file is located in template directory
 */
function appthemes_in_template_directory( $file = false ) {
	$theme_dir = realpath( get_template_directory() );

	if ( ! $file ) {
		$file = dirname( __FILE__ );
	}

	return (bool) ( strpos( $file, $theme_dir ) !== false );
}

/**
 * Generates a better title tag than wp_title().
 */
function appthemes_title_tag( $title ) {
	global $page, $paged;

	$parts = array();

	if ( ! empty( $title ) ) {
		$parts[] = $title;
	}

	if ( is_home() || is_front_page() ) {
		$blog_title = get_bloginfo( 'name' );

		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ! is_paged() ) {
			$blog_title .= ' - ' . $site_description;
		}

		$parts[] = $blog_title;
	}

	if ( ! is_404() && ( $paged >= 2 || $page >= 2 ) ) {
		$parts[] = sprintf( __( 'Page %s', APP_TD ), max( $paged, $page ) );
	}

	$parts = apply_filters( 'appthemes_title_parts', $parts );

	return implode( " - ", $parts );
}

/**
 * Includes custom post types into main feed, hook to 'request' filter
 *
 * @since 1.0.0
 * @param array $query_vars
 *
 * @return array
 */
function appthemes_modify_feed_content( $query_vars ) {

	if ( ! current_theme_supports( 'app-feed' ) ) {
		return $query_vars;
	}

	list( $options ) = get_theme_support( 'app-feed' );

	if ( isset( $query_vars['feed'] ) && ! isset( $query_vars['post_type'] ) ) {
		$query_vars['post_type'] = array( 'post', $options['post_type'] );
	}

	return $query_vars;
}

/**
 * Returns user edit profile url.
 *
 * @return string
 */
function appthemes_get_edit_profile_url() {
	if ( $page_id = APP_User_Profile::get_id() ) {
		return get_permalink( $page_id );
	}

	return get_edit_profile_url( get_current_user_id() );
}

/**
 * Returns an array of user profile options for Display Name field.
 *
 * @param int $user_id (optional)
 *
 * @return array
 */
function appthemes_get_user_profile_display_name_options( $user_id = 0 ) {
	$public_display = array();

	if ( ! $user_id && is_user_logged_in() ) {
		$user_id = get_current_user_id();
	}

	if ( ! $user_id || ! $user = get_user_by( 'id', $user_id ) ) {
		return $public_display;
	}

	$public_display['display_nickname'] = $user->nickname;
	$public_display['display_username'] = $user->user_login;

	if ( ! empty( $user->first_name ) ) {
		$public_display['display_firstname'] = $user->first_name;
	}

	if ( ! empty( $user->last_name ) ) {
		$public_display['display_lastname'] = $user->last_name;
	}

	if ( ! empty( $user->first_name ) && ! empty( $user->last_name ) ) {
		$public_display['display_firstlast'] = $user->first_name . ' ' . $user->last_name;
		$public_display['display_lastfirst'] = $user->last_name . ' ' . $user->first_name;
	}

	// Only add this option if it isn't duplicated elsewhere
	if ( ! in_array( $user->display_name, $public_display ) ) {
		$public_display = array( 'display_displayname' => $user->display_name ) + $public_display;
	}

	$public_display = array_map( 'trim', $public_display );
	$public_display = array_unique( $public_display );

	return apply_filters( 'appthemes_get_user_profile_display_name_options', $public_display, $user_id );
}

/**
 * Retrieves Path to the template file
 *
 * @return string Path to the template file
 */
function app_template_path() {
	return APP_Wrapping::get_main_template();
}

function app_template_base() {
	return APP_Wrapping::get_base();
}

/**
 * Email login credentials to a newly-registered user.
 * A new user registration notification is also sent to admin email.
 *
 * @param int    $user_id        User ID.
 * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
 *
 * @return void
 */
function appthemes_new_user_notification( $user_id, $plaintext_pass = '' ) {
	$user = get_userdata( $user_id );

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	$subject = sprintf( __( '[%s] New User Registration', APP_TD ), $blogname );

	$message  = html( 'p', sprintf( __( 'New user registration on your site %s:', APP_TD ), $blogname ) ) . PHP_EOL;
	$message .= html( 'p', sprintf( __( 'Username: %s', APP_TD ), $user->user_login ) ) . PHP_EOL;
	$message .= html( 'p', sprintf( __( 'E-mail: %s', APP_TD ), $user->user_email ) ) . PHP_EOL;

	$email = array( 'to' => get_option( 'admin_email' ), 'subject' => $subject, 'message' => $message );
	$email = apply_filters( 'appthemes_email_admin_new_user', $email, $user_id, $plaintext_pass );

	appthemes_send_email( $email['to'], $email['subject'], $email['message'] );

	if ( empty( $plaintext_pass ) ) {
		return;
	}

	$subject = sprintf( __( '[%s] Your username', APP_TD ), $blogname );

	$message  = html( 'p', sprintf( __( 'Username: %s', APP_TD ), $user->user_login ) ) . PHP_EOL;
	$message .= html( 'p', html_link( wp_login_url() ) ) . PHP_EOL;

	$email = array( 'to' => $user->user_email, 'subject' => $subject, 'message' => $message );
	$email = apply_filters( 'appthemes_email_user_new_user', $email, $user_id, $plaintext_pass );

	appthemes_send_email( $email['to'], $email['subject'], $email['message'] );
}

/**
 * Adds 'login_post' context which changes URL scheme and escape URL for displaying on site
 *
 * @param string $url
 * @param string $original_url
 * @param string $context
 *
 * @return string
 */
function appthemes_add_login_post_context( $url, $original_url, $context ) {

	if ( $context == 'login_post' ) {
		$url = set_url_scheme( $url, $context );
		$url = wp_kses_normalize_entities( $url );
		$url = str_replace( '&amp;', '&#038;', $url );
		$url = str_replace( "'", '&#039;', $url );
	}

	return $url;
}

/**
 * Displays notice on settings page about disabled redirect from WordPress login pages.
 *
 * @return void
 */
function appthemes_disabled_login_redirect_notice() {
	global $pagenow;

	if ( ! current_theme_supports( 'app-login' ) || ! isset( $_GET['page'] ) ) {
		return;
	}

	list( $options ) = get_theme_support( 'app-login' );

	if ( ! isset( $options['redirect'] ) || $options['redirect'] ) {
		return;
	}

	$parsed_url = parse_url( $options['settings_page'] );
	parse_str( $parsed_url['query'], $url_args );

	if ( $pagenow != $parsed_url['path'] || $_GET['page'] != $url_args['page'] ) {
		return;
	}

	$notice = __( 'The default WordPress login page is still accessible.', APP_TD ) . '<br />';
	$notice .= sprintf( __( 'After you ensure that permalinks on your site are working correctly and you are not using any "maintenance mode" plugins, please disable it in your <a href="%s">theme settings</a>.', APP_TD ), $options['settings_page'] );
	echo scb_admin_notice( $notice );
}
