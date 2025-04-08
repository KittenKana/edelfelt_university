<?php
/**
 * Schema.org structured data ImageObject type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the ImageObject type schema json-ld.
 *
 * @since 2.0.0
 */
class APP_Schema_Type_ImageObject_Attachment extends APP_Schema_Type_ImageObject {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		// Prefill array and error message since ImageObject is a required type.
		$image = array( __( 'No image found', APP_TD ), 0, 0, 0 );

		$thumbnail_id = get_post_thumbnail_id( $post );
		$attachment   = null;

		if ( $thumbnail_id ) {
			$attachment = get_post( $thumbnail_id );
		} else {
			// Grab random image attached to post.
			$attachments = new WP_Query( array(
				'post_parent'            => $post->ID,
				'post_type'              => 'attachment',
				'post_status'            => 'inherit',
				'post_mime_type'         => 'image',
				'posts_per_page'         => 1,
				'orderby'                => 'rand',
				'update_post_term_cache' => false,
			) );

			// Get the first image it finds.
			if ( $attachments->have_posts() ) {
				$attachment = get_post( $attachments->posts[0]->ID );
			}
		}

		if ( $attachment ) {
			$image = wp_get_attachment_image_src( $attachment->ID, 'full' );
			$this
				->set( 'name', esc_html( $attachment->post_title ) )
				->set( 'description', wp_trim_words( strip_shortcodes( $attachment->post_excerpt ), 30, null ) );
		}

		$this
			->set( 'url', $image[0] )
			->set( 'width', $image[1] )
			->set( 'height', $image[2] );

	}
}
