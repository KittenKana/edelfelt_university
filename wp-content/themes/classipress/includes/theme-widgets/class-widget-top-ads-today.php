<?php
/**
 * Today's Popular Ads Widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Today's Popular Ads Widget class.
 *
 * @since 1.0.0
 */
class CP_Widget_Top_Ads_Today extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base' => 'top_ads',
			'name'    => __( 'ClassiPress Top Ads Today', APP_TD ),
			'defaults' => array(
				'title'  => __( 'Popular Ads Today', APP_TD ),
				'number' => 10,
			),
			'widget_ops' => array(
				'description' => __( 'Display the top ads today.', APP_TD ),
				'classname'   => 'widget-top-ads-today',
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	/**
	 * Additional checks before registering the widget.
	 *
	 * @return bool
	 */
	protected function condition() {
		return current_theme_supports( 'app-stats' );
	}

	public function content( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
 			$number = 10;
 		}

		cp_todays_count_widget( APP_POST_TYPE, $number );

	}

	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD )
			),
			array(
				'type' => 'text',
				'name' => 'number',
				'desc' => __( 'Number of ads to show:', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
		);

		return $form_fields;
	}

}
