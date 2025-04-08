/*!
 * jQuery Progress Plugin to perform the steps progress using ajax.
 *
 * Use class 'app-progress-form' to apply plugin automatically.
 *
 * @version 1.0.0
 * @author  AppThemes
 */

(function( $ ) {

	/**
	 * Handle Favorite buttons
	 *
	 * @param {object} options Plugin settings
	 */
	$.fn.appAjaxProgress = function ( options ) {

		this.each( function() {

			var form     = $( this );
			var settings = $.extend( {

				action     : form.data( 'action' ),
				rebind     : function( element ) {
					element.off( 'click.appThemesActionButton', '.app-progress-button' )
						.on( 'click.appThemesActionButton', '.app-progress-button', this.clickHandler )
						.on( 'click.appThemesActionButton', '.app-progress-button', function() { $( this ).hide(); } );
				},

				getPostData : function( element ) {
					var postdata = {
						action          : settings.action,
						_progress_nonce : form.find( '#_progress_nonce' ).val()
					};
					return postdata;
				},

				ajaxSuccess : function( data, element ){
					form.data( 'current-step', data.step_id );

					if ( data.html ) {
						form.find( '#app_progress_step_' + data.step_id ).html( data.html );
						element.click();
					} else {
						form.data( 'current-step', null );
						element.after( '<strong>' + appAjaxProgress.done + '</strong>' );
						this.spinnerProcessing( element );
					}
				},

				spinnerProcessing : function( element ) {
					form.find( '.app-progress-step-spinner' ).each( function() {
						if ( $( this ).attr( 'id' ) === 'app-progress-step-' + form.data( 'current-step' ) + '-spinner' ) {
							$( this ).addClass( 'is-active' );
						} else {
							$( this ).removeClass( 'is-active' );
						}
					} );
				}

			}, options );

			$( this ).appThemesActionButton( settings );
		} );
	};
}( jQuery ));

jQuery( document ).ready( function( $ ) {
	$( '.app-progress-form' ).appAjaxProgress();
} );
