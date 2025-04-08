<?php
/**
 * Schema.org structured data SearchAction type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract SearchAction schema type.
 *
 * @link https://schema.org/SearchAction
 * @link https://developers.google.com/gmail/markup/reference/types/SearchAction
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_SearchAction extends APP_Schema_Type_Action {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'SearchAction';

	/**
	 * A sub property of instrument. The query used on this action.
	 *
	 * @var APP_Schema_Property
	 */
	protected $query;

	/**
	 * A sub property of instrument. The query used on this action.
	 *
	 * An alias of the `query-input` parameter.
	 *
	 * @var APP_Schema_Property
	 */
	protected $query_input;

	/**
	 * Generates the SearchAction schema type json-ld code.
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 */
	public static function type() {

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );
		$instance = new APP_Schema_Type_SearchAction_General();
		return $instance->build();

	}
}
