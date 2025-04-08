<?php
/**
 * Custom post loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'post-item', 'content-wrap' ) ); ?> role="article">

	<a class="entry-thumbnail" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Thumbnail image for %s', APP_TD ), get_the_title() ) ); ?>" aria-hidden="true">
		<div <?php echo apply_filters( 'cp_background_cover', 'item-cover entry-cover', array( 'size' => 'large' ) ); ?>>
			<span class="screen-reader-text"><?php the_title(); ?></span>
		</div>
	</a>

	<div class="content-inner">

		<header class="entry-header">

			<?php appthemes_before_post_title( get_post_type() ); ?>

			<?php
			if ( ( is_archive( 'post' ) || is_home() ) && ! is_author() ) {
				the_title( sprintf( '<h2 class="h4 entry-title"><a href="%1$s" title="%2$s" rel="bookmark">', esc_url( get_permalink() ), esc_attr( get_the_title() ) ), '</a></h2>' );
			} else {
				the_title( sprintf( '<h3 class="h4 entry-title"><a href="%1$s" title="%2$s" rel="bookmark">', esc_url( get_permalink() ), esc_attr( get_the_title() ) ), '</a></h3>' );
			}
			?>

			<?php appthemes_after_post_title( get_post_type() ); ?>

		</header>

		<?php appthemes_before_post_content( get_post_type() ); ?>

		<div class="entry-content subheader">
			<?php echo cp_strimwidth( strip_tags( strip_shortcodes( get_the_content() ), '<p>' ), 0, 1000, '&hellip;' ); ?>
		</div> <!-- .entry-content -->

		<?php appthemes_after_post_content( get_post_type() ); ?>

		<a class="button small hollow" href="<?php the_permalink(); ?>"><?php _e( 'Read more', APP_TD ); ?> <span class="screen-reader-text">
			<?php /* translators: Read more about %title% */ ?>
			<?php printf( __( 'about %s', APP_TD ), get_the_title() ); ?>
			</span></a>

	</div> <!-- .content-inner -->

	<footer class="entry-footer">

		<ul class="meta-list list-inline">
			<li class="post-author"><?php echo cp_get_avatar( get_the_author_meta( 'user_email' ), 24 ); ?></li>
			<li class="post-time post_date date updated"><?php echo get_the_date(); ?></li>
			<li class="post-comments fa-icon fa-comments"><span class="comments-count"><?php echo get_comments_number(); ?></span></li>
		</ul><!-- .meta-list -->

	</footer>

</article>