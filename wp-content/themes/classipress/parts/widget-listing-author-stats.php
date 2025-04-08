<?php
/**
 * Template to display widget "ClassiPress - Listing Author Stats".
 *
 * @todo Remove logic from the template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

global $current_user;

// calculate the total count of live ads for current user
$rows = $wpdb->get_results( $wpdb->prepare( "
	SELECT post_status, COUNT(ID) as count
	FROM $wpdb->posts
	WHERE post_author = %d
	AND post_type = '".APP_POST_TYPE."'
	GROUP BY post_status", $current_user->ID
) );

$counts = array();
foreach ( $rows as $row ) {
	$counts[ $row->post_status ] = $row->count;
}

$counts['total'] = array_sum( $counts );

$statuses = array(
	'publish'    => __( 'Live', APP_TD ),
	'pending'    => __( 'Pending', APP_TD ),
	'draft'      => __( 'Draft', APP_TD ),
	'auto-draft' => __( 'Auto Draft', APP_TD ),
	'total'      => __( 'Total', APP_TD ),
);
?>

<h4 class="stat-section-name"><?php esc_html_e( 'Listings', APP_TD );?></h4>
<ul class="stat-section stat-section-listings">

<?php
foreach ( $counts as $status => $count ) {

	if ( isset( $statuses[ $status ] ) ) {
		$name = $statuses[ $status ];
	} else {
		$name = cp_get_status_i18n( $status );
	}

?>
	<li class="stat-<?php echo esc_attr( $status ); ?>">
		<span class="stat-name"><?php echo esc_html( $name ); ?></span>
		<span class="stat-value label primary"><?php echo esc_html( $count ); ?></span>
	</li>
<?php
} ?>

</ul>
<?php
/**
 * Fires after the main listing stats.
 *
 * @since 4.0.0
 */
do_action( 'cp_widget_listing_author_stats' );
