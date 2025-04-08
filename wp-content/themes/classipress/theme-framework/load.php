<?php
/**
 * AppThemes Theme Framework load
 *
 * @package ThemeFramework
 * @since 1.0.0
 */

define( 'APP_THEME_FRAMEWORK_VER', '2.0.0' );
define( 'APP_THEME_FRAMEWORK_DIR', dirname( __FILE__ ) );
if ( ! defined( 'APP_THEME_FRAMEWORK_DIR_NAME' ) ) {
	define( 'APP_THEME_FRAMEWORK_DIR_NAME', 'theme-framework' );
}

if ( ! defined( 'APP_THEME_FRAMEWORK_URI' ) ) {
	define( 'APP_THEME_FRAMEWORK_URI', get_template_directory_uri() . '/' . APP_THEME_FRAMEWORK_DIR_NAME );
}

require_once dirname( __FILE__ ) . '/kernel/class-autoload.php';
require_once dirname( __FILE__ ) . '/kernel/functions.php';
require_once dirname( __FILE__ ) . '/kernel/deprecated.php';
require_once dirname( __FILE__ ) . '/lib/customizer-custom-controls/load.php';

// Theme specific items.
if ( appthemes_in_template_directory() || apply_filters( 'appthemes_force_load_theme_specific_items', false ) ) {
	// Default filters.
	add_filter( 'wp_title', 'appthemes_title_tag', 9 );

	appthemes_load_textdomain();
}


// Load the breadcrumbs trail.
if ( ! is_admin() && ! function_exists( 'breadcrumb_trail' ) ) {
	require_once dirname( __FILE__ ) . '/kernel/breadcrumb-trail.php';
}

APP_Theme_Framework_Autoload::add_class_map( array(
	'APP_User_Profile'            => dirname( __FILE__ ) . '/kernel/view-edit-profile.php',
	'APP_Mail_From'               => dirname( __FILE__ ) . '/kernel/mail-from.php',
	'APP_Social_Networks'         => dirname( __FILE__ ) . '/kernel/social.php',
	'APP_Wrapping'                => dirname( __FILE__ ) . '/includes/wrapping.php',
	'APP_Login_Base'              => dirname( __FILE__ ) . '/includes/views-login.php',
	'APP_Login'                   => dirname( __FILE__ ) . '/includes/views-login.php',
	'APP_Password_Recovery'       => dirname( __FILE__ ) . '/includes/views-login.php',
	'APP_Password_Reset'          => dirname( __FILE__ ) . '/includes/views-login.php',
	'APP_Registration'            => dirname( __FILE__ ) . '/includes/views-login.php',
	'APP_HTML_Term_Description'   => dirname( __FILE__ ) . '/admin/class-html-term-description.php',
) );

APP_Theme_Framework_Autoload::register();

add_action( 'after_setup_theme', '_appthemes_load_theme_features', 999 );
add_action( 'wp_enqueue_scripts', '_appthemes_register_theme_scripts' );
add_action( 'admin_enqueue_scripts', '_appthemes_register_theme_scripts' );

/**
 * Load framework features.
 */
function _appthemes_load_theme_features() {

	if ( current_theme_supports( 'app-wrapping' ) ) {
		add_filter( 'template_include', array( 'APP_Wrapping', 'wrap' ), 99 );
	}

	if ( current_theme_supports( 'app-login' ) ) {

		list( $templates ) = get_theme_support( 'app-login' );

		new APP_Login( $templates['login'] );
		new APP_Registration( $templates['register'] );
		new APP_Password_Recovery( $templates['recover'] );
		new APP_Password_Reset( $templates['reset'] );

		add_action( 'admin_notices', 'appthemes_disabled_login_redirect_notice' );
		add_filter( 'clean_url', 'appthemes_add_login_post_context', 10, 3 );
	}

	if ( current_theme_supports( 'app-feed' ) ) {
		add_filter( 'request', 'appthemes_modify_feed_content' );
	}

	if ( is_admin() && current_theme_supports( 'app-html-term-description' ) ) {

		$args_sets = get_theme_support( 'app-html-term-description' );

		if ( ! is_array( $args_sets ) ) {
			$args_sets = array();
		}

		foreach ( $args_sets as $args ) {
			$args = wp_parse_args( (array) $args, array( 'taxonomy' => '', 'editor_settings' => array() ) );
			new APP_HTML_Term_Description( $args['taxonomy'], $args['editor_settings'] );
		}
	}

	/**
	 * Fires after the AppThemes framework is loaded.
	 *
	 * @since 1.0.0
	 */
	do_action( 'appthemes_theme_framework_loaded' );
}

/**
 * Register frontend/backend scripts and styles for later enqueue.
 *
 * @since 1.0.0
 *
 * return void
 */
function _appthemes_register_theme_scripts() {

	// Minimize prod or show expanded in dev.
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	require_once APP_THEME_FRAMEWORK_DIR . '/js/localization.php';

	wp_register_script( 'colorbox', APP_THEME_FRAMEWORK_URI . "/js/colorbox/jquery.colorbox{$min}.js", array( 'jquery' ), '1.6.1' );
	wp_register_style( 'colorbox', APP_THEME_FRAMEWORK_URI . "/js/colorbox/colorbox{$min}.css", false, '1.6.1' );
	wp_register_style( 'font-awesome', APP_THEME_FRAMEWORK_URI . "/lib/font-awesome/css/font-awesome{$min}.css", false, '4.7.0' );

	wp_register_script( 'footable', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable{$min}.js", array( 'jquery' ), '2.0.3' );
	wp_register_script( 'footable-grid', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.grid{$min}.js", array( 'footable' ), '2.0.3' );
	wp_register_script( 'footable-sort', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.sort{$min}.js", array( 'footable' ), '2.0.3' );
	wp_register_script( 'footable-filter', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.filter{$min}.js", array( 'footable' ), '2.0.3' );
	wp_register_script( 'footable-striping', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.striping{$min}.js", array( 'footable' ), '2.0.3' );
	wp_register_script( 'footable-paginate', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.paginate{$min}.js", array( 'footable' ), '2.0.3' );
	wp_register_script( 'footable-bookmarkable', APP_THEME_FRAMEWORK_URI . "/js/footable/jquery.footable.bookmarkable{$min}.js", array( 'footable' ), '2.0.3' );

	_appthemes_localize_theme_scripts();
}
