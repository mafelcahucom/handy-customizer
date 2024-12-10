/**
 * Internal dependencies
 */
import {
	queryElement,
	setAttribute,
	updateFieldValue,
	eventListener,
} from '../../../resources/scripts/helpers';

/**
 * Image Radio Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ImageRadio = {
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
						selector: '.hacu-image-radio__input',
					},
				},
			} );
		}
	},

	/**
	 * On select item and update field value based on selected item
	 *
	 * @since 1.0.0
	 */
	onSelectItem() {
		eventListener( 'click', '.hacu-image-radio__item', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = ImageRadio.elements( target );
			if ( ! elements || 0 === value.length || 'default' !== state ) {
				return;
			}

			const { parentElem, inputElem } = elements;
			setAttribute.child( parentElem, '.hacu-image-radio__item', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );
			updateFieldValue( inputElem, value );
		} );
	},
};

export default ImageRadio;
