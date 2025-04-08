<?php
/**
 * Schema.org structured data BlogPosting type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Generates the BlogPosting type schema json-ld.
 *
 * @link  https://schema.org/BlogPosting
 *
 * @since 1.0.0
 */
class APP_Schema_Type_BlogPosting_Post extends APP_Schema_Type_BlogPosting {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		// Get all the tags.
		$tags = get_the_terms( $post->ID, 'post_tag' );

		// Grab all the tag names.
		$tags_names = array();
		if ( $tags ) {
			$tags_names = wp_list_pluck( (array) $tags, 'name' );
		}

		// Break apart array into spaced-separated string.
		$tags_names = implode( ', ', $tags_names );

		// Get all the cats.
		$cats = get_the_terms( $post->ID, 'category' );

		// Grab all the cat names.
		$cats_names = array();
		if ( $cats ) {
			$cats_names = wp_list_pluck( (array) $cats, 'name' );
		}

		// Break apart array into spaced-separated string.
		$cats_names = implode( ', ', $cats_names );

		$author = get_user_by( 'id', $post->post_author );

		if ( $author ) {
			$this->set( 'author', new APP_Schema_Type_Person_User( $author ) );
		}

		$this
			->set( 'name', esc_html( $post->post_title ) )
			->set( 'headline', esc_html( $post->post_title ) )
			->set( 'url', get_permalink( $post->ID ) )
			->set( 'description', wp_trim_words( strip_shortcodes( $post->post_content ), 30, null ) )
			->set( 'wordCount', self::get_word_count( $post->post_content ) )
			->set( 'datePublished', get_the_time( DATE_ISO8601, $post->ID ) )
			->set( 'dateModified', get_post_modified_time( DATE_ISO8601, false, $post->ID ) )
			->set( 'commentCount', $post->comment_count )
			->set( 'comment', new APP_Schema_Type_Comments_Post( $post ) )
			->set( 'image', new APP_Schema_Type_ImageObject_Attachment( $post ) )
			->set( 'mainEntityOfPage', get_permalink( $post->ID ) )
			->set( 'genre', $cats_names )
			->set( 'keywords', $tags_names )
			->set( 'publisher', new APP_Schema_Type_Organization_Home() );
	}

}
