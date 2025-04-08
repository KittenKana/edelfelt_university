<?php
/**
 * Enqueue of admin scripts and styles.
 *
 * @package ClassiPress\Admin\Enqueue
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

add_action( 'admin_enqueue_scripts', 'cp_load_admin_scripts' );

/**
 * Load admin scripts and styles.
 *
 * @todo: selective register/enqueue scripts.
 */
function cp_load_admin_scripts() {
	global $pagenow;


	// Minimize prod or show expanded in dev.
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Set the assets path so we don't repeat ourselves.
	$assets_path = get_template_directory_uri() . '/assets';

	// Load the theme admin scripts.
	wp_enqueue_script( 'theme-admin-scripts', get_template_directory_uri() . "/assets/js/theme-admin-scripts{$min}.js", array( 'jquery', 'media-upload', 'thickbox', 'wp-color-picker' ), CP_VERSION, true );

	// Load the theme admin stylesheet.
	wp_enqueue_style( 'app-admin', get_template_directory_uri() . "/assets/css/style-admin{$min}.css", array(), CP_VERSION );


	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_style( 'thickbox' ); // needed for image upload

	//TODO: For now we call these on all admin pages because of some javascript errors, however it should be registered per admin page (like wordpress does it)
	wp_enqueue_script( 'jquery-ui-sortable' ); //this script has issues on the page edit.php?post_type=ad_listing

	wp_enqueue_script( 'jquery-ui-slider' );

	wp_enqueue_style( 'jquery-ui-style' );
	wp_enqueue_style( 'wp-jquery-ui-datepicker', APP_FRAMEWORK_URI . '/styles/datepicker/datepicker.css' );

	 // only trigger this on CP edit pages otherwise it causes a conflict with edit ad and edit post meta field buttons
	if ( $pagenow == 'edit.php' && ! empty( $_GET['page'] ) && in_array( $_GET['page'], array( 'fields', 'layouts' ) ) ) {
		wp_enqueue_script( 'validate' );
		wp_enqueue_script( 'validate-lang' );
	}

	wp_enqueue_script( 'flot', $assets_path . "/js/lib/flot/jquery.flot{$min}.js", array( 'jquery' ), '0.8.3' );
	wp_enqueue_script( 'flot-time', $assets_path . "/js/lib/flot/jquery.flot.time{$min}.js", array( 'flot' ), '0.8.3' );

	// Load the font awesome toolkit from framework.
	wp_enqueue_style( 'font-awesome' );

	/* Script variables */
	$params = array(
		'text_check_all' => __( 'check all', APP_TD ),
		'text_uncheck_all' => __( 'uncheck all', APP_TD ),
		'text_before_delete_tables' => __( 'WARNING: You are about to completely delete all ClassiPress database tables. Are you sure you want to proceed? (This cannot be undone)', APP_TD ),
		'text_before_delete_options' => __( 'WARNING: You are about to completely delete all ClassiPress configuration options from the wp_options database table. Are you sure you want to proceed? (This cannot be undone)', APP_TD ),
	);
	wp_localize_script( 'theme-admin-scripts', 'classipress_admin_params', $params );

}
