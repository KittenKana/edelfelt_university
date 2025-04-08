<?php
/**
 * Schema.org structured data WebPage type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Generates the WebPage type schema json-ld.
 *
 * @link  https://schema.org/WebPage
 *
 * @since 2.0.0
 */
class APP_Schema_Type_WebPage_Post extends APP_Schema_Type_WebPage {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		$author = get_user_by( 'id', $post->post_author );

		if ( $author ) {
			$this->set( 'author', new APP_Schema_Type_Person_User( $author ) );
		}

		$this
			->set( 'headline', esc_html( $post->post_title ) )
			->set( 'datePublished', get_the_time( DATE_ISO8601, $post->ID ) )
			->set( 'dateModified', get_post_modified_time( DATE_ISO8601, false, $post->ID ) )
			->set( 'description', wp_trim_words( strip_shortcodes( $post->post_content ), 30, null ) )
			->set( 'commentCount', $post->comment_count )
			->set( 'comment', new APP_Schema_Type_Comments_Post( $post ) )
			->set( 'url', get_permalink( $post->ID ) )
			->set( 'image', new APP_Schema_Type_ImageObject_Attachment( $post ) )
			->set( 'publisher', new APP_Schema_Type_Organization_Home() );

	}

}
