/**
 * Set up element's attribute.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const setAttribute = {
	/**
	 * Set the attribute of target element(s).
	 *
	 * @since 1.0.0
	 *
	 * @param {string} selector  Contains the selector of the target element(s).
	 * @param {string} attribute Contains the attribute to be set.
	 * @param {string} value     Contains the value of the attribute.
	 */
	elem( selector, attribute, value ) {
		if ( ! selector || ! attribute ) {
			return;
		}

		const elems = document.querySelectorAll( selector );
		if ( 0 < elems.length ) {
			elems.forEach( ( elem ) => {
				elem.setAttribute( attribute, value );
			} );
		}
	},

	/**
	 * Set the attribute of the target children element(s).
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} parent    Contains the target parent element.
	 * @param {string} selector  Contains the selector of the target child element(s).
	 * @param {string} attribute Contains the attribute to be set.
	 * @param {string} value     Contains the value of the attribute.
	 */
	child( parent, selector, attribute, value ) {
		if ( ! parent || ! selector || ! attribute ) {
			return;
		}

		const elems = parent.querySelectorAll( selector );
		if ( 0 < elems.length ) {
			elems.forEach( ( elem ) => {
				elem.setAttribute( attribute, value );
			} );
		}
	},
};

export default setAttribute;
