<?php
/**
 * Customizer header media custom options.
 *
 * @package ClassiPress
 *
 * @global $wp_customize WP_Customize_Manager
 *
 * @since 4.2.0
 */

/* @var $wp_customize WP_Customize_Manager */

// =============================================================================
// Header Media Alignment
// =============================================================================

$wp_customize->add_setting(
	'header_media_horizontal_alignment',
	array(
		'default'           => 'middle',
		'transport'         => 'refresh',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control( new APP_Customizer_Text_Radio_Button_Control( $wp_customize, 'header_media_horizontal_alignment',
	array(
		'label'   => __( 'Horizontal Alignment', APP_TD ),
		'section' => 'header_image',
		'choices' => array(
			'left'   => __( 'Left', APP_TD ),
			'middle' => __( 'Middle', APP_TD ),
			'right'  => __( 'Right', APP_TD ),
		),
	)
) );

$wp_customize->add_setting(
	'header_media_vertical_alignment',
	array(
		'default'           => 'middle',
		'transport'         => 'refresh',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control( new APP_Customizer_Text_Radio_Button_Control( $wp_customize, 'header_media_vertical_alignment',
	array(
		'label'   => __( 'Vertical Alignment', APP_TD ),
		'section' => 'header_image',
		'choices' => array(
			'top'    => __( 'Top', APP_TD ),
			'middle' => __( 'Middle', APP_TD ),
			'bottom' => __( 'Bottom', APP_TD ),
		),
	)
) );


// =============================================================================
// Header Media Overlay Background Color
// =============================================================================

// Add header media overlay background color setting.
$wp_customize->add_setting(
	'header_media_overlay_background_color',
	array(
		'default'           => 'rgba(0,0,0,0.35)',
		'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization',
		'transport'         => 'refresh',
	)
);

// Add header media overlay background color field.
$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'header_media_overlay_background_color', array(
	'label'       => __( 'Overlay Background Color', APP_TD ),
	'description' => __( 'The color used for the overlay.', APP_TD ),
	'section'     => 'header_image',
) ) );
