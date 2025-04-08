<?php
/**
 * Schema.org structured data ImageObject type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Site Logo ImageObject type schema json-ld.
 *
 * @since 2.0.0
 */
class APP_Schema_Type_ImageObject_Site_Logo extends APP_Schema_Type_ImageObject {

	/**
	 * Constructor.
	 */
	public function __construct() {

		// Prefill array and error message since ImageObject is a required type.
		$image          = array( __( 'No image found', APP_TD ), 0, 0, 0 );
		$custom_logo_id = get_theme_mod( 'custom_logo' );

		if ( $custom_logo_id ) {
			$image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		}

		$this
			->set( 'url', $image[0] )
			->set( 'width', $image[1] )
			->set( 'height', $image[2] );

	}
}
