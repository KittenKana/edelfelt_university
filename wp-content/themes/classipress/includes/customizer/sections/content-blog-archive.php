<?php
/**
 * Customizer blog section content.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

$wp_customize->add_section(
	'cp_content_blog_archive_section',
	array(
		'title'       => __( 'Blog Archive', APP_TD ),
		'description' => __( 'Blog archive section configuration.', APP_TD ),
		'panel'       => 'cp_custom_panel',
		'priority'    => 10,
	)
);

$wp_customize->add_setting(
	'blog_archive_sidebar_position',
	array(
		'default'           => 'none',
		'transport'         => 'refresh',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Image_Radio_Button_Control(
		$wp_customize,
		'blog_archive_sidebar_position',
		array(
			'label'   => __( 'Sidebar Position', APP_TD ),
			'section' => 'cp_content_blog_archive_section',
			'type'    => 'select',
			'choices' => array(
				'left'  => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-left.png',
					'name'  => __( 'Left', APP_TD ),
				),
				'none'  => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-none.png',
					'name'  => __( 'None', APP_TD ),
				),
				'right' => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-right.png',
					'name'  => __( 'Right', APP_TD ),
				),
			),
		)
	)
);
