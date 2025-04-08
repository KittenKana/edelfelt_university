<?php
/**
 * Schema.org structured data Organization type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Organization type schema json-ld.
 *
 * @link  https://schema.org/Organization
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Organization_Home extends APP_Schema_Type_Organization {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this
			->set( 'url', get_home_url() )
			->set( 'name', esc_html( get_bloginfo( 'name' ) ) )
			->set( 'logo', new APP_Schema_Type_ImageObject_Site_Logo() );
	}

}
