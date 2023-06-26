/**
 * Object data type checker.
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
	 * @param {Object} object The object to be checked.
	 * @return {boolean} Whether has empty key.
	 */
	empty( object ) {
		return Object.keys( object ).length === 0;
	},

	/**
	 * Checks if the object has a missing key, if has found
	 * a missing key return true.
	 *
	 * @since 1.0.0
	 *
	 * @param {Array}  keys   The list of keys use as referrence.
	 * @param {Object} object The object to be checked.
	 */
	hasMissingKey( keys, object ) {
		if ( keys.length === 0 || this.empty( object ) ) {
			return;
		}

		let hasMissing = false;
		keys.forEach( function( key ) {
			if ( ! object.hasOwnProperty( key ) ) {
				hasMissing = true;
			}
		} );

		return hasMissing;
	},
};

export default isObject;
