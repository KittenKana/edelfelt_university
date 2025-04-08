<?php
/**
 * Template Name: Edit Listing
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.4
 */

?>

<div class="content-area row">

	<?php if ( 'left' == get_theme_mod( 'user_sidebar_position', 'left' ) ) { get_sidebar( 'user' ); } ?>

	<div id="primary" class="m-large-8 columns">

		<main id="main" class="site-main" role="main">

			<div class="content-wrap content-wrap-clear">

				<?php appthemes_display_checkout(); ?>

			</div>

		</main>

	</div> <!-- #primary -->

	<?php if ( 'right' == get_theme_mod( 'user_sidebar_position', 'left' ) ) { get_sidebar( 'user' ); } ?>

</div> <!-- .row -->
