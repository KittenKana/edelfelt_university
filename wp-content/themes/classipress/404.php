<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<?php get_template_part( 'searchbar' ); ?>

<div class="row">

	<div id="primary" class="content-area medium-10 medium-centered columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

		<main id="main" class="site-main" role="main">

			<article class="content-wrap error-404 not-found text-center">

				<div class="content-inner">

					<header class="page-header">

						<h1 class="page-title"><?php _e( "Oops! That page can't be found.", APP_TD ); ?></h1>

					</header><!-- .page-header -->

					<div class="page-content">

						<p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', APP_TD ); ?></p>

						<div class="medium-8 medium-centered columns">

							<?php get_search_form(); ?>

						</div><!-- .search-form-wrapper -->

						<?php get_template_part( 'parts/adblock', 'content' ); ?>

					</div><!-- .page-content -->

				</div> <!-- .content-inner -->

			</article><!-- .error-404 -->


		</main><!-- .site-main -->

	</div><!-- .content-area -->

</div> <!-- .row -->
