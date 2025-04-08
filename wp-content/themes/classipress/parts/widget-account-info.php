<?php
/**
 * Template to display widget "ClasiPress - Account Info".
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */
global $cp_options;
$current_user = wp_get_current_user();
?>

<div class="listing-owner">
	<div class="listing-owner-avatar">
			<?php echo cp_get_avatar( $current_user->ID, 125, '', '', array( 'class' => 'img-circle' ) ); ?>
	</div>

	<div class="listing-owner-info text-center">
		<h3 class="listing-owner-name"><a href="<?php echo esc_url( get_author_posts_url( $current_user->ID ) ); ?>"><?php echo $current_user->display_name ?></a></h3>
	</div>

	<hr />

	<div class="entry-location text-muted">

		<ul>
			<li><i class="fa fa-user-circle-o" title="<?php esc_html_e( 'Member Since', APP_TD ); ?>"></i> <small><?php echo appthemes_display_date( $current_user->user_registered, 'datetime', true ); ?></small></li>
			<li><i class="fa fa-key" title="<?php esc_html_e( 'Last Login', APP_TD ); ?>"></i> <small><?php echo appthemes_get_last_login( $current_user->ID ); ?></small></li>
		</ul>

	</div><!-- .entry-location -->

	<?php if ( $cp_options->enable_membership_packs ) { ?>
	<hr />

	<div class="text-center">
		<h3><?php _e( 'Membership', APP_TD ); ?></h3>
	</div>

	<?php $membership = cp_get_membership_package( $current_user->active_membership_pack ); ?>
	<div class="text-muted">
		<?php if ( $membership && ( appthemes_days_between_dates( $current_user->membership_expires ) < 0 ) ) { ?>
			<p><i class="fa fa-address-card-o" title="<?php esc_html_e( 'Membership Pack', APP_TD ); ?>"></i>&nbsp;&nbsp;<small><?php echo $membership->pack_name; ?></small></p>
			<p><i class="fa fa-clock-o" title="<?php esc_html_e( 'Membership Expires', APP_TD ); ?>"></i>&nbsp;&nbsp;<small class="expired-date"><?php echo appthemes_display_date( $current_user->membership_expires ); ?></small></p>
			<p><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>" class="button hollow expanded" title="<?php esc_html_e( 'Renew Membership Pack', APP_TD ); ?>"><i class="fa fa-refresh"></i> <?php _e( 'Renew', APP_TD ); ?></a></p>
		<?php } elseif( $membership ) { ?>
			<p><i class="fa fa-address-card-o" title="<?php esc_html_e( 'Membership Pack', APP_TD ); ?>"></i>&nbsp;&nbsp;<small><?php echo $membership->pack_name; ?></small></p>
			<p><i class="fa fa-clock-o" title="<?php esc_html_e( 'Membership Expires', APP_TD ); ?>"></i>&nbsp;&nbsp;<small><?php echo appthemes_display_date( $current_user->membership_expires ); ?></small></p>
			<p><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>" class="button hollow expanded" title="<?php esc_html_e( 'Renew or Extend Your Membership Pack', APP_TD ); ?>"><i class="fa fa-arrow-circle-o-up"></i> <?php _e( 'Extend', APP_TD ); ?></a></p>
		<?php } else { ?>
			<p><a href="<?php echo CP_MEMBERSHIP_PURCHASE_URL; ?>" class="button hollow expanded" title="<?php esc_html_e( 'Purchase a Membership Pack', APP_TD ); ?>"><i class="fa fa-arrow-circle-o-up"></i> <?php _e( 'Purchase', APP_TD ); ?></a></p>
		<?php } ?>
	</div>
	<?php } ?>

	<?php cp_author_info( 'sidebar-user' ); ?>

</div>
