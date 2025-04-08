<?php
/**
 * Order Summary template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.3
 */
?>

<div class="row">

	<div id="primary" class="content-area medium-10 medium-centered columns">

		<main id="main" class="site-main" role="main">

			<div class="content-wrap">

				<div class="content-inner">

					<?php cp_display_form_progress(); ?>

					<div class="post">

						<h2 class="single dotted"><?php _e( 'Order Summary', APP_TD ); ?></h2>

						<?php appthemes_notices(); ?>

						<div class="order-summary">

							<?php the_order_summary(); ?>

						</div>

					</div><!--/post-->

				</div> <!-- .content-inner -->

			</div> <!-- .content-wrap -->

		</main>

	</div> <!-- #primary -->

</div> <!-- .row -->
