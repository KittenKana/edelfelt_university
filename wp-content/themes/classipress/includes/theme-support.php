<?php
/**
 * ClassiPress Theme Support
 * This file defines 'theme support' so WordPress knows what new features it can handle.
 */

global $cp_options;

// Theme supports
add_theme_support( 'app-versions', array(
	'update_page'     => 'admin.php?page=app-settings&firstrun=1',
	'current_version' => CP_VERSION,
	'option_key'      => 'cp_version',
) );

add_theme_support( 'app-wrapping' );

add_theme_support( 'app-search-index', array(
	'admin_page'           => true,
	'admin_top_level_page' => 'app-dashboard',
	'admin_sub_level_page' => 'app-system-info',
) );

add_theme_support( 'app-login', array(
	'login'         => 'tpl-login.php',
	'register'      => 'tpl-registration.php',
	'recover'       => 'tpl-password-recovery.php',
	'reset'         => 'tpl-password-reset.php',
	'redirect'      => $cp_options->disable_wp_login,
	'settings_page' => 'admin.php?page=app-settings&tab=advanced',
) );

add_theme_support( 'app-feed', array(
	'post_type'          => APP_POST_TYPE,
	'blog_template'      => 'home.php',
	'alternate_feed_url' => $cp_options->feedburner_url,
) );

if ( $cp_options->open_graph_enabled ) {
	add_theme_support( 'app-open-graph', array(
		'default_image' => get_header_image() ? get_header_image() : appthemes_locate_template_uri( 'assets/images/admin/cp_logo_black.png' ),
	) );
}

add_theme_support( 'app-payments', array(
	'items'            => cp_get_addons(),
	'items_post_types' => array( APP_POST_TYPE ),
	'options'          => $cp_options,
) );

add_theme_support( 'app-price-format', array(
	'currency_default'    => $cp_options->currency_code,
	'currency_identifier' => $cp_options->currency_identifier,
	'currency_position'   => $cp_options->currency_position,
	'thousands_separator' => $cp_options->thousands_separator,
	'decimal_separator'   => $cp_options->decimal_separator,
	'hide_decimals'       => $cp_options->hide_decimals,
) );

add_theme_support( 'app-plupload', array(
	'max_file_size'   => $cp_options->max_image_size,
	'min_file_width'  => $cp_options->min_file_width,
	'min_file_height' => $cp_options->min_file_height,
	'allowed_files'   => $cp_options->num_images,
	'disable_switch'  => false,
) );

if ( $cp_options->stats_enabled ) {
	add_theme_support( 'app-stats', array(
		'cache'       => 'today',
		'table_daily' => 'cp_ad_pop_daily',
		'table_total' => 'cp_ad_pop_total',
		'meta_daily'  => 'cp_daily_count',
		'meta_total'  => 'cp_total_count',
	) );
}

add_theme_support( 'app-reports', array(
	'post_type'            => array( APP_POST_TYPE ),
	'options'              => $cp_options,
	'admin_top_level_page' => 'app-dashboard',
	'admin_sub_level_page' => 'app-settings',
) );

add_theme_support( 'app-comment-counts' );

add_theme_support( 'post-thumbnails' );

add_theme_support( 'automatic-feed-links' );

add_theme_support( 'app-form-progress', array(
	'checkout_types' => array(
		'create-listing' => array(
			'steps' => array(
				'select-category'     => array( 'title' => __( 'Category', APP_TD ) ),
				'listing-details'     => array( 'title' => __( 'Details', APP_TD ) ),
				'listing-preview'     => array( 'title' => __( 'Preview', APP_TD ) ),
				'select-plan'         => array( 'title' => __( 'Checkout', APP_TD ) ),
				'gateway-select'      => array( 'map_to' => 'select-plan' ),
				'gateway-process'     => array( 'map_to' => 'select-plan' ),
				'listing-submit-free' => array( 'title' => __( 'Thank You', APP_TD ) ),
				'order-summary'       => array( 'title' => __( 'Thank You', APP_TD ) ),
			),
		),
		'membership-purchase' => array(
			'steps' => array(
				'select-membership'  => array( 'title' => __( 'Select Membership', APP_TD ) ),
				'preview-membership' => array( 'title' => __( 'Preview', APP_TD ) ),
				'gateway-select'     => array( 'title' => __( 'Pay', APP_TD ) ),
				'order-summary'      => array( 'title' => __( 'Thank You', APP_TD ) ),
				'gateway-process'    => array( 'map_to' => 'gateway-select' )
			),
		),
	),
) );

/**
 * AppThemes updater not found notice.
 *
 * @since 3.5
 */
add_theme_support( 'app-require-updater', true );

/**
 * Media Manager.
 *
 * @since 3.5
 */
add_theme_support( 'app-media-manager' );

/**
 * Add-ons Marketplace.
 *
 * @since 3.5
 */
add_theme_support( 'app-addons-mp', array(
	'product' => array( 520 ),
) );

/**
 * Enable support for a custom logo.
 *
 * @since 4.0.0
 */
add_theme_support( 'custom-logo', array(
	'height'      => 50,
	'width'       => 150,
	'flex-width'  => true,
	'flex-height' => true,
) );

/**
 * Enable support for a custom background image.
 *
 * @since 4.0.0
 */
add_theme_support( 'custom-background', array(
	'default-color'    => '#F3F3F4',
	'default-image'    => '',
	'wp-head-callback' => 'cp_custom_background_cb',
) );

/**
 * Enable support for a custom header media.
 *
 * @since 4.2.0
 */
add_theme_support( 'custom-header', array(
	'flex-width'  => true,
	'flex-height' => true,
	'video'       => true,
	'width'       => 2000,
	'height'      => 1200,
) );

/**
 * Geo Query 2 support
 */

add_theme_support( 'app-geo-2', array(
	'options' => $cp_options,
) );

function cp_map_icon( $args ) {
	$scheme = get_theme_mod( 'color_scheme', 'red' );
	$main_color = '#B22222';

	$schemes = array(
		'blue'     => '#0E5E86',
		'green'    => '#4CA24E',
		'orange'   => '#FF8736',
		'red'      => '#B22222',
		'teal'     => '#2DB6A0',
		'purple'   => '#6E558A',
		'pink'     => '#FF7C7E',
		'magenta'  => '#971D64',
		'charcoal' => '#383838',
		'custom'   => get_theme_mod( 'color_scheme_custom', '#1AB394' ),
	);

	if ( ! empty( $schemes[ $scheme ] ) ) {
		$main_color = $schemes[ $scheme ];
	}

	if ( ! empty( $main_color ) ) {
		$args['app_icon_color'] = $main_color;
	}

	return $args;
}
add_filter( 'appthemes_map_icon', 'cp_map_icon' );

/**
 * Register app_geodata table with custom name.
 *
 * So we override the original table registration with ClassiPress custom name.
 */
function cp_register_custom_geo_table() {
	remove_action( 'appthemes_first_run', array( 'APP_Geo_Query', 'install' ), 9 );

	// Temporary, until Geo Query 2 support will be added.
	remove_action( 'parse_query', array( 'APP_Geo_Query', 'parse_query' ) );
	remove_filter( 'posts_clauses', array( 'APP_Geo_Query', 'posts_clauses' ), 10, 2 );
}
add_action( 'after_setup_theme', 'cp_register_custom_geo_table', 1000 );

/**
 * Custom WordPress editor stylesheet.
 *
 * @since 4.0.0
 */
 // Minimize prod or show expanded in dev.
$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
add_editor_style( get_template_directory_uri() . "/assets/css/editor-style{$min}.css" );

/**
 * Add support for the language files and set location.
 *
 * @since 3.6.0
 */
function cp_setup_language_support() {
	/**
	 * We want more control over the language file location.
	 *
	 * @todo Remove this once the function has been deprecated from theme-framework.
	 *
	 * @since 3.6.0
	 */
	remove_action( 'appthemes_theme_framework_loaded', 'appthemes_load_textdomain' );

	/**
	 * Add support for language files.
	 *
	 * Looks in WP_LANG_DIR . '/themes/' first otherwise
	 * For example: wp-content/languages/themes/vantage-de_DE.mo
	 *
	 * Otherwise defaults to: "wp-content/themes/vantage/languages/$domain . '-' . $locale.mo"
	 *
	 * @since 3.6.0
	 */
	load_theme_textdomain( APP_TD, get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'cp_setup_language_support' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since 3.6.0
 */
function cp_content_width() {
	/**
	 * Filter the content width in pixels.
	 *
	 * @since 3.6.0
	 *
	 * @param int $width The content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'cp_content_width', 745 );
}
add_action( 'after_setup_theme', 'cp_content_width', 0 );

/**
 * Adds reCaptcha support
 * @since 3.2
 *
 * @return void
 */
function cp_recaptcha_support() {
	global $cp_options;

	if ( ! $cp_options->captcha_enable ) {
		return;
	}

	add_theme_support( 'app-recaptcha', array(
		'api_version' => $cp_options->captcha_version,
		'score'       => $cp_options->captcha_score,
		'theme'       => $cp_options->captcha_theme,
		'public_key'  => $cp_options->captcha_public_key,
		'private_key' => $cp_options->captcha_private_key,
	) );

	// Integrate recaptcha on the User Registration form.
	add_action( 'appthemes_before_login_template', 'cp_before_login_template' );
	add_filter( 'registration_errors', 'cp_recaptcha_verify' );
}
add_action( 'appthemes_init', 'cp_recaptcha_support' );

function cp_recaptcha_verify( $errors ) {

	$response = appthemes_recaptcha_verify();
	if ( is_wp_error( $response ) ) {

		foreach ( $response->get_error_codes() as $code ) {
			$errors->add( $code, $response->get_error_message( $code ) );
		}

	}

	return $errors;
}

function cp_before_login_template( $action ) {
	if ( 'register' !== $action ) {
		return;
	}

	appthemes_enqueue_recaptcha_scripts();
}
