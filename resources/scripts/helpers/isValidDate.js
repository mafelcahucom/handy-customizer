/**
 * Check whether the newly instantiated date is valid.
 *
 * @since 1.0.0
 *
 * @param {string} date Contains the string date to be check.
 * @return {boolean} The flag whether date is valid
 */
const isValidDate = ( date ) => {
	return date instanceof Date && ! isNaN( date );
};

export default isValidDate;
