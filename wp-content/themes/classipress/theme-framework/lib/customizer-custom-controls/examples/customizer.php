<?php
/**
 * Customizer Setup and Custom Controls
 *
 */

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class APP_Customizer_Initialise_Sample_Settings {
	/**
	 * Get our default values.
	 *
	 * @varr array
	 */
	private $defaults;

	/**
	 * Construct
	 */
	public function __construct() {
		// Get our Customizer defaults.
		$this->defaults = appthemes_customizer_sample_generate_defaults();

		// Register our Panels.
		add_action( 'customize_register', array( $this, '_add_customizer_panels' ) );

		// Register our sections.
		add_action( 'customize_register', array( $this, '_add_customizer_sections' ) );

		// Register our social media controls.
		add_action( 'customize_register', array( $this, '_register_social_controls' ) );

		// Register our contact controls.
		add_action( 'customize_register', array( $this, '_register_contact_controls' ) );

		// Register our search controls.
		add_action( 'customize_register', array( $this, '_register_search_controls' ) );

		// Register our WooCommerce controls, only if WooCommerce is active.
		if ( appthemes_customizer_sample_is_woocommerce_active() ) {
			add_action( 'customize_register', array( $this, '_register_woocommerce_controls' ) );
		}

		// Register our sample Custom Control controls.
		add_action( 'customize_register', array( $this, '_register_sample_custom_controls' ) );

		// Register our sample default controls.
		add_action( 'customize_register', array( $this, '_register_sample_default_controls' ) );

		// Enqueue sample scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, '_enqueue_scripts' ) );
		add_action( 'customize_preview_init', array( $this, '_customizer_preview_scripts' ) );
	}

	/**
	 * Enqueue scripts and styles.
	 * Our sample Social Icons are using Font Awesome to display the icons so we need to include the FA CSS
	 *
	 * @return void
	 */
	public function _enqueue_scripts() {
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'app-customizer-example-styles', APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/examples/style.css' );
	}

	/**
	 * Enqueue scripts for our Customizer preview
	 *
	 * @return void
	 */
	public function _customizer_preview_scripts() {
		wp_enqueue_script( 'app-customizer-controls-preview', APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/examples/customizer-preview.js', array( 'customize-preview', 'jquery' ) );
	}

	/**
	 * Register the Customizer panels
	 */
	public function _add_customizer_panels( $wp_customize ) {
		/**
		 * Add our Header & Navigation Panel
		 */
		 $wp_customize->add_panel( 'header_naviation_panel',
		 	array(
				'title' => __( 'Header & Navigation', APP_TD ),
				'description' => esc_html__( 'Adjust your Header and Navigation sections.', APP_TD )
			)
		);
	}

	/**
	 * Register the Customizer sections
	 */
	public function _add_customizer_sections( $wp_customize ) {
		/**
		 * Add our Social Icons Section
		 */
		$wp_customize->add_section( 'social_icons_section',
			array(
				'title' => __( 'Social Icons', APP_TD ),
				'description' => esc_html__( 'Add your social media links and we\'ll automatically match them with the appropriate icons. Drag and drop the URLs to rearrange their order.', APP_TD ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our Contact Section
		 */
		$wp_customize->add_section( 'contact_section',
			array(
				'title' => __( 'Contact', APP_TD ),
				'description' => esc_html__( 'Add your phone number to the site header bar.', APP_TD ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our Search Section
		 */
		$wp_customize->add_section( 'search_section',
			array(
				'title' => __( 'Search', APP_TD ),
				'description' => esc_html__( 'Add a search icon to your primary naigation menu.', APP_TD ),
				'panel' => 'header_naviation_panel'
			)
		);

		/**
		 * Add our WooCommerce Layout Section, only if WooCommerce is active
		 */
		$wp_customize->add_section( 'woocommerce_layout_section',
			array(
				'title' => __( 'WooCommerce Layout', APP_TD ),
				'description' => esc_html__( 'Adjust the layout of your WooCommerce shop.', APP_TD ),
				'active_callback' => 'appthemes_customizer_sample_is_woocommerce_active'
			)
		);

		$wp_customize->add_section( 'sample_custom_controls_section',
			array(
				'title' => __( 'Sample Custom Controls', APP_TD ),
				'description' => esc_html__( 'These are an example of Customizer Custom Controls.', APP_TD  )
			)
		);

		$wp_customize->add_section( 'default_controls_section',
			array(
				'title' => __( 'Default Controls', APP_TD ),
				'description' => esc_html__( 'These are an example of the default Customizer Controls.', APP_TD  )
			)
		);

	}

	/**
	 * Register our social media controls
	 */
	public function _register_social_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'social_newtab',
			array(
				'default' => $this->defaults['social_newtab'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'social_newtab',
			array(
				'label' => __( 'Open in new browser tab', APP_TD ),
				'section' => 'social_icons_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_newtab',
			array(
				'selector' => '.social',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo appthemes_customizer_sample_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Text Radio Button setting and Custom Control for controlling alignment of icons
		$wp_customize->add_setting( 'social_alignment',
			array(
				'default' => $this->defaults['social_alignment'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'appthemes_customizer_radio_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Text_Radio_Button_Control( $wp_customize, 'social_alignment',
			array(
				'label' => __( 'Alignment', APP_TD ),
				'description' => esc_html__( 'Choose the alignment for your social icons', APP_TD ),
				'section' => 'social_icons_section',
				'choices' => array(
					'alignleft' => __( 'Left', APP_TD ),
					'alignright' => __( 'Right', APP_TD  )
				)
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_alignment',
			array(
				'selector' => '.social',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo appthemes_customizer_sample_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'social_urls',
			array(
				'default' => $this->defaults['social_urls'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'appthemes_customizer_url_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Sortable_Repeater_Control( $wp_customize, 'social_urls',
			array(
				'label' => __( 'Social URLs', APP_TD ),
				'description' => esc_html__( 'Add your social media links.', APP_TD ),
				'section' => 'social_icons_section',
				'button_labels' => array(
					'add' => __( 'Add Icon', APP_TD ),
				)
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_urls',
			array(
				'selector' => '.social',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo appthemes_customizer_sample_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

		// Add our Single Accordion setting and Custom Control to list the available Social Media icons
		$socialIconsList = array(
			'Behance' => __( '<i class="fa fa-behance"></i>', APP_TD ),
			'Bitbucket' => __( '<i class="fa fa-bitbucket"></i>', APP_TD ),
			'CodePen' => __( '<i class="fa fa-codepen"></i>', APP_TD ),
			'DeviantArt' => __( '<i class="fa fa-deviantart"></i>', APP_TD ),
			'Dribbble' => __( '<i class="fa fa-dribbble"></i>', APP_TD ),
			'Etsy' => __( '<i class="fa fa-etsy"></i>', APP_TD ),
			'Facebook' => __( '<i class="fa fa-facebook"></i>', APP_TD ),
			'Flickr' => __( '<i class="fa fa-flickr"></i>', APP_TD ),
			'Foursquare' => __( '<i class="fa fa-foursquare"></i>', APP_TD ),
			'GitHub' => __( '<i class="fa fa-github"></i>', APP_TD ),
			'Instagram' => __( '<i class="fa fa-instagram"></i>', APP_TD ),
			'Last.fm' => __( '<i class="fa fa-lastfm"></i>', APP_TD ),
			'LinkedIn' => __( '<i class="fa fa-linkedin"></i>', APP_TD ),
			'Medium' => __( '<i class="fa fa-medium"></i>', APP_TD ),
			'Pinterest' => __( '<i class="fa fa-pinterest"></i>', APP_TD ),
			'Google+' => __( '<i class="fa fa-google-plus"></i>', APP_TD ),
			'Reddit' => __( '<i class="fa fa-reddit"></i>', APP_TD ),
			'Slack' => __( '<i class="fa fa-slack"></i>', APP_TD ),
			'SlideShare' => __( '<i class="fa fa-slideshare"></i>', APP_TD ),
			'Snapchat' => __( '<i class="fa fa-snapchat"></i>', APP_TD ),
			'SoundCloud' => __( '<i class="fa fa-soundcloud"></i>', APP_TD ),
			'Spotify' => __( '<i class="fa fa-spotify"></i>', APP_TD ),
			'Stack Overflow' => __( '<i class="fa fa-stack-overflow"></i>', APP_TD ),
			'Tumblr' => __( '<i class="fa fa-tumblr"></i>', APP_TD ),
			'Twitch' => __( '<i class="fa fa-twitch"></i>', APP_TD ),
			'Twitter' => __( '<i class="fa fa-twitter"></i>', APP_TD ),
			'Vimeo' => __( '<i class="fa fa-vimeo"></i>', APP_TD ),
			'YouTube' => __( '<i class="fa fa-youtube"></i>', APP_TD  ),
		);
		$wp_customize->add_setting( 'social_url_icons',
			array(
				'default' => $this->defaults['social_url_icons'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Single_Accordion_Control( $wp_customize, 'social_url_icons',
			array(
				'label' => __( 'View list of available icons', APP_TD ),
				'description' => $socialIconsList,
				'section' => 'social_icons_section'
			)
		) );

		// Add our Checkbox switch setting and Custom Control for displaying an RSS icon
		$wp_customize->add_setting( 'social_rss',
			array(
				'default' => $this->defaults['social_rss'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'social_rss',
			array(
				'label' => __( 'Display RSS icon', APP_TD ),
				'section' => 'social_icons_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'social_rss',
			array(
				'selector' => '.social',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo appthemes_customizer_sample_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

	}

	/**
	 * Register our Contact controls
	 */
	public function _register_contact_controls( $wp_customize ) {
		// Add our Text field setting and Control for displaying the phone number
		$wp_customize->add_setting( 'contact_phone',
			array(
				'default' => $this->defaults['contact_phone'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			)
		);
		$wp_customize->add_control( 'contact_phone',
			array(
				'label' => __( 'Display phone number', APP_TD ),
				'type' => 'text',
				'section' => 'contact_section'
			)
		);
		$wp_customize->selective_refresh->add_partial( 'contact_phone',
			array(
				'selector' => '.social',
				'container_inclusive' => false,
				'render_callback' => function() {
					echo appthemes_customizer_sample_get_social_media();
				},
				'fallback_refresh' => true
			)
		);

	}

	/**
	 * Register our Search controls
	 */
	public function _register_search_controls( $wp_customize ) {
		// Add our Checkbox switch setting and control for opening URLs in a new tab
		$wp_customize->add_setting( 'search_menu_icon',
			array(
				'default' => $this->defaults['search_menu_icon'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'search_menu_icon',
			array(
				'label' => __( 'Display Search Icon', APP_TD ),
				'section' => 'search_section'
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'search_menu_icon',
			array(
				'selector' => '.menu-item-search',
				'container_inclusive' => false,
				'fallback_refresh' => false
			)
		);
	}

	/**
	 * Register our WooCommerce Layout controls
	 */
	public function _register_woocommerce_controls( $wp_customize ) {

		// Add our Checkbox switch setting and control for displaying a sidebar on the shop page
		$wp_customize->add_setting( 'woocommerce_shop_sidebar',
			array(
				'default' => $this->defaults['woocommerce_shop_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'woocommerce_shop_sidebar',
			array(
				'label' => __( 'Shop page sidebar', APP_TD ),
				'section' => 'woocommerce_layout_section'
			)
		) );

		// Add our Checkbox switch setting and control for displaying a sidebar on the single product page
		$wp_customize->add_setting( 'woocommerce_product_sidebar',
			array(
				'default' => $this->defaults['woocommerce_product_sidebar'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'woocommerce_product_sidebar',
			array(
				'label' => esc_html__( 'Single Product page sidebar', APP_TD ),
				'section' => 'woocommerce_layout_section'
			)
		) );

		// Add our Simple Notice setting and control for displaying a message about the WooCommerce shop sidebars
		$wp_customize->add_setting( 'woocommerce_other_sidebar',
			array(
				'transport' => 'postMessage',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Simple_Notice_Control( $wp_customize, 'woocommerce_other_sidebar',
			array(
				'label' => __( 'Cart, Checkout & My Account sidebars', APP_TD ),
				'description' => esc_html__( 'The Cart, Checkout and My Account pages are displayed using shortcodes. To remove the sidebar from these Pages, simply edit each Page and change the Template (in the Page Attributes Panel) to Full-width Page.', APP_TD ),
				'section' => 'woocommerce_layout_section'
			)
		) );

	}

	/**
	 * Register our sample custom controls
	 */
	public function _register_sample_custom_controls( $wp_customize ) {

		// Test of Toggle Switch Custom Control
		$wp_customize->add_setting( 'sample_toggle_switch',
			array(
				'default' => $this->defaults['sample_toggle_switch'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Toggle_Switch_Control( $wp_customize, 'sample_toggle_switch',
			array(
				'label' => __( 'Toggle switch', APP_TD ),
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Slider Custom Control
		$wp_customize->add_setting( 'sample_slider_control',
			array(
				'default' => $this->defaults['sample_slider_control'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Slider_Control( $wp_customize, 'sample_slider_control',
			array(
				'label' => __( 'Slider Control (px)', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'min' => 10,
					'max' => 90,
					'step' => 1,
				),
			)
		) );

		// Another Test of Slider Custom Control
		$wp_customize->add_setting( 'sample_slider_control_small_step',
			array(
				'default' => $this->defaults['sample_slider_control_small_step'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_range_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Slider_Control( $wp_customize, 'sample_slider_control_small_step',
			array(
				'label' => __( 'Slider Control With a Small Step', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'min' => 0,
					'max' => 4,
					'step' => .5,
				),
			)
		) );

		// Add our Sortable Repeater setting and Custom Control for Social media URLs
		$wp_customize->add_setting( 'sample_sortable_repeater_control',
			array(
				'default' => $this->defaults['sample_sortable_repeater_control'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_url_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Sortable_Repeater_Control( $wp_customize, 'sample_sortable_repeater_control',
			array(
				'label' => __( 'Sortable Repeater', APP_TD ),
				'description' => esc_html__( 'This is the control description.', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'button_labels' => array(
					'add' => __( 'Add Row', APP_TD ),
				)
			)
		) );

		// Test of Image Radio Button Custom Control
		$wp_customize->add_setting( 'sample_image_radio_button',
			array(
				'default' => $this->defaults['sample_image_radio_button'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_radio_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Image_Radio_Button_Control( $wp_customize, 'sample_image_radio_button',
			array(
				'label' => __( 'Image Radio Button Control', APP_TD ),
				'description' => esc_html__( 'Sample custom control description', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'sidebarleft' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-left.png',
						'name' => __( 'Left Sidebar', APP_TD )
					),
					'sidebarnone' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-none.png',
						'name' => __( 'No Sidebar', APP_TD )
					),
					'sidebarright' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-right.png',
						'name' => __( 'Right Sidebar', APP_TD )
					)
				)
			)
		) );

		// Test of Text Radio Button Custom Control
		$wp_customize->add_setting( 'sample_text_radio_button',
			array(
				'default' => $this->defaults['sample_text_radio_button'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_radio_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Text_Radio_Button_Control( $wp_customize, 'sample_text_radio_button',
			array(
				'label' => __( 'Text Radio Button Control', APP_TD ),
				'description' => esc_html__( 'Sample custom control description', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'left' => __( 'Left', APP_TD ),
					'centered' => __( 'Centered', APP_TD ),
					'right' => __( 'Right', APP_TD  )
				)
			)
		) );

		// Test of Image Checkbox Custom Control
		$wp_customize->add_setting( 'sample_image_checkbox',
			array(
				'default' => $this->defaults['sample_image_checkbox'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Image_Checkbox_Control( $wp_customize, 'sample_image_checkbox',
			array(
				'label' => __( 'Image Checkbox Control', APP_TD ),
				'description' => esc_html__( 'Sample custom control description', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'choices' => array(
					'stylebold' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/bold.png',
						'name' => __( 'Bold', APP_TD )
					),
					'styleitalic' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/italic.png',
						'name' => __( 'Italic', APP_TD )
					),
					'styleallcaps' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/allcaps.png',
						'name' => __( 'All Caps', APP_TD )
					),
					'styleunderline' => array(
						'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/underline.png',
						'name' => __( 'Underline', APP_TD )
					)
				)
			)
		) );

		// Test of Single Accordion Control
		$sampleIconsList = array(
			'Behance' => __( '<i class="fa fa-behance"></i>', APP_TD ),
			'Bitbucket' => __( '<i class="fa fa-bitbucket"></i>', APP_TD ),
			'CodePen' => __( '<i class="fa fa-codepen"></i>', APP_TD ),
			'DeviantArt' => __( '<i class="fa fa-deviantart"></i>', APP_TD ),
			'Dribbble' => __( '<i class="fa fa-dribbble"></i>', APP_TD ),
			'Etsy' => __( '<i class="fa fa-etsy"></i>', APP_TD ),
			'Facebook' => __( '<i class="fa fa-facebook"></i>', APP_TD ),
			'Flickr' => __( '<i class="fa fa-flickr"></i>', APP_TD ),
			'Foursquare' => __( '<i class="fa fa-foursquare"></i>', APP_TD ),
			'GitHub' => __( '<i class="fa fa-github"></i>', APP_TD ),
		);
		$wp_customize->add_setting( 'sample_single_accordion',
			array(
				'default' => $this->defaults['sample_single_accordion'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Single_Accordion_Control( $wp_customize, 'sample_single_accordion',
			array(
				'label' => __( 'Single Accordion Control', APP_TD ),
				'description' => $sampleIconsList,
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Alpha Color Picker Control
		$wp_customize->add_setting( 'sample_alpha_color',
			array(
				'default' => $this->defaults['sample_alpha_color'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'appthemes_customizer_hex_rgba_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Customize_Alpha_Color_Control( $wp_customize, 'sample_alpha_color',
			array(
				'label' => __( 'Alpha Color Picker Control', APP_TD ),
				'description' => esc_html__( 'Sample custom control description', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'show_opacity' => true,
				'palette' => array(
					'#000',
					'#fff',
					'#df312c',
					'#df9a23',
					'#eef000',
					'#7ed934',
					'#1571c1',
					'#8309e7'
				)
			)
		) );

		// Test of Simple Notice control
		$wp_customize->add_setting( 'sample_simple_notice',
			array(
				'default' => $this->defaults['sample_simple_notice'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Simple_Notice_Control( $wp_customize, 'sample_simple_notice',
			array(
				'label' => __( 'Simple Notice Control', APP_TD ),
				'description' => __( 'This Custom Control allows you to display a simple title and description to your users. You can even include <a href="https://google.com" target="_blank">basic html</a>.', APP_TD ),
				'section' => 'sample_custom_controls_section'
			)
		) );

		// Test of Dropdown Select2 Control (single select)
		$wp_customize->add_setting( 'sample_dropdown_select2_control_single',
			array(
				'default' => $this->defaults['sample_dropdown_select2_control_single'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Dropdown_Select2_Control( $wp_customize, 'sample_dropdown_select2_control_single',
			array(
				'label' => __( 'Dropdown Select2 Control', APP_TD ),
				'description' => esc_html__( 'Sample Dropdown Select2 custom control (Single Select)', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'placeholder' => __( 'Please select a state...', APP_TD ),
					'multiselect' => false,
				),
				'choices' => array(
					'nsw' => __( 'New South Wales', APP_TD ),
					'vic' => __( 'Victoria', APP_TD ),
					'qld' => __( 'Queensland', APP_TD ),
					'wa' => __( 'Western Australia', APP_TD ),
					'sa' => __( 'South Australia', APP_TD ),
					'tas' => __( 'Tasmania', APP_TD ),
					'act' => __( 'Australian Capital Territory', APP_TD ),
					'nt' => __( 'Northern Territory', APP_TD ),
				)
			)
		) );

		// Test of Dropdown Select2 Control (Multi-Select)
		$wp_customize->add_setting( 'sample_dropdown_select2_control_multi',
			array(
				'default' => $this->defaults['sample_dropdown_select2_control_multi'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Dropdown_Select2_Control( $wp_customize, 'sample_dropdown_select2_control_multi',
			array(
				'label' => __( 'Dropdown Select2 Control', APP_TD ),
				'description' => esc_html__( 'Sample Dropdown Select2 custom control  (Multi-Select)', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'multiselect' => true,
				),
				'choices' => array(
					__( 'Antarctica', APP_TD ) => array(
						'Antarctica/Casey' => __( 'Casey', APP_TD ),
						'Antarctica/Davis' => __( 'Davis', APP_TD ),
						'Antarctica/DumontDurville' => __( 'DumontDUrville', APP_TD ),
						'Antarctica/Macquarie' => __( 'Macquarie', APP_TD ),
						'Antarctica/Mawson' => __( 'Mawson', APP_TD ),
						'Antarctica/McMurdo' => __( 'McMurdo', APP_TD ),
						'Antarctica/Palmer' => __( 'Palmer', APP_TD ),
						'Antarctica/Rothera' => __( 'Rothera', APP_TD ),
						'Antarctica/Syowa' => __( 'Syowa', APP_TD ),
						'Antarctica/Troll' => __( 'Troll', APP_TD ),
						'Antarctica/Vostok' => __( 'Vostok', APP_TD ),
					),
					__( 'Atlantic', APP_TD ) => array(
						'Atlantic/Azores' => __( 'Azores', APP_TD ),
						'Atlantic/Bermuda' => __( 'Bermuda', APP_TD ),
						'Atlantic/Canary' => __( 'Canary', APP_TD ),
						'Atlantic/Cape_Verde' => __( 'Cape Verde', APP_TD ),
						'Atlantic/Faroe' => __( 'Faroe', APP_TD ),
						'Atlantic/Madeira' => __( 'Madeira', APP_TD ),
						'Atlantic/Reykjavik' => __( 'Reykjavik', APP_TD ),
						'Atlantic/South_Georgia' => __( 'South Georgia', APP_TD ),
						'Atlantic/Stanley' => __( 'Stanley', APP_TD ),
						'Atlantic/St_Helena' => __( 'St Helena', APP_TD ),
					),
					__( 'Australia', APP_TD ) => array(
						'Australia/Adelaide' => __( 'Adelaide', APP_TD ),
						'Australia/Brisbane' => __( 'Brisbane', APP_TD ),
						'Australia/Broken_Hill' => __( 'Broken Hill', APP_TD ),
						'Australia/Currie' => __( 'Currie', APP_TD ),
						'Australia/Darwin' => __( 'Darwin', APP_TD ),
						'Australia/Eucla' => __( 'Eucla', APP_TD ),
						'Australia/Hobart' => __( 'Hobart', APP_TD ),
						'Australia/Lindeman' => __( 'Lindeman', APP_TD ),
						'Australia/Lord_Howe' => __( 'Lord Howe', APP_TD ),
						'Australia/Melbourne' => __( 'Melbourne', APP_TD ),
						'Australia/Perth' => __( 'Perth', APP_TD ),
						'Australia/Sydney' => __( 'Sydney', APP_TD ),
					)
				)
			)
		) );

		// Test of Dropdown Posts Control
		$wp_customize->add_setting( 'sample_dropdown_posts_control',
			array(
				'default' => $this->defaults['sample_dropdown_posts_control'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Dropdown_Posts_Control( $wp_customize, 'sample_dropdown_posts_control',
			array(
				'label' => __( 'Dropdown Posts Control', APP_TD ),
				'description' => esc_html__( 'Sample Dropdown Posts custom control description', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'posts_per_page' => -1,
					'orderby' => 'name',
					'order' => 'ASC',
				),
			)
		) );

		// Test of TinyMCE control
		$wp_customize->add_setting( 'sample_tinymce_editor',
			array(
				'default' => $this->defaults['sample_tinymce_editor'],
				'transport' => 'postMessage',
				'sanitize_callback' => 'wp_kses_post'
			)
		);
		$wp_customize->add_control( new APP_Customizer_TinyMCE_Control( $wp_customize, 'sample_tinymce_editor',
			array(
				'label' => __( 'TinyMCE Control', APP_TD ),
				'description' => __( 'This is a TinyMCE Editor Custom Control', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'toolbar1' => 'bold italic bullist numlist alignleft aligncenter alignright link',
					'mediaButtons' => true,
				)
			)
		) );
		$wp_customize->selective_refresh->add_partial( 'sample_tinymce_editor',
			array(
				'selector' => '.footer-credits',
				'container_inclusive' => false,
				'render_callback' => 'appthemes_customizer_sample_get_credits_render_callback',
				'fallback_refresh' => false,
			)
		);

		// Test of Google Font Select Control
		$wp_customize->add_setting( 'sample_google_font_select',
			array(
				'default' => $this->defaults['sample_google_font_select'],
				'sanitize_callback' => 'appthemes_customizer_google_font_sanitization'
			)
		);
		$wp_customize->add_control( new APP_Customizer_Google_Font_Select_Control( $wp_customize, 'sample_google_font_select',
			array(
				'label' => __( 'Google Font Control', APP_TD ),
				'description' => esc_html__( 'All Google Fonts sorted alphabetically', APP_TD ),
				'section' => 'sample_custom_controls_section',
				'input_attrs' => array(
					'font_count' => 'all',
					'orderby' => 'alpha',
				),
			)
		) );
	}

	/**
	 * Register our sample default controls
	 */
	public function _register_sample_default_controls( $wp_customize ) {

		// Test of Text Control
		$wp_customize->add_setting( 'sample_default_text',
			array(
				'default' => $this->defaults['sample_default_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_text',
			array(
				'label' => __( 'Default Text Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'text',
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid rebeccapurple',
					'placeholder' => __( 'Enter name...', APP_TD ),
				),
			)
		);

		// Test of Email Control
		$wp_customize->add_setting( 'sample_email_text',
			array(
				'default' => $this->defaults['sample_email_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_email'
			)
		);
		$wp_customize->add_control( 'sample_email_text',
			array(
				'label' => __( 'Default Email Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'email'
			)
		);

		// Test of URL Control
		$wp_customize->add_setting( 'sample_url_text',
			array(
				'default' => $this->defaults['sample_url_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'esc_url_raw'
			)
		);
		$wp_customize->add_control( 'sample_url_text',
			array(
				'label' => __( 'Default URL Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'url'
			)
		);

		// Test of Number Control
		$wp_customize->add_setting( 'sample_number_text',
			array(
				'default' => $this->defaults['sample_number_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'intval'
			)
		);
		$wp_customize->add_control( 'sample_number_text',
			array(
				'label' => __( 'Default Number Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'number'
			)
		);

		// Test of Hidden Control
		$wp_customize->add_setting( 'sample_hidden_text',
			array(
				'default' => $this->defaults['sample_hidden_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_hidden_text',
			array(
				'label' => __( 'Default Hidden Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'hidden'
			)
		);

		// Test of Date Control
		$wp_customize->add_setting( 'sample_date_text',
			array(
				'default' => $this->defaults['sample_date_text'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_text_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_date_text',
			array(
				'label' => __( 'Default Date Control', APP_TD ),
				'description' => esc_html__( 'Text controls Type can be either text, email, url, number, hidden, or date', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'text'
			)
		);

		 // Test of Standard Checkbox Control
		$wp_customize->add_setting( 'sample_default_checkbox',
			array(
				'default' => $this->defaults['sample_default_checkbox'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( 'sample_default_checkbox',
			array(
				'label' => __( 'Default Checkbox Control', APP_TD ),
				'description' => esc_html__( 'Sample Checkbox description', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'checkbox'
			)
		);

 		// Test of Standard Select Control
		$wp_customize->add_setting( 'sample_default_select',
			array(
				'default' => $this->defaults['sample_default_select'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_radio_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_select',
			array(
				'label' => __( 'Standard Select Control', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'select',
				'choices' => array(
					'wordpress' => __( 'WordPress', APP_TD ),
					'hamsters' => __( 'Hamsters', APP_TD ),
					'jet-fuel' => __( 'Jet Fuel', APP_TD ),
					'nuclear-energy' => __( 'Nuclear Energy', APP_TD )
				)
			)
		);

		// Test of Standard Radio Control
		$wp_customize->add_setting( 'sample_default_radio',
			array(
				'default' => $this->defaults['sample_default_radio'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_radio_sanitization'
			)
		);
		$wp_customize->add_control( 'sample_default_radio',
			array(
				'label' => __( 'Standard Radio Control', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'radio',
				'choices' => array(
					'captain-america' => __( 'Captain America', APP_TD ),
					'iron-man' => __( 'Iron Man', APP_TD ),
					'spider-man' => __( 'Spider-Man', APP_TD ),
					'thor' => __( 'Thor', APP_TD )
				)
			)
		);

		// Test of Dropdown Pages Control
		$wp_customize->add_setting( 'sample_default_dropdownpages',
			array(
				'default' => $this->defaults['sample_default_dropdownpages'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( 'sample_default_dropdownpages',
			array(
				'label' => __( 'Default Dropdown Pages Control', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'dropdown-pages'
			)
		);

		// Test of Textarea Control
		$wp_customize->add_setting( 'sample_default_textarea',
			array(
				'default' => $this->defaults['sample_default_textarea'],
				'transport' => 'refresh',
				'sanitize_callback' => 'wp_filter_nohtml_kses'
			)
		);
		$wp_customize->add_control( 'sample_default_textarea',
			array(
				'label' => __( 'Default Textarea Control', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'textarea',
				'input_attrs' => array(
					'class' => 'my-custom-class',
					'style' => 'border: 1px solid #999',
					'placeholder' => __( 'Enter message...', APP_TD ),
				),
			)
		);

		// Test of Color Control
		$wp_customize->add_setting( 'sample_default_color',
			array(
				'default' => $this->defaults['sample_default_color'],
				'transport' => 'refresh',
				'sanitize_callback' => 'sanitize_hex_color'
			)
		);
		$wp_customize->add_control( 'sample_default_color',
			array(
				'label' => __( 'Default Color Control', APP_TD ),
				'section' => 'default_controls_section',
				'type' => 'color'
			)
		);

		// Test of Media Control
		$wp_customize->add_setting( 'sample_default_media',
			array(
				'default' => $this->defaults['sample_default_media'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'sample_default_media',
			array(
				'label' => __( 'Default Media Control', APP_TD ),
				'description' => esc_html__( 'This is the description for the Media Control', APP_TD ),
				'section' => 'default_controls_section',
				'mime_type' => 'image',
				'button_labels' => array(
					'select' => __( 'Select File', APP_TD ),
					'change' => __( 'Change File', APP_TD ),
					'default' => __( 'Default', APP_TD ),
					'remove' => __( 'Remove', APP_TD ),
					'placeholder' => __( 'No file selected', APP_TD ),
					'frame_title' => __( 'Select File', APP_TD ),
					'frame_button' => __( 'Choose File', APP_TD ),
				)
			)
		) );

		// Test of Image Control
		$wp_customize->add_setting( 'sample_default_image',
			array(
				'default' => $this->defaults['sample_default_image'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'sample_default_image',
			array(
				'label' => __( 'Default Image Control', APP_TD ),
				'description' => esc_html__( 'This is the description for the Image Control', APP_TD ),
				'section' => 'default_controls_section',
				'button_labels' => array(
					'select' => __( 'Select Image', APP_TD ),
					'change' => __( 'Change Image', APP_TD ),
					'remove' => __( 'Remove', APP_TD ),
					'default' => __( 'Default', APP_TD ),
					'placeholder' => __( 'No image selected', APP_TD ),
					'frame_title' => __( 'Select Image', APP_TD ),
					'frame_button' => __( 'Choose Image', APP_TD ),
				)
			)
		) );

		// Test of Cropped Image Control
		$wp_customize->add_setting( 'sample_default_cropped_image',
			array(
				'default' => $this->defaults['sample_default_cropped_image'],
				'transport' => 'refresh',
				'sanitize_callback' => 'absint'
			)
		);
		$wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'sample_default_cropped_image',
			array(
				'label' => __( 'Default Cropped Image Control', APP_TD ),
				'description' => esc_html__( 'This is the description for the Cropped Image Control', APP_TD ),
				'section' => 'default_controls_section',
				'flex_width' => false,
				'flex_height' => false,
				'width' => 800,
				'height' => 400
			)
		) );

		// Test of Date/Time Control
		$wp_customize->add_setting( 'sample_date_only',
			array(
				'default' => $this->defaults['sample_date_only'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_date_time_sanitization',
			)
		);
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_only',
			array(
				'label' => __( 'Default Date Control', APP_TD ),
				'description' => esc_html__( 'This is the Date Time Control but is only displaying a date field. It also has Max and Min years set.', APP_TD ),
				'section' => 'default_controls_section',
				'include_time' => false,
				'allow_past_date' => true,
				'twelve_hour_format' => true,
				'min_year' => '2016',
				'max_year' => '2025',
			)
		) );

		// Test of Date/Time Control
		$wp_customize->add_setting( 'sample_date_time',
			array(
				'default' => $this->defaults['sample_date_time'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_date_time_sanitization',
			)
		);
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_time',
			array(
				'label' => __( 'Default Date Control', APP_TD ),
				'description' => esc_html__( 'This is the Date Time Control. It also has Max and Min years set.', APP_TD ),
				'section' => 'default_controls_section',
				'include_time' => true,
				'allow_past_date' => true,
				'twelve_hour_format' => true,
				'min_year' => '2010',
				'max_year' => '2020',
			)
		) );

		// Test of Date/Time Control
		$wp_customize->add_setting( 'sample_date_time_no_past_date',
			array(
				'default' => $this->defaults['sample_date_time_no_past_date'],
				'transport' => 'refresh',
				'sanitize_callback' => 'appthemes_customizer_date_time_sanitization',
			)
		);
		$wp_customize->add_control( new WP_Customize_Date_Time_Control( $wp_customize, 'sample_date_time_no_past_date',
			array(
				'label' => __( 'Default Date Control', APP_TD ),
				'description' => esc_html__( "This is the Date Time Control but is only displaying a date field. Past dates are not allowed.", APP_TD ),
				'section' => 'default_controls_section',
				'include_time' => false,
				'allow_past_date' => false,
				'twelve_hour_format' => true,
				'min_year' => '2016',
				'max_year' => '2099',
			)
		) );
	}
}

/**
 * Render Callback for displaying the footer credits
 */
function appthemes_customizer_sample_get_credits_render_callback() {
	echo appthemes_customizer_sample_get_credits();
}

/**
 * Initialise our Customizer settings
 */
new APP_Customizer_Initialise_Sample_Settings();
