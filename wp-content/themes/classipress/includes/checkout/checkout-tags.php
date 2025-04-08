<?php
/**
 * Dynamic Checkout API
 *
 * @package Components\Checkouts
 */

/**
 * Setup checkout object.
 *
 * Hash string allows to restore existing checkout object. If hash is not passed
 * it will try to get hash from query. On fail, new checkout object will be
 * created and new hash string generated.
 *
 * @param string $checkout_type Checkout type.
 * @param string $base_url      Base page url.
 * @param string $hash          Optional. Unique checkout hash.
 */
function appthemes_setup_checkout( $checkout_type, $base_url, $hash = '' ) {

	if ( empty( $hash ) ) {
		$hash = _appthemes_get_hash_from_query();
	}

	$checkout = new APP_Dynamic_Checkout( $checkout_type, $hash );
	if ( ! $checkout->verify_hash() ) {
		return false;
	}

	_appthemes_register_checkout( $checkout, $base_url );
}

/**
 * Registers checkout object and steps.
 *
 * @param APP_Dynamic_Checkout $checkout Checkout object.
 * @param string               $base_url Base page url.
 */
function _appthemes_register_checkout( APP_Dynamic_Checkout $checkout, $base_url = '' ) {
	APP_Current_Checkout::register_checkout( $checkout, $base_url );
	do_action( 'appthemes_register_checkout_steps', $checkout );
	do_action( 'appthemes_register_checkout_steps_' . $checkout->get_checkout_type(), $checkout );
}

/**
 * Processes current checkout.
 *
 * Takes step id from the query (current URL). Requires previously setted up
 * checkout object by function appthemes_setup_checkout().
 *
 * @return bool. Return true on success and false on failure.
 */
function appthemes_process_checkout() {

	$step = _appthemes_get_step_from_query();
	$checkout = APP_Current_Checkout::get_checkout();
	if ( ! $checkout ) {
		return false;
	}

	return $checkout->process_step( $step );
}

/**
 * Processes checkout by given parameters without redirection between steps.
 *
 * This function allows to process checkouts in background mode whithout risk to
 * affect any other checkout.
 *
 * It no needs pre-setting checkout since it creates new checkout object itself.
 *
 * Can be used anywhere beyond the active listing process (i.e. action hooks
 * callback or unit tests).
 *
 * @param string $type Checkout type to be processed.
 * @param string $hash Checkout hash.
 * @param string $step Checkout step.
 */
function appthemes_process_background_checkout( $type, $hash, $step ) {
	$preserved = null;
	$checkout  = appthemes_get_checkout();

	if ( $checkout ) {
		// Nothing to do if checkout is already on given step.
		if ( $hash === $checkout->get_hash() && $step === $checkout->get_current_step() ) {
			return;
		}
		// Otherwise preserve current checkout and try to setup another.
		$preserved = $checkout;
		$base_url  = appthemes_get_checkout_url();
	}

	appthemes_setup_checkout( $type, '', $hash );
	$checkout = appthemes_get_checkout();

	if ( $checkout ) {
		if ( ! $step ) {
			$step = $checkout->get_next_step();
		}

		$checkout->set_redirect_flag( false );
		$checkout->process_step( $step );
	}

	// Restore preserved checkout object.
	if ( $preserved ) {
		_appthemes_register_checkout( $preserved, $base_url );
	}
}

/**
 * Processes background checkout by given concrete step URL.
 *
 * Retrieves checkout parameters (step, hash) from URL string and processes step
 * by appthemes_process_background_checkout().
 *
 * @param string $type Checkout type to be processed.
 * @param string $url  URL with hash and step arguments.
 */
function appthemes_process_checkout_by_url( $type, $url ) {

	$url_args   = array();
	$parsed_url = wp_parse_url( $url );
	parse_str( $parsed_url['query'], $url_args );

	if ( ! isset( $url_args['hash'] ) ) {
		return;
	}

	if ( ! isset( $url_args['step'] ) ) {
		$url_args['step'] = '';
	}

	appthemes_process_background_checkout( $type, $url_args['hash'], $url_args['step'] );
}

function appthemes_display_checkout(){

	$step = _appthemes_get_step_from_query();
	$checkout = APP_Current_Checkout::get_checkout();
	if( ! $checkout ) {
		return;
	}

	return $checkout->display_step( $step );
}

function _appthemes_get_hash_from_query(){

	if( ! empty( $_GET['hash'] ) )
		return $_GET['hash'];
	else
		return '';

}

function _appthemes_get_step_from_query(){

	if( ! empty( $_GET['step'] ) )
		return $_GET['step'];
	else if( $checkout = APP_Current_Checkout::get_checkout() )
		return $checkout->get_next_step();
	else
		return '';


}

function appthemes_get_checkout_url( ){
	return APP_Current_Checkout::get_base_url();
}

/**
 * Retrieves current checkout instance.
 *
 * @return APP_Dynamic_Checkout|false Current checkout instance or false.
 */
function appthemes_get_checkout(){
	return APP_Current_Checkout::get_checkout();
}

function appthemes_get_step_url( $step_id = '' ){

	$checkout = appthemes_get_checkout();
	if( empty( $step_id ) ){
		$step_id = $checkout->get_current_step();
	}

	$query_args = array(
		'step' => $step_id,
		'hash' => $checkout->get_hash(),
	);

	return add_query_arg( $query_args, appthemes_get_checkout_url() );

}

function appthemes_get_previous_step(){

	$checkout = appthemes_get_checkout();
	if ( ! $checkout )
		return;

	$current_step = _appthemes_get_step_from_query();

	return $checkout->get_previous_step( $current_step );
}
