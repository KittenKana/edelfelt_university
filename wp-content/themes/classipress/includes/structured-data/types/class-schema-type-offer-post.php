<?php
/**
 * Schema.org structured data Offer type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Offer type schema json-ld.
 *
 * @link  https://schema.org/Offer
 * @link  https://developers.google.com/schemas/reference/types/Offer
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Offer_Post extends APP_Schema_Type_Offer {

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
