<?php
/**
 * The generic posts page.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<div id="primary" class="content-area row">

	<div class="columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

	</div>

	<main id="main" class="site-main small-12 columns" role="main">

		<?php appthemes_before_loop( get_post_type() ); ?>

		<?php if ( have_posts() ) : ?>

			<div class="row entry-wrap small-up-1 medium-up-2 large-up-3">

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

</div> <!-- #primary -->
