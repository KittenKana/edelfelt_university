<?php
/**
 * Blog post single loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

	<div class="content-wrap">

		<div class="content-inner">

			<div class="entry-content">

				<?php appthemes_before_post_content( get_post_type() ); ?>

				<?php
				/* translators: %s: Name of current post */
				the_content( sprintf(
					__( 'Continue reading %s', APP_TD ),
					the_title( '<span class="screen-reader-text">', '</span>', false )
				) );

				wp_link_pages( array(
					'before'      => '<nav class="page-links"><span class="page-links-title">' . __( 'Pages:', APP_TD ) . '</span>',
					'after'       => '</nav>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', APP_TD ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
				?>

				<?php appthemes_after_post_content( get_post_type() ); ?>

			</div> <!-- .entry-content -->

			<?php
			// Author bio.
			if ( is_single() && get_the_author_meta( 'description' ) ) {
				get_template_part( 'parts/content-bio', get_post_type() );
			}
			?>

			<footer class="entry-footer">

				<?php
				/**
				 * Fires in the single page footer.
				 *
				 * @since 4.0.0
				 */
				do_action( 'cp_single_template_footer' );
				?>

			</footer>

		</div> <!-- .content-inner -->

	</div> <!-- .content-wrap -->