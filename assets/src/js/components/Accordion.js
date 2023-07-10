/**
 * Internal Dependencies
 */
import {
	queryElement,
	eventListener,
} from '../helpers';

/**
 * Accordion Component.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Accordion = {

	/**
	 * Initialize.
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
	 * @return {Object} The required elements
	 */
	elements( target ) {
		if ( target ) {
			return queryElement( {
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
		eventListener( 'click', '.hacu-accordion__head', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = Accordion.elements( target );
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
};

export default Accordion;
