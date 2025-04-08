<?php
/**
 * Listing author stats widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing author stats widget class.
 *
 * @since 4.0.0
 */
class CP_Widget_Listing_Author_Stats extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'        => 'cp_widget_listing_author_stats',
			'name'           => __( 'ClassiPress - Listing Author Stats', APP_TD ),
			'defaults'       => array(
				'title' => __( 'Account Stats', APP_TD ),
			),
			'widget_ops'     => array(
				'description' => __( "Display the listing's author stats", APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {
		appthemes_get_template_part( 'parts/widget', 'listing-author-stats' );
	}
}
