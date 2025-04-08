<?php
/**
 * The listing search form template file
 *
 * @package ClassiPress
 * @since 4.0.0
 */

if ( ! isset( $search_form_location ) ) {
	$search_form_location = 'bar';
}

$is_widget       = 'widget' === $search_form_location;
$location_search = apply_filters( 'cp_display_search_location_field', true );

?>
<form method="get" class="search-form" action="<?php echo esc_url( get_post_type_archive_link( APP_POST_TYPE ) ); ?>" role="search">

	<div class="row">

		<div class="search-keywords-wrap medium-<?php echo $is_widget ? 12 : ( $location_search ? 4 : 5 ); ?> columns">
			<input name="s" type="search" id="search_keywords" class="search_keywords" value="<?php the_search_query(); ?>" placeholder="<?php echo esc_attr_x( 'What are you looking for?', 'placeholder text', APP_TD ); ?>" />
		</div>

		<?php if ( $location_search ) { ?>

			<div class="search-location-wrap medium-<?php echo $is_widget ? 12 : 3; ?> columns">
				<input type="text" value="<?php echo isset( $_GET['location'] ) ? esc_attr( $_GET['location'] ) : ''; ?>" name="location" id="search_location" class="search_location app-address-field" placeholder="<?php echo esc_attr_x( 'Location', 'placeholder text', APP_TD ); ?>" autocomplete="off">
			</div><!-- .search-location-wrap -->

		<?php } ?>

		<div class="search-category-wrap medium-<?php echo $is_widget ? 12 : ( $location_search ? 3 : 5 ); ?> columns">
			<?php wp_dropdown_categories( cp_get_dropdown_categories_search_args( $search_form_location ) ); ?>
		</div>

		<div class="search-button-wrap medium-<?php echo $is_widget ? 12 : 2 ?> columns">
			<button type="submit" class="button expanded">
				<i class="fa fa-search" aria-hidden="true"></i>
				<?php esc_html_e( 'Search', APP_TD ); ?>
			</button>
		</div>
		<?php
		/**
		 * Fires after the main header search fields.
		 *
		 * @since 4.0.0
		 */
		do_action( 'cp_listing_header_search_fields_after' );
		?>

		<input type="hidden" name="lat" value="<?php echo ! empty( $_GET[ 'lat' ] ) ? esc_attr( $_GET[ 'lat' ] ) : 0; ?>">
		<input type="hidden" name="lng" value="<?php echo ! empty( $_GET[ 'lng' ] ) ? esc_attr( $_GET[ 'lng' ] ) : 0; ?>">
		<input type="hidden" name="radius" value="<?php echo ! empty( $_GET[ 'radius' ] ) ? esc_attr( $_GET[ 'radius' ] ) : $cp_options->default_radius; ?>">
		<input type="hidden" name="st" value="<?php echo esc_attr( APP_POST_TYPE ); ?>">

	</div> <!-- .row -->

</form>
