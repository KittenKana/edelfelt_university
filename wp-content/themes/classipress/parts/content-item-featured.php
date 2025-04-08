<?php
/**
 * The listing slider item template.
 *
 * Common layout for all types.
 * Can be extended by appending type to the file name.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

$layout = $instance['layout'];
$columns = 'card' === $layout ? 12 : 6;
// height 10 controlled by CSS.
$thumb_height = 10 === (int) $instance['height'] || empty( $instance['height'] ) ? 0 : $instance['height'];
?>

<article id="featured-post-<?php the_ID(); ?>" class="item-single-featured content-wrap display-featured-<?php echo esc_attr( $layout ); ?>">

	<div class="row">

		<?php if ( $instance['height'] > 0 ) { ?>

			<div class="medium-<?php echo esc_attr( $columns ); ?> columns">

				<a class="entry-thumbnail" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Thumbnail image for %s', APP_TD ), get_the_title() ) ); ?>" aria-hidden="true">
					<div <?php echo apply_filters( 'cp_background_cover', 'featured-cover entry-cover', array( 'size' => 'large', 'height' => $thumb_height ) ); ?>>
						<span class="screen-reader-text"><?php the_title(); ?></span>
					</div>
				</a>

			</div> <!-- .columns -->

		<?php } ?>

		<div class="medium-<?php echo esc_attr( $columns ); ?> columns content-inner">

			<header class="entry-header">

				<?php
				appthemes_before_post_title( 'featured' );

				$title_class = 'entry-title';
				$show_number = (int) $instance['show_number'];

				if ( 1 === $show_number ) {
					$title_class .= ' h2';
				} elseif ( $show_number < 3 ) {
					$title_class .= ' h3';
				} elseif ( $show_number > 2 ) {
					$title_class .= ' h4';
				}

				the_title( sprintf( '<h3 class="%3$s"><a href="%1$s" title="%2$s" rel="bookmark">', esc_url( get_permalink() ), esc_attr( get_the_title() ), $title_class ), '</a></h3>' );

				cp_ad_loop_price( 'no-tag-price-wrap' );

				appthemes_after_post_title( 'featured' );
				?>
			</header>

			<?php if ( $instance['show_desc'] ) { ?>
				<div class="entry-content subheader">
					<?php echo cp_strimwidth( strip_tags( strip_shortcodes( wpautop( get_the_content() ) ), '<p>' ), 0, 1000, '&hellip;' ); ?>
				</div> <!-- .entry-content -->
			<?php } ?>

			<?php if ( $instance['show_button'] ) { ?>
				<a class="button" href="<?php the_permalink(); ?>"><?php _e( 'View Ad', APP_TD ); ?> <span class="screen-reader-text"><?php the_title(); ?></span></a>
			<?php } ?>

		</div> <!-- .columns -->

	</div> <!-- .row -->

</article> <!-- .content-wrap -->
