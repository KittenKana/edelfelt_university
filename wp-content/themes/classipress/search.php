<?php
/**
 * Search results template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

$sidebar_position = get_theme_mod( 'blog_sidebar_position', 'right' );

// If search is for listings, use a different template.
if ( isset( $_GET[ 'st' ] ) && APP_POST_TYPE === $_GET[ 'st' ] ) {
	return appthemes_load_template( 'archive-ad_listing.php' );
}
?>

<div id="primary" class="content-area row">

	<div class="columns">

		<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

	</div>

	<?php if ( 'left' === $sidebar_position ) { get_sidebar( 'blog' ); } ?>

	<main id="main" class="site-main <?php echo ( 'none' == $sidebar_position ) ? 'medium-10 medium-centered' : 'm-large-8'; ?> columns" role="main">

		<section>

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( '%d %s for "%s"', APP_TD ), $wp_query->found_posts, _n( 'result', 'results', number_format_i18n( $wp_query->found_posts ), APP_TD ), get_search_query() ); ?></h1>
				</header><!-- .page-header -->

				<?php appthemes_before_loop( get_post_type() ); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'parts/content-search', get_post_type() ); ?>

				<?php endwhile; ?>

				<?php cp_do_pagination(); ?>

			<?php else : ?>

				<?php get_template_part( 'parts/content', 'none' ); ?>

			<?php endif;

			appthemes_after_loop( get_post_type() );
			?>

		</section>

	</main>

	<?php if ( 'right' == $sidebar_position ) { get_sidebar( 'blog' ); } ?>

</div> <!-- #primary -->
