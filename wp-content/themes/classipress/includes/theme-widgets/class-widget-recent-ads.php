<?php
/**
 * Sidebar Recent Ads Widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * Sidebar Recent Ads Widget class.
 *
 * @since 4.2.0
 */
class CP_Widget_Recent_Ads extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'cp_recent_ads',
			'name'            => __( 'ClassiPress - Sidebar Recent Ads', APP_TD ),
			'defaults'        => array(
				'title'          => __( 'Recent Ads', APP_TD ),
				'count'          => 5,
				'show_thumbnail' => true,
				'show_date'      => false,
				'show_category'  => false,
				'show_location'  => true,
				'show_price'     => true,
				'show_views'     => false,
			),
			'widget_ops'      => array(
				'description' => __( 'Your most recent ads on sidebar.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
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
			'posts_per_page'      => absint( $instance['count'] ),
			'ignore_sticky_posts' => true,
		);

		return $args;
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
	public function widget( $args, $instance ) {

		$instance = array_merge( $this->defaults, (array) $instance );

		$items = new WP_Query( $this->get_query_args( $instance ) );

		if ( ! $items->post_count ) {
			wp_reset_postdata();
			return;
		}

		$instance['items']        = $items;
		$instance['before_title'] = $args['before_title'];
		$instance['after_title']  = $args['after_title'];

		parent::widget( $args, $instance );

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

		$instance = array_merge( $this->defaults, (array) $instance );

		$wp_query->set( 'instance', $instance );

		appthemes_get_template_part( 'parts/widget-sidebar-ads' );
	}

	/**
	 * Retrieves form fields in scbForm format.
	 *
	 * @return array Form fields array.
	 */
	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type'  => 'text',
				'name'  => 'count',
				'desc'  => __( 'Number of ads to show:', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_thumbnail',
				'desc' => __( 'Show ad thumbnail', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_date',
				'desc' => __( 'Show post date', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_category',
				'desc' => __( 'Show ad category', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_location',
				'desc' => __( 'Show ad location', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_price',
				'desc' => __( 'Show ad price', APP_TD ),
			),
		);

		if ( current_theme_supports( 'app-stats' ) ) {
			$form_fields[] = array(
				'type' => 'checkbox',
				'name' => 'show_views',
				'desc' => __( 'Show ad views', APP_TD ),
			);
		}

		return $form_fields;
	}

}
