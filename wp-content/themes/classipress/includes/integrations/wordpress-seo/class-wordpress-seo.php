<?php
/**
 * WordPress SEO Plugin integration.
 *
 * @url https://wordpress.org/plugins/wordpress-seo/
 *
 * @package ClassiPress\Integrations
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Override certain features from this plugin.
 */
class CP_WordPress_SEO {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Disable their json-ld output since we handle it within our theme.
		add_filter( 'wpseo_json_ld_output', '__return_false' );
	}
}
