<?php
/**
 * Schema.org structured data Person type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract Person schema type.
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Person extends APP_Schema_Type_Thing {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Person';

	/**
	 * An additional name for a Person, can be used for a middle name.
	 *
	 * @var APP_Schema_Property
	 */
	protected $additionalName;

	/**
	 * Physical address of the item.
	 *
	 * @var APP_Schema_Type_PostalAddress|APP_Schema_Property
	 */
	protected $address;

	/**
	 * An organization that this person is affiliated with. For example, a
	 * school/university, a club, or a team.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $affiliation;

	/**
	 * An organization that the person is an alumni of. Inverse property: alumni.
	 *
	 * @var APP_Schema_Type_EducationalOrganization|APP_Schema_Type_Organization
	 */
	protected $alumniOf;

	/**
	 * An award won by or for this item. Supersedes awards.
	 *
	 * @var APP_Schema_Property
	 */
	protected $award;

	/**
	 * Date of birth.
	 *
	 * @var APP_Schema_Property
	 */
	protected $birthDate;

	/**
	 * The place where the person was born.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $birthPlace;

	/**
	 * The brand(s) associated with a product or service, or the brand(s)
	 * maintained by an organization or business person.
	 *
	 * @var APP_Schema_Type_Brand|APP_Schema_Type_Organization
	 */
	protected $brand;

	/**
	 * A child of the person.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $children;

	/**
	 * A colleague of the person. Supersedes colleagues.
	 *
	 * @var APP_Schema_Type_Person|APP_Schema_Property
	 */
	protected $colleague;

	/**
	 * A contact point for a person or organization. Supersedes contactPoints.
	 *
	 * @var APP_Schema_Type_ContactPoint
	 */
	protected $contactPoint;

	/**
	 * Date of death.
	 *
	 * @var APP_Schema_Property
	 */
	protected $deathDate;

	/**
	 * The place where the person died.
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $deathPlace;

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
	 * Family name. In the U.S., the last name of an Person. This can be used
	 * along with givenName instead of the name property.
	 *
	 * @var APP_Schema_Property
	 */
	protected $familyName;

	/**
	 * The fax number.
	 *
	 * @var APP_Schema_Property
	 */
	protected $faxNumber;

	/**
	 * The most generic uni-directional social relation.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $follows;

	/**
	 * A person or organization that supports (sponsors) something through some
	 * kind of financial contribution.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $funder;

	/**
	 * Gender of the person. While http://schema.org/Male and
	 * http://schema.org/Female may be used, text strings are also acceptable
	 * for people who do not identify as a binary gender.
	 *
	 * @var APP_Schema_Type_GenderType|APP_Schema_Property
	 */
	protected $gender;

	/**
	 * Given name. In the U.S., the first name of a Person. This can be used
	 * along with familyName instead of the name property.
	 *
	 * @var APP_Schema_Property
	 */
	protected $givenName;

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
	 * The height of the item.
	 *
	 * @var APP_Schema_Property|APP_Schema_Property
	 */
	protected $height;

	/**
	 * A contact location for a person's residence.
	 *
	 * @var APP_Schema_Type_ContactPoint|APP_Schema_Type_Place
	 */
	protected $homeLocation;

	/**
	 * An honorific prefix preceding a Person's name such as Dr/Mrs/Mr.
	 *
	 * @var APP_Schema_Property
	 */
	protected $honorificPrefix;

	/**
	 * An honorific suffix preceding a Person's name such as M.D. /PhD/MSCSW.
	 *
	 * @var APP_Schema_Property
	 */
	protected $honorificSuffix;

	/**
	 * The International Standard of Industrial Classification of All Economic
	 * Activities (ISIC), Revision 4 code for a particular organization,
	 * business person, or place.
	 *
	 * @var APP_Schema_Property
	 */
	protected $isicV4;

	/**
	 * The job title of the person (for example, Financial Manager).
	 *
	 * @var APP_Schema_Property
	 */
	protected $jobTitle;

	/**
	 * The most generic bi-directional social/work relation.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $knows;

	/**
	 * A pointer to products or services offered by the organization or person.
	 * Inverse property: offeredBy.
	 *
	 * @var APP_Schema_Type_Offer
	 */
	protected $makesOffer;

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
	 * Nationality of the person.
	 *
	 * @var APP_Schema_Type_Country
	 */
	protected $nationality;

	/**
	 * The total financial value of the person as calculated by subtracting
	 * assets from liabilities.
	 *
	 * @var APP_Schema_Type_MonetaryAmount|APP_Schema_Type_PriceSpecification
	 */
	protected $netWorth;

	/**
	 * Products owned by the organization or person.
	 *
	 * @var APP_Schema_Type_OwnershipInfo|APP_Schema_Type_Product
	 */
	protected $owns;

	/**
	 * A parent of this person. Supersedes parents.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $parent;

	/**
	 * Event that this person is a performer or participant in.
	 *
	 * @var APP_Schema_Type_Event
	 */
	protected $performerIn;

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
	 * The most generic familial relation.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $relatedTo;

	/**
	 * A pointer to products or services sought by the organization or person
	 * (demand).
	 *
	 * @var APP_Schema_Type_Demand
	 */
	protected $seeks;

	/**
	 * A sibling of the person. Supersedes siblings.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $sibling;

	/**
	 * A person or organization that supports a thing through a pledge, promise,
	 * or financial contribution. e.g. a sponsor of a Medical Study or a
	 * corporate sponsor of an event.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $sponsor;

	/**
	 * The person's spouse.
	 *
	 * @var APP_Schema_Type_Person
	 */
	protected $spouse;

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
	 * The Value-added Tax ID of the organization or person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $vatID;

	/**
	 * The weight of the product or person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $weight;

	/**
	 * A contact location for a person's place of work.
	 *
	 * @var APP_Schema_Type_ContactPoint|APP_Schema_Type_Place
	 */
	protected $workLocation;

	/**
	 * Organizations that the person works for.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $worksFor;

	/**
	 * Generates the Person schema type json-ld code.
	 *
	 * @link  https://schema.org/Person
	 *
	 * @since 1.0.0
	 *
	 * Used for compatibility purpose.
	 *
	 * @todo Remove after Vantage and Critic migrated to Structured Data 2.0.0
	 *
	 * @deprecated since 2.0.0
	 *
	 * @param string $name The person's full name.
	 * @param array  $args Optional. Other valid Person properties to pass in. Default empty array.
	 */
	public static function type( $name, $args = array() ) {

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );

		$defaults = array(
			'name' => esc_html( $name ),
		);

		$args = wp_parse_args( $args, $defaults );

		$person = new APP_Schema_Type_Person_User();

		foreach ( $args as $name => $value ) {
			$person->set( $name, $value );
		}

		return $person->build();
	}
}
