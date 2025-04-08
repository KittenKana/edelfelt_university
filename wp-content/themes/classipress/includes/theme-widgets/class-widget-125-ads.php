<?php
/**
 * Widget 125 Ads.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Widget 125 Ads.
 *
 * @since 1.0.0
 */
class CP_Widget_125_Ads extends APP_Widget_125_Ads {

	public static $ads = '';

	public function __construct() {
		$args = array(
			'id_base' => 'cp_125_ads',
			'defaults' => array(
				'style_url' => false,
			),
		);

		parent::__construct( $args );

		self::$ads = $this->defaults['ads'];
	}

}
