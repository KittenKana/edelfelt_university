<?php
/**
 * Template to display widget "ClassiPress - Single Listing Image Slider".
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

$images = $instance['query'];

$banner_args = array(
	'fade'           => $instance['fade'],
	'speed'          => (int) $instance['speed'],
	'autoplay'       => $instance['autoplay'],
	'autoplaySpeed'  => (int) $instance['autoplay_speed'],
	'arrows'         => false,
	'slidesToShow'   => 1,
	'slidesToScroll' => 1,
	'asNavFor'       => "#{$instance['widget_id']}-nav",
	'accessibility'  => true,
	'focusOnSelect'  => true,
);

$banner_args = wp_json_encode( apply_filters( 'cp_listing_image_banner_slider_args', $banner_args ) );

$slider_args = array(
	//'cssEase' => 'ease',
	'arrows'          => true,
	'fade'            => false,
	'speed'           => (int) $instance['speed'],
	'autoplay'        => false,
	'vertical'        => false,
	'verticalSwiping' => false,
	'slidesToShow'    => (int) $instance['show_number'],
	'slidesToScroll'  => (int) $instance['scroll_number'], // setting disabled.
	'asNavFor'        => "#{$instance['widget_id']}-banner",
	'centerMode'      => $instance['center_mode'],
	'focusOnSelect'   => true,
	'accessibility'   => true,
	'responsive'      => array(
		array(
			'breakpoint' => 400,
			'settings'   => array(
				'vertical'        => false,
				'verticalSwiping' => false,
				'slidesToShow'    => ceil( (int) $instance['show_number'] / 2 ),
				'slidesToScroll'  => ceil( (int) $instance['scroll_number'] / 2 ),
			),
		),
	),
);

$slider_args = wp_json_encode( apply_filters( 'cp_listing_image_nav_slider_args', $slider_args ) );

?>

<script>
jQuery( function( $ ) {



	$( '#<?php echo esc_js( $instance['widget_id'] ); ?> .listing-image-slider' ).each( function() {

		var prevArrow = $( this ).parent().find( '.custom-slick-prev' );
		var nextArrow = $( this ).parent().find( '.custom-slick-next' );

		$( this ).slick( {
			adaptiveHeight: false,
			respondTo: 'min',
			prevArrow: prevArrow,
			nextArrow: nextArrow,
			swipeToSlide: true,
			infinite: true,
			rtl: $( 'html' ).attr( 'dir' ) === 'rtl',
		} );
	} );

	$( '#<?php echo esc_js( $instance['widget_id'] ); ?>-banner' ).on( 'setPosition', function( slick ) {
		$( this ).height( $( this ).width() * 9 / 16 );
	} );

	$( '#<?php echo esc_js( $instance['widget_id'] ); ?>-carousel' ).slick( {
		fade: true,
		dots: true,
		rtl: $( 'html' ).attr( 'dir' ) === 'rtl'
	} );

	// Define the links to trigger the modal window.
	$( '#<?php echo esc_attr( $instance['widget_id'] ); ?>-banner .slick-slide' ).click( function( e ) {
		e.preventDefault();

		var slider = $( '#<?php echo esc_js( $instance['widget_id'] ); ?>-carousel' );

		// Open the modal window.
		$( '#<?php echo esc_js( $instance['widget_id'] ); ?>-modal' ).foundation( 'open' );

		// Re-draw position to allow first image to load.
		slider.slick( 'setPosition', 0 );

		// Go to the slide image clicked on.
		slider.slick( 'slickGoTo', parseInt( $( this ).data( 'slick-index' ) ) );
	} );

} );
</script>

<div class="row column">

	<div class="listing-image-slider-wrapper horizontal no-js">

		<div id="<?php echo esc_attr( $instance['widget_id'] ); ?>-banner" style="--aspect-ratio:1.78;" class="listing-image-slider listing-image-slider-banner" data-slick="<?php echo esc_attr( $banner_args ); ?>">

			<?php while ( $images->have_posts() ) : $images->the_post(); ?>

			<?php $title = get_the_excerpt() ? get_the_excerpt() : get_the_title() ?>

				<div title="<?php echo esc_attr( $title ); ?>" <?php echo apply_filters( 'cp_background_cover', 'slide-cover attachment-large', array(
					'size'   => 'large',
					'images' => (array) get_the_ID(),
					) ); ?>>
				</div>

			<?php endwhile; ?>

			<?php wp_reset_postdata(); ?>

		</div>

	</div>

	<?php if ( 1 < $images->post_count ) : ?>

		<div class="items-featured-wrapper listing-image-slider-wrapper horizontal no-js">

			<button type="button" class="custom-slick-prev slick-prev slick-arrow" aria-label="Previous" role="button"><?php esc_html_e( 'Previous', APP_TD ); ?></button>
			<button type="button" data-role="none" class="custom-slick-next slick-next slick-arrow" aria-label="Next" role="button"><?php esc_html_e( 'Next', APP_TD ); ?></button>

			<div id="<?php echo esc_attr( $instance['widget_id'] ); ?>-nav" class="listing-image-slider listing-image-slider-nav" data-slick="<?php echo esc_attr( $slider_args ); ?>">

				<?php while ( $images->have_posts() ) : $images->the_post(); ?>

					<?php $title = get_the_excerpt() ? get_the_excerpt() : get_the_title() ?>

					<div>
						<?php echo wp_get_attachment_image( get_the_ID(), array( 75, 75 ), '', array( 'class' => 'attachment-medium', 'title' => $title ) ); ?>
					</div>

				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>

			</div>

		</div>

	<?php endif; ?>

</div> <!-- .row -->

<div class="full reveal listing-photos-modal" id="<?php echo esc_attr( $instance['widget_id'] ); ?>-modal" data-reveal>
	<strong class="listing-carousel-header text-center h3"><?php echo $post->post_title; ?></strong>
	<div class="row columns listing-photos-modal-content">
		<div id="<?php echo esc_attr( $instance['widget_id'] ); ?>-carousel">
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
