<?php
/**
 * Ad tags and categories cloud widget.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Ad tags and categories cloud widget class.
 *
 * @since 1.0.0
 */
class CP_Widget_Ads_Tag_Cloud extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base' => 'ad_tag_cloud',
			'name'    => __( 'ClassiPress Ads Tag Cloud', APP_TD ),
			'defaults' => array(
				'title'    => __( 'Ad Tags', APP_TD ),
				'taxonomy' => APP_TAX_TAG,
				'number'   => 45,
			),
			'widget_ops' => array(
				'description' => __( 'Your most used ad tags in cloud format.', APP_TD ),
				'classname'   => 'widget_tag_cloud',
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	public function content( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
 			$number = 45;
 		}

		$current_taxonomy = ( empty( $instance['taxonomy'] ) || ! taxonomy_exists( $instance['taxonomy'] ) ) ? APP_TAX_TAG : $instance['taxonomy'];

		echo '<div>';
		wp_tag_cloud( apply_filters( 'widget_tag_cloud_args', array( 'taxonomy' => $current_taxonomy, 'number' => $number ) ) );
		echo "</div>\n";

	}

	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD )
			),
			array(
				'type'   => 'select',
				'name'   => 'taxonomy',
				'desc'   => __( 'Taxonomy:', APP_TD ),
				'values' => $this->get_taxonomy_options(),
			),
			array(
				'type'  => 'text',
				'name'  => 'number',
				'desc'  => __( 'Number of items to show:', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
		);

		return $form_fields;
	}

	protected function get_taxonomy_options() {
		$options = array();
		$taxonomies = get_object_taxonomies( APP_POST_TYPE );

		foreach ( $taxonomies as $taxonomy ) {
			$tax_obj = get_taxonomy( $taxonomy );
			if ( ! $tax_obj->show_tagcloud || empty( $tax_obj->labels->name ) ) {
				continue;
			}
			$options[ $taxonomy ] = $tax_obj->labels->name;
		}

		return $options;
	}

}
