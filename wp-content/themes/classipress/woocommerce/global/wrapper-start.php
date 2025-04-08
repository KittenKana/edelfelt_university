<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/*if ( is_singular( 'product' ) ) {
	$is_active_sidebar = is_active_sidebar( 'widget-area-sidebar-product' );
} else {
	$is_active_sidebar = is_active_sidebar( 'widget-area-sidebar-shop' );
}*/

$is_active_sidebar = true;

$class = ( $is_active_sidebar ) ? 'm-large-8' : 'small-12';
?>
<div class="content-area row">

	<div id="primary" class="<?php echo esc_attr( $class ); ?> columns">

		<main id="main" class="site-main" role="main">
