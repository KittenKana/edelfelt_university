<?php
/**
 * Template Name: Blog Template
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

					<h1 class="page-title cover-wrapper"><?php echo get_option( 'page_for_posts' ) ? get_the_title( get_option( 'page_for_posts' ) ) :  _x( 'Blog', 'blog page title', APP_TD ); ?></h1>

					<?php
					/**
					 * Fires after the blog home header.
					 *
					 * @since 4.0.0
					 */
					do_action( 'cp_blog_template_header' );
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

			<?php appthemes_before_loop( get_post_type() ); ?>

			<?php if ( have_posts() ) : ?>

				<div class="row entry-wrap small-up-1 medium-up-2 large-up-<?php echo ( 'none' === $sidebar_position ) ? '3' : '2'; ?>">

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="column">

						<?php appthemes_before_post( get_post_type() ); ?>

						<?php get_template_part( 'parts/content-item', get_post_type() ); ?>

						<?php appthemes_after_post( get_post_type() ); ?>

					</div> <!-- .column -->

				<?php endwhile; ?>

				<?php appthemes_after_endwhile( get_post_type() ); ?>

				</div> <!-- .row -->

				<?php cp_do_pagination();

			else :

				appthemes_loop_else( get_post_type() );

				get_template_part( 'parts/content-none', get_post_type() );

			endif;
			?>

			<?php appthemes_after_loop( get_post_type() ); ?>

		</main>

		<?php if ( 'right' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

	</div> <!-- #primary -->

</section>
