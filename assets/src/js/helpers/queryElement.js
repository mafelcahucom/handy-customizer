/**
 * Internal dependencies
 */
import isObject from './isObject';

/**
 * Query the required element of field.
 *
 * @since 1.0.0
 *
 * @param {Object} params          Contains the parameters needed required element of field.
 * @param {Object} params.target   The target element.
 * @param {Object} params.elements The list of required elements.
 * @return {Object} The required elements.
 */
const queryElement = function( params = {} ) {
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
	Object.entries( params.elements ).forEach( function( value ) {
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

	return ( hasNotFound ? false : elements );
};

export default queryElement;
