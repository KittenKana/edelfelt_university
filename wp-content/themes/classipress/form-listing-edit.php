<?php
/**
 * Listing Edit Details Template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */
?>
<?php appthemes_notices(); ?>

<div class="section-head">
	<?php if ( $step = appthemes_get_checkout() ) { ?>
		<h1><?php _e( 'Edit Your Ad', APP_TD ); ?></h1>
	<?php }  ?>
</div>

<p><?php _e( 'Edit the fields below and click save to update your ad. Your changes will be updated instantly on the site.', APP_TD ); ?></p>

<form name="mainform" id="mainform" class="app-form form_edit" action="<?php echo appthemes_get_step_url(); ?>" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field( $action ); ?>

	<fieldset class="fieldset">

	<?php
		if ( $form_fields ) {
			cp_formbuilder( $form_fields, $listing );
		} else {
			cp_show_default_form( $listing );
		}

		// check and make sure images are allowed
		if ( $cp_options->ad_images ) {

			if ( appthemes_plupload_is_enabled() ) {
				appthemes_plupload_form( $listing->ID );
			} else {
				$images_count = cp_get_ad_images( $listing->ID );
				// print out image upload fields. pass in count of images allowed
				cp_ad_edit_image_input_fields( $images_count );
			}

		} else { ?>

			<div class="form-field">
				<label>
					<?php _e( 'Images:', APP_TD ); ?>
				</label>
				<p><?php _e( 'Sorry, image editing is not supported for this ad.', APP_TD ); ?></p>
			</div>

		<?php } ?>

	</fieldset>

	<input type="button" class="button primary" onclick="window.location.href='<?php echo CP_DASHBOARD_URL; ?>'" value="<?php _e( 'Cancel', APP_TD ); ?>" />&nbsp;&nbsp;
	<input type="submit" class="button primary" value="<?php _e( 'Update Ad &raquo;', APP_TD ); ?>" name="update" />

	<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
	<input type="hidden" name="ID" value="<?php echo esc_attr( $listing->ID ); ?>" />
</form>
