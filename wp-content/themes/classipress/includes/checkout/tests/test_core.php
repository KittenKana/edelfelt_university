<?php

require_once APP_TESTS_LIB . '/testcase.php';

/**
 * @group checkout
 */
class APP_Checkout_Unit_Tests extends APP_UnitTestCase {

	function test_setup_new_checkout() {

		$type = 'test';

		appthemes_setup_checkout( $type, '', '' );

		$checkout = appthemes_get_checkout();
		$this->assertInstanceOf( 'APP_Dynamic_Checkout', $checkout );

		$check_type = $checkout->get_data( 'checkout_type' );

		$this->assertEquals( $type, $check_type );

	}

	function test_setup_existing_checkout_by_hash() {

		$type = 'test';

		appthemes_setup_checkout( $type, '', '' );

		$checkout = appthemes_get_checkout();
		$hash     = $checkout->get_hash();

		$checkout->add_data( 'test', true );

		appthemes_setup_checkout( $type, '', $hash );

		$checkout = appthemes_get_checkout();
		$this->assertInstanceOf( 'APP_Dynamic_Checkout', $checkout );

		$check_type = $checkout->get_data( 'checkout_type' );

		$this->assertEquals( $type, $check_type );
		$this->assertEquals( $hash, $checkout->get_hash() );
		$this->assertTrue( $checkout->get_data( 'test' ) );

	}

	function test_setup_existing_checkout_by_query() {

		if ( ! method_exists( $this, 'wp_redirect' ) ) {
			return;
		}

		$type = 'test';

		appthemes_setup_checkout( $type, '', '' );

		$checkout = appthemes_get_checkout();
		$hash     = $checkout->get_hash();

		$checkout->add_data( 'test', true );

		// Add hash to query.
		$url = add_query_arg( 'hash', $hash, get_site_url() );
		@$this->go_to( $url );

		// Setup checkout from the query.
		appthemes_setup_checkout( $type, '', '' );

		$checkout = appthemes_get_checkout();
		$this->assertInstanceOf( 'APP_Dynamic_Checkout', $checkout );

		$check_type = $checkout->get_data( 'checkout_type' );

		$this->assertEquals( $type, $check_type );
		$this->assertEquals( $hash, $checkout->get_hash() );
		$this->assertTrue( $checkout->get_data( 'test' ) );

	}

}
