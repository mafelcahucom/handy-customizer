/**
 * Internal Dependencies
 */
import {
	queryElement,
	isValidHexaColor,
	setAttribute,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

/**
 * Color Set Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ColorSet = {

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
						selector: '.hacu-color-set__input',
					},
				},
			} );
		}
	},

	/**
	 * On selecting item and update field value based on selected item.
	 *
	 * @since 1.0.0
	 */
	onSelectItem() {
		eventListener( 'click', '.hacu-color-set__item', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = ColorSet.elements( target );
			if ( ! elements || state !== 'default' || value.length === 0 ) {
				return;
			}

			if ( isValidHexaColor( value ) ) {
				const { parentElem, inputElem } = elements;
				setAttribute.child( parentElem, '.hacu-color-set__item', 'data-state', 'default' );
				target.setAttribute( 'data-state', 'active' );

				updateFieldValue( inputElem, value );
			}
		} );
	},
};

export default ColorSet;
