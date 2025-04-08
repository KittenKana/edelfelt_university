<?php
/**
 * Template to display widget "ClassiPress - Home Latest Listings".
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.0.0
 */

$grid_cols = intval( $instance['grid_cols'] );
$medium_cols = ceil( $grid_cols / 2 );
$items = $instance['items'];
?>

<div class="row column">

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

	<div class="row listing-wrap small-up-1 medium-up-<?php echo $medium_cols; ?> large-up-<?php echo $grid_cols; ?>">
		<?php while ( $items->have_posts() ) : $items->the_post(); ?>

			<div class="column">

				<?php appthemes_before_post( 'widget_latest_listings_item' ); ?>

				<?php get_template_part( 'parts/content-item-' . get_post_type(), get_query_var( 'listing_layout' ) ); ?>

				<?php appthemes_after_post( 'widget_latest_listings_item' ); ?>

			</div> <!-- .column -->

		<?php endwhile; ?>
	</div> <!-- .row -->

</div> <!-- .row -->