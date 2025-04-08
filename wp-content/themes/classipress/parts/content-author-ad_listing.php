<?php
/**
 * Author page listings content template.
 *
 * This template is not in use since 4.2.0, left in compat purpose.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

/**
 * Filters the default Author listing args.
 *
 * @since 4.0.0
 *
 * @param array $args The default arguments.
 */
$args = apply_filters( 'cp_author_listing_items_args', array(
	'post_type'           => APP_POST_TYPE,
	'posts_per_page'      => 10,
	'ignore_sticky_posts' => true,
	'author'              => $author->ID,
	'orderby'             => 'rand',
) );

$author_listings = new WP_Query( $args );

if ( ! $author_listings->have_posts() ) {
	return;
}
?>
<script>
jQuery( function( $ ) {
	$( '.author-listings .items-featured' ).slick( {
		adaptiveHeight: false,
		rtl: $('html').attr('dir') === 'rtl',
		slidesToShow: 2,
		slidesToScroll: 2,
		responsive: [ {
		breakpoint: 840,
		settings: {
			slidesToShow: 1,
			slidesToScroll: 1
		}
		} ]
	} )
	.fadeTo( 'slow' , 1 );
} );
</script>

<div class="row author-listings">

	<div class="columns">

		<section>

			<h3 class="section-title"><?php _e( 'My Listings', APP_TD ); ?></h3>

			<div class="items-featured" style="opacity: 0;">

				<?php while ( $author_listings->have_posts() ) : $author_listings->the_post(); ?>

					<?php get_template_part( 'parts/content-item', get_post_type() ); ?>

				<?php endwhile; ?>

			</div> <!-- .items-featured -->

		</section>

	</div> <!-- .columns -->

</div> <!-- .row -->

<?php wp_reset_query(); ?>
