<?php

require_once APP_TESTS_LIB . '/testcase.php';
require_once 'mock-geocoder-class.php';

/**
 * @group geo
 */
class APP_Geo_Unit_Tests extends APP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		if ( ! class_exists( 'APP_Geo_Query' ) ) {
			$this->markTestSkipped( 'APP_Geo_Query class required for this test. PHPUnit will skip this test method' );
			return;
		}

		// Replace the original object.
		appthemes_register_geocoder( 'APP_Mock_Google_Geocoder' );
	}

	public function test_test() {
		$this->assertTrue( true );
	}

}
