<?php
/**
 * Schema.org structured data Offer type classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Generates the Offer type schema json-ld.
 *
 * @link  https://schema.org/Offer
 * @link  https://developers.google.com/schemas/reference/types/Offer
 *
 * @since 4.0.0
 */
class CP_Schema_Type_Offer_Product extends APP_Schema_Type_Offer_Post {

	/**
	 * Constructor.
	 *
	 * @param WP_Post $post Post object to be used for building schema type.
	 */
	public function __construct( WP_Post $post = null ) {
		global $cp_options;

		if ( ! $post ) {
			return;
		}

		$author = get_user_by( 'id', $post->post_author );

		if ( $author ) {
			$this->set( 'offeredBy', new APP_Schema_Type_Person_User( $author ) );
		}

		$format_args = appthemes_price_format_get_args();
		$decimals    = ( $format_args['hide_decimals'] ) ? 0 : 2;
		$base_price  = number_format( (float) $post->cp_price, $decimals, $format_args['decimal_separator'], $format_args['thousands_separator'] );

		$this
			->set( 'availability', 'http://schema.org/InStock' )
			->set( 'priceValidUntil', mysql2date( DATE_ISO8601, '+1 year' ) )
			->set( 'url', get_permalink( $post->ID ) )
			->set( 'price', $base_price )
			->set( 'priceCurrency', $post->cp_currency ? $post->cp_currency : ( $cp_options->curr_code ? $cp_options->curr_code : $cp_options->curr_symbol ) );
	}

}
