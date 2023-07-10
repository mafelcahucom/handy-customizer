/**
 * Internal Dependencies
 */
import {
	queryElement,
	isNumber,
	updateFieldValue,
	eventListener,
	setAttribute,
} from '../../../assets/src/js/helpers';

/**
 * Size Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Size = {

	/**
	 * Holds the keypress timer.
	 *
	 * @since 1.0.0
	 *
	 * @type {number}
	 */
	timer: null,

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onToggleDropdown();
		this.onSelectUnit();
		this.onChangeNumber();
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
						selector: '.hacu-size__input',
					},
					dropdown: {
						isSingle: true,
						selector: '.hacu-size__dropdown',
					},
					dropdownBtn: {
						isSingle: true,
						selector: '.hacu-size__dropdown-btn',
					},
				},
			} );
		}
	},

	/**
	 * Update the size value in the hidden input.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} input The hidden input element.
	 */
	updateValue( input ) {
		if ( input ) {
			const size = input.getAttribute( 'data-size' );
			const unit = input.getAttribute( 'data-unit' );
			updateFieldValue( input, `${ size }${ unit }` );
		}
	},

	/**
	 * On toggle dropdown button control
	 *
	 * @since 1.0.0
	 */
	onToggleDropdown() {
		eventListener( 'click', '.hacu-size__dropdown-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const elements = Size.elements( target );
			if ( ! elements ) {
				return;
			}

			const { dropdownElem } = elements;
			const state = target.getAttribute( 'data-state' );
			if ( [ 'closed', 'opened' ].includes( state ) ) {
				const updatedState = ( state === 'closed' ? 'opened' : 'closed' );
				target.setAttribute( 'data-state', updatedState );
				dropdownElem.setAttribute( 'data-state', updatedState );
			}
		} );
	},

	/**
	 * Update or select the unit size in hidden input.
	 *
	 * @since 1.0.0
	 */
	onSelectUnit() {
		eventListener( 'click', '.hacu-size__dropdown__li', function( e ) {
			e.preventDefault();
			const target = e.target;
			const elements = Size.elements( target );
			if ( ! elements ) {
				return;
			}

			// inputElem. dropdownElem, dropdownBtnElem
			const { parentElem, inputElem, dropdownElem, dropdownBtnElem } = elements;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			if ( state === 'default' || value.length > 0 ) {
				setAttribute.child( parentElem, '.hacu-size__dropdown__li', 'data-state', 'default' );
				target.setAttribute( 'data-state', 'active' );

				dropdownBtnElem.textContent = value;
				dropdownBtnElem.setAttribute( 'data-state', 'closed' );
				dropdownElem.setAttribute( 'data-state', 'closed' );

				inputElem.setAttribute( 'data-unit', value );
				Size.updateValue( inputElem );
			}
		} );
	},

	/**
	 * Update or type the number size value.
	 *
	 * @since 1.0.0
	 */
	onChangeNumber() {
		const callback = function( e ) {
			const target = e.target;
			const elements = Size.elements( target );
			if ( ! elements ) {
				return;
			}

			const { inputElem } = elements;
			clearTimeout( Size.timer );
			Size.timer = setTimeout( function() {
				const value = ( isNumber( target.value ) ? target.value : '' );
				target.value = value;
				inputElem.setAttribute( 'data-size', value );
				Size.updateValue( inputElem );
			}, 500 );
		};

		eventListener( 'keyup', '.hacu-size__number', callback );
		eventListener( 'paste', '.hacu-size__number', callback );
	},
};

export default Size;
