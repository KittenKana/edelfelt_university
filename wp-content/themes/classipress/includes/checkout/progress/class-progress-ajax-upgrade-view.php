<?php
/**
 * Progress Ajax Upgrade View.
 *
 * @package Components\Checkouts\Progress\Upgrade
 */

/**
 *  Progress Ajax Upgrade View class.
 */
class APP_Progress_Ajax_Upgrade_View extends APP_Progress_Ajax_View {

	protected $new_version, $old_version;

	public function __construct( $checkout_type, $option_key ) {
		add_action( "appthemes_upgrade_$option_key", array( $this, 'init_upgrade' ), 10, 2 );
		parent::__construct( $checkout_type );
	}

	/**
	 * Fires on the App version change and setups upgrade process.
	 *
	 * @param string $new_version New App version.
	 * @param string $old_version Previuosly installed App version.
	 */
	public function init_upgrade( $new_version, $old_version ) {

		// We don't downgrade.
		if ( version_compare( $old_version, $new_version, '>=' ) ) {
			$this->remove_checkout_data();
			return;
		}

		$this->new_version = $new_version;
		$this->old_version = $old_version;

		$this->init_checkout();
	}

	/**
	 * Adds necessary data to a new created checkout object.
	 *
	 * @param APP_Dynamic_Checkout $checkout The checkout object.
	 */
	public function add_checkout_data( $checkout ) {
		if ( isset( $this->new_version ) && isset( $this->old_version ) ) {
			$checkout->add_data( 'new_version', $this->new_version );
			$checkout->add_data( 'old_version', $this->old_version );
		}
	}

}
