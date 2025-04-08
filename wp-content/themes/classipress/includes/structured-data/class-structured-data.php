<?php
/**
 * Structured data markup classes.
 *
 * @package Components\StructuredData
 * @author AppThemes
 * @since 1.0.0
 */

if ( ! class_exists( 'APP_Structured_Data_Autoload' ) ) {
	require_once 'class-autoload.php';
	APP_Structured_Data_Autoload::register();
}

if ( ! class_exists( 'APP_Structured_Data' ) ) {

/**
 * Main class to output json-ld schema.org markup.
 *
 * @since 1.0.0
 */
final class APP_Structured_Data {

	const VERSION = '2.0.1';

	/**
	 * Holds the payload to output json-ld.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public $data = array();

	/**
	 * Holds an instance of the class.
	 *
	 * @since 1.0.0
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Singleton implementation to load construct only once.
	 *
	 * @since 1.0.0
	 *
	 * @return object
	 */
	public static function instance() {

		if ( isset( self::$instance ) ) {
			return self::$instance;
		}

		self::$instance = new self;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'wp_head', array( $this, 'build_schema' ) );
	}

	/**
	 * Build the json-ld schema.org output based on page being viewed.
	 *
	 * Main logic method sub-classes should override in their code.
	 *
	 * @since 1.0.0
	 */
	public function build_schema() {

		/**
		 * Fires at the end of the build schema method before output.
		 *
		 * @since 1.0.0
		 */
		do_action( 'appthemes_schema_build_data' );

		// Send everything to the final output method.
		$this->output();
	}

	/**
	 * Adds the organization json-ld schema.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @return array
	 */
	public function organization_home() {
		_deprecated_function( __METHOD__, '2.0.0' );
		$organization = new APP_Schema_Type_Organization_Home();
		$output = $organization->build();

		/**
		 * Filters the organization home schema output.
		 *
		 * @since 1.0.0
		 *
		 * @param array $output The organization home schema output.
		 */
		return apply_filters( 'appthemes_schema_organization_home', $output );
	}

	/**
	 * Add blog post comments to the output filter.
	 *
	 * Since module version 2.0.0 comments added automatically to the output.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @param array $output List of BlogPosting type schema properties.
	 * @return array
	 */
	public function add_comments( $output ) {
		_deprecated_function( __METHOD__, '2.0.0' );
		return $output;
	}

	/**
	 * Add blog post tags to the output filter.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @param array $output List of BlogPosting type schema properties.
	 * @return array
	 */
	public function add_tags( $output ) {
		_deprecated_function( __METHOD__, '2.0.0' );
		return $output;
	}

	/**
	 * Add blog categories to the output filter.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @param array $output List of BlogPosting type schema properties.
	 * @return array
	 */
	public function add_categories( $output ) {
		_deprecated_function( __METHOD__, '2.0.0' );
		return $output;
	}

	/**
	 * Add the blog post word count to the output filter.
	 *
	 * Since module version 2.0.0 wordCount added automatically to the output.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @param array $output List of BlogPosting type schema properties.
	 * @return array
	 */
	public function add_word_count( $output ) {
		_deprecated_function( __METHOD__, '2.0.0' );
		return $output;
	}

	/**
	 * Add the person content for the author page to the output filter.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @param array $output List of Person type schema properties.
	 * @return array
	 */
	public function author() {
		_deprecated_function( __METHOD__, '2.0.0' );
		$author = get_user_by( 'id', get_query_var( 'author' ) );
		$output = array();

		if ( $author ) {
			$schema = new APP_Schema_Type_Person_User( $author );
			$output = $schema->build();
		}

		return $output;
	}

	/**
	 * Count the total number of words in the post.
	 *
	 * @since 1.0.0
	 * @deprecated since 2.0.0
	 *
	 * @return int The count of total words.
	 */
	public function word_count() {
		global $post;
		_deprecated_function( __METHOD__, '2.0.0', 'APP_Schema_Type_Article::get_word_count( $text )' );
		return APP_Schema_Type_Article::get_word_count( $post->post_content );
	}

	/**
	 * Output the final json-ld code and display it.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function output() {

		/**
		 * Filters the final data output before delivering it to the browser.
		 *
		 * @since 1.0.0
		 *
		 * @param array $this->data The entire data payload of schema.org output.
		 */
		$data = apply_filters( 'appthemes_schema_output', $this->data );

		if ( is_array( $data ) && ! empty( $data ) ) {
			foreach ( $data as &$type ) {
				$type = array_merge( array( '@context' => 'http://schema.org' ), $type );
			}

			if ( defined( 'SCRIPT_DEBUG' ) && version_compare( PHP_VERSION, '5.4.0', '>=' ) ) {
				$output = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
			} else {
				$output = wp_json_encode( $data );
			}

			echo '<!-- Start AppThemes json-ld structured data -->', PHP_EOL;
			echo '<script type="application/ld+json">', PHP_EOL;
			echo $output, PHP_EOL;
			echo '</script>', PHP_EOL;
			echo '<!-- End AppThemes json-ld structured data -->', PHP_EOL;
		}

		// Empty the array so we don't output it multiple times.
		$this->data = array();
	}
}


}

// Backward compatibility with version 1.0.0.
if ( ! defined( 'APP_Structured_Data::VERSION' ) ) {
	$schema = APP_Structured_Data::instance();
	remove_action( 'init', array( $schema, 'require_files' ) );
	add_filter( 'appthemes_schema_output', '_appthemes_check_structured_data' );

	function _appthemes_check_structured_data( $data = array() ) {

		if ( is_array( $data ) && ! empty( $data ) ) {
			foreach ( $data as &$type ) {
				$type = array_merge( array( '@context' => 'http://schema.org' ), $type );
			}
		}

		return $data;
	}
}
