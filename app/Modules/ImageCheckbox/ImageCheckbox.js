/**
 * Internal dependencies
 */
import {
	queryElement,
	getExplodedValue,
	updateFieldValue,
	eventListener,
} from '../../../resources/scripts/helpers';

/**
 * Image Checkbox Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ImageCheckbox = {
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
						selector: '.hacu-image-checkbox__input',
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
		eventListener( 'click', '.hacu-image-checkbox__item', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = ImageCheckbox.elements( target );
			if ( ! elements || 0 === value.length || 0 === state.length ) {
				return;
			}

			const { inputElem } = elements;
			const checked = getExplodedValue( inputElem.value );
			if ( 'default' === state ) {
				if ( ! checked.includes( value ) ) {
					checked.push( value );
				}
				target.setAttribute( 'data-state', 'active' );
			}

			if ( 'active' === state ) {
				const index = checked.indexOf( value );
				if ( -1 !== index ) {
					checked.splice( index, 1 );
				}
				target.setAttribute( 'data-state', 'default' );
			}

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default ImageCheckbox;
