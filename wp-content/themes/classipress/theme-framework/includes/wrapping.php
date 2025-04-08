<?php
/**
 * Automatically pass all templates through a wrapper.php template.
 *
 * @package ThemeFramework\Wrapping
 */

class APP_Wrapping {

	private static $main_template;
	private static $base;

	static function wrap( $template ) {
		self::$main_template = $template;

		self::$base = substr( basename( self::$main_template ), 0, -4 );

		if ( 'index' == self::$base ) {
			self::$base = false;
		}

		$templates = array( 'wrapper.php' );

		if ( self::$base ) {
			array_unshift( $templates, sprintf( 'wrapper-%s.php', self::$base ) );
		}

		return locate_template( $templates );
	}

	static function get_main_template() {
		return self::$main_template;
	}

	static function get_base() {
		return self::$base;
	}
}
