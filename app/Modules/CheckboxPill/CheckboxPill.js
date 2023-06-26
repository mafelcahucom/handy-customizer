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
 * Checkbox Pill Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const CheckboxPill = {

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
						selector: '.hacu-checkbox-pill__input',
					},
				},
			} );
		}
	},

	/**
	 * Update hidden input value based on checked pills.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		eventListener( 'click', '.hacu-checkbox-pill__item-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = CheckboxPill.elements( target );
			if ( ! elements || state.length === 0 || value.length === 0 ) {
				return;
			}

			const { inputElem } = elements;
			const checked = getExplodedValue( inputElem.value );
			switch ( state ) {
				case 'default':
					if ( ! checked.includes( value ) ) {
						checked.push( value );
						target.setAttribute( 'data-state', 'active' );
					}
					break;
				case 'active':
					const index = checked.indexOf( value );
					if ( index !== -1 ) {
						checked.splice( index, 1 );
						target.setAttribute( 'data-state', 'default' );
					}
					break;
			}

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default CheckboxPill;
