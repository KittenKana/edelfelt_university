<?php
/**
 * Customizer header section content.
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

$wp_customize->add_section( 'cp_content_header_section', array(
	'title'       => __( 'Header', APP_TD ),
	'description' => __( 'Header section configuration.', APP_TD ),
	'panel'       => 'cp_custom_panel',
	'priority'    => '',
) );

// =============================================================================
// Header Width
// =============================================================================

$wp_customize->add_setting( 'header_full_width', array(
	'default'           => 1,
	'transport'         => 'postMessage',
	'sanitize_callback' => 'absint',
) );

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'header_full_width', array(
	'label'    => __( 'Expand header full width', APP_TD ),
	'section'  => 'cp_content_header_section',
) ) );


// =============================================================================
// Sticky Header
// =============================================================================

$wp_customize->add_setting(
	'sticky_top_bar',
	array(
		'default'           => 0,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'sticky_top_bar', array(
	'label'   => __( 'Sticky Top Bar', APP_TD ),
	'section' => 'cp_content_header_section',
) ) );

$wp_customize->add_setting(
	'sticky_title_bar',
	array(
		'default'           => 0,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'sticky_title_bar', array(
	'label'   => __( 'Sticky Title Bar', APP_TD ),
	'section' => 'cp_content_header_section',
) ) );

$wp_customize->add_setting(
	'sticky_nav_bar',
	array(
		'default'           => 0,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'sticky_nav_bar', array(
	'label'   => __( 'Sticky Navigation Bar', APP_TD ),
	'section' => 'cp_content_header_section',
) ) );


// =============================================================================
// Header Height
// =============================================================================

$wp_customize->add_setting( 'header_height', array(
	'default'           => 0,
	'transport'         => 'postMessage',
	'sanitize_callback' => 'appthemes_customizer_text_sanitization',
) );

$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_height', array(
	'label'       => __( 'Title Bar height', APP_TD ),
	'section'     => 'cp_content_header_section',
	'type'        => 'number',
	'input_attrs' => array(
		'min'  => 0,
		'step' => 0.1,
	),
) ) );

// =============================================================================
// Header Aligment
// =============================================================================

$wp_customize->add_setting( 'header_vertical_alignment',
	array(
		'default'           => 'middle',
		'transport'         => 'postMessage',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control( new APP_Customizer_Text_Radio_Button_Control( $wp_customize, 'header_vertical_alignment',
	array(
		'label'       => __( 'Vertical Alignment', APP_TD ),
		'description' => __( 'Choose the alignment for your header items', APP_TD ),
		'section'     => 'cp_content_header_section',
		'choices'     => array(
			'top'    => __( 'Top', APP_TD ),
			'middle' => __( 'Middle', APP_TD ),
			'bottom' => __( 'Bottom', APP_TD ),
		),
	)
) );
