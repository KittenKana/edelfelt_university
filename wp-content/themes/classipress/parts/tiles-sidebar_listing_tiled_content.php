<?php
/**
 * Template to display the Tiles layout for the Single Listing page.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

if ( empty( $app_sidebar_tiles ) ) {
	return;
}
?>

<div class="widget single-listing-tiled-content">
	<?php
	if ( ! get_theme_mod( 'show_listing_banner_image', 1 ) ) {
		get_template_part( 'parts/content-title', get_post_type() );
	}
	?>

	<?php
	foreach ( $app_sidebar_tiles as $row ) {

		if ( 12 < $row[0]['size'] ) {
			?>
			<div class="tiled-row clearfix">
				<?php echo $row[0]['content']; ?>
			</div>
			<?php
		} else {
			?>
			<div class="tiled-row row">
				<?php foreach ( $row as $tile ) { ?>
				<div class="column large-<?php echo (int) $tile['size']; ?>">
					<?php echo $tile['content']; ?>
				</div>
				<?php } ?>
			</div>
			<?php
		}
		?>
		<?php
	}
	?>
</div>
