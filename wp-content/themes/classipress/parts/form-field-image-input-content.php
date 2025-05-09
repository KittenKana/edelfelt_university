<?php
/**
 * Plupload image input field template.
 *
 * @global stdClass $app_field_array
 * @global array    $app_plupload_attachments
 * @global array    $app_plupload_options
 *
 * @package ClassiPress\Templates
 */

$req = ( ! empty( $app_field_array->field_req ) ) ? 'app_images_upload' : '';
?>

<div class="app-plupload" data-appfilecount="<?php echo count( $app_plupload_attachments ); ?>" data-allowed_files="<?php echo esc_attr( $app_plupload_options['allowed_files'] ); ?>">

	<input type="hidden" name="app_images_upload_validator" class="<?php echo esc_attr( $req ); ?>" />

	<div class="app-attachment-upload-filelist">
		<ul class="app-attachment-list">
			<?php
			if ( $app_plupload_attachments ) {
				foreach ( $app_plupload_attachments as $attachment ) {
					echo appthemes_plupload_attach_html( $attachment->ID );
				}
			}
			?>
		</ul>
	</div>
	<div class="app-attachment-html-upload-form">
		<ul class="app-attachment-html-upload-fields">
			<?php
			$fields_count = $app_plupload_options['allowed_files'] - count( $app_plupload_attachments );
			if ( $fields_count > 0 ) {
				foreach ( range( 1, $fields_count ) as $i ) {
				?>
					<li class="fileupload_wrap">
						<div class="input-group">
							<input class="fileupload input-group-field" type="file" name="image[]">
							<div class="input-group-button">
								<input type="button" class="clear-file button hollow" value="<?php esc_attr_e( 'Clear', APP_TD ); ?>">
							</div>
						</div>
					</li>
				<?php
				}
			}
			?>
		</ul>
	</div>
	<div class="app-attachment-info">
		<a class="button hollow app-attachment-upload-pickfiles" href="#"><?php esc_html_e( 'Add Image', APP_TD ); ?></a>
	</div>
</div>

<p class="help-text"><?php esc_html_e( 'Drag and drop images to change their order.', APP_TD ); ?></p>
