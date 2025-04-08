/* Kick-off Foundation */
jQuery( function( $ ) {
	$( document ).foundation();

	/* Enable Tabs deep linking for the Foundation 6.2.4 */
	$( '[data-tabs]' ).each( function() {
		var $elem = $( this );

		//use browser to open a tab, if it exists in this tabset
		if ( $elem.data( 'deepLink' ) ) {
			var anchor = window.location.hash;
			//need a hash and a relevant anchor in this tabset
			if ( anchor.length && $elem.find( '[href="' + anchor + '"]' ).length ) {

				$elem.foundation( 'selectTab', anchor );

				//roll up a little to show the titles
				if ( $elem.data( 'deepLinkSmudge' ) ) {
					$( window ).load( function() {
						var offset = $elem.offset();
						$( 'html, body' ).animate( {
							scrollTop: offset.top
						}, $elem.data( 'deepLinkSmudgeDelay' ) );
					} );
				}
			}
		}
	} );

} );
