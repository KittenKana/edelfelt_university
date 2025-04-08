<?php
/**
 * Setup the menus.
 *
 * @package ClassiPress
 * @since   4.0.0
 */

add_action( 'after_setup_theme', 'cp_register_menus' );

/**
 * Register menus
 *
 * @return void
 */
function cp_register_menus() {
	register_nav_menu( 'first_left', __( 'Header Top Bar Left', APP_TD ) );
	register_nav_menu( 'first_right', __( 'Header Top Bar Right', APP_TD ) );
	register_nav_menu( 'primary_top', __( 'Header Middle Bar', APP_TD ) );
	register_nav_menu( 'primary', __( 'Header Bottom Bar', APP_TD ) );
	register_nav_menu( 'secondary', __( 'Footer Navigation', APP_TD ) );
	register_nav_menu( 'theme_dashboard', __( 'User Dashboard', APP_TD ) );
}

/**
 * Setup the Header Top Bar Left menu.
 *
 * @since 4.2.0
 *
 * @param array $args Arguments to be used in wp_nav_menu().
 */
function cp_header_menu_first_left( $args = array() ) {

	$defaults = array(
		'theme_location'  => 'first_left',
		'container_class' => 'top-bar-left',
		'container'       => 'nav',
		'menu_id'         => 'menu-first-bar-left',
		'menu_class'      => 'menu medium-horizontal vertical',
		'items_wrap'      => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown" data-close-on-click-inside="false">%3$s</ul>',
		'fallback_cb'     => false,
		'depth'           => 5,
		'walker'          => new CP_Topbar_Menu_Walker(),
	);

	$args = wp_parse_args( $args, $defaults );

	return wp_nav_menu( $args );
}

/**
 * Setup the Header Top Bar Right menu.
 *
 * @since 4.2.0
 *
 * @param array $args Arguments to be used in wp_nav_menu().
 */
function cp_header_menu_first_right( $args = array() ) {

	$defaults = array(
		'theme_location'  => 'first_right',
		'container_class' => 'top-bar-right',
		'container'       => 'nav',
		'menu_id'         => 'menu-first-bar-right',
		'menu_class'      => 'menu medium-horizontal vertical',
		'items_wrap'      => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown" data-close-on-click-inside="false">%3$s</ul>',
		'fallback_cb'     => false,
		'depth'           => 5,
		'walker'          => new CP_Topbar_Menu_Walker(),
	);

	$args = wp_parse_args( $args, $defaults );

	return wp_nav_menu( $args );
}

if ( ! function_exists( 'cp_header_menu_primary' ) ) :
/**
 * Setup the primary header menu.
 *
 * @since 4.0.0
 *
 * @param array $args Arguments to be used in wp_nav_menu().
 */
function cp_header_menu_primary( $args = array() ) {

	$defaults = array(
		'theme_location'  => 'primary_top',
		'container_class' => 'top-bar-right',
		'container'       => 'nav',
		'menu_id'         => 'menu-top-bar',
		'menu_class'      => 'menu medium-horizontal vertical',
		'items_wrap'      => '<ul id="%1$s" class="%2$s" data-responsive-menu="accordion medium-dropdown" data-close-on-click-inside="false">%3$s</ul>',
		'fallback_cb'     => false,
		'depth'           => 5,
		'walker'          => new CP_Topbar_Menu_Walker(),
	);

	$args = wp_parse_args( $args, $defaults );

	return wp_nav_menu( $args );
}
endif;

if ( ! function_exists( 'cp_header_menu_secondary' ) ) :
/**
 * Setup the secondary header menu.
 *
 * @since 4.0.0
 *
 * @param array $args Arguments to be used in wp_nav_menu().
 */
function cp_header_menu_secondary( $args = array() ) {
	$defaults = array(
		'theme_location' => 'primary',
		'container'      => false,
		'menu_id'        => 'menu-header',
		'menu_class'     => 'menu medium-horizontal vertical',
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'fallback_cb'    => false,
		'depth'          => 5,
		'walker'         => new CP_Topbar_Menu_Walker(),
	);

	$args = wp_parse_args( $args, $defaults );

	// Capture menu HTML
	ob_start();
	wp_nav_menu( $args );
	$menu_html = ob_get_clean();

	// Output menu + cart next to each other in a flex container
	echo '
	<div class="menu-cart-wrapper" style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
		<div class="menu-wrapper" style="flex: 1;">
			' . $menu_html . '
		</div>
	</div>';
}


endif;


if ( ! function_exists( 'cp_footer_menu' ) ) :
/**
 * Setup the footer menu.
 *
 * @since 4.0.0
 *
 * @param array $args Arguments to be used in wp_nav_menu().
 */
function cp_footer_menu( $args = array() ) {
	$defaults = array(
		'theme_location'  => 'secondary',
		'container'       => false,
		'menu_id'         => 'footer-nav-menu',
		'menu_class'      => 'social-media list-inline',
		'fallback_cb'     => false,
		'depth'           => 1,
	);

	$args = wp_parse_args( $args, $defaults );

	return wp_nav_menu( $args );
}
endif;

/**
 * Filters the nav menues so we can dynamically replace the title and URL
 * placeholders with the custom menu items.
 *
 * @since 4.2.0
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param object $item        Menu item data object.
 *
 * @return string $item_output
 */
function cp_header_custom_menu_items( $item_output, $item ) {

	// Remove login/register links for Logged-in user.
	if ( 'custom' === $item->object ) {

		if ( '#login_url' === $item->url ) {
			if ( is_user_logged_in() ) {
				return;
			}

			$item_output = str_replace( $item->url, wp_login_url( get_permalink() ), $item_output );
			return $item_output;
		}

		if ( '#register_url' === $item->url ) {
			if ( is_user_logged_in() || ! get_option( 'users_can_register' ) ) {
				return;
			}

			$item_output = str_replace( $item->url, wp_registration_url(), $item_output );

			return $item_output;
		}
	}

	// Replace placeholders.
	$placeholders = array(
		'{{user_account}}',
		'{{divider}}',
		'{{logout}}',
	);

	if ( ! in_array( $item->title, $placeholders, true ) ) {
		return $item_output;
	}

	if ( ! is_user_logged_in() ) {
		return;
	}

	switch ( $item->title ) {

		case '{{user_account}}':
			$user = wp_get_current_user();

			/**
			 * Filter the display name next to the gravatar.
			 *
			 * @since 4.0.0
			 *
			 * @param string $user->user_login The user login field.
			 * @param array $user The user object.
			 */
			$display_name = apply_filters( 'cp_user_menu_display_name', $user->user_login, $user );
			$avatar       = cp_get_avatar( $user->user_email, 30, '', '', array( 'class' => 'img-circle' ) ) . $display_name;
			$item_output  = str_replace( $item->title, $avatar, $item_output );
			break;

		case '{{divider}}':
			$item_output = '<li class="divider"></li>';
			break;

		case '{{logout}}':
			$item_output = '<li id="menu-item-logout"><a href="' . esc_url( wp_logout_url( get_permalink() ) ) . '" class="user-logout"><i class="fa fa-sign-out" aria-hidden="true"></i>' . __( 'Log out', APP_TD ) . '</a></li>';
			break;

	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'cp_header_custom_menu_items', 10, 2 );

/**
 * Customize the output of menus for Foundation top bar
 *
 * @package ClassiPress
 * @since 4.0.0
 */
class CP_Topbar_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 4.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"vertical menu\">\n";
	}
}

/**
 * Customize the output of menus for the Foundation mobile off-canvas menu
 *
 * @package ClassiPress
 * @since 4.0.0
 */
class CP_Off_Canvas_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker_Nav_Menu::start_lvl()
	 *
	 * @since 4.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of page. Used for padding.
	 * @param array  $args   Not used.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"vertical menu\">\n";
	}
}
