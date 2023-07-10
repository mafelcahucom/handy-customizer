/**
 * Global event listener delegation.
 *
 * @since 1.0.0
 *
 * @param {string}   type     Event type can be multiple seperate with space.
 * @param {string}   selector Target element.
 * @param {Function} callback Callback function
 */
const eventListener = async function( type, selector, callback ) {
	const events = type.split( ' ' );
	events.forEach( function( event ) {
		document.addEventListener( event, function( e ) {
			if ( e.target.matches( selector ) ) {
				callback( e );
			}
		} );
	} );
};

export default eventListener;
