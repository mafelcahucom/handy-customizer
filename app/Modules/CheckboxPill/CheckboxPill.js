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
		this.onSelectItem();
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
						selector: '.hacu-checkbox-pill__input',
					},
				},
			} );
		}
	},

	/**
	 * On select item & update field value based on selected item.
	 *
	 * @since 1.0.0
	 */
	onSelectItem() {
		eventListener( 'click', '.hacu-checkbox-pill__item-btn', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = CheckboxPill.elements( target );
			if ( ! elements || 0 === state.length || 0 === value.length ) {
				return;
			}

			const { inputElem } = elements;
			const checked = getExplodedValue( inputElem.value );
			/* eslint-disable indent */
			switch ( state ) {
				case 'default':
					if ( ! checked.includes( value ) ) {
						checked.push( value );
						target.setAttribute( 'data-state', 'active' );
					}
					break;
				case 'active':
					const index = checked.indexOf( value );
					if ( -1 !== index ) {
						checked.splice( index, 1 );
						target.setAttribute( 'data-state', 'default' );
					}
					break;
			}
			/* eslint-enable */

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default CheckboxPill;
