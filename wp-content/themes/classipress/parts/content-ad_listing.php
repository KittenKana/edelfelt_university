<?php
/**
 * Ad listing single content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

if ( ! dynamic_sidebar( 'sidebar_listing_tiled_content' ) ) {

	if ( ! get_theme_mod( 'show_listing_banner_image', 1 ) ) {

		get_template_part( 'parts/content-title', get_post_type() );

	}
}

cp_tabbed_dynamic_sidebar( 'sidebar_listing_content_tabs' );

dynamic_sidebar( 'sidebar_listing_content' );