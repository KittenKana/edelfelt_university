<?php
/**
 * Schema.org structured data Product type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Abstract Product schema type.
 *
 * Any offered product or service. For example: a pair of shoes; a concert
 * ticket; the rental of a car; a haircut; or an episode of a TV show streamed
 * online.
 *
 * @since 1.0.0
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_Product extends APP_Schema_Type_Thing {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Product';

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
	 * The overall rating, based on a collection of reviews or ratings, of the
	 * item.
	 *
	 * @var APP_Schema_Type_AggregateRating
	 */
	protected $aggregateRating;

	/**
	 * An intended audience, i.e. a group for whom something was created.
	 * Supersedes serviceAudience.
	 *
	 * @var APP_Schema_Type_Audience
	 */
	protected $audience;

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
	 * A category for the item. Greater signs or slashes can be used to
	 * informally indicate a category hierarchy.
	 *
	 * @var APP_Schema_Type_PhysicalActivityCategory|APP_Schema_Property|
	 * APP_Schema_Type_Thing
	 */
	protected $category;

	/**
	 * The color of the product.
	 *
	 * @var APP_Schema_Property
	 */
	protected $color;

	/**
	 * The depth of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $depth;

	/**
	 * The GTIN-12 code of the product, or the product to which the offer
	 * refers. The GTIN-12 is the 12-digit GS1 Identification Key composed of a
	 * U.P.C. Company Prefix, Item Reference, and Check Digit used to identify
	 * trade items. See GS1 GTIN Summary for more details.
	 *
	 * @var APP_Schema_Property
	 */
	protected $gtin12;

	/**
	 * The GTIN-13 code of the product, or the product to which the offer
	 * refers. This is equivalent to 13-digit ISBN codes and EAN UCC-13. Former
	 * 12-digit UPC codes can be converted into a GTIN-13 code by simply adding
	 * a preceeding zero. See GS1 GTIN Summary for more details.
	 *
	 * @var APP_Schema_Property
	 */
	protected $gtin13;

	/**
	 * The GTIN-14 code of the product, or the product to which the offer
	 * refers. See GS1 GTIN Summary for more details.
	 *
	 * @var APP_Schema_Property
	 */
	protected $gtin14;

	/**
	 * The GTIN-8 code of the product, or the product to which the offer refers.
	 * This code is also known as EAN/UCC-8 or 8-digit EAN. See GS1 GTIN Summary
	 * for more details.
	 *
	 * @var APP_Schema_Property
	 */
	protected $gtin8;

	/**
	 * The height of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $height;

	/**
	 * A pointer to another product (or multiple products) for which this
	 * product is an accessory or spare part.
	 *
	 * @var APP_Schema_Type_Product
	 */
	protected $isAccessoryOrSparePartFor;

	/**
	 * A pointer to another product (or multiple products) for which this
	 * product is a consumable.
	 *
	 * @var APP_Schema_Type_Product
	 */
	protected $isConsumableFor;

	/**
	 * A pointer to another, somehow related product (or multiple products).
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Type_Service
	 */
	protected $isRelatedTo;

	/**
	 * A pointer to another, functionally similar product (or multiple
	 * products).
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Type_Service
	 */
	protected $isSimilarTo;

	/**
	 * A predefined value from OfferItemCondition or a textual description of
	 * the condition of the product or service, or the products or services
	 * included in the offer.
	 *
	 * @var APP_Schema_Type_OfferItemCondition
	 */
	protected $itemCondition;

	/**
	 * An associated logo.
	 *
	 * @var APP_Schema_Type_ImageObject|APP_Schema_Property
	 */
	protected $logo;

	/**
	 * The manufacturer of the product.
	 *
	 * @var APP_Schema_Type_Organization
	 */
	protected $manufacturer;

	/**
	 * A material that something is made from, e.g. leather, wool, cotton,
	 * paper.
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Property
	 */
	protected $material;

	/**
	 * The model of the product. Use with the URL of a ProductModel or a textual
	 * representation of the model identifier. The URL of the ProductModel can
	 * be from an external source. It is recommended to additionally provide
	 * strong product identifiers via the gtin8/gtin13/gtin14 and mpn
	 * properties.
	 *
	 * @var APP_Schema_Type_ProductModel|APP_Schema_Property
	 */
	protected $model;

	/**
	 * The Manufacturer Part Number (MPN) of the product, or the product to
	 * which the offer refers.
	 *
	 * @var APP_Schema_Property
	 */
	protected $mpn;

	/**
	 * An offer to provide this item—for example, an offer to sell a product,
	 * rent the DVD of a movie, perform a service, or give away tickets to an
	 * event.
	 *
	 * @var APP_Schema_Type_Offer
	 */
	protected $offers;

	/**
	 * The product identifier, such as ISBN.
	 * For example: meta itemprop='productID' content='isbn:123-456-789'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $productID;

	/**
	 * The date of production of the item, e.g. vehicle.
	 *
	 * @var APP_Schema_Property
	 */
	protected $productionDate;

	/**
	 * The date the item e.g. vehicle was purchased by the current owner.
	 *
	 * @var APP_Schema_Property
	 */
	protected $purchaseDate;

	/**
	 * The release date of a product or product model. This can be used to
	 * distinguish the exact variant of a product.
	 *
	 * @var APP_Schema_Property
	 */
	protected $releaseDate;

	/**
	 * A review of the item. Supersedes reviews.
	 *
	 * @var APP_Schema_Type_Review
	 */
	protected $review;

	/**
	 * The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a
	 * product or service, or the product to which the offer refers.
	 *
	 * @var APP_Schema_Property
	 */
	protected $sku;

	/**
	 * The weight of the product or person.
	 *
	 * @var APP_Schema_Property
	 */
	protected $weight;

	/**
	 * The width of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $width;

	/**
	 * Generates the Product schema type json-ld code.
	 *
	 * @link  https://schema.org/Product
	 * @link  https://developers.google.com/schemas/reference/types/Product
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
		global $post;

		_deprecated_function( __METHOD__, '2.0.0', __CLASS__ . ' implemented build() method' );
		$instance = new APP_Schema_Type_Product_Post( $post );
		return $instance->build();
	}
}
