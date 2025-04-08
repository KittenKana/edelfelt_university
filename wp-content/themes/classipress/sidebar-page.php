<?php
/**
 * Page Sidebar template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */
?>

<div id="sidebar" class="m-large-4 columns" role="complementary">

	<?php appthemes_before_sidebar_widgets( 'page' ); ?>

	<?php if ( ! dynamic_sidebar( 'sidebar_page' ) ) : ?>

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

	<?php appthemes_after_sidebar_widgets( 'page' ); ?>

</div><!-- #sidebar -->
