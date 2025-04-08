<?php
/**
 * Schema.org structured data JobPosting type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.1
 */

/**
 * Generates the JobPosting type schema json-ld.
 *
 * @link  https://schema.org/JobPosting
 * @link  https://developers.google.com/schemas/reference/types/JobPosting
 *
 * @since 2.0.1
 */
class APP_Schema_Type_JobPosting_Post extends APP_Schema_Type_JobPosting {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		$this
			->set( 'name', esc_html( $post->post_title ) )
			->set( 'title', esc_html( $post->post_title ) )
			->set( 'datePosted', get_the_time( DATE_ISO8601, $post->ID ) )
			->set( 'url', get_permalink( $post->ID ) )
			->set( 'description', wp_trim_words( strip_shortcodes( $post->post_content ), 30, null ) )
			->set( 'image', new APP_Schema_Type_ImageObject_Attachment( $post ) );
	}

}
