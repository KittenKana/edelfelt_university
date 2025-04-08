<?php
/**
 * Schema.org structured data SocialMediaPosting type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract SocialMediaPosting schema type.
 *
 * @link  https://schema.org/SocialMediaPosting
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_SocialMediaPosting extends APP_Schema_Type_Article {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'SocialMediaPosting';

	/**
	 * A CreativeWork such as an image, video, or audio clip shared as part of this posting.
	 *
	 * @var APP_Schema_Type_CreativeWork
	 */
	protected $sharedContent;

}
