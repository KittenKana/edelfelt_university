<?php
/**
 * ClassiPress child theme functions.
 *
 * BEFORE USING: Move the classiPress-child theme into the /themes/ folder.
 *
 * @package ClassiPress\Functions
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

/**
 * Registers the stylesheet for the child theme.
 */
function classipress_child_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_uri() );

	// Disable the ClassiPress default styles.
	//wp_dequeue_style( 'at-main' );

	// Disable the Foundation framework styles.
	//wp_dequeue_style( 'foundation' );
}
add_action( 'wp_enqueue_scripts', 'classipress_child_styles', 999 );

/**
 * Registers the scripts for the child theme.
 */
function classipress_child_scripts() {
	wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/general.js' );

	// Disable the ClassiPress default scripts.
	//wp_dequeue_script( 'theme-scripts' );

	// Disable the Foundation framework scripts.
	//wp_dequeue_script( 'foundation' );
	//wp_dequeue_script( 'foundation-motion-ui' );
}
add_action( 'wp_enqueue_scripts', 'classipress_child_scripts', 999 );

/**
 * This function migrates parent theme mods to the child theme.
 */
function classipress_child_assign_mods_on_activation() {

	if ( empty( get_theme_mod( 'migrated_from_parent' ) ) ) {
		$theme = get_option( 'stylesheet' );
		update_option( "theme_mods_$theme", get_option( 'theme_mods_classipress' ) );
		set_theme_mod( 'migrated_from_parent', 1 );
	}
}
add_action( 'after_switch_theme', 'classipress_child_assign_mods_on_activation' );

// You can add you own actions, filters and code below.
