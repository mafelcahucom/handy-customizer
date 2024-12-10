/**
 * Internal dependencies
 */
import {
	queryElement,
	isNumber,
	updateFieldValue,
	eventListener,
} from '../../../resources/scripts/helpers';

/**
 * Counter Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Counter = {
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
		this.onExecuteEvent();
		this.onInputChange();
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
						selector: '.hacu-counter__input',
					},
					decrementBtn: {
						isSingle: true,
						selector: 'button[data-event="-"]',
					},
					incrementBtn: {
						isSingle: true,
						selector: 'button[data-event="+"]',
					},
				},
			} );
		}
	},

	/**
	 * on execute the event decrementing and incrementing
	 *
	 * @since 1.0.0
	 */
	onExecuteEvent() {
		eventListener( 'click', '.hacu-counter__control-btn', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const event = target.getAttribute( 'data-event' );
			const elements = Counter.elements( target );
			if ( ! elements || 'default' !== state || ! [ '-', '+' ].includes( event ) ) {
				return;
			}

			const { inputElem, decrementBtnElem, incrementBtnElem } = elements;
			let value = inputElem.value;
			let min = inputElem.getAttribute( 'data-min' );
			let max = inputElem.getAttribute( 'data-max' );
			let step = inputElem.getAttribute( 'data-step' );
			const isValidMinMax = isNumber( min ) && isNumber( max );

			value = isNumber( value ) ? parseFloat( value ) : 0;
			min = isNumber( min ) ? parseFloat( min ) : 0;
			max = isNumber( max ) ? parseFloat( max ) : 0;
			step = isNumber( step ) ? parseFloat( step ) : 1;

			/* eslint-disable indent */
			switch ( event ) {
				case '-':
					if ( isValidMinMax ) {
						if ( value > min ) {
							value -= step;
							incrementBtnElem.setAttribute( 'data-state', 'default' );
						}

						if ( value <= min ) {
							value = min;
							decrementBtnElem.setAttribute( 'data-state', 'disabled' );
						}
					} else {
						value -= step;
					}
					break;
				case '+':
					if ( isValidMinMax ) {
						if ( value < max ) {
							value += step;
							decrementBtnElem.setAttribute( 'data-state', 'default' );
						}

						if ( value >= max ) {
							value = max;
							incrementBtnElem.setAttribute( 'data-state', 'disabled' );
						}
					} else {
						value += step;
					}
					break;
			}
			/* eslint-enable */

			updateFieldValue( inputElem, value );
		} );
	},

	/**
	 * On execute event when input value change.
	 *
	 * @since 1.0.0
	 */
	onInputChange() {
		// eslint-disable-next-line require-jsdoc
		const callback = ( e ) => {
			const target = e.target;
			const elements = Counter.elements( target );
			if ( ! elements ) {
				return;
			}

			const { decrementBtnElem, incrementBtnElem } = elements;
			let min = target.getAttribute( 'data-min' );
			let max = target.getAttribute( 'data-max' );
			const isValidMinMax = isNumber( min ) && isNumber( max );

			clearTimeout( Counter.timer );
			Counter.timer = setTimeout( () => {
				let value = target.value;
				if ( isNumber( value ) ) {
					if ( isValidMinMax ) {
						min = parseFloat( min );
						max = parseFloat( max );
						value = parseFloat( value );

						if ( value < min ) {
							target.value = min;
							decrementBtnElem.setAttribute( 'data-state', 'disabled' );
							incrementBtnElem.setAttribute( 'data-state', 'default' );
						}

						if ( value > max ) {
							target.value = max;
							incrementBtnElem.setAttribute( 'data-state', 'disabled' );
							decrementBtnElem.setAttribute( 'data-state', 'default' );
						}

						if ( value > min && value < max ) {
							decrementBtnElem.setAttribute( 'data-state', 'default' );
							incrementBtnElem.setAttribute( 'data-state', 'default' );
						}
					}
				} else {
					target.value = isValidMinMax ? min : 0;
				}
			}, 500 );
		};

		eventListener( 'keyup', '.hacu-counter__input', callback );
		eventListener( 'paste', '.hacu-counter__input', callback );
	},
};

export default Counter;
