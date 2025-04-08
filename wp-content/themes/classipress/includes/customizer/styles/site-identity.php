<?php
/**
 * Customizer site identity front-end CSS functionality
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

/**
 * Enqueues front-end CSS for the header site title.
 *
 * @since 4.0.0
 */
function cp_header_text_site_title_css() {
	$header_text_site_title = get_theme_mod( 'header_text_site_title' );

	// Don't do anything if the current color is the default.
	if ( $header_text_site_title === 1 ) {
		return;
	}

	$css = '
		/* Header Site Title */
		.header .site-title {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	';

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_text_site_title_css' );


/**
 * Enqueues front-end CSS for the header site tagline.
 *
 * @since 4.0.0
 */
function cp_header_text_site_tagline_css() {
	$header_text_site_tagline = get_theme_mod( 'header_text_site_tagline', 1 );

	// Don't do anything if the current color is the default.
	if ( $header_text_site_tagline === 1 ) {
		return;
	}

	$css = '
		/* Header Site Tagline */
		.header .site-description {
			clip: rect(1px, 1px, 1px, 1px);
			position: absolute;
		}
	';

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_text_site_tagline_css' );
