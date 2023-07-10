/**
 * Internal dependencies
 */
import {
	queryElement,
	updateFieldValue,
	eventListener,
} from '../../../assets/src/js/helpers';

/**
 * Sortable Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Sortable = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onItemDragStart();
		this.onItemDragStop();
		this.onContainerDragOver();
		this.onItemToggleControl();
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
						selector: '.hacu-sortable__input',
					},
				},
			} );
		}
	},

	/**
	 * Update the value of the hidden input.
	 *
	 * @since 1.0.0
	 * @param {Object} elements The required elements.
	 */
	updateValue( elements ) {
		if ( ! elements ) {
			return;
		}

		const values = [];
		const { parentElem, inputElem } = elements;
		const enabledItemElems = parentElem.querySelectorAll( '.hacu-sortable__item[data-state="enabled"]' );
		if ( enabledItemElems.length > 0 ) {
			enabledItemElems.forEach( function( enabledItemElem ) {
				const itemValue = enabledItemElem.getAttribute( 'data-value' );
				if ( itemValue.length > 0 && ! values.includes( itemValue ) ) {
					values.push( itemValue );
				}
			} );
		}

		updateFieldValue( inputElem, values.join( ',' ) );
	},

	/**
	 * Return the element to be appended of the current dragging element.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} container The container element.
	 * @param {number} yAxis     The current Y Axis of the container element
	 */
	getDragAfterElement( container, yAxis ) {
		const draggableElements = [
			...container.querySelectorAll( '[draggable="true"]:not([data-dragging="yes"])' ),
		];

		return draggableElements.reduce( function( closest, child ) {
			const box = child.getBoundingClientRect();
			const offset = yAxis - box.top - box.height / 2;
			if ( offset < 0 && offset > closest.offset ) {
				return {
					offset,
					element: child,
				};
			}
			return closest;
		}, {
			offset: Number.NEGATIVE_INFINITY,
		} ).element;
	},

	/**
	 * On item dragging start event.
	 *
	 * @since 1.0.0
	 */
	onItemDragStart() {
		eventListener( 'dragstart', '.hacu-sortable__item', function( e ) {
			e.target.setAttribute( 'data-dragging', 'yes' );
		} );
	},

	/**
	 * On item dragging stop event.
	 *
	 * @since 1.0.0
	 */
	onItemDragStop() {
		eventListener( 'dragend', '.hacu-sortable__item', function( e ) {
			const elements = Sortable.elements( e.target );
			if ( ! elements ) {
				return;
			}

			e.target.setAttribute( 'data-dragging', 'no' );

			console.log( elements );

			// Update sortable value.
			Sortable.updateValue( elements );
		} );
	},

	/**
	 * On container dragging over event.
	 *
	 * @since 1.0.0
	 */
	onContainerDragOver() {
		eventListener( 'dragover', '.hacu-sortable__container', function( e ) {
			e.preventDefault();
			const container = e.target;
			const afterElement = Sortable.getDragAfterElement( container, e.clientY );
			const draggable = document.querySelector( '[data-dragging="yes"]' );
			if ( afterElement == null ) {
				container.appendChild( draggable );
			} else {
				container.insertBefore( draggable, afterElement );
			}
		} );
	},

	/**
	 * On toggling item control event.
	 *
	 * @since 1.0.0
	 */
	onItemToggleControl() {
		eventListener( 'click', '.hacu-sortable__item__toggle-btn', function( e ) {
			e.preventDefault();
			const target = e.target;
			const state = target.getAttribute( 'data-state' );
			const elements = Sortable.elements( target );
			if ( ! elements || ! [ 'enabled', 'disabled' ].includes( state ) ) {
				return;
			}

			const itemElem = target.closest( '.hacu-sortable__item' );
			if ( ! itemElem ) {
				return;
			}

			itemElem.setAttribute( 'draggable', ( state === 'enabled' ? 'false' : true ) );
			itemElem.setAttribute( 'data-state', ( state === 'enabled' ? 'disabled' : 'enabled' ) );
			target.setAttribute( 'data-state', ( state === 'enabled' ? 'disabled' : 'enabled' ) );

			// Update sortable value.
			Sortable.updateValue( elements );
		} );
	},
};

export default Sortable;
