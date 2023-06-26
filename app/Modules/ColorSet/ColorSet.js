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
		this.onChange();
		this.onChangeToDefault();
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
					preview: {
						isSingle: true,
						selector: '.hacu-color-set__preview',
					},
					label: {
						isSingle: true,
						selector: '.hacu-color-set__label',
					},
				},
			} );
		}
	},

	/**
	 * Update hidden input value based on selected color item.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		eventListener( 'click', '.hacu-color-set__item', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = ColorSet.elements( target );
			if ( ! elements || state !== 'default' || value.length === 0 ) {
				return;
			}

			if ( ! isValidHexaColor( value ) ) {
				return;
			}

			const { parentElem, inputElem, labelElem, previewElem } = elements;
			labelElem.textContent = value;
			previewElem.style.backgroundColor = value;

			setAttribute.child( parentElem, '.hacu-color-set__item', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );

			updateFieldValue( inputElem, value );
		} );
	},

	/**
	 * Update hidden input value to the default color value.
	 *
	 * @since 1.0.0
	 */
	onChangeToDefault() {
		eventListener( 'click', '.hacu-color-set__default-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const elements = ColorSet.elements( target );
			if ( ! elements || value.length === 0 ) {
				return;
			}

			if ( ! isValidHexaColor( value ) ) {
				return;
			}

			const { parentElem, inputElem, labelElem, previewElem } = elements;
			if ( inputElem.value !== value ) {
				labelElem.textContent = value;
				previewElem.style.backgroundColor = value;

				setAttribute.child( parentElem, '.hacu-color-set__item', 'data-state', 'default' );
				setAttribute.child( parentElem, `.hacu-color-set__item[data-value="${ value }"]`, 'data-state', 'active' );

				updateFieldValue( inputElem, value );
			}
		} );
	},
};

export default ColorSet;
