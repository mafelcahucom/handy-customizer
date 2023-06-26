/**
 * Return the value of joined or imploded to exploded array.
 *
 * @since 1.0.0
 *
 * @param {string} value The value to be exploded as array.
 * @return {Array} The exploded value.
 */
const getExplodedValue = function( value ) {
	if ( value.length === 0 ) {
		return [];
	}

	return value.split( ',' );
};

export default getExplodedValue;
