/**
 * Google Maps custom scripts.
 *
 * @since 4.0.0
 */
jQuery( function( $ ) {
	if ( typeof $.fn.appAddressAutocomplete !== "undefined" ) {
		$( ".app-address-field" ).appAddressAutocomplete();
	}
	// Add a map to the Home page callout in case it has listings-map class.
	$( ".home-cover .listing-map" ).prependTo( $( ".home-cover" ) );
} );

jQuery( function( $ ) {
	if ( typeof $.fn.appthemes_map === "undefined" ) {
		return;
	}
	var data = {};
	var markers = [];
	var ids = [];
	// Loop through all listing results and generate the markers.
	$( ".listing-map-data" ).each( function() {
		var id = $( this ).data( "id" );
		var lat = $( this ).data( "lat" );
		var lng = $( this ).data( "lng" );
		var title = $( this ).data( "title" );
		var image = $( this ).data( "image" );
		var address = $( this ).data( "address" );
		var permalink = $( this ).data( "permalink" );
		if ( lat == 0 || lng == 0 || ids.indexOf( id ) !== -1 ) {
			return true;
		}
		if ( typeof lat == "undefined" || typeof lng == "undefined" ) {
			return true;
		}
		// Build the bubble content.
		var contentString = '<div class="info-window-wrap">' + '<a href="' + permalink + '">' + '<img src="' + image + '">' + '<div class="info-window-body">' + "<h3>" + title + "</h3>" + '<span class="info-window-address">' + address + "</span>" + "</div>" + "</a>" + "</div>";
		var marker = {
			lat: lat,
			lng: lng,
			marker_text: title,
			popup_content: contentString
		};
		markers.push( marker );
		ids.push( id );
	} );
	if ( typeof InfoBubble !== "undefined" ) {
		$.appthemes.appthemes_map.prototype._create_marker_info = function() {
			// Hack to offset the infoBubble window.
			InfoBubble.prototype.getAnchorHeight_ = function() {
				return 35;
			};
			// Setup the infoBubble object.
			return new InfoBubble( {
				map: this.options.map,
				content: "",
				padding: 15,
				borderColor: "#F3F3F4",
				borderRadius: 4,
				minHeight: 100,
				maxHeight: 100,
				minWidth: 250,
				maxWidth: 250,
				shadowStyle: 0,
				arrowPosition: 50,
				disableAutoPan: false,
				hideCloseButton: true,
				backgroundClassName: "info-window-wrap"
			} );
		};
	}
	if ( typeof MarkerClusterer !== "undefined" ) {
		$( document ).on( "appthemes_mapcreate", function( e ) {
			var appthemes_map = $( e.target ).data( "appthemesAppthemes_map" );
			// Use custom cluster images. Name your images m[1-5].png
			var options = {};
			// Build out the group clustering.
			new MarkerClusterer( appthemes_map.options.map, appthemes_map.markers, options );
			// Remove the pre-loader after the canvas renders.
			appthemes_map.options.map.addListener( "tilesloaded", function() {
				if ( $( "#map-loading" ).length ) {
					$( "#map-loading" ).hide();
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
		$( ".listing-map" ).appthemes_map( data );
	}
} );

/**
 * General helper scripts.
 *
 * @since 4.0.0
 */
/**
 * Convert bytes into readable human file sizes.
 *
 * @param int bytes Raw number we want to convert.
 *
 * @since 4.0.0
 */
function humanFileSize( bytes ) {
	var thresh = 1e3;
	if ( Math.abs( bytes ) < thresh ) {
		return bytes + " B";
	}
	var units = [ "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB" ];
	var u = -1;
	do {
		bytes /= thresh;
		++u;
	} while ( Math.abs( bytes ) >= thresh && u < units.length - 1 );
	return bytes.toFixed( 0 ) + " " + units[ u ];
}

/* Kick-off Foundation */
jQuery( function( $ ) {
	$( document ).foundation();
	/* Enable Tabs deep linking for the Foundation 6.2.4 */
	$( "[data-tabs]" ).each( function() {
		var $elem = $( this );
		//use browser to open a tab, if it exists in this tabset
		if ( $elem.data( "deepLink" ) ) {
			var anchor = window.location.hash;
			//need a hash and a relevant anchor in this tabset
			if ( anchor.length && $elem.find( '[href="' + anchor + '"]' ).length ) {
				$elem.foundation( "selectTab", anchor );
				//roll up a little to show the titles
				if ( $elem.data( "deepLinkSmudge" ) ) {
					$( window ).load( function() {
						var offset = $elem.offset();
						$( "html, body" ).animate( {
							scrollTop: offset.top
						}, $elem.data( "deepLinkSmudgeDelay" ) );
					} );
				}
			}
		}
	} );
} );

/* General theme scripts */
jQuery( function( $ ) {
	"use strict";
	// Add header video class after the video is loaded.
	$( document ).on( "wp-custom-header-video-loaded", function() {
		$( "body" ).addClass( "has-header-video" );
	} );
	/**
	 * Typed.js on home page cover.
	 *
	 * @since 4.0.0
	 */
	if ( typeof typedStrings !== "undefined" ) {
		$( ".home-cover .element" ).typed( {
			strings: typedStrings,
			typeSpeed: 0,
			startDelay: 200,
			backSpeed: 0,
			backDelay: 2500,
			loop: true,
			loopCount: 3
		} );
	}
	/**
	 * Listing plan selection step.
	 *
	 * @since 4.0.0
	 */
	$( "#memberships tr" ).click( function() {
		$( this ).find( "input[type=radio]" ).prop( "checked", true );
	} );
	/* listing layout grid or list view toggle buttons */
	$( "#listing-layout button" ).click( function() {
		if ( $( this ).hasClass( "list" ) ) {
			$( this ).toggleClass( "disabled" ).toggleClass( "hollow" );
			$( ".listing-wrap" ).removeClass( "medium-up-3" ).addClass( "medium-up-12 list-view" );
			$( "#grid_view" ).removeClass( "disabled hollow" );
		} else if ( $( this ).hasClass( "grid" ) ) {
			$( this ).toggleClass( "disabled" ).toggleClass( "hollow" );
			$( ".listing-wrap" ).removeClass( "medium-up-12 list-view" ).addClass( "medium-up-3" );
			$( "#list_view" ).removeClass( "disabled hollow" );
		}
	} );
	/**
	 * AJAX listing favorites.
	 *
	 * @since 4.0.0
	 */
	$( document ).on( "click", "a.fave-button", function( e ) {
		e.preventDefault();
		// Save the relative location.
		var that = $( this );
		// Pack up the values we need.
		var data = {
			action: "classipress_favorites",
			id: that.attr( "data-id" ),
			type: that.attr( "data-type" ),
			nonce: that.attr( "data-nonce" )
		};
		// Send the ajax request.
		$.ajax( {
			type: "post",
			dataType: "json",
			context: that,
			data: data,
			url: AppThemes.ajaxurl,
			beforeSend: function() {
				that.addClass( "fave-clicked" );
				that.fadeTo( "fast", .5 );
				that.removeAttr( "href" );
			}
		} ).done( function( r ) {
			that.removeClass( "fave-clicked" );
			that.fadeTo( "fast", 1 );
			if ( r.success ) {
				that.attr( "data-type", r.data.type );
				$( ".fave-text", that ).text( r.data.text );
				$( "i", that ).attr( "class", r.data.icon );
				$( "i", that ).attr( "title", r.data.text );
			} else {
				alert( r.data );
			}
		} );
		return false;
	} );
	/**
	 * AJAX single listing widget contact owner popup.
	 *
	 * @since 4.0.0
	 */
	if ( $( "form#app-contact-form" ).length ) {
		/* listing contact owner ajax */
		var $vaForm = $( "form#app-contact-form" );
		var $vaResp = $( "#app-contact-form-response" );
		var $vaWrap = $( "#app-contact-form-wrap" );
		var $vaSubmit = $( "#app-contact-submit" );
		// Validate the form before posting.
		if ( $vaForm.length ) {
			$vaForm.validate();
		}
		$vaForm.on( "submit", function( e ) {
			e.preventDefault();
			// Make sure form has passed validation.
			if ( !$( this ).valid() ) {
				return;
			}
			// Pack up the values we need.
			var data = {
				action: "appthemes_contact_owner",
				id: $( "#post_ID" ).val(),
				nonce: $( "#_wpnonce" ).val(),
				name: $( "#contact_name" ).val(),
				email: $( "#contact_email" ).val(),
				message: $( "#contact_message" ).val(),
				honeypot: $( "#contact_username" ).val()
			};
			// Send the ajax request.
			$.ajax( {
				type: "post",
				dataType: "json",
				data: data,
				url: AppThemes.ajaxurl,
				beforeSend: function() {
					$vaResp.html( "" ).removeClass( "notice error" );
					$vaSubmit.attr( "disabled", true );
					$( "i.fa-spinner" ).css( "display", "inline-block" );
					$vaWrap.css( "opacity", "0.5" );
				}
			} ).done( function( r ) {
				$vaSubmit.attr( "disabled", false );
				$( "i.fa-spinner" ).css( "display", "none" );
				$vaWrap.css( "opacity", "1" );
				if ( r.success ) {
					$vaWrap.css( "display", "none" );
					$vaResp.html( r.data ).removeClass( "error" ).addClass( "notice success" );
				}
				// Show the success or error message.
				$vaResp.html( r.data ).addClass( "notice error" );
			} );
			return false;
		} );
	}
	/**
	 * Single listing widget image popup slider.
	 *
	 * @since 4.0.0
	 */
	function revealSlickSlider() {
		"use strict";
		var slider = $( "#listing-carousel" );
		if ( null === slider ) {
			return;
		}
		// Init the slider.
		slider.slick( {
			fade: true,
			dots: true,
			rtl: $( "html" ).attr( "dir" ) === "rtl"
		} );
		// Define the links to trigger the modal window.
		$( ".listing-photo-grid a" ).click( function( e ) {
			e.preventDefault();
			// Open the modal window.
			$( "#listingPhotosModal" ).foundation( "open" );
			// Re-draw position to allow first image to load.
			slider.slick( "setPosition", 0 );
			// Go to the slide image clicked on.
			slider.slick( "slickGoTo", parseInt( $( this ).data( "index" ) ) );
		} );
	}
	revealSlickSlider();
	/**
	 * Validate Ad form
	 *
	 * @since 4.0.0
	 */
	if ( typeof $.validator != "undefined" ) {
		$.validator.addMethod( "app_images_upload", function( value, element ) {
			return $( element ).parent().find( "input.fileupload:filled" ).length || $( element ).parent().data().appfilecount > 0;
		}, $.validator.messages.required );
		$( ".app-form" ).submit( function( e ) {
			if ( typeof tinyMCE != "undefined" ) {
				// update underlying textarea before submit validation
				tinyMCE.triggerSave();
			}
		} ).validate( {
			ignore: ".ignore",
			errorPlacement: function( error, element ) {
				if ( element.attr( "type" ) === "checkbox" || element.attr( "type" ) === "radio" ) {
					element.closest( "ol" ).after( error );
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function( element, errorClass, validClass ) {
				$.validator.defaults.highlight( element, errorClass, validClass );
				if ( "textarea" === element.type && $( element ).hasClass( "wp-editor-area" ) ) {
					$( element ).parent()[ 0 ].scrollIntoView();
				}
			}
		} );
		// comment form validation
		$( "#commentform" ).validate();
	}
	/**
	 * Sortable images
	 *
	 * @since 4.2.0
	 */
	if ( $( ".app-attachment-list" ).length > 0 ) {
		$( ".app-attachment-list" ).sortable( {
			refreshPositions: true,
			handle: ".thumbnail",
			scroll: false,
			// Set to false since it scrolls page to the right to mobile menu area.
			//forcePlaceholderSize: true, // Can't get it working, used workaround below.
			connectWith: ".app-attachment-list",
			start: function( e, ui ) {
				ui.placeholder.height( ui.item.height() );
				ui.placeholder.css( "visibility", "visible" );
			}
		} ).disableSelection();
		$( ".app-attachment-list" ).on( "click", "[data-attachment-button]", function() {
			var btn = $( this ).data( "attachment-button" );
			var checked = !$( this ).hasClass( "hollow" );
			var radio = $( this ).closest( ".media-object-section" ).find( '[name="' + btn + '"]' );
			radio.prop( "checked", !checked );
			$( '[data-attachment-button="' + btn + '"]' ).addClass( "hollow" );
			$( this ).toggleClass( "hollow", checked );
		} );
	}
	/**
	 * Sticky header
	 *
	 * @since 4.2.0
	 */
	if ( $( ".sticky-header .header .top-bar" ).length > 0 ) {
		$( function() {
			$( ".sticky-header .header .top-bar" ).wrapAll( '<div data-sticky-container><div id="sticky_header" data-sticky></div></div>' );
			$( "#sticky_header" ).parent().height( $( "#sticky_header" ).height() );
			new Foundation.Sticky( $( "#sticky_header" ), {
				marginTop: 0
			} );
			var slide_args = {
				progress: function() {
					$( "#sticky_header" ).parent().height( $( "#sticky_header" ).height() );
				}
			};
			var shrink_navs = $( ".shrink_sticky_top_bar #first-top-bar, .shrink_sticky_title_bar #top-bar-primary, .shrink_sticky_nav_bar #top-bar-secondary" );
			$( "#sticky_header" ).on( "sticky.zf.stuckto:top", function() {
				shrink_navs.slideUp( slide_args );
			} ).on( "sticky.zf.unstuckfrom:top", function() {
				shrink_navs.slideDown( slide_args );
			} );
		} );
	}
	/**
	 * Masonry categories
	 *
	 * @param el jQuery element
	 *
	 * @since 4.2.0
	 */
	function masonry_categories( el ) {
		var $grid = el.imagesLoaded( function() {
			$grid.masonry( {
				itemSelector: ".parent-cat-wrap"
			} );
		} );
	}
	$( ".listing-cats-page" ).each( function() {
		masonry_categories( $( this ) );
	} );
	$( document ).on( "show.zf.dropdownmenu", function( ev, $el ) {
		$( this ).find( ".menu.dropdown .listing-cats-dropdown" ).each( function() {
			masonry_categories( $( this ) );
		} );
	} );
	// MIGRATED FROM VERSION 3
	/* auto complete the search field with tags */
	$( "#search_keywords" ).on( "keydown", function( event ) {
		if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
			event.preventDefault();
		}
	} ).autocomplete( {
		source: function( request, response ) {
			$.ajax( {
				url: cpSettings.ajax_url,
				dataType: "json",
				data: {
					action: "ajax-tag-search-front",
					tax: cpSettings.appTaxTag,
					term: request.term
				},
				error: function( XMLHttpRequest, textStatus, errorThrown ) {},
				success: function( data ) {
					// remove the current input
					var terms = request.term.split( " " );
					terms.pop();
					terms.push( "" );
					terms = terms.join( " " );
					response( $.map( data, function( item ) {
						return {
							term: item,
							value: terms + unescapeHtml( item.name )
						};
					} ) );
				}
			} );
		},
		search: function() {
			// custom minLength
			var term = this.value.split( " " ).pop();
			if ( term.length < 2 ) {
				return false;
			}
		},
		select: function( event, ui ) {
			this.value = ui.item.value + " ";
			return false;
		},
		minLength: 2
	} );
	// used to unescape any encoded html passed from ajax json_encode (i.e. &amp;)
	var unescapeHtml = function( html ) {
		var temp = document.createElement( "div" );
		temp.innerHTML = html;
		var result = temp.childNodes[ 0 ].nodeValue;
		temp.removeChild( temp.firstChild );
		return result;
	};
	/* Position price currency */
	var cp_currency_position = function( price ) {
		var position = cpSettings.currency_position;
		var currency = cpSettings.ad_currency;
		switch ( position ) {
			case "left":
				return currency + price;

			case "left_space":
				return currency + " " + price;

			case "right":
				return price + currency;

			default:
				// right_space
				return price + " " + currency;
		}
	};
	$( ".refine-search-field-wrap .slider" ).on( "moved.zf.slider", function() {
		$( "#amount" ).val( cp_currency_position( $( "#price_min" ).val() ) + " - " + cp_currency_position( $( "#price_max" ).val() ) );
	} );
	$( ".refine-categories-list-label" ).on( "click", function() {
		$( this ).toggleClass( "open" ).next( ".refine-categories-list-wrap" ).animate( {
			height: [ "toggle", "swing" ],
			opacity: "toggle"
		}, 200 );
		return false;
	} );
	$( ".refine-categories-list-label" ).each( function() {
		var input_name = $( this ).attr( "for" );
		var input = $( 'input[name="' + input_name + '[]"]' );
		if ( input.filter( ":checked" ).length > 0 ) {
			$( this ).toggleClass( "open" ).next( ".refine-categories-list-wrap" ).show();
		}
	} );
	/* CATEGORIES */
	/* initialize the category selection on add-new page */
	if ( $( "#step1 .form_step" ).length > 0 ) {
		cp_handle_form_category_select();
	}
	/* auto select dropdown category if previously selected elsewhere */
	if ( $( "#ad_cat_id" ).val() > 0 ) {
		$( "#ad_cat_id" ).trigger( "change" );
	}
	/* Used for selecting category on add-new form */
	function cp_handle_form_category_select() {
		//if on page load the parent category is already selected, load up the child categories
		$( "#catlvl0" ).attr( "level", 0 );
		//bind the ajax lookup event to #ad_cat_id object
		$( document ).on( "change", "#ad_cat_id", function() {
			var currentLevel = parseInt( $( this ).parent().attr( "level" ), 10 );
			cp_get_subcategories( $( this ), "catlvl", currentLevel + 1, cpSettings.ad_parent_posting );
			//rebuild the entire set of dropdowns based on which dropdown was changed
			$.each( $( this ).parent().parent().children(), function( childLevel, childElement ) {
				if ( currentLevel + 1 < childLevel ) $( childElement ).remove();
				if ( currentLevel + 1 === childLevel ) $( childElement ).removeClass( "hasChild" );
			} );
			//find the deepest selected category and assign the value to the "chosenCateory" field
			if ( $( this ).val() > 0 ) {
				$( "#chosenCategory input:first" ).val( $( this ).val() );
			} else if ( $( "#catlvl" + ( currentLevel - 1 ) + " select" ).val() > 0 ) {
				$( "#chosenCategory input:first" ).val( $( "#catlvl" + ( currentLevel - 1 ) + " select" ).val() );
			} else {
				$( "#chosenCategory input:first" ).val( "-1" );
			}
		} );
	}

	function cp_get_subcategories( dropdown, results_div_id, level, allow_parent_posting ) {
		var parent_dropdown = $( dropdown ).parent();
		var category_ID = $( dropdown ).val();
		var results_div = results_div_id + level;
		if ( !$( parent_dropdown ).hasClass( "hasChild" ) ) {
			$( parent_dropdown ).addClass( "hasChild" ).parent().append( '<div id="' + results_div + '" level="' + level + '" class="childCategory"></div>' );
		}
		$.ajax( {
			type: "POST",
			url: cpSettings.ajax_url,
			dataType: "json",
			data: {
				action: "dropdown-child-categories",
				cat_id: category_ID,
				listing_id: cpSettings.listing_id,
				level: level
			},
			//show loading just when dropdown changed
			beforeSend: function() {
				$( "#getcat" ).hide();
				$( dropdown ).addClass( "ui-autocomplete-loading" ).slideDown( "fast" );
			},
			//stop showing loading when the process is complete
			complete: function() {
				$( dropdown ).removeClass( "ui-autocomplete-loading" );
			},
			error: function( XMLHttpRequest, textStatus, errorThrown ) {},
			// if data is retrieved, store it in html
			success: function( data ) {
				var whenEmpty;
				// child categories found so build and display them
				if ( data.success === true ) {
					$( "#" + results_div ).html( data.html ).slideDown( "fast" );
					//build html from ajax post
					// Trigger the 'change' event for the sub-categories.
					if ( $( "#" + results_div + " select" ).val() ) {
						$( "#" + results_div + " select" ).trigger( "change" );
					}
					if ( level === 1 ) {
						whenEmpty = false;
					} else {
						whenEmpty = true;
					}
				} else {
					$( "#" + results_div ).slideUp( "fast" );
					if ( $( dropdown ).val() === -1 && level === 2 ) {
						whenEmpty = false;
					} else {
						whenEmpty = true;
					}
				}
				// always check if go button should be on or off, $ parent is used for traveling backup the category heirarchy
				if ( allow_parent_posting === "yes" && $( "#chosenCategory input:first" ).val() > 0 ) {
					$( "#getcat" ).fadeIn();
				} else if ( whenEmpty && allow_parent_posting === "whenEmpty" && $( "#chosenCategory input:first" ).val() > 0 ) {
					$( "#getcat" ).fadeIn();
				} else if ( $( "#" + results_div_id + ( level - 1 ) ).hasClass( "childCategory" ) && $( dropdown ).val() > -1 && allow_parent_posting === "no" ) {
					$( "#getcat" ).fadeIn();
				} else {
					$( "#getcat" ).fadeOut();
				}
			}
		} );
	}
	// Expand/collapse .table-expand-row-content
	$( "[data-open-details]" ).click( function( e ) {
		if ( $( this ).find( ".expand-icon" ).is( ":visible" ) ) {
			$( this ).next().toggleClass( "is-active" );
			$( this ).toggleClass( "is-active" );
		}
	} );
} );

// End main function wrapper.
/* Used for enabling the image for uploads */
function enableNextImage( a, i ) {
	jQuery( "#upload" + i ).removeAttr( "disabled" );
}

/* Used for deleting ad on customer dashboard */
function confirmBeforeDeleteAd() {
	return confirm( cpSettings.delete_item );
}
