<?php

/**
 * Schema.org structured data Offer type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract Offer schema type.
 *
 * @link https://schema.org/Offer
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_Offer extends APP_Schema_Type_Intangible {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Offer';

	/**
	 * The payment method(s) accepted by seller for this offer.
	 *
	 * @var APP_Schema_Type_LoanOrCredit|APP_Schema_Type_PaymentMethod
	 */
	protected $acceptedPaymentMethod;

	/**
	 * An additional offer that can only be obtained in combination with the
	 * first base offer (e.g. supplements and extensions that are available for
	 * a surcharge).
	 *
	 * @var APP_Schema_Type_Offer
	 */
	protected $addOn;

	/**
	 * The amount of time that is required between accepting the offer and the
	 * actual usage of the resource or service.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $advanceBookingRequirement;

	/**
	 * The overall rating, based on a collection of reviews or ratings, of the
	 * item.
	 *
	 * @var APP_Schema_Type_AggregateRating
	 */
	protected $aggregateRating;

	/**
	 * The geographic area where a service or offered item is provided.
	 * Supersedes serviceArea.
	 *
	 * @var APP_Schema_Type_AdministrativeArea|APP_Schema_Type_GeoShape|
	 * APP_Schema_Type_Place|APP_Schema_Property
	 */
	protected $areaServed;

	/**
	 * The availability of this item—for example In stock, Out of stock,
	 * Pre-order, etc.
	 *
	 * @var APP_Schema_Type_ItemAvailability
	 */
	protected $availability;

	/**
	 * The end of the availability of the product or service included in the
	 * offer.
	 *
	 * @var APP_Schema_Property
	 */
	protected $availabilityEnds;

	/**
	 * The beginning of the availability of the product or service included in
	 * the offer.
	 *
	 * @var APP_Schema_Property
	 */
	protected $availabilityStarts;

	/**
	 * The place(s) from which the offer can be obtained (e.g. store locations).
	 *
	 * @var APP_Schema_Type_Place
	 */
	protected $availableAtOrFrom;

	/**
	 * The delivery method(s) available for this offer.
	 *
	 * @var APP_Schema_Type_DeliveryMethod
	 */
	protected $availableDeliveryMethod;

	/**
	 * The business function (e.g. sell, lease, repair, dispose) of the offer or
	 * component of a bundle (TypeAndQuantityNode).
	 * The default is http://purl.org/goodrelations/v1#Sell.
	 *
	 * @var APP_Schema_Type_BusinessFunction
	 */
	protected $businessFunction;

	/**
	 * A category for the item. Greater signs or slashes can be used to
	 * informally indicate a category hierarchy.
	 *
	 * @var APP_Schema_Type_PhysicalActivityCategory|APP_Schema_Property|
	 * APP_Schema_Type_Thing
	 */
	protected $category;

	/**
	 * The typical delay between the receipt of the order and the goods either
	 * leaving the warehouse or being prepared for pickup, in case the delivery
	 * method is on site pickup.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $deliveryLeadTime;

	/**
	 * The type(s) of customers for which the given offer is valid.
	 *
	 * @var APP_Schema_Type_BusinessEntityType
	 */
	protected $eligibleCustomerType;

	/**
	 * The duration for which the given offer is valid.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $eligibleDuration;

	/**
	 * The interval and unit of measurement of ordering quantities for which the
	 * offer or price specification is valid. This allows e.g. specifying that a
	 * certain freight charge is valid only for a certain quantity.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $eligibleQuantity;

	/**
	 * The ISO 3166-1 (ISO 3166-1 alpha-2) or ISO 3166-2 code, the place, or the
	 * GeoShape for the geo-political region(s) for which the offer or delivery
	 * charge specification is valid. See also ineligibleRegion.
	 *
	 * @var APP_Schema_Type_GeoShape|APP_Schema_Type_Place|APP_Schema_Property
	 */
	protected $eligibleRegion;

	/**
	 * The transaction volume, in a monetary unit, for which the offer or price
	 * specification is valid, e.g. for indicating a minimal purchasing volume,
	 * to express free shipping above a certain order volume, or to limit the
	 * acceptance of credit cards to purchases to a certain minimal amount.
	 *
	 * @var APP_Schema_Type_PriceSpecification
	 */
	protected $eligibleTransactionVolume;

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
	 * This links to a node or nodes indicating the exact quantity of the
	 * products included in the offer.
	 *
	 * @var APP_Schema_Type_TypeAndQuantityNode
	 */
	protected $includesObject;

	/**
	 * The ISO 3166-1 (ISO 3166-1 alpha-2) or ISO 3166-2 code, the place, or the
	 * GeoShape for the geo-political region(s) for which the offer or delivery
	 * charge specification is not valid, e.g. a region where the transaction is
	 * not allowed. See also eligibleRegion.
	 *
	 * @var APP_Schema_Type_GeoShape|APP_Schema_Type_Place|APP_Schema_Property
	 */
	protected $ineligibleRegion;

	/**
	 * The current approximate inventory level for the item or items.
	 *
	 * @var APP_Schema_Type_QuantitativeValue
	 */
	protected $inventoryLevel;

	/**
	 * A predefined value from OfferItemCondition or a textual description of
	 * the condition of the product or service, or the products or services
	 * included in the offer.
	 *
	 * @var APP_Schema_Type_OfferItemCondition
	 */
	protected $itemCondition;

	/**
	 * The item being offered.
	 *
	 * @var APP_Schema_Type_Product|APP_Schema_Type_Service
	 */
	protected $itemOffered;

	/**
	 * The Manufacturer Part Number (MPN) of the product, or the product to
	 * which the offer refers.
	 *
	 * @var APP_Schema_Property
	 */
	protected $mpn;

	/**
	 * A pointer to the organization or person making the offer.
	 * Inverse property: makesOffer.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $offeredBy;

	/**
	 * The offer price of a product, or of a price component when attached to
	 * PriceSpecification and its subtypes. Usage guidelines: Use the
	 * priceCurrency property (with ISO 4217 codes e.g. 'USD') instead of
	 * including ambiguous symbols such as '$' in the value. Use '.' (Unicode
	 * 'FULL STOP' (U+002E)) rather than ',' to indicate a decimal point. Avoid
	 * using these symbols as a readability separator. Note that both RDFa and
	 * Microdata syntax allow the use of a 'content=' attribute for publishing
	 * simple machine-readable values alongside more human-friendly formatting.
	 * Use values from 0123456789 (Unicode 'DIGIT ZERO' (U+0030) to 'DIGIT NINE'
	 * (U+0039)) rather than superficially similiar Unicode symbols.
	 *
	 * @var APP_Schema_Property
	 */
	protected $price;

	/**
	 * The currency (in 3-letter ISO 4217 format) of the price or a price
	 * component, when attached to PriceSpecification and its subtypes.
	 *
	 * @var APP_Schema_Property
	 */
	protected $priceCurrency;

	/**
	 * One or more detailed price specifications, indicating the unit price and
	 * delivery or payment charges.
	 *
	 * @var APP_Schema_Type_PriceSpecification
	 */
	protected $priceSpecification;

	/**
	 * The date after which the price is no longer available.
	 *
	 * @var APP_Schema_Property
	 */
	protected $priceValidUntil;

	/**
	 * A review of the item. Supersedes reviews.
	 *
	 * @var APP_Schema_Type_Review
	 */
	protected $review;

	/**
	 * An entity which offers (sells / leases / lends / loans) the services /
	 * goods. A seller may also be a provider. Supersedes merchant, vendor.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $seller;

	/**
	 * The serial number or any alphanumeric identifier of a particular product.
	 * When attached to an offer, it is a shortcut for the serial number of the
	 * product included in the offer.
	 *
	 * @var APP_Schema_Property
	 */
	protected $serialNumber;

	/**
	 * The Stock Keeping Unit (SKU), i.e. a merchant-specific identifier for a
	 * product or service, or the product to which the offer refers.
	 *
	 * @var APP_Schema_Property
	 */
	protected $sku;

	/**
	 * The date when the item becomes valid.
	 *
	 * @var APP_Schema_Property
	 */
	protected $validFrom;

	/**
	 * The date after when the item is not valid. For example the end of an
	 * offer, salary period, or a period of opening hours.
	 *
	 * @var APP_Schema_Property
	 */
	protected $validThrough;

	/**
	 * The warranty promise(s) included in the offer.
	 * Supersedes warrantyPromise.
	 *
	 * @var APP_Schema_Type_WarrantyPromise
	 */
	protected $warranty;

}
