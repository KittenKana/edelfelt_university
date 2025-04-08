<?php
/**
 * Membership Packages Template.
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

			<h2><?php _e( 'Purchase a Membership Pack', APP_TD ); ?></h2>

			<?php appthemes_notices(); ?>

			<form name="mainform" id="mainform" class="form_membership_step" action="<?php echo appthemes_get_step_url(); ?>" method="post" enctype="multipart/form-data">
				<?php wp_nonce_field( $action ); ?>

				<div id="membership-packs" class="wrap">

					<table id="memberships" class="table-plain table-listing-plans stack">

						<?php
							if ( $packages ) {
						?>

						<tbody>

							<?php
							foreach ( $packages as $key => $package ) :

								// external plugins can modify or disable field
								$package = apply_filters( 'cp_package_field', $package, 'membership' );
								if ( ! $package ) {
									continue;
								}

								$requiredClass = '';
								if ( $package->pack_satisfies_required ) {
									$requiredClass = 'required';
								}

								?>

								<tr class="plan-option <?php echo esc_attr( $requiredClass ); ?>" id="plan-<?php esc_attr_e( $package->ID ); ?>">

									<?php if ( 1 < count( $packages ) ) { ?>
									<td class="plan-radio">
										<input type="radio" name="pack" <?php echo ( ( isset( $_POST['pack'] ) && $_POST['pack'] === $package->ID ) || 0 === $key ) ? 'checked="checked"' : ''; ?> value="<?php esc_attr_e( $package->ID ); ?>" id="plan-<?php esc_attr_e( $package->ID ); ?>">
									</td>
									<?php } else { ?>
										<input type="hidden" name="pack" value="<?php esc_attr_e( $package->ID ); ?>"/>
									<?php } ?>

									<td class="plan-details">

										<h4><?php echo $package->pack_name; ?></h4>


										<div class="plan-description">
											<p><?php echo $package->description; ?></p>
										</div>

										<div class="plan-categories subheader">
											<p><?php echo cp_get_membership_package_benefit_text( $package->ID ); ?></p>
										</div>

										<div class="plan-info">
											<?php do_action( 'appthemes_purchase_plan_fields', $package ); ?>
										</div>

									</td>

									<td class="plan-costs">
										<h5>
											<span class="plan-price"><?php printf( __( '%1$s / %2$s days', APP_TD ), appthemes_get_price( $package->price ), $package->duration ); ?></span>
										</h5>
									</td>

								</tr>

							<?php endforeach; ?>

						</tbody>

						<?php
							} else {
						?>

								<tr>
									<td colspan="100%"><?php _e( 'No membership packs found.', APP_TD ); ?></td>
								</tr>

						<?php
							}
						?>

					</table>

				</div><!-- end wrap for membership packs-->

				<input type="submit" name="step1" id="step1" class="button primary" value="<?php echo esc_attr_e( 'Buy Now &rsaquo;&rsaquo;', APP_TD ); ?>"/>
				<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>" />
			</form>

		</div>

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->