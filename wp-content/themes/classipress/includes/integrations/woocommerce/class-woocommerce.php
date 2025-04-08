<?php
/**
 * WooCommerce Plugin integration.
 *
 * @url https://wordpress.org/plugins/woocommerce/
 *
 * @package ClassiPress\Integrations
 * @author AppThemes
 * @since 4.1.0
 */

/**
 * Override certain features from this plugin.
 */
class CP_WooCommerce {

	/**
	 * Constructor
	 */
	public function __construct() {

		// Check if WooCommerce is active, otherwise bail.
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}

		add_action( 'after_setup_theme',  array( $this, 'after_setup_theme' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Eventually use all our own styles. For now, override in css file.
		// add_filter( 'woocommerce_enqueue_styles', array( $this, 'dequeue_styles' ) );

		add_filter( 'wp_nav_menu_items', array( $this, 'cart_icon' ), 0, 2 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_count_update' ) );
	}

	/**
	 * Declare support for WooCommerce.
	 *
	 * @since 4.0.0
	 */
	public function after_setup_theme() {
		add_theme_support( 'woocommerce' );
	}

	public function enqueue_scripts() {

		// Handle must match main theme stylesheet handle to work.
		wp_add_inline_style( 'at-main', $this->inline_css() );
	}


	/**
	 * Dequeue core stylesheets so we can use our own.
	 *
	 * @since 4.0.0
	 *
	 * @param array $enqueue_styles The array of stylesheets.
	 * @return array
	 */
	public function dequeue_styles( $enqueue_styles ) {

		unset( $enqueue_styles['woocommerce-general'] );
		unset( $enqueue_styles['woocommerce-layout'] );
		unset( $enqueue_styles['woocommerce-smallscreen'] );

		return $enqueue_styles;
	}

	/**
	 * Include inline css.
	 *
	 * @since 4.0.0
	 *
	 * @return string Inline css
	 */
	public function inline_css() {

		/**
		 * Filters the default stylesheet src.
		 *
		 * @since 4.0.0
		 *
		 * @param string The stylesheet src.
		 */
		$style_path = apply_filters( 'cp_woocommerce_style_src', get_template_directory_uri() . '/includes/integrations/woocommerce/style.css' );

		return file_get_contents( $style_path );
	}

	/**
	 * Adds a shopping cart icon to the navigation menu.
	 *
	 * @since 4.0.0
	 *
	 * @see wp_nav_menu()
	 *
	 * @param string $items The HTML list content for the menu items.
	 * @param object $args  An object containing wp_nav_menu() arguments.
	 */
	public function cart_icon( $items, $args ) {

		if ( 'primary' != $args->theme_location ) {
			return $items;
		}

		$output  = '<li class="menu-item menu-type-link menu-item-type-woocommerce-cart">';
		$output .= '<a href="' . esc_url( wc_get_cart_url() ) . '" class="current-cart" rel="nofollow"><i class="fa fa-shopping-cart" aria-hidden="true"></i>' . $this->cart_count() . '</a>';
		$output .= '</li>';

		return $output . $items;
	}

	 /**
 	 * Update cart total in real time via ajax.
 	 *
 	 * @since 4.0.0
 	 *
 	 * @param array $fragments The array of ajax elements
 	 */
	public function cart_count_update( $fragments ) {
		$fragments['span.current-cart-count'] = $this->cart_count();
		return $fragments;
	}

	/**
	 * Adds the cart total next to the nav menu shopping cart icon.
	 *
	 * @since 4.0.0
	 *
	 * @return string
	 */
	public function cart_count() {
		return '<span class="current-cart-count info badge">' . wc()->cart->cart_contents_count . '</span>';
	}

}
