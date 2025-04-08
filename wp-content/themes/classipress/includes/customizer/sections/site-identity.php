<?php
/**
 * Customizer site identity section functionality
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */
global $cp_options;

$wp_customize->remove_control( 'display_header_text' );

// Allows us to show changes in real-time for default options.
// Also need code in our customize-preview.js file.
$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

// Add header text site setting.
$wp_customize->add_setting( 'header_text_site_title', array(
	'default'           => 0,
	'sanitize_callback' => 'absint',
) );

// Add header text site field.
$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'header_text_site_title', array(
	'label'    => __( 'Display Site Title', APP_TD ),
	'section'  => 'title_tagline',
) ) );

// Add header text tagline setting.
$wp_customize->add_setting( 'header_text_site_tagline', array(
	'default'           => 1,
	'sanitize_callback' => 'absint',
) );

// Add header text tagline field.
$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'header_text_site_tagline', array(
	'label'    => __( 'Display Tagline', APP_TD ),
	'section'  => 'title_tagline',
) ) );

// Add the footer copyright text setting.
$wp_customize->add_setting( 'footer_copyright_text', array(
	'default' => sprintf( __( '&copy; %s %s | All Rights Reserved', APP_TD ), get_bloginfo( 'name' ), date_i18n( 'Y' ) )
) );

// Add footer copyright text field.
$wp_customize->add_control( 'footer_copyright_text', array(
	'label'    => __( 'Footer Copyright', APP_TD ),
	'type'     => 'text',
	'priority' => 20,
	'section'  => 'title_tagline',
) );

$wp_customize->add_setting( 'cp_options[display_website_time]', array(
	'default' => $cp_options->display_website_time,
	'type' => 'option',
) );

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'cp_display_website_time', array(
	'label'      => __( 'Show  time/timezone in the footer', APP_TD ),
	'settings'   => 'cp_options[display_website_time]',
	'section'    => 'title_tagline',
) ) );
