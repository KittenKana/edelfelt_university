<?php
/**
 * Schema.org structured data Comment type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract Comment schema type.
 *
 * A comment on an item - for example, a comment on a blog post. The comment's
 * content is expressed via the text property, and its topic via about,
 * properties shared with all CreativeWorks.
 *
 * @link https://schema.org/Comment
 * @link https://developers.google.com/gmail/markup/reference/types/Comment
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Comment  extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Comment';

	/**
	 * The number of downvotes this question, answer or comment has received
	 * from the community.
	 *
	 * @var APP_Schema_Property
	 */
	protected $downvoteCount;

	/**
	 * The parent of a question, answer or item in general.
	 *
	 * @var APP_Schema_Type_Question
	 */
	protected $parentItem;

	/**
	 * The number of upvotes this question, answer or comment has received from
	 * the community.
	 *
	 * @var APP_Schema_Property
	 */
	protected $upvoteCount;

	/**
	 * Generates the Comment schema type json-ld code.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 */
	public static function type() {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );
		$instance = new APP_Schema_Type_Comments_Post( $post );
		return $instance->build();
	}
}
