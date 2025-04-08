<?php
/**
 * Schema.org structured data Person type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 2.0.0
 */

/**
 * Generates the Person type schema json-ld.
 *
 * @link  https://schema.org/Person
 *
 * @since 2.0.0
 */
class APP_Schema_Type_Person_User extends APP_Schema_Type_Person {

	/**
	 * Constructor.
	 *
	 * @param WP_User $user User object to be used for building schema type.
	 */
	public function __construct( WP_User $user = null ) {
		if ( ! $user ) {
			return;
		}

		$this->set( 'name', esc_html( $user->display_name ) );
	}

}
