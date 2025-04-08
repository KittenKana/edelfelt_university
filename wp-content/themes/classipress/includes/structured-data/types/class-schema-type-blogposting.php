<?php
/**
 * Schema.org structured data BlogPosting type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract BlogPosting schema type.
 *
 * @link  https://schema.org/BlogPosting
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_BlogPosting extends APP_Schema_Type_SocialMediaPosting {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'BlogPosting';

	/**
	 * Generates the BlogPosting schema type json-ld code.
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
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );
		$instance = new APP_Schema_Type_BlogPosting_Post( $post );
		return $instance->build();
	}
}
