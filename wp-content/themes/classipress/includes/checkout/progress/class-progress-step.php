<?php
/**
 * Progress Step Base
 *
 * @package Components\Checkouts\Progress
 */

/**
 * Progress Step Base class
 */
abstract class APP_Progress_Step extends APP_Checkout_Step {

	/**
	 * Retrieves total number of items to be processed withing current step.
	 *
	 * @return int
	 */
	abstract public function get_total();

	/**
	 * Processes items.
	 *
	 * @param APP_Dynamic_Checkout $checkout Checkout object.
	 *
	 * @return int The number of actually processed items.
	 */
	abstract public function progress( APP_Dynamic_Checkout $checkout );

	/**
	 * Creates new Checkout Step instance
	 *
	 * @param string $step_id Step ID.
	 * @param array  $args    Step arguments.
	 */
	public function __construct( $step_id, $args = array() ) {

		$args = wp_parse_args( $args, array(
			'title' => $step_id,
			'icon'  => 'dashicons-before dashicons-admin-generic',
		) );

		parent::__construct( $step_id, $args );
	}

	/**
	 * Displays Checkout Step
	 *
	 * @param APP_Order            $order    Order object.
	 * @param APP_Dynamic_Checkout $checkout Checkout object.
	 */
	public function display( $order, $checkout ) {

		$progress_data  = (array) $checkout->get_data( 'progress_data' );
		$progress_data  = array_filter( $progress_data );
		$done = $total = $sep = '';

		if ( isset( $progress_data[ $this->step_id ] ) ) {
			$done  = $progress_data[ $this->step_id ]['done'];
			$total = $progress_data[ $this->step_id ]['total'];
			$sep   = '/';
		}

		?>
		<p>
			<span class="<?php echo esc_attr( $this->args['icon'] ); ?>"></span>&nbsp;
			<span class="app-progress-step-name"><?php echo $this->args['title']; ?></span>&nbsp;
			<span class="app-progress-step-done"><?php echo $done; ?></span><?php echo $sep; ?><span class="app-progress-step-total"><?php echo $total; ?></span>
			<span id="app-progress-step-<?php echo esc_attr( $this->step_id ); ?>-spinner" class="app-progress-step-spinner spinner" style="float: none;"></span>
		</p>
		<?php
	}

	/**
	 * Processes step
	 *
	 * @param APP_Order            $order    Order object.
	 * @param APP_Dynamic_Checkout $checkout Checkout object.
	 */
	final public function process( $order, $checkout ) {
		if ( ! $this->check_access() ) {
			return;
		}
		$progress_data  = (array) $this->checkout->get_data( 'progress_data' );
		$progress_data  = array_filter( $progress_data );

		if ( ! isset( $progress_data[ $this->step_id ] ) ) {
			$progress_data[ $this->step_id ]['total'] = absint( $this->get_total() );
			$progress_data[ $this->step_id ]['done']  = 0;

			$this->checkout->add_data( 'progress_data', $progress_data );
		}

		$progress = $this->progress( $checkout );

		$progress_data[ $this->step_id ]['done'] += absint( $progress );

		if ( ! $progress || $progress_data[ $this->step_id ]['done'] >= $progress_data[ $this->step_id ]['total'] ) {
			$this->finish_step();
		}

		$this->checkout->add_data( 'progress_data', $progress_data );
	}

	/**
	 * Test if this step should registered in checkout.
	 *
	 * @return boolean
	 */
	public function condition() {
		return true;
	}

	/**
	 * Conditionally registers step in checkout process.
	 *
	 * @param APP_Dynamic_Checkout $checkout Current Checkout type instance.
	 */
	public function register( $checkout ) {
		if ( $this->condition() ) {
			parent::register( $checkout );
		}
	}

	/**
	 * Checks whether current step can be processed.
	 *
	 * @return bool
	 */
	protected function check_access() {

		if ( ! isset( $_REQUEST[ $this->step_id ] ) ) { // Input var okay.
			return false;
		}

		if ( ! wp_verify_nonce( $_REQUEST[ $this->step_id ], $this->checkout->get_checkout_type() ) ) {
			return false;
		}

		if ( ! $this->check_previous() ) {
			$this->cancel_step();
			return false;
		}

		if ( $this->check_step( $this->step_id ) ) {
			$this->finish_step();
			return false;
		}

		return true;
	}

	/**
	 * Checks whether given step was completed.
	 *
	 * @param string $step_id The step ID.
	 *
	 * @return bool True if step was competed, false otherwise.
	 */
	public function check_step( $step_id ) {
		$completed = (array) $this->checkout->get_data( 'step-completed' );
		return in_array( $step_id, $completed, true );
	}

	/**
	 * Checks whether previous step was completed.
	 *
	 * @return boolean
	 */
	public function check_previous() {

		if ( $this->step_id === $this->checkout->get_previous_step() ) {
			return true;
		}

		$previous_step = $this->checkout->get_previous_step( $this->step_id );

		return $this->check_step( $previous_step );
	}

	/**
	 * Finish Checkout Step
	 */
	public function finish_step() {
		$completed = $this->checkout->get_data( 'step-completed' );

		if ( ! is_array( $completed ) ) {
			$completed = array();
		}

		if ( ! in_array( $this->step_id, $completed, true ) ) {
			$completed[] = $this->step_id;
		}

		$this->checkout->add_data( 'step-completed', $completed );

		parent::finish_step();
	}

}
