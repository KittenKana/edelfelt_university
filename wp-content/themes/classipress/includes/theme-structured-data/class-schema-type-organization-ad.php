<?php
/**
 * Schema.org structured data Organization type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the Organization type schema json-ld.
 *
 * @link  https://schema.org/Organization
 *
 * @since 4.0.0
 */
class CP_Schema_Type_Organization_Ad extends APP_Schema_Type_Organization_Post {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		parent::__construct( $post );

		$this->set( 'address', new CP_Schema_Type_PostalAddress_Ad( $post ) );

		if ( $post->cp_phone ) {
			$this->set( 'telephone', $post->cp_phone );
		}
	}

}
