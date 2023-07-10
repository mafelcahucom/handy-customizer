/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

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
	 * On selecting radio and update field value based on selected radio
	 *
	 * @since 1.0.0
	 */
	onSelectRadio() {
		eventListener( 'change', '.hacu-radio__box', function( e ) {
			const target = e.target;
			const value = target.value;
			const elements = Radio.elements( target );
			if ( ! elements || value.length === 0 ) {
				return;
			}

			const { inputElem, radioElems } = elements;
			if ( radioElems.length > 0 ) {
				radioElems.forEach( function( radioElem ) {
					radioElem.checked = false;
				} );
			}

			target.checked = true;
			updateFieldValue( inputElem, value );
		} );
	},
};

export default Radio;
