/**
 * Check if the color string contains a valid hexadecimal color.
 *
 * @since 1.0.0
 *
 * @param {string} color The string color to be check.
 * @return {boolean} Flag if color is valid hexadecimal color
 */
const isValidHexaColor = function( color ) {
	let isValid = false;
	if ( typeof ( color ) === 'string' ) {
		isValid = /^#([a-f0-9]{3}|[a-f0-9]{6}|[a-f0-9]{8})$/i.test( color );
	}

	return isValid;
};

export default isValidHexaColor;
