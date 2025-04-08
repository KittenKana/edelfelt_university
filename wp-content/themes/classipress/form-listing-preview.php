<?php
/**
 * Listing Submit Preview Template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */
?>

<div class="content-wrap">

	<div class="content-inner">

		<?php cp_display_form_progress(); ?>

		<div id="step2">

			<h2><?php _e( 'Review Your Listing', APP_TD ); ?></h2>

			<?php appthemes_notices(); ?>

			<form name="mainform" id="mainform" class="form_step steps-review" action="<?php echo appthemes_get_step_url(); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( $action ); ?>

				<table class="table-listing-preview table-plain table-bootstrap stack">

					<?php
						// pass in the form post array and show the ad summary based on the formid
						echo cp_show_review( $posted_fields );
					?>

				</table>

				<div class="license callout primary"><?php cp_display_message( 'terms_of_use' ); ?></div>

				<p class="terms">
					<small class="subheader">
						<?php _e( 'By clicking the proceed button below, you agree to our terms and conditions.', APP_TD ); ?>
						<br />
						<?php _e( 'Your IP address has been logged for security purposes:', APP_TD ); ?> <?php echo appthemes_get_ip(); ?>
					</small>
				</p>

				<p class="btn2">
					<input type="button" name="goback" class="button primary" value="<?php _e( 'Go back', APP_TD ); ?>" onClick="location.href='<?php echo appthemes_get_step_url( appthemes_get_previous_step() ); ?>';return false;" />
					<input type="submit" name="step2" id="step2" class="button primary" value="<?php _e( 'Continue &rsaquo;&rsaquo;', APP_TD ); ?>" />
				</p>

				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
				<input type="hidden" name="ID" value="<?php echo esc_attr( $listing->ID ); ?>" />
			</form>

		</div>

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->
