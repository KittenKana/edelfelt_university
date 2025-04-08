<?php
/**
 * Template Name: User Dashboard Orders
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.0
 * @since   4.0.0 Migrated to Foundation layout.
 */
?>

<div class="content-area row">

	<div class="columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

	</div>

	<?php
	if ( 'left' == get_theme_mod( 'user_sidebar_position', 'left' ) ) {
		get_sidebar( 'user' );
	}
	?>

	<div id="primary" class="m-large-8 columns">

		<main id="main" class="site-main" role="main">

			<?php appthemes_notices(); ?>

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) : the_post();
			?>

				<div class="content-wrap dashboard-listings">

					<div class="content-inner">

						<section>

							<header class="entry-header text-center">
								<?php the_title( '<h1 class="h2 entry-title">', '</h1>' ); ?>
							</header>

							<div class="entry-content">

								<?php the_content(); ?>

								<?php get_template_part( 'dashboard-orders' ); ?>

							</div> <!-- .entry-content -->

						</section>

					</div> <!-- .content-inner -->

				</div><!-- .content-wrap -->

			<?php
				endwhile;

			endif;
			?>

		</main>

	</div> <!-- #primary -->

	<?php
	if ( 'right' == get_theme_mod( 'user_sidebar_position', 'left' ) ) {
		get_sidebar( 'user' );
	}
	?>

</div> <!-- .row -->
