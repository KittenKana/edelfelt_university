<?php
/**
 * Listing single title template.
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

?>


<header class="entry-header">

	<?php cp_ad_loop_price( 'price-wrap h3' ); ?>

	<?php appthemes_before_post_title( get_post_type() ); ?>

	<?php the_title( '<h1 class="entry-title h2 m-b-2">', '</h1>' ); ?>

	<?php appthemes_after_post_title( get_post_type() ); ?>

</header>