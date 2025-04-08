<?php
/**
 * Schema.org structured data LocalBusiness type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the LocalBusiness type schema json-ld.
 *
 * @link  https://schema.org/LocalBusiness
 *
 * @since 4.0.0
 */
class CP_Schema_Type_LocalBusiness_Ad extends APP_Schema_Type_LocalBusiness_Post {

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

		$this
			->set( 'address', new CP_Schema_Type_PostalAddress_Ad( $post ) )
			->set( 'geo', new CP_Schema_Type_GeoCoordinates_Ad( $post ) );

		if ( $post->cp_phone ) {
			$this->set( 'telephone', $post->cp_phone );
		}

		if ( $post->cp_price ) {
			$this->set( 'priceRange', $post->cp_price );
		}
	}

}
