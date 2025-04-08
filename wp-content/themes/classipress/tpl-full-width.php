<?php
/**
 * Template Name: Full Width Page
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<div class="row">

	<div id="primary" class="content-area small-12 columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

		<main id="main" class="site-main" role="main">

			<?php
			appthemes_before_loop( 'page' );

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					appthemes_before_post( 'page' );

					get_template_part( 'parts/content', 'page' );

					appthemes_after_post( 'page' );

				endwhile;

				appthemes_after_endwhile( 'page' );

			else :

				appthemes_loop_else( 'page' );

				get_template_part( 'parts/content-none', app_template_base() );

			endif;

			appthemes_after_loop( 'page' );
			?>

		</main>

	</div> <!-- #primary -->

</div> <!-- .row -->
