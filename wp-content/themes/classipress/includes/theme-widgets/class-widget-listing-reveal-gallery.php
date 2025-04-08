<?php
/**
 * Widget for single listing image modal gallery.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing gallery widget class.
 */
class CP_Widget_Listing_Reveal_Gallery extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
    		global $post;
	    	$photo_gallery_title = cp_kana_get_header_info();
 
            
            $price = cp_get_price( $post->ID, 'cp_price', false );
		$default_args = array(
			'id_base'        => 'cp_widget_listing_reveal_gallery',
			'name'           => __( 'ClassiPress - Single Listing Photo Gallery', APP_TD ),
            
			'defaults'       => array(
				'title' => __( "$photo_gallery_title", APP_TD ),
			),
			'widget_ops'     => array(
				'description' => __( 'Display the listing photo gallery.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * Echo the widget content.
	 *
	 * @see WP_Widget
	 * @access public
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {
		global $post;

		if ( empty( $post ) ) {
			return;
		}

		$extra_args = array(
			'post_parent' => $post->ID,
		);

		// Exclude the featured image since it's the hero background.
		//$featured_id = get_post_meta( $post->ID, '_cp_banner_image', true );

		$default_args = array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'post_status'    => 'inherit',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		);

		//if ( apply_filters( 'cp_allow_listing_banner_image', true ) ) {
			//$default_args['post__not_in'] = array( $featured_id );
		//}

		$query_args = array_merge( $default_args, $extra_args );
		$images     = new WP_Query( $query_args );

		// Don't show the widget if no images exist.
		if ( ! $images->post_count ) {
			return;
		}

		$instance['query'] = $images;

		parent::widget( $args, $instance );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {
		global $wp_query;

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'listing-reveal-gallery' );
	}
}