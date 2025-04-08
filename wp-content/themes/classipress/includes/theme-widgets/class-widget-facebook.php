<?php
/**
 * Facebook like box widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Facebook like box widget class.
 *
 * @since 1.0.0
 */
class CP_Widget_Facebook extends APP_Widget_Facebook {

	public function __construct() {
		$args = array(
			'id_base' => 'cp_facebook_like',
		);

		parent::__construct( $args );
	}

}
