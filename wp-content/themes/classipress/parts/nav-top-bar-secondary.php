<?php
/**
 * The template for displaying the top navigation
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.0.0
 */

?>
<nav id="top-bar-secondary" class="top-bar" role="navigation">

	<div class="row">

         <div class="menu-cart-wrapper" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="menu-area">
                <?php cp_header_menu_secondary(); ?>
            </div>
            <div class="cart-area">
            
            	<a class="api-btn" style="padding-left:50px; padding-right:50px; color: #565656;" href="/login-2/"> Login </a>
                
                <?php echo do_shortcode('[cart_dropdown]'); ?>
            </div>
</div>

	</div><!-- .row -->

</nav><!-- .top-bar -->