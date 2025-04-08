<?php
/**
 * WordPress Foundation Framework integration.
 *
 * @url http://foundation.zurb.com/
 *
 * @package ClassiPress\Integrations
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Override certain features.
 */
class CP_Foundation_Framework {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'embed_oembed_html', array( $this, 'add_oembed_wrapper' ), 10, 4 );
	}

	/**
	 * Add a custom wrapper around WordPress oembeds so we can
	 * use Foundation's Flex Video module for responsive viewing.
	 *
	 * @see WP_Embed::shortcode()
	 *
	 * @param string $html    The cached HTML result, stored in post meta.
	 * @param string $url     The attempted embed URL.
	 * @param array  $attr    An array of shortcode attributes.
	 * @param int    $post_ID Post ID.
	 *
	 * @return string
	 */
	public function add_oembed_wrapper( $html, $url, $attr, $post_id ) {
		return '<div class="flex-video widescreen">' . $html . '</div>';
	}

}
