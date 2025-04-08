<?php

require_once 'test_core.php';

/**
 * @group geo
 */
class APP_Geo_Query_2_Unit_Tests extends APP_Geo_Unit_Tests {

	public function setUp() {
		parent::setUp();

		if ( ! class_exists( 'APP_Geo_Query_2' ) ) {
			$this->markTestSkipped( 'APP_Geo_Query_2 class required for this test. PHPUnit will skip this test method' );
			return;
		}
	}

	public function test_parse_query() {
		$wp_query = new WP_Query( array(
			'lat' => 37.7749295,
			'lng' => -122.4194155,
		) );

		APP_Geo_Query_2::parse_query( $wp_query );

		$app_geo_query = $wp_query->get( 'app_geo_query' );
		$this->assertNotEmpty( $wp_query->get( 'app_geo_query' ) );
		$this->assertArrayHasPairs( array(
			'lat' => 37.7749295,
			'lng' => -122.4194155,
		), $app_geo_query );
	}

	public function test_parse_query_lat_lng() {
		$wp_query = new WP_Query( array(
			'lat' => 37.7749295,
			'lng' => -122.4194155,
		) );

		APP_Geo_Query_2::parse_query_lat_lng( $wp_query );

		$app_geo_query = $wp_query->get( 'app_geo_query' );
		$this->assertNotEmpty( $wp_query->get( 'app_geo_query' ) );
		$this->assertArrayHasPairs( array(
			'lat' => 37.7749295,
			'lng' => -122.4194155,
		), $app_geo_query );
	}

	public function test_search_query() {

		$post_1_mi_id    = $this->factory->post->create();
		$post_100_mi_id  = $this->factory->post->create();
		$post_50_mi_id   = $this->factory->post->create();
		$post_nowhere_id = $this->factory->post->create();

		appthemes_set_coordinates( $post_1_mi_id, 37.6879241, -122.4702079 );
		appthemes_set_coordinates( $post_100_mi_id, 38.5815719, -121.4943996 );
		appthemes_set_coordinates( $post_50_mi_id, 37.3382082, -121.8863286 );

		// DISABLE NOWHERE.

		$options = APP_Geocoder_Registry::get_options();
		$options->update( array_merge( $options->get(), array( 'include_nowhere' => 0 ) ), false );

		/**
		 * 1. Radius 1
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 1,
		) );

		$this->assertEmpty( $wp_query->posts );

		/**
		 * 2. Radius 34 (smart)
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 34,
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEqualSets( array(
			$post_1_mi_id,
		), $posts );

		/**
		 * 3. Radius 50
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 50,
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEqualSets( array(
			$post_1_mi_id,
			$post_50_mi_id,
		), $posts );

		/**
		 * 4. Radius 100
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 100,
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEqualSets( array(
			$post_1_mi_id,
			$post_100_mi_id,
			$post_50_mi_id,
		), $posts );


		/**
		 * 5. Order By distance
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 100,
			'orderby'  => 'distance',
			'order'    => 'ASC',
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEquals( array(
			$post_1_mi_id,
			$post_50_mi_id,
			$post_100_mi_id,
		), $posts );


		// ENABLE NOWHERE.

		$options = APP_Geocoder_Registry::get_options();
		$options->update( array_merge( $options->get(), array( 'include_nowhere' => 1 ) ), false );

		/**
		 * 6. Radius 1 + nowhere
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 1,
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEqualSets( array(
			$post_nowhere_id,
		), $posts );

		/**
		 * 7. Radius 50 + nowhere
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 50,
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEqualSets( array(
			$post_1_mi_id,
			$post_50_mi_id,
			$post_nowhere_id,
		), $posts );

		/**
		 * 8. Order By distance + nowhere
		 */
		$wp_query = new WP_Query( array(
			'lat'      => 37.7749295,
			'lng'      => -122.4194155,
			'location' => 'San Francisco',
			'radius'   => 100,
			'orderby'  => 'distance',
			'order'    => 'ASC',
		) );

		$posts = wp_list_pluck( $wp_query->posts, 'ID' );

		$this->assertEquals( array(
			$post_1_mi_id,
			$post_50_mi_id,
			$post_100_mi_id,
			$post_nowhere_id,
		), $posts );

	}

}
