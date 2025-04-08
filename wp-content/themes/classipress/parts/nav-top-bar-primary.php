<?php
/**
 * The template for displaying the top navigation
 *
 * @package ClassiPress\Templates
 * @author  AppThemes
 * @since   ClassiPress 4.0.0
 */

$expanded = get_theme_mod( 'header_full_width', 1 ) ? ' expanded' : '';
?>


<div id="top-bar-padding" > <!-- WRAP START -->

<!--Added padding to header -->

<div id="top-bar-primary" class="top-bar" role="navigation"
style="padding:15px"
>

<!-- PADDING START -->

	<div class="row column<?php echo $expanded; ?>">
    
     <!-- UNIVERSITY -->

		<div class="primary-header-wrap">

			<div class="site-branding">
				<a href="/">
                
                <!--
                <img src="https://www.morethantokyo.com/wp-content/uploads/2022/03/cherry-blossoms-g5dd367977_1920.jpg" 
                style="max-height: 110px; width=auto 
   				position: absolute;
  				top: 50%;
                padding-top: 8vh;
  				transform: translate(0, -4vh);             
                
                " >
                -->
                
                </a>
				<?php
				if ( function_exists( 'the_custom_logo' ) ) { // Since 4.0.0
					the_custom_logo();
				}
                
                

				if ( is_front_page() ) { ?>

					<h1 class="site-title">
                    
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
					</h1>

				<?php } else { ?>

					<span class="h1 site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
							<?php bloginfo( 'name' ); ?>
						</a>
                        
					</span>

				<?php } ?>

				<p class="site-description"><?php bloginfo( 'description' ); ?></p>
                
                

			</div><!-- .site-branding -->

			<div class="top-bar-left">

    
				<?php
				/**
				 * Fires in the header next to the logo.
				 *
				 * @since 4.0.0
				 */

                 
                 
				do_action( 'cp_header_top_bar_left' );
				?>


				<?php dynamic_sidebar( 'sidebar_header' ); ?>

			</div>


    

			<?php cp_header_menu_primary(); ?>

		</div><!-- .primary-header-wrap -->

	</div><!-- .row -->
    
</div><!-- .top-bar -->

</div> <!-- complete wrap -->