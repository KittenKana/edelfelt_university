<?php
/**
 * Customizer home section content.
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

$wp_customize->add_section(
	'cp_front_page_section',
	array(
		'title'       => __( 'Front Page', APP_TD ),
		'description' => __( 'Front Page layout configuration.', APP_TD ) . '<br /><br />' .
						'<span class="customize-control-title">' . __( 'Front Page Top Columns', APP_TD ) . '</span>' .
						__( 'By default, this area has one full width column. Each widget occupies a separate line.', APP_TD ) .
						'<br /><br />' .
						sprintf( __( 'Visit the <a href="%s">Widgets panel</a> and configure widgets for this tile set.', APP_TD ), "javascript:if( wp.customize.section( 'sidebar-widgets-sidebar_main_content' ) ) { wp.customize.section( 'sidebar-widgets-sidebar_main_content' ).focus();} else { wp.customize.panel( 'widgets' ).focus(); }" ) .
						'<br /><br />' .
						'<span class="customize-control-title">' . __( 'Front Page Bottom Columns', APP_TD ) . '</span>' .
						__( 'By default, this area has one full width column. Each widget occupies a separate line.', APP_TD ) .
						'<br /><br />' .
						sprintf( __( 'Visit the <a href="%s">Widgets panel</a> and configure widgets for this tile set.', APP_TD ), "javascript:if( wp.customize.section( 'sidebar-widgets-sidebar_main_bottom' ) ) { wp.customize.section( 'sidebar-widgets-sidebar_main_bottom' ).focus();} else { wp.customize.panel( 'widgets' ).focus(); }" ) .
						'<br /><br />',
		'panel'       => 'cp_custom_panel',
		'priority'    => 9,
	)
);

// =============================================================================
// Home Page Header
// =============================================================================

$wp_customize->add_setting(
	'front_page_hero',
	array(
		'default'           => 1,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'front_page_hero',
	array(
		'label'   => __( 'Display Hero Cover', APP_TD ),
		'section' => 'cp_front_page_section',
	)
) );

$wp_customize->add_setting(
	'front_page_searchbar',
	array(
		'default'           => 0,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'front_page_searchbar',
	array(
		'label'   => __( 'Display Searchbar', APP_TD ),
		'section' => 'cp_front_page_section',
	)
) );


// =============================================================================
// Middle Area
// =============================================================================

$wp_customize->add_setting(
	'home_sidebar_position',
	array(
		'default'           => 'right',
		'transport'         => 'refresh',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Image_Radio_Button_Control(
		$wp_customize,
		'home_sidebar_position',
		array(
			'label'   => __( 'Front Page Sidebar', APP_TD ),
			'section' => 'cp_front_page_section',
			'type'    => 'select',
			'choices' => array(
				'left'  => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-left.png',
					'name'  => __( 'Left', APP_TD ),
				),
				'right' => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-right.png',
					'name'  => __( 'Right', APP_TD ),
				),
			),
		)
	)
);
