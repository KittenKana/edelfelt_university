<?php
/**
 * Enqueue of scripts and styles.
 *
 * @package ClassiPress\Enqueue
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

add_action( 'wp_enqueue_scripts', 'cp_load_scripts' );
add_action( 'wp_enqueue_scripts', 'cp_style_changer', 11 );
add_action( 'wp_enqueue_scripts', 'cp_load_styles' );
add_action( 'wp_print_styles', '_cp_inline_styles', 99 );
add_action( 'wp_print_styles', 'cp_preload_styles' );

/**
 * Enqueue scripts.
 *
 * @return void
 */
if ( ! function_exists( 'cp_load_scripts' ) ) :
function cp_load_scripts() {
	global $cp_options;

	// Minimize prod or show expanded in dev.
	$min = cp_get_enqueue_suffix();

	// Set the assets path so we don't repeat ourselves.
	$assets_path = get_template_directory_uri() . '/assets';

	// Needed for single ad sidebar email & comments on pages, edit ad & profile pages, ads, blog posts.
	if ( is_singular() ) {
		wp_enqueue_script( 'validate' );
		wp_enqueue_script( 'validate-lang' );
	}

	// Search autocomplete and slider on certain pages.
	wp_enqueue_script( 'jquery-ui-autocomplete' );

	// Adds touch events to jQuery UI on mobile devices.
	if ( wp_is_mobile() ) {
		wp_enqueue_script( 'jquery-touch-punch' );
	}

	// Comment reply script for threaded comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	appthemes_load_map_provider();

	if ( wp_script_is( 'google-maps-api' ) ) {

		// Load the Marker Clusterer Plus script.
		wp_enqueue_script( 'markerclustererplus', $assets_path . "/js/lib/markerclustererplus/markerclusterer{$min}.js", array( 'google-maps-api' ), '2.1.4', true );

		// Load the Rich Marker script.
		wp_enqueue_script( 'infobubble', $assets_path . "/js/lib/infobubble/infobubble{$min}.js", array( 'google-maps-api' ), '0.8', true );
	}

	$listing_id = 0;

	if ( ! empty( $_GET['action'] ) && 'change' === $_GET['action'] ) {
		$checkout = appthemes_get_checkout();
		$listing_id = $checkout ? $checkout->get_data( 'listing_id' ) : false;
	} elseif( ! empty( $_GET['listing_renew'] ) ) {
		$listing_id = (int) $_GET['listing_renew'];
	}

	$params = array(
		'empty'    => __( 'Strength indicator', APP_TD ),
		'short'    => __( 'Very weak', APP_TD ),
		'bad'      => __( 'Weak', APP_TD ),
		'good'     => __( 'Medium', APP_TD ),
		'strong'   => __( 'Strong', APP_TD ),
		'mismatch' => __( 'Mismatch', APP_TD ),
	);
	wp_localize_script( 'password-strength-meter', 'pwsL10n', $params );

	// Load the Foundation script.
	wp_enqueue_script( 'foundation', $assets_path . "/js/lib/foundation/foundation{$min}.js", array( 'jquery' ), '6.2.4', true );

	// Load the Foundation Motion UI script.
	wp_enqueue_script( 'foundation-motion-ui', $assets_path . "/js/lib/foundation/motion-ui{$min}.js", array( 'jquery', 'foundation' ), '1.2.2', true );

	// Load the Typed script.
	wp_enqueue_script( 'typed', $assets_path . "/js/lib/typed/typed{$min}.js", array( 'jquery' ), '1.1.4', true );

	// Load the Slick script.
	wp_enqueue_script( 'slick', $assets_path . "/js/lib/slick/slick{$min}.js", array( 'jquery' ), '1.6.0', true );

	// Load the Scroll to Top script.
	wp_enqueue_script( 'scrolltotop', $assets_path . "/js/lib/scrolltotop/scrolltotop{$min}.js", array( 'jquery' ), '1.1.0', true );

	// Load the Chosen script in the footer.
	//wp_enqueue_script( 'chosen', $assets_path . "/js/lib/chosen/chosen.jquery{$min}.js", array( 'jquery' ), '1.6.2', true );

	// Load the theme script.
	wp_enqueue_script( 'theme-scripts', $assets_path . "/js/theme-scripts{$min}.js", array( 'jquery', 'masonry' ), CP_VERSION, true );

	/* Script variables */
	$params = array_merge( array(
		'ad_currency'       => $cp_options->curr_symbol,
		'currency_position' => $cp_options->currency_position,
		'ad_parent_posting' => $cp_options->ad_parent_posting,
		'listing_id'        => $listing_id,
		'ajax_url'          => admin_url( 'admin-ajax.php', 'relative' ),
		'appTaxTag'         => APP_TAX_TAG,
	), cp_theme_scripts_strings() );

	// Load any global options we want to access via js (e.g. cpSettings.ajaxurl).
	wp_localize_script( 'theme-scripts', 'cpSettings', $params );

	// Comment reply script for threaded comments.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
endif;


/**
 * Enqueue Add New page form scripts.
 *
 * @return void
 */
if ( ! function_exists( 'cp_load_form_scripts' ) ) :
function cp_load_form_scripts() {
	wp_enqueue_script( 'validate' );
	wp_enqueue_script( 'validate-lang' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}
endif;


/**
 * Enqueue color scheme styles.
 *
 * @return void
 */
if ( ! function_exists( 'cp_style_changer' ) ) :
function cp_style_changer() {

	wp_dequeue_style( 'app-form-progress' );

	// Leave it for backward compatibility.
	if ( file_exists( get_template_directory() . '/styles/custom.css' ) ) {
		wp_enqueue_style( 'at-custom', get_template_directory_uri() . '/styles/custom.css', false );
	}
}
endif;


/**
 * Enqueue styles.
 *
 * @return void
 */
if ( ! function_exists( 'cp_load_styles' ) ) :
function cp_load_styles() {

	// Minimize prod or show expanded in dev.
	$min = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// Set the assets path so we don't repeat ourselves.
	$assets_path = get_template_directory_uri() . '/assets';

	// Load the Foundation stylesheet.
	wp_enqueue_style( 'foundation', get_template_directory_uri() . "/assets/css/foundation{$min}.css", array(), '6.2.4' );

	// Load the rtl foundation stylesheet.
	wp_style_add_data( 'foundation', 'rtl', 'replace' );

	wp_style_add_data( 'foundation', 'suffix', $min );

	// Load the Slick stylesheet.
	wp_enqueue_style( 'slick', $assets_path . "/js/lib/slick/slick{$min}.css", array(), '1.6.0' );

	// Load the Slick theme stylesheet.
	wp_enqueue_style( 'slick-theme', $assets_path . "/js/lib/slick/slick-theme{$min}.css", array(), '1.6.0' );

	//wp_enqueue_style( 'chosen', get_template_directory_uri() . "/assets/js/lib/chosen/chosen{$min}.css", array(), '1.6.2' );

	// Load the font awesome toolkit from framework.
	wp_enqueue_style( 'font-awesome' );

	// Load the Google fonts.
	wp_enqueue_style( 'google-fonts', cp_google_fonts_url(), false );

	// Load the theme stylesheet.
	wp_enqueue_style( 'at-main', get_template_directory_uri() . "/assets/css/style{$min}.css", array('foundation'), CP_VERSION );

	// Load the rtl theme stylesheet.
	wp_style_add_data( 'at-main', 'rtl', 'replace' );

	/**
	 * We are replacing the existing file, and we use a
	 * suffix like `-min` so we need to let core know about it.
	 * That way, it can keep that suffix after the added `-rtl`.
	 * Without this code, we get 404 with this style.min-rtl.css.
	 */
	wp_style_add_data( 'at-main', 'suffix', $min );
}
endif;

/**
 * Build the url for Google fonts we use.
 *
 * @since 4.0.0
 */
function cp_google_fonts_url() {

	// Set the default Google fonts we want to use.
	$defaults = array(
		'Roboto' => array(
			'weights' => array( 400, 500 ),
		),
		'Sanchez' => '',
	);

	$body_font = get_theme_mod( 'global_font_family' );

	if ( empty( $body_font ) ) {
		$defaults['Lato'] = array(
			'weights' => array( 400, 900 ),
		);
	} else {
		$body_font = json_decode( $body_font );
		foreach ( array( 'regularweight', 'italicweight', 'boldweight' ) as $weight ) {
			if ( $body_font->$weight ) {
				$defaults[ $body_font->font ]['weights'][] = $body_font->$weight;
				$defaults[ $body_font->font ]['weights'] = array_unique( $defaults[ $body_font->font ]['weights'] );
			}
		}
	}

	$header_font = get_theme_mod( 'header_font_family' );

	if ( $header_font ) {
		$header_font = json_decode( $header_font );
		foreach ( array( 'regularweight', 'italicweight', 'boldweight' ) as $weight ) {
			if ( $header_font->$weight ) {
				$defaults[ $header_font->font ]['weights'][] = $header_font->$weight;
				$defaults[ $header_font->font ]['weights'] = array_unique( $defaults[ $header_font->font ]['weights'] );
			}
		}
	}

	/**
	 * Filters the default Google fonts used.
	 *
	 * @since 4.0.0
	 *
	 * @param array $defaults The default font values.
	 */
	$fonts = apply_filters( 'cp_google_font_args', $defaults );

	foreach ( $fonts as $font => $value ) {
		$weight = '';

		if ( isset( $value['weights'] ) ) {
			$weight = implode( ',', $value['weights'] );
		}

		$output     = ( ! empty( $weight ) ) ? ':' . $weight : '';
		$families[] = urlencode( $font . $output );
	}

	return add_query_arg( array( 'family' => implode( '|', $families ), /*'display' => 'swap'*/ ), '//fonts.googleapis.com/css' );
}

/**
 * Overrides known CSS styles loaded after the main stylesheet.
 */
function _cp_inline_styles() {

	// override Critic plugin CSS styles
	if ( wp_style_is('critic') ) {
		$custom_css = "
			#critic-review-wrap{margin-top: 0;}
			#critic-review-wrap .critic-reviews-title { margin-bottom:25px; }
			#critic-review-wrap .critic-review { margin-bottom:30px; }
			#criticform input[type='text'], #criticform textarea { width: 100%; }
		";

		wp_add_inline_style( 'critic', $custom_css );
	}

}

/**
 * Global text strings we want to access via js.
 *
 * Add any values we want to access via js.
 * Use var AppThemes to access ajaxurl or current_url.
 *
 * @since 4.0.0
 */
function cp_theme_scripts_strings() {

	$strings = array(
		'delete_item'           => __( 'Are you sure want to delete this item?', APP_TD ), // Edit user listings delete icon.
		'invalid_image_type'    => __( 'Invalid image type.', APP_TD ), // Edit user profile upload cover photo.
		//'image_placeholder'  => va_placeholder_img_src(), // Edit user profile upload cover photo.
	);

	/**
	 * Filters the theme script strings.
	 * Ideal for dynamic values and strings that require translation.
	 *
	 * @since 4.0.0
	 *
	 * @param array $strings List of theme script strings.
	 */
	return apply_filters( 'cp_theme_scripts_strings', $strings );
}

function cp_preload_styles() {
	if ( wp_style_is( 'font-awesome' ) ) {
		?>
		<link rel="preload" href="<?php echo esc_url( APP_THEME_FRAMEWORK_URI . '/lib/font-awesome/fonts/fontawesome-webfont.woff2?v=4.7.0' ); ?>" as="font" type="font/woff2" crossorigin="anonymous">
		<?php
	}
}
