/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

/**
 * Time Picker Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const TimePicker = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.initFlatpickr();
		this.onTogglePicker();
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
						selector: '.hacu-time-picker__input',
					},
					container: {
						isSingle: true,
						selector: '.hacu-time-picker__container',
					},
				},
			} );
		}
	},

	/**
	 * Initialize time picker using flatpickr.js.
	 *
	 * @since 1.0.0
	 */
	initFlatpickr() {
		const timePickerElems = document.querySelectorAll( '.hacu-time-picker__picker' );
		if ( timePickerElems.length > 0 ) {
			timePickerElems.forEach( function( timePickerElem ) {
				const target = timePickerElem;
				const time = target.getAttribute( 'data-time' );
				const format = target.getAttribute( 'data-format' );
				const elements = TimePicker.elements( target );
				if ( elements ) {
					const { inputElem } = elements;
					const is24HourFormat = function() {
						return ( format === 'military' ? true : false );
					};

					const getFormat = function() {
						return ( is24HourFormat() ? 'H:i' : 'h:i K' );
					};

					target.flatpickr( {
						inline: true,
						altInput: true,
						enableTime: true,
						noCalendar: true,
						dateFormat: getFormat(),
						altFormat: getFormat(),
						time_24hr: is24HourFormat(),
						defaultDate: time,
						onChange( selected ) {
							const updatedTime = ( selected.length > 0 ? flatpickr.formatDate( selected[ 0 ], getFormat() ) : '' );
							updateFieldValue( inputElem, updatedTime );
						},
					} );
				}
			} );
		}
	},

	/**
	 * On toggle time picker.
	 *
	 * @since 1.0.0
	 */
	onTogglePicker() {
		eventListener( 'click', '.hacu-time-picker__toggle-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = TimePicker.elements( target );
			if ( ! elements || ! [ 'default', 'active' ].includes( state ) ) {
				return;
			}

			const { containerElem } = elements;
			containerElem.setAttribute( 'data-state', ( state === 'default' ? 'visible' : 'hidden' ) );
			target.setAttribute( 'data-state', ( state === 'default' ? 'active' : 'default' ) );
			target.setAttribute( 'title', ( state === 'default' ? 'Close' : 'Open' ) );
		} );
	},
};

export default TimePicker;
