<?php
/**
 * Theme specific widgets.
 *
 * @package ClassiPress\Widgets
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

add_action( 'widgets_init', 'cp_unregister_widgets', 11 );

/**
 * Displays search form for the search widget.
 *
 * return void
 */
function cp_ad_search_widget() {
	appthemes_load_template( 'searchform-listing.php', array(
		'search_form_location' => 'widget',
	) );
}


/**
 * Filter by City Widget.
 * Not used.
 */
function cp_ad_region_widget() {
	global $wpdb;
?>
	<div class="shadowblock_out">

		<div class="shadowblock">

			<h2 class="dotted"><?php _e( 'Filter by City', APP_TD ); ?></h2>

			<div class="recordfromblog">

				<ul>
					<?php

						//$all_custom_fields = get_post_custom($post->ID);

						// get all the custom field labels so we can match the field_name up against the post_meta keys
						$sql = "SELECT field_values FROM $wpdb->cp_ad_fields f WHERE f.field_name = 'cp_city'";

						//$results = $wpdb->get_results($sql);
						$results = $wpdb->get_row( $sql );


						if ( $results ) {
					?>

							<a href="?region=all"><?php _e( 'All', APP_TD ); ?></a> /
							<?php
								$options = explode( ',', $results->field_values );

								foreach ( $options as $option ) {
							?>
									<a href="?region=<?php echo $option; ?>"><?php echo $option; ?></a> /
							<?php
								}

						} else {

							_e( 'No cities found.', APP_TD );

						}
					?>
				</ul>

			</div><!-- /recordfromblog -->

		</div><!-- /shadowblock -->

	</div><!-- /shadowblock_out -->

<?php
}


/**
 * Removes some of the default sidebar widgets.
 *
 * @return void
 */
function cp_unregister_widgets() {
	unregister_widget( 'P2P_Widget' );
}
