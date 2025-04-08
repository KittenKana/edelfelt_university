<?php
/**
 * Template to display widget "ClassiPress - Single Listing Author".
 *
 * @package ClassiPress\Templates
 * @since 4.0.0 Migrated template from 'includes/sidebar-contact.php'
 */

?>

<div class="listing-owner">
	<div class="listing-owner-avatar">
			<?php echo cp_get_avatar( get_the_author_meta( 'ID' ), 125, '', '', array( 'class' => 'img-circle' ) ); ?>
	</div>

	<div class="listing-owner-info text-center">
		<h3 class="listing-owner-name"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></h3>

		<span class="listing-owner-headline text-muted"><?php echo esc_html( $instance['headline'] ); ?></span>
        <!-- Commenting out Member Since information
		<span class="listing-owner-headline text-muted"><?php _e( 'Member Since:', APP_TD ); ?> <?php echo appthemes_display_date( get_the_author_meta( 'user_registered' ), 'date', true ); ?></span>
        -->

	</div>

	<?php cp_author_info( 'sidebar-ad' ); ?>

	<?php if ( $instance['biography'] && $bio = get_the_author_meta( 'description', get_the_author_meta( 'ID' ) ) ) : ?>
		<div class="listing-owner-bio">
			<?php echo $bio; ?>
		</div>
	<?php endif; ?>

	<?php if ( 'publish' === get_post_status() && 'yes' !== get_post_meta( get_the_ID(), 'cp_ad_sold', true ) ) : ?>
		<div class="listing-owner-contact text-center">
			<?php

			$display_name = '';
			$user_email   = '';

			// Load validation scripts for modal form.
			wp_enqueue_script( 'validate' );
			wp_enqueue_script( 'validate-lang' );

			// Autofill the fields if user is logged in.
			if ( $user = wp_get_current_user() ) {
				$display_name = $user->display_name;
				$user_email   = $user->user_email;
			}
			?>

			<p><a data-open="contactOwnerModal" class="button hollow expanded" id="contact-owner-link"><?php _e( 'Contact Owner', APP_TD ); ?></a></p>

			<!-- Modal window -->
			<div class="reveal" id="contactOwnerModal" data-reveal>

				<p class="lead"><?php _e( 'Contact Owner', APP_TD ); ?></p>

				<?php if ( ( $cp_options->ad_inquiry_form && is_user_logged_in() ) || ! $cp_options->ad_inquiry_form ) : ?>

					<p><?php _e( 'Complete the form below to send a message to this owner.', APP_TD ); ?></p>

					<div id="app-contact-form-response"></div>

					<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>

					<div id="app-contact-form-wrap">

						<form id="app-contact-form" class="app-contact-form" action="post">

							<?php
							/**
							 * Fires before the fields in the contact owner form.
							 *
							 * @since 4.0.0
							 */
							do_action( 'appthemes_contact_owner_form_before_fields' );
							?>

							<label><?php _e( 'Name', APP_TD ); ?>
								<input type="text" name="contact_name" id="contact_name" class="required" value="<?php echo esc_attr( $display_name ); ?>">
							</label>

							<label><?php _e( 'Email', APP_TD ); ?>
								<input type="email" name="contact_email" id="contact_email" class="required email" value="<?php echo esc_attr( $user_email ); ?>">
							</label>

							<label><?php _e( 'Message', APP_TD ); ?>
								<textarea name="contact_message" id="contact_message" rows="5" class="required"></textarea>
							</label>

							<!-- We hide this with CSS (spam trap), that's why it has an ID. -->
							<label id="contact_catcher">Username
								<input type="text" name="contact_username" id="contact_username" autocomplete="off">
							</label>

							<?php cp_maybe_display_recaptcha(); ?>

							<?php
							/**
							 * Fires after the fields in the contact owner form.
							 *
							 * @since 4.0.0
							 */
							do_action( 'appthemes_contact_owner_form_after_fields' );
							?>

							<input type="submit" id="app-contact-submit" class="button expanded" value="<?php esc_attr_e( 'Send Inquiry', APP_TD ); ?>">

							<?php wp_nonce_field( 'appthemes_contact_owner_nonce' ); ?>
							<input type="hidden" name="post_ID" id="post_ID" value="<?php echo get_the_ID(); ?>">

						</form>

					</div>

				<?php else: ?>

					<p><strong><?php _e( 'You must be logged in to inquire about this ad.', APP_TD ); ?></strong></p>

				<?php endif; ?>

				<button class="close-button" data-close aria-label="<?php echo esc_attr_e( 'Close modal window', APP_TD ); ?>" type="button">
					<span aria-hidden="true">&times;</span>
				</button>

			</div>
			<!-- Modal window end -->

		</div><!-- .listing-owner-contact -->
	<?php endif; ?>

	<?php
	/**
	 * Fires after the listing author widget content.
	 *
	 * @since 4.0.0
	 */
	do_action( 'cp_widget_listing_author_after' );
	?>
</div>