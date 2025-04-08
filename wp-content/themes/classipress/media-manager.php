<?php
/**
 * Media Manager template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<div id="<?php echo esc_attr( $atts['id'] ); ?>" class="row <?php echo esc_attr( $atts['class'] ); ?>">

	<div class="medium-6 columns">
		<label>

			<?php
			if ( $atts['title'] ) {
				echo $atts['title'];
			}

			if ( ! empty ( $filters['file_size'] ) ) { ?>
				<p class="image-size-limit text-muted text-small"><?php printf( __( '(Max size: %s)', APP_TD ), esc_html( size_format( (int) $filters['file_size'] ) ) ); ?></p>
			<?php
			}

			?>
			<input type="button" name="cover_image_btn" group_id="<?php echo esc_attr( $atts['id'] ); ?>" class="button upload_button" upload_text="<?php echo esc_attr( $atts['upload_text'] ); ?>" manage_text="<?php echo esc_attr( $atts['manage_text'] ); ?>" value="<?php echo esc_attr( $atts['button_text'] ); ?>">
		</label>
	</div>

	<div class="medium-6 columns">
		<div id="<?php echo esc_attr( $atts['id'] ); ?>" class="media_placeholder">
			<?php if ( empty( $atts['attachment_ids'] ) && empty( $atts['embed_urls'] ) ) { ?>
				<div class="no-media">
					<?php echo $atts['no_media_text']; ?>
				</div>
			<?php } ?>

			<div class="media-attachments">
				<?php appthemes_output_attachments( $atts['attachment_ids'], $atts['attachment_params'] ); ?>
			</div>
			<div class="media-embeds">
				<?php appthemes_output_embed_urls( $atts['embed_urls'], $atts['embed_params'] ); ?>
			</div>
		</div>
	</div>

</div><!-- .row -->
