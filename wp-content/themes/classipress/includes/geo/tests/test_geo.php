<?php

require_once 'test_core.php';

/**
 * @group geo
 */
class APP_Geo_Query_Unit_Tests extends APP_Geo_Unit_Tests {

	function test_distance() {
		$distance = APP_Geo_Query::distance( 37.9298239, -122.28178, 37.6398299, -123.173825, 'mi' );
		$this->assertEquals( 52.6736, $distance );

		$distance = APP_Geo_Query::distance( 37.9298239, -122.28178, 37.6398299, -123.173825, 'km' );
		$this->assertEquals( 84.7647, $distance );
	}

}
