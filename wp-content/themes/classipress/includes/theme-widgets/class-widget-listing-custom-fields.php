<?php
/**
 * Widget for listing custom fields.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing custom fields widget class.
 *
 * @todo Use Listing Details module.
 */
class CP_Widget_Listing_Custom_Fields extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'        => 'cp_widget_listing_custom_fields',
			'name'           => __( 'ClassiPress - Single Listing Custom Fields', APP_TD ),
			'defaults'       => array(
				'title' => '',
			),
			'widget_ops'     => array(
				'description' => __( 'Display the listing custom fields.', APP_TD ),
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
		// grab the category id for the functions below
		$cat_id = appthemes_get_custom_taxonomy( get_the_ID(), APP_TAX_CAT, 'term_id' );

		ob_start();
		cp_get_ad_details( get_the_ID(), $cat_id );
		cp_get_ad_details( get_the_ID(), $cat_id, 'content' );
		$listing_fields = ob_get_clean();

		if ( ! $listing_fields ) {
			return;
		}

		$instance['listing_fields'] = $listing_fields;

		parent::widget( $args, $instance );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {
		echo $instance['listing_fields'];
	}
}
