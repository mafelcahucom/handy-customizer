/**
 * Internal dependencies
 */
import { queryElement, updateFieldValue, eventListener } from '../../../resources/scripts/helpers';

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
	 * Initialize time picker using flatpickr.js
	 *
	 * @since 1.0.0
	 */
	initFlatpickr() {
		jQuery( () => {
			const timePickerElems = document.querySelectorAll( '.hacu-time-picker__picker' );
			if ( 0 < timePickerElems.length ) {
				timePickerElems.forEach( ( timePickerElem ) => {
					const target = timePickerElem;
					const time = target.getAttribute( 'data-time' );
					const format = target.getAttribute( 'data-format' );
					const elements = TimePicker.elements( target );
					if ( elements ) {
						const { inputElem } = elements;
						// eslint-disable-next-line require-jsdoc
						const is24HourFormat = () => {
							return 'military' === format ? true : false;
						};

						// eslint-disable-next-line require-jsdoc
						const getFormat = () => {
							return is24HourFormat() ? 'H:i' : 'h:i K';
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
							// eslint-disable-next-line require-jsdoc
							onChange( selected ) {
								/* eslint-disable no-undef */
								let updatedTime =
									0 < selected.length
										? flatpickr.formatDate( selected[ 0 ], getFormat() )
										: '';
								/* eslint-enable */
								if ( ! is24HourFormat() ) {
									if ( 0 < updatedTime.length ) {
										const splitted = updatedTime.split( ':' );
										if ( 2 === splitted.length ) {
											const hour = parseInt( splitted[ 0 ] );
											if ( 0 <= hour && 9 >= hour ) {
												updatedTime = '0' + updatedTime;
											}
										}
									}
								}

								updateFieldValue( inputElem, updatedTime );
							},
						} );
					}
				} );
			}
		} );
	},

	/**
	 * On toggle time picker.
	 *
	 * @since 1.0.0
	 */
	onTogglePicker() {
		eventListener( 'click', '.hacu-time-picker__toggle-btn', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = TimePicker.elements( target );
			if ( ! elements || ! [ 'default', 'active' ].includes( state ) ) {
				return;
			}

			const { containerElem } = elements;
			containerElem.setAttribute( 'data-state', 'default' === state ? 'visible' : 'hidden' );
			target.setAttribute( 'data-state', 'default' === state ? 'active' : 'default' );
			target.setAttribute( 'title', 'default' === state ? 'Close' : 'Open' );
		} );
	},
};

export default TimePicker;
