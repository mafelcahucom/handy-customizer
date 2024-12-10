/**
 * Return the value of joined or imploded to exploded array.
 *
 * @since 1.0.0
 *
 * @param {string} value Contains the value to be exploded as array.
 * @return {Array} The exploded value
 */
const getExplodedValue = ( value ) => {
	if ( 0 === value.length ) {
		return [];
	}

	return value.split( ',' );
};

export default getExplodedValue;
