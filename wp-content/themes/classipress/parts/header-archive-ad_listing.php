<?php
/**
 * The template for displaying a Ad Listing archive header.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.2.3
 */

?>

<header class="page-header row columns">
	<h1 class="h4">
		<?php
		if ( is_search() ) {
			$archive_title = sprintf( __( '%d %s for "%s"', APP_TD ), $wp_query->found_posts, _n( 'result', 'results', number_format_i18n( $wp_query->found_posts ), APP_TD ), get_search_query() );
		} else {
			$archive_title = get_post_type_labels( get_post_type_object( APP_POST_TYPE ) )->all_items;
		}
		echo esc_html( apply_filters( 'cp_ad_listing_archive_title', $archive_title ) );
		?>
	</h1>
	<?php
	$page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	if ( $wp_query->max_num_pages > 1 ) :
		?>
		<div class="page-count pull-right"><?php printf( __( 'Page %1$s of %2$s', APP_TD ), number_format_i18n( $page_num ), number_format_i18n( $wp_query->max_num_pages ) ); ?></div>
		<?php
	endif;
	?>
</header><!-- .page-header -->

<?php
if ( ! is_search() ) {
	the_archive_description( '<div class="taxonomy-description row columns">', '</div>' );
}
