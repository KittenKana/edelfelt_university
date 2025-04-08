<?php

/**
 * Schema.org structured data general type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract class for any schema types.
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type implements APP_Schema_Type_Buildable {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The property setter.
	 *
	 * @param string $property Property name.
	 * @param mixed  $value    Property value.
	 *
	 * @return $this
	 */
	final public function set( $property, $value ) {

		if ( ! empty( $value ) && ! $value instanceof APP_Schema_Type_Buildable ) {
			$value = new APP_Schema_Property( $value );
		}

		// Some properties might use '-' in the name.
		$property = str_replace( '-', '_', $property );

		$this->$property = $value;

		return $this;
	}

	/**
	 * Magic setter
	 *
	 * Doesn't allow to set magic properties by default.
	 *
	 * @param string $name  The listing property or module name.
	 * @param string $value New value.
	 */
	public function __set( $name, $value ) {}

	/**
	 * Builds schema type properties array.
	 *
	 * @return array
	 */
	final public function build() {

		$output = array(
			'@type' => $this->type,
		);

		$reflection = new ReflectionClass( $this );
		$properties = $reflection->getProperties();

		foreach ( $properties as $property ) {
			if ( $this->{$property->name} instanceof APP_Schema_Type_Buildable ) {
				$build = $this->{$property->name}->build();
				if ( ! empty( $build ) ) {
					$name = str_replace( '_', '-', $property->name );
					$output[ $name ] = $build;
				}
			}
		}

		/**
		 * Filters the type schema properties.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output List of type schema properties.
		 */
		return apply_filters( 'appthemes_schema_type_' . strtolower( $this->type ), $output );
	}

}
