<?php
/**
 * Customizer fonts front-end CSS functionality
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

/**
 * Enqueues front-end CSS for the global font size.
 */
function cp_customizer_fonts_css() {
	$css = '';

	$global_default = 1;
	$global_size = get_theme_mod( 'global_font_size', $global_default );

	if ( $global_size !== $global_default ) {
		$global_size = empty( $global_size ) ? $global_default : $global_size;
		$css .= "
			html {
				font-size: {$global_size}rem;
			}
		";
	}

	$body_font_default = json_encode( array(
		'font' => 'Lato',
		'regularweight' => 'regular',
		'italicweight' => 'italic',
		'boldweight' => '900',
		'category' => 'sans-serif',
	) );

	$body_font = get_theme_mod( 'global_font_family', $body_font_default );

	if ( $body_font !== $body_font_default ) {
		$body_font = empty( $body_font ) ? $body_font_default : $body_font;
		$body_font = json_decode( $body_font );
		$css .= "
			body {
				font-family: '{$body_font->font}', {$body_font->category};
			}
		";
	}

	$header_font_default = json_encode( array(
		'font' => 'Lato',
		'regularweight' => 'regular',
		'italicweight' => 'italic',
		'boldweight' => '900',
		'category' => 'sans-serif',
	) );

	$header_font = get_theme_mod( 'header_font_family', $header_font_default );

	if ( $header_font !== $header_font_default ) {
		$header_font = empty( $header_font ) ? $header_font_default : $header_font;
		$header_font = json_decode( $header_font );
		$css .= "
			.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
				font-family: '{$header_font->font}', {$header_font->category};
			}
		";
	}

	// Don't do anything if the current settings are the default.
	if ( empty( $css ) ) {
		return;
	}

	$css = "
		/* ClassiPress Fonts */
		$css
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_customizer_fonts_css' );
