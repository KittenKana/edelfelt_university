<?php
/**
 * Ad listing Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

?>

<div id="sidebar" class="m-large-5 large-4 columns" role="complementary">

	<?php appthemes_before_sidebar_widgets( 'ad' ); ?>

	<?php cp_tabbed_dynamic_sidebar( 'sidebar_listing_tabs' ); ?>

	<?php if ( ! dynamic_sidebar( 'sidebar_listing' ) ) : ?>

	<!-- no dynamic sidebar so don't do anything -->

	<?php endif; ?>

	<?php appthemes_after_sidebar_widgets( 'ad' ); ?>

</div><!-- #sidebar -->
