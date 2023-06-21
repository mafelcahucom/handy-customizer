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
	 * Checks if the object is empty.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} object The object to be checked.
	 * @return {boolean} Whether has empty key.
	 */
	isObjectEmpty( object ) {
		return Object.keys( object ).length === 0;
	},

	/**
	 * Checks if the object has a missing key, if has found
	 * a missing key return true.
	 *
	 * @since 1.0.0
	 *
	 * @param {Array}  keys   The list of keys use as referrence.
	 * @param {Object} object The object to be checked.
	 */
	isObjectHasMissingKey( keys, object ) {
		if ( keys.length === 0 || this.isObjectEmpty( object ) ) {
			return;
		}

		let hasMissing = false;
		keys.forEach( function( key ) {
			if ( ! object.hasOwnProperty( key ) ) {
				hasMissing = true;
			}
		} );

		return hasMissing;
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
	 * Check if the color string contains a valid hexadecimal color.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} color The string color to be check.
	 * @return {boolean} Flag if color is valid hexadecimal color.
	 */
	isValidHexaColor( color ) {
		let isValid = false;
		if ( typeof ( color ) === 'string' ) {
			isValid = /^#([a-f0-9]{3}|[a-f0-9]{6}|[a-f0-9]{8})$/i.test( color );
		}

		return isValid;
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
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} params          Contains the parameters needed to required element.
	 * @param {Object} params.target   The target element.
	 * @param {Object} params.elements The list of required elements.
	 * @return {Object} The required elements.
	 */
	getRequiredElements( params = {} ) {
		const required = [ 'target', 'elements' ];
		if ( this.isObjectEmpty( params ) || this.isObjectHasMissingKey( required, params ) ) {
			return false;
		}

		if ( this.isObjectEmpty( params.elements ) ) {
			return false;
		}

		const targetClassname = params.target.getAttribute( 'class' );
		if ( ! targetClassname ) {
			return false;
		}

		const classname = targetClassname.split( '__' )[ 0 ];
		const parentElem = params.target.closest( `.${ classname }` );
		if ( ! parentElem ) {
			return false;
		}

		const elements = {};
		let hasNotFound = false;
		Object.entries( params.elements ).forEach( function( value ) {
			const { isSingle, selector } = value[ 1 ];
			if ( isSingle ) {
				const elem = parentElem.querySelector( selector );
				if ( elem ) {
					elements[ `${ value[ 0 ] }Elem` ] = elem;
				} else {
					hasNotFound = true;
				}
			} else {
				const elems = parentElem.querySelectorAll( selector );
				elements[ `${ value[ 0 ] }Elems` ] = elems;
			}
		} );
		elements.parentElem = parentElem;

		return ( hasNotFound ? false : elements );
	},

	/**
	 * Sets the attribute of target elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} selector  The element selector.
	 * @param {string} attribute The Attribute to be set.
	 * @param {string} value     The value of the attribute.
	 */
	setAttribute( selector, attribute, value ) {
		if ( ! selector || ! attribute ) {
			return;
		}

		const elems = document.querySelectorAll( selector );
		if ( elems.length > 0 ) {
			elems.forEach( function( elem ) {
				elem.setAttribute( attribute, value );
			} );
		}
	},

	/**
	 * Sets the children attribute of target elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} parent    The parent element.
	 * @param {string} selector  The element selector.
	 * @param {string} attribute The Attribute to be set.
	 * @param {string} value     The value of the attribute.
	 */
	setChildAttribute( parent, selector, attribute, value ) {
		if ( ! parent || ! selector || ! attribute ) {
			return;
		}

		const elems = parent.querySelectorAll( selector );
		if ( elems.length > 0 ) {
			elems.forEach( function( elem ) {
				elem.setAttribute( attribute, value );
			} );
		}
	},

	/**
	 * Update the hidden input value and dispatch change event.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} input The target hidden input.
	 * @param {*}      value The new value of hidden input.
	 */
	updateFieldValue( input, value ) {
		if ( input ) {
			input.value = value;
			this.trigger( input );
		}
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
 * Global Components.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.component = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.accordion.init();
	},

	/**
	 * Accordion Component.
	 *
	 * @since 1.0.0
	 *
	 * @type {Object}
	 */
	accordion: {

		/**
		 * Initialize Accordion.
		 *
		 * @since 1.0.0
		 */
		init() {
			this.onToggle();
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
				return hacu.fn.getRequiredElements( {
					target,
					elements: {
						body: {
							isSingle: true,
							selector: '.hacu-accordion__body',
						},
					},
				} );
			}
		},

		/**
		 * Toggle or collapse down and up accordion.
		 *
		 * @since 1.0.0
		 */
		onToggle() {
			hacu.fn.eventListener( 'click', '.hacu-accordion__head', function( e ) {
				e.preventDefault();
				const target = e.target;
				const state = target.getAttribute( 'data-state' );
				const elements = hacu.component.accordion.elements( target );
				if ( ! elements || ! [ 'opened', 'closed' ].includes( state ) ) {
					return;
				}

				const { bodyElem } = elements;
				bodyElem.style.maxHeight = bodyElem.scrollHeight + 'px';
				if ( state === 'opened' ) {
					setTimeout( function() {
						bodyElem.style.maxHeight = null;
					}, 300 );
					target.setAttribute( 'data-state', 'closed' );
					bodyElem.setAttribute( 'data-state', 'closed' );
				} else {
					setTimeout( function() {
						bodyElem.style.maxHeight = 'max-content';
					}, 500 );
					target.setAttribute( 'data-state', 'opened' );
					bodyElem.setAttribute( 'data-state', 'opened' );
				}
			} );
		},
	},
};

/**
 * Button Set Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.buttonSet = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onChange();
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
			return hacu.fn.getRequiredElements( {
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
	 * Update hidden input value based on selected item button.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		hacu.fn.eventListener( 'click', '.hacu-button-set__item-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const state = target.getAttribute( 'data-state' );
			const elements = hacu.buttonSet.elements( target );
			if ( ! elements || value.length === 0 || state !== 'default' ) {
				return;
			}

			const { parentElem, inputElem } = elements;
			hacu.fn.updateFieldValue( inputElem, value );
			hacu.fn.setChildAttribute( parentElem, '.hacu-button-set__item-btn', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );
		} );
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
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @return {Object} The required elements.
	 */
	elements( target ) {
		if ( target ) {
			return hacu.fn.getRequiredElements( {
				target,
				elements: {
					input: {
						isSingle: true,
						selector: '.hacu-checkbox-multiple__input',
					},
				},
			} );
		}
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
			const elements = hacu.checkboxMultiple.elements( target );
			if ( ! elements || value.length === 0 ) {
				return;
			}

			const { inputElem } = elements;
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

			hacu.fn.updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

/**
 * Checkbox Pill Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.checkboxPill = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onChange();
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
			return hacu.fn.getRequiredElements( {
				target,
				elements: {
					input: {
						isSingle: true,
						selector: '.hacu-checkbox-pill__input',
					},
				},
			} );
		}
	},

	/**
	 * Update hidden input value based on checked pills.
	 *
	 * @since 1.0.0
	 */
	onChange() {
		hacu.fn.eventListener( 'click', '.hacu-checkbox-pill__item-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = hacu.checkboxPill.elements( target );
			if ( ! elements || state.length === 0 || value.length === 0 ) {
				return;
			}

			const { inputElem } = elements;
			const checked = hacu.fn.getExplodedValue( inputElem.value );
			switch ( state ) {
				case 'default':
					if ( ! checked.includes( value ) ) {
						checked.push( value );
						target.setAttribute( 'data-state', 'active' );
					}
					break;
				case 'active':
					const index = checked.indexOf( value );
					console.log( index );
					if ( index !== -1 ) {
						checked.splice( index, 1 );
						target.setAttribute( 'data-state', 'default' );
					}
					break;
			}

			hacu.fn.updateFieldValue( inputElem, checked.join( ',' ) );
		} );
	},
};

/**
 * Color Set Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
hacu.colorSet = {

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
			return hacu.fn.getRequiredElements( {
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
		hacu.fn.eventListener( 'click', '.hacu-color-set__item', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			const elements = hacu.colorSet.elements( target );
			if ( ! elements || state !== 'default' || value.length === 0 ) {
				return;
			}

			if ( ! hacu.fn.isValidHexaColor( value ) ) {
				return;
			}

			const { parentElem, inputElem, labelElem, previewElem } = elements;
			labelElem.textContent = value;
			previewElem.style.backgroundColor = value;

			hacu.fn.setChildAttribute( parentElem, '.hacu-color-set__item', 'data-state', 'default' );
			target.setAttribute( 'data-state', 'active' );

			hacu.fn.updateFieldValue( inputElem, value );
		} );
	},

	/**
	 * Update hidden input value to the default color value.
	 *
	 * @since 1.0.0
	 */
	onChangeToDefault() {
		hacu.fn.eventListener( 'click', '.hacu-color-set__default-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const value = target.getAttribute( 'data-value' );
			const elements = hacu.colorSet.elements( target );
			if ( ! elements || value.length === 0 ) {
				return;
			}

			if ( ! hacu.fn.isValidHexaColor( value ) ) {
				return;
			}

			const { parentElem, inputElem, labelElem, previewElem } = elements;
			if ( inputElem.value !== value ) {
				labelElem.textContent = value;
				previewElem.style.backgroundColor = value;

				hacu.fn.setChildAttribute( parentElem, '.hacu-color-set__item', 'data-state', 'default' );
				hacu.fn.setChildAttribute( parentElem, `.hacu-color-set__item[data-value="${ value }"]`, 'data-state', 'active' );

				hacu.fn.updateFieldValue( inputElem, value );
			}
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
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @return {Object} The required elements.
	 */
	elements( target ) {
		if ( target ) {
			return hacu.fn.getRequiredElements( {
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
			const elements = hacu.counter.elements( target );
			if ( ! elements || state !== 'default' || ! [ '-', '+' ].includes( event ) ) {
				return;
			}

			const { inputElem, decrementBtnElem, incrementBtnElem } = elements;
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

			hacu.fn.updateFieldValue( inputElem, value );
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
			const elements = hacu.counter.elements( target );
			if ( ! elements ) {
				return;
			}

			const { decrementBtnElem, incrementBtnElem } = elements;
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
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @return {Object} The required elements.
	 */
	elements( target ) {
		if ( target ) {
			return hacu.fn.getRequiredElements( {
				target,
				elements: {
					input: {
						isSingle: true,
						selector: '.hacu-radio__input',
					},
					radio: {
						isSingle: false,
						selector: '.hacu-radio__box',
					},
				},
			} );
		}
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
			const elements = hacu.radio.elements( target );
			if ( ! elements || value.length === 0 ) {
				return;
			}

			const { inputElem, radioElems } = elements;
			if ( radioElems.length > 0 ) {
				radioElems.forEach( function( radioElem ) {
					radioElem.checked = false;
				} );
			}

			target.checked = true;
			hacu.fn.updateFieldValue( inputElem, value );
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
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @return {Object} The required elements.
	 */
	elements( target ) {
		if ( target ) {
			return hacu.fn.getRequiredElements( {
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
			hacu.fn.updateFieldValue( input, `${ size }${ unit }` );
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
			const elements = hacu.size.elements( target );
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
		hacu.fn.eventListener( 'click', '.hacu-size__dropdown__li', function( e ) {
			e.preventDefault();
			const target = e.target;
			const elements = hacu.size.elements( target );
			if ( ! elements ) {
				return;
			}

			// inputElem. dropdownElem, dropdownBtnElem
			const { parentElem, inputElem, dropdownElem, dropdownBtnElem } = elements;
			const state = target.getAttribute( 'data-state' );
			const value = target.getAttribute( 'data-value' );
			if ( state === 'default' || value.length > 0 ) {
				hacu.fn.setChildAttribute( parentElem, '.hacu-size__dropdown__li', 'data-state', 'default' );
				target.setAttribute( 'data-state', 'active' );

				dropdownBtnElem.textContent = value;
				dropdownBtnElem.setAttribute( 'data-state', 'closed' );
				dropdownElem.setAttribute( 'data-state', 'closed' );

				inputElem.setAttribute( 'data-unit', value );
				hacu.size.updateValue( inputElem );
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
			const elements = hacu.size.elements( target );
			if ( ! elements ) {
				return;
			}

			const { inputElem } = elements;
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
	hacu.component.init(); // Hadle global component events.
	hacu.buttonSet.init(); // Handle button set events.
	hacu.checkboxMultiple.init(); // Handle checkbox multiple events.
	hacu.checkboxPill.init(); // Handle checkbox pill events.
	hacu.colorSet.init(); // Handle color set events.
	hacu.counter.init(); // Handle counter events.
	hacu.radio.init(); // Handle radio events.
	hacu.size.init(); // Handle size events.
} );
