<?php
/**
 * Front Page Header template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.2.0
 */

global $cp_options;
?>

<header class="header" role="banner">

	<?php get_template_part( 'parts/header-media' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-first' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-primary' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-secondary' ); ?>

	<?php get_template_part( 'parts/nav-mobile-title-bar' ); ?>

	<?php if ( get_theme_mod( 'front_page_hero', 1 ) ) { ?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'parts/hero', 'cover' ); ?>

		<?php endwhile; ?>

	<?php } ?>


</header> <!-- .header -->

