/**
 * Google Maps custom scripts.
 *
 * @since 4.0.0
 */

jQuery( function( $ ) {
	if ( typeof $.fn.appAddressAutocomplete !== "undefined" ) {
		$( '.app-address-field' ).appAddressAutocomplete();
	}

	// Add a map to the Home page callout in case it has listings-map class.
	$( '.home-cover .listing-map' ).prependTo( $( '.home-cover' ) );
} );

jQuery( function( $ ) {

	if ( typeof $.fn.appthemes_map === "undefined" ) {
		return;
	}

	var data = {};
	var markers = [];
	var ids = [];

	// Loop through all listing results and generate the markers.
	$( '.listing-map-data' ).each( function() {
		var id = $( this ).data( 'id' );
		var lat = $( this ).data( 'lat' );
		var lng = $( this ).data( 'lng' );
		var title = $( this ).data( 'title' );
		var image = $( this ).data( 'image' );
		var address = $( this ).data( 'address' );
		var permalink = $( this ).data( 'permalink' );

		if ( lat == 0 || lng == 0 || ids.indexOf( id ) !== -1 ) {
			return true;
		}

		if ( typeof lat == 'undefined' || typeof lng == 'undefined' ) {
			return true;
		}

		// Build the bubble content.
		var contentString = '<div class="info-window-wrap">' +
			'<a href="' + permalink + '">' +
			'<img src="' + image + '">' +
			'<div class="info-window-body">' +
			'<h3>' + title + '</h3>' +
			'<span class="info-window-address">' + address + '</span>' +
			'</div>' +
			'</a>' +
			'</div>';

		var marker = {
			lat: lat,
			lng: lng,
			marker_text: title,
			popup_content: contentString
		};

		markers.push( marker );
		ids.push( id );

	} );

	if ( typeof InfoBubble !== 'undefined' ) {
		$.appthemes.appthemes_map.prototype._create_marker_info = function() {
			// Hack to offset the infoBubble window.
			InfoBubble.prototype.getAnchorHeight_ = function() {
				return 35;
			};

			// Setup the infoBubble object.
			return new InfoBubble( {
				map: this.options.map,
				content: '',
				padding: 15,
				borderColor: '#F3F3F4',
				borderRadius: 4,
				minHeight: 100,
				maxHeight: 100,
				minWidth: 250,
				maxWidth: 250,
				shadowStyle: 0,
				arrowPosition: 50,
				disableAutoPan: false,
				hideCloseButton: true,
				backgroundClassName: 'info-window-wrap'
			} );
		};
	}

	if ( typeof MarkerClusterer !== 'undefined' ) {
		$( document ).on( 'appthemes_mapcreate', function( e ) {
			var appthemes_map = $( e.target ).data( 'appthemesAppthemes_map' );

			// Use custom cluster images. Name your images m[1-5].png
			var options = {
				//imagePath: 'images/m'
			};

			// Build out the group clustering.
			new MarkerClusterer( appthemes_map.options.map, appthemes_map.markers, options );

			// Remove the pre-loader after the canvas renders.
			appthemes_map.options.map.addListener( 'tilesloaded', function() {
				if ( $( '#map-loading' ).length ) {
					$( '#map-loading' ).hide();
				}
			} );
		} );
	}


	if ( markers.length > 0 ) {

		data = {
			center_lat: markers[ 0 ].lat,
			center_lng: markers[ 0 ].lng,
			zoom: 17,
			auto_zoom: markers.length > 1,
			animation: true,
			markers: markers
		};

		$( '.listing-map' ).appthemes_map( data );
	}
} );
