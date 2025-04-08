<?php
/**
 * Template to display widget "ClassiPress - Page Callout Box".
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

?>

<div class="callout-wrap" style="background-color: <?php echo esc_attr( $instance['background_color'] ); ?>;">

<?php
// We're not using a background cover image or centered text.
if ( ( 'pull' === $instance['background'] ) && ( 'text-center' !== $instance['text_align'] ) ) : ?>

	<div class="row expanded small-collapse" data-equalizer>
		<div class="callout-content-wrap columns <?php echo esc_attr( $instance['text_align'] ); ?>" style="padding: <?php echo esc_attr( $instance['text_padding'] ); ?>rem 0;" data-equalizer-watch>
			<div class="row">
				<div class="small-10 medium-centered columns">
					<h2 class="callout-title" style="color: <?php echo esc_attr( $instance['text_color'] ); ?>;"><?php echo $instance['header']; ?></h2>
					<div class="callout-content" style="color: <?php echo esc_attr( $instance['text_color'] ); ?>;"><?php echo $instance['content']; ?></div>
				</div>
			</div> <!-- .row -->
		</div> <!-- .callout-content -->
		<div class="callout-image medium-6 columns" style="<?php echo esc_attr( $instance['image_css'] ); ?>" data-equalizer-watch></div>
	</div> <!-- .row-fluid -->

<?php else : ?>

	<div class="callout-cover-wrap">
		<div class="callout-cover <?php echo esc_attr( $instance['has_image'] ); ?>">
			<div class="row">
				<div class="callout-content-wrap small-12 columns <?php echo esc_attr( $instance['text_align'] ); ?>" style="padding-top: <?php echo esc_attr( $instance['text_padding'] ); ?>rem; padding-bottom: <?php echo esc_attr( $instance['text_padding'] ); ?>rem;">
					<h2 class="callout-title" style="color: <?php echo esc_attr( $instance['text_color'] ); ?>;"><?php echo $instance['header']; ?></h2>
					<div class="callout-content" style="color: <?php echo esc_attr( $instance['text_color'] ); ?>;"><?php echo wpautop( $instance['content'] ); ?></div>
				</div> <!-- .callout-content-wrap -->
			</div> <!-- .row -->
		</div> <!-- .callout-cover -->
	</div> <!-- .callout-cover-wrap -->

<?php endif; ?>

</div> <!-- .callout-wrap -->
