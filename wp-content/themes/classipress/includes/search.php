<?php
/**
 * Search engine and Refine results.
 *
 * @package ClassiPress\Search
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */


// search suggest
add_action( 'wp_ajax_nopriv_ajax-tag-search-front', 'cp_suggest' );
add_action( 'wp_ajax_ajax-tag-search-front', 'cp_suggest' );
// update Search Index
add_action( 'cp_update_listing', 'appthemes_update_search_index' );

// sanitize search term
add_filter( 'get_search_query', 'appthemes_filter' );


/**
 * Returns category id for search form.
 *
 * @return int
 */
function cp_get_search_catid() {
	global $post;

	$catid = 0;

	if ( is_tax( APP_TAX_CAT ) ) {
		$ad_cat_array = get_term_by( 'slug', get_query_var( APP_TAX_CAT ), APP_TAX_CAT, ARRAY_A );
		$catid = $ad_cat_array['term_id'];
	} else if ( is_singular( APP_POST_TYPE ) ) {
		$term = wp_get_object_terms( $post->ID, APP_TAX_CAT );
		if ( $term ) {
			$catid = $term[0]->term_id;
		}
	} else if ( is_search() ) {
		$catid = get_query_var( 'scat' );
	}

	return absint( $catid );
}


/**
 * Returns search term for refine results form.
 *
 * @return string
 */
function cp_get_search_term() {

	// get not escaped search query
	$search_term = get_search_query( false );

	return $search_term;
}


/**
 * Returns an array of all custom field names. For use in search.
 *
 * @return array
 */
function cp_custom_search_fields() {
	global $wpdb;

	$custom_fields = array();

	$sql = "SELECT field_name FROM $wpdb->cp_ad_fields p WHERE p.field_name LIKE 'cp_%' ";
	$results = $wpdb->get_results( $sql );

	if ( $results ) {
		foreach ( $results as $result ) {
			// put the fields into an array
			$custom_fields[] = $result->field_name;
		}
	}

	return $custom_fields;
}


/**
 * Ajax auto-complete taxonomy search suggest.
 *
 * @return void
 */
function cp_suggest() {
	global $wpdb;

	$request = wp_unslash( $_GET['term'] );

	$taxonomy = ( isset( $_GET['tax'] ) ) ? sanitize_title( $_GET['tax'] ) : '';
	$taxonomy = apply_filters( 'cp_suggest_taxonomies', (array) $taxonomy );

	if ( empty( $taxonomy ) ) {
		die( 'no taxonomy' );
	}

	if ( false !== strpos( $request, ' ' ) ) {
		$s = explode( ' ', $request );
		$s = $s[count( $s ) - 1];
	} else {
		$s = $request;
	}
	$s = trim( $s );
	if ( strlen( $s ) < 2 ) {
		die( __( 'need at least two characters', APP_TD ) ); // require 2 chars for matching
	}

	$terms = $wpdb->get_col( "
		SELECT tt.term_taxonomy_id FROM $wpdb->term_taxonomy AS tt INNER JOIN $wpdb->terms AS t ON tt.term_id = t.term_id ".
		"WHERE tt.taxonomy IN ('" . implode( "', '", $taxonomy ) . "') AND tt.count > 0 ".
		"AND t.name LIKE (
			'%$s%'
		) " .
		"AND '$request' NOT LIKE (
			CONCAT('%', t.name ,'%')
		) " .
		"ORDER BY t.name ASC " .
		"LIMIT 10"
	);

	if ( empty( $terms ) ) {
		echo json_encode( $terms );
		die;
	} else {
		foreach ( $terms as $term ) {
			$term_obj = get_term_by( 'term_taxonomy_id', $term );
			// Use Name as key to prevent duplicates.
			$results[ $term_obj->name ] = $term_obj;
		}
		echo json_encode( $results );
		die;
	}
}


/**
 * Displays refine search form based on the category id.
 *
 * @param int $cat_id
 *
 * @return void
 */
if ( ! function_exists( 'cp_show_refine_search' ) ) :
function cp_show_refine_search( $cat_id ) {
	global $wpdb;

	$form_id = cp_get_form_id( $cat_id );
	$results = null;

	if ( $form_id ) {
		// now we should have the formid so show the form layout based on the category selected
		$sql = $wpdb->prepare( "SELECT f.field_label, f.field_name, f.field_type, f.field_values, f.field_perm, m.field_search, m.meta_id, m.field_pos, m.field_req, m.form_id "
			. "FROM $wpdb->cp_ad_fields f "
			. "INNER JOIN $wpdb->cp_ad_meta m ON f.field_id = m.field_id "
			. "WHERE m.form_id = %s AND m.field_search = '1' "
			. "ORDER BY m.field_pos ASC", $form_id );

		$results = $wpdb->get_results( $sql );
	}

	echo cp_refine_search_builder( (array) $results ); // loop through the custom form fields and display them

}
endif;


/**
 * Displays refine search form based on the form fields.
 *
 * @param array $results
 *
 * @return void
 */
if ( ! function_exists( 'cp_refine_search_builder' ) ) :
	function cp_refine_search_builder( $results ) {
		global $wpdb, $cp_options;

		$scat = cp_get_search_catid();
		$location_search = !! APP_Geocoder_Registry::get_active_geocoder();
		?>

		<h3 class="widget-title"><?php _e( 'Refine Results', APP_TD ); ?></h3>

		<form action="<?php echo esc_url( get_post_type_archive_link( APP_POST_TYPE ) ); ?>" method="get" name="refine-search">

			<input type="hidden" name="st" value="<?php echo esc_attr( APP_POST_TYPE ); ?>">
			<input type="hidden" name="s" value="<?php echo esc_attr( cp_get_search_term() ); ?>" />
			<input type="hidden" name="scat" value="<?php echo esc_attr( $scat ); ?>" />
			<input type="hidden" name="lat" value="<?php echo ! empty( $_GET[ 'lat' ] ) ? esc_attr( $_GET[ 'lat' ] ) : 0; ?>">
			<input type="hidden" name="lng" value="<?php echo ! empty( $_GET[ 'lng' ] ) ? esc_attr( $_GET[ 'lng' ] ) : 0; ?>">
			<input type="hidden" name="location" value="<?php echo ! empty( $_GET[ 'location' ] ) ? esc_attr( $_GET[ 'location' ] ) : ''; ?>">

			<?php if ( $scat ) { ?>
				<div class="refine-search-field-wrap">

					<?php cp_refine_categories(); ?>

				</div><!-- .refine-search-field-wrap -->
			<?php } ?>

			<?php if ( $location_search ) {
				$slider_val = ! empty( $_GET[ 'radius' ] ) ? $_GET[ 'radius' ] : ( ! empty( $cp_options->default_radius ) ? $cp_options->default_radius : 50 );
				?>

				<div class="refine-search-field-wrap search-radius">
                                <!--
					<label class="title" for="radius"><?php _e( 'Radius', APP_TD ); ?></label>
					<div class="slider" data-slider data-start="1" data-end="<?php echo absint( $slider_val ) * 2; ?>" data-initial-start="<?php echo absint( $slider_val ); ?>" data-step="1" >
						<span class="slider-handle" data-slider-handle role="slider" tabindex="1" aria-controls="sliderRadius"></span>
						<span class="slider-fill" data-slider-fill></span>
					</div>
					<div class="slider-text"><input type="text" name="radius" id="sliderRadius" class="input-aria-controls"> <em><?php echo ( 'mi' == $cp_options->geo_unit ) ? __( 'miles', APP_TD ) : __( 'kilometers', APP_TD ); ?></em></div>
				-->
				</div><!-- .refine-search-field-wrap -->

			<?php }

			$price = null;

			// grab the price field first and put into a separate array
			// then remove them from the results array so they don't print out again
			foreach ( $results as $key => $value ) {
				if ( 'cp_price' === $value->field_name ) {
					$price = $results[ $key ];
					unset( $results[ $key ] );
					array_unshift( $results, $price );
					break;
				}
			}

			foreach ( $results as $key => $result ) {
				// show the price field range slider
				if ( $result->field_name == 'cp_price' ) {

					if ( $cp_options->refine_price_slider ) {
					?>

					<div class="refine-search-field-wrap amount">
						<?php
						$cp_min_price = str_replace( ',', '', $wpdb->get_var( "SELECT min( CAST( m.meta_value AS UNSIGNED ) ) FROM $wpdb->postmeta m INNER JOIN $wpdb->posts p ON m.post_id = p.ID WHERE m.meta_key = 'cp_price' AND p.post_status = 'publish'" ) );
						$cp_max_price = str_replace( ',', '', $wpdb->get_var( "SELECT max( CAST( m.meta_value AS UNSIGNED ) ) FROM $wpdb->postmeta m INNER JOIN $wpdb->posts p ON m.post_id = p.ID WHERE m.meta_key = 'cp_price' AND p.post_status = 'publish'" ) );

						$price_min_initial = ( ! empty( $_GET['price_min'] ) ) ? $_GET['price_min'] : $cp_min_price;
						$price_max_initial = ( ! empty( $_GET['price_max'] ) ) ? $_GET['price_max'] : $cp_max_price;
						?>

						<label class="title"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?>:&nbsp;<input type="text" id="amount" name="amount" class="input-aria-controls" /></label>

						<div class="slider" data-slider data-start="<?php echo intval( $cp_min_price ); ?>" data-end="<?php echo intval( $cp_max_price ); ?>" data-initial-start="<?php echo intval( $price_min_initial ); ?>" data-initial-end="<?php echo intval( $price_max_initial ); ?>">
							<span class="slider-handle" data-slider-handle role="slider" tabindex="2" aria-controls="price_min"></span>
							<span class="slider-fill" data-slider-fill></span>
							<span class="slider-handle" data-slider-handle role="slider" tabindex="3" aria-controls="price_max"></span>
							<input type="hidden" id="price_min" name="price_min" value="<?php echo esc_attr( $price_min_initial ); ?>" />
							<input type="hidden" id="price_max" name="price_max" value="<?php echo esc_attr( $price_max_initial ); ?>" />
						</div>
					</div><!-- .refine-search-field-wrap -->
					<?php
					} else {
					?>
					<div class="refine-search-field-wrap price_min_max">
						<label class="title"><?php echo esc_html( translate( $result->field_label, APP_TD ) ); ?> (<?php echo $cp_options->curr_symbol; ?>)</label>
						<div class="row collapsed">
							<div class="large-6 medium-12 column">
								<input type="text" class="text" id="price_min" name="price_min" placeholder="<?php _e( 'from', APP_TD ); ?>" value="<?php if ( isset( $_GET['price_min'] ) ) echo esc_attr( $_GET['price_min'] ); ?>" />
							</div>
							<div class="large-6 medium-12 column">
								<input type="text" class="text" id="price_max" name="price_max" placeholder="<?php _e( 'to', APP_TD ); ?>" value="<?php if ( isset( $_GET['price_max'] ) ) echo esc_attr( $_GET['price_max'] ); ?>" />
							</div>
						</div>
					</div><!-- .refine-search-field-wrap -->
					<?php
					}

				} elseif ( in_array( $result->field_type, array( 'radio', 'checkbox', 'drop-down', 'text box', 'text area' ) ) ) { ?>
					<div class="refine-search-field-wrap">
						<?php echo cp_refine_fields( $result->field_label, $result->field_name, $result->field_values, $result->field_type ); ?>
					</div><!-- .refine-search-field-wrap -->
				<?php }
			}

			$current_orderby = get_query_var( 'sort' );

			if ( is_array( $current_orderby ) ) {
				$current_orderby = array_keys( $current_orderby );
			} else {
				$current_orderby = (array) $current_orderby;
			}

			$current_orderby = array_filter( $current_orderby );
			// Make Distance default option if user searches only by location.
			if ( empty( $current_orderby ) && ! get_query_var( 's' ) && get_query_var( 'lat' ) ) {
				$current_orderby[] = 'distance';
			}
			?>
			<label for="orderby"><?php _e( 'Order By', APP_TD ); ?>
				<select id="sort" name="sort">
					<option value=""><?php _e( 'Relevance', APP_TD ); ?></option>
					<?php

					$orderby_values = array(
						'title'  => __( 'Alphabetical', APP_TD ),
						'date'   => __( 'Newest', APP_TD ),
						'random' => __( 'Random', APP_TD ),
					);

					if ( current_theme_supports( 'app-stats' ) ) {
						$orderby_values['popular'] = __( 'Popular', APP_TD );
					}

					if ( $location_search ) {
						$orderby_values['distance'] = __( 'Closest', APP_TD );
					}

					if ( $price ) {
						$orderby_values = array_merge( $orderby_values, array(
							'low_price'  => __( 'Lowest Price', APP_TD ),
							'high_price' => __( 'Highest Price', APP_TD ),
						) );
					}

					/**
					 * Filters the listing order by values dropdown menu.
					 *
					 * @since 4.0.0
					 *
					 * @param array $orderby_values An array of order by values.
					 */
					$orderby_values = apply_filters( 'cp_orderby_values_dropdown', $orderby_values );

					$selected = '';
					foreach ( $orderby_values as $value => $label ) {
						$_selected = '';
						if ( in_array( $value, $current_orderby, true ) && ! $selected ) {
							$_selected = selected( $value, $value, false );
							$selected  = $_selected;
						}

						echo "\t" . '<option value="' . esc_attr( $value ) . '"' . $_selected . ">$label</option>\n";
					}
				?>
				</select>
			</label>

			<?php
			/**
			 * Fires after the refine search fields.
			 *
			 * @since 4.0.0
			 */
			do_action( 'cp_listing_refine_search_fields_after' );
			?>

			<button class="expanded button" type="submit" tabindex="1" id="go"><?php _e( 'Refine Results &rsaquo;&rsaquo;', APP_TD ); ?></button>

			<input type="hidden" name="refine_search" value="yes" />

		</form>

<?php
	}
endif;


/**
 * Displays form field for refine search form.
 *
 * @param string $label
 * @param string $name
 * @param string $values
 * @param string $type
 *
 * @return void
 */
function cp_refine_fields( $label, $name, $values, $type ) {
	 if ( in_array( $type, array( 'radio', 'checkbox', 'drop-down' ) ) ) {
?>

		<label class="title refine-categories-list-label" for="<?php echo esc_attr( $name ); ?>" aria-expanded="false"><?php echo esc_html( translate( $label, APP_TD ) ); ?></label>

		<div class="refine-categories-list-wrap">

			<?php
			$options = cp_explode( ',', $values );
			$optionCursor = 1;
			$checked = '';
			?>

			<ul class="checkboxes">

				<?php
				$cur = ( isset( $_GET[ $name ] ) && is_array( $_GET[ $name ] ) ) ? array_map( 'stripslashes', $_GET[ $name ] ) : array();
				foreach ( $options as $option ) {
					if ( $cur ) {
						$checked = in_array( $option, $cur ) ? " checked='checked'" : '';
					}
				?>
				<li>
					<label for="<?php echo esc_attr( $name ); ?>[]" class="selectit">
						<input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $option ); ?>" <?php echo $checked; ?> />&nbsp;<?php echo esc_html( $option ); ?>
					</label>
				</li> <!-- #checkbox -->
				<?php } ?>

			</ul> <!-- #checkbox-wrap -->

		</div><!-- .refine-categories-list -->
<?php
	} else {
?>
		<label class="title"><?php echo esc_html( translate( $label, APP_TD ) ); ?></label>
		<input name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" type="text" minlength="2" value="<?php if ( isset( $_GET[ $name ] ) ) echo esc_attr( stripslashes( $_GET[ $name ] ) ); ?>" class="text" />
<?php
	}
}

/**
 * Displays field for refine listings by category.
 *
 * @since 4.2.0
 *
 * @param array $args Array of the default options. Default empty.
 * @return void
 */
function cp_refine_categories( $args = array() ) {

	require_once ABSPATH . '/wp-admin/includes/template.php';

	$defaults = array(
		'taxonomy'    => APP_TAX_CAT,
		'request_var' => 'subcat',
		'class'       => 'refine-categories-list',
		'label'       => __( 'Sub-Categories', APP_TD ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Check the listing cats if they were searched or viewing a listing cat archive page.
	$selected = isset( $_GET[ $args['request_var'] ] ) ? $_GET[ $args['request_var'] ] : array();
	$walker   = new CP_Walker_Refine_Category_Checklist();

	$walker->cp_name = $args['request_var'];

	/**
	 * Filters refine search categories parameters.
	 *
	 * @since 4.2.0
	 *
	 * @param array  $args Array of the default options.
	 */
	$checklist_args =  apply_filters( 'cp_refine_categories_args', array(
		'descendants_and_self' => cp_get_search_catid(),
		'taxonomy'             => $args['taxonomy'],
		'selected_cats'        => $selected,
		'checked_ontop'        => false,
		'walker'               => $walker,
		'echo'                 => false,
	) );

	$output = wp_terms_checklist( 0, $checklist_args );

	if ( $output ) {
		?>
		<label class="title refine-categories-list-label" for="<?php echo esc_attr( $args['request_var'] ); ?>" aria-expanded="false"><?php echo esc_html( translate( $args['label'], APP_TD ) ); ?></label>
		<div class="refine-categories-list-wrap">
			<?php echo $output; ?>
		</div><!-- .refine-categories-list-wrap -->
		<?php
	}
}
