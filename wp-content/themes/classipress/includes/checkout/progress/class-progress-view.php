<?php
/*
 * Progress View.
 *
 * @package Components\Checkouts\Progress
 */

/**
 * Progress View class
 */
class APP_Progress_View extends APP_View {

	/**
	 * The progress checkout type.
	 *
	 * @var string
	 */
	protected $checkout_type;

	/**
	 * Process instances.
	 *
	 * @var APP_Progress_View[]
	 */
	private static $instances = array();

	public function __construct( $checkout_type ) {

		$this->checkout_type = $checkout_type;

		add_action( "appthemes_checkout_{$this->get_checkout_type()}_created", array( $this, 'add_checkout_data' ) );
		add_action( "appthemes_checkout_{$this->get_checkout_type()}_completed", array( $this, 'remove_checkout_data' ), 11 );

		parent::__construct();
		self::$instances[ $checkout_type ] = $this;
	}

	public static function get_instance( $type ) {
		if ( isset( self::$instances[ $type ] ) ) {
			return self::$instances[ $type ];
		}
	}

	public function init_checkout() {

		appthemes_setup_checkout( $this->get_checkout_type(), '' );
		$checkout = appthemes_get_checkout();
		$checkout->set_expiration( 0 );

		// No steps - nothing to do.
		if ( ! $checkout->get_steps_count() ) {
			$checkout->complete_checkout();
			return;
		}

		update_option( $this->get_hash_key(), $checkout->get_hash() );
	}

	/**
	 * Adds necessary data to a new created checkout object.
	 *
	 * @param APP_Dynamic_Checkout $checkout The checkout object.
	 */
	public function add_checkout_data( $checkout ) {}

	/**
	 * Removes data from completed checkout object.
	 */
	public function remove_checkout_data() {
		delete_option( $this->get_hash_key() );
	}

	public function condition() {
		return (bool) $this->get_hash();
	}

	public function display() {

		if ( ! $this->condition() ) {
			return;
		}

		appthemes_setup_checkout( $this->get_checkout_type(), $this->basic_url(), $this->get_hash() );
		$checkout = appthemes_get_checkout();

		ob_start();
		?>
		<ul class="app-progress-list">
			<?php foreach ( $checkout->get_steps() as $step ) { ?>
				<li id="app_progress_step_<?php echo esc_attr( $step['id'] ); ?>" class="app-progress-step">
					<?php $checkout->display_step( $step['id'] ); ?>
				</li>
			<?php } ?>
		</ul>
		<?php
		$content = ob_get_clean();

		echo $this->form_wrap( $content );
	}

	protected function form_wrap( $content ) {
		$nonce  = wp_nonce_field( $this->get_checkout_type(), '_progress_nonce', true, false );
		$button = html( 'button', array( 'type' => 'submit', 'class' => 'button button-primary app-progress-button' ), __( 'Install', APP_TD ) );

		return html( 'form', array(
				'method' => 'post',
				'action' => '',
				'id'     => "app_progress_form_{$this->get_checkout_type()}",
				'class'  => 'app-progress-form',
				'data-current-step' => $this->get_current_step(),
				'data-action'       => $this->get_checkout_type(),
			),
			$content,
			$nonce,
			$button
		);
	}

	/**
	 * Processes steps.
	 */
	public function process() {

		if ( ! $this->condition() ) {
			return;
		}

		appthemes_setup_checkout( $this->get_checkout_type(), $this->basic_url(), $this->get_hash() );

		if ( isset( $_REQUEST['_progress_nonce'] ) ) {
			$_REQUEST[ $this->get_current_step() ] = $_REQUEST['_progress_nonce'];
		}

		appthemes_process_checkout();
	}

	/**
	 * Retrieves the process basic URL.
	 *
	 * @return string
	 */
	protected function basic_url() {
		return scbUtil::get_current_url();
	}

	/**
	 * Retrieves associated checkout type.
	 *
	 * @return string Checkout type.
	 */
	protected function get_checkout_type() {
		return $this->checkout_type;
	}

	/**
	 * Get options meta key of the progress hash field.
	 */
	protected function get_hash_key() {
		return $this->get_checkout_type() . '_hash';
	}

	/**
	 * Get current upgrade hash.
	 */
	protected function get_hash() {
		return get_option( $this->get_hash_key() );
	}

	/**
	 * Retrieves the current step.
	 *
	 * @return string
	 */
	protected function get_current_step() {
		return _appthemes_get_step_from_query();
	}
}
