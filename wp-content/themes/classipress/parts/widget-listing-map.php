<?php
/**
 * Template to display widget Single Listing Map.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0 Migrated template from 'includes/sidebar-gmap.php'
 */

?>

<?php if ( APP_Map_Provider_Registry::get_active_map_provider() ) { ?>
	<div id="<?php echo esc_attr( $instance['widget_id'] ); ?>-canvas" class="listing-map"></div>

	<hr />
<?php } ?>

<div class="entry-location listing-map-data text-muted" <?php echo apply_filters( 'cp_listing_data_attributes', '' ); ?>>

	<ul>
		<?php if ( $instance['address'] && $instance['address_link'] ) : ?>
			<li class="listing-address"><i class="fa fa-map-marker"></i> <div class="listing-address-wrap"><?php echo $instance['address_link']; ?></div></li>
		<?php endif; ?>

		<?php /* if ( $instance['phone'] ) : ?>
		<li class="listing-phone"><i class="fa fa-phone"></i> <a href="tel:<?php echo esc_attr( va_phone_url( get_post_meta( get_the_ID(), 'phone', true ) ) ); ?>" class="text-muted"><?php echo esc_html( get_post_meta( get_the_ID(), 'phone', true ) ); ?></a></li>
		<?php endif; ?>

		<?php if ( $instance['website'] ) : ?>
			<?php if ( $website = get_post_meta( get_the_ID(), 'website', true ) ) { ?>
				<li id="listing-website"><i class="fa fa-globe"></i> <a href="<?php echo esc_url( $website ); ?>" target="_blank"><?php _e( 'Visit Website', APP_TD ); ?></a></li>
			<?php } ?>
		<?php endif; ?>

		<?php if ( $instance['email'] ) : ?>
			<?php if ( $email = get_post_meta( get_the_ID(), 'email', true ) ) { ?>
				<li id="listing-email"><i class="fa fa-envelope-o"></i> <a href="mailto:<?php echo antispambot( $email ); ?>" target="_blank"><?php echo antispambot( $email ); ?></a></li>
			<?php } ?>
		<?php endif; */ ?>

		<?php if ( $instance['directions'] && $instance['is_geo_coded'] ) : ?>
			<li id="listing-directions"><i class="fa fa-exchange"></i> <a href="<?php echo esc_url( $dir_url = add_query_arg( array( 'daddr' => urlencode( $instance['geo_values']['lat'] . ',' . $instance['geo_values']['lng'] ) ), 'https://maps.google.com/maps' ) ); ?>" target="_blank" rel="nofollow"><?php _e( 'Get Directions', APP_TD ); ?></a></li>
		<?php endif; ?>

		<?php do_action( 'cp_display_listing_contact_fields', get_the_ID() ); ?>
	</ul>

</div><!-- .entry-location -->

<?php /*if ( $instance['social'] ) : ?>
	<?php if ( $social_networks = va_get_available_listing_networks( true ) ) { ?>
		<hr />
		<ul class="social-icons">
			<?php foreach ( $social_networks as $social_network => $account ) { ?>
			<li>
				<a href="<?php echo va_get_social_account_url( $social_network, $account ); ?>" title="<?php esc_attr_e( va_get_social_network_title( $social_network ) ); ?>" target="_blank" class="fa-icon fa-<?php esc_attr_e( $social_network ); ?>"></a>
			</li>
			<?php } ?>
		</ul>
	<?php } ?>
<?php endif; */
