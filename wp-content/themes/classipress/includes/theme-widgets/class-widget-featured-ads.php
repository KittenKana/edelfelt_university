<?php
/**
 * Widget displays featured ads.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Widget displays featured ads class.
 * @since 3.3
 */
class CP_Widget_Featured_Ads extends APP_Widget {

	public function __construct( $args = array() ) {

		$default_args = array(
			'id_base'  => 'widget-featured-ads',
			'name'     => __( 'ClassiPress Featured Ads', APP_TD ),
			'defaults' => array(
				'title'  => __( 'Featured Ads', APP_TD ),
				'number' => 10,
			),
			'widget_ops' => array(
				'description' => __( 'Display the featured ads.', APP_TD ),
				'classname'   => 'widget-featured-ads',
			),
			'control_options' => array(),
		);

		extract( $this->_array_merge_recursive( $default_args, $args ) );

		parent::__construct( $id_base, $name, $widget_ops, $control_options, $defaults );
	}

	public function content( $instance ) {
		$instance = array_merge( $this->defaults, (array) $instance );

		if ( empty( $instance['number'] ) || ! $number = absint( $instance['number'] ) ) {
 			$number = 10;
 		}

		$ads_args = array(
			'post__in'       => get_option( 'sticky_posts' ),
			'post_type'      => APP_POST_TYPE,
			'posts_per_page' => $number,
			'orderby'        => 'rand',
			'no_found_rows'  => true,
		);

		$featured_ads = new WP_Query( $ads_args );
		$result = '';

		if ( $featured_ads->have_posts() ) {
			$result .= '<ul>';
			while ( $featured_ads->have_posts() ) {
				$featured_ads->the_post();
				$result .= '<li><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></li>';
			}
			$result .= '</ul>';
		}

		wp_reset_postdata();

		echo $result;

	}

	protected function form_fields() {
		$form_fields = array(
			array(
				'type' => 'text',
				'name' => 'title',
				'desc' => __( 'Title:', APP_TD )
			),
			array(
				'type'  => 'text',
				'name'  => 'number',
				'desc'  => __( 'Number of ads to show:', APP_TD ),
				'extra' => array( 'size' => 3 ),
			),
		);

		return $form_fields;
	}

}
