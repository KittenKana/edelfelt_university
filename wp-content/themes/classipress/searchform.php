<?php
/**
 * The blog search form template file
 *
 * @package ClassiPress\Templates
 * @since 4.2.0
 */

?>

<form method="get" class="search-form" action="<?php echo home_url( '/' ); ?>" role="search">

	<div class="input-group">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', APP_TD ); ?></span>
		<input type="search" class="input-group-field" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', APP_TD ); ?>">
		<div class="input-group-button">
			<input type="submit" class="search-submit button" value="<?php echo esc_attr_x( 'Search', 'submit button', APP_TD ); ?>">
		</div>
	</div>

</form>
