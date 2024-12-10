/**
 * Internal dependencies
 */
import { queryElement, updateFieldValue, eventListener } from '../../../resources/scripts/helpers';

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
	 * @param {Object} elements Contains the required elements.
	 */
	updateValue( elements ) {
		if ( ! elements ) {
			return;
		}

		const values = [];
		const { parentElem, inputElem } = elements;
		const enabledItemElems = parentElem.querySelectorAll(
			'.hacu-sortable__item[data-state="enabled"]'
		);
		if ( 0 < enabledItemElems.length ) {
			enabledItemElems.forEach( ( enabledItemElem ) => {
				const itemValue = enabledItemElem.getAttribute( 'data-value' );
				if ( 0 < itemValue.length && ! values.includes( itemValue ) ) {
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
	 * @param {Object} container Contains the container element.
	 * @param {number} yAxis     Contains the current Y Axis of the container element
	 * @return {Object} The draggable elements.
	 */
	getDragAfterElement( container, yAxis ) {
		const draggableElements = [
			...container.querySelectorAll( '[draggable="true"]:not([data-dragging="yes"])' ),
		];

		return draggableElements.reduce(
			( closest, child ) => {
				const box = child.getBoundingClientRect();
				const offset = yAxis - box.top - box.height / 2;
				if ( 0 > offset && offset > closest.offset ) {
					return {
						offset,
						element: child,
					};
				}
				return closest;
			},
			{
				offset: Number.NEGATIVE_INFINITY,
			}
		).element;
	},

	/**
	 * On item dragging start event.
	 *
	 * @since 1.0.0
	 */
	onItemDragStart() {
		eventListener( 'dragstart', '.hacu-sortable__item', ( e ) => {
			e.target.setAttribute( 'data-dragging', 'yes' );
		} );
	},

	/**
	 * On item dragging stop event.
	 *
	 * @since 1.0.0
	 */
	onItemDragStop() {
		eventListener( 'dragend', '.hacu-sortable__item', ( e ) => {
			const elements = Sortable.elements( e.target );
			if ( ! elements ) {
				return;
			}

			e.target.setAttribute( 'data-dragging', 'no' );

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
		eventListener( 'dragover', '.hacu-sortable__container', ( e ) => {
			e.preventDefault();
			const container = e.target;
			const afterElement = Sortable.getDragAfterElement( container, e.clientY );
			const draggable = document.querySelector( '[data-dragging="yes"]' );
			// eslint-disable-next-line eqeqeq
			if ( null == afterElement ) {
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
		eventListener( 'click', '.hacu-sortable__item__toggle-btn', ( e ) => {
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

			itemElem.setAttribute( 'draggable', 'enabled' === state ? 'false' : true );
			itemElem.setAttribute( 'data-state', 'enabled' === state ? 'disabled' : 'enabled' );
			target.setAttribute( 'data-state', 'enabled' === state ? 'disabled' : 'enabled' );

			// Update sortable value.
			Sortable.updateValue( elements );
		} );
	},
};

export default Sortable;
