<?php
/**
 * Listing Submit Category Select Template.
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

			<form name="mainform" id="mainform" class="form_step" action="<?php echo appthemes_get_step_url(); ?>" method="post">
				<?php wp_nonce_field( $action ); ?>

				<table class="form-fields cat-select table-plain table-bootstrap stack m-t-3">
					<tbody>
						<tr>
							<td>
								<div class="labelwrapper"><label><?php _e( 'Cost Per Listing', APP_TD ); ?></label></div>
							</td>
							<td>
								<div class="ad-static-field"><?php cp_cost_per_listing(); ?></div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="labelwrapper"><label><?php _e( 'Select a Category', APP_TD ); ?></label></div>
							</td>
							<td>
								<div id="ad-categories">
									<div id="catlvl0">
										<?php cp_dropdown_categories_prices(); ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="100%">
								<div id="ad-categories-footer" class="button-container">
									<input type="submit" name="getcat" id="getcat" class="button primary m-t-2" value="<?php _e( 'Go &rsaquo;&rsaquo;', APP_TD ); ?>" style="display: none;" />
									<div id="chosenCategory"><input id="ad_cat_id" name="cat" type="hidden" value="-1" /></div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
				<input type="hidden" name="ID" value="<?php echo esc_attr( $listing->ID ); ?>" />
			</form>

		</div>

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->
