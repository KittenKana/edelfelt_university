<?php
/**
 * Widget 468 x 60 Ads.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Widget 468 x 60 Ads.
 *
 * @since 4.2.0
 */
class CP_Widget_468_Ads extends APP_Widget {

	/**
	 * Constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'cp_468_ads',
			'name'            => __( 'ClassiPress 468x60 Ads', APP_TD ),
			'defaults'        => array(
				'title' => '',
			),
			'widget_ops'      => array(
				'description' => __( 'Places an ad space in the sidebar for 468x60 ads', APP_TD ),
			),
			'control_options' => array(),

		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	protected function form_fields() {
		return array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type' => 'hidden',
				'name' => '_blank',
				'desc' => __( 'Ad code can be set in ClassiPress -> Settings -> Advertising tab.', APP_TD ),
			)
		);
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the widget.
	 *
	 * @return void
	 */
	protected function content( $instance ) {

		appthemes_get_template_part( 'parts/adblock', 'header' );
	}

}
