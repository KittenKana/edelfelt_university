<?php
/**
 * Schema.org structured data PostalAddress type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the PostalAddress type schema json-ld.
 *
 * @link  https://schema.org/PostalAddress
 * @link  https://developers.google.com/schemas/reference/types/PostalAddress
 *
 * @since 4.0.0
 */
class CP_Schema_Type_PostalAddress_Ad extends APP_Schema_Type_PostalAddress {

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
			->set( 'streetAddress', $post->cp_street )
			->set( 'addressLocality', $post->cp_city )
			->set( 'addressRegion', $post->cp_state )
			->set( 'postalCode', $post->cp_zipcode )
			->set( 'addressCountry', $post->cp_country );
	}

}
