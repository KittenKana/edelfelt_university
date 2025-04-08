<?php
/**
 * Plupload image input attachment template.
 *
 * @global WP_Post $app_plupload_attachment
 *
 * @package ClassiPress\Templates
 */
$parent = get_post( $app_plupload_attachment->post_parent );
?>
<li class="app-attachment">
	<div class="media-object stack-for-small">
		<div class="media-object-section">
			<div class="thumbnail attachment-image">
				<?php echo wp_get_attachment_image( $app_plupload_attachment->ID, array( 100, 100 ), false ); ?>
			</div>
		</div>
		<div class="media-object-section main-section">
			<div class="input-group">
				<input type="text" name="app_attach_title[]" class="input-group-field" value="<?php echo esc_attr( $app_plupload_attachment->post_title ) ?>" placeholder="<?php esc_attr_e( 'Change Title', APP_TD ) ?>" class="text" />
				<div class="input-group-button button-group">
					<a href="#" class="attachment-delete button hollow" data-attach_id="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>"><?php esc_html_e( 'Delete', APP_TD ); ?></a>
				</div>
			</div>
			<div class="row">
				<?php if ( apply_filters( 'cp_allow_listing_banner_image', true ) ) { ?>
				<div class="m-large-6 column">
					<a class="expanded success button<?php echo checked( $app_plupload_attachment->ID, $parent->_cp_banner_image, false ) ? '' : ' hollow' ?>" data-attachment-button="_cp_banner_image"><?php esc_html_e( 'Set as a Banner Image', APP_TD ); ?></a>
					<input type="radio" name="_cp_banner_image" style="display:none;" value="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>" <?php checked( $app_plupload_attachment->ID, $parent->_cp_banner_image ) ?>/>
				</div>
				<?php } ?>
				<div class="m-large-6 column">
					<a class="expanded warning button<?php echo checked( $app_plupload_attachment->ID, $parent->_thumbnail_id, false ) ? '' : ' hollow' ?>" data-attachment-button="_thumbnail_id"><?php esc_html_e( 'Set as a Thumbnail Image', APP_TD ); ?></a>
					<input type="radio" name="_thumbnail_id" style="display:none;" value="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>" <?php checked( $app_plupload_attachment->ID, $parent->_thumbnail_id ) ?>/>
				</div>
			</div>
			<input type="hidden" name="app_attach_id[]" value="<?php echo esc_attr( $app_plupload_attachment->ID ); ?>" />
		</div>
	</div>
</li>
