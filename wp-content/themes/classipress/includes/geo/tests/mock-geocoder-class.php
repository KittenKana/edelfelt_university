<?php

class APP_Mock_Google_Geocoder extends APP_Google_Geocoder {

	public $geocode_results = array();

	public $fail_result = array(
		'results' => array( ),
		'status'  => 'FAIL',
	);

	public $ok_result = array(
		'results' => array(
			array(
				'address_components' => array(
					array(
						'long_name'	 => 'San Francisco',
						'short_name' => 'SF',
						'types'		 => array(
							0	 => 'locality',
							1	 => 'political',
						),
					),
					array(
						'long_name'	 => 'San Francisco County',
						'short_name' => 'San Francisco County',
						'types'		 => array(
							0	 => 'administrative_area_level_2',
							1	 => 'political',
						),
					),
					array(
						'long_name'	 => 'California',
						'short_name' => 'CA',
						'types'		 => array(
							0	 => 'administrative_area_level_1',
							1	 => 'political',
						),
					),
					array(
						'long_name'	 => 'United States',
						'short_name' => 'US',
						'types'		 => array(
							0	 => 'country',
							1	 => 'political',
						),
					),
				),
				'formatted_address'	 => 'San Francisco, CA, USA',
				'geometry'			 => array(
					'bounds'		 => array(
						'northeast'	 => array(
							'lat'	 => 37.9298239,
							'lng'	 => -122.28178,
						),
						'southwest'	 => array(
							'lat'	 => 37.6398299,
							'lng'	 => -123.173825,
						),
					),
					'location'		 => array(
						'lat'	 => 37.7749295,
						'lng'	 => -122.4194155,
					),
					'location_type'	 => 'APPROXIMATE',
					'viewport'		 => array(
						'northeast'	 => array(
							'lat'	 => 37.812,
							'lng'	 => -122.3482,
						),
						'southwest'	 => array(
							'lat'	 => 37.7034,
							'lng'	 => -122.527,
						),
					),
				),
				'place_id'			 => 'ChIJIQBpAG2ahYAR_6128GcTUEo',
				'types'				 => array(
					0	 => 'locality',
					1	 => 'political',
				),
			),
		),
		'status'	 => 'OK',
	);

	public function __construct() {
		parent::__construct();

		$this->geocode_results = $this->ok_result;
	}

	/**
	 * Allow to set alternative results.
	 *
	 * @param type $result
	 */
	public function set_expected_result( $result = array() ) {
		$this->geocode_results = $result;
	}

	/**
	 * Expected that geocode_results has been already set.
	 *
	 * @param type $args
	 * @return boolean
	 */
	public function geocode_api( $args ) {

		if ( ! $this->geocode_results ) {
			return false;
		}

		if ( 'OK' !== $this->geocode_results['status'] ) {
			$this->set_response_code();
			return false;
		}

		$this->process_geocode();
	}

}
