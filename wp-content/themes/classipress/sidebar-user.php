<?php
/**
 * User Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

global $current_user, $cp_options;

$logout_url = cp_logout_url();
?>

<div id="sidebar" class="m-large-4 columns" role="complementary">

	<?php appthemes_before_sidebar_widgets( 'user' ); ?>

	<?php dynamic_sidebar( 'sidebar_user' ); ?>

	<?php appthemes_after_sidebar_widgets( 'user' ); ?>

</div><!-- #sidebar -->
