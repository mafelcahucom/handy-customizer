/**
 * Update the hidden input value and dispatch change event.
 *
 * @since 1.0.0
 *
 * @param {Object} input The target hidden input.
 * @param {*}      value The new value of hidden input.
 */
const updateFieldValue = function( input, value ) {
	if ( input ) {
		input.value = value;
		input.dispatchEvent( new Event( 'change', {
			bubbles: true,
		} ) );
	}
};

export default updateFieldValue;
