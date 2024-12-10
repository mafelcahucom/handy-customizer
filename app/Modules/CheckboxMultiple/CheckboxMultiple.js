/**
 * Internal Dependencies
 */
import {
	queryElement,
	getExplodedValue,
	updateFieldValue,
	eventListener,
} from '../../../resources/scripts/helpers';

/**
 * Checkbox Multiple Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const CheckboxMultiple = {
	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onSelectCheckbox();
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
						selector: '.hacu-checkbox-multiple__input',
					},
				},
			} );
		}
	},

	/**
	 * On select checkbox & update field value based on selected item.
	 *
	 * @since 1.0.0
	 */
	onSelectCheckbox() {
		eventListener( 'change', '.hacu-checkbox-multiple__box', ( e ) => {
			const target = e.target;
			const value = target.value;
			const elements = CheckboxMultiple.elements( target );
			if ( ! elements || 0 === value.length ) {
				return;
			}

			const { inputElem } = elements;
			const checked = getExplodedValue( inputElem.value );
			if ( target.checked ) {
				if ( ! checked.includes( target.value ) ) {
					checked.push( value );
				}
			} else {
				const index = checked.indexOf( value );
				if ( -1 !== index ) {
					checked.splice( index, 1 );
				}
			}

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default CheckboxMultiple;
