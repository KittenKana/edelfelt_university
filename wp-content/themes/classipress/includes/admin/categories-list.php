<?php
/**
 * Admin Categories list.
 *
 * @package ClassiPress\Admin\Categories
 * @author  AppThemes
 * @since  4.2.0
 */


// Categories list (right panel)
add_filter( 'manage_edit-' . APP_TAX_CAT . '_columns', 'cp_categories_column_headers', 10, 1 );
add_filter( 'manage_' . APP_TAX_CAT . '_custom_column', 'cp_categories_column_row', 10, 3 );

/**
 * Sets the categories taxonomy headers.
 *
 * @param array $columns
 *
 * @return array
 */
function cp_categories_column_headers( $columns ) {

	$columns = array_merge(
		$columns,
		array(
			'thumbnail_id'     => __( 'Image', APP_TD ),
			'category_icon_id' => __( 'Icon', APP_TD ),
			'category_color'   => __( 'Color', APP_TD ),
		)
	);

	return $columns;
}


/**
 * Returns content for the custom category columns.
 *
 * @param string $row_content
 * @param string $column_name
 * @param int $term_id
 *
 * @return string
 */
function cp_categories_column_row( $row_content, $column_name, $term_id ) {
	global $taxonomy;

	switch ( $column_name ) {

		case 'thumbnail_id':
		case 'category_icon_id':
			$thumbnail_id = get_term_meta( $term_id, $column_name, true );
			$thumbnail_id = array_filter( (array) $thumbnail_id );
			if ( ! empty( $thumbnail_id ) ) {
				return wp_get_attachment_image( array_shift( $thumbnail_id ) );
			}
			break;

		case 'category_color':
			$color = get_term_meta( $term_id, $column_name, true );
			if ( $color ) {
				return '<div style="width:50px;height:50px;background:' . $color . ';"></div>';
			}
			break;


		default:
			break;

	}

}
