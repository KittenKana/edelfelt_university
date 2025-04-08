<?php
/**
 * Recent Blog Posts Widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Recent Blog Posts Widget class.
 *
 * @since 1.0.0
 * @since 4.0.0 Migrated template from 'includes/sidebar-blog-posts.php' to
 * 'parts/widget-sidebar-blog-posts.php'
 */
class CP_Widget_Blog_Posts extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'  => 'cp_recent_posts',
			'name'     => __( 'ClassiPress - Sidebar Recent Blog Posts', APP_TD ),
			'defaults' => array(
				'title'               => __( 'From the Blog', APP_TD ),
				'count'               => 5,
				'orderby'             => 'date',
				'show_thumbnail'      => true,
				'show_author'         => true,
				'show_date'           => true,
				'show_comments_count' => true,
				'show_content'        => true,
			),
			'widget_ops' => array(
				'description' => __( 'Your most recent blog posts.', APP_TD ),
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
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
			'posts_per_page'      => absint( $instance['count'] ),
			'ignore_sticky_posts' => true,
		);

		if ( 'popularity' === $instance['orderby'] ) {
			// give us the most popular blog posts based on page views, last 3 months
			$args['date_start'] = appthemes_mysql_date( current_time( 'mysql' ), -90 );
		}

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
	function widget( $args, $instance ) {

		$instance = array_merge( $this->defaults, (array) $instance );
		$q_args   = $this->get_query_args( $instance );

		if ( 'popularity' === $instance['orderby'] && current_theme_supports( 'app-stats' ) ) {
			// Query the database.
			$items = new CP_Popular_Posts_Query( $q_args );
			// give us the most popular blog posts based on page views, overall
			if ( ! $items->post_count ) {
				unset( $args['date_start'] );
				$items = new CP_Popular_Posts_Query( $q_args );
			}
		} else {
			$items = new WP_Query( $this->get_query_args( $instance ) );
		}

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

	public function content( $instance ) {
		global $wp_query;

		$instance = array_merge( $this->defaults, (array) $instance );

		$wp_query->set( 'instance', $instance );

		// include the main blog loop
		appthemes_get_template_part( 'parts/widget-sidebar-blog-posts' );

	}

	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD )
			),
			array(
				'type'  => 'text',
				'name'  => 'count',
				'desc'  => __( 'Number of posts to show:', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
			array(
				'desc'	 => __( 'Order By:', APP_TD ),
				'type'	 => 'select',
				'name'	 => 'orderby',
				'values' => array(
					'date'       => __( 'Date', APP_TD ),
					'popularity' => __( 'Popularity', APP_TD ),
				),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_thumbnail',
				'desc' => __( 'Show post thumbnail', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_author',
				'desc' => __( 'Show post author', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_date',
				'desc' => __( 'Show post date', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_comments_count',
				'desc' => __( 'Show post comments count', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_content',
				'desc' => __( 'Show post content', APP_TD ),
			),
		);

		return $form_fields;
	}

}
