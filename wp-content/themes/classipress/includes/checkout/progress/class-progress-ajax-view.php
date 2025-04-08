<?php
/**
 * Progress Ajax View.
 *
 * @package Components\Checkouts\Progress
 */

/**
 *  Progress Ajax View class.
 */
class APP_Progress_Ajax_View extends APP_Progress_View {

	public function __construct( $checkout_type ) {
		parent::__construct( $checkout_type );

		add_action( 'wp_ajax_' . $this->checkout_type, array( $this, 'process' ) );
		add_action( 'wp_ajax_nopriv_' . $this->checkout_type, array( $this, 'process' ) );
	}

	/**
	 * Processes steps.
	 */
	public function process() {

		if ( ! $this->condition() ) {
			return;
		}

		check_ajax_referer( $this->checkout_type, '_progress_nonce' );

		$current_step = $this->get_current_step();

		if ( isset( $_REQUEST['_progress_nonce'] ) ) {
			$_REQUEST[ $current_step ] = $_REQUEST['_progress_nonce'];
		}

		appthemes_process_background_checkout( $this->get_checkout_type(), $this->get_hash(), $current_step );

		$checkout = appthemes_get_checkout();

		ob_start();
		$checkout->display_step( $current_step );
		$html = ob_get_clean();

		$progress_data = $checkout->get_data( 'progress_data' );

		$data = array_merge( $progress_data[ $current_step ], array(
			'step_id' => $current_step,
			'html' => $html,
		) );

		die ( json_encode( $data ) );
	}

	/**
	 * Retrieves the current step.
	 *
	 * @return string
	 */
	protected function get_current_step() {

		appthemes_setup_checkout( $this->get_checkout_type(), $this->basic_url(), $this->get_hash() );
		$checkout  = appthemes_get_checkout();
		$completed = $checkout->get_data( 'step-completed' );

		foreach ( $checkout->get_steps() as $step ) {
			if ( ! in_array( $step['id'], (array) $completed ) ) {
				return $step['id'];
			}
		}
	}

	public function enqueue_scripts() {
		APP_Ajax_View::enqueue_scripts();
		wp_enqueue_script(
			'app-ajax-progress',
			APP_CHECKOUTS_URI . '/progress/app.jquery.ajax-progress.js',
			array( 'jquery', 'app-action-button' ),
			'1.0.0'
		);

		wp_localize_script( 'app-ajax-progress', 'appAjaxProgress', array(
			'done' => __( 'Done!', APP_TD ),
		) );
	}
}

