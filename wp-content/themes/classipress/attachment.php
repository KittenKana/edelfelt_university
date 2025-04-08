<?php
/**
 * Attachments template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<div class="row">

	<div id="primary" class="content-area medium-10 medium-centered columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

		<main id="main" class="site-main" role="main">

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					get_template_part( 'parts/content', 'image' );

				endwhile;

			else :

				get_template_part( 'parts/content-none', 'image' );

			endif;
			?>

		</main>

	</div> <!-- #primary -->

</div> <!-- .row -->
