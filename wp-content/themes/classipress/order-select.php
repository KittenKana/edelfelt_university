<?php
/**
 * Order Gateway template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.3
 */
?>

<div class="content-wrap">

	<div class="content-inner">

		<?php cp_display_form_progress(); ?>

		<div class="post">

			<h2><?php _e( 'Order Summary', APP_TD ); ?></h2>

			<?php appthemes_notices(); ?>

			<div class="order-summary p-b-2">

				<?php the_order_summary(); ?>

			</div> <!-- .order-summary -->

			<form action="<?php echo appthemes_get_step_url(); ?>" method="POST">
				<div class="row">

					<div class="payment-method-wrap medium-6 medium-offset-6 columns">

						<h4><?php _e( 'Payment Method', APP_TD ); ?></h4>

						<?php appthemes_list_gateway_dropdown(); ?>

					</div> <!-- .payment-method-wrap -->

				</div> <!-- .row -->

				<button class="button primary" type="submit"><?php _e( 'Continue &rsaquo;&rsaquo;', APP_TD ); ?></button>

			</form>

		</div><!--/post-->

	</div> <!-- .content-inner -->

</div> <!-- .content-wrap -->
