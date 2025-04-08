<?php
/**
 * Customizer Custom Sanitize functions
 *
 * Forked by AppThemes.
 *
 * @package ThemeFramework
 *
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 * @version: 1.0.6
 */

/**
 * URL sanitization
 *
 * @param string $input Input to be sanitized (either a string containing a
 *                      single url or multiple, separated by commas).
 *
 * @return string Sanitized input
 */
function appthemes_customizer_url_sanitization( $input ) {
	$input = explode( ',', $input );
	$input = array_map( 'trim', $input );
	$input = array_map( 'esc_url_raw', $input );
	$input = implode( ',', $input );

	return $input;
}

/**
 * Radio Button and Select sanitization
 *
 * @param string               $input   Input to be sanitized.
 * @param WP_Customize_Setting $setting Setting object.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_radio_sanitization( $input, $setting ) {
	// get the list of possible radio box or select options.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	if ( array_key_exists( $input, $choices ) ) {
		return $input;
	} else {
		return $setting->default;
	}
}

/**
 * Text sanitization
 *
 * @param string $input Input to be sanitized.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_text_sanitization( $input ) {
	$input = explode( ',', $input );
	$input = array_map( 'sanitize_text_field', $input );
	$input = implode( ',', $input );

	return $input;
}

/**
 * Integer sanitization
 *
 * @param string $input Input to be sanitized.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_integer_sanitization( $input ) {
	$input = explode( ',', $input );
	$input = array_map( 'intval', $input );
	$input = implode( ',', $input );

	return $input;
}

/**
 * Alpha Color (Hex & RGBa) sanitization
 *
 * @param string               $input   Input to be sanitized.
 * @param WP_Customize_Setting $setting Setting object.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_hex_rgba_sanitization( $input, $setting ) {
	if ( empty( $input ) || is_array( $input ) ) {
		return $setting->default;
	}

	if ( false === strpos( $input, 'rgba' ) ) {
		// If string doesn't start with 'rgba' then santize as hex color.
		$input = sanitize_hex_color( $input );
	} else {
		// Sanitize as RGBa color.
		$input = str_replace( ' ', '', $input );
		sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		$input = 'rgba(' . appthemes_customizer_in_range( $red, 0, 255 ) . ',' . appthemes_customizer_in_range( $green, 0, 255 ) . ',' . appthemes_customizer_in_range( $blue, 0, 255 ) . ',' . appthemes_customizer_in_range( $alpha, 0, 1 ) . ')';
	}
	return $input;
}

/**
 * Only allow values between a certain minimum & maxmium range.
 *
 * @param mixed $input Input to be sanitized.
 * @param mixed $min   Min value.
 * @param mixed $max   Max value.
 *
 * @return mixed
 */
function appthemes_customizer_in_range( $input, $min, $max ) {
	return min( max( $input, $min ), $max );
}

/**
 * Google Font sanitization
 *
 * @param string $input Input to be sanitized.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_google_font_sanitization( $input ) {
	$val = json_decode( $input, true );
	if ( is_array( $val ) ) {
		foreach ( $val as $key => $value ) {
			$val[ $key ] = sanitize_text_field( $value );
		}
		$input = json_encode( $val );
	} else {
		$input = json_encode( sanitize_text_field( $val ) );
	}
	return $input;
}

/**
 * Date Time sanitization
 *
 * @param string               $input   Input to be sanitized.
 * @param WP_Customize_Setting $setting Setting object.
 *
 * @return string Sanitized input.
 */
function appthemes_customizer_date_time_sanitization( $input, $setting ) {
	$datetimeformat = 'Y-m-d';
	if ( $setting->manager->get_control( $setting->id )->include_time ) {
		$datetimeformat = 'Y-m-d H:i:s';
	}
	$date = DateTime::createFromFormat( $datetimeformat, $input );
	if ( false === $date ) {
		$date = DateTime::createFromFormat( $datetimeformat, $setting->default );
	}
	return $date->format( $datetimeformat );
}

/**
 * Slider sanitization
 *
 * @param string               $input   Slider value to be sanitized.
 * @param WP_Customize_Setting $setting Slider value to be sanitized.
 *
 * @return string Sanitized input
 */
function appthemes_customizer_range_sanitization( $input, $setting ) {
	$attrs = $setting->manager->get_control( $setting->id )->input_attrs;

	$min = ( isset( $attrs['min'] ) ? $attrs['min'] : $input );
	$max = ( isset( $attrs['max'] ) ? $attrs['max'] : $input );
	$step = ( isset( $attrs['step'] ) ? $attrs['step'] : 1 );

	$number = floor( $input / $step ) * $step;

	return appthemes_customizer_in_range( $number, $min, $max );
}
