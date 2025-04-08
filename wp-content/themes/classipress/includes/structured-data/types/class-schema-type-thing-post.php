<?php

/**
 * Schema.org structured data general type class related to post type.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Thing type schema json-ld for WP_Post object.
 *
 * @link https://schema.org/Thing
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Thing_Post extends APP_Schema_Type_Thing {

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
			->set( 'image', new APP_Schema_Type_ImageObject_Attachment( $post ) )
			->set( 'additionalType', null )
			->set( 'alternateName', null )
			->set( 'disambiguatingDescription', null )
			->set( 'identifier', null )
			->set( 'mainEntityOfPage', null )
			->set( 'potentialAction', null )
			->set( 'sameAs', null );

	}

}
