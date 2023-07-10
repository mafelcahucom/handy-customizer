/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
} from '../../../assets/src/js/helpers';

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
		const taggingSelectElems = document.querySelectorAll( '.hacu-tagging-select__selectize' );
		if ( taggingSelectElems.length > 0 ) {
			taggingSelectElems.forEach( function( taggingSelectElem ) {
				const maximum = taggingSelectElem.getAttribute( 'data-maximum' );
				const elements = TaggingSelect.elements( taggingSelectElem );
				if ( elements ) {
					const { inputElem } = elements;
					const getMaximum = function() {
						const parsedMax = parseInt( maximum );
						return ( parsedMax !== NaN && parsedMax > 0 ? parsedMax : null );
					};

					$( taggingSelectElem ).selectize( {
						plugins: [ 'remove_button', 'drag_drop' ],
						maxItems: getMaximum(),
						delimiter: ',',
						persist: true,
						create: false,
						onChange( value ) {
							updateFieldValue( inputElem, value );
						},
					} );
				}
			} );
		}
	},
};

export default TaggingSelect;
