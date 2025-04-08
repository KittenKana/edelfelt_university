<?php
/**
 * Ad listings Archive template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<?php get_template_part( 'searchbar' ); ?>

<div id="primary" class="content-area row">

	<div class="columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

	</div>

	<?php if ( 'left' == get_theme_mod( 'listing_archive_sidebar_position', 'right' ) ) { get_sidebar(); } ?>

	<main id="main" class="site-main m-large-8 columns" role="main">

		<section>

			<?php
			get_template_part( 'parts/header-archive', get_post_type() );

			appthemes_before_loop( get_post_type() );

			if ( have_posts() ) : ?>

				<div class="row small-up-1 medium-up-12 list-view listing-wrap">

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="column">

						<?php appthemes_before_post( get_post_type() ); ?>

						<?php get_template_part( 'parts/content-item', get_post_type() ); ?>

						<?php appthemes_after_post( get_post_type() ); ?>

					</div> <!-- .column -->

				<?php endwhile; ?>

				</div> <!-- .row -->

				<?php cp_do_pagination();

			else :

				appthemes_loop_else( get_post_type() );

				get_template_part( 'parts/content-none', get_post_type() );

			endif;

			appthemes_after_loop( get_post_type() );
			?>

		</section>

	</main>

	<?php if ( 'right' == get_theme_mod( 'listing_archive_sidebar_position', 'right' ) ) { get_sidebar(); } ?>

</div> <!-- #primary -->
