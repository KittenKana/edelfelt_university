<?php
/**
 * Template to display widget "ClassiPress - Author Bio".
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

$author     = get_queried_object();
$authordesc = $author->description;

cp_author_info( 'page' );

if ( $authordesc ) {
	?>
	<div class="content-wrap author-desc-wrap">
		<div class="content-inner">
			<?php echo wpautop( $authordesc ); ?>
		</div>
	</div>
	<?php
}

/**
 * Fires after the author description.
 *
 * @since 4.0.0
 *
 * @param array $author The author array.
 */
do_action( 'cp_author_profile_after_about', $author );
