<?php
/**
 * Featured ads slider template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.0
 * @since   4.0.0 Migrated layout to Foundation framework.
 */

$items = apply_filters( 'cp_featured_slider_ads', $instance['items'] );

$slider_args = apply_filters( 'cp_featured_slick_slider_args', array(
	//'cssEase' => 'ease',
	'speed' => (int) $instance['speed'],
	'fade' => $instance['fade'],
	'autoplay' => $instance['autoplay'],
	'autoplaySpeed' => (int) $instance['autoplay_speed'],
	'slidesToShow' => (int) $instance['show_number'],
	'slidesToScroll' => (int) $instance['scroll_number'],
	'respondTo' => 'min',
	'responsive' => array(
		array(
			'breakpoint' => 1024,
			'settings' => array(
				'slidesToShow'   => (int) $instance['show_number'],
				'slidesToScroll' => (int) $instance['scroll_number'],
			),
		),
		array(
			'breakpoint' => 768,
			'settings' => array(
				'slidesToShow'   => ceil( (int) $instance['show_number'] * 2 / 3 ),
				'slidesToScroll' => ceil( (int) $instance['scroll_number'] * 2 / 3 ),
			),
		),
		array(
			'breakpoint' => 640,
			'settings' => array(
				'slidesToShow'   => ceil( (int) $instance['show_number'] / 2 ),
				'slidesToScroll' => ceil( (int) $instance['scroll_number'] / 2 ),
			),
		),
		array(
			'breakpoint' => 480,
			'settings' => array(
				'slidesToShow'   => ceil( (int) $instance['show_number'] / 3 ),
				'slidesToScroll' => ceil( (int) $instance['scroll_number'] / 3 ),
			),
		),
		array(
			'breakpoint' => 360,
			'settings' => array(
				'slidesToShow'   => ceil( (int) $instance['show_number'] / 4 ),
				'slidesToScroll' => ceil( (int) $instance['scroll_number'] / 4 ),
			),
		),
	),
) );

$slider_args = json_encode( $slider_args );

?>

<div class="row column featured-listings-slider">

	<div class="home-widget-head-wrap">
	<?php

	if ( ! empty( $instance['header'] ) ) {
		echo $instance['before_title'] . $instance['header'] . $instance['after_title'];
	}

	if ( ! empty( $instance['description'] ) ) {
		echo '<p class="home-widget-description">' . $instance['description'] . '</p>';
	}

	?>
	</div> <!-- .home-widget-head-wrap -->

	<?php appthemes_before_loop( 'featured' ); ?>

	<script>
	jQuery( function( $ ) {
		$('.featured-listings-slider .items-featured').each( function() {

			var prevArrow = $( this ).parent().find( '.custom-slick-prev' );
			var nextArrow = $( this ).parent().find( '.custom-slick-next' );

			$( this ).slick( {
				rtl: $('html').attr('dir') === 'rtl',
				prevArrow: prevArrow,
				nextArrow: nextArrow,
				adaptiveHeight: false
			} )
			.fadeTo( 'slow' , 1 );
		} );
	} );
	</script>

	<div class="items-featured-wrapper">

		<button type="button" class="custom-slick-prev slick-prev slick-arrow" aria-label="Previous" role="button"><?php esc_html_e( 'Previous', APP_TD ); ?></button>
		<button type="button" data-role="none" class="custom-slick-next slick-next slick-arrow" aria-label="Next" role="button"><?php esc_html_e( 'Next', APP_TD ); ?></button>

		<div class="items-featured content-main" style="opacity: 0;" data-slick="<?php echo esc_attr( $slider_args ); ?>">

			<?php
			while ( $items->have_posts() ) : $items->the_post(); ?>

				<?php appthemes_before_post( 'featured' ); ?>

				<div class="item-single-featured">

					<?php appthemes_get_template_part( 'parts/content-item-featured', $instance['layout'] ); ?>

				</div> <!-- .item-single-featured -->


				<?php appthemes_after_post( 'featured' ); ?>

			<?php endwhile; ?>

			<?php appthemes_after_endwhile( 'featured' ); ?>

		</div> <!-- .items-featured -->

	</div>

</div> <!-- .row -->

<div class="row column">
	<?php
	if ( $instance['show_more'] ) {
		$featured_url = add_query_arg( array(
			'sort' => 'featured',
			's'    => '',
			'st'   => APP_POST_TYPE,
		), get_post_type_archive_link( APP_POST_TYPE ) );
		echo '<p class="view-more-featured-listings m-a-3 text-center"><a href="' . esc_url( $featured_url ) . '" class="hollow button">' . esc_html__( 'View More Featured Ads', APP_TD ) . '</a></p>';
	}
	?>
</div> <!-- .row -->

<?php appthemes_after_loop( 'featured' );
