<?php
/**
 * Latest blog posts widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Latest blog posts widget class.
 *
 * @since 4.0.0
 */
class CP_Widget_Post_Latest extends CP_Widget_Listing_Latest {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'         => 'cp_widget_post_latest',
			'name'            => __( 'ClassiPress - Home Latest Blog Posts', APP_TD ),
			'defaults'        => array(
				'header'      => __( 'From the Blog', APP_TD ),
				'limit'       => 3,
				'grid_cols'    => 3,
				'next_page'    => false,
				'post__in'     => '',
				'category__in' => '',
				'description' => __( 'Check out our newest articles', APP_TD ),
			),
			'widget_ops'      => array(
				'description' => __( 'Display your latest blog posts.', APP_TD ),
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
				'desc' => __( 'View More button leads to the second page of archive.', APP_TD ) . ' ' . __( 'Make sure the number of items to show in the widget is not less than what should be displayed on the first page', APP_TD ),
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
			'post_type'           => 'post',
			'posts_per_page'      => absint( $instance['limit'] ),
			'ignore_sticky_posts' => true,
		);

		$post__in = array_map( 'trim', explode( ',', $instance['post__in'] ) );

		if ( ! empty( $post__in[0] ) ) {
			$args['post__in'] = $post__in;
		}

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

		$total_pages   = max( 1, absint( $instance['items']->max_num_pages ) );
		$post_type_url = '';

		if ( $instance['next_page'] ) {
			if ( 1 < $total_pages ) {
				$post_type_url = add_query_arg( array( 'paged' => 2 ), get_post_type_archive_link( 'post' ) );
			}
		} else {
			$post_type_url = get_post_type_archive_link( 'post' );
		}

		if ( $post_type_url ) {
			echo '<p class="view-more-articles"><a href="' . esc_url( $post_type_url ) . '" class="hollow button">' . esc_html__( 'View More Articles', APP_TD ) . '</a></p>';
		}

	}
}
