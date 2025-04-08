<?php

/**
 * Schema.org structured data general type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract Thing schema type.
 *
 * @link https://schema.org/Thing
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_Thing extends APP_Schema_Type {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Thing';

	/**
	 * An additional type for the item, typically used for adding more specific
	 * types from external vocabularies in microdata syntax.
	 *
	 * @var APP_Schema_Property
	 */
	protected $additionalType;

	/**
	 * An alias for the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $alternateName;

	/**
	 * A description of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $description;

	/**
	 * A sub property of description. A short description of the item used to
	 * disambiguate from other, similar items. Information from other properties
	 * (in particular, name) may be necessary for the description to be useful
	 * for disambiguation.
	 *
	 * @var APP_Schema_Property
	 */
	protected $disambiguatingDescription;

	/**
	 * The identifier property represents any kind of identifier for any kind of
	 * Thing, such as ISBNs, GTIN codes, UUIDs etc. Schema.org provides
	 * dedicated properties for representing many of these, either as textual
	 * strings or as URL (URI) links.
	 *
	 * @var APP_Schema_Property
	 */
	protected $identifier;

	/**
	 * An image of the item. This can be a URL or a fully described ImageObject.
	 *
	 * @link http://schema.org/ImageObject
	 *
	 * @var APP_Schema_Type_ImageObject
	 */
	protected $image;

	/**
	 * Indicates a page (or other CreativeWork) for which this thing is the main
	 * entity being described.
	 *
	 * @var APP_Schema_Type_CreativeWork|APP_Schema_Property
	 */
	protected $mainEntityOfPage;

	/**
	 * The name of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $name;

	/**
	 * Indicates a potential Action, which describes an idealized action in
	 * which this thing would play an 'object' role.
	 *
	 * @var APP_Schema_Type_Action
	 */
	protected $potentialAction;

	/**
	 * URL of a reference Web page that unambiguously indicates the item's
	 * identity. E.g. the URL of the item's Wikipedia page, Wikidata entry, or
	 * official website.
	 *
	 * @var APP_Schema_Property
	 */
	protected $sameAs;

	/**
	 * URL of the item.
	 *
	 * @var APP_Schema_Property
	 */
	protected $url;

}
