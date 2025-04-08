<?php
/**
 * Schema.org structured data GeoCoordinates type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the GeoCoordinates type schema json-ld.
 *
 * @link  https://schema.org/GeoCoordinates
 * @link  https://developers.google.com/schemas/reference/types/GeoCoordinates
 *
 * @since 4.0.0
 */
class CP_Schema_Type_GeoCoordinates_Ad extends APP_Schema_Type_GeoCoordinates {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		$coordinates = cp_get_geocode( $post->ID );

		if ( ! empty( $coordinates['lat'] ) && ! empty( $coordinates['lng'] ) ) {
			$this
				->set( 'latitude', $coordinates['lat'] )
				->set( 'longitude', $coordinates['lng'] );
		}

	}

}
