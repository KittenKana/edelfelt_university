<?php

if ( is_admin() ) {
	add_action( 'init', 'appthemes_register_geo_settings', 12 );
}

/**
 * Registers the geo settings page
 * @return void
 */
function appthemes_register_geo_settings() {
	new APP_Geo_Settings_Admin( APP_Geocoder_Registry::get_options() );
}

/**
 * Defines the Geo Settings Administration Panel
 */
class APP_Geo_Settings_Admin extends APP_Tabs_Page {

	static $geo_settings_warning;

	/**
	 * Sets up the page
	 * @return void
	 */
	function setup() {
		$this->textdomain = APP_TD;

		$this->args = array(
			'page_title' => __( 'Geo Services', APP_TD ),
			'menu_title' => __( 'Geo Services', APP_TD ),
			'page_slug' => 'app-geo-settings',
			'parent' => 'app-dashboard',
			'screen_icon' => 'options-general',
			'admin_action_priority' => 10,
		);
		add_action( 'wp_ajax_app_check_geocoder', array( $this, '_ajax_check_geocoder' ) );
	}

	function page_content() {
		appthemes_load_map_provider();
		parent::page_content();
	}

	/**
	 * Creates the tabs for the page
	 * @return void
	 */
	protected function init_tabs() {

		$this->tabs->add( 'general', __( 'General', APP_TD ) );

		$this->tab_sections['general']['geo-query'] = array(
			'title' => __( 'Geo Query', APP_TD ),
			'desc' => __( 'Location-based search parameters.', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Distance Unit', APP_TD ),
					'type' => 'select',
					'name' => 'geo_unit',
					'values' => array(
						'km' => __( 'Kilometers', APP_TD ),
						'mi' => __( 'Miles', APP_TD ),
					),
					'tip' => '',
				),
				array(
					'title' => __( 'Search Radius', APP_TD ),
					'type' => 'number',
					'name' => 'default_radius',
					'desc' => __( 'Used for location based searches', APP_TD ),
					'tip' => __( 'If you leave this blank, the radius will automatically be calculated based on the location query. Use numeric values to represent miles or kilometers.', APP_TD ),
					'extra'	 => array(
						'class' => 'small-text'
					),
				),
				array(
					'title' => __( 'Including not geocoded', APP_TD ),
					'type' => 'checkbox',
					'name' => 'include_nowhere',
					'desc' => __( 'Include "anywhere" results in location-based searches.', APP_TD ),
					'tip' => __( 'This option allows you to include items with no coordinates into geo-query results and consider them as they are located anywhere.', APP_TD ),
				),
			)
		);

		$this->tab_sections['general']['geocoders'] = array(
			'title' => __( 'Geocoders', APP_TD ),
			'desc' => __( 'Convert address locations into geographic coordinates (lat and long) which allows theme to accurately perform radius searches and place markers on a map.', APP_TD ),
			'fields' => array(
				array(
					'title' => __( 'Provider', APP_TD ),
					'type' => 'select',
					'name' => 'geocoder',
					'values' => array_merge( array( '' => __( 'Disabled', APP_TD ) ), appthemes_get_geocoder_string_array() ),
				),
				array(
					'title' => __( 'Test geocode API', APP_TD ),
					'type' => 'custom',
					'name' => '_blank',
					'tip' => __( 'Check to make sure the geocoder API is working correctly.', APP_TD ),
					'render' => array( __CLASS__, 'check_geocoder_render' ),
				),
			)
		);

		$geocoders = APP_Geocoder_Registry::get_geocoders();
		foreach ( $geocoders as $geocoder ) {
			if ( APP_Geocoder_Registry::is_geocoder_enabled( $geocoder->identifier() ) || ( ! empty( $_POST['geocoder'] ) && $geocoder->identifier() == $_POST['geocoder'] ) ) {
				if ( ( ! empty( $_POST['geocoder'] ) && $geocoder->identifier() == $_POST['geocoder'] ) || empty( $_POST['geocoder'] ) ){
					$options = APP_Geocoder_Registry::get_geocoder_options( $geocoder->identifier() );

					$geocoder->options = $options;

					if ( true !== ( $required = $geocoder->has_required_vars() ) ) {

						$geocoder_tab = $geocoder->identifier() . '-geocoder';
						$url = admin_url( add_query_arg( array(
								'page' => $this->args['page_slug'],
								'tab' => $geocoder_tab
							), 'admin.php' ) );

						$url = esc_url( $url );

						self::$geo_settings_warning = sprintf( __( '%1$s %2$s is missing a required settings value: %3$s, <a href="%4$s">Click here to update settings.</a>', APP_TD ), $geocoder->display_name( 'admin' ), __( 'Geocoder', APP_TD ), $required, $url );

						add_action( 'admin_notices', array( $this, 'admin_settings_needed_warning' ) );

					}

					$this->load_settings_tab( $geocoder, 'geocoder' );

				}
			}
		}

		$this->tab_sections['general']['map_providers'] = array(
			'title' => __( 'Maps', APP_TD ),
			'desc' => sprintf( __( 'Display accurate address locations based on your map provider. <a href="%s" target="_blank">Browse additional map provider plugins</a>.', APP_TD ), esc_url( 'https://marketplace.appthemes.com/?s=map&author=0&cat=1&product=23&rating=0' ) ),
			'fields' => array(
				array(
					'title' => __( 'Provider', APP_TD ),
					'type' => 'select',
					'name' => 'map_provider',
					'values' => array_merge( array( '' => __( 'Disabled', APP_TD ) ), appthemes_get_map_provider_string_array() ),
				),
			)
		);

		$map_providers = APP_Map_Provider_Registry::get_map_providers();
		foreach ( $map_providers as $map_provider ) {

			if ( APP_Map_Provider_Registry::is_map_provider_enabled( $map_provider->identifier() ) || ( ! empty( $_POST['map_provider'] ) && $map_provider->identifier() == $_POST['map_provider'] ) ) {

				if ( ( ! empty( $_POST['map_provider'] ) && $map_provider->identifier() == $_POST['map_provider'] ) || empty( $_POST['map_provider'] ) ) {

					$options = APP_Map_Provider_Registry::get_map_provider_options( $map_provider->identifier() );

					$map_provider->options = $options;

					if ( true !== ( $required = $map_provider->has_required_vars() ) ) {

						$map_provider_tab = $map_provider->identifier() . '-map_provider';
						$url = admin_url( add_query_arg( array(
								'page' => $this->args['page_slug'],
								'tab' => $map_provider_tab
							), 'admin.php' ) );

						$url = esc_url( $url );

						self::$geo_settings_warning = sprintf( __( '%1$s %2$s is missing a required settings value: %3$s, <a href="%4$s">Click here to update settings.</a>', APP_TD ), $map_provider->display_name( 'admin' ), __( 'Map Provider', APP_TD ), $required, $url );

						add_action( 'admin_notices', array( $this, 'admin_settings_needed_warning' ) );

					}

					$this->load_settings_tab( $map_provider, 'map_provider' );

				}
			}
		}

		$this->tab_sections['general']['map'] = array(
			'title' => __( 'Preview', APP_TD ),
			'renderer' => array( __CLASS__, 'map_render' ),
			'fields' => array(),
		);

	}

	function admin_settings_needed_warning() {
		if ( ! empty( self::$geo_settings_warning ) ) {
			self::admin_msg( self::$geo_settings_warning );
		}
	}

	static function check_geocoder_render() {
		ob_start();
		echo '<div id="check_geocoder">';
		echo scbForms::input( array(
			'name' => '_blank',
			'type' => 'text',
			'extra' => array(
				'placeholder' => __( 'Enter postal address', APP_TD ),
				'class' => 'check-geocoder-input',
			),
		) );
		echo scbForms::input( array(
			'name' => '_blank',
			'type' => 'button',
			'value' => __( 'Test', APP_TD ),
			'extra' => array(
				'class' => 'check-geocoder-button button-secondary',
			),
		) );
		echo '<div id="check_geocoder_panel" style="background-color:#fff"></div>';
		echo '</div>';
		$ajax_nonce = wp_create_nonce( 'app_check_geocoder_nonce' );
		?>
			<script type="text/javascript">
				( function( $ ) {
					$( '.check-geocoder-button' ).click( function( e ) {
						var wrapper = $( this ).closest( '#check_geocoder' );
						var address = wrapper.find( '.check-geocoder-input' ).val();
						var panel = wrapper.find( '#check_geocoder_panel' );
						$.post(
							ajaxurl,
							{
								action: 'app_check_geocoder',
								address: address,
								security: '<?php echo $ajax_nonce; ?>'
							},
							function( data ) {
								panel.html( '<pre>' + JSON.stringify( data, null, 4 ) + '</pre>' );
							},
							"json"
						);
					} );
				} )( jQuery );
			</script>
		<?php

		return ob_get_clean();
	}

	public function _ajax_check_geocoder() {
		check_ajax_referer( 'app_check_geocoder_nonce', 'security' );

		$address = sanitize_text_field( $_POST['address'] );
		$api_result = appthemes_geocode_address( $address );

		$result = array(
			'request' => $address,
			'result'  => $api_result,
		);

		die ( json_encode( $result ) );
	}

	static function map_render() {

		$map_provider = APP_Map_Provider_Registry::get_active_map_provider();
		if ( ! $map_provider ) {
			return;
		}

		$options = APP_Map_Provider_Registry::get_map_provider_options( $map_provider->identifier() );

		$map_provider->options = $options;

		if ( true !== ( $required = $map_provider->has_required_vars() ) ) {

			$map_provider_tab = $map_provider->identifier() . '-map_provider';
			$url = admin_url( add_query_arg( array(
					'page' => $_GET['page'],
					'tab' => $map_provider_tab
				), 'admin.php' ) );

			$url = esc_url( $url );

			printf( __( '%1$s is missing a required settings value: %2$s, <a href="%3$s">Click here to update settings.</a>', APP_TD ), $map_provider->display_name( 'admin' ), $required, $url );

			return;
		}


		echo html( 'div', array( 'id' => 'map_div', 'style' => 'margin:10px 0 0 0;width:410px;height:350px;position:relative;' ) );

		?>
			<script type="text/javascript">
				jQuery(function() {
					var markers_opts = [
						{
							"lat" : 37.789903,
							"lng" : -122.400785,
							'marker_text' : '1',
							'popup_content' : '<h2>AppThemes</h2>',
							'draggable' : true
						}
					];

					jQuery('#map_div').appthemes_map({
						zoom: 15,
						auto_zoom: false,
						markers: markers_opts,
						center_lat: 37.789903,
						center_lng: -122.400785,
						marker_drag_end: function( lat, lng ) {
							console.log("lat: " + lat + ", lng: " + lng);
						}

					});
				});
			</script>
		<?php

		return;
	}

	/**
	 * Loads the gateway form fields into tabs
	 * @param  string $gateway Gateway identifier
	 * @return array           Array for the checkbox to enable the gateway
	 */
	function load_settings_tab( $class, $type ) {

		$types = array(
			'map_provider' => 'Map',
			'geocoder' => 'Geocoder'
		);

		$form_values = $class->form();
		$nicename = $class->identifier();

		$tab_section_name = $nicename . '-' . $type;

		if ( array_key_exists( 'fields', $form_values ) ) {

			// Wrap values
			foreach ( $form_values['fields'] as $key => $block ) {

				$value = $block['name'];
				$form_values['fields'][ $key ]['name'] = array( $type . '_settings', $nicename, $value );

			}

			$this->tab_sections[ $tab_section_name ]['general_settings'] = $form_values;
		} else {

			// Wrap values
			foreach ( $form_values as $s_key => $section ) {
				foreach ( $section['fields'] as $key => $block ) {

					$value = $block['name'];
					$form_values[ $s_key ]['fields'][ $key ]['name'] = array( $type . '_settings', $nicename, $value );

				}
			}

			$this->tab_sections[ $tab_section_name ] = $form_values;
		}

		// Only add a tab for gateways with a form
		$title = $class->display_name( 'admin' );
		if ( $form_values ) {
			$this->tabs->add( $tab_section_name, $types[ $type ] . ' - ' . $title );
		}
	}
}
