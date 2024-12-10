/**
 * Update the hidden input value and dispatch change event.
 *
 * @since 1.0.0
 *
 * @param {Object} input Contains the target hidden input.
 * @param {*}      value Contains the new value of hidden input
 */
const updateFieldValue = ( input, value ) => {
	if ( input ) {
		input.value = value;
		input.dispatchEvent(
			new Event( 'change', {
				bubbles: true,
			} )
		);
	}
};

export default updateFieldValue;
