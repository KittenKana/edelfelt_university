<?php
/**
 * Generic Author template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

$author        = get_queried_object();

$first         = get_the_author_meta( 'first_name', $author->ID );
$display       = get_the_author_meta( 'display_name', $author->ID );

$sidebar_position = get_theme_mod( 'author_sidebar_position', 'left' );
?>

<div id="author-<?php esc_attr_e( $author->ID ); ?>" class="author-page">

	<section <?php echo apply_filters( 'cp_background_cover', 'hero-author entry-cover text-center', array( 'size' => 'full' ) ); ?>></section>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<section>

				<div class="row">

					<div class="m-large-4 columns text-center">

						<div class="post-user post-user-profile">

							<?php echo cp_get_avatar( $author->ID, 300 ); ?>
							<h1 class="h4 user-display-name"><?php echo esc_attr( $display ); ?></h1>

						</div>

					</div> <!-- .columns -->

					<div class="m-large-8 columns">

						<?php
						// Get any social networks that have values.
						if ( array_filter( $social_networks = cp_get_available_user_networks( true, $author->ID ) ) ) :
						?>
						<ul class="social-icons">
							<?php foreach ( $social_networks as $social => $username ) { ?>
							<li class="<?php echo esc_attr( $social ); ?>">
								<a class="fa-icon fa-<?php esc_attr_e( $social ); ?>" href="<?php echo cp_get_social_account_url( $social, $username ); ?>" title="<?php echo esc_attr( cp_get_social_network_title( $social ) ); ?>" target="_blank"></a>
							</li>
							<?php } ?>
						</ul>
						<?php endif; ?>

					</div> <!-- .columns -->

				</div> <!-- .row -->

				<div class="row">

					<?php if ( 'left' === $sidebar_position ) { get_sidebar( 'author' ); } ?>

					<div class="m-large-8 columns">

						<div class="author-content-wrap">

							<?php dynamic_sidebar( 'sidebar_author_content' ); ?>

						</div>

						<div class="author-tabs-wrap">

							<?php cp_tabbed_dynamic_sidebar( 'sidebar_author_tabbed_content' ); ?>

						</div>

					</div> <!-- .columns -->

					<?php if ( 'right' === $sidebar_position ) { get_sidebar( 'author' ); } ?>

				</div> <!-- .row -->

				<?php
				/**
				 * Fires after the author profile.
				 *
				 * @since 4.0.0
				 *
				 * @param array $author The author array.
				 */
				do_action( 'cp_author_profile_after', $author );
				?>

			</section>

		</main>

	</div> <!-- #primary -->

</div> <!-- #author-id -->
