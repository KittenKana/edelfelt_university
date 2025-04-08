<?php
/**
 * Widget for single listing map.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing map widget class.
 *
 * @todo Migrate to new widget maps code.
 */
class CP_Widget_Listing_Map extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'         => 'cp_widget_listing_map',
			'name'            => __( 'ClassiPress - Single Listing Map &amp; Contact Info', APP_TD ),
			'defaults'        => array(
				'title'      => '',
				'address'    => true,
				'phone'      => true,
				'website'    => true,
				'email'      => true,
				'directions' => true,
				'social'     => true,
			),
			'widget_ops'      => array(
				'description' => __( 'Display the listing map and contact info.', APP_TD ),
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
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'address',
				'desc' => __( 'Show Address', APP_TD ),
			),
//			array(
//				'type' => 'checkbox',
//				'name' => 'phone',
//				'desc' => __( 'Show Phone Number', APP_TD ),
//			),
//			array(
//				'type' => 'checkbox',
//				'name' => 'website',
//				'desc' => __( 'Show Website URL', APP_TD ),
//			),
//			array(
//				'type' => 'checkbox',
//				'name' => 'email',
//				'desc' => __( 'Show Email', APP_TD ),
//			),
			array(
				'type' => 'checkbox',
				'name' => 'directions',
				'desc' => __( 'Show "Get Directions"', APP_TD ),
			),
//			array(
//				'type' => 'checkbox',
//				'name' => 'social',
//				'desc' => __( 'Show Social Networks', APP_TD ),
//			),
		);
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {

		// Only allow this widget on the single listing page.
		if ( ! is_singular( APP_POST_TYPE ) ) {
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
		global $wp_query, $post;

		$instance = array_merge( $this->defaults, (array) $instance );

		$is_geo_coded = false;
		$geo_values = array();

		$address_parts = array_filter( array(
			get_post_meta( $post->ID, 'cp_street', false ),
			get_post_meta( $post->ID, 'cp_city', true ),
			get_post_meta( $post->ID, 'cp_state', true ),
			get_post_meta( $post->ID, 'cp_zipcode', true ),
		) );
		$make_address = implode( '&nbsp;', $address_parts );
		$coordinates  = cp_get_geocode( $post->ID );

		if ( $coordinates ) {
			$geo_values = $coordinates;
		}

		// Make sure we've got coordinates to work with.
		if ( ! empty( $geo_values['lat'] ) && ! empty( $geo_values['lng'] ) ) {
			$is_geo_coded = true;
			$address_link = '<a href="' . esc_url( 'https://maps.google.com/maps/search/?api=1&zoom=16&query=' . $geo_values['lat'] . ',' . $geo_values['lng'] ) . '" target="_blank" rel="nofollow">' . $make_address . '</a>';
		} else {
			$address_link = $make_address;
		}

		$instance['geo_values'] = $geo_values;
		$instance['address_link'] = $address_link;
		$instance['is_geo_coded'] = $is_geo_coded;
		$instance['widget_id'] = $this->id;

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'listing-map' );
	}
}