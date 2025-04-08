<?php
/**
 * ClassiPress Autoloader
 *
 * @package ClassiPress
 * @author  AppThemes
 * @since   4.0.0
 */

/**
 * Autoloader class.
 */
class CP_Autoload {

	/**
	 * The class map array.
	 *
	 * @var array
	 */
	private static $class_map = array();

	/**
	 * Adds class map to the end of registered.
	 *
	 * Overrides already been mapped classes.
	 *
	 * @param array $class_map The class map array.
	 *                         Keys - class names, values - class file path.
	 */
	static function add_class_map( array $class_map ) {
		self::$class_map = array_merge( self::$class_map, $class_map );
	}

	/**
	 * Registers autoloader in the system.
	 */
	static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload callback.
	 *
	 * Checks the class map and loads file if it has been mapped.
	 *
	 * @param string $class Class name.
	 */
	static function autoload( $class ) {
		if ( '\\' === $class[0] ) {
			$class = substr( $class, 1 );
		}

		if ( isset( self::$class_map[ $class ] ) && is_file( self::$class_map[ $class ] ) ) {
			require self::$class_map[ $class ];
		}
	}
}
