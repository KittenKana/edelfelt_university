<?php
/**
 * Template Name: User Profile
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */
?>

<div class="content-area row">

	<?php if ( 'left' == get_theme_mod( 'user_sidebar_position', 'left' ) ) { get_sidebar( 'user' ); } ?>

	<div id="primary" class="m-large-8 columns">

		<main id="main" class="site-main" role="main">

			<?php appthemes_notices(); ?>

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					get_template_part( 'parts/content', 'edit-profile' );

				endwhile;

			else :

				get_template_part( 'parts/content', 'none' );

			endif;
			?>

		</main>

	</div> <!-- #primary -->

	<?php if ( 'right' == get_theme_mod( 'user_sidebar_position', 'left' ) ) { get_sidebar( 'user' ); } ?>

</div> <!-- .row -->
