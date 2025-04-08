<?php

/**
 * Schema.org structured data Rating type class.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract Rating schema type.
 *
 * A rating is an evaluation on a numeric scale, such as 1 to 5 stars.
 *
 * @link https://schema.org/Rating
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_Rating extends APP_Schema_Type_Intangible {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Rating';

	/**
	 * The author of this content or rating. Please note that author is special
	 * in that HTML 5 provides a special mechanism for indicating authorship via
	 * the rel tag. That is equivalent to this and may be used interchangeably.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $author;

	/**
	 * The highest value allowed in this rating system.
	 * If bestRating is omitted, 5 is assumed.
	 *
	 * @var APP_Schema_Property
	 */
	protected $bestRating;

	/**
	 * The rating for the content.
	 *
	 * @var APP_Schema_Property
	 */
	protected $ratingValue;

	/**
	 * The lowest value allowed in this rating system.
	 * If worstRating is omitted, 1 is assumed.
	 *
	 * @var APP_Schema_Property
	 */
	protected $worstRating;

}
