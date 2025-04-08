<?php
/**
 * Ads Search Widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Ads Search Widget class.
 *
 * @since 1.0.0
 */
class CP_Widget_Search extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'  => 'ad_search',
			'name'     => __( 'ClassiPress Ad Search Box', APP_TD ),
			'defaults' => array(
				'title' => __( 'Search Classified Ads', APP_TD ),
			),
			'widget_ops' => array(
				'description' => __( 'Your sidebar ad search box.', APP_TD ),
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	public function content( $instance ) {

		cp_ad_search_widget();

	}

}