/**
 * Admin jQuery functions
 * Written by AppThemes
 *
 * https://www.appthemes.com
 *
 * Built for use with the jQuery library
 *
 *
 */


jQuery( document ).ready( function( $ ) {

	/* admin option pages tabs */
	$( "div#tabs-wrap" ).tabs( {
		fx: {
			opacity: 'toggle',
			duration: 200
		},
		show: function() {
			$( 'div#tabs-wrap' ).tabs( 'option', 'selected' );
		}
	} );

	/* strip out all the auto classes since they create a conflict with the calendar */
	$( '#tabs-wrap' ).removeClass( 'ui-tabs ui-widget ui-widget-content ui-corner-all' );
	$( 'ul.ui-tabs-nav' ).removeClass( 'ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all' );
	$( 'div#tabs-wrap div' ).removeClass( 'ui-tabs-panel ui-widget-content ui-corner-bottom' );

	/* clear text field, hide image preview */
	$( ".delete_button" ).click( function( el ) {
		var id = $( this ).attr( "rel" );
		$( "#" + id ).val( "" );
		$( "#" + id + "_image img" ).hide();
	} );

	/* check all categories button */
	$( "#form-categorydiv .checkall" ).on( 'change', function() {
		var checkedStatus = this.checked;
		$( "#categorychecklist input:checkbox" ).prop( "checked", checkedStatus );
		$( this )
			.closest( 'label' )
			.find( '.checkall-label' )
			.html( checkedStatus ? classipress_admin_params.text_uncheck_all : classipress_admin_params.text_check_all );
		return false;
	} );


} );


/* Used for deleting theme database tables */
function cp_confirmBeforeDeleteTables() {
	return confirm( classipress_admin_params.text_before_delete_tables );
}


/* Used for deleting theme options */
function cp_confirmBeforeDeleteOptions() {
	return confirm( classipress_admin_params.text_before_delete_options );
}

/**
 * General widget image upload button logic.
 *
 * @since 4.2.0
 */
window.themeImageWidget = window.themeImageWidget || {},
	function( a, b, c ) {
		themeImageWidget.MediaManager = function( a ) {
			this.options = a, this.media = this, this.target = a.target, this.$target = b( '#' + this.options.target ), this.$trigger = b( '.' + this.options.target + '-add' ), this.setFrame(), this.bindEvents();
		}, themeImageWidget.MediaManager.prototype.bindEvents = function() {
			var a = this;
			b( document ).on( 'widget-added widget-updated', function( c, d ) {
				var e = d.find( 'input.multi_number' ).val();
				a.target = a.options.target.replace( '__i__', e ), a.$target = b( '#' + a.target ), a.$trigger = b( '.' + a.target + '-add' ), a.$trigger.on( 'click', function( b ) {
					b.preventDefault(), a.bindFrame();
				} );
			} ), this.$trigger.on( 'click', function( b ) {
				b.preventDefault(), a.bindFrame();
			} );
		}, themeImageWidget.MediaManager.prototype.bindFrame = function() {
			var a = this;
			this.frame.open(), this.frame.on( 'select', function() {
				a.media.attachItem();
			} );
		}, themeImageWidget.MediaManager.prototype.setFrame = function() {
			this.frame = c.media.frames._frame = c.media( {
				title: 'Choose an Image',
				button: {
					text: 'Use Image'
				},
				multiple: !1
			} );
		}, themeImageWidget.MediaManager.prototype.attachItem = function() {
			var b = this.frame.state().get( 'selection' ).first().toJSON();
			this.$target.val( b.sizes.full.url );
		};
	}( this, jQuery, wp );

jQuery( function( $ ) {
	$( document ).on( 'widget-added', function( c, d ) {
		d.find( 'input[type="colorpicker"]' )
			.wpColorPicker( {
				change: function( e, ui ) {
					$( e.target ).val( ui.color.toString() );
					$( e.target ).trigger( 'change' );
				}
			} )
			.closest( '.wp-picker-container' )
			.css( 'display', 'block' );
	} );
} );
