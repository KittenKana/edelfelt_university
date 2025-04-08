<?php
/**
 * The template for displaying pages.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

$sidebar_position = get_theme_mod( 'page_sidebar_position', 'right' );
?>

<div id="primary" class="content-area row">

	<div class="columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

	</div>

	<?php if ( 'left' === $sidebar_position ) { get_sidebar( app_template_base() ); } ?>

	<main id="main" class="site-main <?php echo ( 'none' === $sidebar_position ) ? 'medium-10 medium-centered' : 'm-large-8' ; ?> columns" role="main">

		<?php
		appthemes_before_loop( 'page' );

		if ( have_posts() ) :

			while ( have_posts() ) : the_post();

				appthemes_before_post( 'page' );

				get_template_part( 'parts/content', app_template_base() );

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

	<?php if ( 'right' === $sidebar_position ) { get_sidebar( app_template_base() ); } ?>

</div> <!-- #primary -->