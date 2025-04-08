<?php
/**
 * Categories Page content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="navigation">

	<div class="content-wrap">

		<?php the_post_thumbnail( 'full' ); ?>

		<div class="content-inner">

			<header class="entry-header">

				<?php appthemes_before_post_title( 'page' ); ?>

				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<?php appthemes_after_post_title( 'page' ); ?>

			</header>

			<div class="entry-content">

				<?php appthemes_before_post_content( 'page' ); ?>

				<?php the_content(); ?>

				<div id="directory" class="directory listing-cats listing-cats-page row collapse small-up-1 medium-up-2 <?php cp_display_style( 'dir_cols' ); ?>">

					<?php echo cp_create_categories_list( 'dir' ); ?>

				</div><!--/directory-->

				<?php appthemes_after_post_content( 'page' ); ?>

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

	</div> <!-- .content-wrap -->

</article>
