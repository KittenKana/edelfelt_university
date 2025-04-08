<?php
/**
 *  Marketplace add-ons request class.
 *
 * @package Components\Add-ons
 */

class APP_Addons_MP_Request {

	const SERVER_URL    = 'https://marketplace.appthemes.com/wp-json';
	const API_NAMESPACE = 'mktplc/v2';

	/**
	 * The request arguments.
	 *
	 * @var array
	 */
	protected $args = array();

	/**
	 * Constructor.
	 *
	 * @param array $args Additional args for the request.
	 */
	public function __construct( $args = array() ) {
		$this->args = $args;
	}

	/**
	 * Fetches the add-ons filters from cache (if not expired) or from the marketplace REST API, directly.
	 *
	 * @return array The list of add-ons filters.
	 */
	public function fetch_mp_filters() {
		$query_hash = substr( md5( self::SERVER_URL . '/' . self::API_NAMESPACE . '/filters' ), 0, 21 );

		if ( ! $filters = get_transient( "_appthemes-addons-mp-filters-$query_hash" ) ) {
			$response = $this->remote_get( 'filters' );
			$filters  = $response['items'];

			if ( is_object( $filters ) && $filters->message ) {
				// TODO: $this->error belongs to APP_Addons_List_Table class !!!
				$this->error = new WP_Error( $filters->code, $filters->message, $filters->data );
				return array();
			}

			// Super elegant recursively cast a PHP object to array.
			$filters = json_decode( json_encode( $filters ), true );
			set_transient( "_appthemes-addons-mp-filters-$query_hash", $filters, DAY_IN_SECONDS );
		}

		return $filters;
	}

	/**
	 * Fetches the add-ons from cache (if not expired) or from the marketplace
	 * REST API, directly.
	 *
	 * @param array $args An array of the query arguments.
	 *
	 * @return array      The list of add-ons.
	 */
	public function fetch_mp_items( $args = array() ) {
		$filters  = $this->get_filter_list();
		$defaults = array_merge( $this->args['defaults'], array(
			'per_page' => $this->args['addons_per_page'],
			'page'     => $this->args['page'],
			'view'     => $this->args['tab'],
		) );

		$args = array_merge( $defaults, $args );

		// Filter out all unknows arguments.
		foreach ( $args as $key => $arg ) {
			if ( empty( $arg ) || ( empty( $filters[ $key ][ $arg ] ) && ! in_array( $key, array( 'per_page', 'page', 'view', 's', 'search' ) ) ) ) {
				unset( $args[ $key ] );
				continue;
			}
		}

		// Look for a keyword search.
		if ( ! empty( $args['s'] ) ) {
			$args['search'] = $args['s'];
		}

		// Avoid conflict settings page slug with the page number.
		$args['page'] = $defaults['page'];

		$query_url  = add_query_arg( $args, self::SERVER_URL . '/' . self::API_NAMESPACE . '/items' );
		$query_hash = substr( md5( $query_url ), 0, 21 );

		if ( ! $response = get_transient( '_appthemes-addons-mp-response-' . $query_hash ) ) {
			$response = $this->remote_get( 'items', $args );
			$items    = $response['items'];

			if ( is_object( $items ) && $items->message ) {
				// TODO: $this->error belongs to APP_Addons_List_Table class !!!
				$this->error = new WP_Error( $items->code, $items->message, $items->data );
				return array(
					'items' => array(),
					'total' => 0,
				);
			}

			$addons     = array();
			$filters    = $this->get_filter_list();
			$authors    = $filters['author'];
			$all_cats   = $filters['cat'];
			$all_prods  = $filters['product'];

			foreach ( $items as $item ) {

				// Get the add-ons meta.
				$addon = new stdClass();

				$addon->id          = $item->id;
				$addon->title       = $item->title->rendered;
				$addon->description = $item->excerpt->rendered;

				$addon->date        = $item->date;
				$addon->human_date  = human_time_diff( strtotime( $item->date ) );

				if ( ! empty( $authors[ $item->author ] ) ) {
					$addon->author      = $authors[ $item->author ];
					$addon->author_link = html( 'a', array( 'href' => esc_url( sprintf( 'https://www.appthemes.com/members/%1$s/seller/', $addon->author ) ), 'target' => 'blank' ), $addon->author );
				} else {
					$addon->author      = '';
					$addon->author_link = '';
				}

				$addon->category_desc = implode( ', ', $item->_mkt_item_category );

				// Requirements.
				$addon->compats = implode( ', ', $item->_mkt_item_compats );

				// Strip all HTML tags from the description.
				$addon->description = wp_trim_words( wp_strip_all_tags( $addon->description ), 50, '...' );

				// Custom RSS tags.
				$link_args = array(
					'utm_source'   => 'addons',
					'utm_medium'   => 'wp-admin',
					'utm_campaign' => 'Add-ons%20Module',
				);

				$addon->link = $item->link;
				$addon->link = add_query_arg( $link_args, $addon->link );

				// Use the custom permalink tag for the item title link.
				$addon->title = html( 'a', array( 'href' => esc_url( $addon->link ), 'target' => 'blank' ), $addon->title );

				// Thumbnail.
				$addon->image = ! empty( $item->_mkt_thumbnail[0] ) ? html( 'img', array( 'src' => $item->_mkt_thumbnail[0] ) ) : '';

				// Custom meta.
				$addon->last_updated_t = $item->_mkt_last_updated ? $item->_mkt_last_updated : strtotime( $item->modified_gmt );
				$addon->last_updated   = date( 'Y-m-d H:i:s', $addon->last_updated_t );
				$addon->last_updated_h = human_time_diff( $addon->last_updated_t );
				$addon->price          = '$' . $item->_mkt_item_price;
				$addon->rating         = $item->_mkt_item_rating;
				$addon->votes          = $item->_mkt_item_votes;

				$addons[] = $addon;
			}

			$response['items'] = $addons;
			set_transient( '_appthemes-addons-mp-response-' . $query_hash, $response, DAY_IN_SECONDS );
		}

		return $response;
	}

	/**
	 * Do a request to REST API server.
	 *
	 * @param string $type The request route.
	 * @param array  $args The request arguments.
	 *
	 * @return array An associative array with a list of retrieved items and their total.
	 */
	public function remote_get( $type, $args = array() ) {

		$server_url = self::SERVER_URL;
		$namespace  = self::API_NAMESPACE;

		$args    = array_merge( array( 'per_page' => 100 ), $args );
		$api_url = add_query_arg( $args, "{$server_url}/{$namespace}/{$type}" );
		$api_url = esc_url_raw( $api_url );

		$raw_response = wp_remote_get( $api_url );

		if ( is_wp_error( $raw_response ) ) {
			return array(
				'items' => $raw_response,
				'total' => 0,
			);
		}

		$response['items'] = json_decode( wp_remote_retrieve_body( $raw_response ) );
		$response['total'] = wp_remote_retrieve_header( $raw_response, 'x-wp-total' );

		return $response;
	}

	/**
	 * Retrieves a list of all the available Add-ons filters.
	 *
	 * @return array An associative array of available filters.
	 */
	public function get_filter_list() {
		$raw_filters  = $this->fetch_mp_filters();
		$filters_list = $this->args['filters'];
		$filters      = array();

		// Add-ons Products Filter.
		foreach ( $raw_filters as $raw_filter ) {
			$name = $raw_filter['name'];
			if ( 'view' === $name || ! isset( $raw_filter['values'] ) ) {
				continue;
			}

			if ( isset( $filters_list[ $name ] ) && ! $filters_list[ $name ] ) {
				continue;
			}

			foreach ( $raw_filter['values'] as &$value ) {
				$value = translate_with_gettext_context( $value, 'MarketPlace Addons page translation', APP_TD );
			}

			if ( ! empty( $filters_list[ $name ] ) ) {
				$all_values = call_user_func_array( 'array_merge', array_values( $raw_filter['values'] ) );
				$raw_filter['values'] = array_intersect_key( $all_values, array_flip( (array) $filters_list[ $name ] ) );
			}

			$filters[ $name ] = $raw_filter['values'];
		}

		return $filters;
	}

	/**
	 * Retrieves the list of new items since the last admin view.
	 *
	 * @return array $items An array of item objects.
	 */
	public function get_new_items() {
		$items    = array();
		$response = $this->fetch_mp_items( array( 'view' => 'new' ) );

		if ( empty( $response['items'] ) ) {
			return $items;
		}

		$last_new = get_option( 'appthemes_addons_mp_newest_published_item' );

		if ( ! $last_new ) {
			$last_new =  $response['items'][0]->id;
			update_option( 'appthemes_addons_mp_newest_published_item', $last_new );
		}

		foreach (  $response['items'] as $item ) {
			if ( $item->id > $last_new ) {
				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * Retrieves the list of new updated items since the last admin view.
	 *
	 * @return array $items An array of item objects.
	 */
	public function get_updated_items() {
		$items    = array();
		$response = $this->fetch_mp_items( array( 'view' => 'updated' ) );

		if ( empty(  $response['items'] ) ) {
			return $items;
		}

		$last_new = get_option( 'appthemes_addons_mp_newest_updated_item' );

		if ( ! $last_new ) {
			$last_new =  $response['items'][0]->last_updated_t;
			update_option( 'appthemes_addons_mp_newest_updated_item', $last_new );
		}

		foreach (  $response['items'] as $item ) {
			if ( $item->last_updated_t > $last_new ) {
				$items[] = $item;
			}
		}

		return $items;
	}

	/**
	 * Updates counters in DB.
	 */
	public function update_counters() {
		$new = wp_list_pluck( $this->get_new_items(), 'id' );
		$upd = wp_list_pluck( $this->get_updated_items(), 'id' );

		update_option( 'appthemes_addons_mp_new_items_counter', $new );
		update_option( 'appthemes_addons_mp_updated_items_counter', $upd );
	}

}
