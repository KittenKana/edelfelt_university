<?php
require_once APP_FRAMEWORK_DIR . '/load-p2p.php';
require_once APP_FRAMEWORK_DIR . '/admin/class-widget.php';

P2P_Autoload::register( 'APP_', dirname( __FILE__ ) );

/**
 * Register widgets template directory.
 *
 * @param array $directories An array of template directories URIs keyed
 *                           with their paths.
 *
 * @return array
 */
function appthemes_widgets_register_template_directories( $directories ) {
	$widgets_dir = get_template_directory() . '/includes/widgets/templates';
	$widgets_uri = get_template_directory_uri() . '/includes/widgets/templates';

	$directories[ $widgets_dir ] = $widgets_uri;

	return $directories;
}
add_filter( 'appthemes_get_template_directories' , 'appthemes_widgets_register_template_directories' );
