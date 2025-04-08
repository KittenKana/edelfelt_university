<?php
/**
 * Google maps class.
 *
 * @package Geo
 *
 * @since 1.0.0
 */

/**
 * Map provider APP_Google_Map_Provider class
 *
 * @since   1.0.0
 */
class APP_Google_Map_Provider extends APP_Map_Provider {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'google', array(
			'dropdown' => __( 'Google', APP_TD ),
			'admin'    => __( 'Google', APP_TD ),
		) );
	}

	/**
	 * Init
	 */
	public function init() {}

	/**
	 * Check to see if there are required variables.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function has_required_vars() {

		if ( empty( $this->options['geo_region'] ) && empty( $_POST['map_provider_settings']['google']['geo_region'] ) ) {
			return __( 'Region', APP_TD );
		}

		if ( empty( $this->options['geo_language'] ) && empty( $_POST['map_provider_settings']['google']['geo_language'] ) ) {
			return __( 'Language', APP_TD );
		}

		if ( empty( $this->options['geo_unit'] ) && empty( $_POST['map_provider_settings']['google']['geo_unit'] ) ) {
			return __( 'Unit', APP_TD );
		}

		return true;
	}

	/**
	 * Load up the map scripts.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function _enqueue_scripts() {

		$url = '//maps.googleapis.com/maps/api/js';

		$defaults = array(
			'geo_region'   => 'US',
			'geo_language' => 'en',
			'geo_unit'     => 'mi',
			'api_key'      => '',
		);

		$options = wp_parse_args( $this->options, $defaults );

		$args = array(
			'libraries' => array( 'geometry', 'places' ),
			'region'    => strtolower( $options['geo_region'] ),
			'language'  => strtolower( $options['geo_language'] ),
			'unit'      => strtolower( $options['geo_unit'] ),
		);

		/**
		 * Filters the default Google Maps args.
		 *
		 * @param array $defaults The default map values.
		 */
		$args = apply_filters( 'appthemes_google_map_default_args', $args );

		$args['key'] = $options['api_key'];

		$args['libraries'] = implode( ',', $args['libraries'] );

		wp_enqueue_script( 'google-maps-api', add_query_arg( $args, $url ), array(), null, false );
		wp_enqueue_script( 'appthemes-google-maps', get_template_directory_uri() . '/includes/geo/map-providers/google-maps.js', array( 'google-maps-api', 'appthemes-maps' ), '20180916', true );
	}

	/**
	 * Custom styles for the map.
	 *
	 * @since  2.0.0
	 *
	 * @return array
	 */
	public function _map_styles() {

		$styles   = '';
		$filename = 'standard.json';

		if ( isset( $this->options['color_scheme'] ) ) {
			$filename = "{$this->options['color_scheme']}.json";
		}

		// Grab the style json file from the schemes folder.
		$scheme = trailingslashit( dirname( __FILE__ ) ) . trailingslashit( 'schemes' ) . $filename;

		/**
		 * Filters the Google map default style file.
		 *
		 * @since 2.0.0
		 *
		 * @param array $scheme The path to the default style file.
		 */
		$scheme = apply_filters( 'appthemes_google_map_styles_file', $scheme );

		if ( file_exists( $scheme ) ) {
			$data = file_get_contents( $scheme );
		}

		// Convert the json file to a php array.
		if ( ! is_null( $data ) && $data ) {
			$styles = json_decode( $data, true );
		}

		return $styles;
	}

	/**
	 * Map provider variables.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function _map_provider_vars() {
		$this->map_provider_vars = wp_parse_args( $this->options, array(
			'text_directions_error' => __( 'Could not get directions to the given address. Please make your search more specific.', APP_TD ),
			'styles' => $this->_map_styles(),
		) );
	}

	/**
	 * Generates the admin option fields.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function form() {

		$general = array(
			array(
				'title'  => __( 'General', APP_TD ),
				'fields' => array(
					array(
						'title' => __( 'Region Biasing', APP_TD ),
						'desc'  => sprintf( __( 'Find your two-letter <a href="%s" target="_blank">region code</a>', APP_TD ), 'https://en.wikipedia.org/wiki/ISO_3166-1#Current_codes' ),
						'type'  => 'text',
						'name'  => 'geo_region',
						'tip'   => __( "If you set this to 'IT' and a user enters 'Florence' in the location search field, it will target 'Florence, Italy' rather than 'Florence, Alabama'.", APP_TD ),
						'extra' => array(
							'class' => 'small-text',
						),
					),
					array(
						'title' => __( 'Language', APP_TD ),
						'desc'  => sprintf( __( 'Find your two-letter <a href="%s" target="_blank">language code</a>', APP_TD ), 'https://en.wikipedia.org/wiki/List_of_ISO_639_language_codes' ),
						'type'  => 'text',
						'name'  => 'geo_language',
						'tip'   => __( 'Used to format the address and map controls.', APP_TD ),
						'extra' => array(
							'class' => 'small-text',
						),
					),
					array(
						'title'  => __( 'Distance Unit', APP_TD ),
						'type'   => 'select',
						'name'   => 'geo_unit',
						'values' => array(
							'km' => __( 'Kilometers', APP_TD ),
							'mi' => __( 'Miles', APP_TD ),
						),
						'tip'    => '',
					),
					array(
						'title' => __( 'API Key', APP_TD ),
						'desc'  => sprintf( __( 'Get started using the <a href="%s" target="_blank">Maps API</a>', APP_TD ), 'https://developers.google.com/maps/documentation/javascript/tutorial#api_key' ),
						'type'  => 'text',
						'name'  => 'api_key',
						'tip'   => __( 'Activate your Google Maps JavaScript API Service and paste in the API key here. This field is optional but recommended.', APP_TD ),
					),
				),
			),
			array(
				'title' => __( 'Color Settings', APP_TD ),
				'fields' => array(
					array(
						'title'  => __( 'Color Scheme', APP_TD ),
						'type'   => 'select',
						'name'   => 'color_scheme',
						'values' => array(
							'standard'           => __( 'Standard', APP_TD ),
							'apple'              => __( 'Apple', APP_TD ),
							'blue-hues'          => __( 'Blue Hues', APP_TD ),
							'blue-water'         => __( 'Blue Water', APP_TD ),
							'blue-essence'       => __( 'Blue Essence', APP_TD ),
							'cream'              => __( 'Cream', APP_TD ),
							'light-dream'        => __( 'Light Dream', APP_TD ),
							'midnight-commander' => __( 'Midnight Commander', APP_TD ),
							'night'              => __( 'Night', APP_TD ),
							'pale-dawn'          => __( 'Pale Dawn', APP_TD ),
							'paper'              => __( 'Paper', APP_TD ),
							'red-hues'           => __( 'Red Hues', APP_TD ),
							'retro'              => __( 'Retro', APP_TD ),
							'subtle-grayscale'   => __( 'Subtle Grayscale', APP_TD ),
							'ultra-light'        => __( 'Ultra Light', APP_TD ),
						),
						'tip'    => sprintf( __( 'Select a preset color scheme for the maps. If you are a developer, use your own .json color scheme via the "appthemes_google_map_styles_file" filter. Download a preset from <a href="%1$s" target="_blank">Snazzy Maps</a> or build your own with the <a href="%2$s" target="_blank">Google Maps Styling Wizard</a>.', APP_TD ), 'https://snazzymaps.com', 'https://mapstyle.withgoogle.com/' ),
					),
				),
			),
		);

		return $general;
	}
}

appthemes_register_map_provider( 'APP_Google_Map_Provider' );
