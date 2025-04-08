/**
 * Scripts within the customizer controls window.
 *
 * @since 4.0.0
 */
( function() {
	wp.customize.bind( "ready", function() {
		/**
		 * Only show the color picker control when there's a custom color scheme.
		 *
		 * @since 4.0.0
		 */
		wp.customize( "color_scheme", function( setting ) {
			wp.customize.control( "color_scheme_custom", function( control ) {
				var visibility = function() {
					if ( "custom" === setting.get() ) {
						control.container.slideDown( 180 );
					} else {
						control.container.slideUp( 180 );
					}
				};
				visibility();
				setting.bind( visibility );
			} );
		} );
	} );
} )( jQuery );
