<?php
/**
 * Represents an escrow order.
 *
 * @package Components\Payments\Escrow
 */
class APP_Escrow_Order extends APP_Order {

	protected $statuses = array(
		APPTHEMES_ORDER_PENDING => 'pending',
		APPTHEMES_ORDER_FAILED => 'failed',
		APPTHEMES_ORDER_REFUNDED => 'refunded',
		APPTHEMES_ORDER_PAID => 'paid',
		APPTHEMES_ORDER_COMPLETED => 'completed',
	);

	/**
	 * User ID's list the secondary receivers for escrow funds.
	 * @var array
	 */
	protected $receivers = array();

	/**
	 * Indentifies a refunded order.
	 * @var bool
	 */
	protected $refunded;

	/**
	 * Sets up the order objects.
	 *
	 * @param object $post Post object returned from get_post()
	 * See (https://developer.wordpress.org/reference/functions/get_post/)
	 * @param array $items Array of items currently attached to the order
	 */
	public function __construct( $post, $items ) {

		parent::__construct( $post, $items );

		$receivers = get_post_meta( $this->id, 'receivers', true );

		if ( ! empty( $receivers ) ) {
			$this->receivers = (array) $receivers;
		}
	}

	/**
	 * Adds a receiver to the order.
	 *
	 * @param int $user_id The receiver user ID
	 * @param double $amount The amount to be transferred to the receiver (after fees)
	 * @param bool $fees If set to True subtracts the site fees to the receiver final amount
	 *
	 * @return bool|void False if user is already a receiver, True otherwise
	 */
	public function add_receiver( $user_id, $amount, $fees = true ) {

		$receivers = $this->get_receivers();

		if ( isset( $receivers[ $user_id ] ) ) {
			return false;
		}

		$this->receivers[ $user_id ] = $fees ? appthemes_escrow_receiver_amount( $amount ) : $amount;

		update_post_meta( $this->id, 'receivers', $this->receivers );

		return true;
	}

	/**
	 * Retrieves the list of receivers for the current Order.
	 *
	 * @return array The receivers list
	 */
	public function get_receivers() {
		return $this->receivers;
	}

	/**
	* Returns a status's hook identifier.
	 *
	* @return string
	*/
	protected function get_status_identifier( $status ){
		if ( ! isset( $this->statuses[ $status] ) ) {
			return false;
		}
		return $this->statuses[ $status ];
	}

	/**
	 * Returns a version of the current state for display..
	 *
	 * @return string Current state, localized for display
	 */
	public function get_display_status() {

		$statuses = array(
			APPTHEMES_ORDER_PENDING => __( 'Pending (Waiting for Funds)', APP_TD ),
			APPTHEMES_ORDER_FAILED => __( 'Failed', APP_TD ),
			APPTHEMES_ORDER_PAID => __( 'Paid (Funds in Escrow)', APP_TD ),
			APPTHEMES_ORDER_REFUNDED => __( 'Refunded', APP_TD ),
			APPTHEMES_ORDER_COMPLETED => __( 'Completed', APP_TD ),
		);

		$status = $this->get_status();
		return $statuses[ $status ];
	}

	/**
	 * Sets the order as pending. Causes action 'appthemes_transaction_pending'.
	 */
	public function pending() {
		$this->set_status( APPTHEMES_ORDER_PENDING );
		$this->log( __( 'Marked as Pending', APP_TD ), 'minor' );
	}

	/**
	 * Sets the order as failed. Causes action 'appthemes_transaction_failed'.
	 */
	public function failed() {
		$this->set_status( APPTHEMES_ORDER_FAILED );
		$this->log( __( 'Marked as Failed', APP_TD ), 'major' );
	}

	/**
	 * Sets the order as refunded. Causes action 'appthemes_transaction_refunded'.
	 */
	public function refunded(){
		$response = true;

		if ( APPTHEMES_ORDER_PAID === $this->get_status() ) {
			$response = appthemes_escrow_refund( get_post( $this->get_id() ) );
		}

		if ( $response ) {
			$this->set_status( APPTHEMES_ORDER_REFUNDED );
			$this->log( __( 'Marked as Refunded', APP_TD ), 'major' );
		} else {
			$this->log( __( 'Failed to Refund', APP_TD ), 'major' );
		}
	}

	/**
	 * Sets the order as paid. Causes action 'appthemes_transaction_paid'.
	 */
	public function paid() {
		$this->set_status( APPTHEMES_ORDER_PAID );
		$this->log( __( 'Marked as Paid', APP_TD ), 'major' );
	}

	/**
	 * Sets the order as completed. Causes action 'appthemes_transaction_completed'.
	 */
	public function complete() {
		$response = appthemes_escrow_complete( $this );

		if ( $response ) {
			$this->set_status( APPTHEMES_ORDER_COMPLETED );
			$this->log( __( 'Marked as Completed', APP_TD ), 'major' );
		} else {
			$this->log( __( 'Failed to Complete', APP_TD ), 'major' );
		}
	}

	/**
	 * Returns true if the order recurrs.
	 */
	public function is_recurring(){
		return false;
	}

	/**
	 * Returns true if escrow order.
	 */
	public function is_escrow(){
		return true;
	}

}
