<?php

/**
 * Base class for Geocoders
 */
abstract class APP_Geocoder {

	/**
	 * Unique identifier for this geocoder.
	 *
	 * @var string
	 */
	private $identifier;

	/**
	 * Display names used for this Geocoder.
	 *
	 * @var array
	 */
	private $display;

	/**
	 * Geocoder options.
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Current coordinates.
	 *
	 * @var stdClass
	 */
	public $coords;

	/**
	 * Current formattred address.
	 *
	 * @var string
	 */
	public $address;

	/**
	 * Current address components array.
	 *
	 * The array structure:
	 *
	 *     $address_components = array(
	 *         'street_number' => string,
	 *         'street'        => string,
	 *         'city'          => string,
	 *         'state'         => string,
	 *         'country'       => string,
	 *         'postal_code'   => string,
	 *     );
	 *
	 * @var array
	 */
	public $address_components;

	/**
	 * Current address area bounds.
	 *
	 * @var array
	 */
	public $bounds;

	/**
	 * Smart radius.
	 *
	 * @var int
	 */
	public $radius;

	/**
	 * Response code.
	 *
	 * @var string
	 */
	public $response_code;

	/**
	 * Creates the Geocoder class with the required information to display it.
	 *
	 * @param string $identifier The unique indentifier used to indentify
	 *                           geocoder provider.
	 * @param array  $args       The display provider name parameters.
	 */
	public function __construct( $identifier, $args ) {

		$defaults = array(
			'dropdown' => $identifier,
			'admin' => $identifier,
		);

		$args = wp_parse_args( $args, $defaults );

		$this->display = array(
			'dropdown' => $args['dropdown'],
			'admin' => $args['admin'],
		);

		$this->identifier = $identifier;
	}

	/**
	 * Returns an array representing the form to output for admin configuration.
	 *
	 * @return array scbForms style form array
	 */
	public abstract function form();

	/**
	 * Checks whether geocoder has all necessary options entered.
	 *
	 * @return bool
	 */
	public abstract function has_required_vars();

	/**
	 * Processes a geocode request for an address
	 *
	 * @param string $address Address to be geocoded.
	 *
	 * @return void
	 */
	public abstract function geocode_address( $address );

	/**
	 * Processes a reverse geocode request for a lat and lng
	 *
	 * @param string $lat Latitude to be geocoded.
	 * @param string $lng Longitude to be geocoded.
	 *
	 * @return void
	 */
	public abstract function geocode_lat_lng( $lat, $lng );

	/**
	 * Retrieves bounds array from geocode result.
	 */
	public abstract function set_bounds();

	/**
	 * Retrieves coordinates from geocode result.
	 */
	public abstract function set_coords();

	/**
	 * Retrieves formatted address from geocode result.
	 */
	public abstract function set_address();

	/**
	 * Retrieves address components array from geocode result.
	 */
	public function set_address_components() {}

	/**
	 * Builds formatted address components array from the raw data.
	 *
	 * @param array $data Raw components array.
	 *
	 * @return array Formatted array.
	 */
	public function parse_address_components( $data ) {
		return $data;
	}

	/**
	 * Retrieves response code from geocode result.
	 */
	public function set_response_code() {}

	/**
	 * Processes geocode result and structures retrieved data.
	 */
	public function process_geocode() {
		$this->set_bounds();
		$this->set_coords();
		$this->set_address();
		$this->set_address_components();
		$this->set_response_code();
		$this->calculate_radius();
	}


	/**
	 * Sets address area bounds in a common structured array.
	 *
	 * @param string $ne_lat North-east latitude.
	 * @param string $ne_lng North-east longitude.
	 * @param string $sw_lat South-west latitude.
	 * @param string $sw_lng South-west longitude.
	 */
	protected final function _set_bounds( $ne_lat, $ne_lng, $sw_lat, $sw_lng ) {
		$this->bounds = array(
			'ne_lat' => $ne_lat,
			'ne_lng' => $ne_lng,
			'sw_lat' => $sw_lat,
			'sw_lng' => $sw_lng,
		);
	}

	/**
	 * Sets address coordinates in a common structured object.
	 *
	 * @param string $lat Latitude.
	 * @param string $lng Longitude.
	 */
	protected final function _set_coords( $lat, $lng ) {
		$this->coords = new stdClass();
		$this->coords->lat = $lat;
		$this->coords->lng = $lng;
	}

	/**
	 * Sets formatted address.
	 *
	 * @param string $address Given address.
	 */
	public final function _set_address( $address ) {
		$this->address = $address;
	}

	/**
	 * Sets address components.
	 *
	 * @param string $address_array Given address array.
	 */
	protected final function _set_address_components( $address_array ) {
		$defaults = array(
			'street_number' => '',
			'street'        => '',
			'city'          => '',
			'state'         => '',
			'country'       => '',
			'postal_code'   => '',
		);

		$this->address_components = array_merge( $defaults, (array) $address_array );
	}

	/**
	 * Sets respponse code.
	 *
	 * @param string $response_code Given respponse code.
	 */
	protected final function _set_response_code( $response_code ) {
		$this->response_code = $response_code;
	}

	/**
	 * Retrieves current formatted address
	 *
	 * @return string
	 */
	public final function get_address() {
		return ! empty( $this->address ) ? $this->address : false;
	}

	/**
	 * Retrieves current address components
	 *
	 * @return array
	 */
	public final function get_address_components() {
		return ! empty( $this->address_components ) ? $this->address_components : array();
	}

	/**
	 * Retrieves current coordinates.
	 *
	 * @return stdClass
	 */
	public final function get_coords() {
		return ! empty( $this->coords ) ? $this->coords : false;
	}

	/**
	 * Retrieves current coordinates.
	 *
	 * @return array
	 */
	public final function get_bounds() {
		return ! empty( $this->bounds ) ? $this->bounds : false;
	}

	/**
	 * Retrieves current smart radius.
	 *
	 * @return int
	 */
	public final function get_radius() {
		return ! empty( $this->radius ) ? $this->radius : false;
	}

	/**
	 * Retrieves current response code
	 *
	 * @return string
	 */
	public final function get_response_code() {
		return ! empty( $this->response_code ) ? $this->response_code : null;
	}

	/**
	 * Calculates distance between to points.
	 *
	 * @param float  $lat_1 Latitude of the first point.
	 * @param float  $lng_1 Longitude of the first point.
	 * @param float  $lat_2 Latitude of the second point.
	 * @param float  $lng_2 Longitude of the second point.
	 * @param string $unit  Distance unit (mi or km).
	 *
	 * @return int
	 */
	protected function distance( $lat_1, $lng_1, $lat_2, $lng_2, $unit ) {
		$earth_radius = ( 'mi' == $unit ) ? 3959 : 6371;

		$delta_lat = $lat_2 - $lat_1;
		$delta_lon = $lng_2 - $lng_1;
		$alpha    = $delta_lat / 2;
		$beta     = $delta_lon / 2;
		$a        = sin( deg2rad( $alpha ) ) * sin( deg2rad( $alpha ) ) + cos( deg2rad( $lat_1 ) ) * cos( deg2rad( $lat_2 ) ) * sin( deg2rad( $beta ) ) * sin( deg2rad( $beta ) ) ;
		$c        = asin( min( 1, sqrt( $a ) ) );
		$distance = 2 * $earth_radius * $c;
		$distance = round( $distance, 4 );

		return $distance;
	}

	/**
	 * Calculates smart radius.
	 *
	 * @return boolean
	 */
	public function calculate_radius() {
		$bounds = $this->get_bounds();

		if ( empty( $bounds['ne_lat'] ) ) {
			return false;
		}

		$unit   = ! empty( $this->options['geo_unit'] ) ? $this->options['geo_unit'] : 'mi';
		$coords = $this->get_coords();

		$distance_a = $this->distance( $coords->lat, $coords->lng, $bounds['sw_lat'], $bounds['sw_lng'], $unit );
		$distance_b = $this->distance( $coords->lat, $coords->lng, $bounds['ne_lat'], $bounds['ne_lng'], $unit );
		$distance_c = $this->distance( $coords->lat, $coords->lng, $bounds['ne_lat'], $bounds['sw_lng'], $unit );
		$distance_d = $this->distance( $coords->lat, $coords->lng, $bounds['sw_lat'], $bounds['ne_lng'], $unit );

		$this->radius = max( $distance_a, $distance_b, $distance_c, $distance_d, 1 );
	}

	/**
	 * Provides the display name for this Geocoder
	 *
	 * @param string $type Where to display in dropdown or admin menu.
	 *
	 * @return string
	 */
	public final function display_name( $type = 'dropdown' ) {
		return $this->display[ $type ];
	}

	/**
	 * Provides the unique identifier for this Geocoder
	 *
	 * @return string
	 */
	public final function identifier() {
		return $this->identifier;
	}

}
