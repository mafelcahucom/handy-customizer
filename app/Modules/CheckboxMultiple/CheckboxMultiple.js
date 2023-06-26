/**
 * Internal Dependencies
 */
import {
	queryElement,
	getExplodedValue,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

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
		this.onChange();
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
						selector: '.hacu-checkbox-multiple__input',
					},
				},
			} );
		}
	},

	/**
	 * Update hidden input value based on checked checkbox.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		eventListener( 'change', '.hacu-checkbox-multiple__box', function( e ) {
			const target = e.target;
			const value = target.value;
			const elements = CheckboxMultiple.elements( target );
			if ( ! elements || value.length === 0 ) {
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
				if ( index !== -1 ) {
					checked.splice( index, 1 );
				}
			}

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default CheckboxMultiple;
