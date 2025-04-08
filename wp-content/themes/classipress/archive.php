<?php
/**
 * Archive template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

$sidebar_position = get_theme_mod( 'blog_archive_sidebar_position', 'none' );
?>
<section>

	<div <?php echo apply_filters( 'cp_background_cover', 'page-cover entry-cover text-center', array( 'size' => 'full' ) ); ?>>

		<div class="hero-blog-wrap row">

			<div class="column">

				<header class="entry-header">

					<?php cp_the_archive_title( '<h1 class="page-title cover-wrapper">', '</h1>' ); ?>
					<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>

					<?php
					/**
					 * Fires in the archive page header.
					 *
					 * @since 4.0.0
					 */
					do_action( 'cp_archive_template_header' );
					?>

				</header>

			</div> <!-- .column -->

		</div> <!-- .row -->

	</div>

	<div id="primary" class="content-area row">

		<div class="columns">

			<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

		</div>

		<?php if ( 'left' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

		<main id="main" class="site-main <?php echo ( 'none' === $sidebar_position ) ? 'small-12' : 'm-large-7 large-8'; ?> columns" role="main">

			<?php if ( have_posts() ) : ?>

				<div class="row entry-wrap small-up-1 medium-up-2 large-up-<?php echo ( 'none' === $sidebar_position ) ? '3' : '2'; ?>">

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="column">

					<?php get_template_part( 'parts/content-item', get_post_type() ); ?>

					</div> <!-- .column -->

				<?php endwhile; ?>

				</div> <!-- .row -->

				<?php cp_do_pagination(); ?>

			<?php else : ?>

				<?php appthemes_loop_else( get_post_type() ); ?>

				<?php get_template_part( 'parts/content-none', get_post_type() ); ?>

			<?php endif; ?>

			<?php appthemes_after_loop( get_post_type() ); ?>

		</main>

		<?php if ( 'right' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

	</div> <!-- #primary -->

</section>
