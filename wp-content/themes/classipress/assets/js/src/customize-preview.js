/**
 * Real-time changes for elements in the Customizer preview.
 *
 * @since 4.0.0
 */
( function( $ ) {
	'use strict';

	var style = $( '#classipress-color-scheme-css' );

	if ( !style.length ) {
		style = $( 'head' ).append( '<style type="text/css" id="classipress-color-scheme-css" />' )
			.find( '#classipress-color-scheme-css' );
	}

	var sticky_header_background_style = $( '#classipress-sticky-header-background-css' );

	if ( !sticky_header_background_style.length ) {
		sticky_header_background_style = $( 'head' ).append( '<style type="text/css" id="classipress-sticky-header-background-css" />' )
			.find( '#classipress-sticky-header-background-css' );
	}

	var sticky_header_color_style = $( '#classipress-sticky-header-color-css' );

	if ( !sticky_header_color_style.length ) {
		sticky_header_color_style = $( 'head' ).append( '<style type="text/css" id="classipress-sticky-header-color-css" />' )
			.find( '#classipress-sticky-header-color-css' );
	}

	/**
	 * Site title.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	/**
	 * Background color.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'header_text_site_title', function( value ) {
		value.bind( function( to ) {
			$( '.site-title' ).css( {
				'clip': 'rect(1px, 1px, 1px, 1px)',
				'position': 'absolute'
			} );
		} );
	} );

	/**
	 * Site tagline.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	/**
	 * Color schemes.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'color_scheme', function( value ) {
		value.bind( function( to ) {
			$( 'body' )
				.removeClass( 'theme-blue theme-green theme-orange theme-red theme-teal theme-purple theme-pink theme-magenta theme-charcoal theme-custom' )
				.addClass( 'theme-' + to );
		} );
	} );

	/**
	 * Custom color.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'color_scheme_custom', function( value ) {
		value.bind( function( to ) {

			// Update custom color CSS.
			var style = $( '#custom-theme-colors' ),
				color = style.data( 'color' ),
				css = style.html();

			// Find and replace all instances of the color hex.
			css = css.split( color ).join( to );
			style.html( css ).data( 'color', to );
		} );
	} );

	/**
	 * Background color.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'background_color', function( value ) {
		value.bind( function( to ) {
			$( '#content.off-canvas-content' ).css( 'background-color', to );
		} );
	} );

	/**
	 * Header background color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_top_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.header #first-top-bar' ).css( 'background-color', to );
		} );
	} );

	/**
	 * Header text color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'top_header_menu_color', function( value ) {
		value.bind( function( to ) {
			$( '.header #first-top-bar nav > ul > li > a' ).css( 'color', to );
		} );
	} );

	/**
	 * Header background color.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'header_background_color', function( value ) {
		value.bind( function( to ) {
			$( '#top-bar-primary' ).css( 'background-color', to );
			// We know that color is not exactly that color, but we don't want
			// to calculate darker/lighter colors in js. It's ok for preview.
			$( '.header #top-bar-primary' ).css( 'border-bottom-color', to );
		} );
	} );

	/**
	 * Header Middle Bar text color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_text_color', function( value ) {
		value.bind( function( to ) {
			$( '#top-bar-primary' ).css( 'color', to );
		} );
	} );

	/**
	 * Header text color.
	 *
	 * @since 4.1.0
	 */
	wp.customize( 'middle_header_menu_color', function( value ) {
		value.bind( function( to ) {
			$( '#top-bar-primary li.menu-item a' ).css( 'color', to );
		} );
	} );

	/**
	 * Header bottom background color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_bottom_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.header #top-bar-secondary' ).css( 'background-color', to );
		} );
	} );

	/**
	 * Header text color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'bottom_header_menu_color', function( value ) {
		value.bind( function( to ) {
			$( '.header #top-bar-secondary #menu-header > .menu-item > a' ).css( 'color', to );
		} );
	} );

	/**
	 * Sticky Header background color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'sticky_header_background_color', function( value ) {
		value.bind( function( to ) {
			if ( !to ) {
				sticky_header_background_style.html( "" );
			} else {
				sticky_header_background_style.html( "\
					.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary, \n\
					.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary, \n\
					.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar {\n\
						background-color: " + to + ";\n\
					}\n\
					.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary {\n\
						border-bottom-color: " + to + ";\n\
					}\n\
					" );
			}
		} );
	} );

	/**
	 * Sticky Header text color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'sticky_header_text_color', function( value ) {
		value.bind( function( to ) {
			if ( !to ) {
				sticky_header_color_style.html( "" );
			} else {
				sticky_header_color_style.html( "\
					.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar nav > ul > li > a, \n\
					.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary, \n\
					.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary li.menu-item a, \n\
					.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary #menu-header > .menu-item > a {\n\
						color: " + to + ";\n\
					}\n\
					" );
			}
		} );
	} );

	/**
	 * Global text color.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'global_text_color', function( value ) {
		value.bind( function( to ) {
			$( 'body' ).css( 'color', to );
		} );
	} );

	/**
	 * Footer color.
	 *
	 * @since 4.0.0
	 */
	wp.customize( 'footer_background_color', function( value ) {
		value.bind( function( to ) {
			$( '.site-footer' ).css( 'background-color', to );
		} );
	} );


	/**
	 * Change global font size.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'global_font_size', function( control ) {
		control.bind( function( controlValue ) {
			$( 'html' ).css( 'font-size', controlValue + 'rem' );
		} );
	} );

	/**
	 * Expand header full width.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_full_width', function( control ) {
		control.bind( function( controlValue ) {
			$( '#first-top-bar > .row' ).toggleClass( 'expanded', controlValue );
			$( '#top-bar-primary > .row' ).toggleClass( 'expanded', controlValue );
		} );
	} );

	/**
	 * Expand header height.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_height', function( control ) {
		control.bind( function( controlValue ) {
			$( '.header #top-bar-primary' ).css( 'padding-top', controlValue + 'rem' );
			$( '.header #top-bar-primary' ).css( 'padding-bottom', controlValue + 'rem' );
		} );
	} );

	/**
	 * Header alignment.
	 *
	 * @since 4.2.0
	 */
	wp.customize( 'header_vertical_alignment', function( control ) {
		control.bind( function( controlValue ) {
			var value = controlValue === 'middle' ? 'center' : ( controlValue === 'top' ? 'flex-start' : 'flex-end' );
			$( '.header #top-bar-primary .primary-header-wrap' ).css( 'align-items', value );
		} );
	} );

	// Whether a header image is available.
	function hasHeaderImage() {
		var image = wp.customize( 'header_image' )();
		return '' !== image && 'remove-header' !== image;
	}

	// Whether a header video is available.
	function hasHeaderVideo() {
		var externalVideo = wp.customize( 'external_header_video' )(),
			video = wp.customize( 'header_video' )();

		return '' !== externalVideo || ( 0 !== video && '' !== video );
	}

	// Toggle a body class if a custom header exists.
	$.each( [ 'external_header_video', 'header_image', 'header_video' ], function( index, settingId ) {
		wp.customize( settingId, function( setting ) {
			setting.bind( function() {
				if ( hasHeaderImage() ) {
					$( document.body ).addClass( 'has-header-image' );
				} else {
					$( document.body ).removeClass( 'has-header-image' );
				}

				if ( !hasHeaderVideo() ) {
					$( document.body ).removeClass( 'has-header-video' );
				}
			} );
		} );
	} );

} )( jQuery );
