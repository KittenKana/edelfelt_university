<?php
/**
 * Hold deprecated functions and hooks
 *
 * DO NOT UPDATE WITHOUT UPDATING ALL OTHER THEMES!
 * This is a shared file so changes need to be propagated to insure sync
 *
 * @package Framework\Deprecated
 */



/**
 * @deprecated
 *
 */
function appthemes_page_comment() {
	_deprecated_function( __FUNCTION__, '2012-11-30' );

	do_action( 'appthemes_page_comment' );
}


/**
 * @deprecated
 *
 */
function appthemes_blog_comment() {
	_deprecated_function( __FUNCTION__, '2012-11-30' );

	do_action( 'appthemes_blog_comment' );
}


/**
 * @deprecated
 *
 */
function appthemes_comment() {
	_deprecated_function( __FUNCTION__, '2012-11-30' );

	do_action( 'appthemes_comment' );
}


/**
 * invokes the 468x60 advertise section, called in header
 *
 * @deprecated Use appthemes_advertise_header()
 * @see appthemes_advertise_header()
 */
function appthemes_header_ad_468x60() {
	_deprecated_function( __FUNCTION__, '2012-11-30', 'appthemes_advertise_header()' );

	return appthemes_advertise_header();
}


/**
 * invokes the 336x280 advertise section, called in content
 *
 * @deprecated Use appthemes_advertise_content()
 * @see appthemes_advertise_content()
 */
function appthemes_single_ad_336x280() {
	_deprecated_function( __FUNCTION__, '2012-11-30', 'appthemes_advertise_content()' );

	return appthemes_advertise_content();
}


/**
 * determines whether multisite support is enabled
 *
 * @deprecated Use is_multisite()
 * @see is_multisite()
 */
function appthemes_is_wpmu() {
	_deprecated_function( __FUNCTION__, '2013-01-22', 'is_multisite()' );

	return is_multisite();
}


/**
 * inserts line breaks before new lines
 *
 * @deprecated Use nl2br()
 * @see nl2br()
 */
function appthemes_nl2br( $string ) {
	_deprecated_function( __FUNCTION__, '2013-01-22', 'nl2br()' );

	return nl2br( $string );
}


/**
 * deprecated action and filter hooks
 *
 */
appthemes_deprecate_hook( 'app_importer_import_row_post', 'appthemes_importer_import_row_post', '2014-01-14', 'filter', 1 );
appthemes_deprecate_hook( 'app_importer_import_row_post_meta', 'appthemes_importer_import_row_post_meta', '2014-01-14', 'filter', 1 );
appthemes_deprecate_hook( 'app_importer_import_row_after', 'appthemes_importer_import_row_after', '2014-01-14', 'action', 2 );
appthemes_deprecate_hook( 'app_plupload_config', 'appthemes_plupload_config', '2014-10-21', 'filter', 1 );


/**
 * Page action hooks
 *
 */


/**
 * called in page.php before the loop executes
 * @deprecated since 2017-12-21, use "appthemes_before_loop( 'page' )"
 */
function appthemes_before_page_loop() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_loop( 'page' )" );

	appthemes_before_loop( 'page' );
}


/**
 * called in page.php before the page post section
 * @deprecated since 2017-12-21, use "appthemes_before_post( 'page' )"
 */
function appthemes_before_page() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post( 'page' )" );

	appthemes_before_post( 'page' );
}


/**
 * called in page.php before the page post title tag
 * @deprecated since 2017-12-21, use "appthemes_before_post_title( 'page' )"
 */
function appthemes_before_page_title() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post_title( 'page' )" );

	appthemes_before_post_title( 'page' );
}


/**
 * called in page.php after the page post title tag
 * @deprecated since 2017-12-21, use "appthemes_after_post_title( 'page' )"
 */
function appthemes_after_page_title() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post_title( 'page' )" );

	appthemes_after_post_title( 'page' );
}


/**
 * called in page.php before the page post content
 * @deprecated since 2017-12-21, use "appthemes_before_post_content( 'page' )"
 */
function appthemes_before_page_content() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post_content( 'page' )" );

	appthemes_before_post_content( 'page' );
}


/**
 * called in page.php after the page post content
 * @deprecated since 2017-12-21, use "appthemes_after_post_content( 'page' )"
 */
function appthemes_after_page_content() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post_content( 'page' )" );

	appthemes_after_post_content( 'page' );
}


/**
 * called in page.php after the page post section
 * @deprecated since 2017-12-21, use "appthemes_after_post( 'page' )"
 */
function appthemes_after_page() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post( 'page' )" );

	appthemes_after_post( 'page' );
}


/**
 * called in page page.php after the loop endwhile
 * @deprecated since 2017-12-21, use "appthemes_after_endwhile( 'page' )"
 */
function appthemes_after_page_endwhile() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_endwhile( 'page' )" );

	appthemes_after_endwhile( 'page' );
}


/**
 * called in page page.php after the loop else
 * @deprecated since 2017-12-21, use "appthemes_loop_else( 'page' )"
 */
function appthemes_page_loop_else() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_loop_else( 'page' )" );

	appthemes_loop_else( 'page' );
}


/**
 * called in page page.php after the loop executes
 * @deprecated since 2017-12-21, use "appthemes_after_loop( 'page' )"
 */
function appthemes_after_page_loop() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_loop( 'page' )" );

	appthemes_after_loop( 'page' );
}


/**
 * called in page comments-page.php before the comments list block
 * @deprecated since 2017-12-21, use "appthemes_before_comments( 'page' )"
 */
function appthemes_before_page_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_comments( 'page' )" );

	appthemes_before_comments( 'page' );
}


/**
 * called in page comments-page.php in the ol block
 * @deprecated since 2017-12-21, use "appthemes_list_comments( 'page' )"
 */
function appthemes_list_page_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_list_comments( 'page' )" );

	appthemes_list_comments( 'page' );
}


/**
 * called in page comments-page.php after the comments list block
 * @deprecated since 2017-12-21, use "appthemes_after_comments( 'page' )"
 */
function appthemes_after_page_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_comments( 'page' )" );

	appthemes_after_comments( 'page' );
}


/**
 * called in page comments.php before the pings list block
 * @deprecated since 2017-12-21, use "appthemes_before_pings( 'page' )"
 */
function appthemes_before_page_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_pings( 'page' )" );

	appthemes_before_pings( 'page' );
}


/**
 * called in page comments.php in the ol block
 * @deprecated since 2017-12-21, use "appthemes_list_pings( 'page' )"
 */
function appthemes_list_page_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_list_pings( 'page' )" );

	appthemes_list_pings( 'page' );
}


/**
 * called in page comments.php after the pings list block
 * @deprecated since 2017-12-21, use "appthemes_after_pings( 'page' )"
 */
function appthemes_after_page_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_pings( 'page' )" );

	appthemes_after_pings( 'page' );
}


/**
 * called in page comments-page.php before the comments respond block
 * @deprecated since 2017-12-21, use "appthemes_before_respond( 'page' )"
 */
function appthemes_before_page_respond() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_respond( 'page' )" );

	appthemes_before_respond( 'page' );
}


/**
 * called in page comments-page.php after the comments respond block
 * @deprecated since 2017-12-21, use "appthemes_after_respond( 'page' )"
 */
function appthemes_after_page_respond() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_respond( 'page' )" );

	appthemes_after_respond( 'page' );
}


/**
 * called in page comments-page.php before the comments form block
 * @deprecated since 2017-12-21, use "appthemes_before_comments_form( 'page' )"
 */
function appthemes_before_page_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_comments_form( 'page' )" );

	appthemes_before_comments_form( 'page' );
}


/**
 * called in page comments-page.php to include the comments form block
 * @deprecated since 2017-12-21, use "appthemes_comments_form( 'page' )"
 */
function appthemes_page_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_comments_form( 'page' )" );

	appthemes_comments_form( 'page' );
}


/**
 * called in page comments-page.php after the comments form block
 * @deprecated since 2017-12-21, use "appthemes_after_comments_form( 'page' )"
 */
function appthemes_after_page_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_comments_form( 'page' )" );

	appthemes_after_comments_form( 'page' );
}



/**
 * Blog action hooks
 *
 */


/**
 * called in loop.php before the loop executes
 * @deprecated since 2017-12-21, use "appthemes_before_loop( 'post' )"
 */
function appthemes_before_blog_loop() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_loop( 'post' )" );

	do_action( 'appthemes_before_blog_loop' );
}


/**
 * called in loop.php before the blog post section
 * @deprecated since 2017-12-21, use "appthemes_before_post( 'post' )"
 */
function appthemes_before_blog_post() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post( 'post' )" );

	do_action( 'appthemes_before_blog_post' );
}


/**
 * called in loop.php before the blog post title tag
 *
 * @deprecated since 2017-12-21, use "appthemes_before_post_title( 'post' )"
 */
function appthemes_before_blog_post_title() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post_title( 'post' )" );

	do_action( 'appthemes_before_blog_post_title' );
}


/**
 * called in loop.php after the blog post title tag
 *
 * @deprecated since 2017-12-21, use "appthemes_after_post_title( 'post' )"
 */
function appthemes_after_blog_post_title() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post_title( 'post' )" );

	do_action( 'appthemes_after_blog_post_title' );
}


/**
 * called in loop.php before the blog post content
 *
 * @deprecated since 2017-12-21, use "appthemes_before_post_content( 'post' )"
 */
function appthemes_before_blog_post_content() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_post_content( 'post' )" );

	do_action( 'appthemes_before_blog_post_content' );
}


/**
 * called in loop.php after the blog post content
 *
 * @deprecated since 2017-12-21, use "appthemes_after_post_content( 'post' )"
 */
function appthemes_after_blog_post_content() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post_content( 'post' )" );

	do_action( 'appthemes_after_blog_post_content' );
}


/**
 * called in loop.php after the blog post section
 *
 * @deprecated since 2017-12-21, use "appthemes_after_post( 'post' )"
 */
function appthemes_after_blog_post() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_post( 'post' )" );

	do_action( 'appthemes_after_blog_post' );
}


/**
 * called in blog loop.php after the loop endwhile
 *
 * @deprecated since 2017-12-21, use "appthemes_after_endwhile( 'post' )"
 */
function appthemes_after_blog_endwhile() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_endwhile( 'post' )" );

	do_action( 'appthemes_after_blog_endwhile' );
}


/**
 * called in blog loop.php after the loop else
 *
 * @deprecated since 2017-12-21, use "appthemes_loop_else( 'post' )"
 */
function appthemes_blog_loop_else() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_loop_else( 'post' )" );

	do_action( 'appthemes_blog_loop_else' );
}


/**
 * called in blog loop.php after the loop executes
 *
 * @deprecated since 2017-12-21, use "appthemes_after_loop( 'post' )"
 */
function appthemes_after_blog_loop() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_loop( 'post' )" );

	do_action( 'appthemes_after_blog_loop' );
}


/**
 * called in blog comments-blog.php before the comments list block
 *
 * @deprecated since 2017-12-21, use "appthemes_before_comments( 'post' )"
 */
function appthemes_before_blog_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_comments( 'post' )" );

	do_action( 'appthemes_before_blog_comments' );
}


/**
 * called in blog comments.php in the ol block
 *
 * @deprecated since 2017-12-21, use "appthemes_list_comments( 'post' )"
 */
function appthemes_list_blog_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_list_comments( 'post' )" );

	do_action( 'appthemes_list_blog_comments' );
}


/**
 * called in blog comments-blog.php after the comments list block
 *
 * @deprecated since 2017-12-21, use "appthemes_after_comments( 'post' )"
 */
function appthemes_after_blog_comments() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_comments( 'post' )" );

	do_action( 'appthemes_after_blog_comments' );
}


/**
 * called in blog comments.php before the pings list block
 *
 * @deprecated since 2017-12-21, use "appthemes_before_pings( 'post' )"
 */
function appthemes_before_blog_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_pings( 'post' )" );

	do_action( 'appthemes_before_blog_pings' );
}


/**
 * called in blog comments.php in the ol block
 *
 * @deprecated since 2017-12-21, use "appthemes_list_pings( 'post' )"
 */
function appthemes_list_blog_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_list_pings( 'post' )" );

	do_action( 'appthemes_list_blog_pings' );
}


/**
 * called in blog comments.php after the pings list block
 *
 * @deprecated since 2017-12-21, use "appthemes_after_pings( 'post' )"
 */
function appthemes_after_blog_pings() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_pings( 'post' )" );

	do_action( 'appthemes_after_blog_pings' );
}


/**
 * called in blog comments-blog.php before the comments respond block
 *
 * @deprecated since 2017-12-21, use "appthemes_before_respond( 'post' )"
 */
function appthemes_before_blog_respond() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_respond( 'post' )" );

	do_action( 'appthemes_before_blog_respond' );
}


/**
 * called in blog comments-blog.php after the comments respond block
 *
 * @deprecated since 2017-12-21, use "appthemes_after_respond( 'post' )"
 */
function appthemes_after_blog_respond() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_respond( 'post' )" );

	do_action( 'appthemes_after_blog_respond' );
}


/**
 * called in blog comments-blog.php before the comments form block
 *
 * @deprecated since 2017-12-21, use "appthemes_before_comments_form( 'post' )"
 */
function appthemes_before_blog_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_before_comments_form( 'post' )" );

	do_action( 'appthemes_before_blog_comments_form' );
}


/**
 * called in blog comments-blog.php to include the comments form block
 *
 * @deprecated since 2017-12-21, use "appthemes_comments_form( 'post' )"
 */
function appthemes_blog_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_comments_form( 'post' )" );

	do_action( 'appthemes_blog_comments_form' );
}


/**
 * called in blog comments-blog.php after the comments form block
 *
 * @deprecated since 2017-12-21, use "appthemes_after_comments_form( 'post' )"
 */
function appthemes_after_blog_comments_form() {
	_deprecated_function( __FUNCTION__, '2017-12-21', "appthemes_after_comments_form( 'post' )" );

	do_action( 'appthemes_after_blog_comments_form' );
}


appthemes_deprecate_hook( 'appthemes_before_blog_loop', 'appthemes_before_post_loop', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_post', 'appthemes_before_post', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_post_title', 'appthemes_before_post_title', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_post_title', 'appthemes_after_post_title', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_post_content', 'appthemes_before_post_content', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_post_content', 'appthemes_after_post_content', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_post', 'appthemes_after_post', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_endwhile', 'appthemes_after_post_endwhile', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_blog_loop_else', 'appthemes_post_loop_else', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_loop', 'appthemes_after_post_loop', '2017-12-21' );

appthemes_deprecate_hook( 'appthemes_before_blog_comments', 'appthemes_before_post_comments', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_list_blog_comments', 'appthemes_list_post_comments', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_comments', 'appthemes_after_post_comments', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_pings', 'appthemes_before_post_pings', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_list_blog_pings', 'appthemes_list_post_pings', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_pings', 'appthemes_after_post_pings', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_respond', 'appthemes_before_post_respond', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_respond', 'appthemes_after_post_respond', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_before_blog_comments_form', 'appthemes_before_post_comments_form', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_blog_comments_form', 'appthemes_post_comments_form', '2017-12-21' );
appthemes_deprecate_hook( 'appthemes_after_blog_comments_form', 'appthemes_after_post_comments_form', '2017-12-21' );

