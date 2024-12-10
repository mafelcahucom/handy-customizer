/**
 * Global event listener delegation.
 *
 * @since 1.0.0
 *
 * @param {string}   type     Contains the event type can be multiple seperate with space.
 * @param {string}   selector Contains the target element selector.
 * @param {Function} callback Contains the callback function.
 */
const eventListener = async ( type, selector, callback ) => {
	const events = type.split( ' ' );
	events.forEach( ( event ) => {
		document.addEventListener( event, ( e ) => {
			if ( e.target.matches( selector ) ) {
				callback( e );
			}
		} );
	} );
};

export default eventListener;
