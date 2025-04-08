<?php
/**
 * Listing Submit Details Template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */
?>

<div class="content-wrap">

	<div class="content-inner">

		<?php cp_display_form_progress(); ?>

		<div id="step1">

			<h2><?php _e( 'Submit Your Listing', APP_TD ); ?></h2>

			<?php appthemes_notices(); ?>

			<form name="mainform" id="mainform" class="form_step app-form" action="<?php echo appthemes_get_step_url(); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( $action ); ?>

				<div class="form-field">
					<label>
						<?php _e( 'Category', APP_TD ); ?>
					</label>
					<div class="ad-static-field"><strong><?php echo $category->name; ?></strong>&nbsp;&nbsp;<small><a href="<?php echo $select_category_url; ?>"><?php _e( '(change)', APP_TD ); ?></a></small></div>
				</div>

				<?php cp_show_form( $category->term_id, $listing ); ?>

				<input type="submit" name="step1" id="step1" class="button primary m-t-2" value="<?php _e( 'Continue &rsaquo;&rsaquo;', APP_TD ); ?>" />

				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
				<input type="hidden" name="ID" value="<?php echo esc_attr( $listing->ID ); ?>" />
			</form>

		</div>

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->
