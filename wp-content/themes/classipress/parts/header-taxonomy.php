<?php
/**
 * The template for displaying a generic taxonomy header.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.2.0
 */

?>

<header class="page-header row columns">
	<?php $the_term = get_queried_object(); ?>
	<a class="fa-icon fa-rss rss-link-icon" href="<?php echo esc_url( get_term_feed_link( $the_term->term_id, $taxonomy ) ); ?>" title="<?php echo esc_attr( sprintf( __( '%s RSS Feed', APP_TD ), $the_term->name ) ); ?>">
		<span class="screen-reader-text"><?php printf( __( 'RSS Feed for ad tag %s', APP_TD ), $the_term->name ); ?></span>
	</a>
	<h1 class="h4">
		<?php
		if ( is_tax( APP_TAX_TAG ) ) {
			$term_title = sprintf( _x( 'Listings tagged with "%1$s" (%2$d)', 'Listings tagged with {TERM NAME} ({NUMBER})', APP_TD ), $the_term->name, $wp_query->found_posts );
		} else {
			$term_title = sprintf( _x( 'All Ads in %1$s (%2$d)', 'All Ads in {TERM NAME} ({NUMBER})', APP_TD ), $the_term->name, $wp_query->found_posts );
		}
		echo esc_html( apply_filters( 'cp_single_term_title', $term_title, $the_term ) );
		?>
	</h1>
	<?php
	$page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<div class="page-count pull-right"><?php printf( __( 'Page %1$s of %2$s', APP_TD ), number_format_i18n( $page_num ), number_format_i18n( $wp_query->max_num_pages ) ); ?></div>
		<?php
	endif;
	?>
</header><!-- .page-header -->

<?php
the_archive_description( '<div class="taxonomy-description row columns">', '</div>' );
