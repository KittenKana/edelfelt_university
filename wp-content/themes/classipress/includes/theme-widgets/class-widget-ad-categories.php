<?php
/**
 * Widget to show all ad categories.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Widget to show all ad categories.
 * @since 3.3
 */
class CP_Widget_Ad_Categories extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'         => 'widget-ad-categories',
			'name'            => __( 'ClassiPress Ad Categories', APP_TD ),
			'defaults'        => array(
				'title'         => __( 'Ad Categories', APP_TD ),
				'sub_num'       => '3',
				'depth'         => '3',
				'cols'          => '1',
				'show_count'    => '0',
				'hide_empty'    => '0',
				'extra_class'   => '',
				'show_bg_image' => '0',
				'show_bg_color' => '0',
				'show_icon'     => '0',
				'cat_include'   => false,
			),
			'widget_ops'      => array(
				'description' => __( 'Display the ad categories.', APP_TD ),
				'classname'   => 'widget-ad-categories',
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	public function content( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );

		if ( $instance['show_bg_image'] ) {
			$instance['extra_class'] .= ' has-images';
		}

		if ( $instance['show_bg_color'] ) {
			$instance['extra_class'] .= ' has-bg-colors';
		}

		if ( $instance['show_icon'] ) {
			$instance['extra_class'] .= ' has-icons';
		}

		$instance['extra_class'] = trim( $instance['extra_class'] );

		$large_cols  = $instance['cols'];
		$medium_cols = ( 2 <= $large_cols ) ? 2 : 1;
		$classes     = ( $instance['extra_class'] ) ? ' ' . $instance['extra_class'] : '';

		$args = array(
			'menu_depth'       => $instance['depth'],
			'menu_sub_num'     => $instance['sub_num'],
			'cat_parent_count' => $instance['show_count'],
			'cat_child_count'  => $instance['show_count'],
			'cat_hide_empty'   => $instance['hide_empty'],
			'show_bg_image'    => $instance['show_bg_image'],
			'show_bg_color'    => $instance['show_bg_color'],
			'show_icon'        => $instance['show_icon'],
			'cat_include'      => $instance['cat_include'],
		);

		?>
		<div class="row column">

			<div class="<?php echo esc_attr( rtrim( $classes ) ); ?>" >

				<div id="directory" class="directory listing-cats listing-cats-page row collapse small-up-1 medium-up-<?php echo $medium_cols ?> large-up-<?php echo $large_cols ?>">

					<?php echo cp_create_categories_list( 'dir', $args ); ?>

				</div><!--/directory-->

			</div>


		</div>
		<?php
	}

	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD )
			),
			array(
				'type' => 'text',
				'name' => 'extra_class',
				'desc' => __( 'Extra CSS classes (delimited by space):', APP_TD )
			),
			array(
				'type' => 'text',
				'name' => 'cat_include',
				'desc' => __( 'Comma-separated list of term ids to include:', APP_TD )
			),
			array(
				'desc'	 => __( 'Number of Sub-Categories:', APP_TD ),
				'type'	 => 'number',
				'name'	 => 'sub_num',
			),
			array(
				'desc'	 => __( 'Category Depth:', APP_TD ),
				'type'	 => 'number',
				'name'	 => 'depth',
			),
			array(
				'desc'	 => __( 'Number of Columns:', APP_TD ),
				'type'	 => 'select',
				'name'	 => 'cols',
				'values' => array(
					'1'	 => '1',
					'2'	 => '2',
					'3'	 => '3',
					'4'	 => '4',
				),
			),
			array(
				'desc' => __( 'Hide Empty', APP_TD ),
				'type' => 'checkbox',
				'name' => 'hide_empty',
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_count',
				'desc' => __( 'Show ads counts', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_bg_color',
				'desc' => __( 'Show category background color', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_bg_image',
				'desc' => __( 'Show category background image', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_icon',
				'desc' => __( 'Show category icon', APP_TD ),
			),
		);

		return $form_fields;
	}

}
