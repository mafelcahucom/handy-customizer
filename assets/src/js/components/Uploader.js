/**
 * Internal Dependencies
 */
import {
	queryElement,
	eventListener,
} from '../helpers';

/**
 * Uploader Component.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const Uploader = {

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.onSelectAttachment();
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
						selector: '.hacu-uploader__input',
					},
				},
			} );
		}
	},

	/**
	 * On Open meida  library and select an attachement.
	 * 
	 * @since 1.0.0
	 */
	onSelectAttachment() {
		eventListener( 'click', '.hacu-uploader__library-selector', function( e ) {
			const target = e.target;
			const type = target.getAttribute( 'data-type' );
			const mimes = target.getAttribute( 'data-mimes' );
			const elements = Uploader.elements( target );
			if ( ! elements || ! type || ! mimes ) {
				return;
			}

			const { inputElem } = elements;
			const currentValue = inputElem.value;

			// Parsing encoded mimes.
			let parsedMimes;
			try {
				parsedMimes = JSON.parse( mimes );
			} catch( e ) {
				parsedMimes = [];
			}

			// Opening media libarary.
			let mediaUploader;
			if ( mediaUploader ) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'HAHAHA',
				button: {
					text: 'Select'
				},
				library: {
					type: parsedMimes
				},
				multiple: false
			}).on( 'open', function() {
				
			}).on( 'select', function( e ) {
				let attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
				
			});

			mediaUploader.open();
		});
	}
};

export default Uploader;