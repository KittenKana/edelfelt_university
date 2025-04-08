<?php
/**
 * Schema.org structured data WebPage type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

/**
 * Generates the WebPage type schema json-ld.
 *
 * @link  https://schema.org/WebPage
 * @since 1.0.0
 *
 * @since 2.0.0 Class is abstract
 */
abstract class APP_Schema_Type_WebPage extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'WebPage';

	/**
	 * A set of links that can help a user understand and navigate a website hierarchy.
	 *
	 * @var APP_Schema_Type_BreadcrumbList|APP_Schema_Property
	 */
	protected $breadcrumb;

	/**
	 * Date on which the content on this web page was last reviewed for accuracy and/or completeness.
	 *
	 * @var APP_Schema_Property
	 */
	protected $lastReviewed;

	/**
	 * Indicates if this web page element is the main subject of the page. Supersedes aspect.
	 *
	 * @var APP_Schema_Type_WebPageElement
	 */
	protected $mainContentOfPage;

	/**
	 * Indicates the main image on the page.
	 *
	 * @var APP_Schema_Type_ImageObject
	 */
	protected $primaryImageOfPage;

	/**
	 * A link related to this web page, for example to other related web pages.
	 *
	 * @var APP_Schema_Property
	 */
	protected $relatedLink;

	/**
	 * People or organizations that have reviewed the content on this web page for accuracy and/or completeness.
	 *
	 * @var APP_Schema_Type_Organization|APP_Schema_Type_Person
	 */
	protected $reviewedBy;

	/**
	 * One of the more significant URLs on the page. Typically, these are the non-navigation links that are clicked on the most. Supersedes significantLinks.
	 *
	 * @var APP_Schema_Property
	 */
	protected $significantLink;

	/**
	 * Indicates sections of a Web page that are particularly "speakable" in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the "speakable" property serves to indicate the parts most likely to be generally useful for speech.
	 *
	 * @var APP_Schema_Type_SpeakableSpecification|APP_Schema_Property
	 */
	protected $speakable;

	/**
	 * One of the domain specialities to which this web page"s content applies.
	 *
	 * @var APP_Schema_Type_Specialty
	 */
	protected $specialty;

	/**
	 * Generates the WebPage schema type json-ld code.
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
		$instance = new APP_Schema_Type_WebPage_Post( $post );
		return $instance->build();
	}
}
