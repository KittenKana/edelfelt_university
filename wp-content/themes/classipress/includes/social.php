<?php
/**
 * Social meta functions
 *
 * @package ClassiPress\Social
 *
 * @since 4.0.0
 */

/**
 * Returns the title for the given social network
 *
 * @since 1.0.0
 *
 * @param string $social_network
 *
 * @return string social network name
 */
function cp_get_social_network_title( $social_network ) {
	return APP_Social_Networks::get_title( $social_network );
}

/**
 * Returns the tip for filling the given social network
 *
 * @since 1.0.0
 *
 * @param string $social_network
 *
 * @return string social network tip
 */
function cp_get_social_network_tip( $social_network ) {
	return APP_Social_Networks::get_tip( $social_network );
}

/**
 * Returns the user account URL for the given social network
 *
 * @since 1.0.0
 *
 * @param type $social_network
 * @param type $account
 *
 * @return string Escaped URL
 */
function cp_get_social_account_url( $social_network, $account = '' ) {
	return APP_Social_Networks::get_url( $social_network, $account );
}

/**
 * Get all user social networks.
 *
 * @since 4.0.0
 *
 * @param boolean $remove_empty Removes any social networks from array if value is empty.
 * @param int     $user_id      User ID.
 *
 * @return array $fields Key value pair of user social network name and url.
 */
function cp_get_available_user_networks( $remove_empty = false, $user_id = 0 ) {

	$fields = array();

	foreach ( cp_get_allowed_user_networks() as $key => $value ) {
		$fields[ $value ] = get_user_meta( $user_id, $value, true );

		// Backward compatibility with legacy meta fields.
		if ( ! $fields[ $value ] && ( 'twitter' == $value || 'facebook' === $value ) ) {
			$fields[ $value ] = get_user_meta( $user_id, $value . '_id', true );
		}
	}

	if ( true === $remove_empty ) {
		$fields = array_filter( $fields );
	}

	return $fields;
}

/**
 * Get all user account social networks.
 *
 * @since 4.0.0
 */
function cp_get_allowed_user_networks() {
	/**
	 * Filter the whitelist of user account social networks.
	 *
	 * @since 4.0.0
	 *
	 * @param array A list of all whitelisted social networks.
	 */
	return apply_filters( 'cp_user_allowed_social_networks', cp_allowed_social_networks() );
}

/**
 * Retrieves an array of allowed social networks to be refined for each type of object
 *
 * @since 4.0.0
 *
 * @return array An array of allowed registered social networks
 */
function cp_allowed_social_networks() {
	$networks = array(
		'google-plus',
		'facebook',
		'twitter',
		'instagram',
		'youtube',
		'pinterest',
	);

	/**
	 * Filter the whitelist of social networks.
	 *
	 * @since 4.0.0
	 *
	 * @param array $networks A list of all whitelisted social networks.
	 */
	return apply_filters( 'cp_allowed_social_networks', $networks );
}