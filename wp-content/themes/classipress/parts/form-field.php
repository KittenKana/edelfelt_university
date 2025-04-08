<?php
/**
 * Template for displaying a single form field.
 *
 * @global array  $app_field_array The field parameters.
 * @global string $app_field_token The actual field input token (like "%input%")
 *
 * Template usage:
 *  - `form-field.php`               - for all fields
 *  - `form-field-{$field_type}.php` - for fields of particular type.
 *
 * @package Listing\Templates
 * @author  AppThemes
 * @since   Listing 1.0
 */

// @codeCoverageIgnoreStart
$name = scbForms::get_name( $app_field_array->field_name );

?>
<div id="list_<?php echo esc_attr( $name ); ?>" class="form-field <?php echo esc_attr( sanitize_title( $app_field_array->field_type ) ); ?>">
	<label for="<?php echo esc_attr( $name ); ?>">
		<?php echo esc_html( translate( $app_field_array->field_label, APP_TD ) ); ?> <?php if ( $app_field_array->field_req ) echo '<span class="colour">(*)</span>'; ?>
	</label>

	<?php echo $app_field_token; ?>

	<?php if ( ! empty( $app_field_array->field_tooltip ) ) { ?>
		<p class="help-text" id="<?php echo esc_attr( $name ); ?>HelpText"><?php echo translate( $app_field_array->field_tooltip, APP_TD ); ?></p>
	<?php } ?>
</div>
