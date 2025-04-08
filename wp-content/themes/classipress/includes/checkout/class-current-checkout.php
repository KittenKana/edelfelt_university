<?php
/**
 * Dynamic Checkout API
 *
 * @package Components\Checkouts
 */

/**
 * Current Checkout class
 */
class APP_Current_Checkout {

	/**
	 * The current checkout object.
	 *
	 * @var APP_Dynamic_Checkout
	 */
	static $checkout = false;

	/**
	 * The current checkout base URL.
	 *
	 * @var string
	 */
	static $base_url = '';

	/**
	 * Registers current checkout object.
	 *
	 * @param APP_Dynamic_Checkout $checkout The checkout object.
	 * @param string               $base_url The checkout base URL.
	 */
	public static function register_checkout( $checkout, $base_url ) {
		self::$checkout = $checkout;
		self::$base_url = $base_url;
	}

	/**
	 * Retrieves the current checkout object.
	 *
	 * @return APP_Dynamic_Checkout
	 */
	public static function get_checkout() {
		return self::$checkout;
	}

	/**
	 * Retrieves the current checkout base URL.
	 *
	 * @return string
	 */
	public static function get_base_url() {
		return self::$base_url;
	}
}
