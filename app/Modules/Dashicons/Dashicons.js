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
 * Dashicons Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Dashicons = {

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
						selector: '.hacu-dashicons__input',
					},
				},
			} );
		}
	},

	/**
	 * On selecting item and update field value based on selected item
	 *
	 * @since 1.0.0
	 */
	onSelectItem() {
		eventListener( 'click', '.hacu-dashicons__item', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = Dashicons.elements( target );
			if ( ! elements || value.length === 0 || state !== 'default' ) {
				return;
			}

			const { parentElem, inputElem } = elements;
			updateFieldValue( inputElem, value );
			setAttribute.child( parentElem, '.hacu-dashicons__item', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );
		} );
	},
};

export default Dashicons;
