/* global $, jQuery, l10n, validateL10n, appthemes_map_vars, appthemes_map_icon */
( function( $ ) {

	$.extend( true, $.appthemes.appthemes_map.prototype, {
		options: {
			zoom: 17
		},

		_auto_zoom: function() {

			if ( this.markers.length < 1 ) {
				return;
			}

			var markerBounds = new google.maps.LatLngBounds();
			for ( var i = 0; i < this.markers.length; i++ ) {
				markerBounds.extend( this.markers[i].getPosition() );
			}

			// Don't zoom in too far on only one marker
			if ( markerBounds.getNorthEast().equals( markerBounds.getSouthWest() ) ) {
				var extendPoint1 = new google.maps.LatLng( markerBounds.getNorthEast().lat() + 0.01, markerBounds.getNorthEast().lng() + 0.01 );
				var extendPoint2 = new google.maps.LatLng( markerBounds.getNorthEast().lat() - 0.01, markerBounds.getNorthEast().lng() - 0.01 );
				markerBounds.extend( extendPoint1 );
				markerBounds.extend( extendPoint2 );
			}

			this.options.map.fitBounds( markerBounds );
		},

		_create_map: function() {
			var mapOptions = {
				zoom: this.options.zoom,
				scrollwheel: false,
				center: { lat: this.options.center_lat, lng: this.options.center_lng },
				streetViewControl: true,
				styles: this.options.styles
			};

			return new google.maps.Map( this.element[0], mapOptions );
		},

		_create_marker: function( marker_opts ) {
			if ( this.options.use_app_icon ) {

				_icon_url = this._get_icon_url( marker_opts );

				var marker_icon = {
					url: _icon_url,
					scaledSize: new google.maps.Size(this.options.app_icon_width, this.options.app_icon_height)
				};

				/*
				var marker_icon_shadow = new google.maps.MarkerImage( this.options.app_icon_shadow_url,
						new google.maps.Size( this.options.app_icon_shadow_width, this.options.app_icon_shadow_height ),
						new google.maps.Point( 0, 0 ) );

				var marker_icon_shape = {
					coord: this.options.app_icon_click_coords,
					type: "rect"
				};
				*/
			}

			var marker = new google.maps.Marker( {
				position: new google.maps.LatLng( marker_opts.lat, marker_opts.lng ),
				map: this.options.map,
				animation: ( this.options.animation ? google.maps.Animation.DROP : false ),
				draggable: ( marker_opts.draggable ? true : false ),
				title: ( typeof marker_opts.marker_text !== 'undefined' ? marker_opts.marker_text : '' ),
				icon: ( typeof marker_icon !== 'undefined' ? marker_icon : '' ),
				//shadow: ( typeof marker_icon_shadow !== 'undefined' ? marker_icon_shadow : '' ),
				//shape: ( typeof marker_icon_shape !== 'undefined' ? marker_icon_shape : '' ),
				optimized: false
			} );

			return marker;
		},

		_create_marker_info: function() {
			return new google.maps.InfoWindow( {
				content: '',
				pixelOffset: new google.maps.Size( parseInt( this.options.app_popup_offset_x ), parseInt( this.options.app_popup_offset_y ) ),
				maxWidth: this.options.info_max_width
			} );
		},

		_set_marker_info: function( info, marker, marker_opts ) {
			var map = this.options.map;

			google.maps.event.addListener( map, 'click', function() {
				info.close();
			} );

			google.maps.event.addListener( marker, "click", function( e ) {
				info.close();
				info.setContent( marker_opts.popup_content );
				info.setPosition( marker.getPosition() );
				info.open( map, marker );
			} );
		},

		_set_marker_anchor: function( marker, anchor ) {
			google.maps.event.addListener( marker, "click", function( e ) {
				location = anchor;
			} );
		},

		_get_marker_position: function( marker ) {
			return marker.getPosition();
		},

		_marker_drag_end: function( marker ) {
			var $this = this;
			google.maps.event.addListener( marker, "dragend", function() {
				var drag_position = marker.getPosition();

				$this.options.marker_drag_end( marker, drag_position.lat(), drag_position.lng() );
			});
		},

		_update_marker_position: function( updated_pos, marker, map ) {
			var updated_position = new google.maps.LatLng( updated_pos.lat, updated_pos.lng );
			map.setCenter( updated_position );
			marker.setPosition( updated_position );
		},

		_directions_btn_handler: function( start_address, end_address, directions_panel, print_directions_btn ) {
			var $this = this;
			var directionsDisplay = new google.maps.DirectionsRenderer();
			var directionsService = new google.maps.DirectionsService();
			var start = jQuery( '#' + start_address ).val();
			var end = end_address; // This is the address for the listing
			var map = this.options.map;
			var request = {
				origin: start,
				destination: end,
				region: appthemes_map_vars.geo_region,
				travelMode: google.maps.TravelMode.DRIVING,
				unitSystem: ( appthemes_map_vars.geo_unit == 'mi' ) ? google.maps.UnitSystem.IMPERIAL : google.maps.UnitSystem.METRIC
			};

			directionsService.route( request, function( result, status ) {
				jQuery( '#' + directions_panel ).show();
				if ( status == google.maps.DirectionsStatus.OK ) {
					directionsDisplay.setDirections( result );
					this.markers[0].setVisible( false );
					jQuery( '#' + print_directions_btn ).slideDown( 'fast' );
					directionsDisplay.setPanel( document.getElementById( directions_panel ) );
					directionsDisplay.setMap( map );
				} else {
					jQuery( '#' + print_directions_btn ).hide();
					jQuery( '#' + directions_panel ).html( appthemes_map_vars.text_directions_error ).fadeOut( 5000, function() {
						$( this ).html( '' );
					} );
					// restore original map on failure
					directionsDisplay.setMap( null );

					if ( $this.options.markers ) {
						$.each( $this.options.markers, function( i, marker_opts ) {
							var marker = $this.add_marker( marker_opts );
							$this.markers.push( marker );
						} );

						$this.auto_zoom();
					}
				}
			} );
		}
	} );

} )( jQuery );

( function( $ ) {

	var onPlaceChange = $.appthemes.appAddressAutocomplete.prototype.onPlaceChange;

	$.extend( true, $.appthemes.appAddressAutocomplete.prototype, {

		enterpressed: false,

		onPlaceChange: function() {
			onPlaceChange.apply( this, arguments );
			if ( this.enterpressed ) {
				this.options.form.submit();
			}
		},

		loadAPI: function() {
			var api = new google.maps.places.Autocomplete(
				this.element[0], {
					types: []
				}
			);

			var $this = this;

			// Trigger if location field value changes.
			api.addListener( 'place_changed', function() {
				$this.onPlaceChange();
			} );

			// Emulate location select when enter key (13 o 9) is pressed.
			this.element.keydown( function( event ) {
				if ( event.keyCode == 13 || event.keyCode == 9 ) {
					if ( $( '.pac-item-selected' ).length === 0 ) {
						google.maps.event.trigger( event.target, "keydown", {
							keyCode: 40
						} );
					}
					google.maps.event.trigger( event.target, "keydown", {
						keyCode: 13
					} );
					$this.enterpressed = true;
					return false;
				} else {
					return true;
				}
			} );

			return api;
		},

		getPlaceData: function() {
			var place = this.api.getPlace();

			if ( !place.geometry && '' !== $.trim( this.element.val() ) ) {
				window.alert( this.options.no_geocode );
				return;
			}

			var data = {
				lat: place.geometry.location.lat(),
				lng: place.geometry.location.lng(),
				formatted_address: place.formatted_address,
				place_id: place.place_id,
				address_components: place.address_components,
				radius: this.options.radius_input.val()
			};

			// The smart radius implementation.
			if ( place.geometry.viewport && this.options.radius_input.length !== 0 && this.options.default_radius === '' ) {
				var ne_lat = place.geometry.viewport.getNorthEast().lat();
				var ne_lng = place.geometry.viewport.getNorthEast().lng();
				var sw_lat = place.geometry.viewport.getSouthWest().lat();
				var sw_lng = place.geometry.viewport.getSouthWest().lng();

				data.radius = this.getSmartRadius( data.lat, data.lng, sw_lat, sw_lng, ne_lat, ne_lng, this.options.geo_unit );
			}

			return data;
		}

	} );

} )( jQuery );
