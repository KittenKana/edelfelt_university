<?php
/**
 * Membership Preview Template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */

global $current_user;
?>


<div class="content-wrap">

	<div class="content-inner">

		<?php cp_display_form_progress(); ?>

		<div id="step2">

			<h2><?php _e( 'Review Your Membership Purchase', APP_TD ); ?></h2>

			<?php appthemes_notices(); ?>

			<form name="mainform" id="mainform" class="form_step" action="<?php echo appthemes_get_step_url(); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( $action ); ?>

				<table class="table-listing-preview table-plain table-bootstrap stack">

					<tbody>

						<tr>
							<td class="labelwrapper"><strong><?php if ( $renew ) { _e( 'Membership Renewal:', APP_TD ); } else { _e( 'Membership:', APP_TD ); } ?></strong></td>
							<td class="review ad-static-field"><?php echo stripslashes( $membership->pack_name ); ?></td>
						</tr>

						<tr>
							<td class="labelwrapper"><strong><?php _e( 'Benefit:', APP_TD ); ?></strong></td>
							<td class="review ad-static-field"><?php echo cp_get_membership_package_benefit_text( $membership->ID ); ?></td>
						</tr>

						<tr>
							<td class="labelwrapper"><strong><?php _e( 'Length:', APP_TD ); ?></strong></td>
							<td class="review ad-static-field"><?php if ( $renew ) printf( __( '%s more days', APP_TD ), $membership->duration ); else printf( _n( '%d day', '%d days', $membership->duration, APP_TD ), $membership->duration ); ?></td>
						</tr>

						<?php if ( $renew ) { ?>
							<tr>
								<td class="labelwrapper"><strong><?php _e( 'Previous Expiration:', APP_TD ); ?></strong></td>
								<td class="review ad-static-field"><?php echo appthemes_display_date( $current_user->membership_expires ); ?></td>
							</tr>

							<tr>
								<td class="labelwrapper"><strong><?php _e( 'New Expiration:', APP_TD ); ?></strong></td>
								<td class="review ad-static-field">
									<?php echo appthemes_display_date( appthemes_mysql_date( $current_user->membership_expires, $membership->duration ) ); ?>
								</td>
							</tr>
						<?php } ?>

						<tr>
							<td class="labelwrapper"><strong><?php _e( 'Price:', APP_TD ); ?></strong></td>
							<td class="review ad-static-field"><?php appthemes_display_price( $membership->price ); ?></td>
						</tr>

					</tbody>

					<tbody>
						<?php do_action( 'appthemes_purchase_fields', CP_PACKAGE_MEMBERSHIP_PTYPE ); ?>
					</tbody>

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
					<input type="submit" name="step2" id="step2" class="button primary" value="<?php echo esc_attr_e( 'Continue &rsaquo;&rsaquo;', APP_TD ); ?>" />
				</p>

				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
			</form>

		</div>

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->
