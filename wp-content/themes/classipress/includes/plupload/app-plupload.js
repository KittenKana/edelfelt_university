/*!
 * jQuery appAttachment Plugin for plupload feature.
 *
 * Use class 'app-plupload' to apply plugin automatically.
 *
 * @version 4.0.0
 * @author  AppThemes
 */

( function( $ ) {

	$.fn.appAttachment = function( options ) {

		return this.each( function() {
			var form     = $( this );
			var htmlform = $( this ).find( '.app-attachment-html-upload-form' );
			var filelist = $( this ).find( '.app-attachment-upload-filelist' );
			var file_ul  = $( this ).find( '.app-attachment-list' );
			var button   = $( this ).find( '.app-attachment-upload-pickfiles' );
			var settings = $.extend( {
				maxFiles: parseInt( form.data().allowed_files, 10 ),
				init: function() {
					if ( ! form.data( 'appfilecount' ) ) {
						form.data( 'appfilecount', 0 );
					}
					filelist.on( 'click', 'a.attachment-delete', this.removeAttachment );

					$( '.upload-flash-bypass' ).on( 'click', 'a', this.hideFlashUploader );
					$( '.upload-html-bypass' ).on( 'click', 'a', this.hideHtmlUploader );

					htmlform.on( 'click', '.clear-file', function() {
						$( this ).closest( '.fileupload_wrap' ).html( $( this ).closest( '.fileupload_wrap' ).html() );
						return false;
					} );

					this.attachUploader();
					this.hideUploadBtn();
					this.hideHtmlUploader();
				},
				hideFlashUploader: function() {
					button.hide();
					$( 'p.upload-flash-bypass' ).hide();
					htmlform.show();
					$( 'p.upload-html-bypass' ).show();
					settings.hideHtmlFields();
					return false;
				},
				hideHtmlUploader: function() {
					htmlform.hide();
					$( 'p.upload-html-bypass' ).hide();
					$( 'p.upload-flash-bypass' ).show();
					settings.showUploadBtn();
					return false;
				},
				hideHtmlFields: function() {
					if ( settings.maxFiles === 0 ) {
						return;
					}

					htmlform.find( 'li' ).each( function( index ) {
						if ( ( index + 1 ) > ( settings.maxFiles - form.data( 'appfilecount' ) ) ) {
							$( this ).hide();
						} else {
							$( this ).show();
						}
					} );
				},
				hideUploadBtn: function() {
					if ( settings.maxFiles !== 0 && form.data( 'appfilecount' ) >= settings.maxFiles ) {
						button.hide();
					}
				},
				showUploadBtn: function() {
					if ( htmlform.is( ':visible' ) ) {
						return;
					}

					if ( settings.maxFiles !== 0 && form.data( 'appfilecount' ) < settings.maxFiles ) {
						button.show();
					}
				},
				attachUploader: function() {
					if ( typeof plupload === 'undefined' ) {
						return;
					}

					var min_img_dimensions = function( min_size, file, cb, dimension, obj ) {
						var self = obj, img = new o.Image();

						function finalize( result ) {
							// cleanup
							img.destroy();
							img = null;

							// if rule has been violated in one way or another, trigger an error
							if ( ! result ) {
								self.trigger( 'Error', {
									code: -888,
									file: file
								} );

							}
							cb( result );
						}

						img.onload = function() {
							// check if size cap is not exceeded
							finalize( img[dimension] > min_size );
						};

						img.onerror = function() {
							finalize( false );
						};

						img.load( file.getSource() );

					};

					plupload.addFileFilter( 'min_file_width', function( min_size, file, cb ) { min_img_dimensions( min_size, file, cb, 'width', this ); } );
					plupload.addFileFilter( 'min_file_height', function( min_size, file, cb ) { min_img_dimensions( min_size, file, cb, 'height', this ); } );

					var plupload_args = $.extend( {
						browse_button: button.get(0),
						container: form.get(0)
					}, AppPluploadConfig.plupload );

					var attachUploader = new plupload.Uploader( plupload_args );

					button.click( function( e ) {
						attachUploader.refresh();
						attachUploader.start();
						e.preventDefault();
					} );

					attachUploader.init();

					attachUploader.bind( 'FilesAdded', function( up, files ) {
						$.each( files, function( i, file ) {
							filelist.append(
									'<div id="' + file.id + '" class="app-attachment-upload-progress">' +
									file.name + ' (' + plupload.formatSize( file.size ) + ') <b></b>' +
									'</div>' );

							form.data().appfilecount++;
							settings.hideUploadBtn();
						} );

						up.refresh();
						attachUploader.start();
					} );

					attachUploader.bind( 'UploadProgress', function( up, file ) {
						$( '#' + file.id + " b" ).html( file.percent + "%" );
					} );

					attachUploader.bind( 'Error', function( up, err ) {
						var error = $( '<div class="error">' + settings.errorMessage( up, err ) + '</div>' );
						filelist.append( error );
						error.delay( 5000 ).fadeOut( 'slow' );

						up.refresh();
					} );

					attachUploader.bind( 'FileUploaded', function( up, file, response ) {
						var resp = $.parseJSON( response.response );
						if ( resp.success ) {
							file_ul.append( resp.html );
						} else {
							form.data().appfilecount--;
							settings.showUploadBtn();
						}
						$( '#' + file.id ).remove();

						up.refresh();
					} );
					attachUploader.bind( 'StateChanged', function() {
						if ( attachUploader.files.length === ( attachUploader.total.uploaded + attachUploader.total.failed ) ) {
							$( 'input[type="submit"]' ).prop( 'disabled', false );
						} else {
							$( 'input[type="submit"]' ).prop( 'disabled', true );
						}
					} );
				},
				removeAttachment: function( e ) {
					e.preventDefault();

					if ( confirm( AppPluploadConfig.confirmMsg ) ) {
						var el = $( this ),
								data = {
									'attach_id': el.data( 'attach_id' ),
									'nonce': AppPluploadConfig.nonce,
									'action': 'app_plupload_handle_delete'
								};

						$.post( AppPluploadConfig.ajaxurl, data, function() {
							el.closest( '.app-attachment' ).remove();

							form.data().appfilecount--;
							settings.showUploadBtn();
						} );
					}
				},
				errorMessage: function( up, err ) {
					var codes = {
						'-100': pluploadL10n.default_error,
						'-200': pluploadL10n.http_error,
						'-300': pluploadL10n.io_error,
						'-400': pluploadL10n.security_error,
						'-500': pluploadL10n.default_error,
						'-600': pluploadL10n.file_exceeds_size_limit.replace( '%s', err.file.name ),
						'-601': pluploadL10n.invalid_filetype,
						'-602': pluploadL10n.default_error,
						'-700': pluploadL10n.not_an_image,
						'-701': pluploadL10n.image_memory_exceeded,
						'-702': pluploadL10n.image_dimensions_exceeded,
						'-888': AppPluploadConfig.dimErMsg
					};
					if ( err.code in codes )
						return codes[ err.code ];
					else
						return pluploadL10n.default_error;
				}
			}, options );

			settings.init();

		} );

	};

}( jQuery ) );

jQuery( document ).ready( function( $ ) {
	$( '.app-plupload' ).appAttachment();
} );
