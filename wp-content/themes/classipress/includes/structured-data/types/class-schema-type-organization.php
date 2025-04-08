<?php
/**
 * Schema.org structured data Organization type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract Organization schema type.
 *
 * @since 1.0.0
 *
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Organization extends APP_Schema_Type_Thing {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Organization';

	/**
	 * For a NewsMediaOrganization or other news-related Organization, a
	 * statement about public engagement activities (for news media, the
	 * newsroom’s), including involving the public - digitally or otherwise --
	 * in coverage decisions, reporting and activities after publication.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $actionableFeedbackPolicy;

	/**
	 * Physical address of the item.
	 *
	 * @var APP_Schema_Type_PostalAddress|APP_Schema_Property
	 */
	protected $address;

	/**
	 * The overall rating, based on a collection of reviews or ratings, of the
	 * item.
	 *
	 * @var APP_Schema_Type_AggregateRating
	 */
	protected $aggregateRating;

	/**
	 * Alumni of an organization. Inverse property: alumniOf.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $alumni;

	/**
	 * The geographic area where a service or offered item is provided.
	 * Supersedes serviceArea.
	 *
	 * @var APP_Schema_Type_AdministrativeArea|APP_Schema_Type_GeoShape|
	 * APP_Schema_Type_Place|APP_Schema_Property
	 */
	protected $areaServed;

	/**
	 * An award won by or for this item. Supersedes awards.
	 *
	 * @var APP_Schema_Property
	 */
	protected $award;

	/**
	 * The brand(s) associated with a product or service, or the brand(s)
	 * maintained by an organization or business person.
	 *
	 * @var APP_Schema_Type_Brand|APP_Schema_Type_Organization
	 */
	protected $brand;

	/**
	 * A contact point for a person or organization. Supersedes contactPoints.
	 *
	 * @var APP_Schema_Type_ContactPoint
	 */
	protected $contactPoint;

	/**
	 * For an Organization (e.g. NewsMediaOrganization), a statement describing
	 * (in news media, the newsroom’s) disclosure and correction policy for
	 * errors.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $correctionsPolicy;

	/**
	 * A relationship between an organization and a department of that
	 * organization, also described as an organization (allowing different urls,
	 * logos, opening hours). For example: a store with a pharmacy, or a bakery
	 * with a cafe.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $department;

	/**
	 * The date that this organization was dissolved.
	 *
	 * @var APP_Schema_Property
	 */
	protected $dissolutionDate;

	/**
	 * Statement on diversity policy by an Organization e.g. a
	 * NewsMediaOrganization. For a NewsMediaOrganization, a statement
	 * describing the newsroom’s diversity policy on both staffing and sources,
	 * typically providing staffing data.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $diversityPolicy;

	/**
	 * The Dun & Bradstreet DUNS number for identifying an organization or
	 * business person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $duns;

	/**
	 * Email address.
	 *
	 * @var APP_Schema_Property
	 */
	protected $email;

	/**
	 * Someone working for this organization. Supersedes employees.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $employee;

	/**
	 * Statement about ethics policy, e.g. of a NewsMediaOrganization regarding
	 * journalistic and publishing practices, or of a Restaurant, a page
	 * describing food source policies. In the case of a NewsMediaOrganization,
	 * an ethicsPolicy is typically a statement describing the personal,
	 * organizational, and corporate standards of behavior expected by the
	 * organization.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $ethicsPolicy;

	/**
	 * Upcoming or past event associated with this place, organization, or
	 * action. Supersedes events.
	 *
	 * @var APP_Schema_Type_Event
	 */
	protected $event;

	/**
	 * The fax number.
	 *
	 * @var APP_Schema_Property
	 */
	protected $faxNumber;

	/**
	 * A person who founded this organization. Supersedes founders.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $founder;

	/**
	 * The date that this organization was founded.
	 *
	 * @var APP_Schema_Property
	 */
	protected $foundingDate;

	/**
	 * The place where the Organization was founded.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $foundingLocation;

	/**
	 * A person or organization that supports (sponsors) something through some
	 * kind of financial contribution.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $funder;

	/**
	 * The Global Location Number (GLN, sometimes also referred to as
	 * International Location Number or ILN) of the respective organization,
	 * person, or place. The GLN is a 13-digit number used to identify parties
	 * and physical locations.
	 *
	 * @var APP_Schema_Property
	 */
	protected $globalLocationNumber;

	/**
	 * Indicates an OfferCatalog listing for this Organization, Person, or
	 * Service.
	 *
	 * @var APP_Schema_Type_OfferCatalog
	 */
	protected $hasOfferCatalog;

	/**
	 * Points-of-Sales operated by the organization or person.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $hasPOS;

	/**
	 * The International Standard of Industrial Classification of All Economic
	 * Activities (ISIC), Revision 4 code for a particular organization,
	 * business person, or place.
	 *
	 * @var APP_Schema_Property
	 */
	protected $isicV4;

	/**
	 * The official name of the organization, e.g. the registered company name.
	 *
	 * @var APP_Schema_Property
	 */
	protected $legalName;

	/**
	 * An organization identifier that uniquely identifies a legal entity as
	 * defined in ISO 17442.
	 *
	 * @var APP_Schema_Property
	 */
	protected $leiCode;

	/**
	 * The location of for example where the event is happening, an organization
	 * is located, or where an action takes place.
	 *
	 * @var APP_Schema_Type_Place|APP_Schema_Type_PostalAddress|
	 * APP_Schema_Property
	 */
	protected $location;

	/**
	 * An associated logo.
	 *
	 * @var APP_Schema_Type_ImageObject|APP_Schema_Property
	 */
	protected $logo;

	/**
	 * A pointer to products or services offered by the organization or person.
	 * Inverse property: offeredBy.
	 *
	 * @var APP_Schema_Type_Offer
	 */
	protected $makesOffer;

	/**
	 * A member of an Organization or a ProgramMembership. Organizations can be
	 * members of organizations; ProgramMembership is typically for individuals.
	 * Supersedes members, musicGroupMember. Inverse property: memberOf.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $member;

	/**
	 * An Organization (or ProgramMembership) to which this Person or
	 * Organization belongs. Inverse property: member.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_ProgramMembership
	 */
	protected $memberOf;

	/**
	 * The North American Industry Classification System (NAICS) code for a
	 * particular organization or business person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $naics;

	/**
	 * The number of employees in an organization e.g. business.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $numberOfEmployees;

	/**
	 * Products owned by the organization or person.
	 *
	 * @var APP_Schema_Type_OwnershipInfo|APP_Schema_Type_Product
	 */
	protected $owns;

	/**
	 * The larger organization that this organization is a subOrganization of,
	 * if any. Supersedes branchOf. Inverse property: subOrganization.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $parentOrganization;

	/**
	 * The publishingPrinciples property indicates (typically via URL) a
	 * document describing the editorial principles of an Organization (or
	 * individual e.g. a Person writing a blog) that relate to their activities
	 * as a publisher, e.g. ethics or diversity policies. When applied to a
	 * CreativeWork (e.g. NewsArticle) the principles are those of the party
	 * primarily responsible for the creation of the CreativeWork. While such
	 * policies are most typically expressed in natural language, sometimes
	 * related information (e.g. indicating a funder) can be expressed using
	 * schema.org terminology.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $publishingPrinciples;

	/**
	 * A review of the item. Supersedes reviews.
	 *
	 * @var APP_Schema_Type_Review
	 */
	protected $review;

	/**
	 * A pointer to products or services sought by the organization or person
	 * (demand).
	 *
	 * @var APP_Schema_Type_Demand
	 */
	protected $seeks;

	/**
	 * A person or organization that supports a thing through a pledge, promise,
	 * or financial contribution. e.g. a sponsor of a Medical Study or a
	 * corporate sponsor of an event.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $sponsor;

	/**
	 * A relationship between two organizations where the first includes the
	 * second, e.g., as a subsidiary. See also: the more specific department
	 * property. Inverse property: parentOrganization.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $subOrganization;

	/**
	 * The Tax / Fiscal ID of the organization or person, e.g. the TIN in the US
	 * or the CIF/NIF in Spain.
	 *
	 * @var APP_Schema_Property
	 */
	protected $taxID;

	/**
	 * The telephone number.
	 *
	 * @var APP_Schema_Property
	 */
	protected $telephone;

	/**
	 * For an Organization (typically a NewsMediaOrganization), a statement
	 * about policy on use of unnamed sources and the decision process required.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $unnamedSourcesPolicy;

	/**
	 * The Value-added Tax ID of the organization or person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $vatID;

	/**
	 * Generates the Organization schema type json-ld code.
	 *
	 * @link  https://schema.org/Organization
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 */
	public static function type() {

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );
		$instance = new APP_Schema_Type_Organization_Home();
		return $instance->build();
	}
}
