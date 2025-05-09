<?php
/**
 * Google reCaptcha.
 *
 * @reference https://developers.google.com/recaptcha/intro
 * @reference https://developers.google.com/recaptcha/docs/display (v2)
 * @reference https://developers.google.com/recaptcha/docs/v3 (v3)
 * @reference https://developers.google.com/recaptcha/docs/verify (v2 & v3)
 *
 * @package Components\reCaptcha
 */

/**
 * Google reCaptcha Wrapper Class.
 */
class APP_Recaptcha {

	const JS_HANDLE = 'g-recaptcha';
	const API_SECURE_SERVER = 'https://www.google.com/recaptcha/api.js';

	/**
	 * The site key.
	 *
	 * @var string
	 */
	private static $site_key;

	/**
	 * The private key.
	 *
	 * @var string
	 */
	private static $private_key;

	/**
	 * API version.
	 *
	 * @var string
	 */
	private static $api_version = 'v2';

	/**
	 * Additional options.
	 *
	 * @var array
	 */
	private static $options;

	/**
	 * Initialize the ReCaptcha wrapper class.
	 *
	 * @param  string $site_key           The site key.
	 * @param  string $private_key        The private key.
	 * @param  array  $options (optional) Additional ReCaptcha options like: theme, type or size.
	 */
	public static function init( $site_key, $private_key, $options = array() ) {

		self::$site_key    = $site_key;
		self::$private_key = $private_key;
		self::$options     = $options;

		if ( ! empty( self::$options['api_version'] ) ) {
			self::$api_version = self::$options['api_version'];
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_scripts' ), 9 );
		add_filter( 'script_loader_tag', array( __CLASS__, 'defer_scripts' ), 10, 2 );
	}

	/**
	 * Register script dependencies.
	 *
	 * Important: the theme or plugin must explicitly use function
	 * appthemes_enqueue_recaptcha_scripts().
	 */
	public static function register_scripts() {
		$url_args = array();
		if ( self::$api_version === 'v3' ) {
			$url_args['render'] = self::$site_key;
		}

		/**
		 * Allows to add parameters to the service URL.
		 *
		 * See https://developers.google.com/recaptcha/docs/display#js_param
		 */
		$url_args = apply_filters( 'appthemes_recaptcha_url_args', $url_args );
		$secure_server = esc_url( add_query_arg( $url_args, self::API_SECURE_SERVER ) );

		wp_register_script( self::JS_HANDLE, $secure_server, array(), self::$api_version, true );
	}

	/**
	 * Enqueue script dependencies.
	 *
	 * Used by appthemes_enqueue_recaptcha_scripts().
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( self::JS_HANDLE );
	}

	/**
	 * Updates the <script> tag to use 'defer' and 'async' properties.
	 *
	 * Note: the 'script_loader_tag' was only introduced in WP 4.1.
	 *
	 * @param  string $tag    The HTML tag.
	 * @param  string $handle The script handle name.
	 * @return string         The updated HTML tag.
	 */
	public static function defer_scripts ( $tag, $handle ) {

		if ( self::JS_HANDLE !== $handle ) {
			return $tag;
		}

		if ( self::$api_version === 'v3' ) {
			return $tag;
		}

		return str_replace( ' src', ' defer async src', $tag );
	}

	/**
	 * Outputs the ReCaptcha widget.
	 */
	public static function display() {
		if ( self::$api_version === 'v3' ) {
			self::display_v3();
		} else {
			self::display_v2();
		}
	}

	/**
	 * Outputs the ReCaptcha widget v2.
	 */
	public static function display_v2() {

		$atts = array();

		$defaults = array(
			'theme' => 'light',
			'type'  => 'image',
			'size'  => wp_is_mobile() ? 'compact' : 'normal',
		);
		$options = wp_parse_args( self::$options, $defaults );

		foreach ( $options as $key => $value ) {
			if ( ! in_array( $key, array( 'theme', 'type', 'size' ) ) ) {
				continue;
			}
			$atts[] = sprintf( ' data-%1$s = "%2$s" ', $key, esc_attr( $value ) );
		}

		?><div class="g-recaptcha" data-sitekey="<?php esc_attr_e( self::$site_key ); ?>" <?php echo implode( ' ', $atts ); ?> ></div><?php
	}

	/**
	 * Outputs the ReCaptcha widget v3.
	 */
	public static function display_v3() {
		?>
		<input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response" value="">
		<script>
			jQuery(document).ready(function($) {
				grecaptcha.ready(function() {
					grecaptcha.execute('<?php echo esc_js( self::$site_key ); ?>', {action: 'social'}).then(function(token) {
						document.getElementById('g-recaptcha-response').value = token;
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Verifies the user response token.
	 *
	 * @param  string $response  The user response token.
	 * @param  string $remote_ip The users IP address.
	 * @return boolean|WP_Error  True on success, WP_Error object on failure.
	 */
	public static function verify( $response, $remote_ip ) {

		self::include_dependencies();

		$recaptcha = new ReCaptcha\ReCaptcha( self::$private_key, new APP_Recaptcha_Request() );

		$resp = $recaptcha->verify( $response, $remote_ip );

		if ( ! $resp->isSuccess() ) {

			$errors = new WP_Error();

			foreach ( $resp->getErrorCodes() as $code ) {
				$errors->add( $code, self::get_error_message( $code ) );
			}

			return $errors;
		}

		// Check v3 score
		if ( self::$api_version === 'v3' ) {
			$req_score = floatval( self::$options['score'] );
			$score = floatval( $resp->getScore() );
			if ( $score < $req_score ) {
				return new WP_Error( 'invalid-input-response', __( 'The reCaptcha response score is too low.', APP_TD ) );
			}
		}

		return true;
	}

	/**
	 * Retrieve the error description for a given error code.
	 *
	 * @param  string $code The error code.
	 * @return string       The related error description.
	 */
	private static function get_error_message( $code ) {

		$errors = self::get_errors_verbiages();
		if ( empty( $errors[ $code ] ) ) {
			return $code;
		}

		return $errors[ $code ];
	}

	/**
	 * Retrieves the list of all the available error verbiages.
	 *
	 * @return array The list of error verbiages.
	 */
	private static function get_errors_verbiages() {

		return array(
			'missing-input-secret'   => __( 'The reCaptcha secret parameter is missing.', APP_TD ),
			'invalid-input-secret'   => __( 'The reCaptcha secret parameter is invalid or malformed.', APP_TD ),
			'missing-input-response' => __( 'The reCaptcha response is missing.', APP_TD ),
			'invalid-input-response' => __( 'The reCaptcha response is invalid or malformed.', APP_TD ),
			'bad-request'            => __( 'The reCaptcha request is invalid or malformed.', APP_TD ),
			'timeout-or-duplicate'   => __( 'The reCaptcha response is no longer valid: either is too old or has been used previously.', APP_TD ),
		);

	}

	/**
	 * Include ReCpatcha API dependencies.
	 */
	protected static function include_dependencies() {
		// https://github.com/google/recaptcha
		require_once( dirname( __FILE__ ) . '/lib/autoload.php' );

		require_once( dirname( __FILE__ ) . '/class-recaptcha-request.php' );
	}

}
