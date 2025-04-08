<?php
/**
 * Action and filter hooks.
 *
 * @package ClassiPress\Actions
 * @author  AppThemes
 * @since   ClassiPress 3.1
 */

add_action( 'init', 'cp_social_connect_login' );
add_action( 'init', 'cp_disable_auto_embeds' );
add_action( 'init', 'cp_disable_wp_features' );

add_filter( 'excerpt_more', 'cp_ads_excerpt_more' );
add_filter( 'excerpt_length', 'cp_ads_excerpt_length' );

add_action( 'after_setup_theme', '_cp_remove_default_notices', 9999 );
add_action( 'after_setup_theme', 'cp_custom_registration_email', 1000 );

add_action( 'appthemes_display_notice', 'cp_output_notices', 10, 2 );

add_action( 'wp_head', 'cp_generator' );
add_action( 'wp_head', 'cp_pingback_header' );
add_action( 'wp_head', 'cp_alternate_rss' );

add_action( 'wp_footer', 'cp_google_analytics_code' );

add_action( 'appthemes_after_post', 'cp_single_ad_banner' );
add_action( 'appthemes_after_post_loop', 'cp_category_search_ad_banner' );

add_action( 'before_delete_post', 'cp_delete_ad_meta' );
//add_action( 'appthemes_before_post_title', 'cp_ad_loop_price' );
//add_action( 'appthemes_after_post_content', 'cp_do_loop_stats' );
add_action( 'cp_single_template_footer', 'cp_add_listing_status_meta' );
add_action( 'cp_single_template_footer', 'cp_blog_post_meta_footer' );
add_action( 'cp_single_template_footer', 'cp_single_listing_actions_bar' );
add_action( 'cp_post_single_bar_actions', 'cp_edit_ad_link' );





add_action( 'cp_listing_single_head', 'cp_add_listing_single_head' );

add_action( 'cp_kana_listing_info', 'cp_kana_get_header_info' );

add_action( 'cp_listing_single_bar_actions', 'cp_edit_ad_link', 40 );
add_action( 'cp_listing_single_bar_actions', 'cp_report_listing_button', 45 );

add_action( 'cp_listing_single_status_meta', 'cp_do_ad_ref_id' );
add_action( 'appthemes_after_ad_listing', 'cp_single_ad_banner' );
add_action( 'appthemes_after_ad_listing_loop', 'cp_category_search_ad_banner' );





add_action( 'wp', 'cp_cache_featured_images' );
add_filter( 'social_connect_redirect_to', 'cp_social_connect_redirect_to', 10, 1 );

add_action( 'register_form', 'cp_maybe_display_recaptcha' );
add_filter( 'show_password_fields_on_registration', 'cp_password_fields_support', 10, 1 );

add_action( 'wp_login', 'cp_redirect_to_home_page' );
add_action( 'app_login', 'cp_redirect_to_home_page' );

add_action( 'appthemes_advertise_content', 'cp_adbox_336x280' );
add_action( 'appthemes_advertise_header', 'cp_adbox_468x60' );

add_filter( 'close_comments_for_post_types', 'cp_close_comments_for_old_ads' );
add_filter( 'wp_dropdown_cats', 'cp_change_dropdown_indentation_on_mobile' );
add_filter( 'cp_formbuilder_cp_price', 'cp_limit_characters_in_price_field' );
add_action( 'user_register', 'cp_move_social_url_on_user_registration' );
add_action( 'template_redirect', 'cp_set_default_template_vars' );

add_filter( 'mce_buttons', 'cp_editor_modify_buttons', 10, 2 );

add_filter( 'cp_background_cover', 'cp_background_cover', 10, 2 );
add_filter( 'cp_listing_item_head', 'cp_add_listing_item_featured', 8 );
add_action( 'cp_listing_item_head', 'cp_ad_loop_price', 9 );
//add_action( 'appthemes_before_featured_title', 'cp_ad_loop_price' );
add_action( 'cp_listing_item_head', 'cp_add_listing_item_title_tag', 10 );
add_action( 'cp_listing_item_meta', 'cp_ad_loop_meta' );
add_action( 'cp_listing_item_content', 'cp_ad_loop_content' );

add_filter( 'widget_meta_poweredby', 'cp_meta_poweredby' );

// Disabled since 4.2.0
//add_action( 'cp_author_profile_after', 'cp_author_profile_listings' );

add_filter( 'cp_listing_data_attributes', 'cp_listing_data_values' );

add_filter( 'cp_allow_listing_banner_image', '_cp_disallow_listing_banner_image' );

/**
 * Inserts the title tag in the single listing item head.
 *
 * @since 4.0.0
 *
 * @return string
 */
function cp_add_listing_item_title_tag() {

	$title = get_the_title();
	$htag  = ( is_archive( APP_POST_TYPE ) && ! is_author() ) ? 'h2' : 'h3';

	printf( '<%4$s class="h4 entry-title"><a href="%1$s" title="%2$s" rel="bookmark">%3$s</a></%4$s>', esc_url( get_permalink() ), esc_attr( $title ), $title, $htag );
}

/**
 * Inserts the content in the single listing item.
 *
 * @since 4.0.0
 *
 * @return string
 */
function cp_ad_loop_content() {
	echo cp_get_content_preview();
}

/**
 * Inserts the featured label in the single listing item head.
 *
 * @since 4.0.0
 *
 * @return string
 */
function cp_add_listing_item_featured() {

	if ( ! is_sticky() ) {
		return;
	}

	echo '<span class="featured-label warning label">' . __( 'Featured', APP_TD ) . '</span>';
}


/**
 * Inserts the search bar in the header.
 *
 * @since 4.0.0
 *
 * Is not in use since 4.2.0
 *
 * @return string
 */
function cp_add_header_search_bar() {
	echo '<div class="top-bar-left">';
	get_template_part( 'parts/adblock', 'header' );
	echo '</div>';
}
//add_action( 'cp_header_top_bar_left', 'cp_add_header_search_bar' );

/**
 * Displays a background cover image for pages, listings, and posts.
 *
 * @since 4.0.0
 *
 * @param string $class Pass in a css class.
 * @param array  $args Pass in some arguments.
 * @return string $atts
 */
function cp_background_cover( $class, $args = array() ) {
	global $post, $cp_options;

	/**
	 * Filters the background cover photo default values.
	 *
	 * @since 4.0.0
	 */
	$defaults = apply_filters( 'cp_cover_defaults', array(
		'images'     => false,
		'object_ids' => false,
		'size'       => 'large',
	) );

	$args  = wp_parse_args( $args, $defaults );
	$image = false;
	$post  = get_post();

	// Heavy lifting. Based on the page/post/taxonomy/author, figure out which image to show, if any.
	if ( ( is_home() && ! in_the_loop() ) || ( ! in_the_loop() && is_singular( 'post' ) ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_option( 'page_for_posts' ) ), $args[ 'size' ] );
	} else if ( ( ! did_action( 'loop_start' ) && is_archive() && ! is_author() )
		|| ( ! did_action( 'loop_start' ) && is_year() ) // Also covers month and day since year is parent in url.
		|| ( $args[ 'images' ] || $args[ 'object_ids' ] )
	) {
		if ( empty( $args[ 'images' ] ) ) {
			$thumbnail_id = get_term_meta( get_queried_object_id(), 'thumbnail_id', true );
			$thumbnail_id = array_filter( (array) $thumbnail_id );
			$args['images'] = (array) $thumbnail_id;
		}
		$image = cp_tax_cover_image( $args );
	} else if ( ( ! did_action( 'loop_start' ) && is_author() ) ) {
		$author = get_user_by( 'id', get_query_var( 'author' ) );
		$id     = get_user_meta( $author->ID, 'cover_image', true );
		if ( is_array( $id ) ) {
			$id = $id[0];
		}
		$image  = wp_get_attachment_image_src( $id, $args[ 'size' ] );
	} elseif ( is_a( $post, 'WP_Post' ) ) {
		if ( APP_POST_TYPE === $post->post_type ) {
			if ( $cp_options->ad_images ) {
				// If there is a special banner image - load it, otherwise if we
				// need full size image - fallback to category banner.
				if ( 'full' === $args['size'] ) {
					$image_id = get_post_meta( $post->ID, '_cp_banner_image', true );

					if ( ! empty( $image_id ) ) {
						$image = wp_get_attachment_image_src( $image_id, $args['size'] );
					}

					if ( ! $image ) {
						$terms   = get_the_terms( $post->ID, APP_TAX_CAT );
						$term_id = 0;
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							$term_id = $terms[0]->term_id;
						}
						$thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
						$thumbnail_id = array_filter( (array) $thumbnail_id );
						if ( ! empty( $thumbnail_id ) ) {
							$image = wp_get_attachment_image_src( $thumbnail_id[0], $args['size'] );
						}
					}
				} else {
					$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $args[ 'size' ] );
					if ( ! $image ) {
						$image_id = (int) cp_get_featured_image_id( $post->ID );
						if ( ! empty( $image_id ) ) {
							$image = wp_get_attachment_image_src( $image_id, $args['size'] );
						}
					}

					if ( ! $image && get_theme_mod( 'listing_thumbnail_image_placeholder' ) ) {
						$image = wp_get_attachment_image_src( get_theme_mod( 'listing_thumbnail_image_placeholder' ), $args['size'] );
						if ( ! $image ) {
							$image = array( get_theme_mod( 'listing_thumbnail_image_placeholder' ) );
						}
					}
				}
			}
		} elseif ( ! empty( $post->_thumbnail_id ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), $args['size'] );
		}
	}

	/**
	 * Filters the background cover photo image url and arguments.
	 *
	 * @since 4.0.0
	 *
	 * @param string $image The url to the image.
	 * @param array  $args  The arguments for the image.
	 * @return string $atts
	 */
	$image = apply_filters( 'cp_cover_image', $image, $args );

	$height = ! empty( $args['height'] ) ? 'padding:' . (int) $args['height'] . 'em 0;' : '';

	if ( ! $image ) {
		$class .= ' no-image';
		$atts[] = sprintf( 'style="%s"', $height );
	} else {
		$class .= ' has-image';
		$atts[] = sprintf( 'style="background-image: url(%s);%s"', $image[0], $height );
	}
	$atts[] = sprintf( 'class="%s"', $class );

	return implode( ' ', $atts );
}

/**
 * Adds AppThemes link to Meta widget
 *
 * @param string $text
 * @return string
 */
function cp_meta_poweredby( $text ) {

	$text = sprintf( '<li><a href="%s" title="%s">%s</a></li>',
		esc_url( 'https://www.appthemes.com/' ),
		esc_attr__( 'Premium WordPress Themes', APP_TD ),
		_x( 'AppThemes', 'meta widget link text' )
	) . $text;

	return $text;
}


### Hook Callbacks

/**
 * Adds the google analytics tracking code in the footer.
 * @since 3.0.5
 *
 * @return void
 */
function cp_google_analytics_code() {
	global $cp_options;

	if ( ! empty( $cp_options->google_analytics ) ) {
		echo stripslashes( $cp_options->google_analytics );
	}

}



/**
 * Adds the ad price field in the loop before the ad title.
 * @since 3.1.3
 *
 * @since 4.2.0 Added parameter $class.
 *
 * @param string $class The price tag wrapper class.
 *
 * @return void
 */
function cp_ad_loop_price( $class = '' ) {
	global $post;

	if ( APP_POST_TYPE !== $post->post_type ) {
		return;
	}

	$price = cp_get_price( $post->ID, 'cp_price', false );

	if ( '&nbsp;' === $price ) {
		return;
	}

	if ( empty( $class ) ) {
		$class = 'price-wrap';
	}
?>

	<div class="<?php echo esc_attr( $class ); ?>">
		<span class="tag-head"><span class="post-price"><?php echo $price; ?></span></span>
	</div>

<?php
}



/**
 * Adds the ad meta in the loop after the ad title.
 * @since 3.1
 *
 * @return void
 */
function cp_ad_loop_meta() {
	global $post, $cp_options;

	$display = ( 'grid' !== get_query_var( 'listing_layout' ) && ( get_query_var( 'listing_layout' ) || is_archive() || is_post_type_archive( APP_POST_TYPE ) || is_search() || is_tax( array( APP_TAX_CAT, APP_TAX_TAG ) ) ) ) ? 'list' : 'grid';
	?>
	<ul class="meta-list list-inline">
		<li class="listing-cat fa-icon fa-list-ul"><?php if ( $post->post_type == 'post' ) the_category( ', ' ); else echo get_the_term_list( $post->ID, APP_TAX_CAT, '', ', ', '' ); ?></li>
		<?php if ( 'list' === $display ) { ?>
			<?php if ( ! is_author() ) { ?>
				<li class="listing-owner"><?php if ( $cp_options->ad_gravatar_thumb ) appthemes_get_profile_pic( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_email' ), 24 ); ?><?php the_author_posts_link(); ?></li>
			<?php } ?>
			<li class="listing-date fa-icon fa-clock-o"> <?php echo appthemes_date_posted( $post->post_date ); ?></li>
		<?php } ?>
	</ul><!-- .meta-list -->

<?php
}

/**
 * Adds the ad reference ID after the ad listing content.
 * @since 3.1.3
 *
 * @return void
 */
function cp_do_ad_ref_id() {
	global $post;

	if ( ! $post || APP_POST_TYPE !== $post->post_type ) {
		return;
	}

?>
	<span class="label" title="<?php esc_attr_e( 'Listing ID', APP_TD ); ?>">
		<i class="fa fa-id-card-o" aria-hidden="true"></i>
		<span class="screen-reader-text"><?php _e( 'Listing ID', APP_TD ); ?></span>  <?php if ( get_post_meta( $post->ID, 'cp_sys_ad_conf_id', true ) ) echo get_post_meta( $post->ID, 'cp_sys_ad_conf_id', true ); else _e( 'N/A', APP_TD ); ?>
	</span>
<?php
}


/**
 * Adds the pagination after the ad listing and blog post content.
 * @since 3.1
 * @since 4.0.0 added argument $wp_query;
 *
 * @param WP_Query $wp_query Custom query object. If null, will be used main
 *                           query.
 *
 * @return void
 */
function cp_do_pagination( $wp_query = null ) {

	$links = appthemes_pagenavi( $wp_query, 'paged', array(
		'echo' => false,
		'pages_text' => false,
		'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
		'next_text' => is_rtl() ? '&larr;' : '&rarr;',
		'type'      => 'array',
		'end_size'  => 3,
		'mid_size'  => 3,
	) );

	if ( ! $links ) {
		return;
	}

	foreach ( $links as &$link ) {
		if ( strpos( $link, 'current' ) ) {
			$link = html( 'li class="current"', html( "a href='#'", $link ) );
		} else {
			$link = html( 'li', $link );
		}
	}

	$r = '';
	$r .= "<ul class='pagination text-center' role='navigation'>\n\t";
	$r .= join( "\n\t", $links );
	$r .= "\n</ul>\n";

	echo $r;
}



/**
 * Adds the blog post meta footer content.
 * @since 3.1.3
 *
 * @return void
 */
function cp_blog_post_meta_footer() {
	global $post;

	if ( ! is_singular( array( 'post', APP_POST_TYPE ) ) ) {
		return;
	}
?>
	<div class="prdetails">
		<p class="post-tags">
		<?php
		if ( is_singular( 'post' ) && get_the_tags() ) {
			the_tags( '<i class="fa fa-tags" aria-hidden="true"></i> <span class="label">', '</span> <span class="label">', '</span>' );
		} elseif ( is_singular( APP_POST_TYPE ) && get_the_term_list( $post->ID, APP_TAX_TAG ) ) {
			echo get_the_term_list( $post->ID, APP_TAX_TAG, '<i class="fa fa-tags" aria-hidden="true"></i> <span class="label">', '</span> <span class="label">', '</span>' );
		} ?>
		</p>
		<?php if ( function_exists( 'wp_email' ) || function_exists( 'wp_print' ) ) { ?>
			<p class="dashicons-before print"><?php if ( function_exists( 'wp_email' ) ) email_link(); ?>&nbsp;&nbsp;<?php if ( function_exists( 'wp_print' ) ) print_link(); ?></p>
		<?php } ?>
	</div>

<?php
}

/**
 * Adds Listing post and expire dates.
 *
 * @since 4.0.0
 */
function cp_add_listing_status_meta() {
	global $post;

	if ( ! is_singular( APP_POST_TYPE ) ) {
		return;
	}
?>
	<div class="text-muted text-small">
		<p id="cp_listed" class="label"><i class="fa fa-calendar-o" aria-hidden="true" title="<?php esc_attr_e( 'Listed', APP_TD ); ?>"></i> <?php echo appthemes_display_date( $post->post_date ); ?></p>
	<?php if ( $expire_date = get_post_meta( $post->ID, 'cp_sys_expire_date', true ) ): ?>
		<p id="cp_expires" class="label"><i class="fa fa-hourglass-o" aria-hidden="true" title="<?php esc_attr_e( 'Expires', APP_TD ); ?>"></i> <?php echo cp_timeleft( $expire_date ); ?></p>
	<?php endif; ?>
	<?php
	/**
	 * Allows to add custom listing meta inline with listed and expire dates.
	 *
	 * @since 4.2.0
	 */
	do_action( 'cp_listing_single_status_meta' );
	?>
	</div>
	<?php
}

/**
 * Displays edit ad link. Use only in loop.
 *
 * @return void
 */
function cp_edit_ad_link() {
	global $post, $current_user, $cp_options;

	if ( ! is_user_logged_in() ) {
		return;
	}

	$edit_link = '';

	if ( current_user_can( 'manage_options' ) ) {
		$edit_link = get_edit_post_link( $post->ID );
	} elseif( $cp_options->ad_edit && $post->post_author == $current_user->ID && $post->post_type === APP_POST_TYPE ) {
		$edit_link = add_query_arg( 'listing_edit', $post->ID, CP_EDIT_URL );
	}

	if ( $edit_link ) {
		$post_type_obj = get_post_type_object( $post->post_type );
		echo '<a href="' . esc_url( $edit_link ) . '" class="listing-edit-link listing-icon" title="' . esc_attr( $post_type_obj->labels->edit_item ) . '"><i class="fa fa-pencil"></i><span class="screen-reader-text">' . $post_type_obj->labels->edit_item . '</span></a>';
	}

}


/**
 * Adds the blog and ad listing single page banner ad.
 * @since 3.1.3
 *
 * @return void
 */
function cp_single_ad_banner() {
	global $post;

	if ( ! is_singular( array( 'post', APP_POST_TYPE ) ) ) {
		return;
	}

	appthemes_advertise_content();
}

/**
 * Adds the blog and ad listing category/search banner ad.
 *
 * @since 3.5
 *
 * @return void
 */
function cp_category_search_ad_banner() {
	global $post;

	if ( ! is_category() && ! is_tax( APP_TAX_CAT ) && ! is_search() ) {
		return;
	}

	appthemes_advertise_content();
}

/**
 * Collects featured images if are enabled, limits db queries.
 * @since 3.1.8
 *
 * @return void
 */
function cp_cache_featured_images() {
	global $cp_options;

	if ( $cp_options->ad_images && ! is_singular( array( APP_POST_TYPE, 'post' ) ) ) {
		add_action( 'appthemes_before_ad_listing_loop', 'cp_collect_featured_images' );
		add_action( 'appthemes_before_featured_loop', 'cp_collect_featured_images' );
		//add_action( 'appthemes_before_search_loop', 'cp_collect_featured_images' );
		add_action( 'appthemes_before_post_loop', 'cp_collect_featured_images' );
	}
}


/**
 * Modifies Social Connect redirect to url.
 * @since 3.1.9
 *
 * @param string $redirect_to
 *
 * @return string
 */
function cp_social_connect_redirect_to( $redirect_to ) {
	if ( preg_match( '#/wp-(admin|login)?(.*?)$#i', $redirect_to ) ) {
		$redirect_to = home_url();
	}

	if ( current_theme_supports( 'app-login' ) ) {
		if ( APP_Login::get_url( 'redirect' ) == $redirect_to || appthemes_get_registration_url( 'redirect' ) == $redirect_to ) {
			$redirect_to = home_url();
		}
	}

	return $redirect_to;
}


/**
 * Process Social Connect request if App Login enabled.
 * @since 3.2
 *
 * @return void
 */
function cp_social_connect_login() {
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'social_connect' ) {
		if ( current_theme_supports( 'app-login' ) && function_exists( 'sc_social_connect_process_login' ) ) {
			sc_social_connect_process_login( false );
		}
	}
}


/**
 * Display reCatpcha if theme supports it.
 *
 * @since 3.5.2
 */
function cp_maybe_display_recaptcha() {
	global $cp_options;

	if ( ! $cp_options->captcha_enable ) {
		return;
	}

	appthemes_display_recaptcha();
}


/**
 * Controls password fields visibility.
 * @since 3.2
 *
 * @param bool $show_password
 *
 * @return bool
 */
function cp_password_fields_support( $show_password ) {
	global $cp_options;

	return (bool) $cp_options->allow_registration_password;
}


/**
 * Replaces default registration email.
 * @since 3.2
 *
 * @return void
 */
function cp_custom_registration_email() {
	remove_action( 'appthemes_after_registration', 'appthemes_new_user_notification', 10, 2 );
	add_action( 'appthemes_after_registration', 'cp_new_user_notification', 10, 2 );
}


/**
 * Redirects logged in users to homepage.
 * @since 3.2
 *
 * @return void
 */
function cp_redirect_to_home_page() {
	if ( ! isset( $_REQUEST['redirect_to'] ) ) {
		wp_redirect( home_url() );
		exit();
	}
}


/**
 * Displays 336 x 280 ad box on single page.
 * @since 3.3
 *
 * @return void
 */
function cp_adbox_336x280() {
	global $cp_options;

	if ( ! $cp_options->adcode_336x280_enable ) {
		return;
	}
?>
	<aside>
		<div class="content-wrap">
			<div class="content-inner">
				<h2 class="dotted"><?php _e( 'Sponsored Links', APP_TD ); ?></h2>
				<?php
				if ( ! empty( $cp_options->adcode_336x280 ) ) {
					echo stripslashes( $cp_options->adcode_336x280 );
				} elseif ( $cp_options->adcode_336x280_url ) {
					$img = html( 'img', array( 'src' => $cp_options->adcode_336x280_url, 'border' => '0', 'alt' => '' ) );
					echo html( 'a', array( 'href' => $cp_options->adcode_336x280_dest, 'target' => '_blank' ), $img );
				}
				?>
			</div><!-- /.content-inner -->
		</div><!-- /.content-wrap -->
	</aside>
<?php
}


/**
 * Displays 468 x 60 ad box in header.
 * @since 3.3
 *
 * @return void
 */
function cp_adbox_468x60() {
	global $cp_options;

	if ( ! $cp_options->adcode_468x60_enable ) {
		return;
	}

	if ( ! empty( $cp_options->adcode_468x60 ) ) {
		echo stripslashes( $cp_options->adcode_468x60 );
	} else {
		if ( ! $cp_options->adcode_468x60_url ) {
			$img = html( 'img', array( 'src' => get_template_directory_uri() . '/assets/images/468x60-banner.jpg', 'width' => '468', 'height' => '60', 'border' => '0', 'alt' => 'Premium WordPress Themes - AppThemes' ) );
			echo html( 'a', array( 'href' => 'https://www.appthemes.com', 'target' => '_blank' ), $img );
		} else {
			$img = html( 'img', array( 'src' => $cp_options->adcode_468x60_url, 'border' => '0', 'alt' => '' ) );
			echo html( 'a', array( 'href' => $cp_options->adcode_468x60_dest, 'target' => '_blank' ), $img );
		}
	}

}


/**
 * Disables WordPress 'auto-embeds' option.
 * @since 3.3
 *
 * @return void
 */
function cp_disable_auto_embeds() {
	global $cp_options;

	if ( ! $cp_options->disable_embeds ) {
		return;
	}

	remove_filter( 'the_content', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
}

/**
 * Pings 'update services' while publish ad listing.
 * @since 3.3
 */
add_action( 'publish_' . APP_POST_TYPE, '_publish_post_hook', 5, 1 );


/**
 * Closes comments for old ads.
 * @see WordPress->Settings->Discussion
 * @since 3.3
 *
 * @param array $post_types
 *
 * @return array
 */
function cp_close_comments_for_old_ads( $post_types ) {
	$post_types[] = APP_POST_TYPE;

	return $post_types;
}


/**
 * Changes drop down indentation on mobile devices.
 * @since 3.3.1
 *
 * @param string $dropdown
 *
 * @return string
 */
function cp_change_dropdown_indentation_on_mobile( $dropdown ) {
	if ( wp_is_mobile() ) {
		$dropdown = preg_replace( '/&nbsp;&nbsp;&nbsp;/', ' - ', $dropdown );
	}

	return $dropdown;
}


/**
 * Limits characters in the price field.
 * @since 3.3.1
 *
 * @param array $args
 *
 * @return array
 */
function cp_limit_characters_in_price_field( $args ) {
	$args['maxlength'] = 15;

	return $args;
}


/**
 * Moves social URLs into custom fields on user registration.
 * @since 3.3.2
 *
 * @param int $user_id
 *
 * @return void
 */
function cp_move_social_url_on_user_registration( $user_id ) {

	$user_info = get_userdata( $user_id );

	if ( empty( $user_info->user_url ) ) {
		return;
	}

	if ( preg_match( '#facebook.com#i', $user_info->user_url ) ) {
		wp_update_user( array ( 'ID' => $user_id, 'user_url' => '' ) );
		update_user_meta( $user_id, 'facebook_id', $user_info->user_url );
	}
}


/**
 * Make the options object instantly available in templates.
 * @since 3.3.2
 *
 * @return void
 */
function cp_set_default_template_vars() {
	global $cp_options;

	appthemes_add_template_var( 'cp_options', $cp_options );
}


/**
 * Adds version number in the header for troubleshooting.
 * @since 3.3.2
 *
 * @return void
 */
function cp_generator() {
	echo "\n\t" . '<meta name="generator" content="ClassiPress ' . CP_VERSION . '" />' . "\n";
}


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 *
 * @since 3.6.0
 */
function cp_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", get_bloginfo( 'pingback_url' ) );
	}
}


/**
 * Add an alternate rss feed url if Feedburner is provided. Otherwise use default.
 *
 * @since 3.6.0
 */
function cp_alternate_rss() {
	printf( '<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="%s">' . "\n", appthemes_get_feed_url() );
}


/**
 * Disables some WordPress features.
 * @since 3.3.2
 *
 * @return void
 */
function cp_disable_wp_features() {
	global $cp_options;

	// remove the WordPress version meta tag
	if ( $cp_options->remove_wp_generator ) {
		remove_action( 'wp_head', 'wp_generator' );
	}

	// remove the new 3.1 admin header toolbar visible on the website if logged in
	if ( $cp_options->remove_admin_bar ) {
		add_filter( 'show_admin_bar', '__return_false' );
	}

}


/**
 * Modify available buttons in html editor.
 * @since 3.3.3
 *
 * @param array $buttons
 * @param string $editor_id
 *
 * @return array
 */
function cp_editor_modify_buttons( $buttons, $editor_id ) {
	if ( is_admin() || ! is_array( $buttons ) ) {
		return $buttons;
	}

	$remove = array( 'wp_more', 'spellchecker' );

	return array_diff( $buttons, $remove );
}


/**
 * Displays report listing form.
 * @since 3.4
 *
 * @return void
 */
function cp_report_listing_button() {
	global $post;

	if ( ! is_singular( array( APP_POST_TYPE ) ) || !current_theme_supports( 'app-reports' ) ) {
		return;
	}

	if ( get_current_user_id() && get_current_user_id() === (int) get_post()->post_author && ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$form = appthemes_get_reports_form( $post->ID, 'post' );
	if ( ! $form ) {
		$content = '<a href="' . appthemes_get_login_url( 'display', get_permalink() ) . '" data-open="reports_modal_form" class="reports_form_link listing-icon" title="' . esc_attr__( 'Report problem', APP_TD ) . '"><i class="fa fa-bullhorn"></i><span class="screen-reader-text">' . __( 'Report problem', APP_TD ) . '</span></a>';
	} else {
		$close = '<button class="close-button" data-close aria-label="' . esc_attr__( 'Close modal', APP_TD ) . '" type="button"><span aria-hidden="true">&times;</span></button>';

		$content = '<a href="#" data-open="reports_modal_form" class="reports_form_link listing-icon" title="' . esc_attr__( 'Report problem', APP_TD ) . '"><i class="fa fa-bullhorn"></i><span class="screen-reader-text">' . __( 'Report problem', APP_TD ) . '</span></a>';
		$content .= '<div class="report-form reveal" id="reports_modal_form" data-reveal>' . $form . $close .'</div>';
	}

	echo $content;
}


/**
 * Deletes all meta and attachments related with the ad.
 *
 * @since 3.5
 *
 * @param int $post_id
 *
 * @return void
 */
function cp_delete_ad_meta( $post_id ) {
	global $wpdb;

	if ( APP_POST_TYPE != get_post_type( $post_id ) ) {
		return;
	}

	$attachments_query = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_parent = %d AND post_type='attachment'", $post_id );
	$attachments = $wpdb->get_results( $attachments_query );

	// delete all associated attachments
	if ( $attachments ) {
		foreach ( $attachments as $attachment ) {
			wp_delete_attachment( $attachment->ID, true );
		}
	}

}


/**
 * Overrides the excerpt read more text.
 *
 * @since 3.5
 *
 * @param string $text The more text
 *
 * @return string The overriden more text.
 */
function cp_ads_excerpt_more( $text ) {
	global $post;

	if ( empty( $post ) || APP_POST_TYPE != $post->post_type ) {
		return $text;
	}

	return ' ' . html( 'a class="moretag" href="'. get_permalink( $post->ID ) . '"', __( '[&hellip;]', APP_TD ) );
}


/**
 * Overrides the excerpt length.
 *
 * @since 3.5
 *
 * @param int $length The excerpt length
 *
 * @return int The overriden excerpt length.
 */
function cp_ads_excerpt_length( $length ) {
	global $post;

	if ( empty( $post ) || APP_POST_TYPE != $post->post_type ) {
		return $length;
	}

	return 25;
}

/**
 * Remove default notices markup.
 *
 * @since 3.5
 */
function _cp_remove_default_notices() {
	remove_action( 'appthemes_display_notice', array( 'APP_Notices', 'outputter' ), 10 );
}


/**
 * Prints notices.
 *
 * @since 3.5
 *
 * @param string $class CSS class of notice block.
 * @param array $msgs Messages to be displayed.
 * @return void
 */
function cp_output_notices( $class, $msgs ) {
?>
	<div class="notice <?php echo esc_attr( $class ); ?>">
		<?php foreach ( $msgs as $msg ) { ?>
			<div class="dashicons-before"><?php echo $msg; ?></div>
		<?php } ?>
	</div>
<?php
}

/**
 * Inserts the title and categories in the single listing page head.
 *
 * @since 4.0.0
 *
 * @return string
 */
function cp_add_listing_single_head() {
	global $post;

	the_title( '<h1 class="entry-title"> Listing Title: ', '</h1>' );
?>
	<p class="entry-title cp_price"> Price: <?php cp_get_price( $post->ID, 'cp_price' ); ?></p>

	<!--<div class="entry-categories">
		<?php //echo get_the_term_list( $post->ID, APP_TAX_CAT, '', ', ', '' ); ?>
	</div> .entry-categories -->
<?php
}

function cp_kana_get_header_info() {
	global $post;
	$post_id = $post->ID;
	$title = get_the_title();
    $ret_val = cp_get_price( $post->ID, 'cp_price', false );
    $test_val = "Hello";
	return strval($test_val);
}


/**
 * Returns data attributes for each listing
 * which are primarily used by the map info bubble.
 *
 * @since 4.0.0
 *
 * @return string
 */
function cp_listing_data_values() {
	global $post;

	$output = array();

	$make_address = get_post_meta( $post->ID, 'cp_street', true ) . ' ' . get_post_meta( $post->ID, 'cp_city', true ) . ' ' . get_post_meta( $post->ID, 'cp_state', true ) . ' ' . get_post_meta( $post->ID, 'cp_zipcode', true );
	$coordinates  = cp_get_geocode( $post->ID );

	$data['id']        = $post->ID;
	$data['title']     = the_title_attribute( array( 'post' => $post, 'echo' => false ) );
	$data['permalink'] = get_permalink( $post->ID );
	$data['address']   = $make_address;

	if ( $coordinates ) {
		$data['lat'] = (float) $coordinates['lat'];
		$data['lng'] = (float) $coordinates['lng'];
	}

	$image = wp_get_attachment_image_src( cp_get_featured_image_id( $post->ID ), 'thumbnail' );

	if ( is_array( $image ) && isset( $image[ 0 ] ) ) {
		$data['image'] = $image[ 0 ];
	} else {
		$data['image'] = get_template_directory_uri() . '/assets/images/placeholder.png';
	}

	// Build out the data attributes.
	foreach ( $data as $key => $value ) {
		$output[] .= sprintf( 'data-%s="%s"', $key, esc_attr( strip_tags( $value ) ) );
	}

	return implode( ' ', $output );
}

/**
 * Disable banner images if the banner disabled on the single ad listing page.
 *
 * @since 4.2.0
 *
 * @param bool $allow
 * @return bool
 */
function _cp_disallow_listing_banner_image( $allow ) {
	return get_theme_mod( 'show_listing_banner_image', 1 );
}

/**
 * Adds actions bar on the listing footer if listing banner is not enabled.
 */
function cp_single_listing_actions_bar() {
	if ( APP_POST_TYPE !== get_post_type() || get_theme_mod( 'show_listing_banner_image', 1 ) ) {
		return;
	}
	get_template_part( 'parts/content-action-bar', get_post_type() );
}
