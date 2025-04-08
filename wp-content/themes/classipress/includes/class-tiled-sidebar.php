<?php
/**
 * Dynamic Tiled Sidebar.
 *
 * To convert any sidebar into a tiled one need to add parameter `tile_size` to
 * the register_sidebar() function on registration.
 * The value of the parameter sets the default widget column size (1-12).
 *
 * Widgets on the tiled sidebar have the Size option on their form.
 *
 * It uses a template part 'parts/tiles' which can be extended with a sidebar
 * name. For example 'parts/tiles-sidebar_footer'.
 *
 * @package ClassiPress\Sidebars
 * @author AppThemes
 * @since 4.2.0
 */

/**
 * ClassiPress Dynamic Tiled Sidebar class.
 *
 * @since 4.2.0
 */
class CP_Tiled_Sidebar {

	/**
	 * An array of tile widths which will be used to set column width for each
	 * widget.
	 *
	 * @var array
	 */
	protected $tiles = array();

	/**
	 * The Constructor.
	 */
	public function __construct() {

		if ( ! is_admin() ) {
			// Start buffering.
			add_action( 'dynamic_sidebar_before', array( $this, 'sidebar_before' ) );
			// End buffering.
			add_action( 'dynamic_sidebar_after', array( $this, 'sidebar_after' ) );
			// Wrap widget in a column and registers the width.
			add_filter( 'dynamic_sidebar_params', array( $this, 'sidebar_params' ), 10 );
		}

		// Add special control on each widget form in tiled sidebar.
		add_action( 'in_widget_form', array( $this, 'widget_form' ), 10, 3 );
		// Update control setting.
		add_filter( 'widget_update_callback', array( $this, 'widget_form_update' ), 5, 3 );

	}

	/**
	 * Start buffering the a sidebar content.
	 *
	 * @global array $wp_registered_sidebars
	 *
	 * @param string $id The sidebar ID.
	 */
	public function sidebar_before( $id ) {
		global $wp_registered_sidebars;

		if ( empty( $wp_registered_sidebars[ $id ]['tile_size'] ) ) {
			return;
		}

		ob_start();
	}

	/**
	 * Renders the sidebar.
	 *
	 * @param string $id The sidebar ID.
	 */
	public function sidebar_after( $id ) {
		global $wp_query, $wp_registered_sidebars;

		if ( empty( $wp_registered_sidebars[ $id ]['tile_size'] ) ) {
			return;
		}

		$output = ob_get_clean();

		$sizes = ( ! empty( $this->tiles[ $id ] ) ) ? $this->tiles[ $id ] : array();

		preg_match_all( '/<!--BEG_TILE-->(.*?)<!--END_TILE-->/s', $output, $matches );

		$tiles_html = array();

		foreach ( $sizes as $wid => $size ) {
			$tile_html = '';
			foreach ( $matches[1] as $match ) {
				if ( strpos( $match, $wid ) !== false ) {
					$tile_html = $match;
				}
			}
			$tiles_html[] = $tile_html;
		}

		// If something goes wrong.
		if ( empty( $tiles_html ) ) {
			echo $output;
			return;
		}

		$tiles      = array();
		$rows       = array( array() );
		$_rows      = array();
		$row_index  = 0;
		foreach ( $tiles_html as $tile_html ) {

			$size = (int) current( $sizes );

			if ( array_sum( $rows[ $row_index ] ) >= 12 ) {
				$row_index++;
			}
			$rows[ $row_index ][] = $size;

			// Register non empty row.
			if ( ! empty( $tile_html ) ) {
				$_rows[ $row_index ] = true;
			}

			$tiles[ $row_index ][] = array(
				'content' => $tile_html,
				'size'    => $size,
			);

			if ( ! next( $sizes ) ) {
				reset( $sizes );
			}
		}

		// Unset empty rows.
		$tiles = array_intersect_key( $tiles, $_rows );

		// Set query var so we can pass into template.
		$wp_query->set( 'app_sidebar_tiles', $tiles );

		appthemes_get_template_part( 'parts/tiles', $id );
	}

	/**
	 * Wraps widget in a column and registers the width.
	 *
	 * @param array $params Sidebar parameters.
	 * @return array
	 */
	public function sidebar_params( $params ) {
		global $wp_registered_widgets;

		if ( empty( $params[0]['tile_size'] ) ) {
			return $params;
		}

		$widget_id  = $params[0]['widget_id'];
		$widget_obj = $wp_registered_widgets[ $widget_id ];
		$_callback  = $widget_obj['callback'];
		$widget_opt = get_option( $_callback[0]->option_name );
		$widget_num = $widget_obj['params'][0]['number'];

		$params[0]['before_widget'] = '<!--BEG_TILE-->' . $params[0]['before_widget'];
		$params[0]['after_widget'] .= '<!--END_TILE-->';

		$tile_size = empty( $widget_opt[ $widget_num ]['tile_size'] ) ? $params[0]['tile_size'] : $widget_opt[ $widget_num ]['tile_size'];

		$this->tiles[ $params[0]['id'] ][ $widget_id ] = $tile_size;

		return $params;
	}

	/**
	 * Adds custom fields on the widget form.
	 *
	 * @global array $wp_registered_sidebars
	 *
	 * @param WP_Widget $widget   The widget instance (passed by reference).
	 * @param null      $return   Return null if new fields are added.
	 * @param array     $instance An array of the widget's settings.
	 */
	public function widget_form( $widget, $return, $instance ) {
		global $wp_registered_sidebars;

		$sidebars   = wp_get_sidebars_widgets();
		$sidebar_id = false;
		foreach ( $sidebars as $id => $sidebar ) {
			if ( false !== array_search( $widget->id, $sidebar, true ) ) {
				$sidebar_id = $id;
				break;
			}
		}

		if ( empty( $wp_registered_sidebars[ $sidebar_id ]['tile_size'] ) ) {
			return;
		}

		// Deafult tile size for the current sidebar.
		$tile_size = (int) $wp_registered_sidebars[ $sidebar_id ]['tile_size'];
		$instance  = wp_parse_args( (array) $instance, array( 'tile_size' => $tile_size ) );

		// Display form.
		$input = scbForms::input_with_value(
			array(
				'type'    => 'select',
				'desc'    => __( 'The width is measured in columns, in total, the container can contain up to 12 columns. Expanded widget takes all container width. ', APP_TD ),
				'wrap'    => __( 'The widget width:', APP_TD ) . scbForms::TOKEN,
				'name'    => $widget->get_field_name( 'tile_size' ),
				'choices' => array(
					1  => 1,
					2  => 2,
					3  => 3,
					4  => 4,
					5  => 5,
					6  => 6,
					7  => 7,
					8  => 8,
					9  => 9,
					10 => 10,
					11 => 11,
					12 => 12,
					13 => __( 'Expanded', APP_TD ),
				),
				'extra'   => array(
					'id' => $widget->get_field_id( 'tile_size' ),
				),
			),
			$instance['tile_size']
		);

		echo html( 'p', $input );
	}

	/*
	 * Validates the form input.
	 */
	public function widget_form_update( $instance, $new_instance, $old_instance ) {
		$instance['tile_size'] = ! empty( $new_instance['tile_size'] ) ? absint( $new_instance['tile_size'] ) : 1;
		return $instance;
	}

}
