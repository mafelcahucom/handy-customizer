/**
 * Internal Dependencies
 */
import {
	queryElement,
	setAttribute,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

/**
 * Button Set Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ButtonSet = {

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
						selector: '.hacu-button-set__input',
					},
				},
			} );
		}
	},

	/**
	 * On selecting item & update field value based on selected item.
	 *
	 * @since 1.0.0
	 */
	onSelectItem() {
		eventListener( 'click', '.hacu-button-set__item-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = ButtonSet.elements( target );
			if ( ! elements || value.length === 0 || state !== 'default' ) {
				return;
			}

			const { parentElem, inputElem } = elements;
			updateFieldValue( inputElem, value );
			setAttribute.child( parentElem, '.hacu-button-set__item-btn', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );
		} );
	},
};

export default ButtonSet;
