<?php
/**
 * Generic Footer template.
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 1.0
 */

?>

<footer id="footer" class="site-footer" role="contentinfo">

	<div class="row column">

		<div class="footer-top row">

			<?php dynamic_sidebar( 'sidebar_footer' ); ?>

		</div> <!-- .footer-top -->

		<div class="divider"></div>

		<div class="footer-bottom">

			<div class="row column">

				<?php cp_footer_menu(); ?>

				<div class="copyright">
					<?php echo get_theme_mod( 'footer_copyright_text', sprintf( __( '&copy; %s %s | All Rights Reserved', APP_TD ), '<span class="copyright-year">' . date_i18n( 'Y' ) . '</span>', '<span class="copyright-holder">' . get_bloginfo( 'name' ) . '</span>' ) ); ?>
					<?php cp_website_current_time(); ?>
				</div> <!-- .copyright -->

			</div> <!-- .row -->

		</div> <!-- .footer-bottom -->

	</div> <!-- .row -->

</footer><!-- .site-footer -->
