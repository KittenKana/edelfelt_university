<?php
/**
 * Generic Header template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

global $cp_options;
?>

<header class="header" role="banner">

	<?php get_template_part( 'parts/header-media' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-first' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-primary' ); ?>

	<?php get_template_part( 'parts/nav-top-bar-secondary' ); ?>

	<?php get_template_part( 'parts/nav-mobile-title-bar' ); ?>

</header> <!-- .header -->
