/**
 * Internal dependencies
 */
import {
	queryElement,
	getExplodedValue,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

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
						selector: '.hacu-image-checkbox__input',
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
		eventListener( 'click', '.hacu-image-checkbox__item', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = ImageCheckbox.elements( target );
			if ( ! elements || value.length === 0 || state.length === 0 ) {
				return;
			}

			const { inputElem } = elements;
			const checked = getExplodedValue( inputElem.value );
			if ( state === 'default' ) {
				if ( ! checked.includes( value ) ) {
					checked.push( value );
				}
				target.setAttribute( 'data-state', 'active' );
			}

			if ( state === 'active' ) {
				const index = checked.indexOf( value );
				if ( index !== -1 ) {
					checked.splice( index, 1 );
				}
				target.setAttribute( 'data-state', 'default' );
			}

			updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

export default ImageCheckbox;
