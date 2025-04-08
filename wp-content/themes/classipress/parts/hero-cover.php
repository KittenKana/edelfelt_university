<?php
/**
 * The template for displaying hero background images and search.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>
<section <?php echo apply_filters( 'cp_background_cover', 'home-cover entry-cover', array( 'size' => 'full' ) ); ?>>

	<div class="row">

		<div class="small-12 columns">

			<header class="entry-header">

				// <?php the_title( '<h2 class="entry-title">', ' <span class="element"></span></h2>' ); ?>

				<div class="summary">
					//<?php the_content(); ?>
				</div>

				<?php
				/**
				 * Fires in the home page header.
				 *
				 * @since 4.0.0
				 */
				// do_action( 'cp_home_template_header' );
				?>

			</header>

		</div> <!-- .columns -->

	</div> <!-- .row -->

</section>