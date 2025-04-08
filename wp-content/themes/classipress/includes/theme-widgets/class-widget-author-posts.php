<?php
/**
 * Author blog posts widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * Author blog posts widget class.
 *
 * @since 4.2.0
 */
class CP_Widget_Author_Posts extends CP_Widget_Post_Latest {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'         => 'cp_widget_author_posts',
			'name'            => __( 'ClassiPress - Author Posts', APP_TD ),
			'defaults'        => array(
				'header'       => __( 'Posts', APP_TD ),
				'grid_cols'    => 2,
				'category__in' => '',
				'description'  => '',
			),
			'widget_ops'      => array(
				'description' => __( 'Display the author latest blog posts.', APP_TD ),
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
				'type'  => 'number',
				'name'  => 'grid_cols',
				'desc'  => __( 'Number of columns in a grid:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'max'   => 8,
					'step'  => 1,
				),
			),
			array(
				'type' => 'text',
				'name' => 'category__in',
				'desc' => __( 'Filter posts by category.', APP_TD ) . " " . __( 'Enter categories IDs delimited by comma:', APP_TD ),
			),
		);
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
	 * Retrieves a query arg array to get specific list of items
	 *
	 * @param array $instance The settings for the particular instance of the
	 *                         widget.
	 *
	 * @return array Query args array.
	 */
	protected function get_query_args( $instance ) {

		$instance = array_merge( $this->defaults, (array) $instance );

		$args = array(
			'post_type'           => 'post',
			'ignore_sticky_posts' => true,
			'author'              => get_queried_object_id(),
			'paged'               => ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1,
		);

		$category__in = array_map( 'trim', explode( ',', $instance['category__in'] ) );

		if ( ! empty( $category__in[0] ) ) {
			$args['category__in'] = $category__in;
		}

		return $args;
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
		appthemes_get_template_part( 'parts/widget-latest', 'post' );

		cp_do_pagination( $instance['items'] );
	}
}
