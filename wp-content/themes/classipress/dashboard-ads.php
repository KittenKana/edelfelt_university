<?php
/**
 * Dashboard listings template.
 *
 * @package ClassiPress\Templates
 * @since 3.5.0
 */
?>

<p><?php _e( 'Below you will find a listing of all your classified ads. Click on one of the options to perform a specific task. If you have any questions, please contact the site administrator.', APP_TD ); ?></p>

<?php appthemes_get_template_part( 'parts/dashboard-ads', 'filters' ); ?>

<?php if ( $listings = cp_get_user_dashboard_listings() ) : ?>

	<?php
	/**
	 * Filters the listing dashboard sortable columns.
	 *
	 * @param array $columns Column titles keyed with column names.
	 */
	$sortable_columns = apply_filters( 'cp_dashboard_listings_sortable_columns',
		array(
			'listing_title' => 'asc',
			'listed'        => 'desc',
		)
	);

	/**
	 * Filters the listing dashboard columns.
	 *
	 * Use in pair with action 'cp_dashboard_listings_column_' . $key
	 *
	 * @param array $columns Column titles keyed with column names.
	 */
	$dashboard_columns = apply_filters( 'cp_dashboard_listings_columns',
		array(
			'ad_thumbnail'  => '',
			'listing_title' => __( 'Title', APP_TD ),
			'listed'        => __( 'Listed', APP_TD ),
			'status'        => __( 'Status', APP_TD ),
		)
	);
	?>

	<table class="table-plain table-bootstrap stack">
		<thead>
			<tr>
				<?php foreach ( $dashboard_columns as $key => $column ) : ?>
					<th class="<?php echo esc_attr( $key ); ?>">
						<?php
						if ( isset( $sortable_columns[ $key ] ) ) {
							$current_order = strtolower( get_query_var( 'order' ) );

							if ( get_query_var( 'orderby' ) !== $key || empty( $current_order ) || ! in_array( $current_order, array( 'asc', 'desc' ) ) ) {
								$current_order = $link_order = $sortable_columns[ $key ];
							} else {
								$link_order = 'asc' === $current_order ? 'desc' : 'asc';
							}

							echo html( 'a', array(
								'href' => add_query_arg( array(
									'orderby' => $key,
									'order'   => $link_order,
								) ),
								'class' => $current_order . ( get_query_var( 'orderby' ) === $key ? ' sorted-col' : ' sortable-col' ),
							), $column . ' <i class="sorting-handle fa fa-caret-up"></i>' );
						} else {
							echo $column;
						}

						?>
					</th>
				<?php endforeach; ?>
				<th></th>
			</tr>
		</thead>
		<tbody>

		<?php while ( $listings->have_posts() ) : $listings->the_post(); ?>

			<?php
			$status      = cp_get_listing_status_name( $post->ID );
			$expire_time = strtotime( get_post_meta( $post->ID, 'cp_sys_expire_date', true ) );
			$listing     = get_post();
			?>

			<tr class="listing-row table-expand-row" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-open-details>

				<?php foreach ( $dashboard_columns as $key => $column ) : ?>

					<td class="<?php echo esc_attr( $key ); ?>">
						<div class="row">
							<div class="column small-3 hide-for-m-large">
								<?php if ( 'listing_title' === $key ) : ?>
									<?php cp_ad_loop_thumbnail(); ?>
								<?php else: ?>
									<strong><?php echo $column; ?></strong>
								<?php endif; ?>
							</div>
							<div class="column small-9 m-large-12">
								<?php
								if ( 'listing_title' === $key ) : ?>

									<a href="<?php the_permalink(); ?>" target="_blank"><?php if ( mb_strlen( get_the_title() ) >= 75 ) { echo mb_substr( get_the_title(), 0, 75 ) . '...'; } else { the_title(); } ?></a>
									<div class="text-muted">
										<small><i class="fa fa-folder-o"></i> <?php
											$term_list = get_the_term_list( get_the_id(), APP_TAX_CAT, '', ', ', '' );
											echo $term_list ? $term_list : esc_html( 'Uncategorized', APP_TD );
										?></small>
									</div>

								<?php
								elseif ( 'status' === $key ) : ?>

									<span class="label <?php echo esc_attr( $status ); ?>"><?php echo cp_get_status_i18n( $status ); ?></span>
									<?php
									if ( 'yes' === get_post_meta( get_the_ID(), 'cp_ad_sold', true ) ) {
										?>
										<span class="label sold"><?php esc_html_e( 'Sold', APP_TD ); ?></span>
										<?php
									}

									if ( $expire_time ) {
										$flag = $expire_time < time() ? 'expired-date' : 'active-date text-muted';
										echo '<p><small class="' . $flag . '">(' . appthemes_display_date( $expire_time, 'date' ) . ')</small></p>';
									}
									?>
								<?php
								elseif ( 'listed' === $key ) : ?>

									<small><?php echo appthemes_date_posted( get_the_date( "Y-m-d H:i:s" ) ); ?></small>

								<?php
								elseif ( 'ad_thumbnail' === $key ) :

									?>
									<div class="show-for-m-large">
										<?php cp_ad_loop_thumbnail(); ?>
									</div>
									<?php

								else :

									/**
									 * Fires on the listing dashboard custom column.
									 *
									 * The dynamic portion of the hook refers to the
									 * column name.
									 *
									 * Use in pair with filter 'cp_dashboard_listings_columns'
									 *
									 * @param WP_Post $listing The listing object.
									 */
									do_action( 'cp_dashboard_listings_column_' . $key, $listing );

								endif; ?>

							</div>

						</div><!-- .column -->

					</td>

				<?php endforeach; ?>

				<td class="show-for-m-large"><span class="expand-icon"></span></td>

			</tr>

			<tr class="table-expand-row-content">
				<td colspan="100%" class="table-expand-row-nested">
					<?php
					/**
					 * Fires on the listings dashboard in the expanded row
					 * before listing actions.
					 *
					 * @param WP_Post $listing The listing object.
					 */
					do_action( 'cp_dashboard_listing_expanded_row', $listing );
					?>

					<?php cp_dashboard_listing_actions(); ?>
				</td>
			</tr>

		<?php endwhile; ?>

		</tbody>

	</table>

	<?php cp_do_pagination( $listings ); ?>

<?php else : ?>

	<p><?php _e( 'You currently have no classified ads.', APP_TD ); ?></p>

<?php endif; ?>

<?php wp_reset_postdata(); ?>
