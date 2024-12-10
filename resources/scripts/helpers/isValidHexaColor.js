/**
 * Checks whether the color is a valid hexa color.
 *
 * @since 1.0.0
 *
 * @param {string} color Contains the color to be validated.
 * @return {booean} The flag whether the color is a valid hexa color.
 */
const isValidHexaColor = ( color ) => {
	let isValid = false;
	if ( 'string' === typeof color ) {
		isValid = /^#([a-f0-9]{3}|[a-f0-9]{6}|[a-f0-9]{8})$/i.test( color );
	}

	return isValid;
};

export default isValidHexaColor;
