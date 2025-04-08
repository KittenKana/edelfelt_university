<?php
/**
 * Schema.org structured data Comment type classes.
 *
 * @package Components\StructuredData\Type
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract Review schema type.
 *
 * A review of an item - for example, of a restaurant, movie, or store.
 *
 * Designed to work mainly with the Critic AppThemes plugin.
 *
 * @link https://schema.org/Review
 * @link https://developers.google.com/gmail/markup/reference/types/Review
 *
 * @todo Make more modular so theme/plugin could potentally use it.
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Review extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Review';

	/**
	 * The item that is being reviewed/rated.
	 *
	 * @var APP_Schema_Type_Thing
	 */
	protected $itemReviewed;

	/**
	 * The actual body of the review.
	 *
	 * @var APP_Schema_Property
	 */
	protected $reviewBody;

	/**
	 * The rating given in this review. Note that reviews can themselves be
	 * rated. The reviewRating applies to rating given by the review.
	 * The aggregateRating property applies to the review itself, as a creative
	 * work.
	 *
	 * @var APP_Schema_Type_Rating
	 */
	protected $reviewRating;

	/**
	 * Generates the Review schema type json-ld code.
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 *
	 * @param array  $type            The type of comment(s) we want to include.
	 * @param string $rating_meta_key The wp_commentmeta key which stores the rating value.
	 * @param string $name_meta_key   The wp_commentmeta key which stores the review
	 *                                name/subject/title value (Optional).
	 */
	public static function type( $type = array( 'comment' ), $rating_meta_key = '', $name_meta_key = '' ) {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );

		$reviews_per_page = get_option( 'comments_per_page' );

		/**
		 * Filters the review number to include.
		 *
		 * @since 1.0.0
		 *
		 * @param int $reviews_per_page Number of reviews to include.
		 */
		$number	= apply_filters( 'appthemes_schema_type_reviews_limit', $reviews_per_page );

		$reviews = get_comments( array(
			'post_id' => $post->ID,
			'number'  => $number,
			'status'  => 'approve',
			'type'    => $type,
			'parent'  => 0,
		) );

		// No reviews found so return.
		if ( 0 === count( $reviews ) ) {
			return;
		}

		// We've got reviews so build the array.
		foreach ( $reviews as $review ) {

			// If we don't have a meta key, just set it to zero or empty.
			$rating = ( $rating_meta_key ) ? get_comment_meta( $review->comment_ID, $rating_meta_key, true ) : 0;
			$name   = ( $name_meta_key ) ? get_comment_meta( $review->comment_ID, $name_meta_key, true ) : '';

			// Support for Critic or other plugins that store serialized values in an array.
			$name = is_array( $name ) ? $name['title'] : $name;
			$author = new APP_Schema_Type_Person_Commenter( $review );

			$output[] = array(
				'@type'         => 'Review',
				'reviewRating'  => array(
					'@type'       => 'Rating',
					'bestRating'  => 5,
					'ratingValue' => (float) $rating,
					'worstRating' => 1,
				),
				'name'          => esc_html( $name ),
				'datePublished' => mysql2date( DATE_ISO8601, $review->comment_date ),
				'reviewBody'    => esc_html( $review->comment_content ),
				'author'        => $author->build(),
				'url'           => esc_url( get_comment_link( $review->comment_ID ) ),
			);
		}

		/**
		 * Filters the Review type schema properties.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output  List of Review type schema properties.
		 * @param array $reviews The reviews array for this page/post.
		 */
		return apply_filters( 'appthemes_schema_type_review', $output, $reviews );
	}
}
