<?php
/**
 * Customizer Custom Controls
 *
 * Forked by AppThemes.
 *
 * @package ThemeFramework
 *
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 * @version: 1.0.6
 */

/**
 * Custom Control Base Class
 */
class APP_Customizer_Custom_Control extends WP_Customize_Control {

	/**
	 * Retrieves resource URL.
	 *
	 * @return string
	 */
	protected function get_resource_url() {
		return APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/';
	}
}

/**
 * Image Check Box Custom Control
 */
class APP_Customizer_Image_Checkbox_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'image_checkbox';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="image_checkbox_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<?php	$chkboxValues = explode( ',', esc_attr( $this->value() ) ); ?>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-multi-image-checkbox" <?php $this->link(); ?> />
			<?php foreach ( $this->choices as $key => $value ) { ?>
				<label class="checkbox-label">
					<input type="checkbox" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( esc_attr( $key ), $chkboxValues ), 1 ); ?> class="multi-image-checkbox"/>
					<img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
				</label>
			<?php	} ?>
		</div>
		<?php
	}
}

/**
 * Text Radio Button Custom Control
 */
class APP_Customizer_Text_Radio_Button_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'text_radio_button';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="text_radio_button_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<div class="radio-buttons">
				<?php foreach ( $this->choices as $key => $value ) { ?>
					<label class="radio-button-label">
						<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
						<span><?php echo esc_attr( $value ); ?></span>
					</label>
				<?php	} ?>
			</div>
		</div>
		<?php
	}
}

/**
 * Image Radio Button Custom Control
 */
class APP_Customizer_Image_Radio_Button_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'image_radio_button';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="image_radio_button_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>

			<?php foreach ( $this->choices as $key => $value ) { ?>
				<label class="radio-button-label">
					<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( esc_attr( $key ), $this->value() ); ?>/>
					<img src="<?php echo esc_attr( $value['image'] ); ?>" alt="<?php echo esc_attr( $value['name'] ); ?>" title="<?php echo esc_attr( $value['name'] ); ?>" />
				</label>
			<?php } ?>
		</div>
		<?php
	}
}

/**
 * Single Accordion Custom Control
 */
class APP_Customizer_Single_Accordion_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'single_accordion';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
		wp_enqueue_style( 'font-awesome' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'class' => array(),
				'target' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'i' => array(
				'class' => array(),
			),
		);
		?>
		<div class="single-accordion-custom-control">
			<div class="single-accordion-toggle"><?php echo esc_html( $this->label ); ?><span class="accordion-icon-toggle dashicons dashicons-plus"></span></div>
			<div class="single-accordion customize-control-description">
				<?php
				if ( is_array( $this->description ) ) {
					echo '<ul class="single-accordion-description">';
					foreach ( $this->description as $key => $value ) {
						echo '<li>' . $key . wp_kses( $value, $allowed_html ) . '</li>';
					}
					echo '</ul>';
				} else {
					echo wp_kses( $this->description, $allowed_html );
				}
				?>
			</div>
		</div>
		<?php
	}
}

/**
 * Simple Notice Custom Control
 */
class APP_Customizer_Simple_Notice_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'simple_notice';
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'title' => array(),
				'class' => array(),
				'target' => array(),
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
			'i' => array(
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'code' => array(),
		);
		?>
		<div class="simple-notice-custom-control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo wp_kses( $this->description, $allowed_html ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}

/**
 * Slider Custom Control
 */
class APP_Customizer_Slider_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'slider_control';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'jquery', 'jquery-ui-core' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="slider-custom-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
			<div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"></div><span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
		</div>
		<?php
	}
}

/**
 * Toggle Switch Custom Control
 */
class APP_Customizer_Toggle_Switch_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'toggle_switch';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="toggle-switch-control">
			<div class="toggle-switch">
				<input type="checkbox" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" class="toggle-switch-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?>>
				<label class="toggle-switch-label" for="<?php echo esc_attr( $this->id ); ?>">
					<span class="toggle-switch-inner"></span>
					<span class="toggle-switch-switch"></span>
				</label>
			</div>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
		</div>
		<?php
	}
}

/**
 * Sortable Repeater Custom Control
 */
class APP_Customizer_Sortable_Repeater_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'sortable_repeater';
	/**
	 * Button labels
	 *
	 * @var array
	 */
	public $button_labels = array();
	/**
	 * The Input arguments
	 *
	 * @var array
	 */
	public $input_args = array();

	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		// Merge the passed button labels with our default labels.
		$this->button_labels = wp_parse_args( $this->button_labels,
			array(
				'add' => __( 'Add', APP_TD ),
			)
		);
		$this->input_args = wp_parse_args( $this->input_args,
			array(
				'type'        => 'text',
				'class'       => 'repeater-input',
				'placeholder' => 'https://',
				'value'       => '',
			)
		);
	}
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'jquery', 'jquery-ui-core' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
	  <div class="sortable_repeater_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo $this->description; ?></span>
			<?php } ?>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-sortable-repeater" <?php $this->link(); ?> />
			<div class="sortable">
				<div class="repeater">
					<?php echo html( 'input', $this->input_args ); ?>
					<span class="dashicons dashicons-sort"></span><a class="customize-control-sortable-repeater-delete" href="#"><span class="dashicons dashicons-no-alt"></span></a>
				</div>
			</div>
			<button class="button customize-control-sortable-repeater-add" type="button"><?php echo $this->button_labels['add']; ?></button>
		</div>
		<?php
	}
}

/**
 * Dropdown Select2 Custom Control
 */
class APP_Customizer_Dropdown_Select2_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'dropdown_select2';
	/**
	 * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
	 *
	 * @var bool
	 */
	private $multiselect = false;
	/**
	 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
	 *
	 * @var string
	 */
	private $placeholder = '';
	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		// Check if this is a multi-select field.
		if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
			$this->multiselect = true;
		}
		// Check if a placeholder string has been specified.
		if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
			$this->placeholder = $this->input_attrs['placeholder'];
		} else {
			$this->placeholder = __( 'Please select...', APP_TD );
		}
	}
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), '4.0.6', true );
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'app-customizer-select2-js' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.1', 'all' );
		wp_enqueue_style( 'app-customizer-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), '4.0.6', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$defaultValue = $this->value();
		if ( $this->multiselect ) {
			$defaultValue = explode( ',', $this->value() );
		}
		?>
		<div class="dropdown_select2_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</label>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
			<select name="select2-list-<?php echo ( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2" data-placeholder="<?php echo $this->placeholder; ?>" <?php echo ( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
				<?php
				if ( ! $this->multiselect ) {
					// When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this).
					echo '<option></option>';
				}
				foreach ( $this->choices as $key => $value ) {
					if ( is_array( $value ) ) {
						echo '<optgroup label="' . esc_attr( $key ) . '">';
						foreach ( $value as $optgroupkey => $optgroupvalue ) {
							echo '<option value="' . esc_attr( $optgroupkey ) . '" ' . ( in_array( esc_attr( $optgroupkey ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $optgroupvalue ) . '</option>';
						}
						echo '</optgroup>';
					} else {
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false )  . '>' . esc_attr( $value ) . '</option>';
					}
				}
				?>
			</select>
		</div>
		<?php
	}
}

/**
 * Dropdown Posts Custom Control
 */
class APP_Customizer_Dropdown_Posts_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'dropdown_posts';

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="dropdown_posts_control">
			<?php if ( ! empty( $this->label ) ) { ?>
				<label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
				</label>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<select name="<?php echo $this->id; ?>" id="<?php echo $this->id; ?>" <?php $this->link(); ?>>
				<?php
				// Get our Posts.
				$posts = get_posts( $this->input_attrs );
				if ( ! empty( $posts ) ) {
					foreach ( $posts as $post ) {
						printf( '<option value="%s" %s>%s</option>',
							$post->ID,
							selected( $this->value(), $post->ID, false ),
							$post->post_title
						);
					}
				}
				?>
			</select>
		</div>
		<?php
	}
}

/**
 * TinyMCE Custom Control
 */
class APP_Customizer_TinyMCE_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'tinymce_editor';
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.0', 'all' );
		wp_enqueue_editor();
	}
	/**
	 * Pass our TinyMCE toolbar string to JavaScript
	 */
	public function to_json() {
		parent::to_json();
		$this->json['appthemestinymcetoolbar1'] = isset( $this->input_attrs['toolbar1'] ) ? esc_attr( $this->input_attrs['toolbar1'] ) : 'bold italic bullist numlist alignleft aligncenter alignright link';
		$this->json['appthemestinymcetoolbar2'] = isset( $this->input_attrs['toolbar2'] ) ? esc_attr( $this->input_attrs['toolbar2'] ) : '';
		$this->json['appthemesmediabuttons'] = isset( $this->input_attrs['mediaButtons'] ) && ( $this->input_attrs['mediaButtons'] === true ) ? true : false;
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="tinymce-control">
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) { ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
			<textarea id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-tinymce-editor" <?php $this->link(); ?>><?php echo esc_attr( $this->value() ); ?></textarea>
		</div>
		<?php
	}
}

/**
 * Google Font Select Custom Control
 */
class APP_Customizer_Google_Font_Select_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'google_fonts';
	/**
	 * The list of Google Fonts
	 *
	 * @var array
	 */
	private $fontList = array();
	/**
	 * The saved font values decoded from json
	 *
	 * @var array
	 */
	private $fontValues = [];
	/**
	 * The index of the saved font within the list of Google fonts
	 *
	 * @var int
	 */
	private $fontListIndex = 0;
	/**
	 * The number of fonts to display from the json file. Either positive integer or 'all'. Default = 'all'
	 *
	 * @var string
	 */
	private $fontCount = 'all';
	/**
	 * The font list sort order. Either 'alpha' or 'popular'. Default = 'alpha'
	 *
	 * @var string
	 */
	private $fontOrderBy = 'alpha';

	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Optional. Arguments to override class property defaults.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		// Get the font sort order.
		if ( isset( $this->input_attrs['orderby'] ) && strtolower( $this->input_attrs['orderby'] ) === 'popular' ) {
			$this->fontOrderBy = 'popular';
		}
		// Get the list of Google fonts.
		if ( isset( $this->input_attrs['font_count'] ) ) {
			if ( 'all' != strtolower( $this->input_attrs['font_count'] ) ) {
				$this->fontCount = ( abs( (int) $this->input_attrs['font_count'] ) > 0 ? abs( (int) $this->input_attrs['font_count'] ) : 'all' );
			}
		}
		// Get our list of fonts from the json file.
		$this->fontList = $this->get_google_fonts( 'all' );
		// Decode the default json font value.
		$this->fontValues = json_decode( $this->value() );
		// Find the index of our default font within our list of Google fonts.
		$this->fontListIndex = $this->get_font_index( $this->fontList, $this->fontValues->font );
	}
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-select2-js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', array( 'jquery' ), '4.0.6', true );
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'app-customizer-select2-js' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array(), '1.1', 'all' );
		wp_enqueue_style( 'app-customizer-select2-css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', array(), '4.0.6', 'all' );
	}
	/**
	 * Export our List of Google Fonts to JavaScript
	 */
	public function to_json() {
		parent::to_json();
		$this->json['appthemesfontslist'] = $this->fontList;
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$fontCounter = 0;
		$isFontInList = false;
		$fontListStr = '';

		if ( ! empty($this->fontList) ) {
			?>
			<div class="google_fonts_select_control">
				<?php if ( ! empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if ( ! empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php } ?>
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-google-font-selection" <?php $this->link(); ?> />
				<div class="google-fonts">
					<select class="google-fonts-list" control-name="<?php echo esc_attr( $this->id ); ?>">
						<?php
						foreach( $this->fontList as $key => $value ) {
							$fontCounter++;
							$fontListStr .= '<option value="' . $value->family . '" ' . selected( $this->fontValues->font, $value->family, false ) . '>' . $value->family . '</option>';
							if ( $this->fontValues->font === $value->family ) {
								$isFontInList = true;
							}
							if ( is_int( $this->fontCount ) && $fontCounter === $this->fontCount ) {
								break;
							}
						}
						if ( !$isFontInList && $this->fontListIndex ) {
							// If the default or saved font value isn't in the list of displayed fonts, add it to the top of the list as the default font.
							$fontListStr = '<option value="' . $this->fontList[$this->fontListIndex]->family . '" ' . selected( $this->fontValues->font, $this->fontList[$this->fontListIndex]->family, false ) . '>' . $this->fontList[$this->fontListIndex]->family . ' (default)</option>' . $fontListStr;
						}
						// Display our list of font options.
						echo $fontListStr;
						?>
					</select>
				</div>
				<div class="customize-control-description"><?php esc_html_e( 'Select weight & style for regular text', APP_TD ); ?></div>
				<div class="weight-style">
					<select class="google-fonts-regularweight-style">
						<?php
						foreach( $this->fontList[$this->fontListIndex]->variants as $key => $value ) {
							echo '<option value="' . $value . '" ' . selected( $this->fontValues->regularweight, $value, false ) . '>' . $value . '</option>';
						}
						?>
					</select>
				</div>
				<div class="customize-control-description"><?php esc_html_e( 'Select weight for italic text', APP_TD ); ?></div>
				<div class="weight-style">
					<select class="google-fonts-italicweight-style" <?php disabled( in_array( 'italic', $this->fontList[ $this->fontListIndex ]->variants ), false ); ?> data-not-available="<?php esc_attr_e( 'Not Available for this font', APP_TD ) ?>">
						<?php
						$optionCount = 0;
						foreach( $this->fontList[ $this->fontListIndex ]->variants as $key => $value ) {
							// Only add options that are italic.
							if ( strpos( $value, 'italic' ) !== false ) {
								echo '<option value="' . $value . '" ' . selected( $this->fontValues->italicweight, $value, false ) . '>' . $value . '</option>';
								$optionCount++;
							}
						}
						if ( $optionCount == 0 ) {
							echo '<option value="">' . esc_html__( 'Not Available for this font', APP_TD ) . '</option>';
						}
						?>
					</select>
				</div>
				<div class="customize-control-description"><?php esc_html_e( 'Select weight for bold text', APP_TD ); ?></div>
				<div class="weight-style">
					<select class="google-fonts-boldweight-style" data-not-available="<?php esc_attr_e( 'Not Available for this font', APP_TD ) ?>">
						<?php
						$optionCount = 0;
						foreach ( $this->fontList[ $this->fontListIndex ]->variants as $key => $value ) {
							// Only add options that aren't italic.
							if ( strpos( $value, 'italic' ) === false ) {
								echo '<option value="' . $value . '" ' . selected( $this->fontValues->boldweight, $value, false ) . '>' . $value . '</option>';
								$optionCount++;
							}
						}
						// This should never evaluate as there'll always be at least a 'regular' weight.
						if ( ! $optionCount ) {
							echo '<option value="">' . esc_html__( 'Not Available for this font', APP_TD ) . '</option>';
						}
						?>
					</select>
				</div>
				<input type="hidden" class="google-fonts-category" value="<?php echo $this->fontValues->category; ?>">
			</div>
			<?php
		}
	}

	/**
	 * Find the index of the saved font in our multidimensional array of Google Fonts
	 */
	public function get_font_index( $haystack, $needle ) {
		foreach( $haystack as $key => $value ) {
			if ( $value->family == $needle ) {
				return $key;
			}
		}
		return false;
	}

	/**
	 * Return the list of Google Fonts from our json file. Unless otherwise specfied, list will be limited to 30 fonts.
	 */
	public function get_google_fonts( $count = 30 ) {
		// Google Fonts json generated from https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=YOUR-API-KEY
		$fontFile = $this->get_resource_url() . 'inc/google-fonts-alphabetical.json';
		if ( $this->fontOrderBy === 'popular' ) {
			$fontFile = $this->get_resource_url() . 'inc/google-fonts-popularity.json';
		}

		$request = wp_remote_get( $fontFile );
		if ( is_wp_error( $request ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $request );
		$content = json_decode( $body );

		if ( $count == 'all' ) {
			return $content->items;
		} else {
			return array_slice( $content->items, 0, $count );
		}
	}
}

/**
 * Alpha Color Picker Custom Control
 *
 * Forked by AppThemes
 *
 * @author Braad Martin <http://braadmartin.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @link https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker
 */
class APP_Customizer_Customize_Alpha_Color_Control extends APP_Customizer_Custom_Control {
	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'alpha-color';
	/**
	 * Add support for palettes to be passed in.
	 *
	 * Supported palette values are true, false, or an array of RGBa and Hex colors.
	 */
	public $palette;
	/**
	 * Add support for showing the opacity value on the slider handle.
	 */
	public $show_opacity;
	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'app-customizer-custom-controls-js', $this->get_resource_url() . 'js/customizer.js', array( 'jquery', 'wp-color-picker' ), '1.0', true );
		wp_enqueue_style( 'app-customizer-custom-controls-css', $this->get_resource_url() . 'css/customizer.css', array( 'wp-color-picker' ), '1.0', 'all' );
	}
	/**
	 * Render the control in the customizer
	 */
	public function render_content() {

		// Process the palette
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean. Default to true.
		$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

		?>
			<label>
				<?php // Output the label and description if they were passed in.
				if ( isset( $this->label ) && '' !== $this->label ) {
					echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
				}
				if ( isset( $this->description ) && '' !== $this->description ) {
					echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
				} ?>
			</label>
			<input class="alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
		<?php
	}
}
