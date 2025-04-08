<?php
/**
 * Dashboard filters template.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

$status_filters = apply_filters( 'cp_user_dashboard_status_filters', array(
	''        => __( 'Any Status', APP_TD ),
	'live'    => cp_get_status_i18n( 'live' ),
	'ended'   => cp_get_status_i18n( 'ended' ),
	'offline' => cp_get_status_i18n( 'offline' ),
	'pending' => cp_get_status_i18n( 'pending' ),
) );

$status   = ! empty( $_GET['status'] ) && isset( $status_filters[ $_GET['status'] ] ) ? stripslashes( $_GET['status'] ) : '';
$dcat     = ! empty( $_GET['dcat'] ) ? absint( $_GET['dcat'] ) : '';
$keywords = ! empty( $_GET['ds'] ) ? stripslashes( $_GET['ds'] ) : '';

?>

<form method="get" class="dashboard-filter-form" action="" role="search">

	<table class="table-plain table-bootstrap stack">
		<tbody>
			<tr>
				<td>
					<?php
					echo scbForms::input( array(
						'type'   => 'select',
						'title'  => '',
						'name'   => 'status',
						'values' => $status_filters,
					), array( 'status' => $status ) ) ?>
					</td>
				<td>
					<?php
					wp_dropdown_categories( array(
						'taxonomy' => APP_TAX_CAT,
						'show_option_all' => __( 'All Categories', APP_TD ),
						'hierarchical' => true,
						'name' => 'dcat',
						'selected' => $dcat,
					) );
					?>
				</td>
				<td>
					<input name="ds" type="search" id="search_keywords" class="search_keywords" value="<?php echo esc_attr( $keywords ); ?>" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder text', APP_TD ); ?>" />
				</td>
				<td>
					<button type="submit" class="button expanded" title="<?php esc_attr_e( 'Search', APP_TD ); ?>">
						<i class="fa fa-search" aria-hidden="true"></i>
					</button>
				</td>
			</tr>
		</tbody>
	</table>

	<?php do_action( 'cp_dashboard_listings_filters' ); ?>

</form>
