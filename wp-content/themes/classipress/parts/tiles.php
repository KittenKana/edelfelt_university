<?php
/**
 * Template to display the Tiles layout.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

if ( ! isset( $app_sidebar_tiles ) ) {
	return;
}

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
			<div class="column medium-<?php echo (int) $tile['size']; ?>">
				<?php echo $tile['content']; ?>
			</div>
			<?php } ?>
		</div>
		<?php
	}
	?>
	<?php
}
