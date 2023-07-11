/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
} from '../../../assets/src/js/helpers';

/**
 * Tagging Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Tagging = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.initSelectize();
	},

	/**
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @return {Object} The required elements.
	 */
	elements( target ) {
		if ( target ) {
			return queryElement( {
				target,
				elements: {
					input: {
						isSingle: true,
						selector: '.hacu-tagging__input',
					},
				},
			} );
		}
	},

	/**
	 * Initialize tagging using selectize.js
	 *
	 * @since 1.0.0
	 */
	initSelectize() {
		jQuery( function() {
			const taggingElems = document.querySelectorAll( '.hacu-tagging__selectize' );
			if ( taggingElems.length > 0 ) {
				taggingElems.forEach( function( taggingElem ) {
					const maximum = taggingElem.getAttribute( 'data-maximum' );
					const elements = Tagging.elements( taggingElem );
					if ( elements ) {
						const { inputElem } = elements;
						const getMaximum = function() {
							const parsedMax = parseInt( maximum );
							return ( parsedMax !== NaN && parsedMax > 0 ? parsedMax : null );
						};

						jQuery( taggingElem ).selectize( {
							plugins: [ 'remove_button', 'drag_drop' ],
							maxItems: getMaximum(),
							delimiter: ',',
							persist: false,
							create( value ) {
								return {
									value,
									text: value,
								};
							},
							onChange( value ) {
								updateFieldValue( inputElem, value );
							},
						} );
					}
				} );
			}
		} );
	},
};

export default Tagging;
