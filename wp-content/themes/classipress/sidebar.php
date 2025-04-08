<?php
/**
 * Generic Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

global $cp_options;
?>

<div id="sidebar" class="m-large-4 columns" role="complementary">
	<?php
	if ( is_tax( APP_TAX_CAT ) ) {
		// go get the taxonomy category id so we can filter with it
		// have to use slug instead of name otherwise it'll break with multi-word cats
		if ( ! isset( $filter ) ) {
			$filter = '';
		}

		$ad_cat_array = get_term_by( 'slug', get_query_var( APP_TAX_CAT ), APP_TAX_CAT, ARRAY_A, $filter );

		?>
		<aside class="widget widget_refine_search">
			<?php
			// build the advanced sidebar search
			cp_show_refine_search( $ad_cat_array['term_id'] );
			?>
		</aside>
		<?php

	} else if ( is_search() && isset( $_GET[ 'scat' ] ) && isset( $_GET['st'] ) && $_GET['st'] === APP_POST_TYPE ) {

		?>
		<aside class="widget widget_refine_search">
			<?php
			// build the advanced sidebar search
			cp_show_refine_search( get_query_var( 'scat' ) );
			?>
		</aside>
		<?php
	} // is_search
	?>

	<?php appthemes_before_sidebar_widgets( 'main' ); ?>

	<?php if ( ! dynamic_sidebar( 'sidebar_main' ) ) : ?>

		<?php
		the_widget(
			'WP_Widget_Meta',
			array(
				'title' => __( 'Meta', APP_TD ),
			),
			array(
				'id'            => 'widget-area-sidebar-1',
				'before_widget' => '<aside class="widget widget_meta">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		?>

	<?php endif; ?>

	<?php appthemes_after_sidebar_widgets( 'main' ); ?>

</div><!-- #sidebar -->
