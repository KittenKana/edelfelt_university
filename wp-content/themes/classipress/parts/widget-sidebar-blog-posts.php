<?php
/**
 * Sidebar Recent Posts template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */
?>

<?php $blog_posts = $instance['items']; ?>

<ul class="from-blog">

	<?php if ( $blog_posts->have_posts() ) : ?>

		<?php while( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>

			<li>

				<?php if ( ! empty( $instance['show_thumbnail'] ) ) { ?>
					<div class="post-thumb">
						<?php if ( has_post_thumbnail() ) { echo get_the_post_thumbnail( $post->ID, 'sidebar-thumbnail' ); } ?>
					</div>
				<?php } ?>

				<a class="entry-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

				<p class="side-meta">
					<?php if ( ! empty( $instance['show_author'] ) ) { ?>
						<?php _e( 'by', APP_TD ); ?> <?php the_author_posts_link(); ?>
						<?php if ( ! empty( $instance['show_date'] ) ) { ?>
							<?php _e( 'on', APP_TD ); ?>
						<?php } ?>
					<?php } ?>
					<?php if ( ! empty( $instance['show_date'] ) ) { ?>
						<?php echo appthemes_date_posted( get_the_date( "Y-m-d H:i:s" ) ); ?>
					<?php } ?>
					<?php if ( ! empty( $instance['show_comments_count'] ) ) { ?>
					  - <?php comments_popup_link( __( '0 Comments', APP_TD ), __( '1 Comment', APP_TD ), __( '% Comments', APP_TD ) ); ?>
					<?php } ?>
				</p>

				<?php if ( ! empty( $instance['show_content'] ) ) { ?>
					<p><?php echo cp_get_content_preview( 160 ); ?></p>
				<?php } ?>

			</li>

		<?php endwhile; ?>

	<?php else: ?>

		<li><?php _e( 'There are no blog articles yet.', APP_TD ); ?></li>

	<?php endif; ?>

	<?php wp_reset_postdata(); ?>

</ul>