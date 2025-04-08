<?php
/**
 * ClassiPress core theme functions.
 *
 * @package ClassiPress\Functions
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */


/**
 * Displays the login message in the header.
 *
 * @todo Deprecate since 4.0.0
 *
 * @return void
 */
if ( ! function_exists( 'cp_login_head' ) ) {
	function cp_login_head() {

		if ( is_user_logged_in() ) :
			$current_user = wp_get_current_user();
			$logout_url = cp_logout_url();
			?>
			<?php _e( 'Welcome,', APP_TD ); ?> <strong><?php echo $current_user->display_name; ?></strong> [ <a href="<?php echo esc_url( CP_DASHBOARD_URL ); ?>"><?php _e( 'My Dashboard', APP_TD ); ?></a> | <a href="<?php echo esc_url( $logout_url ); ?>"><?php _e( 'Log out', APP_TD ); ?></a> ]&nbsp;
		<?php else : ?>

			<?php _e( 'Welcome,', APP_TD ); ?> <strong><?php _e( 'visitor!', APP_TD ); ?></strong> [

			<?php if ( get_option('users_can_register') ): ?>
				<a href="<?php echo esc_url( appthemes_get_registration_url() ); ?>"><?php _e( 'Register', APP_TD ); ?></a> |
			<?php endif; ?>

			<a href="<?php echo wp_login_url(); ?>"><?php _e( 'Login', APP_TD ); ?></a> ]&nbsp;

		<?php endif;

	}
}


/**
 * Returns user name depend of account type.
 *
 * @param object $user (optional)
 *
 * @return string
 */
function cp_get_user_name( $user = false ) {

	if ( ! $user && is_user_logged_in() ) {
		$user = wp_get_current_user();
	} else if ( is_numeric( $user ) ) {
		$user = get_userdata( $user );
	}

	if ( is_object( $user ) ) {
		return $user->display_name;
	} else {
		return false;
	}
}


/**
 * Returns logout url depend of login type.
 *
 * @patam string $url (optional)
 *
 * @return string
 */
function cp_logout_url( $url = '' ) {

	if ( ! $url ) {
		$url = home_url();
	}

	if ( is_user_logged_in() ) {
		return wp_logout_url( $url );
	} else {
		return false;
	}
}


/**
 * Corrects logout url in admin bar.
 *
 * @return void
 */
function cp_admin_bar_render() {
	global $wp_admin_bar;

	if ( is_user_logged_in() ) {
		$wp_admin_bar->remove_menu( 'logout' );
		$wp_admin_bar->add_menu( array(
			'parent' => 'user-actions',
			'id'     => 'logout',
			'title'  => __( 'Log out', APP_TD ),
			'href'   => cp_logout_url(),
		) );
	}

}
add_action( 'wp_before_admin_bar_render', 'cp_admin_bar_render' );


/**
 * Returns formatted price based on user settings, used for display prices.
 *
 * @param float $price
 *
 * @return string
 */
function cp_price_format( $price ) {
	global $cp_options;

	if ( is_numeric( $price ) ) {
		$decimals = ( $cp_options->hide_decimals || $price == 0 ) ? 0 : 2;
		$decimal_separator = $cp_options->decimal_separator;
		$thousands_separator = $cp_options->thousands_separator;

		$price = number_format( $price, $decimals, $decimal_separator, $thousands_separator );
	}

	return $price;
}


/**
 * Displays passed price based on user defined format and currency symbol.
 *
 * @param float $price
 * @param string $price_type (optional)
 * @param bool $echo (optional)
 *
 * @return string
 */
function cp_display_price( $price, $price_type = '', $echo = true ) {

	$price = cp_price_format( $price );
	$price = cp_pos_currency( $price, $price_type );

	if ( $echo ) {
		echo $price;
	} else {
		return $price;
	}
}


/**
 * Displays ad price and position the currency symbol.
 *
 * @param int $post_id
 * @param string $meta_field
 *
 * @return string
 */
if ( ! function_exists( 'cp_get_price' ) ) {
	function cp_get_price( $post_id, $meta_field, $echo = true ) {
		global $cp_options;

		if ( get_post_meta( $post_id, $meta_field, true ) ) {

			$price_out = get_post_meta( $post_id, $meta_field, true );
			$price_out = cp_price_format( $price_out );
			$price_out = cp_pos_currency( $price_out, 'ad' );

		} else {
			if ( $cp_options->force_zeroprice ) {
				$price_out = cp_pos_currency( 0, 'ad' );
			} else {
				$price_out = '&nbsp;';
			}
		}

		if ( $echo ) {
			echo $price_out;
		} else {
			return $price_out;
		}
	}
}


/**
 * Position the currency symbol and return it with the price.
 *
 * @param float $price_out
 * @param string $price_type (optional)
 *
 * @return string
 */
function cp_pos_currency( $price_out, $price_type = '' ) {
	global $post, $cp_options;

	$price = $price_out;

	if ( $price_type == 'ad' ) {
		$curr_symbol = $cp_options->curr_symbol;
	} else {
		$curr_symbol = $price_type;
	}

	// if ad have custom currency, display it instead of default one
	if ( $price_type == 'ad' && isset( $post ) && is_object( $post ) ) {
		$custom_curr = get_post_meta( $post->ID, 'cp_currency', true );
		if ( ! empty( $custom_curr ) ) {
			$curr_symbol = $custom_curr;
		}
	}

	// possition the currency symbol
	if ( current_theme_supports( 'app-price-format' ) ) {
		$price_out = _appthemes_format_display_price( $price_out, $curr_symbol, $cp_options->currency_position );
	} else {
		$price_out = $price_out . '&nbsp;' . $curr_symbol;
	}

	return apply_filters( 'cp_currency_position', $price_out, $price, $curr_symbol, $price_type );
}

/**
 * Returns the taxonomy terms list.
 *
 * @param int $id (optional)
 * @param string $taxonomy
 * @param string $before (optional)
 * @param string $sep (optional)
 * @param string $after (optional)
 *
 * @return string
 */
function cp_get_the_term_list( $id = 0, $taxonomy = null, $before = '', $sep = '', $after = '' ) {

	if ( ! $taxonomy ) {
		return false;
	}

	$terms = get_the_terms( $id, $taxonomy );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return false;
	}

	foreach ( $terms as $term ) {
		$link = get_term_link( $term, $taxonomy );
		if ( is_wp_error( $link ) ) {
			return $link;
		}
		$term_links[] = $term->name . ', ';
	}

	$term_links = apply_filters( "term_links-$taxonomy", $term_links );

	return $before . join( $sep, $term_links ) . $after;
}


/**
 * Changes ad if it's expired.
 *
 * @param int $post_id
 *
 * @return bool
 */
function cp_has_ad_expired( $post_id ) {
	$expire_time = strtotime( get_post_meta( $post_id, 'cp_sys_expire_date', true ) );

	// if current date is past the expires date, change post status to expired.
	if ( current_time( 'timestamp' ) > $expire_time ) {
		$my_post                = array();
		$my_post['ID']          = $post_id;
		$my_post['post_status'] = CP_POST_STATUS_EXPIRED;
		wp_update_post( $my_post );

		return true;
	}

	return false;
}


/**
 * Checks if ad listing is expired.
 *
 * @param int $listing_id (optional)
 *
 * @return bool
 */
function cp_is_listing_expired( $listing_id = 0 ) {
	$listing_id = $listing_id ? $listing_id : get_the_ID();
	$listing    = get_post( $listing_id );

	return CP_POST_STATUS_EXPIRED === $listing->post_status;
}


/**
 * Determines what the ad listing post status should be.
 *
 * @param array $advals
 *
 * @return string
 */
if ( ! function_exists( 'cp_set_post_status' ) ) {
	function cp_set_post_status( $advals ) {
		global $cp_options;

		if ( $cp_options->moderate_ads ) {
			return 'pending';
		}

		if ( cp_payments_is_enabled() ) {
			return 'pending';
		}

		return 'publish';
	}
}


/**
 * Prints an redirect button and javascript.
 *
 * @param string $url
 * @param string $message (optional)
 *
 * @return void
 */
function cp_js_redirect( $url, $message = '' ) {
	if ( empty( $message ) ) {
		$message = __( 'Continue', APP_TD );
	}

	echo html( 'a', array( 'href' => $url ), $message );
	echo html( 'script', 'location.href="' . $url . '"' );
}


/**
 * Shows how much time is left before the ad expires.
 *
 * @param string $date_time
 *
 * @return string
 */
function cp_timeleft( $date_time ) {
	if ( is_string( $date_time ) ) {
		$date_time = strtotime( $date_time );
	}

	$timeLeft = $date_time - current_time( 'timestamp' );

	$expired_label = __( 'This ad has expired', APP_TD );

	if ( $timeLeft > 0 ) {
		$days  = floor( $timeLeft/60/60/24 );
		$hours = floor( $timeLeft/60/60 )%24;
		$mins  = floor( $timeLeft/60 )%60;
		$secs  = $timeLeft%60;

		$d_label = _n( 'day', 'days', $days, APP_TD );
		$h_label = _n( 'hour', 'hours', $hours, APP_TD );
		$m_label = _n( 'min', 'mins', $mins, APP_TD );
		$s_label = _n( 'sec', 'secs', $secs, APP_TD );

		if ( $days ) {
			$theText = $days . " " . $d_label;
			if ( $hours ) { $theText .= ", " .$hours . " " . $h_label; }
		} elseif ( $hours ) {
			$theText = $hours . " " . $h_label;
			if ( $mins ) { $theText .= ", " .$mins . " " . $m_label; }
		} elseif ( $mins ) {
			$theText = $mins . " " . $m_label;
			if ( $secs ) { $theText .= ", " .$secs . " " . $s_label; }
		} elseif ( $secs ) {
			$theText = $secs . " " . $s_label;
		}
	} else {
		$theText = $expired_label;
	}

	return $theText;
}


/**
 * Displays breadcrumb on the top of pages.
 *
 * @return void
 */
function cp_breadcrumb() {

	breadcrumb_trail( array(
		'container'   => 'nav',
		'list_tag'    => 'ul',
		'item_tag'    => 'li',
		'show_browse' => false,
		'labels' => array(
			//aria-hidden="true"
			'home' => '<span aria-hidden="true" style="display: none;">' . __( 'Home', APP_TD ) . '</span><i class="fa fa-home"></i>',
		),
	) );

}

/**
 * Returns user ads for his dashboard.
 *
 * @param array $args (optional)
 *
 * @return object|bool Boolean false on failure
 */
function cp_get_user_dashboard_listings( $args = array() ) {
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$status   = ! empty( $_GET['status'] ) ? stripslashes( $_GET['status'] ) : '';
	$dcat     = ! empty( $_GET['dcat'] ) ? absint( $_GET['dcat'] ) : '';
	$keywords = ! empty( $_GET['ds'] ) ? stripslashes( $_GET['ds'] ) : '';

	if ( $keywords ) {
		$args['s'] = $keywords;
	}

	if ( $dcat ) {
		$args['tax_query'][] = array(
			'taxonomy' => APP_TAX_CAT,
			'field'    => 'id',
			'terms'    => $dcat,
		);
	}

	if ( $status ) {
		switch ( $status ) {
			case 'live':
				$args['post_status'] = 'publish';
				break;
			case 'ended':
				$args['post_status'] = CP_POST_STATUS_EXPIRED;
				break;
			case 'offline':
				$args['post_status']  = 'draft';
				$args['meta_query'][] = array(
					'key'     => 'cp_sys_expire_date',
					'value'   => current_time( 'mysql' ),
					'compare' => '>',
					'type'    => 'DATETIME',
				);
				break;
			case 'pending':
				$args['post_status'] = 'pending';
				break;
		}
	}

	if ( get_query_var( 'orderby' ) && get_query_var( 'order' ) ) {
		$args['order'] = get_query_var( 'order' );
	}

	if ( 'listing_title' === get_query_var( 'orderby' ) ) {
		$args['orderby'] = 'title';
	}

	if ( 'listed' === get_query_var( 'orderby' ) ) {
		$args['orderby'] = 'date';
	}

	$defaults = array(
		'post_type'        => APP_POST_TYPE,
		'post_status'      => array( 'publish', 'pending', 'draft', CP_POST_STATUS_EXPIRED ),
		'author'           => get_current_user_id(),
		'paged'            => $paged,
		'suppress_filters' => false,
		'posts_per_page'   => 30,
	);
	$args = wp_parse_args( $args, $defaults );

	$args = apply_filters( 'cp_user_dashboard_listings_args', $args );

	$listings = new WP_Query( $args );

	if ( ! $listings->have_posts() ) {
		return false;
	}

	return apply_filters( 'cp_user_dashboard_listings', $listings );
}


/**
 * Returns user orders for his dashboard.
 *
 * @since 3.5
 *
 * @param array $args (optional)
 *
 * @return object|bool Boolean false on failure
 */
function cp_get_user_dashboard_orders( $args = array() ) {
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

	$defaults = array(
		'post_type'        => APPTHEMES_ORDER_PTYPE,
		'post_status'      => 'any',
		'author'           => get_current_user_id(),
		'paged'            => $paged,
		'suppress_filters' => false,
	);
	$args = wp_parse_args( $args, $defaults );

	if ( get_query_var('order_status') ) {
		$args['post_status'] = get_query_var('order_status');
	}

	$args = apply_filters( 'cp_user_dashboard_orders_args', $args );

	$orders = new WP_Query( $args );

	if ( ! $orders->have_posts() ) {
		return false;
	}

	return apply_filters( 'cp_user_dashboard_orders', $orders );
}


/**
 * Deletes ad listing together with associated attachments.
 *
 * @param int $post_id
 *
 * @return bool
 */
function cp_delete_ad_listing( $post_id ) {

	// delete post and it's revisions, comments, meta
	if ( wp_delete_post( $post_id, true ) ) {
		return true;
	} else {
		return false;
	}

}


/**
 * Checks if a membership is required to post into given category ID.
 *
 * @param int $cat_id
 *
 * @return mixed
 */
function get_membership_requirement( $cat_id ) {
	global $cp_options;

	if ( $cp_options->required_membership_type == 'all' ) {
		// if all posts require "required" memberships
		return 'all';
	} else if ( $cp_options->required_membership_type == 'category' ) {
		// if post requirements are based on category specific requirements
		$required_categories = $cp_options->required_categories;
		if ( ! empty( $required_categories[ $cat_id ] ) ) {
			return $cat_id;
		}
	}

	// no requirements active
	return false;
}


/**
 * Checks and redirects to membership purchase page if not meet membership requirement.
 *
 * @return void
 */
function cp_redirect_membership() {
	global $current_user, $cp_options;

	$current_requirement = false;

	if ( ! $cp_options->enable_membership_packs ) {
		return;
	}

	if ( isset( $_POST['cat'] ) ) {
		$current_requirement = get_membership_requirement( $_POST['cat'] );
	}

	if ( $cp_options->required_membership_type == 'all' ) {
		$current_requirement = 'all';
	}

	if ( ! $current_requirement ) {
		return;
	}

	$current_membership = cp_get_user_membership_package( $current_user->ID );

	if ( ! $current_membership || ! $current_membership->pack_satisfies_required ) {
		$redirect_url = add_query_arg( array( 'membership' => 'required', 'cat' => $current_requirement ), CP_MEMBERSHIP_PURCHASE_URL );
		wp_redirect( $redirect_url );
		exit;
	}

}


/**
 * Updates geo location in db.
 *
 * @param int $post_id
 * @param string $cat
 * @param float $lat
 * @param float $lng
 *
 * @return bool
 */
function cp_update_geocode( $post_id, $cat, $lat, $lng ) {

	if ( ! empty( $cat ) ) {
		_deprecated_argument( __FUNCTION__, '3.3.2' );
	}

	if ( ! $lat || ! $lng || ! $post_id ) {
		return false;
	}

	$post_id = absint( $post_id );
	$lat     = floatval( $lat );
	$lng     = floatval( $lng );

	return appthemes_set_coordinates( $post_id, $lat, $lng );
}


/**
 * Returns geo location from db.
 *
 * @param int $post_id
 * @param string $cat (optional)
 *
 * @return array|bool Boolean false on failure
 */
function cp_get_geocode( $post_id, $cat = '' ) {

	if ( ! empty( $cat ) ) {
		_deprecated_argument( __FUNCTION__, '3.3.2' );
	}

	$row = appthemes_get_coordinates( $post_id, false );

	if ( is_object( $row ) ) {
		return array( 'lat' => $row->lat, 'lng' => $row->lng );
	} else {
		return false;
	}
}

function cp_maybe_geocode_address( $post_id, array $address_comps ) {

	$listing = get_post( $post_id );

	if ( ! $listing || empty( $address_comps ) ) {
		return;
	}

	$needs_update = count( array_diff_assoc( $address_comps, array(
		'cp_street'  => $listing->cp_street,
		'cp_city'    => $listing->cp_city,
		'cp_state'   => $listing->cp_state,
		'cp_zipcode' => $listing->cp_zipcode,
		'cp_country' => $listing->cp_country,
	) ) );

	/**
	 * Whether to geocode address.
	 *
	 * @since 4.2.5
	 *
	 * @param bool  $needs_update  True if address is changed False if not.
	 * @param int   $post_id       The listing ID to geocode address for.
	 * @param array $address_comps The address components.
	 */
	if ( apply_filters( 'cp_maybe_geocode_address', ! ! $needs_update, $post_id, $address_comps ) ) {
		cp_geocode_address( $post_id, $address_comps );
	}
}

function cp_geocode_address( $post_id, array $address_comps ) {

	$listing = get_post( $post_id );

	if ( ! $listing || empty( $address_comps ) ) {
		return;
	}

	// remove old geocode result
	appthemes_delete_coordinates( $post_id );

	$address = array_map( 'rtrim', $address_comps );
	$address = sanitize_text_field( implode( ', ', $address_comps ) );

	$results = appthemes_geocode_address( $address );

	/**
	 * Fires after fetching results from Geocoder API, but before saving coords.
	 *
	 * @since 4.2.5
	 *
	 * @param int   $post_id The listing ID to save data for.
	 * @param array $results The geocoder results.
	 */
	do_action( 'cp_geocode_lisitng_address', $post_id, $results );

	if ( ! $results || empty( $results['coords'] ) ) {
		return false;
	}

	$lat = $results['coords']->lat;
	$lng = $results['coords']->lng;

	cp_update_geocode( $post_id, '', $lat, $lng );

	return appthemes_get_coordinates( $post_id );
}


/**
 * Returns the content excerpt.
 *
 * @uses apply_filters() Calls 'cp_get_content_preview'
 *
 * @return string
 */
function cp_get_content_preview() {
	return apply_filters( 'cp_get_content_preview', get_the_excerpt(), 55 );
}


/**
 * Loads all page templates, setups cache, limits db queries.
 *
 * @return void
 */
function cp_load_all_page_templates() {
	$pages = get_posts( array(
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'posts_per_page' => -1,
		'no_found_rows' => true,
	) );

}


/**
 * Returns localized status if available.
 *
 * @param string $status
 *
 * @return string
 */
function cp_get_status_i18n( $status ) {
	$statuses = array(
		'active' => __( 'Active', APP_TD ),
		'active_membership' => __( 'Active', APP_TD ),
		'chargeback' => __( 'Chargeback', APP_TD ),
		'completed' => __( 'Completed', APP_TD ),
		'denied' => __( 'Denied', APP_TD ),
		'draft' => __( 'Draft', APP_TD ),
		'ended' => __( 'Ended', APP_TD ),
		'expired' => __( 'Expired', APP_TD ),
		'failed' => __( 'Failed', APP_TD ),
		'future' => __( 'Scheduled', APP_TD ),
		'inactive' => __( 'Inactive', APP_TD ),
		'inactive_membership' => __( 'Inactive', APP_TD ),
		'live' => __( 'Live', APP_TD ),
		'live_expired' => __( 'Live-Expired', APP_TD ),
		'offline' => __( 'Offline', APP_TD ),
		'pending' => __( 'Pending', APP_TD ),
		'pending_moderation' => __( 'Awaiting approval', APP_TD ),
		'pending_payment' => __( 'Awaiting payment', APP_TD ),
		'private' => __( 'Private', APP_TD ),
		'publish' => __( 'Published', APP_TD ),
		'refunded' => __( 'Refunded', APP_TD ),
		'reversed' => __( 'Reversed', APP_TD ),
		'trash' => __( 'Trash', APP_TD ),
		'unverified' => __( 'Unverified', APP_TD ),
		'verified' => __( 'Verified', APP_TD ),
		'voided' => __( 'Voided', APP_TD )
	);

	$status = strtolower( $status );

	if ( array_key_exists( $status, $statuses ) ) {
		return $statuses[ $status ];
	} else {
		return ucfirst( $status );
	}
}


/**
 * Returns ad listing status name.
 *
 * @param int $listing_id (optional)
 *
 * @return string
 */
function cp_get_listing_status_name( $listing_id = 0 ) {
	global $cp_options;

	$listing_id = $listing_id ? $listing_id : get_the_ID();
	$listing = get_post( $listing_id );

	if ( cp_is_listing_expired( $listing->ID ) ) {
		if ( ! $cp_options->post_prune ) {
			return 'live_expired';
		} else {
			return 'ended';
		}
	} else if ( $listing->post_status == 'draft' ) {
		// Compatibility with ads expired prior 4.2.0 update.
		$expire_time = strtotime( get_post_meta( $listing_id, 'cp_sys_expire_date', true ) );
		if ( $expire_time < current_time( 'timestamp' ) ) {
			return 'ended';
		} else {
			return 'offline';
		}
	} else if ( $listing->post_status == 'pending' ) {
		if ( cp_have_pending_payment( $listing->ID ) ) {
			return 'pending_payment';
		} else {
			return 'pending_moderation';
		}
	}

	return 'live';
}


/**
 * Returns user dashboard ad listing actions.
 *
 * @param int $listing_id (optional)
 *
 * @return string
 */
function cp_get_dashboard_listing_actions( $listing_id = 0 ) {
	global $cp_options;

	$actions = array();
	$listing_id = $listing_id ? $listing_id : get_the_ID();
	$listing = get_post( $listing_id );
	$listing_status = cp_get_listing_status_name( $listing_id );

	// edit button
	if ( $cp_options->ad_edit ) {
		$edit_attr = array(
			'title' => __( 'Edit Ad', APP_TD ),
			'description' => '<i class="fa fa-pencil" aria-hidden="true"></i> ' . __( 'Edit Ad', APP_TD ),
			'href' => add_query_arg( array( 'listing_edit' => $listing->ID ), CP_EDIT_URL ),
		);

		if ( in_array( $listing_status, array( 'live', 'offline' ) ) ) {
			$actions['edit'] = $edit_attr;
		}
		if ( $cp_options->moderate_edited_ads && in_array( $listing_status, array( 'pending_moderation', 'pending_payment' ) ) ) {
			$actions['edit'] = $edit_attr;
		}
	}

	// delete button
	$actions['delete'] = array(
		'title' => __( 'Delete Ad', APP_TD ),
		'description' => '<i class="fa fa-trash-o" aria-hidden="true"></i> ' . __( 'Delete Ad', APP_TD ),
		'href' => add_query_arg( array( 'aid' => $listing->ID, 'action' => 'delete' ), CP_DASHBOARD_URL ),
		'onclick' => 'return confirmBeforeDeleteAd();',
	);

	// pause button
	if ( $listing_status == 'live' ) {
		$actions['pause'] = array(
			'title' => __( 'Pause Ad', APP_TD ),
			'href' => add_query_arg( array( 'aid' => $listing->ID, 'action' => 'pause' ), CP_DASHBOARD_URL ),
			'description' => '<i class="fa fa-pause-circle-o" aria-hidden="true"></i> ' . __( 'Pause Ad', APP_TD ),
		);
	}

	// restart button
	if ( $listing_status == 'offline' ) {
		$actions['restart'] = array(
			'title' => __( 'Restart ad', APP_TD ),
			'href' => add_query_arg( array( 'aid' => $listing->ID, 'action' => 'restart' ), CP_DASHBOARD_URL ),
			'description' => '<i class="fa fa-play-circle" aria-hidden="true"></i> ' . __( 'Restart ad', APP_TD ),
		);
	}

	// set/unset sold links
	if ( in_array( $listing_status, array( 'live', 'offline' ) ) ) {
		$sold = get_post_meta( $listing->ID, 'cp_ad_sold', true );
		if ( $sold != 'yes' ) {
			// set sold
			$actions['set_sold'] = array(
				'title' => __( 'Mark Sold', APP_TD ),
				'href' => add_query_arg( array( 'aid' => $listing->ID, 'action' => 'setSold' ), CP_DASHBOARD_URL ),
				'description' => '<i class="fa fa-check-square-o" aria-hidden="true"></i> ' . __( 'Mark Sold', APP_TD ),
			);
		} else {
			// unset sold
			$actions['unset_sold'] = array(
				'title' => __( 'Unmark Sold', APP_TD ),
				'href' => add_query_arg( array( 'aid' => $listing->ID, 'action' => 'unsetSold' ), CP_DASHBOARD_URL ),
				'description' => '<i class="fa fa-square-o" aria-hidden="true"></i> ' . __( 'Unmark Sold', APP_TD ),
			);
		}
	}

	// relist link
	if ( $cp_options->allow_relist && in_array( $listing_status, array( 'ended', 'live_expired' ) ) ) {
		$actions['relist'] = array(
			'title' => __( 'Relist Ad', APP_TD ),
			'href' => add_query_arg( array( 'listing_renew' => $listing->ID ), get_permalink( CP_Renew_Listing::get_id() ) ),
			'description' => '<i class="fa fa-repeat" aria-hidden="true"></i> ' . __( 'Relist Ad', APP_TD ),
		);
	}

	// payment links
	if ( $listing_status == 'pending_payment' ) {
		$order = appthemes_get_order_connected_to( $listing->ID );
		// pay order
		$actions['pay_order'] = array(
			'title' => __( 'Pay now', APP_TD ),
			'href' => appthemes_get_order_url( $order->get_id() ),
			'description' => '<i class="fa fa-credit-card" aria-hidden="true"></i> ' . __( 'Pay now', APP_TD ),
		);
		if ( $order->get_gateway() ) {
			// reset gateway
			$actions['reset_gateway'] = array(
				'title' => __( 'Reset Gateway', APP_TD ),
				'href' => get_the_order_cancel_url( $order->get_id() ),
				'description' => '<i class="fa fa-fast-backward" aria-hidden="true"></i> ' . __( 'Reset Gateway', APP_TD ),
			);
		}
	}

	return apply_filters( 'cp_dashboard_listing_actions', $actions, $listing );
}


/**
 * Displays user dashboard ad listing actions.
 *
 * @param int $listing_id (optional)
 *
 * @return string
 */
function cp_dashboard_listing_actions( $listing_id = 0 ) {
	$listing_id = $listing_id ? $listing_id : get_the_ID();

	$actions = cp_get_dashboard_listing_actions( $listing_id );
	$li = '';

	foreach ( $actions as $action => $attr ) {

		$description = isset( $attr['description'] ) ? $attr['description'] : $attr['title'];
		$attr['class'] = "btn btn-white btn-sm listing-$action-link";

		$a = html( 'a', $attr, $description );
		$li .= html( 'li', array( 'class' => $action ), $a ) . "\r\n";
	}

	$ul = html( 'ul', array( 'id' => 'listing-actions-' . $listing_id, 'class' => 'listing-actions list-inline dashboard-actions' ), $li );
	echo $ul;
}


/**
 * Helper function to display element classes and styles.
 *
 * @param array $tags
 * @param bool  $echo
 *
 * @return void
 */
function cp_display_style( $tags, $echo = true ) {
	global $cp_options;

	$styles = array();

	foreach ( (array) $tags as $tag ) {
		switch ( $tag ) {
			case 'dir_cols':
				$styles[] = 'large-up-' . (int) $cp_options->cat_dir_cols;
				break;
			case 'featured':
				if ( is_sticky() ) {
					$styles[] = 'featured';
				}
				break;
		}
	}

	$styles = apply_filters( 'cp_display_style', $styles, $tags );
	$styles = implode( ' ', $styles );

	if ( $echo ) {
		echo $styles;
	} else {
		return $styles;
	}

}


/**
 * Helper function to display messages.
 *
 * @param string $tag
 *
 * @return void
 */
function cp_display_message( $tag ) {
	global $cp_options;

	switch ( $tag ) {
		case 'terms_of_use':
			$message = $cp_options->ads_tou_msg;
			break;
		case 'ads_form_help':
			$message = $cp_options->ads_form_msg;
			break;
		case 'membership_form_help':
			$message = $cp_options->membership_form_msg;
			break;
		default:
			$message = '';
			break;
	}

	$message = apply_filters( 'the_content', $message );

	echo apply_filters( 'cp_display_message', $message, $tag );
}


/**
 * Displays website current time and timezone in footer.
 * @since 3.3
 *
 * @return void
 */
function cp_website_current_time() {
	global $cp_options;

	if ( ! $cp_options->display_website_time ) {
		return;
	}

	$timezone = get_option( 'gmt_offset' );
	$time = date_i18n( get_option( 'time_format' ) );
	$message = sprintf( __( 'All times are GMT %1$s. The time now is %2$s.', APP_TD ), $timezone, $time );
	$message = html( 'p', $message );
	echo html( 'div', array( 'class' => 'website-time' ), $message );
}


/**
 * Generates unique ID for ads and memberships
 * @since 3.3.1
 *
 * @param string $type
 *
 * @return string
 */
function cp_generate_id( $type = 'ad' ) {
	$id = uniqid( rand( 10, 1000 ), false );

	if ( $type == 'ad' ) {
		if ( cp_get_listing_by_ref( $id ) ) {
			return cp_generate_id();
		}
	}

	return $id;
}


/**
 * Retrieves listing data by given reference ID.
 * @since 3.3.1
 *
 * @param string $reference_id An listing reference ID
 *
 * @return object|bool A listing object, boolean False otherwise
 */
function cp_get_listing_by_ref( $reference_id ) {

	if ( empty( $reference_id ) || ! is_string( $reference_id ) ) {
		return false;
	}

	$reference_id = appthemes_numbers_letters_only( $reference_id );

	$listing_q = new WP_Query( array(
		'post_type' => APP_POST_TYPE,
		'post_status' => 'any',
		'meta_key' => 'cp_sys_ad_conf_id',
		'meta_value' => $reference_id,
		'posts_per_page' => 1,
		'suppress_filters' => true,
		'no_found_rows' => true,
	) );

	if ( empty( $listing_q->posts ) ) {
		return false;
	}

	return $listing_q->posts[0];
}


/**
 * Outputs the HTML for a single instance of the editor.
 * @since 3.3.3
 *
 * @param string $content The content of the editor.
 * @param array $args The textarea attributes.
 *
 * @return void
 */
function cp_editor( $content, $args = array() ) {

	$args = wp_parse_args( $args, array( 'class' => '', 'id' => 'post_content' ) );

	ob_start();
	wp_editor( ' ', $args['id'], cp_get_editor_settings() );
	$editor = ob_get_clean();

	$args['class'] .= ' wp-editor-area';
	$textarea_field = html( 'textarea', $args, apply_filters( 'the_editor_content', $content ) );

	// Note: Replace splitted into 2 steps as some strings might be trigered as regex
	$editor_tmp = preg_replace( '#<textarea[^>]*>(.*?)</textarea>#is', 'textarea_field', $editor );
	echo str_replace( 'textarea_field', $textarea_field, $editor_tmp );
}


/**
 * Returns an array of settings for WP Editor used on the frontend.
 * @since 3.3.3
 *
 * @return array An array of WP Editor settings.
 */
function cp_get_editor_settings() {
	$settings = array(
		'wpautop' => true,
		'media_buttons' => false,
		'teeny' => false,
		'dfw' => true,
		'tinymce' => array(
			'setup' => 'function(ed){ ed.onChange.add(function(ed, l){ed.save(); jQuery("#"+ed.id).valid();})}'
		),
		'quicktags' => array(
			'buttons' => 'strong,em,ul,ol,li,link,close',
		),
	);

	return $settings;
}


/**
 * Returns listing categories.
 *
 * @param int $listing_id (optional)
 *
 * @return array
 */
function cp_get_listing_categories( $listing_id = 0 ) {
	$listing_id = $listing_id ? $listing_id : get_the_ID();

	$_terms = get_the_terms( $listing_id, APP_TAX_CAT );

	if ( ! $_terms || is_wp_error( $_terms ) ) {
		return array();
	}

	// WordPress does not always key with the term_id, but thats what we want for the key.
	$terms = array();
	foreach ( $_terms as $_term ) {
		$terms[ $_term->term_id ] = $_term;
	}

	return $terms;
}


/**
 * Retrieves Listing object.
 *
 * @param int $listing_id The listing ID.
 * @param int $checkout_listing Optional. Is this a valid checkout listing or an auto draft.
 *
 * @return object
 */
function cp_get_listing_obj( $listing_id = 0, $checkout_listing = true ) {
	$listing_id = $listing_id ? $listing_id : get_the_ID();

	$listing = get_post( $listing_id );

	if ( ! $listing ) {
		return false;
	}

	if ( $checkout_listing ) {
		$categories = wp_get_post_terms( $listing->ID, APP_TAX_CAT );
	}
	$listing->category = ( ! empty( $categories ) ) ? $categories[0]->term_id : false;

	$form_id = cp_get_form_id( $listing->category );
	$form_fields = cp_get_custom_form_fields( $form_id );
	foreach ( $form_fields as $field ) {
		if ( in_array( $field->field_name, array( 'post_title', 'post_content' ) ) ) {
			continue;
		} else if ( $field->field_name == 'tags_input' ) {
			$listing->{$field->field_name} = rtrim( trim( cp_get_the_term_list( $listing->ID, APP_TAX_TAG ) ), ',' );
		} else {
			$is_single = ( $field->field_type != 'checkbox' );
			$listing->{$field->field_name} = get_post_meta( $listing->ID, $field->field_name, $is_single );
		}
	}

	// Clear the default 'Auto Draft' title
	if ( $listing->post_title == __( 'Auto Draft' ) ) {
		$listing->post_title = '';
	}

	return $listing;
}


/**
 * Outputs the 'View More Ads' markup.
 *
 * @param string $url The 'View More Ads' URL.
 * @param string $content Optional. The content context for the link (random, popular, etc).
 * @param sting $text Optional. The 'View More Ads' text replacement.
 */
function cp_the_view_more_ads_link( $url, $content = 'default', $text = '' ) {

	$text = $text ? $text : __( 'View More Ads', APP_TD );

	ob_start();

	?><div class="paging"><a href="<?php echo esc_url( $url ); ?>"> <?php echo $text; ?> </a></div><?php

	echo apply_filters( 'cp_the_view_more_ads_link', ob_get_clean(), $url, $content, $text );
}


/**
 * Retrieves the '.min' suffix for CSS/JS files on a live site considering SCRIPT_DEBUG constant.
 *
 * @sinec 3.5
 */
function cp_get_enqueue_suffix() {
	return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
}


//setup function to stop function from failing if sm debug bar is not installed
//this allows for optional use of sm debug bar plugin
if ( ! function_exists( 'dbug' ) ) { function dbug( $args ) {} }


if ( ! function_exists( 'cp_the_archive_title' ) ) :
/**
 * Display the archive title based on the queried object.
 *
 * Based off the WordPress the_archive_title() function.
 * Added here so text strings can be translated with theme.
 *
 * @since 4.0.0
 *
 * @see the_archive_title()
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 * @return string
 */
function cp_the_archive_title( $before = '', $after = '' ) {

	$title = cp_get_the_archive_title();

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'cp_get_the_archive_title' ) ) :
/**
 * Retrieve the archive title based on the queried object.
 * Based off the WordPress core function. Forked so we can
 * extract the text strings for easy translation.
 *
 * @since 4.0.0
 *
 * @return string Archive title.
 */
function cp_get_the_archive_title() {

	if ( is_category() ) {
		$title = single_cat_title( '', false );
	} elseif ( is_tag() ) {
		$title = single_tag_title( '', false );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', APP_TD ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', APP_TD ), get_the_date( _x( 'Y', 'yearly archives date format' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', APP_TD ), get_the_date( _x( 'F Y', 'monthly archives date format' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', APP_TD ), get_the_date( _x( 'F j, Y', 'daily archives date format' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', APP_TD );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', APP_TD );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', APP_TD ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', APP_TD );
	}

	/**
	 * Filter the archive title.
	 *
	 * @since 4.0.0
	 *
	 * @param string $title Archive title to be displayed.
	 */
	return apply_filters( 'cp_get_the_archive_title', $title );
}
endif;

/**
 * Generate icon tooltips via Foundation Framework's tooltip js.
 *
 * @since 4.0.0
 *
 * @param string $text     The text to display in the tooltip.
 * @param string $position (Optional) The placement of the tooltip (e.g. top, left, right).
 * @param string $id       (Optional) The span ID in case you want to control differently.
 * @param string $icon     (Optional) The Font Awesome icon you want to use. Defaults to fa-question-circle.
 * @return string
 */
function cp_tooltip_icon( $text = '', $position = 'top', $id = '', $icon = 'fa-question-circle' ) {

	$output = '<span data-tooltip aria-haspopup="true" id="' . esc_attr( $id ) . '" class="cp-tooltip has-tip ' . esc_attr( $position ) . ' " data-disable-hover="false" tabindex="2" title="' . esc_attr( $text ) . '"><i class="fa ' . esc_attr( $icon ) . '" aria-hidden="true"></i></span>';

	/**
	 * Filters the icon tooltip.
	 *
	 * @since 4.0.0
	 *
	 * @param string $text     The text to display in the tooltip.
	 * @param string $position (Optional) The placement of the tooltip (e.g. top, left, right).
	 * @param string $id       (Optional) The span ID in case you want to control differently.
	 * @param string $icon     (Optional) The Font Awesome icon you want to use. Defaults to fa-question-circle.
	 */
	return apply_filters( 'cp_tooltip_icon', $output, $text, $position, $id, $icon );
}

/**
 * Outputs the form progress step tree using pure CSS
 * @param array $params Additional parameters:
 *	- $walker string					The walker that outputs the progress tree
 *	- $classes['done','todo'] string	The progress CSS classes for 'done' and 'todo' steps
 *
 * @since 4.0.0
 */
function cp_display_form_progress( $params = array() ) {

		$defaults = array(
			'walker' => '_cp_display_form_progress_tree',
		);
		$params = wp_parse_args( $params, $defaults ) ;

	appthemes_display_form_progress( $params );
}

/**
 * Outputs the step tree
 * @param array The steps list
 *
 * @since 4.0.0
 */
function _cp_display_form_progress_tree( $steps ) {
	?>
	<div class="progress-wrapper">
		<ol class="progress-indicator" data-progtrckr-steps="<?php echo esc_attr( count( $steps ) ); ?>">
		<?php
		$current = 0;
		// Find current.
		foreach ( $steps as $step ) {
			if ( 'done' === $step['class'] && $current/* + 1*/ !== count( $steps ) ) {
				$current++;
			}
		}

		$num = 1;
		foreach ( $steps as $step ) {

			$args = array(
				'data-step' => $num,
			);

			if ( 'done' === $step['class'] && $num <= $current ) {
				$args['class'] = ( $num === $current && $num < count( $steps ) ) ? 'is-current' : 'is-complete';
			}

			echo html( 'li', $args, html( 'span', $step['title'] ) );
			//echo html( 'li', $args, html( 'span class="show-for-large"', $step['title'] ) );
			$num++;
		}
		?>
		</ol>
	</div>
	<?php
}

/**
 * The wrapper for the function get_avatar(), allows to set default alt text to
 * use in &lt;img&gt; tag.
 *
 * @since 4.2.0
 *
 * @param mixed $id_or_email The Gravatar to retrieve. Accepts a user_id, gravatar md5 hash,
 *                           user email, WP_User object, WP_Post object, or WP_Comment object.
 * @param int    $size       Optional. Height and width of the avatar image file in pixels. Default 96.
 * @param string $default    Optional. URL for the default image or a default type. Accepts '404'
 *                           (return a 404 instead of a default image), 'retro' (8bit), 'monsterid'
 *                           (monster), 'wavatar' (cartoon face), 'indenticon' (the "quilt"),
 *                           'mystery', 'mm', or 'mysteryman' (The Oyster Man), 'blank' (transparent GIF),
 *                           or 'gravatar_default' (the Gravatar logo). Default is the value of the
 *                           'avatar_default' option, with a fallback of 'mystery'.
 * @param string $alt        Optional. Alternative text to use in &lt;img&gt; tag. Default empty.
 * @param array  $args       {
 *     Optional. Extra arguments to retrieve the avatar.
 *
 *     @type int          $height        Display height of the avatar in pixels. Defaults to $size.
 *     @type int          $width         Display width of the avatar in pixels. Defaults to $size.
 *     @type bool         $force_default Whether to always show the default image, never the Gravatar. Default false.
 *     @type string       $rating        What rating to display avatars up to. Accepts 'G', 'PG', 'R', 'X', and are
 *                                       judged in that order. Default is the value of the 'avatar_rating' option.
 *     @type string       $scheme        URL scheme to use. See set_url_scheme() for accepted values.
 *                                       Default null.
 *     @type array|string $class         Array or string of additional classes to add to the &lt;img&gt; element.
 *                                       Default null.
 *     @type bool         $force_display Whether to always show the avatar - ignores the show_avatars option.
 *                                       Default false.
 *     @type string       $extra_attr    HTML attributes to insert in the IMG element. Is not sanitized. Default empty.
 * }
 * @return false|string `<img>` tag for the user's avatar. False on failure.
 */
function cp_get_avatar( $id_or_email, $size = 96, $default = '', $alt = '', $args = null ) {
	if ( empty( $alt ) ) {
		$user = null;

		// Process the user identifier.
		if ( is_numeric( $id_or_email ) ) {
			$user = get_user_by( 'id', absint( $id_or_email ) );
		} elseif ( $id_or_email instanceof WP_User ) {
			// User Object.
			$user = $id_or_email;
		} elseif ( is_string( $id_or_email ) && is_email( $id_or_email ) ) {
			$user = get_user_by( 'email', $id_or_email );
		} elseif ( $id_or_email instanceof WP_Post ) {
			// Post Object.
			$user = get_user_by( 'id', (int) $id_or_email->post_author );
		} elseif ( $id_or_email instanceof WP_Comment ) {
			if ( ! empty( $id_or_email->user_id ) ) {
				$user = get_user_by( 'id', (int) $id_or_email->user_id );
			}
		}

		if ( $user instanceof WP_User ) {
			$alt = sprintf( __( 'Avatar of %s', APP_TD ), $user->display_name );
		} else {
			$alt = __( 'Avatar of the user', APP_TD );
		}
	}

	return get_avatar( $id_or_email, $size, $default, $alt, $args );
}

/**
 * Turns any sidebar into a tabbed one.
 *
 * NOTE: Read the CP_Tabbed_Sidebar class annotations for info and limitations.
 * @see CP_Tabbed_Sidebar
 *
 * @param string $sidebar_id The sidebar ID.
 */
function cp_tabbed_dynamic_sidebar( $sidebar_id = '' ) {
	if ( ! $sidebar_id ) {
		return;
	}

	$tabbed_sidebar = new CP_Tabbed_Sidebar( $sidebar_id );
	$tabbed_sidebar->render();
}
