<?php
/**
 * Sidebar Ads template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.2.0
 */
?>

<?php $ads = $instance['items']; ?>

<ul class="recent-ads">

	<?php if ( $ads->have_posts() ) : ?>

		<?php while ( $ads->have_posts() ) : $ads->the_post(); ?>

			<li>

				<div class="media-object">
					<?php if ( ! empty( $instance['show_thumbnail'] ) ) { ?>
						<div class="media-object-section">
							<?php cp_ad_loop_thumbnail( array( 100, 100 ) ); ?>
						</div>
					<?php } ?>

					<div class="media-object-section">

						<div class="recent-ad-content">

							<a class="recent-ad-title" href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_title(); ?></a>

							<ul class="recent-ad-meta">

								<?php if ( ! empty( $instance['show_date'] ) ) { ?>
									<li class="recent-ad-meta-item recent-ad-date">
										<i class="fa fa-calendar-o" aria-hidden="true" title="<?php esc_attr_e( 'Listed', APP_TD ); ?>"></i>
										<?php echo appthemes_date_posted( get_the_date( "Y-m-d H:i:s" ) ); ?>
									</li>
								<?php } ?>

								<?php if ( ! empty( $instance['show_category'] ) ) { ?>
									<li class="recent-ad-meta-item recent-ad-category">
										<i class="fa fa-icon fa-list-ul"></i> <?php echo get_the_term_list( get_the_ID(), APP_TAX_CAT, '', ', ', '' ); ?>
									</li>
								<?php } ?>

								<?php if ( ! empty( $instance['show_location'] ) ) {

									$address_parts = array_filter( array(
										get_post_meta( $post->ID, 'cp_city', true ),
										get_post_meta( $post->ID, 'cp_state', true ),
										get_post_meta( $post->ID, 'cp_country', true ),
									) );

									$make_address = implode( ', ', $address_parts );

									if ( $make_address ) { ?>
										<li class="recent-ad-meta-item recent-ad-location">
											<i class="fa fa-map-marker"></i> <?php echo $make_address; ?>
										</li>
									<?php } ?>
								<?php } ?>

								<?php if ( ! empty( $instance['show_views'] ) ) { ?>
									<li class="recent-ad-meta-item recent-ad-views">
										<i class="fa fa-bar-chart" aria-hidden="true"></i>
										<?php
										$views = appthemes_get_stats_by( get_the_ID(), 'total' );
										printf( _n( '%d view', '%d views', $views, APP_TD ), $views );
										?>
									</li>
								<?php } ?>

								<?php if ( ! empty( $instance['show_price'] ) ) { ?>
									<li class="recent-ad-meta-item recent-ad-price">
										<?php cp_get_price( get_the_ID(), 'cp_price' ); ?>
									</li>
								<?php } ?>

							</ul>

						</div>

					</div>

				</div>

			</li>

		<?php endwhile; ?>

	<?php else : ?>

		<li><?php esc_html_e( 'There are no ads yet.', APP_TD ); ?></li>

	<?php endif; ?>

</ul>