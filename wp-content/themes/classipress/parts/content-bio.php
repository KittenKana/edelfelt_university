<?php
/**
 * The template for displaying the author bio.
 *
 * @since 4.0.0
 */

?>

<section class="author-info">

	<div class="author-avatar">
		<?php echo cp_get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
	</div><!-- .author-avatar -->

	<div class="author-description">

		<h2 class="author-title"><span class="author-heading"><?php echo _x( 'By', 'byline for author name', APP_TD ); ?></span> <span class="author-name"><?php echo get_the_author(); ?></span></h2>

		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
			<a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php printf( __( 'View all posts by %s', APP_TD ), get_the_author() ); ?></a>
		</p><!-- .author-bio -->

	</div><!-- .author-description -->

</section><!-- .author-info -->
