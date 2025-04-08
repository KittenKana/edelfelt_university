<?php
/**
 * Schema.org structured data GeoCoordinates type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract GeoCoordinates schema type.
 *
 * @link https://schema.org/GeoCoordinates
 * @link https://developers.google.com/gmail/markup/reference/types/GeoCoordinates
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_GeoCoordinates extends APP_Schema_Type_StructuredValue {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'GeoCoordinates';

	/**
	 * Physical address of the item.
	 *
	 * @var APP_Schema_Type_PostalAddress|APP_Schema_Property
	 */
	protected $address;

	/**
	 * The country. For example, USA. You can also provide the two-letter
	 * ISO 3166-1 alpha-2 country code.
	 *
	 * @var APP_Schema_Type_Country|APP_Schema_Property
	 */
	protected $addressCountry;

	/**
	 * The elevation of a location (WGS 84).
	 *
	 * @var APP_Schema_Property
	 */
	protected $elevation;

	/**
	 * The latitude of a location. For example 37.42242 (WGS 84).
	 *
	 * @var APP_Schema_Property
	 */
	protected $latitude;

	/**
	 * The longitude of a location. For example -122.08585 (WGS 84).
	 *
	 * @var APP_Schema_Property
	 */
	protected $longitude;

	/**
	 * The postal code. For example, 94043.
	 *
	 * @var APP_Schema_Property
	 */
	protected $postalCode;

	/**
	 * Generates the GeoCoordinates schema type json-ld code.
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 */
	public static function type() {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );

		// Grab all the geo fields.
		if ( ! is_array( $fields = APP_Google_Geocoding::get_post_meta( $post->ID ) ) ) {
			return;
		}

		$output = array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => $fields['lat'],
			'longitude' => $fields['lng'],
		);

		/**
		 * Filters the GeoCoordinates type schema properties.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output List of GeoCoordinates type schema properties.
		 */
		return apply_filters( 'appthemes_schema_type_geocoordinates', $output );
	}
}
