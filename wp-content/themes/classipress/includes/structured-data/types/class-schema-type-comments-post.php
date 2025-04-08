<?php
/**
 * Schema.org structured data Comment type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the list of Comment type schema json-ld.
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Comments_Post extends APP_Schema_Property {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		$comments_per_page = get_option( 'comments_per_page' );

		/**
		 * Filters the comment number to include.
		 *
		 * @since 1.0.0
		 *
		 * @param int $comments_per_page Number of comments to include.
		 */
		$number = apply_filters( 'appthemes_schema_type_comments_limit', $comments_per_page );

		$comments = get_comments( array(
			'post_id' => $post->ID,
			'number'  => $number,
			'status'  => 'approve',
			'type'    => 'comment',
		) );

		// No comments found so return.
		if ( 0 === count( $comments ) ) {
			return;
		}

		// We've got comments so build the array.
		foreach ( $comments as $comment ) {
			$this->value[] = new APP_Schema_Type_Comment_Single( $comment );
		}

	}

}
