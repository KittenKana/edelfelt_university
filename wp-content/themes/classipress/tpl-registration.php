<?php
/**
 * Template Name: Register
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 3.2
 */

?>

<div class="row">

	<div id="primary" class="content-area medium-8 large-4 medium-centered columns">

		<main id="main" class="site-main" role="main">

			<?php appthemes_notices(); ?>

			<?php
			if ( have_posts() ) :

				while ( have_posts() ) : the_post();
			?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article">

					<div class="content-wrap">

						<div class="content-inner">

							<header class="entry-header text-center">
								<?php the_title( '<h1 class="h2 entry-title">', '</h1>' ); ?>
							</header>

							<div class="entry-content">

								<?php the_content(); ?>

								<?php if ( get_option( 'users_can_register' ) ) : ?>

									<?php require APP_THEME_FRAMEWORK_DIR . '/templates/foundation/form-registration.php'; ?>

								<?php else : ?>

									<p><?php _e( '** User registration is currently disabled. Please contact the site administrator. **', APP_TD ); ?></p>

								<?php endif; ?>

							</div> <!-- .entry-content -->

						</div> <!-- .content-inner -->

					</div> <!-- .content-wrap -->

				</article>

			<?php
				endwhile;

			endif;
			?>

		</main>

	</div> <!-- #primary -->

</div> <!-- .row -->
