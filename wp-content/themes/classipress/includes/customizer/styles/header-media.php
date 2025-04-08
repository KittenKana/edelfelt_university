<?php
/**
 * Customizer header edia front-end CSS functionality
 *
 * @package ClassiPress
 *
 * @since 4.2.0
 */

/**
 * Enqueues front-end CSS for the custom top bar background color.
 *
 * @since 4.2.0
 */
function cp_header_media_overlay_background_color_css() {
	$default = 'rgba(0,0,0,0.35)';
	$color   = get_theme_mod( 'header_media_overlay_background_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	}

	try {
		new APP_PHPColors( $color );
	} catch ( Exception $exc ) {
		$color = $default;
	}

	$css = "
		/* ClassiPress Header Media Overlay Background Color */
		.custom-header-media:after {
			background-color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_media_overlay_background_color_css' );

/**
 * Enqueues front-end CSS for the custom header media alignment.
 *
 * @since 4.2.0
 */
function cp_header_media_alignment_css() {
	$default     = 'middle';
	$alignment_y = get_theme_mod( 'header_media_vertical_alignment', $default );
	$alignment_x = get_theme_mod( 'header_media_horizontal_alignment', $default );

	if ( $alignment_y === $default && $alignment_x === $default ) {
		return;
	}

	if ( 'left' === $alignment_x ) {
		$left        = 0;
		$right       = 'auto';
		$transform_x = 0;
		$position_x  = 0;
	} elseif ( 'right' === $alignment_x ) {
		$left        = 'auto';
		$right       = 0;
		$transform_x = 0;
		$position_x  = '100%';
	} else {
		$left        = '50%';
		$right       = 'auto';
		$transform_x = '-50%';
		$position_x  = '50%';
	}

	if ( 'top' === $alignment_y ) {
		$top         = 0;
		$bottom      = 'auto';
		$transform_y = 0;
		$position_y  = 0;
	} elseif ( 'bottom' === $alignment_y ) {
		$top         = 'auto';
		$bottom      = 0;
		$transform_y = 0;
		$position_y  = '100%';
	} else {
		$top         = '50%';
		$bottom      = 'auto';
		$transform_y = '-50%';
		$position_y  = '50%';
	}

	$css = "
		/* ClassiPress Header Media Alignment */
		.has-header-image .custom-header-media img,
		.has-header-video .custom-header-media video,
		.has-header-video .custom-header-media iframe {
			left: {$left};
			right: {$right};
			top: {$top};
			bottom: {$bottom};
			-webkit-transform: translateX({$transform_x}) translateY({$transform_y});
					transform: translateX({$transform_x}) translateY({$transform_y});
		}
		/* For browsers that support 'object-fit' */
		@supports ((-o-object-fit: cover) or (object-fit: cover)) {
			.has-header-image .custom-header-media img,
			.has-header-video .custom-header-media video {
				-o-object-position: {$position_x} {$position_y};
				object-position: {$position_x} {$position_y};
			}
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_media_alignment_css' );

/**
 * Customize video play/pause button in the custom header.
 *
 * @param array $settings Video settings.
 * @return array The filtered video settings.
 */
function cp_header_video_controls( $settings ) {
	$settings['l10n']['play']  = '<span class="screen-reader-text">' . __( 'Play background video', APP_TD ) . '</span><i class="fa fa-play"></i>';
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . __( 'Pause background video', APP_TD ) . '</span><i class="fa fa-pause"></i>';
	return $settings;
}
add_filter( 'header_video_settings', 'cp_header_video_controls' );
