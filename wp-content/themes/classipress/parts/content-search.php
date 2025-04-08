<?php
/**
 * Search loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'content-wrap', 'post-search' ) ); ?> role="article">

	<?php if ( has_post_thumbnail() ) : ?>
		<div <?php echo apply_filters( 'cp_background_cover', 'entry-header entry-cover no-gradient' ); ?>></div>
	<?php endif; ?>

	<div class="content-inner">

		<header class="entry-header">
			<?php the_title( sprintf( '<h3 class="entry-title"><a href="%1$s" title="%2$s" rel="bookmark">', esc_url( get_permalink() ), esc_attr( get_the_title() ) ), '</a></h3>' ); ?>
		</header>

		<div class="entry-content">
			<?php echo cp_strimwidth( strip_tags( strip_shortcodes( get_the_content() ) ), 0, 400, '&hellip;' ); ?>
		</div> <!-- .entry-content -->

		<footer class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="button button-small"><?php _e( 'Read More', APP_TD ); ?> <span class="screen-reader-text">
			<?php /* translators: Read more about %title% */ ?>
			<?php printf( __( 'about %s', APP_TD ), get_the_title() ); ?>
			</span></a>
		</footer><!-- .entry-footer -->

	</div> <!-- .content-inner -->

</article> <!-- .content-wrap -->
