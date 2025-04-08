<?php
/**
 * Schema.org structured data Person type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Person type schema json-ld.
 *
 * @link  https://schema.org/Person
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Person_Commenter extends APP_Schema_Type_Person {

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

		$this->set( 'name', esc_html( $comment->comment_author ) );
		$this->set( 'url', esc_url( $comment->comment_author_url ) );
	}

}
