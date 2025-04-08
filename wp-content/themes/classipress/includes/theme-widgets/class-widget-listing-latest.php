<?php
/**
 * Latest listings widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Latest listings widget class.
 *
 * @since 4.0.0
 */
class CP_Widget_Listing_Latest extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'          => 'cp_widget_listing_latest',
			'name'             => __( 'ClassiPress - Home Latest Listings', APP_TD ),
			'defaults'         => array(
				'header'       => __( 'Latest Listings', APP_TD ),
				'limit'        => 6,
				'grid_cols'    => 3,
				'layout'       => 'grid',
				'next_page'    => false,
				'post__in'     => '',
				'category__in' => '',
				'description'  => __( 'Check out our newest listings', APP_TD ),
			),
			'widget_ops'      => array(
				'description' => __( 'Display your latest listings.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
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
				'choices' => apply_filters( 'cp_widget_listing_latest_layouts', array( 'grid' => __( 'Grid', APP_TD ), 'list' => __( 'List', APP_TD ) ) ),
			),
			array(
				'type'  => 'number',
				'name'  => 'limit',
				'desc'  => __( 'Number to show:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'step'  => 1,
				),
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
				'name' => 'post__in',
				'desc' => __( 'Filter posts by IDs.', APP_TD ) . " " . __( 'Enter posts IDs delimited by comma:', APP_TD ),
			),
			array(
				'type' => 'text',
				'name' => 'category__in',
				'desc' => __( 'Filter posts by category.', APP_TD ) . " " . __( 'Enter categories IDs delimited by comma:', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'next_page',
				'desc' => __( 'View More button leads to the second page of archive.', APP_TD ) . ' ' . __( 'Make sure the number of items to show in the widget is not less than what should be displayed on the first page.', APP_TD ),
			),
		);
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
			'post_type'           => APP_POST_TYPE,
			'posts_per_page'      => absint( $instance['limit'] ),
			'ignore_sticky_posts' => true,
		);

		$post__in = array_map( 'trim', explode( ',', $instance['post__in'] ) );

		if ( ! empty( $post__in[0] ) ) {
			$args['post__in'] = $post__in;
		}

		$category__in = array_map( 'trim', explode( ',', $instance['category__in'] ) );

		if ( ! empty( $category__in[0] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => APP_TAX_CAT,
					'field'    => 'id',
					'terms'    => $category__in,
				),
			);
		}

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
	function widget( $args, $instance ) {
		global $wp_query;

		// Query the database.
		$items = new WP_Query( $this->get_query_args( $instance ) );

		if ( ! $items->post_count ) {
			wp_reset_postdata();
			return;
		}

		$preserved = $wp_query->get( 'listing_layout' );

		if ( isset( $instance['layout'] ) ) {
			$wp_query->set( 'listing_layout', $instance['layout'] );
		}

		$instance['items']        = $items;
		$instance['before_title'] = $args['before_title'];
		$instance['after_title']  = $args['after_title'];
		$instance['header']       = apply_filters( 'widget_title', isset( $instance['header'] ) ? $instance['header'] : '', $instance, $this->id_base );
		$instance['description']  = apply_filters( 'cp_widget_description', isset( $instance['description'] ) ? $instance['description'] : '', $instance, $this->id_base );

		parent::widget( $args, $instance );

		$wp_query->set( 'listing_layout', $preserved );

		wp_reset_postdata();
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
		appthemes_get_template_part( 'parts/widget-latest', APP_POST_TYPE );

		$total_pages   = max( 1, absint( $instance['items']->max_num_pages ) );
		$post_type_url = '';

		if ( $instance['next_page'] ) {
			if ( 1 < $total_pages ) {
				$post_type_url = add_query_arg( array( 'paged' => 2 ), get_post_type_archive_link( APP_POST_TYPE ) );
			}
		} else {
			$post_type_url = get_post_type_archive_link( APP_POST_TYPE );
		}

		if ( $post_type_url ) {
			echo '<p class="view-more-listings"><a href="' . esc_url( $post_type_url ) . '" class="hollow button">' . esc_html__( 'View More Ads', APP_TD ) . '</a></p>';
		}
	}
}
