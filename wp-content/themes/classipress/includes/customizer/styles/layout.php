<?php
/**
 * Customizer layout front-end CSS functionality
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

/**
 * Enqueues front-end CSS for the custom header height.
 *
 * @since 4.2.0
 */
function cp_header_height_css() {
	$height = get_theme_mod( 'header_height', 0 );

	if ( ! $height ) {
		return;
	}

	$css = "
		/* ClassiPress Header Height */
		.header #top-bar-primary {
			padding-top: {$height}rem;
			padding-bottom: {$height}rem;
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_height_css' );

/**
 * Enqueues front-end CSS for the custom header alignment.
 *
 * @since 4.2.0
 */
function cp_header_vertical_alignment_css() {
	$default   = 'middle';
	$alignment = get_theme_mod( 'header_vertical_alignment', $default );

	if ( $alignment === $default ) {
		return;
	}

	$alignment = 'top' === $alignment ? 'flex-start' : 'flex-end';

	$css = "
		/* ClassiPress Header Alignment */
		.header #top-bar-primary .primary-header-wrap {
			align-items: {$alignment};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_vertical_alignment_css' );
