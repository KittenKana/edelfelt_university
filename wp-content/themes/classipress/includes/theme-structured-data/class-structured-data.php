<?php
/**
 * Structured data markup class.
 *
 * @package ClassiPress\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Main class to hook into core structured data class.
 *
 * @since 4.0.0
 * @see APP_Structured_Data
 */
class CP_Structured_Data {

	/**
	 * Constructor
	 */
	public function __construct() {
		APP_Structured_Data::instance();
		add_action( 'appthemes_schema_build_data', array( $this, 'build_schema' ) );
		add_filter( 'appthemes_schema_type_person', array( $this, 'add_author_social_networks' ) );
	}

	/**
	 * Build the json-ld schema.org output based on page being viewed.
	 *
	 * @since 4.0.0
	 */
	public function build_schema() {
		global $post;

		$schema = APP_Structured_Data::instance();

		if ( is_front_page() ) {
			$website_home   = new APP_Schema_Type_Website_Home();
			$org_home       = new APP_Schema_Type_Organization_Home();
			$schema->data[] = $website_home->build();
			$schema->data[] = $org_home->build();
		}

		if ( is_singular( 'post' ) ) {
			$blog_posting   = new APP_Schema_Type_BlogPosting_Post( $post );
			$schema->data[] = $blog_posting->build();
		}

		if ( is_singular( APP_POST_TYPE ) ) {
			$listing_schema = $this->build_listing_schema( $post );
			if ( ! empty( $listing_schema ) ) {
				$schema->data[] = $listing_schema;
			}
		}

		if ( is_author() ) {
			$author = get_user_by( 'id', get_query_var( 'author' ) );

			if ( $author ) {
				$author_schema  = new APP_Schema_Type_Person_User( $author );
				$schema->data[] = $author_schema->build();
			}
		}

		if ( is_page() ) {
			$page = new APP_Schema_Type_WebPage_Post( $post );
			$schema->data[] = $page->build();
		}

	}

	/**
	 * Build the json-ld schema.org output for a given listing.
	 *
	 * @param WP_Post $post Given listing.
	 *
	 * @return array json-ld schema.
	 */
	public function build_listing_schema( $post ) {
		global $cp_options;

		$schema = array();

		switch ( $cp_options->listing_schema ) {
			case 'thing':
				$thing = new APP_Schema_Type_Thing_Post( $post );
				$schema = $thing->build();
				break;
			case 'offer':
				$offer = new CP_Schema_Type_Offer_Ad( $post );
				$schema = $offer->build();
				break;
			case 'product':
				$product = new CP_Schema_Type_Product_Ad( $post );
				$schema = $product->build();
				break;
			case 'organization':
				$organization   = new CP_Schema_Type_Organization_Ad( $post );
				$schema = $organization->build();
				break;
			case 'localbusiness':
				$local_business = new CP_Schema_Type_LocalBusiness_Ad( $post );
				$schema = $local_business->build();
				break;

			default:
				break;
		}

		return $schema;
	}

	/**
	 * Add the user contact methods to the output filter.
	 *
	 * @since 4.0.0
	 *
	 * @param array $output List of Person type schema properties.
	 * @return array
	 */
	public function add_author_social_networks( $output ) {

		$user_id = 0;
		$author  = get_user_by( 'id', get_query_var( 'author' ) );

		if ( $author ) {
			$user_id = $author->ID;
		}

		// Add any social networks that have values.
		if ( ! array_filter( $social_networks = cp_get_available_user_networks( true, $user_id ) ) ) {
			return $output;
		}

		foreach ( $social_networks as $key => $value ) {
			$s[] = cp_get_social_account_url( $key, $value );
		}

		// Run esc_url() on all array values.
		$r['sameAs'] = array_map( 'esc_url', $s );

		return array_merge( $output, $r );
	}

}
