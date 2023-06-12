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
	 * Return the value of joined or imploded to exploded array.
	 * 
	 * @since 1.0.0
	 * 
	 * @param  {string} value The value to be exploded as array.
	 * @return {array}
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
				'bubbles': true
			} ) );
		}
	}
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
	 * Update hidden input value on box check.
	 * 
	 * @since 1.0.0
	 */
	onChange() {
		hacu.fn.eventListener( 'change', '.hacu-checkbox-multiple__box', function( e ) {
			const target = e.target;
			const parent = target.closest( '.hacu-checkbox-multiple' );
			const input = parent.querySelector( '.hacu-checkbox-multiple__input' );
			if ( ! parent || ! input ) {
				return;
			}

			const value = target.value;
			if ( value.length === 0 ) {
				return;
			}

			const checked = hacu.fn.getExplodedValue( input.value );
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

			input.value = checked.join( ',' );
			hacu.fn.trigger( input );
		});
	}
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
} );