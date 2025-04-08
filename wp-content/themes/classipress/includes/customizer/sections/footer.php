<?php
/**
 * Customizer footer section content.
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

$wp_customize->add_section( 'cp_footer_section', array(
	'title'       => __( 'Footer', APP_TD ),
	'description' => __( 'Footer section configuration.', APP_TD ) . '<br /><br />' .
					'<span class="customize-control-title">' . __( 'Footer Columns', APP_TD ) . '</span>' .
					__( 'By default you can have up to four items which will display in the footer from left to right.', APP_TD ) . '<br /><br />' .
					sprintf( __( 'Visit the <a href="%s">Widgets panel</a> and configure widgets widths for custom tiled layout.', APP_TD ), "javascript:if( wp.customize.section( 'sidebar-widgets-sidebar_footer' ) ) { wp.customize.section( 'sidebar-widgets-sidebar_footer' ).focus();} else { wp.customize.panel( 'widgets' ).focus(); }" ),
	'panel'       => 'cp_custom_panel',
	'priority'    => 99,
) );

// Dummy control to display section while there is no other controls.
$wp_customize->add_setting( 'footer_columns',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'appthemes_customizer_integer_sanitization',
	)
);
$wp_customize->add_control( new APP_Customizer_Simple_Notice_Control( $wp_customize, 'footer_columns',
	array(
		'section' => 'cp_footer_section',
	)
) );
