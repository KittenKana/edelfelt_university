<?php
/**
 * Template to display widget "ClassiPress - Single Listing Photo Gallery".
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

$images = $instance['query'];
$post  = get_post();

/**
 * Fires before the single listing photo gallery widget.
 *
 * @since 4.0.0
 */
do_action( 'cp_widget_listing_gallery_before' );

?>
<table class="listing-photo-grid">
	<tr>
		<?php
		// Grab the first image from the object for the main block.
		$image_large  = wp_get_attachment_image_src( $images->posts[0]->ID, 'large' );
		$image_alt    = get_post_meta( $images->posts[0]->ID, '_wp_attachment_image_alt', true );
		?>
		<td class="listing-photo-grid-main">
			<a data-index="0"><img src="<?php echo esc_url( $image_large[0] ); ?>" class="attachment-large" alt="<?php echo esc_attr( strip_tags( $image_alt ) ); ?>" /></a>
		</td>
		<?php if ( 1 < $images->post_count ) : ?>
			<td class="listing-photo-grid-sub">
				<?php
				// Skip the first image and get the next two to create the sidebar images.
				foreach ( array_slice( $images->posts, 1, 2 ) as $key => $image ) :
					$image_medium = wp_get_attachment_image_src( $image->ID, 'medium' );
					$image_alt    = get_post_meta( $image->ID, '_wp_attachment_image_alt', true );
				?>
					<a data-index="<?php echo esc_attr( $key + 1 ); ?>"><img src="<?php echo esc_url( $image_medium[0] ); ?>" class="attachment-medium" alt="<?php echo esc_attr( strip_tags( $image_alt ) ); ?>" /></a>
				<?php endforeach; ?>
				<div class="listing-photo-grid-more">
					<a data-index="<?php echo esc_attr( $key + 2 ); ?>"><i class="fa fa-camera" aria-hidden="true"></i><br/><span><?php _e( 'All photos', APP_TD ); ?> (<?php echo number_format_i18n( $images->post_count ); ?>)</span></a>
				</div>
			</td>
		<?php endif; ?>
	</tr>
</table>

<div class="full reveal listing-photos-modal" id="listingPhotosModal" data-reveal>
	<div class="row columns listing-photos-modal-content">
		<strong class="listing-carousel-header text-center h3"><?php echo $post->post_title; ?></strong>
		<div id="listing-carousel">
			<?php
			// Load up the carousel images.
			foreach ( $images->posts as $image ) :
				$image_url = wp_get_attachment_image_src( $image->ID, 'full' );
				$alt_text  = get_post_meta( $image->ID, '_wp_attachment_image_alt', true );
			?>
			<div class="listing-carousel-photo">
				<img src="<?php echo esc_url( $image_url[0] ); ?>" class="attachment-large" alt="<?php echo esc_attr( strip_tags( $alt_text ) ); ?>" />
				<p class="slide-caption"><?php echo strip_tags( $image->post_excerpt ); ?></p>
			</div>
			<?php endforeach; ?>
		</div><!-- .listing-carousel -->
	</div><!-- .row -->
	<button class="close-button" data-close type="button">
		<span aria-hidden="true">&times;</span>
	</button>
</div><!-- .reveal -->

<?php
/**
 * Fires after the single listing photo gallery widget.
 *
 * @since 4.0.0
 */
do_action( 'cp_widget_listing_gallery_after' );