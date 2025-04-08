<?php
/**
 * Customizer core theme functionality.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

/**
 * Setup the ClassiPress customizer class.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */
class CP_Customizer {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register',                 array( $this, 'custom_panels' ), 10 );
		add_action( 'customize_register',                 array( $this, 'custom_sections' ), 20 );
		add_action( 'after_setup_theme',                  array( $this, 'custom_styles' ) );
		add_action( 'customize_preview_init',             array( $this, 'cp_customize_preview_js' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'cp_customize_controls_js' ) );

		add_filter( 'body_class',                         array( $this, 'custom_body_class' ) );
	}

	/**
	 * Load custom panels.
	 *
	 * @since 4.0.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customize Manager object.
	 *
	 * @return void
	 */
	public function custom_panels( $wp_customize ) {
		foreach ( glob( dirname( __FILE__ ) . '/panels/*.php' ) as $file ) {
			include_once( $file );
		}
	}

	/**
	 * Load custom sections.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function custom_sections( $wp_customize ) {
		foreach ( glob( dirname( __FILE__ ) . '/sections/*.php' ) as $file ) {
			include_once( $file );
		}
	}

	/**
	 * Load custom styles for the front-end.
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 */
	public function custom_styles( $wp_customize ) {
		foreach ( glob( dirname( __FILE__ ) . '/styles/*.php' ) as $file ) {
			include_once( $file );
		}
	}

	/**
	 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
	 *
	 * @since 4.0.0
	 */
	public function cp_customize_preview_js() {
		wp_enqueue_script( 'theme-customize-preview', get_template_directory_uri() . '/assets/js/customize-preview.js', array( 'customize-preview' ), CP_VERSION, true );
	}

	/**
	 * Load dynamic logic for the customizer controls area.
	 *
	 * @since 4.0.0
	 */
	public function cp_customize_controls_js() {
		wp_enqueue_script( 'theme-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array(), CP_VERSION, true );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 4.0.0
	 *
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	function custom_body_class( $classes ) {

		// Add class if we're viewing the Customizer for easier styling of theme options.
		if ( is_customize_preview() ) {
			$classes[] = 'cp-customizer';
		}

		// Add a class if there is a custom header.
		if ( has_header_image() ) {
			$classes[] = 'has-header-image';
		}

		// Add class if header is sticky.
		if ( get_theme_mod( 'sticky_top_bar' ) || get_theme_mod( 'sticky_title_bar' ) || get_theme_mod( 'sticky_nav_bar' ) ) {
			$classes[] = 'sticky-header';

			// Shrinking non sticky nav bars.
			foreach ( array( 'sticky_top_bar', 'sticky_title_bar', 'sticky_nav_bar' ) as $navbar ) {
				if ( ! get_theme_mod( $navbar ) ) {
					$classes[] = 'shrink_' . $navbar;
				}
			}
		}

		// Get the color scheme or the default if there isn't one.
		$colors = get_theme_mod( 'color_scheme', 'red' );
		$classes[] = 'theme-' . $colors;

		return $classes;
	}

}
