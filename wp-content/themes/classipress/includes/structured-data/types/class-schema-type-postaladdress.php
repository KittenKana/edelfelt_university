<?php
/**
 * Schema.org structured data PostalAddress type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract PostalAddress schema type.
 *
 * @link https://schema.org/PostalAddress
 * @link https://developers.google.com/gmail/markup/reference/types/PostalAddress
 *
 * @since 1.0.0
 */
abstract class APP_Schema_Type_PostalAddress extends APP_Schema_Type_ContactPoint {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'PostalAddress';

	/**
	 * The country. For example, USA. You can also provide the two-letter
	 * ISO 3166-1 alpha-2 country code.
	 *
	 * @var APP_Schema_Type_Country|APP_Schema_Property
	 */
	protected $addressCountry;

	/**
	 * The locality. For example, Mountain View.
	 *
	 * @var APP_Schema_Property
	 */
	protected $addressLocality;

	/**
	 * The region. For example, CA.
	 *
	 * @var APP_Schema_Property
	 */
	protected $addressRegion;

	/**
	 * The post office box number for PO box addresses.
	 *
	 * @var APP_Schema_Property
	 */
	protected $postOfficeBoxNumber;

	/**
	 * The postal code. For example, 94043.
	 *
	 * @var APP_Schema_Property
	 */
	protected $postalCode;

	/**
	 * The street address. For example, 1600 Amphitheatre Pkwy.
	 *
	 * @var APP_Schema_Property
	 */
	protected $streetAddress;

	/**
	 * Generates the PostalAddress schema type json-ld code.
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
			'@type'           => 'PostalAddress',
			'streetAddress'   => $fields['street_number'] . ' ' . $fields['street'],
			'addressLocality' => $fields['city'],
			'addressRegion'   => $fields['state_short'],
			'postalCode'      => $fields['postal_code'],
			'addressCountry'  => $fields['country_short'],
		);

		/**
		 * Filters the PostalAddress type schema properties.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output List of PostalAddress type schema properties.
		 */
		return apply_filters( 'appthemes_schema_type_postaladdress', $output );
	}
}
