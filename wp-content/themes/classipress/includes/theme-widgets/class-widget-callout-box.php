<?php
/**
 * Widget for home page callout.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * Callout content widget class
 */
class CP_Widget_Callout_Box extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'        => 'cp_widget_callout_box',
			'name'           => __( 'ClassiPress - Page Callout Box', APP_TD ),
			'defaults'       => array(
				'header'              => '',
				'content'             => '',
				'text_align'          => 'center',
				'text_color'          => '#565656',
				'text_padding'        => '10',
				'image'               => '',
				'opacity'             => 1,
				'background'          => 'pull',
				'background_position' => 'center center',
				'background_color'    => '#ffffff',
			),
			'widget_ops'     => array(
				'description' => __( 'Display a full-width callout', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * Retrieves form fields in scbForm format.
	 *
	 * @return array Form fields array.
	 */
	protected function form_fields() {
		return array(
			array(
				'type' => 'text',
				'name' => 'header',
				'desc' => __( 'Title:', APP_TD ),
			),
			array(
				'type'     => 'textarea',
				'name'     => 'content',
				'extra'    => "style='width: 100%' rows='5'",
				'desc'     => __( 'Content:', APP_TD ),
				'sanitize' => array( $this, 'sanitize' ),
			),
			array(
				'type'	  => 'select',
				'name'	  => 'text_align',
				'choices' => array(
					'left'   => __( 'Left', APP_TD ),
					'center' => __( 'Center', APP_TD ),
					'right'  => __( 'Right', APP_TD ),
				),
				'extra'	  => array( 'class' => 'widefat' ),
				'desc'	  => __( 'Text Align:', APP_TD ),
			),
			array(
				'type'  => 'colorpicker',
				'name'  => 'text_color',
				'desc'  => __( 'Text Color:', APP_TD ),
				'extra' => array(
					'data-default-color' => '#565656',
					'class'              => 'color-picker-hex',
				),
			),
			array(
				'type'  => 'number',
				'name'  => 'text_padding',
				'desc'  => __( 'Padding:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
				),
			),
			array(
				'type'   => 'custom',
				'name'   => 'image',
				'render' => array( $this, '_image_control' ),
				'desc'   => __( 'Background Image:', APP_TD ),
			),
			array(
				'type'	  => 'select',
				'name'	  => 'background',
				'choices' => array(
					'cover' => __( 'Cover', APP_TD ),
					'pull'  => __( 'Pull Out', APP_TD ),
				),
				'extra'	  => array( 'class' => 'widefat' ),
				'desc'	  => __( 'Style:', APP_TD ),
			),
			array(
				'type'	  => 'select',
				'name'	  => 'background_position',
				'choices' => array(
					'left top'      => __( 'Left Top', APP_TD ),
					'left center'   => __( 'Left Center', APP_TD ),
					'left bottom'   => __( 'Left Bottom', APP_TD ),
					'right top'     => __( 'Right Top', APP_TD ),
					'right center'  => __( 'Right Center', APP_TD ),
					'right bottom'  => __( 'Right Bottom', APP_TD ),
					'center top'    => __( 'Center Top', APP_TD ),
					'center center' => __( 'Center Center', APP_TD ),
					'center bottom' => __( 'Center Bottom', APP_TD ),
					'center top'    => __( 'Center Top', APP_TD ),
				),
				'extra'	  => array( 'class' => 'widefat' ),
				'desc'	  => __( 'Position:', APP_TD ),
			),
			array(
				'type'  => 'colorpicker',
				'name'  => 'background_color',
				'desc'  => __( 'Background Color:', APP_TD ),
				'extra' => array(
					'data-default-color' => '#ffffff',
					'class'              => 'color-picker-hex',
				),
			),
			array(
				'type'  => 'number',
				'name'  => 'opacity',
				'desc'  => __( 'Image Opacity:', APP_TD ),
				'extra' => array(
					'class' => 'widefat',
					'min'   => 0,
					'max'   => 1,
					'step'  => 0.05,
				),
			),
		);
	}

	/**
	 * Custom content sanitizer.
	 *
	 * @param string $text Original text.
	 *
	 * @return string Sanitized text.
	 */
	public function sanitize( $text ) {
		if ( ! current_user_can( 'unfiltered_html' ) ) {
			$text = wp_kses_post( $text );
		}

		return $text;
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {
		global $wp_query;

		$instance = array_merge( $this->defaults, (array) $instance );

		if ( 'right' === $instance['text_align'] ) {
			$instance['text_align'] = 'medium-6 pull-right';
		} elseif ( 'center' === $instance['text_align'] ) {
			$instance['text_align'] = 'text-center';
		} else {
			$instance['text_align'] = 'medium-6';
		}

		$instance['has_image'] = '';
		$instance['image_css'] = '';

		if ( $instance['image'] ) {
			$instance['image_css'] = 'background-image:url("' . $instance['image'] . '"); background-size:cover; background-position:' . $instance['background_position'] . '; opacity:' . $instance['opacity'] . ';';
			$instance['has_image'] = 'has-image';
		} ?>

		<style>
			#<?php echo esc_attr( $this->id ) ?> .callout-wrap a { text-decoration: underline; color: <?php echo esc_attr( $instance['text_color'] ); ?>}
			#<?php echo esc_attr( $this->id ) ?> .callout-cover.has-image:before {<?php echo $instance['image_css']; ?>}
		</style>
		<?php

		// Safely set query var.
		$wp_query->set( 'instance', $instance );
		appthemes_get_template_part( 'parts/widget', 'callout-box' );
	}

	/**
	 * Build out the widget options.
	 *
	 * @since 4.2.0
	 *
	 * @see APP_Widget::form()
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		foreach ( $this->form_fields() as $field ) {
			if ( 'colorpicker' === $field['type'] ) {
				wp_enqueue_script( 'wp-color-picker' );
				wp_enqueue_style( 'wp-color-picker' );
				break;
			}
		}
		parent::form( $instance );
	}

	/**
	 *
	 * @param type $value
	 * @param scbCustomField $inst
	 */
	public function _image_control( $value, $inst ) {

		$name = $inst->name;
		$name = array_pop( $name );

		wp_enqueue_media();

		ob_start();
		?>
		<p style="margin-bottom: 0;">
			<label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php echo $inst->desc; ?></label>
		</p>
		<p style="margin-top: 3px;">
			<a href="#" class="button-secondary <?php echo esc_attr( $this->get_field_id( $name ) ); ?>-add"><?php _e( 'Choose Image', APP_TD ); ?></a>
		</p>
		<p>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>"value="<?php echo esc_attr( $value ); ?>" placeholder="http://" />
		</p>
		<script>
			jQuery( function( $ ) {
				var <?php echo esc_js( $name ); ?> = new themeImageWidget.MediaManager( {
					target: '<?php echo esc_attr( $this->get_field_id( $name ) ); ?>'
				} );
			} );
		</script>
		<?php

		return ob_get_clean();
	}
}
