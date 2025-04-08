<?php
/**
 * Schema.org structured data WebSite type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the WebSite type schema json-ld.
 *
 * @link  https://schema.org/WebSite
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Website_Home extends APP_Schema_Type_Website {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this
			->set( 'url', get_home_url() )
			->set( 'name', esc_html( get_bloginfo( 'name' ) ) )
			->set( 'potentialAction', new APP_Schema_Type_SearchAction_General() );
	}

}
