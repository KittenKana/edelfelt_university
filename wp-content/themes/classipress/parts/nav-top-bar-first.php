<?php
/**
 * The template for displaying the first top bar navigation
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

$expanded = get_theme_mod( 'header_full_width', 1 ) ? ' expanded' : '';
?>
<div id="first-top-bar" class="top-bar" role="navigation">

	<div class="row column<?php echo $expanded; ?>">

		<?php cp_header_menu_first_left(); ?>

		<?php cp_header_menu_first_right(); ?>

	</div><!-- .row -->

</div><!-- .top-bar -->