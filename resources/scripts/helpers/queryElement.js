/**
 * Internal dependencies
 */
import isObject from './isObject';

/**
 * Return the queried elements from a target parent element.
 *
 * @since 1.0.0
 *
 * @param {Object} params          Contains the necessary parameters needed to query elements.
 * @param {Object} params.target   Contains the target element.
 * @param {Object} params.elements Contains the list of required elements
 * @return {Object} The queried elements.
 */
const queryElement = ( params = {} ) => {
	const required = [ 'target', 'elements' ];
	if ( isObject.empty( params ) || isObject.hasMissingKey( required, params ) ) {
		return false;
	}

	if ( isObject.empty( params.elements ) ) {
		return false;
	}

	const targetClassname = params.target.getAttribute( 'class' );
	if ( ! targetClassname ) {
		return false;
	}

	const classname = targetClassname.split( '__' )[ 0 ];
	const parentElem = params.target.closest( `.${ classname }` );
	if ( ! parentElem ) {
		return false;
	}

	const elements = {};
	let hasNotFound = false;
	Object.entries( params.elements ).forEach( ( value ) => {
		const { isSingle, selector } = value[ 1 ];
		if ( isSingle ) {
			const elem = parentElem.querySelector( selector );
			if ( elem ) {
				elements[ `${ value[ 0 ] }Elem` ] = elem;
			} else {
				hasNotFound = true;
			}
		} else {
			const elems = parentElem.querySelectorAll( selector );
			elements[ `${ value[ 0 ] }Elems` ] = elems;
		}
	} );
	elements.parentElem = parentElem;

	return hasNotFound ? false : elements;
};

export default queryElement;
