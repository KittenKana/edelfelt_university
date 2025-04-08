<?php
/**
 * Customizer functionality
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

// Allows us to show changes in real-time for default options.
// Also need code in our customize-preview.js file.
$wp_customize->get_setting( 'background_color' )->transport = 'postMessage';

// Remove the core header textcolor control, as it shares the main text color.
$wp_customize->remove_control( 'header_textcolor' );

$wp_customize->add_section( 'colors', array(
	'title'       => __( 'Colors', APP_TD ),
	'description' => '',
	'priority'    => 20,
) );

// =============================================================================
// Color Scheme
// =============================================================================

// Add color scheme setting.
$wp_customize->add_setting( 'color_scheme', array(
	'default'           => 'red',
	'transport'         => 'postMessage',
	'sanitize_callback' => 'cp_sanitize_color_scheme',
) );

// Add color scheme radio buttons.
// For new colors, make sure to whitelist in cp_sanitize_color_scheme().
$wp_customize->add_control( 'color_scheme', array(
	'type'     => 'radio',
	'label'    => __( 'Color Scheme', APP_TD ),
	'choices'  => array(
		'teal'     => __( 'Teal', APP_TD ),
		'blue'     => __( 'Blue', APP_TD ),
		'green'    => __( 'Green', APP_TD ),
		'orange'   => __( 'Orange', APP_TD ),
		'red'      => __( 'Red', APP_TD ),
		'purple'   => __( 'Purple', APP_TD ),
		'pink'     => __( 'Pink', APP_TD ),
		'magenta'  => __( 'Magenta', APP_TD ),
		'charcoal' => __( 'Charcoal', APP_TD ),
		'custom'   => __( 'Custom', APP_TD ),
	),
	'section'  => 'colors',
	'priority' => 5,
) );

// Add main color setting.
$wp_customize->add_setting( 'color_scheme_custom', array(
	'default'           => '#B22222',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add main color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_scheme_custom', array(
	'label'    => __( 'Primary Color', APP_TD ),
	'section'  => 'colors',
	'priority' => 6,
) ) );


// =============================================================================
// Global Text Color
// =============================================================================

// Add global text color setting.
$wp_customize->add_setting( 'global_text_color', array(
	'default'           => '#565656',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add header text color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'global_text_color', array(
	'label'   => __( 'Global Text Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Top Bar Background Color
// =============================================================================

// Add header top background color setting.
$wp_customize->add_setting( 'header_top_background_color', array(
	'default'           => '#FFFFFF',
	'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization',
	'transport'         => 'postMessage',
) );

// Add header top background color field.
$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'header_top_background_color', array(
	'label'   => __( 'Header Top Bar Background Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Top Bar Menu Links Color
// =============================================================================

// Add top header menu links color setting.
$wp_customize->add_setting( 'top_header_menu_color', array(
	'default'           => '#565656',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add top header menu links color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'top_header_menu_color', array(
	'label'   => __( 'Header Top Bar Menu Links Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Middle Bar Background Color
// =============================================================================

// Add header background color setting.
$wp_customize->add_setting( 'header_background_color', array(
	'default'           => '#FFFFFF',
	'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization',
	'transport'         => 'postMessage',
) );

// Add header background color field.
$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'header_background_color', array(
	'label'   => __( 'Header Middle Bar Background Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Middle Bar Text Color
// =============================================================================

// Add middle header text color setting.
$wp_customize->add_setting( 'header_text_color', array(
	'default'           => '#565656',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add header text color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_text_color', array(
	'label'   => __( 'Header Middle Bar Text Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Middle Bar Menu Links Color
// =============================================================================

// Add middle header menu links color setting.
$wp_customize->add_setting( 'middle_header_menu_color', array(
	'default'           => '#8A8A8A',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add middle header menu links color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'middle_header_menu_color', array(
	'label'   => __( 'Header Middle Bar Menu Links Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Header Bottom Bar Background Color
// =============================================================================

// Add header top background color setting.
$wp_customize->add_setting( 'header_bottom_background_color', array(
	'default'           => '',
	'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization',
	'transport'         => 'postMessage',
) );

// Add header top background color field.
$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'header_bottom_background_color', array(
	'label'       => __( 'Header Bottom Bar Background Color', APP_TD ),
	'description' => __( 'Defaults to color scheme primary color.', APP_TD ),
	'section'     => 'colors',
) ) );


// =============================================================================
// Header Bottom Bar Menu Links Color
// =============================================================================

// Add top header menu links color setting.
$wp_customize->add_setting( 'bottom_header_menu_color', array(
	'default'           => '',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add top header menu links color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'bottom_header_menu_color', array(
	'label'   => __( 'Header Bottom Menu Links Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Sticky Header Background Color
// =============================================================================

$wp_customize->add_setting(
	'sticky_header_background_color',
	array(
		'default'           => '',
		'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'sticky_header_background_color', array(
	'label'       => __( 'Sticky Header Background Color', APP_TD ),
	'description' => __( 'Can be useful for transparent headers.', APP_TD ),
	'section'     => 'colors',
) ) );


// =============================================================================
// Sticky Header Text Color
// =============================================================================

$wp_customize->add_setting(
	'sticky_header_text_color',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	)
);

$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'sticky_header_text_color', array(
	'label'   => __( 'Sticky Header Text Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Footer Background Color
// =============================================================================

// Add footer background color setting.
$wp_customize->add_setting( 'footer_background_color', array(
	'default'           => '#313131',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'postMessage',
) );

// Add footer background color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_background_color', array(
	'label'   => __( 'Footer Background Color', APP_TD ),
	'section' => 'colors',
) ) );


// =============================================================================
// Footer Text Color
// =============================================================================

// Add footer text color setting.
$wp_customize->add_setting( 'footer_text_color', array(
	'default'           => '#FFFFFF',
	'sanitize_callback' => 'sanitize_hex_color',
	'transport'         => 'refresh',
) );

// Add header text color field.
$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'footer_text_color', array(
	'label'   => __( 'Footer Text Color', APP_TD ),
	'section' => 'colors',
) ) );

/**
 * Sanitize the color scheme.
 *
 * @since 4.0.0
 *
 * @param string $input The value of color scheme.
 */
function cp_sanitize_color_scheme( $input ) {

	$valid = array( 'blue', 'green', 'orange', 'red', 'teal', 'purple', 'pink', 'magenta', 'charcoal', 'custom' );

	if ( in_array( $input, $valid ) ) {
		return $input;
	}

	return 'red';
}
