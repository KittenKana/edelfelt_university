<?php
/**
 * Schema.org structured data AggregateRating type classes.
 *
 * @package Components\StructuredData\Type
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract AggregateRating schema type.
 *
 * The average rating based on multiple ratings or reviews.
 *
 * @link https://schema.org/AggregateRating
 * @link https://developers.google.com/gmail/markup/reference/types/AggregateRating
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_AggregateRating extends APP_Schema_Type_Rating {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Rating';

	/**
	 * The item that is being reviewed/rated.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $itemReviewed;

	/**
	 * The count of total number of ratings.
	 *
	 * @var APP_Schema_Property
	 */
	protected $ratingCount;

	/**
	 * The count of total number of reviews.
	 *
	 * @var APP_Schema_Property
	 */
	protected $reviewCount;

	/**
	 * Generates the AggregateRating schema type json-ld code.
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 *
	 * @param array  $type The type of comment(s) we want to include.
	 * @param string $meta_key The wp_postmeta key which stores the rating avg value.
	 */
	public static function type( $type = array( 'comment' ), $meta_key = '' ) {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );

		$reviews = get_comments( array(
			'post_id' => $post->ID,
			'status'  => 'approve',
			'type'    => $type,
			'parent'  => 0,
		) );

		// No reviews found so return.
		if ( 0 === count( $reviews ) ) {
			return;
		}

		// If we don't have a meta key, just set it to zero.
		$rating_avg = ( $meta_key ) ? get_post_meta( $post->ID, $meta_key, true ) : 0;

		$output[] = array(
			'@type'       => 'AggregateRating',
			'bestRating'  => 5,
			'ratingValue' => (float) $rating_avg,
			'worstRating' => 1,
			'ratingCount' => count( $reviews ),
		);

		/**
		 * Filters the AggregateRating type schema properties.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output List of AggregateRating type schema properties.
		 */
		return apply_filters( 'appthemes_schema_type_aggregaterating', $output );
	}
}
