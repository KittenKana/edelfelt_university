<?php
/**
 * Schema.org structured data Product type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the Product type schema json-ld.
 *
 * @link  https://schema.org/Product
 * @link  https://developers.google.com/schemas/reference/types/Product
 *
 * @since 4.0.0
 */
class CP_Schema_Type_Product_Ad extends APP_Schema_Type_Product_Post {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		if ( ! $post ) {
			return;
		}

		parent::__construct( $post );

		$category   = '';
		$categories = get_the_terms( $post->ID, APP_TAX_CAT );

		if ( ! empty( $categories ) ) {
			$categories = array_shift( $categories );
			$category   = $categories->name;
		}

		if ( $category && function_exists( 'get_term_parents_list' ) ) {
			$category = get_term_parents_list( $categories->term_id, APP_TAX_CAT, array(
				'link' => false,
			) );
		}

		$this
			->set( 'sku', $post->ID )
			->set( 'mpn', $post->ID )
			->set( 'category', $category )
			->set( 'offers', new CP_Schema_Type_Offer_Product( $post ) );
	}

}
