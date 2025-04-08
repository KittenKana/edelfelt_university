<?php
/**
 * Widget for single listing comments.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing comments widget class
 */
class CP_Widget_Listing_Comments extends APP_Widget {

	/**
	 * Preserved title.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'         => 'cp_widget_listing_comments',
			'name'            => __( 'ClassiPress - Listing Comments', APP_TD ),
			'defaults'        => array(
				'title' => '',
			),
			'widget_ops'      => array(
				'description' => __( 'Display the listing comments.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the widget instance.
	 */
	public function content( $instance ) {
		// Set so we can access from other class functions.
		$this->title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

		comments_template();
	}

}
