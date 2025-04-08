<?php
/**
 * Edit profile content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

$current_user   = wp_get_current_user();
$last_updated   = get_user_meta( $current_user->ID, 'last_update', true );
$show_avatar    = get_option( 'show_avatars' );
?>

<article id="user-<?php esc_attr_e( $current_user->ID ); ?>" class="user-profile-edit content-main">

	<header class="entry-header">
		<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
	</header>

	<div class="entry-content">
		<?php the_content(); ?>
	</div> <!-- .entry-content -->

	<?php if ( '' != $last_updated ) : ?>
	<div class="profile-last-updated"><?php _e( 'Updated:', APP_TD ); ?> <?php echo appthemes_display_date( $last_updated ); ?></div>
	<?php endif; ?>

	<form id="profile" class="app-form" name="profile" action="" method="post" enctype="multipart/form-data">

		<?php wp_nonce_field( 'app-edit-profile' ); ?>

		<div class="content-wrap">

			<div class="content-inner">

				<div class="view-profile-link"><a class="small button" href="<?php echo esc_url( get_author_posts_url( $current_user->ID ) ); ?>" target="_blank"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;<?php _e( 'View profile', APP_TD ); ?></a></div>
				<h3><?php _e( 'General', APP_TD ); ?></h3>

				<div class="row">

					<div class="medium-<?php echo $show_avatar ? 6 : 12; ?> columns">

						<label>
							<?php _e( 'Username', APP_TD ); ?>
							<input type="text" name="user_login" id="user_login" value="<?php echo esc_attr( $current_user->user_login ); ?>" disabled />
						</label>
						<p class="help-text"><?php _e( 'Usernames cannot be changed.', APP_TD ); ?></p>

					</div>

					<?php if ( $show_avatar ) { ?>
						<div class="medium-6 columns">
							<div class="avatar-wrap">
								<?php echo cp_get_avatar( $current_user->ID, 140 ); ?>
								<span class="change-avatar text-small"><a href="<?php echo esc_url( __( 'https://en.gravatar.com/', APP_TD ) ); ?>" target="_blank"><?php _e( 'Change avatar', APP_TD ); ?></a></span>
								<?php echo cp_tooltip_icon( __( 'You can change your profile picture on Gravatar.com.', APP_TD ) ); ?>
							</div>
						</div>
					<?php } ?>

				</div><!-- .row -->

				<div class="row">

					<div class="medium-6 columns">
						<label>
							<?php _e( 'First Name', APP_TD ); ?>
							<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" />
						</label>
					</div>

					<div class="medium-6 columns">
						<label>
							<?php _e( 'Last Name', APP_TD ); ?>
							<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>" />
						</label>
					</div>

				</div><!-- .row -->

				<div class="row">

					<div class="medium-6 columns">
						<label>
							<?php _e( 'Nickname', APP_TD ); ?>
							<input type="text" name="nickname" id="nickname" value="<?php echo esc_attr( $current_user->nickname ); ?>" />
						</label>
					</div>

					<div class="medium-6 columns">
						<label>
							<?php _e( 'Display Name', APP_TD ); ?>
							<select name="display_name" class="regular-dropdown" id="display_name">
							<?php foreach ( appthemes_get_user_profile_display_name_options() as $id => $item ) { ?>
								<option id="<?php echo esc_attr( $id ); ?>" value="<?php echo esc_attr( $item ); ?>" <?php selected( $current_user->display_name, $item ); ?>><?php echo esc_attr( $item ); ?></option>
							<?php } ?>
							</select>
						</label>
					</div>

				</div><!-- .row -->

				<label>
					<?php _e( 'Email', APP_TD ); ?>
					<input type="text" name="email" id="email" class="required email" value="<?php echo esc_attr( $current_user->user_email ); ?>" />
				</label>

				<label>
					<?php _e( 'Website', APP_TD ); ?>
					<input type="url" name="url" id="url" value="<?php echo esc_attr( $current_user->user_url ); ?>" />
				</label>

				<?php
				appthemes_media_manager(
					$current_user->ID,
					array(
						'id'            => 'cover_image',
						'object'        => 'user',
						'title'         => __( 'Header Cover Image', APP_TD ),
						'upload_text'   => __( 'Choose File', APP_TD ),
						'no_media_text' => '<div class="text-muted text-small">' . __( 'No media added yet', APP_TD ) . '</div>'
					),
					array(
						'mime_types'  => 'Image',
						'file_limit'  => 1,
						'embed_limit' => 0,
						'file_size'   => wp_max_upload_size(),
						'delete_files' => true,
					)
				);
				?>

			</div> <!-- .content-inner -->

		</div> <!-- .content-wrap -->

		<div class="content-wrap">

			<div class="content-inner">

				<h3><?php _e( 'Social', APP_TD ); ?></h3>

				<div class="row social-edit-profile">

					<?php
					foreach ( wp_get_user_contact_methods() as $name => $desc ) {

						// Only show allowed social networks.
						if ( in_array( $name, cp_get_allowed_user_networks() ) ) {
							$value = $current_user->$name;

							// Backward compatibility with legacy names.
							if ( ! $value && $current_user->{$name.'_id'} ) {
								$value = $current_user->{$name.'_id'};
							}
						?>
							<div class="medium-6 columns">
								<label for="<?php esc_attr_e( $name ); ?>">
									<?php echo apply_filters( "user_{$name}_label", $desc ); ?>
									<div class="input-group">
										<span class="input-group-label text-muted fa-icon fa-<?php esc_attr_e( $name ); ?>"></span>
										<input type="text" name="<?php esc_attr_e( $name ); ?>" id="<?php esc_attr_e( $name ); ?>" class="input-group-field" value="<?php esc_attr_e( $value ); ?>" />
									</div><!-- .input-group -->
								</label>
							</div><!-- .columns -->
						<?php
						}
					}
					?>

				</div><!-- .row -->

			</div> <!-- .content-inner -->

		</div> <!-- .content-wrap -->


		<div class="content-wrap">

			<div class="content-inner">

				<h3><?php _e( 'About Me', APP_TD ); ?></h3>

					<label>
						<?php _e( 'Description', APP_TD ); ?>
						<textarea name="description" id="description" rows="5"><?php echo $current_user->description; ?></textarea>
					</label>

				</div> <!-- .content-inner -->

			</div> <!-- .content-wrap -->

		<?php if ( $show_password_fields = apply_filters( 'show_password_fields', true, $current_user ) ) : ?>

			<div class="content-wrap">

				<div class="content-inner">

					<h3><?php _e( 'Account Management', APP_TD ); ?></h3>
					<p><?php _e( 'Auto-generate a new strong password or enter your own custom one.', APP_TD ); ?></p>

					<div id="password" class="user-pass1-wrap">

						<input class="hidden" value=" " /><!-- #24364 workaround -->
						<button type="button" class="button warning sm-full-width wp-generate-pw hide-if-no-js"><i class="fa fa-key" aria-hidden="true"></i> <?php _e( 'Generate New Password', APP_TD ); ?></button>

						<div class="wp-pwd hide-if-js">

							<div class="input-group">

								<span class="password-input-wrapper">
									<input type="password" name="pass1" id="pass1" class="input-group-field" value="" autocomplete="off" data-pw="<?php echo esc_attr( wp_generate_password( 24 ) ); ?>" aria-describedby="pass-strength-result" />
								</span>
								<div class="input-group-button">
									<button type="button" class="button wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="<?php esc_attr_e( 'Cancel password change', APP_TD ); ?>"><?php _e( 'Cancel', APP_TD ); ?></button>
								</div><!-- .input-group-button -->

							</div><!-- .input-group -->

							<div style="display:none" id="pass-strength-result" aria-live="polite"></div>

						</div><!-- .wp-pwd .hide-if-js -->

					</div><!-- .user-pass1-wrap -->

					<div class="user-pass2-wrap hide-if-js">

						<label class="col-sm-2 control-label" for="pass2"><?php _e( 'Password Again', APP_TD ); ?>
							<input type="password" name="pass2" id="pass2" value="" autocomplete="off">
						</label>

					</div><!-- .user-pass2-wrap -->

				</div> <!-- .content-inner -->

			</div> <!-- .content-wrap -->

		<?php endif; ?>

		<?php
		// Wrap only if there is something to wrap.
		if ( has_action( 'profile_personal_options' ) || has_action( 'show_user_profile' ) ) {
			?>
			<div class="content-wrap">

				<div class="content-inner">

					<?php
						do_action( 'profile_personal_options', $current_user );
						do_action( 'show_user_profile', $current_user );
					?>

				</div> <!-- .content-inner -->

			</div> <!-- .content-wrap -->
			<?php
		}
		?>

		<input type="submit" class="button sm-full-width" value="<?php _e( 'Update Profile', APP_TD ); ?>">

		<?php
		// Need to pass in these values otherwise they get blown away in wp-admin/profile.php.
		if ( ! empty( $current_user->rich_editing ) ) { ?>
			<input type="hidden" name="rich_editing" value="<?php esc_attr_e( $current_user->rich_editing ); ?>" />
		<?php } ?>
		<?php if ( ! empty( $current_user->admin_color ) ) { ?>
			<input type="hidden" name="admin_color" value="<?php esc_attr_e( $current_user->admin_color ); ?>" />
		<?php } ?>
		<?php if ( ! empty( $current_user->comment_shortcuts ) ) { ?>
			<input type="hidden" name="comment_shortcuts" value="<?php esc_attr_e( $current_user->comment_shortcuts ); ?>" />
		<?php } ?>
		<input type="hidden" name="admin_bar_front" value="<?php esc_attr_e( get_user_option( 'show_admin_bar_front', $current_user->ID ) ); ?>" />

		<input type="hidden" name="from" value="profile" />
		<input type="hidden" name="action" value="app-edit-profile" />
		<input type="hidden" name="user_id" id="user_id" value="<?php esc_attr_e( $current_user->ID ); ?>" />
	</form>

</article>
