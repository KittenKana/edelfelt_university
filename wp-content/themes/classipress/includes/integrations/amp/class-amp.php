<?php
/**
 * WordPress Amp Plugin integration.
 *
 * @url https://wordpress.org/plugins/amp/
 * @url https://github.com/Automattic/amp-wp/blob/master/readme.md
 *
 * @package ClassiPress\Integrations
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Custom actions and filters to use with plugin.
 */
class CP_Amp {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'amp_init',                   array( $this, 'add_post_type_support' ) );
		add_filter( 'amp_content_max_width',      array( $this, 'amp_content_max_width' ) );
		add_filter( 'amp_post_template_metadata', array( $this, 'amp_post_template_metadata' ), 10, 2 );
	}

	/**
	 * Add custom post type support.
	 *
	 * @since 4.0.0
	 */
	public function add_post_type_support() {
		add_post_type_support( APP_POST_TYPE, AMP_QUERY_VAR );
	}

	/**
	 * Increase the max content width.
	 *
	 * @since 4.0.0
	 */
	public function amp_content_max_width( $content_max_width ) {
		return 810;
	}

	/**
	 * Use our own structured data markup.
	 *
	 * @since 4.0.0
	 */
	public function amp_post_template_metadata( $metadata, $post ) {

		if ( is_singular( 'post' ) && class_exists( 'APP_Schema_Type_BlogPosting_Post' ) ) {
			$schema   = new APP_Schema_Type_BlogPosting_Post( $post );
			$metadata = $schema->build();
		}

		if ( APP_POST_TYPE == $post->post_type ) {
			/* @var $schema CP_Structured_Data */
			$schema   = appthemes_get_instance( 'CP_Structured_Data' );
			$metadata = $schema->build_listing_schema( $post );
		}

		// Avoid data with no context.
		$metadata = array_merge( array( '@context' => 'http://schema.org' ), $metadata );

		return $metadata;
	}

}
