<?php
/**
 * Listings search results page classes.
 *
 * @package ClassiPress\Views
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Logic for the listings search results page.
 *
 * @since 4.0.0
 */
class CP_Ads_Search extends APP_View {

	/**
	 * Init and whitelist search parameters.
	 *
	 * @since  4.0.0
	 *
	 * @return none
	 */
 	function init() {
 		global $wp;

		// Use a different keyword search param since 's' is reserved for the blog.
 		//$wp->add_query_var( 'ls' );

		// Declare a 'search type' param (defaults to listing) so things like events can be added later.
		$wp->add_query_var( 'st' );

		$wp->add_query_var( 'scat' );
		$wp->add_query_var( 'subcat' );
		$wp->add_query_var( 'sort' );
		$wp->add_query_var( 'refine_search' );
 	}

	/**
	 * Check if this class should handle the current view.
	 *
	 * @return bool
	 */
	public function condition() {
		return ! is_admin() && is_search() && isset( $_GET['st'] ) && $_GET['st'] === APP_POST_TYPE;
	}

	/**
	 * Called after the query variable object is created, but before the actual query is run.
	 *
	 * @since 1.0.0
	 *
	 * @uses APP_Google_Maps
	 *
	 * @param WP_Query $wp_query The WP_Query instance (passed by reference).
	 */
	function parse_query( $wp_query ) {
		global $wpdb;

		$wp_query->set( 'post_status', 'publish' );

		// setup the array for post types
		$post_type_array = array( APP_POST_TYPE );

		$wp_query->set( 'post_type', $post_type_array );

		// Orderby section.
		$sort = $wp_query->get( 'sort' );
		if ( 'random' === $sort ) {
			$wp_query->set( 'orderby', 'rand' );
		} elseif ( 'date' === $sort ) {
			$wp_query->set( 'orderby', 'date' );
		} elseif ( 'popular' === $sort && current_theme_supports( 'app-stats' ) ) {
			$wp_query->set( '_popular_posts_total', true );
		} elseif ( 'title' === $sort || 'distance' === $sort ) {
			$wp_query->set( 'orderby', $sort );
			$wp_query->set( 'order', 'ASC' );
		} elseif ( 'featured' === $sort ) {
			$wp_query->set( 'post__in', get_option( 'sticky_posts' ) );
		}

		if ( $wp_query->get( 'refine_search' ) ) {
			$meta_query = array();
			$price_set = false;


			foreach ( $_GET as $key => $value ) {
				if ( empty( $value ) ) {
					continue;
				}
				switch ( $key ) {
					case 'sort' :
						if ( in_array( $sort, array( 'low_price', 'high_price' ), true ) ) {
							$meta_query[] = array(
								'relation' => 'OR',
								array(
									'key'     => 'cp_price',
									'compare' => 'EXISTS',
								),
								array(
									'key'     => 'cp_price',
									'compare' => 'NOT EXISTS',
								),
							);

							if ( empty( $_GET['price_min'] ) && empty( $_GET['price_max'] ) ) {
								$wp_query->set( 'orderby', 'meta_value_num' );
							} else {
								$wp_query->set( 'orderby', 'cp_price' );
							}

							if ( 'low_price' === $sort ) {
								$wp_query->set( 'order', 'ASC' );
							}
						}
						break;

					case 'price_min' :
					case 'price_max' :
						if ( $price_set ) {
							break;
						}
						$price_set = true;
						$price_min = empty( $_GET['price_min'] ) ? 0 : (int) $_GET['price_min'];
						$price_max = empty( $_GET['price_max'] ) ? 9999999999 : (int) $_GET['price_max'];
						$value     = array( $price_min, $price_max );

						$meta_query[] = array(
							'key'     => 'cp_price',
							'value'   => $value,
							'compare' => 'BETWEEN',
							'type'    => 'numeric',
						);
						break;

					default :
						if ( 'cp_' == substr( $key, 0, 3 ) ) {
							$value = wp_kses_post_deep( $value );
							$field = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->cp_ad_fields WHERE field_name = %s", $key ) );
							if ( $field === null ) {
								break;
							}
							$compare = ( in_array( $field->field_type, array( 'radio', 'checkbox', 'drop-down' ) ) ) ? 'IN' : 'LIKE';
							$meta_query[] = array(
								'key'     => $key,
								'value'   => $value,
								'compare' => $compare,
							);
						}
						break;
				}
			}
			$wp_query->set( 'meta_query', $meta_query );
		}

		// Override default parameters.
//		$wp_query->set( 'ls', trim( get_query_var( 'ls' ) ) );
//		$wp_query->set( 'posts_per_page', $va_options->listings_per_page );
//
//		// Filter by listing cat if needed.
//		if ( isset( $_GET['listing_cat'] ) ) {
//			$tax_query[] = array(
//				'taxonomy' => VA_LISTING_CATEGORY,
//				'terms'    => $_GET['listing_cat'],
//			);
//			$wp_query->set( 'tax_query', $tax_query );
//		}
//
//

	}

	/**
	 * Search clauses manipulation.
	 *
	 * @since 4.1.4
	 *
	 * @global wpdb $wpdb
	 *
	 * @param string $search Search request clauses.
	 */
	function posts_search( $search ) {
		global $wpdb;

		if ( ! cp_search_index_enabled() ) {
			// get the custom fields to add to search
			$customs = cp_custom_search_fields();
			// add some internal custom fields to search
			$customs = array_merge( $customs, array( 'cp_sys_ad_conf_id' ) );
		}

		$query = '';

		$var_q = stripslashes( cp_get_search_term() );
		//empty the s parameter if set to default search text
		if ( __( 'What are you looking for?', APP_TD ) == $var_q ) {
			$var_q = '';
		}

		if ( isset( $_GET['sentence'] ) || $var_q == '' ) {
			$search_terms = array($var_q);
		} else {
			preg_match_all( '/".*?("|$)|((?<=[\\s",+])|^)[^\\s",+]+/', $var_q, $matches );
			$search_terms = array_map( array( $this, 'trim' ), $matches[0] );
		}

		if ( ! isset( $_GET['exact'] ) ) {
			$_GET['exact'] = '';
		}

		$n = ( $_GET['exact'] ) ? '' : '%';

		$searchand = '';
		$likes = array();

		foreach ( (array) $search_terms as $term ) {
			$term  = $wpdb->esc_like( $term );
			$like  = $n . $term . $n;

			$query .= "{$searchand}(";

			if ( ! cp_search_index_enabled() ) {
				$query .= "($wpdb->posts.post_title LIKE %s)";
				$query .= " OR ($wpdb->posts.post_content LIKE %s)";
				$query .= " OR ((t.name LIKE %s)) OR ((t.slug LIKE %s))";
				$likes = array_merge( $likes, array( $like, $like, $like, $like ) );
				foreach ( $customs as $custom ) {
					$query .= " OR (";
					$query .= "(m.meta_key = %s)";
					$query .= " AND (m.meta_value LIKE %s)";
					$query .= ")";
					$likes[] = $custom;
					$likes[] = $like;
				}
			} else {
				$query .= "($wpdb->posts.post_content_filtered LIKE %s)";
				$likes[] = $like;
			}

			$query .= ")";
			$searchand = ' AND ';
		}

		$term  = $wpdb->esc_like( $var_q );
		$like  = $n . $term . $n;

		if ( ! isset( $_GET['sentence'] ) && count( $search_terms ) > 1 && $search_terms[0] != $var_q ) {
			if ( ! cp_search_index_enabled() ) {
				$query .= " OR ($wpdb->posts.post_title LIKE %s)";
				$query .= " OR ($wpdb->posts.post_content LIKE %s)";
				$likes[] = $like;
				$likes[] = $like;
			} else {
				$query .= " OR ($wpdb->posts.post_content_filtered LIKE %s)";
				$likes[] = $like;
			}
		}

		$query = $wpdb->prepare( $query, $likes );

		if ( ! empty( $query ) ) {

			$search = " AND ({$query}) ";

		}

		return $search;
	}

	/**
	 * Filters all query clauses at once, for convenience.
	 *
	 * Covers the WHERE, GROUP BY, JOIN, ORDER BY, DISTINCT,
	 * fields (SELECT), and LIMITS clauses.
	 *
	 * @since 4.0.0
	 *
	 * @param array    $clauses  The list of clauses for the query.
	 * @param WP_Query $wp_query The WP_Query instance.
	 */
	function posts_clauses( $clauses, $wp_query ) {
		global $wpdb;

		$where = $clauses['where'];
		$join  = $clauses['join'];

		// JOIN.

		if ( ! cp_search_index_enabled() ) {
			$join  .= " LEFT JOIN $wpdb->term_relationships AS r ON ($wpdb->posts.ID = r.object_id) ";
			$join .= " LEFT JOIN $wpdb->term_taxonomy AS x ON (r.term_taxonomy_id = x.term_taxonomy_id) ";
			$join .= " AND (x.taxonomy = '".APP_TAX_TAG."' OR x.taxonomy = '".APP_TAX_CAT."' OR 1=1) ";
		}

		// if an ad category is selected, limit results to that cat only
		$catid = get_query_var( 'scat' );

		if ( ! empty( $catid ) ) {

			// put the catid into an array
			(array) $include_cats[] = $catid;

			if ( ! empty( get_query_var( 'subcat' ) ) ) {
				// get only selected sub cats of catid and put them into the array.
				$descendants = get_query_var( 'subcat' );
			} else {
				// get all sub cats of catid and put them into the array.
				$descendants = get_term_children( (int) $catid, APP_TAX_CAT );
			}

			foreach ( $descendants as $key => $value ) {
				$include_cats[] = $value;
			}

			// take catids out of the array and separate with commas
			$include_cats = "'" . implode( "', '", $include_cats ) . "'";

			// add the category filter to show anything within this cat or it's children
			$join .= " INNER JOIN $wpdb->term_relationships AS tr2 ON ($wpdb->posts.ID = tr2.object_id) ";
			$join .= " INNER JOIN $wpdb->term_taxonomy AS tt2 ON (tr2.term_taxonomy_id = tt2.term_taxonomy_id) ";
			$join .= " AND tt2.term_id IN ($include_cats) ";

		}

		if ( ! cp_search_index_enabled() ) {
			$join .= " INNER JOIN $wpdb->postmeta AS m ON ($wpdb->posts.ID = m.post_id) ";
			$join .= " LEFT JOIN $wpdb->terms AS t ON x.term_id = t.term_id ";
		}

		if ( $wp_query->get( '_popular_posts_total' ) && current_theme_supports( 'app-stats' ) ) {
			$join  .= " INNER JOIN $wpdb->cp_ad_pop_total ON ($wpdb->posts.ID = $wpdb->cp_ad_pop_total.postnum) ";
			$where .= " AND $wpdb->cp_ad_pop_total.postcount > 0 ";
			$clauses['orderby'] = "$wpdb->cp_ad_pop_total.postcount DESC";
		}

		//$clauses['distinct'] = 'DISTINCT';
		$clauses['groupby'] = "$wpdb->posts.ID";
		$clauses['where'] = $where;
		$clauses['join'] = $join;

		return $clauses;
	}

	/**
	 * Fallback for location search when there is no any geocoder available.
	 *
	 * @param WP_Query $wp_query Current query object.
	 */
	public function pre_get_posts( $wp_query ) {
		$location   = trim( $wp_query->get( 'location' ) );
		$meta_query = $wp_query->get( 'meta_query' );

		if ( ! $location || empty( $meta_query ) ) {
			return $wp_query;
		}

		foreach ( $meta_query as $index => $args ) {
			if ( isset( $args['key'] ) && 'address' === $args['key'] && $location === $args['value'] ) {
				unset( $meta_query[ $index ] );
				$meta_query[] = array(
					'relation' => 'OR',
					array(
						'key' => 'cp_country',
						'value' => $location,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'cp_state',
						'value' => $location,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'cp_city',
						'value' => $location,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'cp_zipcode',
						'value' => $location,
						'compare' => 'LIKE',
					),
					array(
						'key' => 'cp_street',
						'value' => $location,
						'compare' => 'LIKE',
					),
				);
			}

		}

		$wp_query->set( 'meta_query', $meta_query );
	}

	public function trim( $string ) {
		return trim( $string, '\\"\'\\n\\r ');
	}

}
