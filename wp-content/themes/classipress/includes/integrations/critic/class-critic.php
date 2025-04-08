<?php
/**
 * WordPress Critic Plugin integration.
 *
 * @url https://marketplace.appthemes.com/plugins/critic/
 *
 * @package ClassiPress\Integrations
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Custom actions and filters to use with plugin.
 */
class CP_Critic {

	/**
	 * Constructor
	 */
	public function __construct() {

		if ( ! current_theme_supports( 'critic-plugin' ) ) {
			return;
		}



		add_action( 'wp_head', array( $this, 'init' ) );
		add_filter( 'cp_orderby_values_dropdown', array( $this, 'orderby_values_dropdown' ) );

		// Displaying stars on the loop requires additional design works.
		add_action( 'cp_listing_item_head',  array( $this, 'star_ratings_review_count' ), 99 );

		new CP_Critic_View();
	}

	public function init() {

		if ( ! get_theme_mod( 'show_listing_banner_image', 1 ) && is_singular( APP_POST_TYPE ) ) {
			add_action( 'appthemes_before_ad_listing_title', array( $this, 'star_ratings_review_count' ) );
		} else {
			add_action( 'cp_listing_single_bar_actions',  array( $this, 'star_ratings_review_count' ) );
		}
		add_action( 'cp_listing_single_bar_actions', array( $this, 'submit_review_link' ) );
	}

	/**
	 * Display the listing submit review link.
	 *
	 * @since 4.0.0
	 */
	public function submit_review_link() {
		global $post;

		// Exit if the post closed the reviews.
		if ( get_post_meta( $post->ID, CRITIC_PSTATUS_KEY, true ) ) {
			return;
		}

		$review_link =  '<a href="#respond" title="' . esc_attr__( 'Write a Review', APP_TD ) . '" class="submit-review-link listing-icon"><i class="fa fa-star" aria-hidden="true"></i><span class="screen-reader-text">' . __( 'Write a Review', APP_TD ) . '</span></a>';
		/**
		 * Filter the submit review link.
		 *
		 * @since 4.0.0
		 *
		 * @param string  $review_link The html review a href link.
		 * @param WP_Post $post Post object.
		 */
		echo apply_filters( 'cp_submit_review_link', $review_link, $post );
	}

	/**
	 * Displays the star ratings and total review count.
	 *
	 * @since 4.0.0
	 */
	function star_ratings_review_count() {
		global $post;

		$rating = get_post_meta( $post->ID, CRITIC_PAVG_KEY, true );
		$total  = get_post_meta( $post->ID, CRITIC_PVOTE_KEY, true );

		$results = '<div class="critic-rating-result-wrap"><span class="critic-rating-result" data-rating="' . esc_attr( (float) $rating ) . '"></span> <span class="critic-rating-total">(' . number_format( (int) $total ) . ')</span></div>';

		/**
		 * Filter the total rating stars and results html.
		 *
		 * @since 4.0.0
		 *
		 * @param string  $results The html review star rating and total count.
		 * @param int     $rating  The avg star rating number.
		 * @param int     $total   The total number of reviews.
		 * @param WP_Post $post    Post object.
		 */
	 	echo apply_filters( 'cp_star_ratings_review_count', $results, $rating, $total, $post );
	}

	/**
	 * Filters the listing order by values dropdown menu.
	 *
	 * @since 4.2.6
	 *
	 * @param array $orderby_values An array of order by values.
	 */
	public function orderby_values_dropdown( $orderby_values ) {
		$orderby_values['rating'] = __( 'Rating', APP_TD );

		return $orderby_values;
	}
}

class CP_Critic_View extends APP_View {

	/**
	 * Determine what page(s) this class should load on.
	 *
	 * @since  1.0.0
	 *
	 * @return bool true if successful, false otherwise
	 */
	function condition() {
		return isset( $_GET['sort'] ) && 'rating' === $_GET['sort'];
	}

	function pre_get_posts( $wp_query ) {

		$meta_query = (array) $wp_query->get( 'meta_value', 1 );
		$meta_query = array_filter( $meta_query );
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key'     => CRITIC_PAVG_KEY,
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => CRITIC_PAVG_KEY,
				'compare' => 'EXISTS',
			),
		);

		$wp_query->set( 'meta_query', $meta_query );
		$wp_query->set( 'orderby', array( 'meta_value' => 'DESC', 'date' => 'DESC' ) );
	}

}
