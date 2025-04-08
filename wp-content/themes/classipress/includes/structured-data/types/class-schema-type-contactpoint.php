<?php

/**
 * Schema.org Contact Point type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract ContactPoint schema type.
 *
 * @link https://schema.org/ContactPoint
 * @link https://developers.google.com/gmail/markup/reference/types/ContactPoint
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_ContactPoint extends APP_Schema_Type_StructuredValue {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'ContactPoint';

	/**
	 * The geographic area where a service or offered item is provided. Supersedes serviceArea.
	 *
	 * @var APP_Schema_Type_AdministrativeArea|APP_Schema_Type_GeoShape|
	 * APP_Schema_Type_Place|APP_Schema_Property
	 */
	protected $areaServed;

	/**
	 * A language someone may use with or at the item, service or place. Please
	 * use one of the language codes from the IETF BCP 47 standard.
	 * See also inLanguage
	 *
	 * @var APP_Schema_Type_Language|APP_Schema_Property
	 */
	protected $availableLanguage;

	/**
	 * An option available on this contact point (e.g. a toll-free number or
	 * support for hearing-impaired callers).
	 *
	 * @var APP_Schema_Type_ContactPointOption
	 */
	protected $contactOption;

	/**
	 * A person or organization can have different contact points, for different
	 * purposes. For example, a sales contact point, a PR contact point and so
	 * on. This property is used to specify the kind of contact point.
	 *
	 * @var APP_Schema_Property
	 */
	protected $contactType;

	/**
	 * Email address.
	 *
	 * @var APP_Schema_Property
	 */
	protected $email;

	/**
	 * The fax number.
	 *
	 * @var APP_Schema_Property
	 */
	protected $faxNumber;

	/**
	 * The hours during which this service or contact is available.
	 *
	 * @var APP_Schema_Type_OpeningHoursSpecification
	 */
	protected $hoursAvailable;

	/**
	 * The product or service this support contact point is related to (such as
	 * product support for a particular product line). This can be a specific
	 * product or product line (e.g. "iPhone") or a general category of products
	 * or services (e.g. "smartphones").
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Property
	 */
	protected $productSupported;

	/**
	 * The telephone number.
	 *
	 * @var APP_Schema_Property
	 */
	protected $telephone;

}
