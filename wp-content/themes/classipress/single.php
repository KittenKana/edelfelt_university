<?php
/**
 * The Template for displaying all single posts.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
$sidebar_position = get_theme_mod( 'blog_sidebar_position', 'right' );
appthemes_before_loop( get_post_type() );

if ( have_posts() ) :

	while ( have_posts() ) : the_post();
	?>
		<main role="main">

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

				<?php get_template_part( 'parts/content-cover', get_post_type() ); ?>

				<div id="primary" class="content-area row">

					<div class="columns">

						<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

					</div>

					<?php if ( 'left' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

					<div id="main" class="site-main <?php echo ( 'none' == $sidebar_position ) ? 'medium-10 medium-centered' : 'm-large-7 large-8' ; ?> columns">

						<?php
						appthemes_before_post( get_post_type() );

						get_template_part( 'parts/content', get_post_type() );

						appthemes_after_post( get_post_type() );

						get_template_part( 'parts/content-comments', get_post_type() );
						?>

					</div>

					<?php if ( 'right' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

				</div> <!-- #primary -->

			</article>

		</main>

		<?php

	endwhile;

	appthemes_after_endwhile( get_post_type() );

else:

	appthemes_loop_else( get_post_type() );

	get_template_part( 'parts/content-none', get_post_type() );

endif;

appthemes_after_loop( get_post_type() );
