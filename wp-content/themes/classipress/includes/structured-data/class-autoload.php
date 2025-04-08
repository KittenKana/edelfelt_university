<?php
/**
 * Schema Type Autoloader
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Autoloader class.
 */
class APP_Structured_Data_Autoload {

	/**
	 * The class map array.
	 *
	 * @var array
	 */
	private static $class_map = array();

	/**
	 * Adds class map to the end of registered.
	 *
	 * Overrides already been mapped classes.
	 *
	 * @param array $class_map The class map array.
	 *                         Keys - class names, values - class file path.
	 */
	static function add_class_map( array $class_map ) {
		self::$class_map = array_merge( self::$class_map, $class_map );
	}

	/**
	 * Registers autoloader in the system.
	 */
	static function register() {
		$dir = dirname( __FILE__ );
		self::add_class_map( array(
			'APP_Schema_Type_Buildable'              => "$dir/interface-schema-type-buildable.php",
			'APP_Schema_Property'                    => "$dir/class-schema-property.php",
			'APP_Schema_Type'                        => "$dir/types/class-schema-type.php",
			'APP_Schema_Type_Action'                 => "$dir/types/class-schema-type-action.php",
			'APP_Schema_Type_AggregateRating'        => "$dir/types/class-schema-type-aggregaterating.php",
			'APP_Schema_Type_Article'                => "$dir/types/class-schema-type-article.php",
			'APP_Schema_Type_BlogPosting'            => "$dir/types/class-schema-type-blogposting.php",
			'APP_Schema_Type_BlogPosting_Post'       => "$dir/types/class-schema-type-blogposting-post.php",
			'APP_Schema_Type_Comment'                => "$dir/types/class-schema-type-comment.php",
			'APP_Schema_Type_Comment_Single'         => "$dir/types/class-schema-type-comment-single.php",
			'APP_Schema_Type_Comments_Post'          => "$dir/types/class-schema-type-comments-post.php",
			'APP_Schema_Type_ContactPoint'           => "$dir/types/class-schema-type-contactpoint.php",
			'APP_Schema_Type_CreativeWork'           => "$dir/types/class-schema-type-creativework.php",
			'APP_Schema_Type_GeoCoordinates'         => "$dir/types/class-schema-type-geocoordinates.php",
			'APP_Schema_Type_ImageObject'            => "$dir/types/class-schema-type-imageobject.php",
			'APP_Schema_Type_ImageObject_Attachment' => "$dir/types/class-schema-type-imageobject-attachment.php",
			'APP_Schema_Type_ImageObject_Site_Logo'  => "$dir/types/class-schema-type-imageobject-site-logo.php",
			'APP_Schema_Type_Intangible'             => "$dir/types/class-schema-type-intangible.php",
			'APP_Schema_Type_JobPosting'             => "$dir/types/class-schema-type-jobposting.php",
			'APP_Schema_Type_JobPosting_Post'        => "$dir/types/class-schema-type-jobposting-post.php",
			'APP_Schema_Type_LocalBusiness'          => "$dir/types/class-schema-type-localbusiness.php",
			'APP_Schema_Type_LocalBusiness_Post'     => "$dir/types/class-schema-type-localbusiness-post.php",
			'APP_Schema_Type_MediaObject'            => "$dir/types/class-schema-type-mediaobject.php",
			'APP_Schema_Type_Offer'                  => "$dir/types/class-schema-type-offer.php",
			'APP_Schema_Type_Offer_Post'             => "$dir/types/class-schema-type-offer-post.php",
			'APP_Schema_Type_Organization'           => "$dir/types/class-schema-type-organization.php",
			'APP_Schema_Type_Organization_Home'      => "$dir/types/class-schema-type-organization-home.php",
			'APP_Schema_Type_Organization_Post'      => "$dir/types/class-schema-type-organization-post.php",
			'APP_Schema_Type_Person'                 => "$dir/types/class-schema-type-person.php",
			'APP_Schema_Type_Person_Commenter'       => "$dir/types/class-schema-type-person-commenter.php",
			'APP_Schema_Type_Person_User'            => "$dir/types/class-schema-type-person-user.php",
			'APP_Schema_Type_Place'                  => "$dir/types/class-schema-type-place.php",
			'APP_Schema_Type_PostalAddress'          => "$dir/types/class-schema-type-postaladdress.php",
			'APP_Schema_Type_Product'                => "$dir/types/class-schema-type-product.php",
			'APP_Schema_Type_Product_Post'           => "$dir/types/class-schema-type-product-post.php",
			'APP_Schema_Type_Rating'                 => "$dir/types/class-schema-type-rating.php",
			'APP_Schema_Type_Review'                 => "$dir/types/class-schema-type-review.php",
			'APP_Schema_Type_SearchAction'           => "$dir/types/class-schema-type-searchaction.php",
			'APP_Schema_Type_SearchAction_General'   => "$dir/types/class-schema-type-searchaction-general.php",
			'APP_Schema_Type_SocialMediaPosting'     => "$dir/types/class-schema-type-socialmediaposting.php",
			'APP_Schema_Type_StructuredValue'        => "$dir/types/class-schema-type-structuredvalue.php",
			'APP_Schema_Type_Thing'                  => "$dir/types/class-schema-type-thing.php",
			'APP_Schema_Type_Thing_Post'             => "$dir/types/class-schema-type-thing-post.php",
			'APP_Schema_Type_WebPage'                => "$dir/types/class-schema-type-webpage.php",
			'APP_Schema_Type_WebPage_Post'           => "$dir/types/class-schema-type-webpage-post.php",
			'APP_Schema_Type_Website'                => "$dir/types/class-schema-type-website.php",
			'APP_Schema_Type_Website_Home'           => "$dir/types/class-schema-type-website-home.php",
		) );

		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload callback.
	 *
	 * Checks the class map and loads file if it has been mapped.
	 *
	 * @param string $class Class name.
	 */
	static function autoload( $class ) {
		if ( '\\' === $class[0] ) {
			$class = substr( $class, 1 );
		}

		if ( isset( self::$class_map[ $class ] ) && is_file( self::$class_map[ $class ] ) ) {
			require self::$class_map[ $class ];
		}
	}
}
