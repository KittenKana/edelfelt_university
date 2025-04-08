<?php
/**
 * Customizer blog section content.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

$wp_customize->add_section( 'cp_content_blog_section', array(
	'title'       => __( 'Blog Post', APP_TD ),
	'description' => __( 'Blog post section configuration.', APP_TD ),
	'panel'       => 'cp_custom_panel',
	'priority'    => 10,
) );

$wp_customize->add_setting( 'blog_sidebar_position', array(
	'default' => 'right',
	'transport' => 'refresh',
	'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
) );

$wp_customize->add_control( new APP_Customizer_Image_Radio_Button_Control( $wp_customize, 'blog_sidebar_position', array(
	'label'   => __( 'Sidebar Position', APP_TD ),
	'section' => 'cp_content_blog_section',
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
) ) );
