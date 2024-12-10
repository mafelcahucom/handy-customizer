/**
 * Internal dependencies
 */
import { queryElement, updateFieldValue } from '../../../resources/scripts/helpers';

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
	 * @param {Object} target Contains the target element.
	 * @return {Object|void} The required elements.
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
		jQuery( () => {
			const taggingElems = document.querySelectorAll( '.hacu-tagging__selectize' );
			if ( 0 < taggingElems.length ) {
				taggingElems.forEach( ( taggingElem ) => {
					const maximum = taggingElem.getAttribute( 'data-maximum' );
					const elements = Tagging.elements( taggingElem );
					if ( elements ) {
						const { inputElem } = elements;
						// eslint-disable-next-line require-jsdoc
						const getMaximum = () => {
							const parsedMax = parseInt( maximum );
							return parsedMax !== NaN && 0 < parsedMax ? parsedMax : null;
						};

						jQuery( taggingElem ).selectize( {
							plugins: [ 'remove_button', 'drag_drop' ],
							maxItems: getMaximum(),
							delimiter: ',',
							persist: false,
							// eslint-disable-next-line require-jsdoc
							create( value ) {
								return {
									value,
									text: value,
								};
							},
							// eslint-disable-next-line require-jsdoc
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
