<?php
/**
 * Widget for single listing image slider.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * Widget for single listing image slider.
 */
class CP_Widget_Listing_Image_Slider extends CP_Widget_Listing_Reveal_Gallery {

	/**
	 * Construct the widget.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'cp_widget_listing_image_slider',
			'name'            => __( 'ClassiPress - Single Listing Image Slider', APP_TD ),
			'defaults'        => array(
				'title'          => '',
				'show_number'    => 5,
				'scroll_number'  => 1,
				'center_mode'    => false,
				'fade'           => false,
				'autoplay'       => false,
				'autoplay_speed' => 3000,
				'speed'          => 800,
			),
			'widget_ops'      => array(
				'description' => __( 'Display the listing images.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args );
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

		$instance['widget_id'] = $this->id;

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'listing-image-slider' );
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
				'desc' => __( 'Title:', APP_TD )
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
			/*array(
				'type'  => 'number',
				'name'  => 'scroll_number',
				'desc'  => __( 'Number to scroll:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 1,
					'step'  => 1,
				),
			),*/
			array(
				'type' => 'checkbox',
				'name' => 'center_mode',
				'desc' => __( 'Center Mode (best for odd numbered counts)', APP_TD ),
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
		);

		return $form_fields;
	}

}
