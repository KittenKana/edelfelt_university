<?php
/**
 * Schema.org structured data LocalBusiness type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract LocalBusiness schema type.
 *
 * A particular physical business or branch of an organization. Examples of
 * LocalBusiness include a restaurant, a particular branch of a restaurant chain,
 * a branch of a bank, a medical practice, a club, a bowling alley, etc.
 *
 * LocalBusiness extends two types: Organization and Place. Using PHP we can
 * extend only one class (Organization), for Place we'll just copy properties.
 *
 * @link  https://schema.org/LocalBusiness
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_LocalBusiness extends APP_Schema_Type_Organization {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'LocalBusiness';

	/**
	 * The currency accepted (in ISO 4217 currency format).
	 *
	 * @var APP_Schema_Property
	 */
	protected $currenciesAccepted;

	/**
	 * The general opening hours for a business. Opening hours can be specified
	 * as a weekly time range, starting with days, then times per day. Multiple
	 * days can be listed with commas ',' separating each day. Day or time
	 * ranges are specified using a hyphen '-'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $openingHours;

	/**
	 * Cash, credit card, etc.
	 *
	 * @var APP_Schema_Property
	 */
	protected $paymentAccepted;

	/**
	 * The price range of the business, for example $$$.
	 *
	 * @var APP_Schema_Property
	 */
	protected $priceRange;

	############################# Place Properties #############################

	/**
	 * A property-value pair representing an additional characteristics of the
	 * entitity, e.g. a product feature or another characteristic for which
	 * there is no matching property in schema.org. Note: Publishers should be
	 * aware that applications designed to use specific schema.org properties
	 * (e.g. http://schema.org/width, http://schema.org/color,
	 * http://schema.org/gtin13, ...) will typically expect such data to be
	 * provided using those properties, rather than using the generic
	 * property/value mechanism.
	 *
	 * @var APP_Schema_Type_PropertyValue
	 */
	protected $additionalProperty;

	/**
	 * An amenity feature (e.g. a characteristic or service) of the
	 * Accommodation. This generic property does not make a statement about
	 * whether the feature is included in an offer for the main accommodation or
	 * available at extra costs.
	 *
	 * @var APP_Schema_Type_LocationFeatureSpecification
	 */
	protected $amenityFeature;

	/**
	 * A short textual code (also called 'store code') that uniquely identifies
	 * a place of business. The code is typically assigned by the
	 * parentOrganization and used in structured URLs. For example, in the URL
	 * http://www.starbucks.co.uk/store-locator/etc/detail/3047 the code '3047'
	 * is a branchCode for a particular branch.
	 *
	 * @var APP_Schema_Property
	 */
	protected $branchCode;

	/**
	 * The basic containment relation between a place and one that contains it.
	 * Supersedes containedIn. Inverse property: containsPlace.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $containedInPlace;

	/**
	 * The basic containment relation between a place and another that it
	 * contains. Inverse property: containedInPlace.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $containsPlace;

	/**
	 * The geo coordinates of the place.
	 *
	 * @var APP_Schema_Type_GeoCoordinates|APP_Schema_Type_GeoShape
	 */
	protected $geo;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a containing geometry to a contained geometry. 'a
	 * contains b iff no points of b lie in the exterior of a, and at least one
	 * point of the interior of b lies in the interior of a'. As defined in
	 * DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyContains;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a geometry to another that covers it. As defined in
	 * DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyCoveredBy;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a covering geometry to a covered geometry. 'Every
	 * point of b is a point of (the interior or boundary of) a'. As defined in
	 * DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyCovers;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a geometry to another that crosses it: 'a crosses b:
	 * they have some but not all interior points in common, and the dimension
	 * of the intersection is less than that of at least one of them'. As
	 * defined in DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyCrosses;

	/**
	 * Represents spatial relations in which two geometries (or the places they
	 * represent) are topologically disjoint: they have no point in common. They
	 * form a set of disconnected geometries.' (a symmetric relationship, as
	 * defined in DE-9IM)
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyDisjoint;

	/**
	 * Represents spatial relations in which two geometries (or the places they
	 * represent) are topologically equal, as defined in DE-9IM. 'Two geometries
	 * are topologically equal if their interiors intersect and no part of the
	 * interior or boundary of one geometry intersects the exterior of the
	 * other' (a symmetric relationship)
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyEquals;

	/**
	 * Represents spatial relations in which two geometries (or the places they
	 * represent) have at least one point in common. As defined in DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyIntersects;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a geometry to another that geospatially overlaps it,
	 * i.e. they have some but not all points in common. As defined in DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyOverlaps;

	/**
	 * Represents spatial relations in which two geometries (or the places they
	 * represent) touch: they have at least one boundary point in common, but no
	 * interior points.' (a symmetric relationship, as defined in DE-9IM )
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyTouches;

	/**
	 * Represents a relationship between two geometries (or the places they
	 * represent), relating a geometry to one that contains it, i.e. it is
	 * inside (i.e. within) its interior. As defined in DE-9IM.
	 *
	 * @var APP_Schema_Type_GeospatialGeometry|APP_Schema_Type_Place
	 */
	protected $geospatiallyWithin;

	/**
	 * A URL to a map of the place. Supersedes map, maps.
	 *
	 * @var APP_Schema_Type_Map|APP_Schema_Property
	 */
	protected $hasMap;

	/**
	 * A flag to signal that the item, event, or place is accessible for free.
	 * Supersedes free.
	 *
	 * @var APP_Schema_Property
	 */
	protected $isAccessibleForFree;

	/**
	 * The total number of individuals that may attend an event or venue.
	 *
	 * @var APP_Schema_Property
	 */
	protected $maximumAttendeeCapacity;

	/**
	 * The opening hours of a certain place.
	 *
	 * @var APP_Schema_Type_OpeningHoursSpecification
	 */
	protected $openingHoursSpecification;

	/**
	 * A photograph of this place. Supersedes photos.
	 *
	 * @var APP_Schema_Type_ImageObject|APP_Schema_Type_Photograph
	 */
	protected $photo;

	/**
	 * A flag to signal that the Place is open to public visitors. If this
	 * property is omitted there is no assumed default boolean value
	 *
	 * @var APP_Schema_Property
	 */
	protected $publicAccess;

	/**
	 * Indicates whether it is allowed to smoke in the place, e.g. in the
	 * restaurant, hotel or hotel room.
	 *
	 * @var APP_Schema_Property
	 */
	protected $smokingAllowed;

	/**
	 * The special opening hours of a certain place. Use this to explicitly
	 * override general opening hours brought in scope by
	 * openingHoursSpecification or openingHours.
	 *
	 * @var APP_Schema_Type_OpeningHoursSpecification
	 */
	protected $specialOpeningHoursSpecification;

	/**
	 * Generates the LocalBusiness schema type json-ld code.
	 *
	 * @link  https://schema.org/LocalBusiness
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 *
	 * @param string $type A more specific type of LocalBusiness
	 *                     (e.g. Restaurant, CafeOrCoffeeShop, etc).
	 */
	public static function type( $type = 'LocalBusiness' ) {
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );

		$localbusiness   = new APP_Schema_Type_LocalBusiness_Post( $post );
		$output          = $localbusiness->build();
		$output['@type'] = $type;

		return $output;
	}
}
