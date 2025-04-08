<?php

/**
 * Content Ad Block template.
 *
 * @package ClassiPress\Templates
 * @since 4.0.0
 */

?>

<?php if ( $cp_options->adcode_336x280_enable ) { ?>

	<div class="row columns adblock">

		<div class="text-center">

			<?php appthemes_advertise_content(); ?>

		</div>

	</div><!-- /adblock -->

<?php }
