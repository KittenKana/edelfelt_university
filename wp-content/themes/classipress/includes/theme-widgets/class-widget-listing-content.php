<?php
/**
 * Widget for listing main content.
 *
 * @package ClassiPress\Widgets
 * @author AppThemes
 * @since 4.0.0
 */

/**
 * Listing content widget class.
 */
class CP_Widget_Listing_Content extends APP_Widget {

	/**
	 * Constructor
	 *
	 * @param array $args Predefined widget arguments.
	 */
	public function __construct( $args = array() ) {
		$default_args = array(
			'id_base'        => 'cp_widget_listing_content',
			'name'           => __( 'ClassiPress - Single Listing Description', APP_TD ),
			'defaults'       => array(
				'title' => '',
			),
			'widget_ops'     => array(
				'description' => __( 'Display the listing description.', APP_TD ),
			),
			'control_options' => array(),
		);

		$args = $this->_array_merge_recursive( $default_args, $args );

		parent::__construct( $args['id_base'], $args['name'], $args['widget_ops'], $args['control_options'], $args['defaults'] );
	}

	/**
	 * This is where the actual widget content goes.
	 *
	 * @param array $instance The settings for the particular instance of the
	 * widget.
	 */
	public function content( $instance ) {

		appthemes_before_post_content( get_post_type() );

		the_content();
		appthemes_after_post_content( get_post_type() );
		?>
        
        <!--
        <div class="hero-listing-bar">
        -->

            <div class="row">

                <div class="columns">

                    <?php
                    /**
                     * Fires in the single listing hero footer section.
                     *
                     * @since 4.0.0
                     */
                    do_action( 'cp_listing_single_bar_actions' );
                    ?>

                </div> <!-- .columns -->

            </div> <!-- .row -->
		<!--
        </div> <!-- .hero-listing-bar -->
        -->

        
		<footer class="entry-footer">
			<?php

			/**
			 * Fires in the single page footer.
			 *
			 * @since 4.0.0
			 */
            
			do_action( 'cp_single_template_footer' );
			?>

		</footer>
		<?php
	}
}