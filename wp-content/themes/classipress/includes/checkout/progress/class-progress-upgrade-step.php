<?php
/**
 * Progress Upgrade Step.
 *
 * @package Components\Checkouts\Progress\Upgrade
 */

/**
 * Upgrade Step Base class
 */
abstract class APP_Progress_Upgrade_Step extends APP_Progress_Step {

	/**
	 * Creates new Checkout Step instance
	 *
	 * @param string $step_id Step ID.
	 * @param array  $args    Step arguments.
	 */
	public function __construct( $step_id, $args = array() ) {

		$args = wp_parse_args( $args, array(
			'since' => '',
		) );

		parent::__construct( $step_id, $args );
	}

	/**
	 * Test if this step should registered in checkout.
	 *
	 * @return boolean
	 */
	public function condition() {
		$checkout    = appthemes_get_checkout();
		$new_version = $checkout->get_data( 'new_version' );
		$old_version = $checkout->get_data( 'old_version' );

		// If there is no old_version - it's definitely installation.
		//
		// Step version might be empty only if there is no previous one.
		if ( ! $old_version && empty( $this->args['since'] ) ) {
			return true;
		}

		// Otherwise it's upgrade. Upgrade must be provided with old_version.
		//
		// Step version must be greater than old version.
		// Step version must not be greater than new version.
		if ( $old_version && version_compare( $this->args['since'], $old_version, '>' ) && version_compare( $this->args['since'], $new_version, '<=' ) ) {
			return true;
		}

		return false;
	}

}
