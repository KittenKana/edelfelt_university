<?php
/**
 * Media Manager attachment template.
 *
 * @global int  $app_attachment_id
 * @global bool $app_show_description
 * @global bool $app_show_image_thumbs
 *
 * @package Framework\Media-Manager\Templates
 */

$attachment = get_post( $app_attachment_id );
$file       = appthemes_get_attachment_meta( $app_attachment_id, $app_show_description );
$file       = array_merge( $file, array(
	'caption'     => $attachment->post_excerpt,
	'description' => $attachment->post_content,
) );

$title = $app_show_description ? $file['title'] : '';

$mime_type = explode( '/', $file['mime_type'] );

$wrapper_class = ( 'image' === $mime_type[0] && $app_show_image_thumbs ) ? '' : 'file-extension ' . appthemes_get_mime_type_icon_class( $file['mime_type'] );

?>
<div class="media-attachment <?php echo esc_attr( $wrapper_class ); ?>">
	<?php
	if ( 'image' === $mime_type[0] && $app_show_image_thumbs ) {
		echo wp_get_attachment_image( $app_attachment_id, 'thumb' ) . ' ';
	}
	?>

	<a href="<?php echo esc_url( $file['url'] ); ?>" title="<?php echo esc_attr( $file['title'] ); ?>" alt="<?php echo esc_attr( $file['alt'] ); ?>" target="_blank"><?php echo $title; ?></a>

	<?php
	if ( $app_show_description && $attachment ) {
		?>
		<p class="file-description"><?php echo $file['description']; ?></p>
		<?php
	}
	?>
</div>
<?php
