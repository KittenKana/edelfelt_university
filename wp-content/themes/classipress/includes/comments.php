<?php
/**
 * Comment functions.
 *
 * @package ClassiPress\Comments
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */

/**
 * Custom callback to list comments.
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 *
 * @return void
 */
function cp_custom_comment( $comment, $args, $depth ) {
	global $post;
	$GLOBALS['comment'] = $comment;
	$GLOBALS['comment_depth'] = $depth;

	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
?>
			<li class="pingback">
				<?php comment_author_link(); ?>
<?php
			break;
		default :
?>

			<li id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? 'parent' : '' ); ?>>

				<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

					<footer class="comment-meta">
						<div class="comment-author">
							<?php echo cp_get_avatar( $comment, $args['avatar_size'] ); ?>
							<?php printf( __( '%s', APP_TD ), sprintf( '<b class="fn">%s</b>', get_comment_author_link() ) ); ?>
							<?php
							if ( $comment->user_id === $post->post_author ) :
								$comment_badge = ( APP_POST_TYPE == $post->post_type ? __( 'listing owner', APP_TD ) : __( 'author', APP_TD ) );
							?>
								<span class="label success comment-badge <?php esc_attr_e( $comment_badge ); ?>"><?php echo $comment_badge; ?></span>
							<?php endif; ?>
						</div> <!-- .comment-author -->

						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
								<time title="<?php esc_attr( printf( _x( '%1$s @ %2$s', '1: date, 2: time', APP_TD ), get_comment_date(), get_comment_time() ) ); ?>">
									<?php printf( _x( '%s ago', '%s = human-readable time difference', APP_TD ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?>
								</time>
							</a>
							<?php edit_comment_link( __( 'Edit', APP_TD ), '<span class="edit-link">', '</span>' ); ?>
						</div> <!-- .comment-metadata -->

						<?php if ( '0' == $comment->comment_approved ) : ?>
							<div class="alert alert-info">
								<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', APP_TD ); ?></p>
							</div>
						<?php endif; ?>

					</footer> <!-- .comment-meta -->

					<div class="comment-content">
						<?php comment_text(); ?>
					</div> <!-- .comment-content -->

					<?php
					comment_reply_link( array_merge( $args, array(
						'reply_text'    => __( 'Reply', APP_TD ),
						'reply_to_text' => __( 'Reply to %s', APP_TD ),
						'login_text'    => __( 'Log in to reply.', APP_TD ),
						'add_below'     => 'div-comment',
						'depth'         => $depth,
						'max_depth'     => $args['max_depth'],
						'before'        => '<div class="reply">',
						'after'         => '</div>',
					) ) );
					?>

				</article> <!-- .comment-body -->

<?php
				break;
		endswitch;
}


/**
 * Displays comments.
 *
 * @return void
 */
function cp_list_comments() {
	wp_list_comments( array(
		'avatar_size' => 60,
		'style'       => 'ol',
		'type'        => 'comment',
		'callback'    => 'cp_custom_comment',
	) );
}


/**
 * Displays pings.
 *
 * @return void
 */
function cp_list_pings() {
	wp_list_comments( array( 'callback' => 'cp_custom_comment', 'type' => 'pings' ) );
}


/**
 * Displays main comments form.
 *
 * @return void
 */
function cp_main_comment_form() {
	comment_form();
}


/**
 * Displays commenter link.
 *
 * @return void
 */
function commenter_link() {
	$commenter = get_comment_author_link();

	if ( strstr( ']* class=[^>]+>', $commenter ) ) {
		$commenter = str_replace( '(]* class=[\'"]?)', '\\1url ' , $commenter );
	} else {
		$commenter = str_replace( '(<a )/', '\\1class="url "' , $commenter );
	}

	echo $commenter;
}


/**
 * Displays commenter avatar.
 *
 * @return void
 */
function commenter_avatar() {
	$avatar_email = get_comment_author_email();
	$avatar = str_replace( 'class="avatar', 'class="photo avatar', cp_get_avatar( $avatar_email, 140 ) );

	echo $avatar;
}

/**
 * Use custom comment form arguments.
 *
 * This is so we can use Foundation markup and include
 * text for the .pot file vs using WordPress .pot
 *
 * @since 4.0.0
 *
 * @param array $defaults The default comment form arguments.
 */
function cp_comment_form_defaults( $defaults ) {

	$post_id       = get_the_ID();
	$user          = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$defaults = array(
		'comment_field'        => '<div class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun', APP_TD ) . '</label> <textarea id="comment" name="comment" cols="45" rows="8" aria-describedby="form-allowed-tags" aria-required="true" required="required"></textarea></div>',
		'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', APP_TD ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s">Log out?</a>', APP_TD ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'class_submit'         => 'button',
		'name_submit'          => 'submit',
		'title_reply'          => __( 'Leave a Reply', APP_TD ),
		'title_reply_to'       => __( 'Leave a Reply to %s', APP_TD ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_before'  => ' <small>',
		'cancel_reply_after'   => '</small>',
		'cancel_reply_link'    => __( 'Cancel reply', APP_TD ),
		'label_submit'         => __( 'Post Comment', APP_TD ),
		'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
		'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
		'format'               => 'html5',
	);

	return $defaults;
}
add_filter( 'comment_form_defaults', 'cp_comment_form_defaults' );


/**
 * Setup the comment form fields.
 *
 * @since 4.0.0
 *
 * @param array $fields The default comment fields.
 */
function cp_comment_fields( $fields ) {

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	$html_req  = ( $req ? " required='required'" : '' );

	$fields    = array(
		'author' => '<div class="comment-form-author"><label for="author">' . __( 'Name', APP_TD ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" class="regular-text required" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . $html_req . ' /></div>',
		'email'  => '<div class="comment-form-email"><label for="email">' . __( 'Email', APP_TD ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" class="regular-text required email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-describedby="email-notes"' . $aria_req . $html_req  . ' /></div>',
		'url'    => '<div class="comment-form-url"><label for="url">' . __( 'Website', APP_TD ) . '</label><input id="url" class="regular-text" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '"></div>',
	);

	return $fields;
}
add_filter( 'comment_form_default_fields', 'cp_comment_fields' );

/**
 * Filters the comment reply link.
 *
 * All this just to change the class names for Foundation styling!
 *
 * @since 4.0.0
 *
 * @param string  $link    The HTML markup for the comment reply link.
 * @param array   $args    An array of arguments overriding the defaults.
 * @param object  $comment The object of the comment being replied.
 * @param WP_Post $post    The WP_Post object.
 */
function cp_comment_reply_link( $link, $args, $comment, $post ) {

	if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) {
		$link = sprintf( '<a rel="nofollow" class="comment-reply-login button small hollow" href="%s">%s</a>',
			esc_url( wp_login_url( get_permalink() ) ),
			$args['login_text']
		);
	} else {
		$onclick = sprintf( 'return addComment.moveForm( "%1$s-%2$s", "%2$s", "%3$s", "%4$s" )',
			$args['add_below'], $comment->comment_ID, $args['respond_id'], $post->ID
		);

		$link = sprintf( "<a rel='nofollow' class='comment-reply-link button small hollow' href='%s' onclick='%s' aria-label='%s'>%s</a>",
			esc_url( add_query_arg( 'replytocom', $comment->comment_ID, get_permalink( $post->ID ) ) ) . "#" . $args['respond_id'],
			$onclick,
			esc_attr( sprintf( $args['reply_to_text'], $comment->comment_author ) ),
			$args['reply_text']
		);
	}

	return $link;
}
add_filter( 'comment_reply_link', 'cp_comment_reply_link', 10, 4 );

