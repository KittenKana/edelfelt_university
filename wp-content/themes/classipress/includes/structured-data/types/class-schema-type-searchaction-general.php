<?php
/**
 * Schema.org structured data SearchAction type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the SearchAction type schema json-ld.
 *
 * @since 2.0.0
 */
class APP_Schema_Type_SearchAction_General extends APP_Schema_Type_SearchAction {

	/**
	 * Constructor.
	 */
	public function __construct() {

		/**
		 * Filters the search URL.
		 *
		 * @since 1.0.0
		 *
		 * @param string $search_url The search URL for this site with a
		 *                           `{search_term_string}` variable.
		 */
		$search_url = apply_filters( 'appthemes_schema_type_search_url', get_home_url() . '?s={search_term_string}' );

		$this
			->set( 'target', $search_url )
			->set( 'query-input', 'required name=search_term_string' );
	}

}
