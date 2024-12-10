/**
 * Checks whether a data is a valid number.
 *
 * @since 1.0.0
 *
 * @param {*} value Contains the data to be validated.
 * @return {booean} The flag whether the data is a valid number.
 */
const isNumber = ( value ) => {
	// eslint-disable-next-line no-undefined
	if ( null === value || value === undefined || 0 === value.length ) {
		return false;
	}

	return ! isNaN( value ) && ! isNaN( parseFloat( value ) );
};

export default isNumber;
