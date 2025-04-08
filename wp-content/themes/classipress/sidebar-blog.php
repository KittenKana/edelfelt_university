<?php
/**
 * Blog Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

?>

<div id="sidebar" class="m-large-5 large-4 columns" role="complementary">

	<?php appthemes_before_sidebar_widgets( 'blog' ); ?>

	<?php if ( ! dynamic_sidebar( 'sidebar_blog' ) ) : ?>

		<!-- no dynamic sidebar so don't do anything -->

	<?php endif; ?>

	<?php appthemes_after_sidebar_widgets( 'blog' ); ?>

</div><!-- #sidebar -->
