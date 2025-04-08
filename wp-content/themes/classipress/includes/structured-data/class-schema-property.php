<?php
/**
 * Schema.org data property class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Schema property class.
 *
 * Default schema property type for scalar fields or collections of fields.
 */
class APP_Schema_Property implements APP_Schema_Type_Buildable {

	/**
	 * Property value.
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * Create single property.
	 *
	 * @param mixed $value The property value.
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Build type.
	 *
	 * @return mixed Property value.
	 */
	public function build() {
		$value = $this->value;

		if ( is_array( $value ) ) {
			foreach ( $value as &$item ) {
				if ( $item instanceof APP_Schema_Type_Buildable ) {
					$item = $item->build();
				}
			}
		} elseif ( $value instanceof APP_Schema_Type_Buildable ) {
			$value = $value->build();
		}

		return $value;
	}
}
