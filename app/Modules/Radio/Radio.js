/**
 * Internal dependencies
 */
import { queryElement, updateFieldValue, eventListener } from '../../../resources/scripts/helpers';

/**
 * Radio Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Radio = {
	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onSelectRadio();
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
						selector: '.hacu-radio__input',
					},
					radio: {
						isSingle: false,
						selector: '.hacu-radio__box',
					},
				},
			} );
		}
	},

	/**
	 * On select radio and update field value based on selected radio
	 *
	 * @since 1.0.0
	 */
	onSelectRadio() {
		eventListener( 'change', '.hacu-radio__box', ( e ) => {
			const target = e.target;
			const value = target.value;
			const elements = Radio.elements( target );
			if ( ! elements || 0 === value.length ) {
				return;
			}

			const { inputElem, radioElems } = elements;
			if ( 0 < radioElems.length ) {
				radioElems.forEach( ( radioElem ) => {
					radioElem.checked = false;
				} );
			}

			target.checked = true;
			updateFieldValue( inputElem, value );
		} );
	},
};

export default Radio;
