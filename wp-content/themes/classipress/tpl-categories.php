<?php
/**
 * Template Name: Categories Template
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.2
 */
?>

<div class="row">

	<div id="primary" class="content-area medium-10 medium-centered columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

		<main id="main" class="site-main" role="main">

			<?php
			appthemes_before_loop( 'page' );

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					appthemes_before_post( 'page' );

					get_template_part( 'parts/content', app_template_base() );

					appthemes_after_post( 'page' );

				endwhile;

				appthemes_after_endwhile( 'page' );

			endif;

			appthemes_after_loop( 'page' );
			?>

		</main>

	</div> <!-- #primary -->

</div> <!-- .row -->
