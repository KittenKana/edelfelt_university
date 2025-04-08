<?php
/**
 * Custom listing loop content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

$featured = cp_display_style( 'featured', false );

// Different layout based on view.
// Pass in anything for the layout query var to use list layout.
$display = ( 'grid' !== get_query_var( 'listing_layout' ) && ( get_query_var( 'listing_layout' ) || is_archive() || is_post_type_archive( APP_POST_TYPE ) || is_search() || is_tax( array( APP_TAX_CAT, APP_TAX_TAG ) ) ) ) ? array( 'list', 5, 7 ) : array( 'grid', 12, 12 );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'listing-item', 'listing-map-data', 'content-wrap', 'display-' . $display[0], $featured ) ); ?> <?php echo apply_filters( 'cp_listing_data_attributes', '', false ); ?> role="article">

	<div class="row">

		<div class="small-12 medium-<?php echo $display[1]; ?> columns">

			<a class="entry-thumbnail" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Thumbnail image for %s', APP_TD ), get_the_title() ) ); ?>" aria-hidden="true">
				<div <?php echo apply_filters( 'cp_background_cover', 'item-cover entry-cover', array( 'size' => 'large' ) ); ?>>
					<span class="screen-reader-text"><?php the_title(); ?></span>
				</div>
			</a>

		</div> <!-- .columns -->

		<div class="small-12 medium-<?php echo $display[2]; ?> columns">

			<div class="content-inner">

				<header class="entry-header">
					<?php
					/**
					 * Fires in the listing item head.
					 *
					 * @since 4.0.0
					 */
					do_action( 'cp_listing_item_head' );
					?>
				</header>

				<div class="entry-header">

					<div class="listing-meta">
						<?php
						/**
						 * Fires in the listing item meta.
						 *
						 * @since 4.0.0
						 */
						do_action( 'cp_listing_item_meta' );
						?>
					</div>

				</div> <!-- .entry-content -->

				<div class="entry-content subheader">

					<?php
					/**
					 * Fires in the listing item meta.
					 *
					 * @since 4.0.0
					 */
					do_action( 'cp_listing_item_content' );
					?>

				</div> <!-- .entry-content -->

				<footer class="entry-footer">
					
					<?php
					/**
					 * Fires in the listing item meta.
					 *
					 * @since 4.0.0
					 */
                     
					do_action( 'cp_listing_item_footer' );
					?>
 
				</footer> <!-- .entry-content -->

			</div> <!-- .content-inner -->

		</div> <!-- .columns -->

	</div> <!-- .row -->

</article>