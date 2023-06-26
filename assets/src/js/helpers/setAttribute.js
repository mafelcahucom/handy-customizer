/**
 * Set element attribute.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const setAttribute = {

	/**
	 * Set the attribute of target elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} selector  The element selector.
	 * @param {string} attribute The Attribute to be set.
	 * @param {string} value     The value of the attribute.
	 */
	elem( selector, attribute, value ) {
		if ( ! selector || ! attribute ) {
			return;
		}

		const elems = document.querySelectorAll( selector );
		if ( elems.length > 0 ) {
			elems.forEach( function( elem ) {
				elem.setAttribute( attribute, value );
			} );
		}
	},

	/**
	 * Set the children attribute of target elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} parent    The parent element.
	 * @param {string} selector  The element selector.
	 * @param {string} attribute The Attribute to be set.
	 * @param {string} value     The value of the attribute.
	 */
	child( parent, selector, attribute, value ) {
		if ( ! parent || ! selector || ! attribute ) {
			return;
		}

		const elems = parent.querySelectorAll( selector );
		if ( elems.length > 0 ) {
			elems.forEach( function( elem ) {
				elem.setAttribute( attribute, value );
			} );
		}
	},
};

export default setAttribute;
