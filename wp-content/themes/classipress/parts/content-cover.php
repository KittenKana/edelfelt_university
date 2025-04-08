<?php
/**
 * Blog post single loop content head cover template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<div <?php echo apply_filters( 'cp_background_cover', 'entry-cover fixed-cover text-center', array( 'size' => 'full' ) ); ?>>

	<div class="hero-post-wrap row">

		<div class="column">

			<header class="entry-header">

				<?php appthemes_before_post_title( get_post_type() ); ?>

				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				<?php appthemes_after_post_title( get_post_type() ); ?>

				<div class="entry-meta">

					<span class="entry-meta-left"><span class="entry-author"><?php _e( 'By', APP_TD ); ?> <?php the_author_posts_link(); ?></span></span>

					<span class="entry-avatar">
						<?php echo cp_get_avatar( get_the_author_meta( 'user_email' ), 96 ); ?>
					</span>

					<span class="entry-meta-right">
						<span class="entry-date"><?php echo get_the_date(); ?></span>
					</span>

				</div> <!-- .entry-meta -->

				<div class="entry-meta-sub">

					<span class="entry-category">
						<i class="fa fa-folder-open-o" aria-hidden="true"></i> <?php the_category( ', ' ); ?>
					</span>

					<?php if ( get_comments_number() ) { ?>
					<span class="entry-comments sep-l">
						<i class="fa fa-comments-o" aria-hidden="true"></i>  <?php printf( _nx( '1 Comment', '%1$s Comments', get_comments_number(), 'comments title', APP_TD ), number_format_i18n( get_comments_number() ) ); ?>
					</span>
					<?php } ?>

					<?php
					/**
					 * Fires in the single listing hero meta section.
					 *
					 * @since 4.0.0
					 */
					do_action( 'cp_post_single_hero_meta' );
					?>

				</div> <!-- .entry-meta-sub -->

				<?php
				/**
				 * Fires in the single page header.
				 *
				 * @since 4.0.0
				 */
				do_action( 'cp_single_template_header' );
				?>

			</header>

		</div> <!-- .column -->

	</div> <!-- .row -->

	<?php get_template_part( 'parts/content-action-bar', get_post_type() ); ?>

</div>
