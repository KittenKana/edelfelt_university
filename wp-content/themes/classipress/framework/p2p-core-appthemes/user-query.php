<?php

class P2P_User_Query extends WP_User_Query {

	public $_p2p_capture;

	/**
	 * Constructor.
	 *
	 * @param null|string|array $query Optional. The query variables.
	 *                                 See WP_User_Query::prepare_query() for information on accepted arguments.
	 */
	public function __construct( $query = null ) {
		add_filter( 'users_pre_query', array( $this, 'users_pre_query' ), 10, 2 );

		parent::__construct( $query );
	}

	/**
	 * Retrieve the list of users with p2p data.
	 *
	 * @note Behavior of fetching users data has been changed in WordPress 6.1.
	 * @see https://github.com/WordPress/WordPress/commit/6e619966a6c2e2a12f3a9e0e2ff6962d1d82c643
	 *
	 * @param array|null    $results Return an array of user data to short-circuit WP's user query
	 *                               or null to allow WP to run its normal queries.
	 * @param WP_User_Query $query   The WP_User_Query instance (passed by reference).
	 *
	 * @return array|null
	 */
	public function users_pre_query( $results, $query ) {
		global $wpdb;

		if ( empty( $query->query_vars['connected_type'] ) ) {
			return $results;
		}

		if ( ! is_array( $query->query_vars['fields'] ) ) {
			return $results;
		}

		$request =
			"SELECT {$query->query_fields}
			{$query->query_from}
			{$query->query_where}
			{$query->query_orderby}
			{$query->query_limit}";

		$results_raw = $wpdb->get_results( $request );

		if ( isset( $query->query_vars['count_total'] ) && $query->query_vars['count_total'] ) {
			$found_users_query = apply_filters( 'found_users_query', 'SELECT FOUND_ROWS()', $query );
			$query->total_users = (int) $wpdb->get_var( $found_users_query );
		}

		$results = array();
		foreach ( $results_raw as $key => $user ) {
			$results[ $key ] = new WP_User( $user, '', $query->query_vars['blog_id'] );
		}

		// Reset fields to avoid further processing results
		$query->query_vars['fields'] = '';

		return $results;
	}

}
