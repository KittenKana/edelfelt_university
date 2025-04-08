<?php
/**
 * Schema.org structured data Article type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Abstract Article schema type.
 *
 * @link  https://schema.org/Article
 *
 * @since 2.0.0
 */
abstract class APP_Schema_Type_Article extends APP_Schema_Type_CreativeWork {

	/**
	 * Schema type.
	 *
	 * @var string
	 */
	protected $type = 'Article';

	/**
	 * The actual body of the article.
	 *
	 * @var APP_Schema_Property
	 */
	protected $articleBody;

	/**
	 * Articles may belong to one or more 'sections' in a magazine or newspaper, such as Sports, Lifestyle, etc.
	 *
	 * @var APP_Schema_Property
	 */
	protected $articleSection;

	/**
	 * The page on which the work ends; for example '138' or 'xvi'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $pageEnd;

	/**
	 * The page on which the work starts; for example '135' or 'xiii'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $pageStart;

	/**
	 * Any description of pages that is not separated into pageStart and pageEnd; for example, '1-6, 9, 55' or '10-12, 46-49'.
	 *
	 * @var APP_Schema_Property
	 */
	protected $pagination;

	/**
	 * Indicates sections of a Web page that are particularly 'speakable' in the sense of being highlighted as being especially appropriate for text-to-speech conversion. Other sections of a page may also be usefully spoken in particular circumstances; the 'speakable' property serves to indicate the parts most likely to be generally useful for speech. The speakable property can be repeated an arbitrary number of times, with three kinds of possible 'content-locator' values: 1.) id-value URL references - uses id-value of an element in the page being annotated. The simplest use of speakable has (potentially relative) URL values, referencing identified sections of the document concerned. 2.) CSS Selectors - addresses content in the annotated page, eg. via class attribute. Use the cssSelector property. 3.) XPaths - addresses content via XPaths (assuming an XML view of the content). Use the xpath property. For more sophisticated markup of speakable sections beyond simple ID references, either CSS selectors or XPath expressions to pick out document section(s) as speakable. For this we define a supporting type, SpeakableSpecification which is defined to be a possible value of the speakable property.
	 *
	 * @var APP_Schema_Type_SpeakableSpecification|APP_Schema_Property
	 */
	protected $speakable;

	/**
	 * The number of words in the text of the Article.
	 *
	 * @var APP_Schema_Property
	 */
	protected $wordCount;

	/**
	 * Counts words in a text string.
	 *
	 * Uses code from wp_trim_words().
	 *
	 * @param string $text Given text.
	 */
	public static function get_word_count( $text = '' ) {
		$translations = get_translations_for_domain( 'default' );
		$translation  = $translations->translate( 'words', 'Word count type. Do not translate!' );
		$words_array  = array();
		$text         = wp_strip_all_tags( $text );

		// Use code from wp_trim_words().
		if ( strpos( $translation, 'characters' ) === 0 && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, 0, PREG_SPLIT_NO_EMPTY );
		}

		return count( $words_array );
	}

}
