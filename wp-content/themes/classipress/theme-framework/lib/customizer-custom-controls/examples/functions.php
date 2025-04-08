<?php

/**
 * Check if WooCommerce is active
 * Use in the active_callback when adding the WooCommerce Section to test if WooCommerce is activated
 *
 * @return boolean
 */
function appthemes_customizer_sample_is_woocommerce_active() {
	if ( class_exists( 'woocommerce' ) ) {
		return true;
	}
	return false;
}

/**
 * Set our Social Icons URLs.
 * Only needed for our sample customizer preview refresh
 *
 * @return array Multidimensional array containing social media data
 */
function appthemes_customizer_sample_generate_social_urls() {
	$social_icons = array(
		array( 'url' => 'behance.net', 'icon' => 'fa-behance', 'title' => esc_html__( 'Follow me on Behance', APP_TD ), 'class' => 'behance' ),
		array( 'url' => 'bitbucket.org', 'icon' => 'fa-bitbucket', 'title' => esc_html__( 'Fork me on Bitbucket', APP_TD ), 'class' => 'bitbucket' ),
		array( 'url' => 'codepen.io', 'icon' => 'fa-codepen', 'title' => esc_html__( 'Follow me on CodePen', APP_TD ), 'class' => 'codepen' ),
		array( 'url' => 'deviantart.com', 'icon' => 'fa-deviantart', 'title' => esc_html__( 'Watch me on DeviantArt', APP_TD ), 'class' => 'deviantart' ),
		array( 'url' => 'dribbble.com', 'icon' => 'fa-dribbble', 'title' => esc_html__( 'Follow me on Dribbble', APP_TD ), 'class' => 'dribbble' ),
		array( 'url' => 'etsy.com', 'icon' => 'fa-etsy', 'title' => esc_html__( 'favourite me on Etsy', APP_TD ), 'class' => 'etsy' ),
		array( 'url' => 'facebook.com', 'icon' => 'fa-facebook', 'title' => esc_html__( 'Like me on Facebook', APP_TD ), 'class' => 'facebook' ),
		array( 'url' => 'flickr.com', 'icon' => 'fa-flickr', 'title' => esc_html__( 'Connect with me on Flickr', APP_TD ), 'class' => 'flickr' ),
		array( 'url' => 'foursquare.com', 'icon' => 'fa-foursquare', 'title' => esc_html__( 'Follow me on Foursquare', APP_TD ), 'class' => 'foursquare' ),
		array( 'url' => 'github.com', 'icon' => 'fa-github', 'title' => esc_html__( 'Fork me on GitHub', APP_TD ), 'class' => 'github' ),
		array( 'url' => 'instagram.com', 'icon' => 'fa-instagram', 'title' => esc_html__( 'Follow me on Instagram', APP_TD ), 'class' => 'instagram' ),
		array( 'url' => 'last.fm', 'icon' => 'fa-lastfm', 'title' => esc_html__( 'Follow me on Last.fm', APP_TD ), 'class' => 'lastfm' ),
		array( 'url' => 'linkedin.com', 'icon' => 'fa-linkedin', 'title' => esc_html__( 'Connect with me on LinkedIn', APP_TD ), 'class' => 'linkedin' ),
		array( 'url' => 'medium.com', 'icon' => 'fa-medium', 'title' => esc_html__( 'Follow me on Medium', APP_TD ), 'class' => 'medium' ),
		array( 'url' => 'pinterest.com', 'icon' => 'fa-pinterest', 'title' => esc_html__( 'Follow me on Pinterest', APP_TD ), 'class' => 'pinterest' ),
		array( 'url' => 'reddit.com', 'icon' => 'fa-reddit', 'title' => esc_html__( 'Join me on Reddit', APP_TD ), 'class' => 'reddit' ),
		array( 'url' => 'slack.com', 'icon' => 'fa-slack', 'title' => esc_html__( 'Join me on Slack', APP_TD ), 'class' => 'slack.' ),
		array( 'url' => 'slideshare.net', 'icon' => 'fa-slideshare', 'title' => esc_html__( 'Follow me on SlideShare', APP_TD ), 'class' => 'slideshare' ),
		array( 'url' => 'snapchat.com', 'icon' => 'fa-snapchat', 'title' => esc_html__( 'Add me on Snapchat', APP_TD ), 'class' => 'snapchat' ),
		array( 'url' => 'soundcloud.com', 'icon' => 'fa-soundcloud', 'title' => esc_html__( 'Follow me on SoundCloud', APP_TD ), 'class' => 'soundcloud' ),
		array( 'url' => 'spotify.com', 'icon' => 'fa-spotify', 'title' => esc_html__( 'Follow me on Spotify', APP_TD ), 'class' => 'spotify' ),
		array( 'url' => 'stackoverflow.com', 'icon' => 'fa-stack-overflow', 'title' => esc_html__( 'Join me on Stack Overflow', APP_TD ), 'class' => 'stackoverflow' ),
		array( 'url' => 'tumblr.com', 'icon' => 'fa-tumblr', 'title' => esc_html__( 'Follow me on Tumblr', APP_TD ), 'class' => 'tumblr' ),
		array( 'url' => 'twitch.tv', 'icon' => 'fa-twitch', 'title' => esc_html__( 'Follow me on Twitch', APP_TD ), 'class' => 'twitch' ),
		array( 'url' => 'twitter.com', 'icon' => 'fa-twitter', 'title' => esc_html__( 'Follow me on Twitter', APP_TD ), 'class' => 'twitter' ),
		array( 'url' => 'vimeo.com', 'icon' => 'fa-vimeo', 'title' => esc_html__( 'Follow me on Vimeo', APP_TD ), 'class' => 'vimeo' ),
		array( 'url' => 'youtube.com', 'icon' => 'fa-youtube', 'title' => esc_html__( 'Subscribe to me on YouTube', APP_TD ), 'class' => 'youtube' ),
	);

	return apply_filters( 'appthemes_customizer_sample_social_icons', $social_icons );
}

/**
 * Return an unordered list of linked social media icons, based on the urls provided in the Customizer Sortable Repeater
 * This is a sample function to display some social icons on your site.
 * This sample function is also used to show how you can call a PHP function to refresh the customizer preview.
 * Add the following to header.php if you want to see the sample social icons displayed in the customizer preview and your theme.
 * Before any social icons display, you'll also need to add the relevent URL's to the Header Navigation > Social Icons section in the Customizer.
 * <div class="social">
 *	 <?php echo appthemes_customizer_sample_get_social_media(); ?>
 * </div>
 *
 * @return string Unordered list of linked social media icons
 */
function appthemes_customizer_sample_get_social_media() {
	$defaults = appthemes_customizer_sample_generate_defaults();
	$output = '';
	$social_icons = appthemes_customizer_sample_generate_social_urls();
	$social_urls = [];
	$social_newtab = 0;
	$social_alignment = '';
	$contact_phone = '';

	$social_urls = explode( ',', get_theme_mod( 'social_urls', $defaults['social_urls'] ) );
	$social_newtab = get_theme_mod( 'social_newtab', $defaults['social_newtab'] );
	$social_alignment = get_theme_mod( 'social_alignment', $defaults['social_alignment'] );
	$contact_phone = get_theme_mod( 'contact_phone', $defaults['contact_phone'] );

	if( !empty( $contact_phone ) ) {
		$output .= sprintf( '<li class="%1$s"><i class="fa %2$s"></i>%3$s</li>',
			'phone',
			'fa-phone',
			$contact_phone
		);
	}

	foreach( $social_urls as $key => $value ) {
		if ( !empty( $value ) ) {
			$domain = str_ireplace( 'www.', '', parse_url( $value, PHP_URL_HOST ) );
			$index = array_search( $domain, array_column( $social_icons, 'url' ) );
			if( false !== $index ) {
				$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
					$social_icons[$index]['class'],
					esc_url( $value ),
					$social_icons[$index]['title'],
					( !$social_newtab ? '' : ' target="_blank"' ),
					$social_icons[$index]['icon']
				);
			}
			else {
				$output .= sprintf( '<li class="nosocial"><a href="%2$s"%3$s><i class="fa %4$s"></i></a></li>',
					$social_icons[$index]['class'],
					esc_url( $value ),
					( !$social_newtab ? '' : ' target="_blank"' ),
					'fa-globe'
				);
			}
		}
	}

	if( get_theme_mod( 'social_rss', $defaults['social_rss'] ) ) {
		$output .= sprintf( '<li class="%1$s"><a href="%2$s" title="%3$s"%4$s><i class="fa %5$s"></i></a></li>',
			'rss',
			home_url( '/feed' ),
			'Subscribe to my RSS feed',
			( !$social_newtab ? '' : ' target="_blank"' ),
			'fa-rss'
		);
	}

	if ( !empty( $output ) ) {
		$output = '<ul class="social-icons ' . $social_alignment . '">' . $output . '</ul>';
	}

	return $output;
}

/**
 * Append a search icon to the primary menu
 * This is a sample function to show how to append an icon to the menu based on the customizer search option
 * The search icon wont actually do anything
 */
function appthemes_customizer_sample_add_search_menu_item( $items, $args ) {
	$defaults = appthemes_customizer_sample_generate_defaults();

	if( get_theme_mod( 'search_menu_icon', $defaults['search_menu_icon'] ) ) {
		if( $args->theme_location == 'primary' ) {
			$items .= '<li class="menu-item menu-item-search"><a href="#" class="nav-search"><i class="fa fa-search"></i></a></li>';
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_items', 'appthemes_customizer_sample_add_search_menu_item', 10, 2 );

/**
 * Return a string containing the sample TinyMCE Control
 * This is a sample function to show how you can use the TinyMCE Control for footer credits in your Theme
 * Add the following three lines of code to your footer.php file to display the content of your sample TinyMCE Control
 * <div class="footer-credits">
 *		<?php echo appthemes_customizer_sample_get_credits(); ?>
 *	</div>
 */
function appthemes_customizer_sample_get_credits() {
	$defaults = appthemes_customizer_sample_generate_defaults();

	// wpautop this so that it acts like the new visual text widget, since we're using the same TinyMCE control
	return wpautop( get_theme_mod( 'sample_tinymce_editor', $defaults['sample_tinymce_editor'] ) );
}

/**
* Set our Customizer default options
*/
function appthemes_customizer_sample_generate_defaults() {
	$customizer_defaults = array(
		'social_newtab' => 0,
		'social_urls' => '',
		'social_alignment' => 'alignright',
		'social_rss' => 0,
		'social_url_icons' => '',
		'contact_phone' => '',
		'search_menu_icon' => 0,
		'woocommerce_shop_sidebar' => 1,
		'woocommerce_product_sidebar' => 0,
		'sample_toggle_switch' => 0,
		'sample_slider_control' => 48,
		'sample_slider_control_small_step' => 2,
		'sample_sortable_repeater_control' => '',
		'sample_image_radio_button' => 'sidebarright',
		'sample_text_radio_button' => 'right',
		'sample_image_checkbox' => 'stylebold,styleallcaps',
		'sample_single_accordion' => '',
		'sample_alpha_color' => 'rgba(209,0,55,0.7)',
		'sample_simple_notice' => '',
		'sample_dropdown_select2_control_single' => 'vic',
		'sample_dropdown_select2_control_multi' => array (
			'Antarctica/McMurdo',
			'Australia/Melbourne',
			'Australia/Broken_Hill',
			),
		'sample_dropdown_posts_control' => '',
		'sample_tinymce_editor' => '',
		'sample_google_font_select' => json_encode(
			array(
				'font' => 'Open Sans',
				'regularweight' => 'regular',
				'italicweight' => 'italic',
				'boldweight' => '700',
				'category' => 'sans-serif'
			)
		),
		'sample_default_text' => '',
		'sample_email_text' => '',
		'sample_url_text' => '',
		'sample_number_text' => '',
		'sample_hidden_text' => '',
		'sample_date_text' => '',
		'sample_default_checkbox' => 0,
		'sample_default_select' => 'jet-fuel',
		'sample_default_radio' => 'spider-man',
		'sample_default_dropdownpages' => '1548',
		'sample_default_textarea' => '',
		'sample_default_color' => '#333',
		'sample_default_media' => '',
		'sample_default_image' => '',
		'sample_default_cropped_image' => '',
		'sample_date_only' => '2017-08-28',
		'sample_date_time' => '2017-08-28 16:30:00',
		'sample_date_time_no_past_date' => date( 'Y-m-d' ),
	);

	return apply_filters( 'appthemes_customizer_sample_defaults', $customizer_defaults );
}

/**
* Load all our Customizer options
*/
include_once trailingslashit( dirname(__FILE__) ) . 'customizer.php';
