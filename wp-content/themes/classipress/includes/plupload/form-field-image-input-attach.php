<?php
/**
 * Plupload image input attachment template.
 *
 * @global WP_Post $app_plupload_attachment
 *
 * @package ClassiPress\Plupload
 */

?>
<li class="app-attachment">
	<div class="media-object stack-for-small">
		<div class="media-object-section">
			<div class="thumbnail attachment-image">
				<?php echo wp_get_attachment_image( $app_plupload_attachment->ID, 'thumbnail', false ); ?>
			</div>
		</div>
		<div class="media-object-section main-section">
			<div class="input-group">
				<input type="text" name="app_attach_title[]" class="input-group-field" value="<?php echo esc_attr( $app_plupload_attachment->post_title ) ?>" placeholder="<?php esc_attr_e( 'Change Title', APP_TD ) ?>" class="text" />
				<div class="input-group-button">
					<a href="#" class="attachment-delete button hollow" data-attach_id="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>"><?php esc_html_e( 'Delete', APP_TD ); ?></a>
				</div>
			</div>
			<input type="hidden" name="app_attach_id[]" value="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>" />
		</div>
	</div>
</li>
