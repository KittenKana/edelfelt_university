<?php
/**
 * Schema.org structured data WebSite type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract WebSite schema type.
 *
 * @link  https://schema.org/WebSite
 *
 * @since 1.0.0
 *
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Website extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'WebSite';

	/**
	 * The International Standard Serial Number (ISSN) that identifies this
	 * serial publication. You can repeat this property to identify different
	 * formats of, or the linking ISSN (ISSN-L) for, this serial publication.
	 *
	 * @var APP_Schema_Property
	 */
	protected $issn;

	/**
	 * Generates the WebSite schema type json-ld code.
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
		$instance = new APP_Schema_Type_Website_Home();
		return $instance->build();
	}
}
