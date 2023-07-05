/**
 * Internal dependencies
 */
import {
	queryElement,
	isValidDate,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

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
	 * Initialize date picker using flatpickr.js.
	 *
	 * @since 1.0.0
	 */
	initFlatpickr() {
		const datePickerElems = document.querySelectorAll( '.hacu-date-picker__picker' );
		if ( datePickerElems.length > 0 ) {
			datePickerElems.forEach( function( datePickerElem ) {
				const target = datePickerElem;
				const dates = target.getAttribute( 'data-dates' );
				const mode = target.getAttribute( 'data-mode' );
				const enableTime = target.getAttribute( 'data-enable-time' );
				const elements = DatePicker.elements( target );
				if ( elements ) {
					const { inputElem } = elements;
					const isEnabledTime = function() {
						return ( enableTime ? true : false );
					};

					const getMode = function() {
						return ( [ 'single', 'range' ].includes( mode ) ? mode : 'single' );
					};

					const getDateFormat = function() {
						return ( isEnabledTime() ? 'Y-m-d H:i' : 'Y-m-d' );
					};

					const getAltFormat = function() {
						return ( isEnabledTime() ? 'F j, Y H:i' : 'F j, Y' );
					};

					const getDefaultDates = function() {
						const defaultDates = [];
						if ( dates.length > 0 ) {
							const currentDates = dates.split( ',' );
							currentDates.forEach( function( currentDate ) {
								const newDate = new Date( currentDate );
								if ( isValidDate( newDate ) ) {
									const getFullDate = function() {
										const date = `${ newDate.getFullYear() }-${ newDate.getMonth() + 1 }-${ newDate.getDate() }`;
										const time = `${ newDate.getHours() }:${ newDate.getMinutes() }`;
										return ( isEnabledTime() ? `${ date } ${ time }` : date );
									};

									defaultDates.push( getFullDate() );
								}
							} );
						}

						return ( defaultDates.length > 0 ? defaultDates : 'today' );
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
						onChange( selected ) {
							const selectedDates = selected.map( function( date ) {
								return flatpickr.formatDate( date, getDateFormat() );
							} );

							updateFieldValue( inputElem, selectedDates.join( ',' ) );
						},
					} );
				}
			} );
		}
	},

	/**
	 * On toggle calendar picker.
	 *
	 * @since 1.0.0
	 */
	onTogglePicker() {
		eventListener( 'click', '.hacu-date-picker__toggle-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = DatePicker.elements( target );
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

export default DatePicker;
