<?php
/**
 * Schema.org structured data LocalBusiness type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the LocalBusiness type schema json-ld.
 *
 * @link  https://schema.org/LocalBusiness
 *
 * @since 2.0.0
 */
class APP_Schema_Type_LocalBusiness_Post extends APP_Schema_Type_LocalBusiness {

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
			->set( 'url', get_permalink( $post->ID ) )
			->set( 'description', wp_trim_words( strip_shortcodes( $post->post_content ), 30, null ) )
			->set( 'image', new APP_Schema_Type_ImageObject_Attachment( $post ) );
	}

}
