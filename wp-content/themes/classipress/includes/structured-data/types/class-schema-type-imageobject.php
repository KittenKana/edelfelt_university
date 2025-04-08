<?php
/**
 * Schema.org structured data ImageObject type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract ImageObject schema type.
 *
 * @link https://schema.org/ImageObject
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_ImageObject extends APP_Schema_Type_MediaObject {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'ImageObject';

	/**
	 * Generates the ImageObject schema type json-ld code.
	 *
	 * @since   1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 *
	 * @since 2.0.0
	 *
	 * @param string $type The type of image we want. 'logo' or defaults to attached image.
	 */
	public static function type( $type = '' ) {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', ' implemented build() method' );

		if ( 'logo' === $type ) {
			$instance = new APP_Schema_Type_ImageObject_Site_Logo();
		} else {
			$instance = new APP_Schema_Type_ImageObject_Attachment( $post );
		}

		return $instance->build();
	}
}
