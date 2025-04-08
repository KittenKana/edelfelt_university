<?php
/**
 * Sidebar
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 *
 * @author WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! is_singular( 'product' ) ) {
	return;
}

get_sidebar( 'shop' );
?>
