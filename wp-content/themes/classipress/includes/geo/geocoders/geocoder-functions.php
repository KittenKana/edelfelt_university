<?php
/**
 * Setup the geocoder class and functions.
 *
 * @package Geo
 * @since   1.0.0
 */

/**
 * Registers a geocoder with the APP_Geocoder_Registry
 *
 * @param string $class_name Name of the class to be used as a Geocoder.
 *
 * @return void
 */
function appthemes_register_geocoder( $class_name ) {
	APP_Geocoder_Registry::register_geocoder( $class_name );
}


function appthemes_geocode_address( $address ) {

	$geocoder = APP_Geocoder_Registry::get_active_geocoder();
	if ( ! $geocoder ) {
		return false;
	}

	$options = APP_Geocoder_Registry::get_geocoder_options( $geocoder->identifier() );

	$geocoder->options = $options;

	if ( $geocoder->has_required_vars() ) {
		$geocoder->geocode_address( $address );

		$result = array();
		$result['coords'] = $geocoder->get_coords();
		$result['bounds'] = $geocoder->get_bounds();
		$result['address'] = $geocoder->get_address();
		$result['radius'] = $geocoder->get_radius();
		$result['address_components'] = $geocoder->get_address_components();
		if ( current_user_can( 'manage_options' ) ) {
			$result['response_code'] = $geocoder->get_response_code();
		}

		return $result;
	} else {
		return false;
	}

}

function appthemes_geocode_lat_lng( $lat, $lng ) {

	$geocoder = APP_Geocoder_Registry::get_active_geocoder();
	if ( ! $geocoder ) {
		return false;
	}

	$options = APP_Geocoder_Registry::get_geocoder_options( $geocoder->identifier() );

	$geocoder->options = $options;

	if ( $geocoder->has_required_vars() ) {
		$geocoder->geocode_lat_lng( $lat, $lng );

		$result = array();
		$result['coords'] = $geocoder->get_coords();
		$result['bounds'] = $geocoder->get_bounds();
		$result['address'] = $geocoder->get_address();
		$result['address_components'] = $geocoder->get_address_components();
		if ( current_user_can( 'manage_options' ) ) {
			$result['response_code'] = $geocoder->get_response_code();
		}

		return $result;
	} else {
		return false;
	}

}

function appthemes_get_geocoder_string_array() {
	$geocoders = array();
	foreach ( APP_Geocoder_Registry::get_geocoders() as $geocoder ) {
		if ( current_user_can( 'manage_options' ) ) {
			$geocoders[ $geocoder->identifier() ] = $geocoder->display_name( 'dropdown' );
		}
	}

	return $geocoders;
}

/**
 * Extends APP_Geo_Query::parse_query to utilize the installed APP_Geocoder
 */
class APP_Geo_Query_2 extends APP_Geo_Query {

	/**
	 * Sets the radius query based on location variables.
	 *
	 * @since  1.0.0
	 *
	 * @param  WP_Query $wp_query The WP_Query instance.
	 *
	 * @return void
	 */
	static function parse_query( $wp_query ) {

		// Using new auto-complete hidden lat/lng fields
		// so bypass the slow geocode lookup.
		if ( $wp_query->get( 'lat' ) && $wp_query->get( 'lng' ) ) {
			return self::parse_query_lat_lng( $wp_query );
		}

		extract( appthemes_geo_2_get_args() );

		$location = trim( $wp_query->get( 'location' ) );
		if ( ! $location ) {
			return;
		}

		$wp_query->is_search = true;

		$smart_radius = false;

		$radius = is_numeric( $wp_query->get( 'radius' ) ) ? $wp_query->get( 'radius' ) : false;
		if ( ! $radius ) {
			$radius = ! empty( $options->default_radius ) && is_numeric( $options->default_radius ) ? $options->default_radius : false;
		}

		$transient_key = 'app_geo_2_' . md5( $location );

		if ( defined( 'WP_DEBUG' ) ) {
			$geo_coord = false;
		} else {
			$geo_coord = get_transient( $transient_key );
		}

		if ( ! $geo_coord ) {
			$geocode = appthemes_geocode_address( $location );

			if ( ! empty( $geocode['coords']->lat ) ) {

				if ( ! empty( $geocode['radius'] ) ) {
					$smart_radius = $geocode['radius'];
				}

				$geo_coord = array(
					'lat' => $geocode['coords']->lat,
					'lng' => $geocode['coords']->lng,
					'rad' => $smart_radius,
				);
				// Cache for a week.
				set_transient( $transient_key, $geo_coord, 60 * 60 * 24 * 7 );
			}
		}

		if ( $geo_coord ) {
			// Final fallback just in case $wp_query->get( 'radius' ) and default_radius are not set and smart_radius fails due to API not returning a bounds/viewport.
			if ( ! $radius ) {
				$radius = ( ! empty( $geo_coord['rad'] ) ) ? $geo_coord['rad'] : 50;
			}

			$wp_query->set( 'app_geo_query', apply_filters( 'appthemes_geo_query', array(
				'lat' => $geo_coord['lat'],
				'lng' => $geo_coord['lng'],
				'rad' => $radius,
				'smart_radius' => ! empty( $geo_coord['rad'] ) && $geo_coord['rad'] === $radius,
				'include_nowhere' => $options->include_nowhere,
			) ) );
		} else {
			// Fall back to basic string matching.
			$meta_query = $wp_query->get( 'meta_query' );
			if ( empty( $meta_query ) ) {
				$meta_query = array();
			}
			$meta_query[] = array(
				'key'     => 'address',
				'value'   => $location,
				'compare' => 'LIKE',
			);
			$wp_query->set( 'meta_query', $meta_query );
		}

		if ( 'distance' == $wp_query->get( 'orderby' ) ) {
			$wp_query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Sets the radius query based on location variables.
	 *
	 * Assumes lat and lng coordinates are passed in.
	 *
	 * @since  2.0.0
	 *
	 * @param WP_Query $wp_query The WP_Query instance.
	 *
	 * @return void
	 */
	static function parse_query_lat_lng( $wp_query ) {

		extract( appthemes_geo_2_get_args() );

		$wp_query->is_search = true;

		$radius = $wp_query->get( 'radius', $options->default_radius ) ? $wp_query->get( 'radius', $options->default_radius ) : 50;

		$wp_query->set( 'app_geo_query', apply_filters( 'appthemes_geo_query', array(
			'lat' => (float) $wp_query->get( 'lat' ),
			'lng' => (float) $wp_query->get( 'lng' ),
			'rad' => (int) $radius,
			'include_nowhere' => $options->include_nowhere,
		) ) );

	}

	/**
	 * Builds the radius query based on location variables.
	 *
	 * @since  2.0.0
	 *
	 * @param  array $clauses  The list of clauses for the query.
	 * @param  array $wp_query The WP_Query instance.
	 *
	 * @return array
	 */
	static function posts_clauses( $clauses, $wp_query ) {
		global $wpdb;

		extract( appthemes_geo_2_get_args() );

		$geo_query = $wp_query->get( 'app_geo_query' );

		if ( ! $geo_query ) {
			return $clauses;
		}

		extract( $geo_query, EXTR_SKIP );

		// Radius of the earth 3959 miles or 6371 kilometers.
		$earth_radius = 'mi' == $options->geo_unit ? 3959 : 6371;

		if ( 'distance' == $wp_query->get( 'orderby' ) ) {
			$clauses['orderby'] = 'distance ' . ( 'DESC' == strtoupper( $wp_query->get( 'order' ) ) ? 'DESC' : 'ASC' );
		}

		if ( ! empty( $include_nowhere ) ) {
			$join_type = 'LEFT';
			$clauses['where'] .= $wpdb->prepare( " AND ( distance < %d OR distance IS NULL )", (int) $rad );
			if ( 'distance' == $wp_query->get( 'orderby' ) ) {
				$clauses['orderby'] = 'COALESCE( distance, 999999999 ) ' . ( 'DESC' == strtoupper( $wp_query->get( 'order' ) ) ? 'DESC' : 'ASC' );
			} else {
				$clauses['orderby'] = implode( ', ', array( $clauses['orderby'], 'COALESCE( distance, 999999999 ) ASC' ) );
			}
		} else {
			$join_type = 'INNER';
			$clauses['where'] .= $wpdb->prepare( " AND distance < %d", (int) $rad );
		}

		// Use the spherical law of cosines formula to find listings
		// within a given distance between two points on the earth’s surface.
		$clauses['join'] .= $wpdb->prepare( "
			$join_type JOIN (
				SELECT post_id,
				( %d * acos(
					cos( radians(%f) ) *
					cos( radians(lat) ) *
					cos( radians(lng) - radians(%f) ) +
					sin( radians(%f) ) *
					sin( radians(lat) )
				) )
				AS distance
				FROM $wpdb->app_geodata
			)
			AS distances
			ON ( $wpdb->posts.ID = distances.post_id )",
			$earth_radius,
			$lat,
			$lng,
			$lat
		);

		return $clauses;
	}
}
