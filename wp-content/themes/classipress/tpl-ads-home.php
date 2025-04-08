<?php
/**
 * Template Name: Ads Home Template
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.3
 */

$sidebar_position = get_theme_mod( 'home_sidebar_position', 'right' );
$search_bar       = get_theme_mod( 'front_page_searchbar', 0 );
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php if ( $search_bar ) { ?>

		<?php get_template_part( 'searchbar' ); ?>

	<?php } ?>


	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<section class="home-top-area">

				<?php dynamic_sidebar( 'sidebar_main_content' ); ?>

			</section>


			<section class="home-middle-area row">

				<?php if ( 'left' === $sidebar_position ) { ?>

					<div class="column medium-5 m-large-4">

						<?php dynamic_sidebar( 'sidebar_main_middle_right' ); ?>

					</div>

				<?php } ?>

				<div class="column medium-7 m-large-8">

					<?php dynamic_sidebar( 'sidebar_main_middle_left' ); ?>

				</div>

				<?php if ( 'right' === $sidebar_position ) { ?>

					<div class="column medium-5 m-large-4">

						<?php dynamic_sidebar( 'sidebar_main_middle_right' ); ?>

					</div>

				<?php } ?>

			</section>

			<section class="home-bottom-area">

				<?php dynamic_sidebar( 'sidebar_main_bottom' ); ?>

			</section>


		</main>

	</div> <!-- #primary -->

<?php endwhile;
