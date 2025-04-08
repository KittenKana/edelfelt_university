/* global $, jQuery, l10n, validateL10n, appthemes_map_vars, appthemes_map_icon */
( function( $ ) {

	$.widget( "appthemes.appthemes_map", {

		options: {
			zoom: 14,
			center_lat: 0,
			center_lng: 0,
			map: null,
			info: null,
			markers: [],
			animation: false,
			info_max_width: null,
			marker_drag_end: function(){},
			auto_zoom: false,
			// Directions options.
			directions: false,
			get_directions_btn: 'get_directions',
			directions_from: 'directions_from',
			directions_panel: 'directions_panel',
			end_address: '',
			print_directions_btn: 'print_directions',
			// AppIcon options.
			use_app_icon: true,
			app_icon_color: '#c44e44',
			app_icon_template: [
				'<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" ',
					'width="{{ width }}" height="{{ height }}" x="0px" y="0px" viewBox="0 0 52.3 84.7" enable-background="new 0 0 52.3 84.7">',
					'<g>',
						'<path id="svg_2" fill="{{ color }}" d="m 26.245638,84.700002 c -1.9,-9.5 -5.4,-17.4 -9.500003,-24.8 -3.1,-5.4 -6.6,-10.5 -9.9000004,-15.7 -1.1,-1.8 -2,-3.6 -3.1,-5.5 -2.1,-3.699995 -3.79999997,-7.899995 -3.69999997,-13.399995 0.1,-5.4 1.69999997,-9.7 3.89999997,-13.2 3.7,-5.7999995 9.9000004,-10.4999995 18.1000034,-11.79999949 6.8,-1 13.1,0.69999999 17.6,3.29999999 3.7,2.2 6.5,5 8.7,8.3999995 2.3,3.5 3.8,7.7 3.9,13.2 0.1,2.8 -0.4,5.4 -1,7.5 -0.7,2.2 -1.7,3.999995 -2.6,5.899995 -1.8,3.8 -4.1,7.2 -6.4,10.7 -6.8,10.5 -13.2,21 -16,35.4 z"/>',
						'<circle id="svg_4" fill="#FFFFFF" cx="26.15" cy="25.3" r="13.2"/>',
					'</g>',
				'</svg>'
                ].join('\n'),
			/*app_icon: 'teal',
			app_icon_url: '',
			app_icon_base_url: '',*/
			app_icon_width: 35,
			app_icon_height: 45,
			/*app_icon_point_x: 11,
			app_icon_point_y: 26,
			app_icon_click_coords: [ 1, 1, 19, 17 ],
			app_icon_shadow_url: '',
			app_icon_shadow_width: 24,
			app_icon_shadow_height: 2,
			app_icon_shadow_point_x: 11,
			app_icon_shadow_point_y: 2,*/
			app_popup_offset_x: 0,
			app_popup_offset_y: 0
		},

		/**
		 * Marker objects collection, specific to a service provider.
		 */
		markers: [],

		_create: function() {
			this.options = $.extend( {}, $.fn.appthemes_map.defaults, this.options );

			if ( typeof appthemes_map_vars !== 'undefined' ) {
				$.extend( this.options, appthemes_map_vars );
			}

			if ( typeof appthemes_map_icon !== 'undefined' ) {
				$.extend( this.options, appthemes_map_icon );
			}

			if ( ! this.options.map ) {
				this.options.map = this._create_map();
			}

			if ( ! this.options.info ) {
				this.options.info = this._create_marker_info();
			}

			if ( this.options.directions ) {
				jQuery( '#' + this.options.get_directions_btn ).click( function() {
					this._directions_btn_handler( this.options.directions_from, this.options.end_address, this.options.directions_panel, this.options.print_directions_btn );
				} );
			}

			if ( this.options.markers ) {
				var $this = this;
				$.each( this.options.markers, function( i, marker_opts ) {
					var marker = $this.add_marker( marker_opts );
					$this.markers.push( marker );
				} );

				if ( this.options.auto_zoom ) {
					this.auto_zoom();
				}
			}

			this._trigger( 'ready', null, this );
		},

		// Public methods.
		// These methods just call callbacks in backward compatibility purposes.

		auto_zoom: function() {
			this._auto_zoom();
		},

		add_marker: function( marker_opts ) {
			return this._add_marker( marker_opts );
		},

		update_marker_position: function( updated_pos ) {
			var marker_key = updated_pos.marker_key ? updated_pos.marker_key : 0;
			marker = this.markers[ marker_key ];

			this._update_marker_position( updated_pos, marker, this.options.map );
		},

		// Private methods.

		_get_icon_svg: function( marker_opts ) {
			var icon = $.extend( {
				icon_color: this.options.app_icon_color,
				icon_template: this.options.app_icon_template,
				icon_width: this.options.app_icon_width,
				icon_height: this.options.app_icon_height
			}, marker_opts );

			var svg = icon.icon_template
				.replace( '{{ color }}', icon.icon_color )
				.replace( '{{ width }}', icon.icon_width )
				.replace( '{{ height }}', icon.icon_height );

			return svg;
		},

		_get_icon_url: function( marker_opts ) {
			var _icon_url = this.options.app_icon_url;

			if ( ! _icon_url ) {
				var svg = this._get_icon_svg( marker_opts );
				//_icon_url = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent( svg );
				_icon_url = "data:image/svg+xml;charset=UTF-8;base64," + btoa( svg );
			}

			return _icon_url;
		},

		_add_marker: function( marker_opts ) {

			var marker = this._create_marker( marker_opts );

			marker.key = ( this.markers.length );

			if ( typeof marker_opts.popup_content !== 'undefined' ) {
				this._set_marker_info( this.options.info, marker, marker_opts );
			}

			if ( typeof marker_opts.anchor !== 'undefined' ) {
				this._set_marker_anchor( marker, marker_opts.anchor );
			}

			if ( typeof marker_opts.draggable !== 'undefined' ) {
				this._marker_drag_end( marker );
			}

			return marker;
		},


		// Provider-specific methods. Needs to be implemented in the prototype.

		_auto_zoom: function() {},

		_create_map: function() {},

		_create_marker: function( marker_opts ) { return {}; },

		_create_marker_info: function() {},

		_set_marker_info: function( info, marker, marker_opts ) {},

		_set_marker_anchor: function( marker, anchor ) {},

		_get_marker_position: function( marker ) {},

		_marker_drag_end: function( marker ) {},

		_update_marker_position: function( updated_pos, marker, map ) {},

		_directions_btn_handler: function( start_address, end_address, directions_panel, print_directions_btn ) {}
	} );

	// This is how the plugin can be extended by a provider custom options and methods.
	//$.appthemes.appthemes_map.prototype.options.zoom = 15;

	// This is how to create a callback function and apply it to the plugin created event.
	//$( document ).on( 'appthemes_mapcreate', function( e, appthemes_map ) {
		//var appthemes_map = $( e.target ).data('appthemesAppthemes_map' );
	//} );

} )( jQuery );


( function( $ ) {

	$.widget( "appthemes.appAddressAutocomplete", {

		options: {
			form: null,
			radius_input: null,
			lat_input: null,
			lng_input: null,
			geo_unit: 'mi',
			default_radius: ''
		},

		api: null,

		_create: function() {

			if ( typeof appthemes_map_vars !== 'undefined' ) {
				$.extend( this.options, appthemes_map_vars );
			}

			if ( ! this.options.form ) {
				this.options.form = this.element.closest( 'form' );
			}

			if ( ! this.options.radius_input ) {
				this.options.radius_input = this.options.form.find( 'input[name=radius]' );
			}

			if ( ! this.options.lat_input ) {
				this.options.lat_input = this.options.form.find( 'input[name=lat]' );
			}

			if ( ! this.options.lng_input ) {
				this.options.lng_input = this.options.form.find( 'input[name=lng]' );
			}

			this.api = this.loadAPI( this.element );

			var $this = this;

			this.options.form.submit( function( e ) {
				$this.formHandler();
			} );

			this._trigger( 'ready', null, this );
		},

		loadAPI: function() {},

		getPlaceData: function() {},

		onPlaceChange: function() {
			var data = this.getPlaceData();

			if ( typeof data.lat === 'undefined' && '' !== $.trim( this.element.val() ) ) {
				window.alert( this.options.no_geocode );
				return;
			}

			this.populateInputs( data );

			this._trigger( 'onPlaceChange', null, data );
		},

		formHandler: function() {
			// Get the location value and trim it.
			var loc = $.trim( this.element.val() );
			// Check if empty of not
			if ( loc === '' ) {
				this.populateInputs();
				this._trigger( 'onSubmitEmptyPlace', null, this );
			}
		},

		populateInputs: function( data ) {
			data = $.extend( {}, {
				formatted_address: '',
				lat: '',
				lng: '',
				radius: this.options.radius_input.val()
			}, data );

			this.element.val( data.formatted_address );
			this.options.lat_input.val( data.lat );
			this.options.lng_input.val( data.lng );
			this.options.radius_input.val( data.radius );
			this._trigger( 'populateInputs', null, data );
		},

		getDistance: function( lat_1, lng_1, lat_2, lng_2, unit ) {
			var earth_radius = ( 'mi' === unit ) ? 3959 : 6371;

			var alpha = ( lat_2 - lat_1 ) / 2;
			var beta = ( lng_2 - lng_1 ) / 2;

			// Converts from degrees to radians.
			Math.radians = function( degrees ) {
				return degrees * Math.PI / 180;
			};
			var a = Math.sin( Math.radians( alpha ) ) * Math.sin( Math.radians( alpha ) ) +
				Math.cos( Math.radians( lat_1 ) ) * Math.cos( Math.radians( lat_2 ) ) *
				Math.sin( Math.radians( beta ) ) * Math.sin( Math.radians( beta ) );

			var distance = 2 * earth_radius * Math.asin( Math.min( 1, Math.sqrt( a ) ) );

			distance = distance.toFixed( 4 );

			return distance;
		},

		getSmartRadius: function( center_lat, center_lng, sw_lat, sw_lng, ne_lat, ne_lng, unit ) {
			var distance_a = this.getDistance( center_lat, center_lng, sw_lat, sw_lng, unit );
			var distance_b = this.getDistance( center_lat, center_lng, ne_lat, ne_lng, unit );
			var distance_c = this.getDistance( center_lat, center_lng, ne_lat, sw_lng, unit );
			var distance_d = this.getDistance( center_lat, center_lng, sw_lat, ne_lng, unit );

			var radius = Math.max( distance_a, distance_b, distance_c, distance_d, 1 );

			return radius;
		}

	} );

	// This is how the plugin can be extended by a provider custom options and methods.
	//$.appthemes.appthemes_map.prototype.options.foo = bar;

	// This is how to create a callback function and apply it to the plugin created event.
	//$( document ).on( 'appAddressAutocompletecreate', function( e, input ) {
		//var input = $( e.target ).data('appthemesAppAddressAutocomplete' );
	//} );

} )( jQuery );