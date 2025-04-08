<?php
/**
 * Widget for listing author content.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * Listing author widget class.
 */
class CP_Widget_Author_Bio extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'cp_widget_author_bio',
			'name'            => __( 'ClassiPress - Author Bio', APP_TD ),
			'defaults'        => array(
				'title' => __( 'About Me', APP_TD ),
			),
			'widget_ops'      => array(
				'description' => __( 'Display the author bio.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {

		// Only allow this widget on the author page.
		if ( ! is_author() || ! get_queried_object()->description ) {
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

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'author-bio' );
	}

}
