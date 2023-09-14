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
'use strict';

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
	 * @return {Function} The callback function.
	 */
	execute( func ) {
		if ( typeof func !== 'function' ) {
			return;
		}
		if ( document.readyState === 'interactive' || document.readyState === 'complete' ) {
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
Handy.domReady.execute( function() {
	const Fragments = [ ...Components, ...Modules ];
	Fragments.forEach( function( Fragment ) {
		if ( 'init' in Fragment ) {
			Fragment.init();
		}
	} );
} );
