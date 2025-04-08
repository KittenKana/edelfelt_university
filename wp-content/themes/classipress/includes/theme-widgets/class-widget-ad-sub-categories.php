<?php
/**
 * Widget to show all ad sub-categories.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Widget to show all ad sub-categories.
 * @since 3.5
 */
class CP_Widget_Ad_Sub_Categories extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'  => 'widget-ad-sub-categories',
			'name'     => __( 'ClassiPress Ad Sub Categories', APP_TD ),
			'defaults' => array(
				'title'      => __( 'Sub-Categories', APP_TD ),
				'number'     => '0',
				'show_count' => '0',
				'hide_no_cats' => '0',
				'taxonomy' => array( APP_TAX_CAT ),
			),
			'widget_ops' => array(
				'description' => __( 'Display sub-categories on a category page.', APP_TD ),
				'classname'   => 'widget-ad-categories',
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	/**
	 * Only display widget on ad category pages.
	 *
	 * note: cannot be used with 'condition()' since it's too soon for the conditional tag.
	 */
	public function widget( $args, $instance ) {

		$instance = array_merge( $this->defaults, (array) $instance );

		if ( empty( $instance['taxonomy'] ) || ! is_tax( $instance['taxonomy'] ) ) {
			return;
		}

		// Don't display widget if there is no categories.
		ob_start();
		$this->content( $instance );
		$instance['the_content'] = ob_get_clean();

		if ( empty( $instance['the_content'] ) ) {
			return;
		}

		parent::widget( $args, $instance );
	}

	public function content( $instance ) {
		if ( isset( $instance['the_content'] ) ) {
			echo $instance['the_content'];
			return;
		}

		$instance = array_merge( $this->defaults, (array) $instance );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
 			$number = null;
 		}
		$show_count = ! empty( $instance['show_count'] ) ? '1' : '0';
		$use_dropdown = ! empty( $instance['use_dropdown'] );

		// go get the taxonomy category id so we can filter with it
		// have to use slug instead of name otherwise it'll break with multi-word cats
		if ( ! isset( $filter ) ) {
			$filter = '';
		}

		$tax = get_queried_object();

		// show all subcategories if any
		$args = array(
			'hide_empty'       => false,
			'show_count'       => $show_count,
			'title_li'         => '',
			'echo'             => false,
			'taxonomy'         => $tax->taxonomy,
			'depth'            => 1,
			'child_of'         => $tax->term_id,
			'number'           => $number,
			'class'            => 'postform cat-dropdownlist',
			'value_field'      => 'slug',
			'show_option_none' => $use_dropdown ? __( 'Any', APP_TD )  : false,
			'hierarchical'     => true,
			'hide_if_empty'    => true
		);

		$js_submit = '';

		if ( $use_dropdown ) {
			$subcats = wp_dropdown_categories( $args );

			ob_start();
?>
			<script type="text/javascript">
				<!--
				var dropdown = document.getElementById("cat");
				function onCatChange() {
					if ( dropdown.options[dropdown.selectedIndex].value !== '' ) {
						location.href = document.URL+'/'+dropdown.options[dropdown.selectedIndex].value;
					}
				}
				dropdown.onchange = onCatChange;
				-->
			</script>
<?php
			$js_submit = ob_get_clean();
		} else {
			$subcats = wp_list_categories( $args );
		}

		if ( ! empty( $subcats ) ) {
			echo html( 'ul', print_r( $subcats, true ) );
			echo $js_submit;
		} elseif ( empty( $instance['hide_no_cats'] ) ) {
			echo html( 'p', __( 'No sub-categories available', APP_TD ) );
		}

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
				'name' => 'number',
				'desc' => __( 'Number of sub-categories to show (0 for all):', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'use_dropdown',
				'desc' => __( 'Use Dropdown', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'show_count',
				'desc' => __( 'Show ads counts', APP_TD ),
			),
			array(
				'type' => 'checkbox',
				'name' => 'hide_no_cats',
				'desc' => __( 'Hide widget for no categories', APP_TD ),
			),
		);

		$taxonomies = get_object_taxonomies( APP_POST_TYPE, 'objects' );
		$taxonomies = wp_list_filter( $taxonomies, array( 'hierarchical' => true ) );
		$taxonomies = wp_list_pluck( $taxonomies, 'label' );

		$form_fields[] = array(
			'desc'   => '<p>' . __( 'Select the Taxonomy:', APP_TD ) . '</p>',
			'type'   => 'custom',
			'name'   => '__blank__',
			'render' => function() {
				return '<p>' . __( 'Select the Taxonomy:', APP_TD ) . '</p>';
			},
		);

		$form_fields[] = array(
			'type'   => 'checkbox',
			'name'   => 'taxonomy',
			'values' => $taxonomies,
		);

		return $form_fields;
	}

	function form( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );

		$output = '';
		foreach ( $this->form_fields() as $field ) {
			$output .= html( 'p', $this->input( $field, $instance ) );
		}
		$output .= html( 'p', __( '<strong>Note:</strong> This widget is only displayed on category pages.', APP_TD ) );

		echo $output;
	}

}
