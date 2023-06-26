/**
 * Check if the value is a valid number.
 *
 * @since 1.0.0
 *
 * @param {*} value The value to be checked.
 * @return {boolean} Flag if value is number.
 */
const isNumber = function( value ) {
	if ( value === null || value === undefined || value.length === 0 ) {
		return false;
	}

	return ! isNaN( value ) && ! isNaN( parseFloat( value ) );
};

export default isNumber;
