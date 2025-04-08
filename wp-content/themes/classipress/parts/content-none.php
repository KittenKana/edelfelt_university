<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<section class="no-results not-found content-wrap">

	<div class="content-inner">

		<header class="page-header">
			<h1 class="page-title"><?php esc_html_e( 'Nothing Found', APP_TD ); ?></h1>
		</header><!-- .page-header -->

		<div class="page-content">
			<?php if ( is_search() ) : ?>

				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', APP_TD ); ?></p>

			<?php else : ?>

				<p><?php esc_html_e( "It seems we can't find what you're looking for. Perhaps searching can help.", APP_TD ); ?></p>

			<?php endif; ?>
		</div><!-- .page-content -->

	</div> <!-- .content-inner -->

</section><!-- .no-results -->
