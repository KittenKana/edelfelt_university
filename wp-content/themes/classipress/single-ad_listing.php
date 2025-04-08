<?php
/**
 * The Template for displaying all single ads.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.0
 */
$sidebar_position  = get_theme_mod( 'listing_sidebar_position', 'right' );
$show_banner       = get_theme_mod( 'show_listing_banner_image', 1 );
$full_width_banner = get_theme_mod( 'listing_banner_full_width', 1 );
appthemes_before_loop( get_post_type() );

if ( have_posts() ) :

	while ( have_posts() ) : the_post(); ?>

		<main role="main">

			<?php
			if ( ! $show_banner || ! $full_width_banner ) {
				get_template_part( 'searchbar' );
			}
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'content-main' ) ); ?>>

				<?php
				if ( $show_banner && $full_width_banner ) {
					get_template_part( 'parts/content-cover', get_post_type() );
				}
				?>

				<div id="primary" class="content-area row">
                    
					<div class="columns">
                      
						<?php get_template_part( 'parts/breadcrumbs', app_template_base() ); ?>

					</div>

					<?php if ( 'left' === $sidebar_position ) { get_sidebar( 'ad' ); } ?>

					<div id="main" class="site-main <?php echo ( 'none' == $sidebar_position ) ? 'medium-10 medium-centered' : 'm-large-7 large-8' ; ?> columns">

						<?php appthemes_notices(); ?>

						<?php if ( $show_banner && ! $full_width_banner ) { ?>

							<div class="content-wrap">
								<?php get_template_part( 'parts/content-cover', get_post_type() ); ?>
							</div>

						<?php } ?>

						<?php
						appthemes_before_post( get_post_type() );

						get_template_part( 'parts/content', get_post_type() );

						appthemes_after_post( get_post_type() );
						?>

					</div>

					<?php if ( 'right' === $sidebar_position ) { get_sidebar( 'ad' ); } ?>

				</div> <!-- #primary -->

			</article> <!-- #post-ID -->

		</main>
		<?php

	endwhile;

	appthemes_after_endwhile( get_post_type() );

else:

	appthemes_loop_else( get_post_type() );

	get_template_part( 'parts/content-none', get_post_type() );

endif;

appthemes_after_loop( get_post_type() );