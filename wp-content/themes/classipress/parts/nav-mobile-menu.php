<?php
/**
 * Template part for mobile menu
 *
 * @since 4.0.0
 */

?>
<!-- off-canvas left menu -->
<div class="off-canvas dark position-<?php echo is_rtl() ? 'right' : 'left'; ?>" id="offCanvasLeft" data-off-canvas data-position="<?php echo is_rtl() ? 'right' : 'left'; ?>">

	<!-- Close button -->
	<button class="close-button" aria-label="<?php echo esc_attr_x( 'Close menu', 'aria-label', APP_TD ); ?>" type="button" data-close>
		<span aria-hidden="true">&times;</span>
	</button>

	<ul class="mobile-ofc vertical menu">
		<li class="title"><?php bloginfo( 'name' ); ?></li>
	</ul>

	<!-- Menu -->
	<?php $first_left = cp_header_menu_first_left( array( 'container' => '', 'echo' => false ) ); ?>
	<?php echo $first_left ? '<div class="mobile-hr"></div>' . $first_left : ''; ?>

	<!-- Menu -->
	<?php $second_left = cp_header_menu_secondary( array( 'echo' => false ) ); ?>
	<?php echo $second_left ? '<div class="mobile-hr"></div>' . $second_left : ''; ?>

</div>

<!-- off-canvas right menu -->
<div class="off-canvas dark position-<?php echo is_rtl() ? 'left' : 'right'; ?>" id="offCanvasRight" data-off-canvas data-position="<?php echo is_rtl() ? 'left' : 'right'; ?>">

	<!-- Close button -->
	<button class="close-button" aria-label="<?php echo esc_attr_x( 'Close menu', 'aria-label', APP_TD ); ?>" type="button" data-close>
		<span aria-hidden="true">&times;</span>
	</button>

	<ul class="mobile-ofc vertical menu">
		<li class="title"><?php bloginfo( 'name' ); ?></li>
	</ul>

	<!-- Menu -->
	<?php $first_right = cp_header_menu_first_right( array( 'container' => '', 'echo' => false ) ); ?>
	<?php echo $first_right ? '<div class="mobile-hr"></div>' . $first_right : ''; ?>

	<!-- Menu -->
	<?php $second_right = cp_header_menu_primary( array( 'container' => 'div', 'echo' => false ) ); ?>
	<?php echo $second_right ? '<div class="mobile-hr"></div>' . $second_right : ''; ?>

</div>
