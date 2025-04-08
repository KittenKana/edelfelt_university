<?php
/**
 * Hold deprecated functions and hooks.
 *
 * DO NOT UPDATE WITHOUT UPDATING ALL OTHER THEMES!
 * This is a shared file so changes need to be propagated to insure sync.
 *
 * @package ThemeFramework\Deprecated
 */


/**
 * Was adding an HTML tag with information about the favicon.
 *
 * @deprecated Use Site Icon feature
 * @see https://codex.wordpress.org/Creating_a_Favicon#WordPress_Version_4.3_or_later
 *
 * @return void
 */
function appthemes_favicon() {
	_deprecated_function( __FUNCTION__, '2017-01-16', 'Site Icon feature' );

	$uri = apply_filters( 'appthemes_favicon', appthemes_locate_template_uri( 'images/favicon.ico' ) );

	if ( ! $uri ) {
		return;
	}

?>
<link rel="shortcut icon" href="<?php echo esc_url( $uri ); ?>" />
<?php
}

