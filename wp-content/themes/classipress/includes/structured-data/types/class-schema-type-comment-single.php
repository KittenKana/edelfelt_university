<?php
/**
 * Schema.org structured data Comment type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Comment type schema json-ld.
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Comment_Single extends APP_Schema_Type_Comment {

	/**
	 * Constructor.
	 *
	 * @param WP_Comment $comment Comment object to be used for building schema
	 *                            type.
	 */
	public function __construct( WP_Comment $comment = null ) {
		if ( ! $comment ) {
			return;
		}

		$this
			->set( 'dateCreated', mysql2date( DATE_ISO8601, $comment->comment_date ) )
			->set( 'description', wp_trim_words( strip_shortcodes( esc_html( $comment->comment_content ) ), 30, null ) )
			->set( 'author', new APP_Schema_Type_Person_Commenter( $comment ) )
			->set( 'url', esc_url( get_comment_link( $comment->comment_ID ) ) );
	}

}
