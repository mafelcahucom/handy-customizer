/**
 * Internal dependencies
 */
import { queryElement, eventListener } from '../../../resources/scripts/helpers';

/**
 * Range Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Range = {
	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onUpdateValue();
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
					output: {
						isSingle: true,
						selector: '.hacu-range__output',
					},
				},
			} );
		}
	},

	/**
	 * On update the value and update output text content
	 *
	 * @since 1.0.0
	 */
	onUpdateValue() {
		eventListener( 'input', '.hacu-range__input', ( e ) => {
			const target = e.target;
			const elements = Range.elements( target );
			if ( ! elements ) {
				return;
			}

			elements.outputElem.textContent = target.value;
		} );
	},
};

export default Range;
