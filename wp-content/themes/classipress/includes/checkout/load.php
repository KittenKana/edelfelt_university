<?php
/**
 * Checkouts module load
 *
 * @package Components\Checkouts
 */

if ( ! defined( 'APP_CHECKOUTS_URI' ) ) {
	define( 'APP_CHECKOUTS_URI', get_template_directory_uri() . '/includes/checkout' );
}

require dirname( __FILE__ ) . '/class-autoload.php';

APP_Checkout_Autoload::add_class_map( array(
	'APP_List'                       => APP_FRAMEWORK_DIR . '/admin/class-list.php',
	'APP_Dynamic_Checkout'           => dirname( __FILE__ ) . '/class-checkout.php',
	'APP_Relational_Checkout_List'   => dirname( __FILE__ ) . '/class-checkout-list.php',
	'APP_Checkout_Step'              => dirname( __FILE__ ) . '/class-checkout-step.php',
	'APP_Current_Checkout'           => dirname( __FILE__ ) . '/class-current-checkout.php',

	'APP_Progress_Step'              => dirname( __FILE__ ) . '/progress/class-progress-step.php',
	'APP_Progress_View'              => dirname( __FILE__ ) . '/progress/class-progress-view.php',
	'APP_Progress_Ajax_View'         => dirname( __FILE__ ) . '/progress/class-progress-ajax-view.php',

	'APP_Progress_Upgrade_Step'      => dirname( __FILE__ ) . '/progress/class-progress-upgrade-step.php',
	'APP_Progress_Ajax_Upgrade_View' => dirname( __FILE__ ) . '/progress/class-progress-ajax-upgrade-view.php',
) );

APP_Checkout_Autoload::register();

require dirname( __FILE__ ) . '/checkout-tags.php';

if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
	require dirname( __FILE__ ) . '/checkout-dev.php';
}
