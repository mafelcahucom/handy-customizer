/**
 * Provides a useful `Object` data type checker utilities.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const isObject = {
	/**
	 * Checks if the object is empty.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} object Contains the object object to be checked.
	 * @return {boolean} The flag whether a certain key is empty.
	 */
	empty( object ) {
		return 0 === Object.keys( object ).length;
	},

	/**
	 * Checks if the object has a missing key, if has found
	 * a missing key return true.
	 *
	 * @since 1.0.0
	 *
	 * @param {Array}  keys   Contains the list of keys use as referrence.
	 * @param {Object} object Contains the object to be checked.
	 * @return {boolean|void} The flag whether a certain key is missing.
	 */
	hasMissingKey( keys, object ) {
		if ( 0 === keys.length || this.empty( object ) ) {
			return;
		}

		let hasMissing = false;
		keys.forEach( ( key ) => {
			if ( ! object.hasOwnProperty( key ) ) {
				hasMissing = true;
			}
		} );

		return hasMissing;
	},
};

export default isObject;
