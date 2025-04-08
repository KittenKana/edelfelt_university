<?php
/**
 * Page loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

	<div class="content-wrap">

		<?php the_post_thumbnail( 'full' ); ?>

		<div class="content-inner">

			<header class="entry-header">

				<?php appthemes_before_post_title( 'page' ); ?>

				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<?php appthemes_after_post_title( 'page' ); ?>

				<?php
				/**
				 * Fires in the page page header.
				 *
				 * @since 4.0.0
				 */
				do_action( 'cp_page_template_header' );
				?>

			</header>

			<div class="entry-content">

				<?php appthemes_before_post_content( 'page' ); ?>

				<?php the_content(); ?>

				<?php appthemes_after_post_content( 'page' ); ?>

				<?php
				wp_link_pages( array(
					'before'      => '<nav class="page-links"><span class="page-links-title">' . __( 'Pages:', APP_TD ) . '</span>',
					'after'       => '</nav>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', APP_TD ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
				?>

			</div> <!-- .entry-content -->

			<footer class="entry-footer">
				
				<?php
				/**
				 * Fires in the page page footer.
				 *
				 * @since 4.0.0
				 */
				do_action( 'cp_page_template_footer' );
				?>

			</footer>

		</div> <!-- .content-inner -->

	</div><!-- .content-wrap -->
	
	<?php get_template_part( 'parts/content-comments', app_template_base() ); ?>

</article>