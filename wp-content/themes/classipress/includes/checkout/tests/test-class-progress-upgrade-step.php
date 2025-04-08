<?php
/**
 * Example of the progress upgrade step.
 *
 * @group checkout
 */
class APP_Progress_Step_Test extends APP_Progress_Upgrade_Step {

	public function get_total() {
		return 50;
	}

	public function progress( APP_Dynamic_Checkout $checkout ) {

		$data  = $checkout->get_data( 'progress_data' );
		$total = $data[$this->step_id]['total'];

		return ( $total - $data[$this->step_id]['done'] ) ? 5 : 0;
	}

}
