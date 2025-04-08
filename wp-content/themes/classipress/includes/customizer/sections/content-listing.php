<?php
/**
 * Customizer listing section content.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

$wp_customize->add_section(
	'cp_content_listing_section',
	array(
		'title'       => __( 'Listing Page', APP_TD ),
		'description' => __( 'Listing section configuration.', APP_TD ) . '<br /><br />' .
						'<span class="customize-control-title">' . __( 'Page Layout', APP_TD ) . '</span>' .
						__( 'Almost the entire ad page is built on widgets.', APP_TD ) . '<br /><br />' .
						sprintf( __( 'Visit the <a href="%s">Widgets panel</a> for further configuration.', APP_TD ), "javascript:wp.customize.panel( 'widgets' ).focus();" ),
		'panel'       => 'cp_custom_panel',
		'priority'    => 10,
	)
);

// =============================================================================
// Banner Image
// =============================================================================
$wp_customize->add_setting(
	'show_listing_banner_image',
	array(
		'default'           => 1,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Toggle_Switch_Control(
		$wp_customize,
		'show_listing_banner_image',
		array(
			'label'       => __( 'Show Banner Image', APP_TD ),
			'description' => __( 'If the banner disabled, the title, price, category and other info will appear on the Single Listing Description widget.', APP_TD ),
			'section'     => 'cp_content_listing_section',
		)
	)
);

$wp_customize->add_setting(
	'listing_banner_full_width',
	array(
		'default'           => 1,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Toggle_Switch_Control(
		$wp_customize,
		'listing_banner_full_width',
		array(
			'label'       => __( 'Full Width Banner', APP_TD ),
			'description' => __( 'Display banner on the full page width or the content width. Works if the banner image enabled.', APP_TD ),
			'section'     => 'cp_content_listing_section',
		)
	)
);

// =============================================================================
// Listing Thumbnail
// =============================================================================

$wp_customize->add_setting(
	'listing_thumbnail_placeholder_notice',
	array(
		'transport'         => 'postMessage',
		'sanitize_callback' => 'appthemes_customizer_text_sanitization',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Simple_Notice_Control(
		$wp_customize,
		'listing_thumbnail_placeholder_notice',
		array(
			'label'       => __( 'Listing Thumbnail Placeholder', APP_TD ),
			'description' => __( 'If a listing has no images it will use one of the following options for the thumbnail.', APP_TD ),
			'section'     => 'cp_content_listing_section',
		)
	)
);

$wp_customize->add_setting(
	'category_thumbnail_placeholder',
	array(
		'default'           => 0,
		'transport'         => 'refresh',
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Toggle_Switch_Control(
		$wp_customize,
		'category_thumbnail_placeholder',
		array(
			'label'       => __( 'Category Image Thumbnail', APP_TD ),
			'description' => __( 'Use Category Image as the placeholder for listing thumbnails', APP_TD ),
			'section'     => 'cp_content_listing_section',
		)
	)
);

$wp_customize->add_setting(
	'listing_thumbnail_image_placeholder',
	array(
		'transport'         => 'refresh',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize,
		'listing_thumbnail_image_placeholder',
		array(
			'label'         => __( 'Default Thumbnail Image', APP_TD ),
			'section'       => 'cp_content_listing_section',
			'button_labels' => array(
				'select'       => __( 'Select Image', APP_TD ),
				'change'       => __( 'Change Image', APP_TD ),
				'remove'       => __( 'Remove', APP_TD ),
				'default'      => __( 'Default', APP_TD ),
				'placeholder'  => __( 'No image selected', APP_TD ),
				'frame_title'  => __( 'Select Image', APP_TD ),
				'frame_button' => __( 'Choose Image', APP_TD ),
			),
		)
	)
);

// =============================================================================
// Sidebar Position
// =============================================================================

$wp_customize->add_setting(
	'listing_sidebar_position',
	array(
		'default'           => 'right',
		'transport'         => 'refresh',
		'sanitize_callback' => 'appthemes_customizer_radio_sanitization',
	)
);

$wp_customize->add_control(
	new APP_Customizer_Image_Radio_Button_Control(
		$wp_customize,
		'listing_sidebar_position',
		array(
			'label'   => __( 'Sidebar Position', APP_TD ),
			'section' => 'cp_content_listing_section',
			'type'    => 'select',
			'choices' => array(
				'left'  => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-left.png',
					'name'  => __( 'Left', APP_TD ),
				),
				'none'  => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-none.png',
					'name'  => __( 'None', APP_TD ),
				),
				'right' => array(
					'image' => APP_THEME_FRAMEWORK_URI . '/lib/customizer-custom-controls/images/sidebar-right.png',
					'name'  => __( 'Right', APP_TD ),
				),
			),
		)
	)
);
