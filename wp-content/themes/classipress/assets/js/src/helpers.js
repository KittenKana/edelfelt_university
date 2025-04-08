/**
 * General helper scripts.
 *
 * @since 4.0.0
 */

/**
 * Convert bytes into readable human file sizes.
 *
 * @param int bytes Raw number we want to convert.
 *
 * @since 4.0.0
 */
function humanFileSize( bytes ) {
	var thresh = 1000;

	if ( Math.abs( bytes ) < thresh ) {
		return bytes + ' B';
	}

	var units = [ 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB' ];
	var u = -1;

	do {
		bytes /= thresh;
		++u;
	} while ( Math.abs( bytes ) >= thresh && u < units.length - 1 );

	return bytes.toFixed( 0 ) + ' ' + units[ u ];
}
