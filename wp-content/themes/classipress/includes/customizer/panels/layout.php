<?php
/**
 * Customizer layout panel declaration.
 *
 * @package ClassiPress
 *
 * @since 4.0.0
 */

// Add the layout panel.
$wp_customize->add_panel( 'cp_custom_panel', array(
	'title'       => __( 'Layout', APP_TD ),
	'description' => __( 'General content layout settings.', APP_TD ),
	'priority'    => 99,
) );
