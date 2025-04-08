<?php
/**
 * Comments content template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

// If comments are open or there's at least one comment.
if ( comments_open() || get_comments_number() ) :
?>

<div class="comments-wrap content-wrap">

	<div class="content-inner">

		<?php comments_template(); ?>

	</div> <!-- .content-inner -->

</div> <!-- .comments-wrap -->

<?php
endif;
