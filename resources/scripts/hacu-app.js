/**
 * Internal Dependencies.
 */
import Modules from './modules';
import Components from './components';

/**
 * Strict mode.
 *
 * @since 1.0.0
 *
 * @author Mafel John Cahucom
 */
( 'use strict' ); // eslint-disable-line no-unused-expressions

/**
 * Namespace.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Handy = Handy || {};

/**
 * Is Dom Ready.
 *
 * @since 1.0.0
 */
Handy.domReady = {
	/**
	 * Execute the code when dom is ready.
	 *
	 * @param {Function} func callback.
	 * @return {Function|void} The callback function.
	 */
	execute( func ) {
		if ( 'function' !== typeof func ) {
			return;
		}
		if ( 'interactive' === document.readyState || 'complete' === document.readyState ) {
			return func();
		}

		document.addEventListener( 'DOMContentLoaded', func, false );
	},
};

/**
 * Initialze Controls.
 *
 * @since 1.0.0
 */
Handy.domReady.execute( () => {
	const Fragments = [ ...Components, ...Modules ];
	Fragments.forEach( ( Fragment ) => {
		if ( 'init' in Fragment ) {
			Fragment.init();
		}
	} );
} );
