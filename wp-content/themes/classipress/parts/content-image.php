<?php
/**
 * Image loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<nav id="image-navigation" class="navigation image-navigation">

	<div class="nav-links">

		<div class="row">
			<div class="nav-previous small-6 columns"><?php previous_image_link( false, __( '&larr; Previous Image', APP_TD ) ); ?></div>
			<div class="nav-next small-6 columns text-right"><?php next_image_link( false, __( 'Next Image &rarr;', APP_TD ) ); ?></div>
		</div> <!-- .row -->

	</div><!-- .nav-links -->

</nav><!-- .image-navigation -->

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="content-wrap">

		<div class="content-inner">

			<header class="entry-header text-center">

				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<?php if ( get_post()->post_parent != 0 ) : ?>
					<p><a href="<?php echo get_permalink( get_post()->post_parent ); ?>" class="back-to-post"><?php printf( __( '&larr; Back to %s', APP_TD ), get_the_title( get_post()->post_parent ) ); ?></a></p>
				<?php endif; ?>

			</header><!-- .entry-header -->

			<div class="entry-content">

				<div class="entry-attachment">

					<?php
					$image_url = wp_get_attachment_image_src( get_the_ID(), 'full' );
					$alt_text  = get_post_meta( get_the_ID(), '_wp_attachment_image_alt', true );
					?>
					<img src="<?php echo esc_url( $image_url[0] ); ?>" class="attachment-large" alt="<?php echo esc_attr( strip_tags( $alt_text ) ); ?>" />

					<?php if ( has_excerpt() ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
					<?php endif; ?>

				</div><!-- .entry-attachment -->

				<div class="entry-content">
					<?php the_content(); ?>
				</div> <!-- .entry-content -->

				<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', APP_TD ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
						'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', APP_TD ) . ' </span>%',
						'separator'   => '<span class="screen-reader-text">, </span>',
					) );
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">

				<?php
				/**
				 * Fires in the image page footer.
				 *
				 * @since 4.0.0
				 */
				do_action( 'cp_image_template_footer' );
				?>

			</footer><!-- .entry-footer -->

		</div> <!-- .content-inner -->

	</div> <!-- .content-wrap -->

</article>
