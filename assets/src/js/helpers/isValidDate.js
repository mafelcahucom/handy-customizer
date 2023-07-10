/**
 * Check if the newly instantiated date is valid.
 *
 * @since 1.0.0
 *
 * @param {string} date The string date to be check.
 * @return {boolean} Flag if date is valid
 */
const isValidDate = function( date ) {
	return date instanceof Date && ! isNaN( date );
};

export default isValidDate;
