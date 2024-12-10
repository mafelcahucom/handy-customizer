/**
 * Internal dependencies
 */
import { queryElement, updateFieldValue } from '../../../resources/scripts/helpers';

/**
 * Tagging Select Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const TaggingSelect = {
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
						selector: '.hacu-tagging-select__input',
					},
				},
			} );
		}
	},

	/**
	 * Initialize tagging select using selectize.js
	 *
	 * @since 1.0.0
	 */
	initSelectize() {
		jQuery( () => {
			const taggingSelectElems = document.querySelectorAll(
				'.hacu-tagging-select__selectize'
			);

			if ( 0 < taggingSelectElems.length ) {
				taggingSelectElems.forEach( ( taggingSelectElem ) => {
					const maximum = taggingSelectElem.getAttribute( 'data-maximum' );
					const elements = TaggingSelect.elements( taggingSelectElem );
					if ( elements ) {
						const { inputElem } = elements;
						// eslint-disable-next-line require-jsdoc
						const getMaximum = () => {
							const parsedMax = parseInt( maximum );
							return parsedMax !== NaN && 0 < parsedMax ? parsedMax : null;
						};

						jQuery( taggingSelectElem ).selectize( {
							plugins: [ 'remove_button', 'drag_drop' ],
							maxItems: getMaximum(),
							delimiter: ',',
							persist: true,
							create: false,
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

export default TaggingSelect;
