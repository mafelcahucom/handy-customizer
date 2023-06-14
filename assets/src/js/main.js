/**
 * Strict mode.
 *
 * @since 1.0.0
 *
 * @author Mafel John Cahucom
 */

/**
 * Strict mode.
 *
 * @since 1.0.0
 *
 * @author Mafel John Cahucom
 */
'use strict';

/**
 * Namespace.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const hacu = hacu || {};

/**
 * Helper.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.fn = {

	/**
	 * Global event listener delegation.
	 *
	 * @since 1.0.0
	 *
	 * @param {string}   type     Event type can be multiple seperate with space.
	 * @param {string}   selector Target element.
	 * @param {Function} callback Callback function.
	 */
	async eventListener( type, selector, callback ) {
		const events = type.split( ' ' );
		events.forEach( function( event ) {
			document.addEventListener( event, function( e ) {
				if ( e.target.matches( selector ) ) {
					callback( e );
				}
			} );
		} );
	},

	/**
	 * Check if the value is a valid number.
	 *
	 * @since 1.0.0
	 *
	 * @param {*} value The value to be checked.
	 * @return {boolean} Flag if value is number.
	 */
	isNumber( value ) {
		if ( value === null || value === undefined || value.length === 0 ) {
			return false;
		}

		return ! isNaN( value ) && ! isNaN( parseFloat( value ) );
	},

	/**
	 * Return the value of joined or imploded to exploded array.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} value The value to be exploded as array.
	 * @return {Array} The exploded value.
	 */
	getExplodedValue( value ) {
		if ( value.length === 0 ) {
			return [];
		}

		return value.split( ',' );
	},

	/**
	 * Dispatch or trigger on chage event to an element.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} element The target element to dispatched.
	 */
	trigger( element ) {
		if ( element ) {
			element.dispatchEvent( new Event( 'change', {
				bubbles: true,
			} ) );
		}
	},
};

/**
 * Checkbox Multiple Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.checkboxMultiple = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onChange();
	},

	/**
	 * Update hidden input value based on checked checkbox.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		hacu.fn.eventListener( 'change', '.hacu-checkbox-multiple__box', function( e ) {
			const target = e.target;
			const value = target.value;
			const parentElem = target.closest( '.hacu-checkbox-multiple' );
			const inputElem = parentElem.querySelector( '.hacu-checkbox-multiple__input' );
			if ( value.length === 0 || ! parentElem || ! inputElem ) {
				return;
			}

			const checked = hacu.fn.getExplodedValue( inputElem.value );
			if ( target.checked ) {
				if ( ! checked.includes( target.value ) ) {
					checked.push( value );
				}
			} else {
				const index = checked.indexOf( value );
				if ( index !== -1 ) {
					checked.splice( index, 1 );
				}
			}

			inputElem.value = checked.join( ',' );
			hacu.fn.trigger( inputElem );
		} );
	},
};

/**
 * Counter Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.counter = {

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
	 * Execute the event decrementing and incrementing.
	 *
	 * @since 1.0.0
	 */
	onExecuteEvent() {
		hacu.fn.eventListener( 'click', '.hacu-counter__control-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const event = target.getAttribute( 'data-event' );
			const parentElem = target.closest( '.hacu-counter' );
			const inputElem = parentElem.querySelector( '.hacu-counter__input' );
			if ( ! parentElem || ! inputElem || state !== 'default' || ! [ '-', '+' ].includes( event ) ) {
				return;
			}

			const decrementBtnElem = parentElem.querySelector( 'button[data-event="-"]' );
			const incrementBtnElem = parentElem.querySelector( 'button[data-event="+"]' );
			if ( ! decrementBtnElem || ! incrementBtnElem ) {
				return;
			}

			let value = inputElem.value;
			let min = inputElem.getAttribute( 'data-min' );
			let max = inputElem.getAttribute( 'data-max' );
			let step = inputElem.getAttribute( 'data-step' );
			const isValidMinMax = ( hacu.fn.isNumber( min ) && hacu.fn.isNumber( max ) );

			value = ( hacu.fn.isNumber( value ) ? parseFloat( value ) : 0 );
			min = ( hacu.fn.isNumber( min ) ? parseFloat( min ) : 0 );
			max = ( hacu.fn.isNumber( max ) ? parseFloat( max ) : 0 );
			step = ( hacu.fn.isNumber( step ) ? parseFloat( step ) : 1 );

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

			inputElem.value = value;
			hacu.fn.trigger( inputElem );
		} );
	},

	/**
	 * Execute event when input value change.
	 *
	 * @since 1.0.0
	 */
	onInputChange() {
		const callback = function( e ) {
			const target = e.target;
			const parentElem = target.closest( '.hacu-counter' );
			const decrementBtnElem = parentElem.querySelector( 'button[data-event="-"]' );
			const incrementBtnElem = parentElem.querySelector( 'button[data-event="+"]' );
			if ( ! parentElem || ! decrementBtnElem || ! incrementBtnElem ) {
				return;
			}

			let min = target.getAttribute( 'data-min' );
			let max = target.getAttribute( 'data-max' );
			const isValidMinMax = ( hacu.fn.isNumber( min ) && hacu.fn.isNumber( max ) );

			clearTimeout( hacu.counter.timer );
			hacu.counter.timer = setTimeout( function() {
				let value = target.value;
				if ( hacu.fn.isNumber( value ) ) {
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
					target.value = ( isValidMinMax ? min : 0 );
				}
			}, 500 );
		};

		hacu.fn.eventListener( 'keyup', '.hacu-counter__input', callback );
		hacu.fn.eventListener( 'paste', '.hacu-counter__input', callback );
	},
};

/**
 * Radio Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.radio = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onChange();
	},

	/**
	 * Update hidden input value based on checked radio.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		hacu.fn.eventListener( 'change', '.hacu-radio__box', function( e ) {
			const target = e.target;
			const value = target.value;
			const parentElem = target.closest( '.hacu-radio' );
			const inputElem = parentElem.querySelector( '.hacu-radio__input' );
			if ( value.length === 0 || ! parentElem || ! inputElem ) {
				return;
			}

			const radioElems = parentElem.querySelectorAll( '.hacu-radio__box' );
			if ( radioElems.length > 0 ) {
				radioElems.forEach( function( radioElem ) {
					radioElem.checked = false;
				} );
			}

			target.checked = true;
			inputElem.value = value;
			hacu.fn.trigger( inputElem );
		} );
	},
};

/**
 * Size Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.size = {

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
			input.value = `${ size }${ unit }`;
			hacu.fn.trigger( input );
		}
	},

	/**
	 * On toggle dropdown button control.
	 *
	 * @since 1.0.0
	 */
	onToggleDropdown() {
		hacu.fn.eventListener( 'click', '.hacu-size__dropdown-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const parentElem = target.closest( '.hacu-size' );
			const dropdownElem = parentElem.querySelector( '.hacu-size__dropdown' );
			if ( ! parentElem || ! dropdownElem ) {
				return;
			}

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
		hacu.fn.eventListener( 'click', '.hacu-size__dropdown__li', function( e ) {
			e.preventDefault();
			const target = e.target;
			const parentElem = target.closest( '.hacu-size' );
			const inputElem = parentElem.querySelector( '.hacu-size__input' );
			const dropdownElem = parentElem.querySelector( '.hacu-size__dropdown' );
			const dropdownBtnElem = parentElem.querySelector( '.hacu-size__dropdown-btn' );
			if ( ! parentElem || ! inputElem || ! dropdownElem || ! dropdownBtnElem ) {
				return;
			}

			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			if ( state === 'default' || value.length > 0 ) {
				inputElem.setAttribute( 'data-unit', value );
				hacu.size.updateValue( inputElem );

				const dropdownLiElems = parentElem.querySelectorAll( '.hacu-size__dropdown__li' );
				if ( dropdownLiElems.length > 0 ) {
					dropdownLiElems.forEach( function( dropdownLiElem ) {
						dropdownLiElem.setAttribute( 'data-state', 'default' );
					} );
				}
				target.setAttribute( 'data-state', 'active' );

				dropdownBtnElem.textContent = value;
				dropdownBtnElem.setAttribute( 'data-state', 'closed' );
				dropdownElem.setAttribute( 'data-state', 'closed' );
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
			const parentElem = target.closest( '.hacu-size' );
			const inputElem = parentElem.querySelector( '.hacu-size__input' );
			if ( ! parentElem || ! inputElem ) {
				return;
			}

			clearTimeout( hacu.size.timer );
			hacu.size.timer = setTimeout( function() {
				const value = ( hacu.fn.isNumber( target.value ) ? target.value : '' );
				target.value = value;
				inputElem.setAttribute( 'data-size', value );
				hacu.size.updateValue( inputElem );
			}, 500 );
		};

		hacu.fn.eventListener( 'keyup', '.hacu-size__number', callback );
		hacu.fn.eventListener( 'paste', '.hacu-size__number', callback );
	},
};

/**
 * Is Dom Ready.
 *
 * @since 1.0.0
 */
hacu.domReady = {

	/**
	 * Execute the code when dom is ready.
	 *
	 * @param {Function} func callback
	 * @return {Function} The callback function.
	 */
	execute( func ) {
		if ( typeof func !== 'function' ) {
			return;
		}
		if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
			return func();
		}

		document.addEventListener( 'DOMContentLoaded', func, false );
	},
};

hacu.domReady.execute( function() {
	hacu.checkboxMultiple.init(); // Handle checkbox multiple events.
	hacu.counter.init(); // Handle counter events.
	hacu.radio.init(); // Handle radio events.
	hacu.size.init(); // Handle size events.
} );
