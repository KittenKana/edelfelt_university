<?php
/**
 * Taxonomy metabox implementation.
 *
 * @package Framework\Metaboxes
 */
class CP_Ad_Listing_Category_Meta_Box extends APP_Taxonomy_Meta_Box {

	/**
	 * Sets up metabox.
	 */
	public function __construct() {
		parent::__construct( 'cp-ad_cat-form', __( 'Custom Fields', APP_TD ), APP_TAX_CAT );
	}

	/**
	 * Enqueues admin scripts.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		appthemes_enqueue_media_manager( array(
			'post_id' => $this->get_term_id(),
		) );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Display some extra HTML before the form.
	 *
	 * @param object $term
	 *
	 * @return void
	 */
	public function before_form( $term ) {
		?>
		<script>
		jQuery( function( $ ) {
			$( document ).find( 'input[type="colorpicker"]' )
				.wpColorPicker( {
					change: function( e, ui ) {
						$( e.target ).val( ui.color.toString() );
						$( e.target ).trigger( 'change' );
					}
				} )
				.closest( '.wp-picker-container' )
				.css( 'display', 'block' );
		} );
		</script>
			<?php
	}

	/**
	 * Saves media data.
	 *
	 * @param int $term_id Given term ID.
	 *
	 * @return void
	 */
	protected function save( $term_id ) {
		parent::save( $term_id );

		APP_Media_Manager::handle_media_upload( $term_id, 'term', array(), true );
	}

	/**
	 * Returns an array of form fields.
	 *
	 * @return array
	 */
	public function form() {

		return array(
			/*array(
				'type'  => 'text',
				'name'  => 'cp_icon_class',
				'title' => __( 'Icon class', APP_TD ),
				'tip'   => __( 'The icons associated with taxonomy term can be used later in front-end.', APP_TD ),
				'desc'  => sprintf( __( 'The Font Awesome Icon class name (i.e. "%1$s", find more on %2$s', APP_TD ), '<a target="_blank" href="https://fontawesome.com/v4.7.0/icons/car">fa-car</a>', '<a target="_blank" href="https://fontawesome.com/v4.7.0/icons">https://fontawesome.com/v4.7.0/icons</a>' ),
			),*/
			array(
				'type'       => 'custom',
				'render'     => array( __CLASS__, '_render_media_manager' ),
				'name'       => 'thumbnail_id',
				'title'      => __( 'Category Image', APP_TD ),
				'tip'        => __( 'The images associated with taxonomy term can be used later as backgrounds.', APP_TD ),
				'listing_id' => $this->get_term_id(),
				'props'      => array(
					'required'    => 0,
					'extensions'  => 'Image',
					'file_limit'  => 1,
					'embed_limit' => 0,
					'file_size'   => '',
				),
			),
			array(
				'type'       => 'custom',
				'render'     => array( __CLASS__, '_render_media_manager' ),
				'name'       => 'category_icon_id',
				'title'      => __( 'Category Icon', APP_TD ),
				'desc'       => __( 'Category Icon should be square and at least 512 × 512 pixels.', APP_TD ),
				'tip'        => __( 'The images associated with taxonomy term can be used later in front-end.', APP_TD ),
				'listing_id' => $this->get_term_id(),
				'props'      => array(
					'required'    => 0,
					'extensions'  => 'image/png',
					'file_limit'  => 1,
					'embed_limit' => 0,
					'file_size'   => '',
				),
			),
			array(
				'title' => __( 'Category Background Color', APP_TD ),
				'type'  => 'colorpicker',
				'name'  => 'category_color',
				'desc'  => '',
				'extra' => array(
					'data-default-color' => '#565656',
					'class'              => 'color-picker-hex',
				),
			),
		);
	}

	/**
	 * Retrieves field html
	 *
	 * Copied from Listing module APP_Media_Field_Type::_render().
	 *
	 * @param mixed          $value Field value.
	 * @param scbCustomField $inst  Field object.
	 *
	 * @return string Generated html
	 */
	public static function _render_media_manager( $value, $inst ) {
		$class = ( isset( $inst->extra ) && isset( $inst->extra['class'] ) ) ? $inst->extra['class'] : '';

		$file_limit  = ( isset( $inst->props['file_limit'] ) ) ? $inst->props['file_limit'] : 1 ;
		$embed_limit = ( isset( $inst->props['embed_limit'] ) ) ? $inst->props['embed_limit'] : 0 ;
		$file_size   = ( isset( $inst->props['file_size'] ) ) ? intval( $inst->props['file_size'] ) * 1024 : wp_max_upload_size();

		ob_start();

		echo '<div class="'. esc_attr( $class ) .'">';

		$atts = array(
			'id'          => scbForms::get_name( $inst->name ),
			'object'      => 'term',
			'upload_text' => __( 'Select Image', APP_TD ),
		);

		if ( $inst->props['required'] ) {
			$atts['no_media_text'] = __( 'No media added yet', APP_TD ) . ' ' . html( 'input type="hidden" name="' . $inst->name . '_required" class="required_media"', '' );
		}

		appthemes_media_manager(
			$inst->listing_id,
			$atts,
			array(
				'mime_types'  => $inst->props['extensions'],
				'file_limit'  => (int) $file_limit,
				'embed_limit' => (int) $embed_limit,
				'file_size'   => (int) $file_size,
			)
		);

		echo '</div>';

		return str_replace( scbForms::TOKEN, ob_get_clean(), $inst->wrap );
	}

}
