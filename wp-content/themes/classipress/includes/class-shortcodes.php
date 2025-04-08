<?php
/**
 * Shortcode classes.
 *
 * @package ClassiPress\Shortcodes
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * ClassiPress Shortcodes.
 *
 * @since 4.0.0
 */
class CP_Shortcodes {

	/**
	 * Constructor
	 */
	public function __construct() {

		add_shortcode( 'classipress_listing_categories',  array( $this, 'output_listing_categories' ) );
		add_shortcode( 'classipress_typed_elements', array( $this, 'typed_elements' ) );
		add_shortcode( 'classipress_searchbar', array( $this, 'searchbar' ) );
		add_shortcode( 'classipress_tag_cloud', array( $this, 'tag_cloud' ) );
		add_shortcode( 'classipress_share_button', array( $this, 'share_button' ) );
		add_shortcode( 'classipress_listings_map', array( $this, 'listings_map' ) );
	}

	/**
	 * Listing categories shortcode
	 *
	 * [classipress_listing_categories]
	 *
	 * Displays a hierarchical list of all listing categories.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public function output_listing_categories( $atts ) {

		$args = shortcode_atts( array(
				'id'               => '',
				'classes'          => 'listing-cats listing-cats-page row',
				'taxonomy'         => APP_TAX_CAT,
				'menu_cols'        => 1,
				'menu_depth'       => 3,
				'menu_sub_num'     => 3,
				'cat_parent_count' => 0,
				'cat_child_count'  => 0,
				'cat_hide_empty'   => 0,
				'show_bg_image'    => 0,
				'show_bg_color'    => 0,
				'show_icon'        => 0,
				'cat_nocatstext'   => true,
				'cat_include'      => false,
			), $atts
		);

		if ( 1 < $args['menu_cols'] ) {
			$large_cols  = $args['menu_cols'];
			$medium_cols = ( 2 <= $large_cols ) ? 2 : 1;
			$args['classes'] .= 'small-up-1 medium-up-' . $medium_cols . ' large-up-' . $large_cols;
		}

		if ( ! get_taxonomy( $args['taxonomy'] ) ) {
			return __( 'Wrong taxonomy name!', APP_TD );
		}

		if ( ! empty( $args['id'] ) ) {
			$wrapper_id = ' id="' . esc_attr( $args['id'] ) . '"';
		} else {
			$wrapper_id = '';
		}

		ob_start();
		?>
			<div<?php echo $wrapper_id; ?> class="<?php echo esc_attr( $args['classes'] ); ?>">
				<?php echo cp_create_categories_list( 'shortcode', $args ); ?>
			</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get the typed elements shortcode.
	 *
	 * Usage: [classipress_typed_elements text="hello dolly, wasssup fool??, and more stuff"]
	 *
	 * @since 4.0.0
	 *
	 * @param array $atts The shortcode arguments.
	 */
	public function typed_elements( $atts ) {

		extract( shortcode_atts( array(
				'text' => '',
			), $atts
		) );

		if ( ! $text ) {
			return;
		}

		$text_array = array_map( 'trim', explode( ',', strip_tags( $text ) ) );

		return '<script>/* <![CDATA[ */var typedStrings = ' . json_encode( $text_array ) . '/* ]]> */</script>';
	}


	/**
	 * Listing categories shortcode
	 *
	 * Displays a hierarchical list of all listing categories.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 * @param array $args
	 * @return string
	 */
	public function searchbar( $atts ) {

		extract( shortcode_atts( array(
				'id' => '',
			), $atts
		) );

		ob_start();
		?>
		<div class="searchbar-shortcode row column">
			<div class="column">
				<?php get_template_part( 'searchform-listing' ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Listing Tags and Categories Cloud shortcode
	 *
	 * Displays a list of given taxonomy terms.
	 *
	 * @since 4.0.0

	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function tag_cloud( $atts = array() ) {

		$args = shortcode_atts( array(
			'taxonomy'   => APP_TAX_TAG,
			'number'     => 10,
			'smallest'   => 1,
			'largest'    => 1,
			'unit'       => 'rem',
			'orderby'    => 'count',
			'order'      => 'DESC',
			'show_count' => false,
			'exclude'    => '',
			'include'    => '',
		), $atts );

		$args = array_merge( $args, array(
			'format' => 'array',
		) );

		$output = array();
		$tags   = wp_tag_cloud( $args );

		foreach ( $tags as $tag ) {
			$output[] = html( 'span class="label"', $tag );
		}

		$output = implode( "\n", $output );

		ob_start();
		?>
		<div class="tag-cloud-shortcode row column">
			<div class="column">
				<?php echo $output; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generates a link to share current page in a social networks
	 *
	 * @since 4.0.0

	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function share_button( $atts = array() ) {

		$output = '';
		$args   = shortcode_atts( array(
			'icon'      => '',
			'share_url' => '',
			'title'     => '',
		), $atts );

		if ( ! $args['icon'] || ! $args['share_url'] ) {
			return $output;
		}

		$output = html( 'a', array(
			'href'   => $args['share_url'] . get_permalink(),
			'title'  => $args['title'],
			'target' => '_blank',
			'class'  => 'fa-icon ' . $args['icon'],
		), '' );

		return $output;
	}

	/**
	 * Listings map shortcode
	 *
	 * Displays a map with all found listings on the page.
	 *
	 * @since 4.1.0
	 *
	 * @access public
	 * @param array $atts
	 * @return string
	 */
	public function listings_map( $atts ) {

		$args = shortcode_atts( array(
				'id' => 'listing-map-' . substr( sha1( time() . mt_rand( 0, 1000 ) ), 0, 20 ),
			), $atts
		);

		ob_start();
		?>
			<div id="<?php echo esc_attr( $args['id'] ); ?>-canvas" class="listing-map"></div>
		<?php
		return ob_get_clean();
	}
}
