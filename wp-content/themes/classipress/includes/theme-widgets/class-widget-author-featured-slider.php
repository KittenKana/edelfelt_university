<?php
/**
 * Featured listings widget class.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Featured listings widget class.
 *
 * @since 4.0.0
 */
class CP_Widget_Author_Featured_Slider extends CP_Widget_Listing_Featured_Slider {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'         => 'cp_widget_author_featured',
			'name'            => __( 'ClassiPress - Author Featured Listings', APP_TD ),
			'defaults'        => array(
				'header'         => __( 'Featured', APP_TD ),
				'layout'         => 'hero',
				'limit'          => 6,
				'show_button'    => false,
				'show_more'      => false,
				'show_desc'      => false,
				'show_number'    => 1,
				'scroll_number'  => 1,
				'fade'           => true,
				'autoplay'       => true,
				'autoplay_speed' => 3000,
				'speed'          => 800,
				'height'         => 7,
				'category__in'   => '',
				'description'    => '',
			),
			'widget_ops'      => array(
				'description' => __( 'Display the author featured listings.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args );
	}

	/**
	 * Retrieves form fields in scbForm format.
	 *
	 * @return array Form fields array.
	 */
	protected function form_fields() {
		return array(
			array(
				'type' => 'text',
				'name' => 'header',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type' => 'text',
				'name' => 'description',
				'desc' => __( 'Description:', APP_TD ),
			),
			array(
				'type' => 'select',
				'name' => 'layout',
				'desc' => __( 'Layout type:', APP_TD ),
				'choices' => array( 'hero' => __( 'Hero', APP_TD ), 'card' => __( 'Card', APP_TD ) ),
			),
			array(
				'type' => 'number',
				'name' => 'height',
				'desc' => __( 'Thumbnail height (em):', APP_TD ),
				'extra' => array(
					'max'  => 10,
					'min'  => 0,
				),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_button',
				'desc' => __( 'Show View Ad button', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_desc',
				'desc' => __( 'Show Ad description', APP_TD ),
			),
			array(
				'type'  => 'number',
				'name'  => 'show_number',
				'desc'  => __( 'Number to show:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'step'  => 1,
				),
			),
			array(
				'type'  => 'number',
				'name'  => 'scroll_number',
				'desc'  => __( 'Number to scroll:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'step'  => 1,
				),
			),
			array(
				'type'  => 'number',
				'name'  => 'limit',
				'desc'  => __( 'Total number of slides:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'step'  => 1,
				),
			),
			array(
				'type' => 'checkbox',
				'name' => 'fade',
				'desc' => __( 'Fade slides', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'autoplay',
				'desc' => __( 'Autoplay', APP_TD ),
			),
			array(
				'type' => 'number',
				'name' => 'autoplay_speed',
				'desc' => __( 'Autoplay Speed in milliseconds:', APP_TD ),
			),
			array(
				'type' => 'number',
				'name' => 'speed',
				'desc' => __( 'Slide/Fade animation speed:', APP_TD ),
			),
			array(
				'type' => 'text',
				'name' => 'category__in',
				'desc' => __( 'Filter posts by category.', APP_TD ) . " " . __( 'Enter categories IDs delimited by comma:', APP_TD ),
			),
		);
	}

	/**
	 * Retrieves a query arg array to get specific list of items.
	 *
	 * Query featured listings in random order.
	 *
	 * @param array $instance The settings for the particular instance of the
	 *                         widget.
	 *
	 * @return array Query args array.
	 */
	protected function get_query_args( $instance ) {

		$args = parent::get_query_args( $instance );

		$args['author'] = get_queried_object_id();

		$args = apply_filters( 'cp_author_featured_slider_args', $args );

		return $args;
	}

	/**
	 * Echo the widget content.
	 *
	 * @see WP_Widget
	 * @access public
	 *
	 * @global WP_Query $wp_query
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		// Only allow this widget on the author page.
		if ( ! is_author() ) {
			return;
		}

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

		$instance = array_merge( $this->defaults, (array) $instance );

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'featured', APP_POST_TYPE );
	}
}
