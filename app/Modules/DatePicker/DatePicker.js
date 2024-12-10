/**
 * Internal dependencies
 */
import {
	queryElement,
	isValidDate,
	updateFieldValue,
	eventListener,
} from '../../../resources/scripts/helpers';

/**
 * Date Picker Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const DatePicker = {
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
						selector: '.hacu-date-picker__input',
					},
					container: {
						isSingle: true,
						selector: '.hacu-date-picker__container',
					},
				},
			} );
		}
	},

	/**
	 * Initialize date picker using flatpickr.js
	 *
	 * @since 1.0.0
	 */
	initFlatpickr() {
		jQuery( () => {
			const datePickerElems = document.querySelectorAll( '.hacu-date-picker__picker' );
			if ( 0 < datePickerElems.length ) {
				datePickerElems.forEach( ( datePickerElem ) => {
					const target = datePickerElem;
					const dates = target.getAttribute( 'data-dates' );
					const mode = target.getAttribute( 'data-mode' );
					const enableTime = target.getAttribute( 'data-enable-time' );
					const elements = DatePicker.elements( target );
					if ( elements ) {
						const { inputElem } = elements;
						// eslint-disable-next-line require-jsdoc
						const isEnabledTime = () => {
							return enableTime ? true : false;
						};

						// eslint-disable-next-line require-jsdoc
						const getMode = () => {
							return [ 'single', 'range' ].includes( mode ) ? mode : 'single';
						};

						// eslint-disable-next-line require-jsdoc
						const getDateFormat = () => {
							return isEnabledTime() ? 'Y-m-d H:i' : 'Y-m-d';
						};

						// eslint-disable-next-line require-jsdoc
						const getAltFormat = () => {
							return isEnabledTime() ? 'F j, Y H:i' : 'F j, Y';
						};

						// eslint-disable-next-line require-jsdoc
						const getDefaultDates = () => {
							const defaultDates = [];
							if ( 0 < dates.length ) {
								const currentDates = dates.split( ',' );
								currentDates.forEach( ( currentDate ) => {
									const newDate = new Date( currentDate );
									if ( isValidDate( newDate ) ) {
										// eslint-disable-next-line require-jsdoc
										const getFullDate = () => {
											const date = `${ newDate.getFullYear() }-${
												newDate.getMonth() + 1
											}-${ newDate.getDate() }`;
											const time = `${ newDate.getHours() }:${ newDate.getMinutes() }`;
											return isEnabledTime() ? `${ date } ${ time }` : date;
										};

										defaultDates.push( getFullDate() );
									}
								} );
							}

							return 0 < defaultDates.length ? defaultDates : 'today';
						};

						target.flatpickr( {
							inline: true,
							altInput: true,
							mode: getMode(),
							enableTime: isEnabledTime(),
							dateFormat: getDateFormat(),
							defaultDate: getDefaultDates(),
							altFormat: getAltFormat(),
							time_24hr: true,
							// eslint-disable-next-line require-jsdoc
							onChange( selected ) {
								const selectedDates = selected.map( ( date ) => {
									// eslint-disable-next-line no-undef
									return flatpickr.formatDate( date, getDateFormat() );
								} );

								updateFieldValue( inputElem, selectedDates.join( ',' ) );
							},
						} );
					}
				} );
			}
		} );
	},

	/**
	 * On toggle calendar picker.
	 *
	 * @since 1.0.0
	 */
	onTogglePicker() {
		eventListener( 'click', '.hacu-date-picker__toggle-btn', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = DatePicker.elements( target );
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

export default DatePicker;
