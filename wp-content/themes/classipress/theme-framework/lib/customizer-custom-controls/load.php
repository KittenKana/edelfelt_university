<?php
/**
 * AppThemes Customizer Custom Controls load
 *
 * @link https://github.com/AppThemes/theme-framework/wiki/Customizer-Custom-Controls Documentation and examples.
 *
 * @package ThemeFramework
 * @since 2.0.0
 */

require_once dirname( __FILE__ ) . '/inc/sanitizers.php';

$custom_controls_file = dirname( __FILE__ ) . '/inc/custom-controls.php';

APP_Theme_Framework_Autoload::add_class_map( array(
	'APP_Customizer_Custom_Control'                => $custom_controls_file,
	'APP_Customizer_Image_Checkbox_Control'        => $custom_controls_file,
	'APP_Customizer_Text_Radio_Button_Control'     => $custom_controls_file,
	'APP_Customizer_Image_Radio_Button_Control'    => $custom_controls_file,
	'APP_Customizer_Single_Accordion_Control'      => $custom_controls_file,
	'APP_Customizer_Simple_Notice_Control'         => $custom_controls_file,
	'APP_Customizer_Slider_Control'                => $custom_controls_file,
	'APP_Customizer_Toggle_Switch_Control'         => $custom_controls_file,
	'APP_Customizer_Sortable_Repeater_Control'     => $custom_controls_file,
	'APP_Customizer_Dropdown_Select2_Control'      => $custom_controls_file,
	'APP_Customizer_Dropdown_Posts_Control'        => $custom_controls_file,
	'APP_Customizer_TinyMCE_Control'               => $custom_controls_file,
	'APP_Customizer_Google_Font_Select_Control'    => $custom_controls_file,
	'APP_Customizer_Customize_Alpha_Color_Control' => $custom_controls_file,
) );

// Uncomment to enable samples.
//require_once dirname( __FILE__ ) . '/examples/functions.php';
