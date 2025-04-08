<?php
/**
 * Customizer fonts section declaration.
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

// Add the fonts panel.
$wp_customize->add_section( 'cp_custom_fonts_section', array(
	'title'       => __( 'Fonts', APP_TD ),
	'description' => ''
	. '<p>' . __( 'Theme fonts settings gives you more than just changing fonts.', APP_TD ) . '</p>'
	. '<p>' . __( 'Here you can change whole site layout scale by setting <strong>Global font size</strong>.', APP_TD ) . '</p>'
	. '<p>' . __( 'Whether you need a classic compact template, or a wide modern one, it all depends on the global font size.', APP_TD ) . '</p>',
) );

$wp_customize->add_setting( 'global_font_size', array(
	'default' => 1,
	'transport' => 'postMessage',
	'sanitize_callback' => 'appthemes_customizer_range_sanitization',
) );

$wp_customize->add_control( new APP_Customizer_Slider_Control( $wp_customize, 'global_font_size', array(
	'label'       => __( 'Global font size (rem)', APP_TD ),
	'description' => __( 'The global font size affect whole layout that scales up and down uniformly.', APP_TD ),
	'section'     => 'cp_custom_fonts_section',
	'input_attrs' => array(
		'min' => 0.5,
		'max' => 1.5,
		'step' => .001,
	),
) ) );

$wp_customize->add_setting( 'global_font_family', array(
	'default' => json_encode( array(
		'font' => 'Lato',
		'regularweight' => 'regular',
		'italicweight' => 'italic',
		'boldweight' => '900',
		'category' => 'sans-serif',
	) ),
	'sanitize_callback' => 'appthemes_customizer_google_font_sanitization',
) );

$wp_customize->add_control( new APP_Customizer_Google_Font_Select_Control( $wp_customize, 'global_font_family', array(
	'label'       => __( 'Global Font Family', APP_TD ),
	'description' => esc_html__( 'The main theme font.', APP_TD ),
	'section'     => 'cp_custom_fonts_section',
	'input_attrs' => array(
		'font_count' => 'all',
		'orderby' => 'alpha',
	),
) ) );

$wp_customize->add_setting( 'header_font_family', array(
	'default' => json_encode( array(
		'font' => 'Lato',
		'regularweight' => 'regular',
		'italicweight' => 'italic',
		'boldweight' => '900',
		'category' => 'sans-serif',
	) ),
	'sanitize_callback' => 'appthemes_customizer_google_font_sanitization',
) );

$wp_customize->add_control( new APP_Customizer_Google_Font_Select_Control( $wp_customize, 'header_font_family', array(
	'label'       => __( 'Headers Font Family', APP_TD ),
	'description' => esc_html__( 'The headers font.', APP_TD ),
	'section'     => 'cp_custom_fonts_section',
	'input_attrs' => array(
		'font_count' => 'all',
		'orderby' => 'alpha',
	),
) ) );
