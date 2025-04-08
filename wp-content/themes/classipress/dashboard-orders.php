<?php if ( ( $orders = cp_get_user_dashboard_orders() ) || get_query_var('order_status') ) : ?>

	<p><?php _e( 'Below is your Order history. You can use the provided filter to filter all the orders.', APP_TD); ?></p>

	<form class="filter" method="get" action="<?php echo esc_url( CP_DASHBOARD_ORDERS_URL ) ?>" >

		<input type="hidden" name="tab" value="orders" />

		<?php $statuses = cp_get_order_statuses_verbiages(); ?>

		<table id="order_status_filters" class="stack table-plain text-small">
			<thead>
				<tr>
					<?php foreach( $statuses as $order_status => $name ): ?>

						<?php $checked = (bool) ( ! get_query_var('order_status') || in_array( $order_status, get_query_var('order_status') ) ); ?>
						<th>
							<input type="checkbox" name="order_status[]" id="order_status_<?php echo esc_attr( $order_status ); ?>" value="<?php echo esc_attr( $order_status ); ?>" <?php checked( $checked ); ?> />
							<label for="order_status_<?php echo esc_attr( $order_status ); ?>"><?php echo $name; ?></label>
						</th>

					<?php endforeach; ?>
					<th>
						<?php if ( get_query_var('order_status') ) { ?>
							<a class="button secondary" href="<?php echo esc_url( add_query_arg( 'tab', 'orders', CP_DASHBOARD_ORDERS_URL ) ); ?>"><i class="fa fa-filter" aria-hidden="true"></i> <?php _e( 'Remove Filters', APP_TD ); ?></a>
						<?php } else { ?>
							<button class="button secondary" title="<?php esc_attr_e( 'Filter', APP_TD ); ?>"><i class="fa fa-filter" aria-hidden="true"></i> <?php _e( 'Filter', APP_TD ); ?></button>
						<?php } ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="<?php echo count( $statuses ) + 1; ?>">
						<span><?php printf( __( 'Found: %d', APP_TD ), $orders->found_posts ); ?></span>
					</th>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td colspan="1">
						<?php _e( 'Pending', APP_TD ); ?>
					</td>
					<td colspan="<?php echo count( $statuses ); ?>">
						<span><?php echo __( 'Order not processed.', APP_TD ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<?php _e( 'Failed', APP_TD ); ?>
					</td>
					<td colspan="<?php echo count( $statuses ); ?>">
						<span><?php echo __( 'Order failed or manually canceled.', APP_TD ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<?php _e( 'Completed', APP_TD ); ?>
					</td>
					<td colspan="<?php echo count( $statuses ); ?>">
						<span><?php echo __( 'Order processed succesfully but pending moderation before activation.', APP_TD ); ?></span>
					</td>
				</tr>
				<tr>
					<td colspan="1">
						<?php _e( 'Activated', APP_TD ); ?>
					</td>
					<td colspan="<?php echo count( $statuses ); ?>">
						<span><?php echo __( 'Order processed succesfully and activated.', APP_TD ); ?></span>
					</td>
				</tr>
			</tbody>
		</table>

	</form>

	<?php if ( empty( $orders ) ): ?>

		<p><?php _e( 'No Orders found.', APP_TD ); ?></p>

	<?php else: ?>

		<table class="table-plain table-bootstrap stack text-small">
			<thead>
				<tr>
					<th><?php _e( 'ID', APP_TD ); ?></th>
					<th><?php _e( 'Date', APP_TD ); ?></th>
					<th><?php _e( 'Order Summary', APP_TD ); ?></th>
					<th><?php _e( 'Price', APP_TD ); ?></th>
					<th><?php _e( 'Payment/Status', APP_TD ); ?></th>
				</tr>
			</thead>
			<tbody>

			<?php if ( $orders->have_posts() ) : ?>

				<?php while ( $orders->have_posts() ) : $orders->the_post(); ?>

					<?php $order = appthemes_get_order( $orders->post->ID ); ?>
						<tr>
							<td class="order-history-id">#<?php the_ID(); ?></td>
							<td class="date"><strong><?php the_time(__('j M',APP_TD)); ?></strong><br/><span class="year"><?php the_time(__('Y',APP_TD)); ?></span></td>
							<td class="order-history-summary">
								<span class="order-history-ad"><?php the_order_ad_link( $order ); ?></span>
								<?php echo cp_get_the_order_summary( $order, $output_type = 'html' ); ?>
							</td>
							<td class="order-history-price"><?php echo appthemes_get_price( $order->get_total() ); ?></td>
							<td class="order-history-payment"><?php the_orders_history_payment( $order ); ?></td>
						</tr>

				<?php endwhile; ?>

			<?php else: ?>
				<tr><td colspan="7"><?php _e( 'No Orders found.', APP_TD ); ?></td></tr>
			<?php endif; ?>

			</tbody>

		</table>

		<?php cp_do_pagination( $orders ); ?>

	<?php endif; ?>

<?php else: ?>

	<p><?php _e( 'You don\'t have any Orders, yet.', APP_TD ); ?></p>

<?php endif; ?>

<?php wp_reset_postdata(); ?>
