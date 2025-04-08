<?php
/**
 * Customizer user section content.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

$wp_customize->add_section( 'cp_content_user_section', array(
	'title'       => __( 'User', APP_TD ),
	'description' => __( 'User section configuration.', APP_TD ),
	'panel'       => 'cp_custom_panel',
	'priority'    => 10,
) );

$wp_customize->add_setting( 'user_sidebar_position', array(
	'default' => 'left',
	'transport' => 'refresh',
	'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
) );

$wp_customize->add_control( new APP_Customizer_Image_Radio_Button_Control( $wp_customize, 'user_sidebar_position', array(
	'label'   => __( 'Sidebar Position', APP_TD ),
	'section' => 'cp_content_user_section',
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
