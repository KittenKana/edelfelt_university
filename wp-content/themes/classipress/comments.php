<?php
/**
 * The template for displaying Comments.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */


// Prevent direct file calls
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( post_password_required() ) { ?>

	<p class="nocomments"><?php _e( 'This post is password protected. Enter the password to view comments.', APP_TD ); ?></p>
<?php
	return;
}
?>

<aside id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comments_number = get_comments_number();
			if ( 1 == $comments_number ) {
				/* translators: %s: post title */
				printf( _x( 'One response to &ldquo;%s&rdquo;', 'comments title', APP_TD ), get_the_title() );
			} else {
				printf(
					/* translators: 1: number of comments, 2: post title */
					_nx(
						'%1$s response to &ldquo;%2$s&rdquo;',
						'%1$s responses to &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						APP_TD
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}
			?>
		</h2>

		<?php appthemes_before_comments( get_post_type() ); ?>

		<ol class="comment-list">

			<?php cp_list_comments(); ?>

		</ol><!-- .comment-list -->

		<?php appthemes_after_comments( get_post_type() ); ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">

				<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', APP_TD ); ?></h2>

				<div class="nav-links">
					<div class="row">
						<div class="nav-previous small-6 columns"><?php previous_comments_link( esc_html__( '&larr; Older Comments', APP_TD ) ); ?></div>
						<div class="nav-next small-6 columns text-right"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', APP_TD ) ); ?></div>
					</div> <!-- .row -->
				</div><!-- .nav-links -->

			</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

		<?php appthemes_before_pings( get_post_type() ); ?>

		<?php $carray = separate_comments( $comments ); // get the comments array to check for pings ?>

		<?php if ( ! empty( $carray['pings'] ) ) : // pings include pingbacks & trackbacks ?>

			<h2 class="comments-title" id="pings"><?php _e( 'Trackbacks/Pingbacks', APP_TD ); ?></h2>

			<ol class="pinglist">

				<?php cp_list_pings(); ?>

			</ol>

		<?php endif; ?>

		<?php appthemes_after_pings( get_post_type() ); ?>

	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', APP_TD ); ?></p>

	<?php elseif ( comments_open() ) : ?>

		<?php appthemes_before_respond( get_post_type() ); ?>

		<?php appthemes_before_comments_form( get_post_type() ); ?>

		<?php cp_main_comment_form(); ?>

		<?php appthemes_after_comments_form( get_post_type() ); ?>

		<?php appthemes_after_respond( get_post_type() ); ?>

	<?php endif; ?>

</aside><!-- .comments-area -->
