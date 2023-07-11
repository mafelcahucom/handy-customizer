/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
} from '../../../assets/src/js/helpers';

/**
 * Color Picker Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ColorPicker = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.initPckr();
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
						selector: '.hacu-color-picker__input',
					},
					container: {
						isSingle: true,
						selector: '.hacu-color-picker__container',
					},
					preview: {
						isSingle: true,
						selector: '.hacu-color-picker__picker__preview',
					},
				},
			} );
		}
	},

	/**
	 * Initialize color picker using pickr.js
	 *
	 * @since 1.0.0
	 */
	initPckr() {
		jQuery( function() {
			const colorPickerElems = document.querySelectorAll( '.hacu-color-picker__picker' );
			if ( colorPickerElems.length > 0 ) {
				colorPickerElems.forEach( function( colorPickerElem ) {
					const target = colorPickerElem;
					const value = target.getAttribute( 'data-value' );
					const format = target.getAttribute( 'data-format' );
					const elements = ColorPicker.elements( colorPickerElem );
					if ( elements && [ 'hex', 'hexa', 'hsl', 'hsla', 'rgb', 'rgba' ].includes( format ) ) {
						const { inputElem, containerElem, previewElem } = elements;
						const getValue = function() {
							return ( value.length > 0 ? value : '#ffffff' );
						};

						const isEnableOpacity = function() {
							return ( [ 'hexa', 'hsla', 'rgba' ].includes( format ) ? true : false );
						};

						const colorPicker = Pickr.create( {
							el: colorPickerElem,
							container: containerElem,
							appClass: 'hacu-color-picker__app',
							theme: 'nano',
							inline: false,
							autoReposition: false,
							useAsButton: true,
							default: getValue(),
							swatches: [
								'#f44336', '#e91e63', '#9c27b0', '#673ab7', '#3f51b5', '#2196f3', '#03a9f4',
								'#00bcd4', '#009688', '#4caf50', '#8bc34a', '#cddc39', '#ffeb3b', '#ffc107',
								'#ff9800', '#ff5722', '#795548', '#9e9e9e', '#607d8b', '#000000',
							],
							components: {
								preview: true,
								hue: true,
								opacity: isEnableOpacity(),
								interaction: {
									hex: false,
									rgba: false,
									hsla: false,
									hsva: false,
									cmyk: false,
									save: true,
									cancel: true,
								},
							},
						} );

						colorPicker.on( 'save', function( color ) {
							let updatedColor = '';
							if ( [ 'hex', 'hexa' ].includes( format ) ) {
								updatedColor = color.toHEXA().toString( 0 );
							}

							if ( [ 'hsl', 'hsla' ].includes( format ) ) {
								updatedColor = color.toHSLA().toString( 0 );
							}

							if ( [ 'rgb', 'rgba' ].includes( format ) ) {
								updatedColor = color.toRGBA().toString( 0 );
							}

							previewElem.style.backgroundColor = updatedColor;
							updateFieldValue( inputElem, updatedColor );
						} );
					}
				} );
			}
		} );
	},
};

export default ColorPicker;
