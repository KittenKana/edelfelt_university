<?php
/**
 * Customizer colors front-end CSS functionality
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

/**
 * Enqueues front-end CSS for the custom top bar background color.
 *
 * @since 4.2.0
 */
function cp_header_top_background_color_css() {
	$default = '#FFFFFF';
	$color   = get_theme_mod( 'header_top_background_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	}

	try {
		$php_colors = new APP_PHPColors( $color );
	} catch ( Exception $exc ) {
		$color      = $default;
		$php_colors = new APP_PHPColors( $color );
	}

	$css = "
		/* ClassiPress Header Top Bar Background Color */
		.header #first-top-bar {
			background-color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_top_background_color_css' );

/**
 * Enqueues front-end CSS for the custom top bar text color.
 *
 * @since 4.2.0
 */
function cp_header_top_menu_color_css() {
	$default = '#565656';
	$color   = get_theme_mod( 'top_header_menu_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.
	$php_colors = new APP_PHPColors( $color );

	// It's missing the hash so append it.
	$hover_color = '#' . $php_colors->smart_scale();

	$css = "
		/* ClassiPress Header Top Bar Links Color */
		.header #first-top-bar nav > ul > li > a {
			color: {$color};
		}
		.header #first-top-bar nav > ul > li > a:focus, .header #first-top-bar nav > ul > li > a:hover, .header #first-top-bar nav > ul > li > a:active {
			color: {$hover_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_top_menu_color_css' );

/**
 * Enqueues front-end CSS for the custom header background color.
 *
 * @since 4.0.0
 */
function cp_header_background_color_css() {
	$default = '#FFFFFF';
	$color   = get_theme_mod( 'header_background_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.

	try {
		$php_colors = new APP_PHPColors( $color );
	} catch ( Exception $exc ) {
		$color      = $default;
		$php_colors = new APP_PHPColors( $color );
	}

	$sep_color = new APP_PHPColors( $php_colors->darken( 5 ) );

	$sep_color->alpha = $php_colors->alpha;

	$css = "
		/* ClassiPress Header Background Color */
		.header #top-bar-primary {
			background-color: {$color};
		}
		.header #top-bar-primary {
			border-bottom: 1px solid {$sep_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_background_color_css' );

/**
 * Enqueues front-end CSS for the custom header text color.
 *
 * @since 4.1.0
 */
function cp_header_text_color_css() {
	$default = '#565656';
	$color   = get_theme_mod( 'header_text_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	$css = "
		/* ClassiPress Primary Header Text Color */
		#top-bar-primary {
			color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_text_color_css' );

/**
 * Enqueues front-end CSS for the custom header text color.
 *
 * @since 4.2.0
 */
function cp_header_middle_menu_color_css() {
	$default = '#8A8A8A';
	$color   = get_theme_mod( 'middle_header_menu_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.
	$php_colors = new APP_PHPColors( $color );

	// It's missing the hash so append it.
	$hover_color = '#' . $php_colors->darken();

	$css = "
		/* ClassiPress Primary Header Links Color */
		#top-bar-primary li.menu-item a {
			color: {$color};
		}
		#top-bar-primary li.menu-item a:focus, #top-bar-primary li.menu-item a:hover {
			color: {$hover_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_middle_menu_color_css' );

/**
 * Enqueues front-end CSS for the custom bottom bar background color.
 *
 * @since 4.2.0
 */
function cp_header_bottom_background_color_css() {
	// Use primary theme color as default one.
	$color = get_theme_mod( 'header_bottom_background_color' );

	if ( ! $color ) {
		return;
	}

	$css = "
		/* ClassiPress Header Bottom Bar Background Color */
		.header #top-bar-secondary {
			background-color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_bottom_background_color_css' );

/**
 * Enqueues front-end CSS for the custom bottom bar text color.
 *
 * @since 4.2.0
 */
function cp_header_bottom_menu_color_css() {
	$default = '';
	$color   = get_theme_mod( 'bottom_header_menu_color', $default );

	// Don't do anything if the current color is the default.
	if ( empty( $color ) ) {
		return;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.
	$php_colors = new APP_PHPColors( $color );

	// It's missing the hash so append it.
	$hover_color = '#' . $php_colors->smart_scale();

	$css = "
		/* ClassiPress Header Bottom Bar Links Color */
		.header #top-bar-secondary #menu-header > .menu-item > a {
			color: {$color};
		}
		.header #top-bar-secondary #menu-header > .menu-item > a:focus, .header #top-bar-secondary #menu-header > .menu-item > a:hover, .header #top-bar-secondary #menu-header > .menu-item > a:active {
			color: {$hover_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_header_bottom_menu_color_css' );

/**
 * Enqueues front-end CSS for the sticky header background color.
 *
 * @since 4.2.0
 */
function cp_sticky_header_background_color_css() {

	$color = get_theme_mod( 'sticky_header_background_color' );

	if ( ! $color ) {
		return;
	}

	try {
		$php_colors = new APP_PHPColors( $color );
	} catch ( Exception $exc ) {
		return;
	}

	$sep_color = new APP_PHPColors( $php_colors->darken( 5 ) );

	$sep_color->alpha = $php_colors->alpha;

	$css = "
		/* ClassiPress Sticky Header Background Color */
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary,
		.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary,
		.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar {
			background-color: {$color};
		}
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary {
			border-bottom: 1px solid {$sep_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_sticky_header_background_color_css' );

/**
 * Enqueues front-end CSS for the sticky header text color.
 *
 * @since 4.2.0
 */
function cp_sticky_header_text_color_css() {
	$default = '';
	$color   = get_theme_mod( 'sticky_header_text_color', $default );

	// Don't do anything if the current color is the default.
	if ( empty( $color ) ) {
		return;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.
	$php_colors = new APP_PHPColors( $color );

	// It's missing the hash so append it.
	$hover_color = '#' . $php_colors->smart_scale();

	$css = "
		/* ClassiPress Sticky Header Text Color */
		.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar nav > ul > li > a,
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary,
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary li.menu-item a,
		.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary #menu-header > .menu-item > a {
			color: {$color};
		}
		.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar nav > ul > li > a:focus,
		.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar nav > ul > li > a:hover,
		.sticky-header:not(.shrink_sticky_top_bar) #sticky_header.is-stuck #first-top-bar nav > ul > li > a:active,
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary li.menu-item a:focus,
		.sticky-header:not(.shrink_sticky_title_bar) #sticky_header.is-stuck #top-bar-primary li.menu-item a:hover,
		.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary #menu-header > .menu-item > a:focus,
		.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary #menu-header > .menu-item > a:hover,
		.sticky-header:not(.shrink_sticky_nav_bar) #sticky_header.is-stuck #top-bar-secondary #menu-header > .menu-item > a:active {
			color: {$hover_color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_sticky_header_text_color_css' );

/**
 * Enqueues front-end CSS for the custom global text color.
 *
 * @since 4.2.0
 */
function cp_global_text_color_css() {
	$default = '#565656';
	$color   = get_theme_mod( 'global_text_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	$css = "
		/* ClassiPress Global Text Color */
		body {
			color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_global_text_color_css' );

/**
 * Enqueues front-end CSS for the custom footer background color.
 *
 * @since 4.0.0
 */
function cp_footer_background_color_css() {
	$default = '#313131';
	$color   = get_theme_mod( 'footer_background_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	$css = "
		/* ClassiPress Footer Background Color */
		#footer {
			background-color: {$color};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_footer_background_color_css' );

/**
 * Enqueues front-end CSS for the custom footer text color.
 *
 * @since 4.2.0
 */
function cp_footer_text_color_css() {
	$default = '#FFFFFF';
	$color   = get_theme_mod( 'footer_text_color', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.

	try {
		$php_colors = new APP_PHPColors( $color );
	} catch ( Exception $exc ) {
		$color      = $default;
		$php_colors = new APP_PHPColors( $color );
	}

	$php_colors->alpha = 0.7;
	$lighten_7         = "$php_colors";
	$php_colors->alpha = 0.4;
	$lighten_4         = "$php_colors";
	$php_colors->alpha = 0.1;
	$lighten_1         = "$php_colors";

	$css = "
		/* ClassiPress Footer Text Color */
		#footer {
			color: {$lighten_7};
		}

		.site-footer a,
		#footer abbr,
		#footer acronym {
			color: {$lighten_7};
		}
		.site-footer a:hover, .site-footer a:focus, .site-footer a:active,
		#footer abbr:hover,
		#footer abbr:focus,
		#footer abbr:active,
		#footer acronym:hover,
		#footer acronym:focus,
		#footer acronym:active {
			color: {$color};
		}

		.footer-top .widget-title {
			color: {$color};
		}

		#footer .divider {
			border-bottom: 1px solid {$lighten_1};
		}

		.footer-bottom .social-media li .fa:hover {
			color: {$color};
		}

		.footer-bottom .copyright {
			color: {$lighten_4};
		}
	";

	// Handle must match main theme stylesheet handle to work.
	wp_add_inline_style( 'at-main', $css );
}
add_action( 'wp_enqueue_scripts', 'cp_footer_text_color_css' );

/**
 * Enqueues front-end CSS for the custom color scheme.
 *
 * We can't use wp_enqueue_scripts & wp_add_inline_style b/c
 * this loads the custom css after style.css so it causes inheritance
 * bleed through on colors. Need to use wp_head instead to solve issue.
 * TwentySeventeen does the same thing.
 *
 * @since 4.0.0
 */
function cp_color_scheme_css() {
	$default = '#1AB394';
	$color   = get_theme_mod( 'color_scheme_custom', $default );

	// Don't do anything if the current color is the default.
	if ( $color === $default ) {
		return;
	} elseif ( empty( $color ) || 0 !== stripos( $color, '#' ) ) {
		$color = $default;
	}

	// Use a hex color generator to create our hover
	// color based off the custom color_scheme.
	$php_colors = new APP_PHPColors( $color );

	$lighten_color = '#' . $php_colors->lighten( 20 );

	if ( $php_colors->isDark() ) {
		$foreground  = '#FEFEFE';
		$smart_scale = '#' . $php_colors->darken( 5 );
	} else {
		$foreground  = '#0A0A0A';
		$smart_scale = '#' . $php_colors->lighten( 5 );
	}

	$hover_color = $smart_scale;

	$css = "
		/* ClassiPress Custom Color Scheme */
		.theme-custom a {
			color: {$color};
		}
		.theme-custom a:hover, .theme-custom a:focus {
			color: {$hover_color};
		}
		.theme-custom .button {
			background-color: {$color};
			color: {$foreground};
		}
		.theme-custom .button:hover, .theme-custom .button:focus {
			background-color: {$hover_color};
			color: {$foreground};
		}
		.theme-custom .button.primary {
			background-color: {$color};
		}
		.theme-custom .button.primary:hover, .theme-custom .button.primary:focus {
			background-color: {$hover_color};
			color: {$foreground};
		}
		.theme-custom .button.hollow {
			border-color: {$color};
			color: {$color};
			background-color: transparent;
		}
		.theme-custom .button.hollow:hover, .theme-custom .button.hollow:focus {
			background-color: {$hover_color};
			border-color: {$hover_color};
			color: {$foreground};
		}
		.theme-custom .button.hollow.primary {
			border-color: {$color};
			color: {$color};
		}
		.theme-custom .button.hollow.primary:hover, .theme-custom .button.hollow.primary:focus {
			border-color: {$hover_color};
			color: {$hover_color};
		}
		.theme-custom .button.disabled:hover, .theme-custom .button.disabled:focus {
			background-color: {$color};
		}
		.theme-custom .button.disabled.primary:hover, .theme-custom .button.disabled.primary:focus {
			background-color: {$color};
		}
		.theme-custom .accordion-title {
			color: {$color};
		}
		.theme-custom .is-accordion-submenu-parent > a::after {
			border-color: {$color} transparent transparent;
		}
		.theme-custom .badge {
			background: {$color};
		}
		.theme-custom .breadcrumbs a {
			color: {$color};
		}
		.theme-custom .button-group.primary .button {
			background-color: {$color};
		}
		.theme-custom .button-group.primary .button:hover, .theme-custom .button-group.primary .button:focus {
			background-color: {$hover_color};
			color: {$foreground};
		}
		.theme-custom .menu .active > a {
			background: {$color};
		}
		.theme-custom .is-drilldown-submenu-parent > a::after {
			border-color: transparent transparent transparent {$color};
		}
		.theme-custom .js-drilldown-back > a::before {
			border-color: transparent {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu > li.is-dropdown-submenu-parent > a::after {
			border-color: {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.vertical > li.opens-left > a::after {
			border-color: transparent {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$color};
		}
		.theme-custom .dropdown.menu.medium-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.medium-vertical > li.opens-left > a::after {
			border-color: transparent {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.medium-vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$color};
		}
		.theme-custom .dropdown.menu.large-horizontal > li.is-dropdown-submenu-parent > a::after {
			border-color: {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.large-vertical > li.opens-left > a::after {
			border-color: transparent {$color} transparent transparent;
		}
		.theme-custom .dropdown.menu.large-vertical > li.opens-right > a::after {
			border-color: transparent transparent transparent {$color};
		}
		.theme-custom .is-dropdown-submenu .is-dropdown-submenu-parent.opens-left > a::after {
			border-color: transparent {$color} transparent transparent;
		}
		.theme-custom .is-dropdown-submenu .is-dropdown-submenu-parent.opens-right > a::after {
			border-color: transparent transparent transparent {$color};
		}
		.theme-custom .pagination .current {
			background: {$color};
		}
		.theme-custom .pagination .current a:hover, .theme-custom .pagination .current a:focus, .theme-custom .pagination .current a:active {
			color: inherit;
			background: {$color};
		}
		.theme-custom .progress.primary .progress-meter {
			background-color: {$color};
		}
		.theme-custom .progress-meter {
			background-color: {$color};
		}
		.theme-custom .slider-handle {
			background-color: {$color};
		}
		.theme-custom input:checked ~ .switch-paddle {
			background: {$color};
		}
		.theme-custom .tabs.primary {
			border-bottom: 3px solid {$color} !important;
		}
		.theme-custom .tabs.primary > .tabs-title > a[aria-selected='true'] {
			background: {$color};
			color: {$foreground};
		}
		.theme-custom .tabs.primary > .tabs-title > a:hover, .theme-custom .tabs.primary > .tabs-title > a:focus {
			background: {$hover_color};
			color: {$foreground};
		}
		.theme-custom .progress-indicator > li.is-complete {
			color: {$color};
		}
		.theme-custom .progress-indicator > li.is-complete::before, .theme-custom .progress-indicator > li.is-complete::after {
			background: {$color};
		}
		.theme-custom .progress-indicator > li.is-complete span {
			color: {$color};
		}
		.theme-custom .progress-indicator > li.is-current {
			color: {$lighten_color};
		}
		.theme-custom .progress-indicator > li.is-current::before {
			background: {$lighten_color};
		}
		.theme-custom .progress-indicator > li.is-current span {
			color: {$lighten_color};
		}
		.theme-custom #top-bar-secondary {
			background-color: {$color};
		}
		.theme-custom #top-bar-secondary #menu-header > .menu-item > a {
			color: {$foreground};
		}
		.theme-custom #topcontrol {
			background: {$color};
		}
		.theme-custom #topcontrol:hover {
			background: {$hover_color};
		}
		.theme-custom .price-wrap .tag-head {
			background: {$color};
		}
		.theme-custom .refine-categories-list-label::after {
			border-color: {$color} transparent transparent;
		}
	";
?>
<style type="text/css" id="custom-theme-colors" <?php if ( is_customize_preview() ) { echo 'data-color="' . $color . '" data-foreground-color="' . $foreground . '" data-hover-color="' . $hover_color . '"'; } ?>>
	<?php
	/**
	 * Filters custom color CSS.
	 *
	 * @since 4.0.0
	 *
	 * @param $css         string Custom theme color CSS.
	 * @param $color       string The user's selected color hex.
	 * @param $hover_color string The calculated hover color hex.
	 * @param $foreground  string The calculated foreground color hex.
	 */
	echo apply_filters( 'cp_custom_colors_css', $css, $color, $hover_color, $foreground );
	?>
</style>
<?php
}
// Priority is very important! Above 7 equals css inheritance issues.
add_action( 'wp_head', 'cp_color_scheme_css', 7 );
