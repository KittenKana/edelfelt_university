<?php
/**
 * Widget for listing author content.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing author widget class.
 */
class CP_Widget_Listing_Author extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'cp_widget_listing_author',
			'name'            => __( 'ClassiPress - Single Listing Author', APP_TD ),
			'defaults'        => array(
				'title'     => '',
				'headline'  => __( 'Listing Owner', APP_TD ),
				'biography' => 1,
			),
			'widget_ops'      => array(
				'description' => __( 'Display the listing author.', APP_TD ),
			),
			'control_options' => array(),
		);

		add_action( 'wp_ajax_appthemes_contact_owner', array( __CLASS__, 'contact_process' ) );
		add_action( 'wp_ajax_nopriv_appthemes_contact_owner', array( __CLASS__, 'contact_process' ) );

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * Retrieves form fields in scbForm format.
	 *
	 * @return array Form fields.
	 */
	protected function form_fields() {
		return array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type' => 'text',
				'name' => 'headline',
				'desc' => __( 'Headline:', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'biography',
				'desc' => __( 'Show biographical info', APP_TD ),
			),
		);
	}

	/**
	 * Echo the widget content.
	 *
	 * @param array $args     Display arguments including before_title, after_title,
	 *                        before_widget, and after_widget.
	 * @param array $instance The settings for the particular instance of the widget.
	 */
	function widget( $args, $instance ) {

		// Only allow this widget on the single listing page.
		if ( ! is_single() ) {
			return;
		}

		parent::widget( $args, $instance );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {
		global $wp_query;

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'listing-author' );
	}

	public static function contact_process() {
		global $cp_options;

		// Make sure it's a legit request.
		check_ajax_referer( 'appthemes_contact_owner_nonce', 'nonce' );

		// We need certain params to proceed.
		if ( ! isset( $_POST[ 'id' ] ) ) {
			wp_send_json_error( __( 'Post ID is required.', APP_TD ) );
		}

		// Make sure it's a real post.
		$post = get_post( $_POST[ 'id' ] );

		// Make sure post is valid.
		if ( ! $post ) {
			wp_send_json_error( __( 'Post not found.', APP_TD ) );
		}

		// $error = new WP_Error( '-2', 'The post ID was not set' );
		// wp_send_json_error( $error );

		// Clean the fields.
		if ( isset( $_POST['honeypot'] ) ) {
			$honeypot = trim( $_POST['honeypot'] );
		}

		if ( isset( $_POST['email'] ) && is_string( $_POST['email'] ) ) {
			$contact_email = trim( $_POST['email'] );
		}

		if ( isset( $_POST['message'] ) && is_string( $_POST['message'] ) ) {
			$contact_message = trim( $_POST['message'] );
		}

		$errors = new WP_Error();

		// Validate the fields.
		if ( $honeypot ) {
			$errors->add( 'spam_ trap', __( "You triggered our spam trap. Disable your browsers' autocomplete feature and try again.", APP_TD ) );
		}

		if ( ! is_email( $contact_email ) ) {
			$errors->add( 'required_email', __( 'Name and email are required fields.', APP_TD ) );
		}

		if ( ! $contact_message ) {
			$errors->add( 'no_message', __( 'Please enter a message.', APP_TD ) );
		}

		if ( $cp_options->captcha_enable ) {
			$errors = cp_recaptcha_verify( $errors );
		}

		if ( $errors->get_error_codes() ) {
			wp_send_json_error( $errors );
		}

		cp_contact_ad_owner_email( $post->ID );

		/**
		 * Fires after the contact listing owner email was sent.
		 *
		 * @param WP_Post|array|null $post The post object.
		 */
		do_action( 'appthemes_contact_listing_owner_success', $post );

		// Return the response.
		wp_send_json_success( __( 'Your message was successfully sent!', APP_TD ) );
	}
}
