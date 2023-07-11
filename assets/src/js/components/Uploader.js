/**
 * Internal Dependencies
 */
import {
	queryElement,
	eventListener,
	updateFieldValue,
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
		this.initMediaPlayer();
		this.onSelectAttachment();
		this.onRemoveAttachment();
	},

	/**
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target The target element.
	 * @param {string} type   The type of attachment.
	 * @return {Object} The required elements
	 */
	elements( target, type ) {
		if ( target ) {
			let elements = {
				input: {
					isSingle: true,
					selector: '.hacu-uploader__input',
				},
				uploader: {
					isSingle: true,
					selector: '.hacu-uploader__uploader',
				},
				thumbnail: {
					isSingle: true,
					selector: '.hacu-uploader__thumbnail',
				},

			};

			if ( type === 'image' ) {
				const imageElements = {
					image: {
						isSingle: true,
						selector: '.hacu-uploader__image-thumbnail__image',
					},
				};

				elements = { ...elements, ...imageElements };
			}

			if ( [ 'audio', 'video' ].includes( type ) ) {
				const mediaElements = {
					mediaPlayer: {
						isSingle: true,
						selector: '.hacu-media-player',
					},
				};

				elements = { ...elements, ...mediaElements };
			}

			if ( [ 'audio', 'application' ].includes( type ) ) {
				const fileElements = {
					icon: {
						isSingle: true,
						selector: '.hacu-uploader__file-thumbnail__icon',
					},
					filename: {
						isSingle: true,
						selector: '.hacu-uploader__file-thumbnail__filename',
					},
					filemeta: {
						isSingle: true,
						selector: '.hacu-uploader__file-thumbnail__filemeta',
					},
					fileThumbnail: {
						isSingle: true,
						selector: '.hacu-uploader__file-thumbnail',
					},
				};

				elements = { ...elements, ...fileElements };
			}

			return queryElement( {
				target,
				elements,
			} );
		}
	},

	/**
	 * Initialize media player using MediaElement.js.
	 *
	 * @since 1.0.0
	 */
	initMediaPlayer() {
		const mediaPlayerElems = document.querySelectorAll( '.hacu-media-player' );
		if ( mediaPlayerElems.length > 0 ) {
			mediaPlayerElems.forEach( function( mediaPlayerElem ) {
				const id = mediaPlayerElem.getAttribute( 'id' );
				if ( id ) {
					jQuery( `#${ id }` ).mediaelementplayer();
				}
			} );
		}
	},

	/**
	 * Set the file thumbnail content.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} elements   The required elements.
	 * @param {Object} attachment The attachment meta data.
	 */
	setFileThumbnail( elements, attachment ) {
		const { iconElem, filenameElem, filemetaElem, fileThumbnailElem } = elements;
		const { icon, filename, filesizeHumanReadable, dateFormatted } = attachment;
		const getFilename = function( file ) {
			if ( file.length > 24 ) {
				const rawFilename = file.replace( /\.[^/.]+$/, '' );
				const extension = file.replace( `${ rawFilename }.`, '' );
				return rawFilename.substring( 0, 16 ) + '...' + extension;
			}

			return file;
		};

		const getFilemeta = function( filesize, date ) {
			const month = date.split( ' ' )[ 0 ];
			return `${ filesize } - ${ date.replace( month, month.substring( 0, 3 ) ) }`;
		};

		iconElem.setAttribute( 'src', icon );
		fileThumbnailElem.setAttribute( 'title', filename );
		filenameElem.textContent = getFilename( filename );
		filemetaElem.textContent = getFilemeta( filesizeHumanReadable, dateFormatted );
	},

	/**
	 * Set the image thumbnail content.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} elements   The required elements.
	 * @param {Object} attachment The attachment meta data.
	 */
	setImageThumbnail( elements, attachment ) {
		const { imageElem } = elements;
		const { title, sizes } = attachment;
		const imageUrl = ( 'medium' in sizes ? sizes.medium.url : sizes.full.url );

		imageElem.setAttribute( 'title', title );
		imageElem.setAttribute( 'src', imageUrl );
	},

	/**
	 * Set the media player new source.
	 *
	 * @since 1.0.0
	 *
	 * @param {string} id         The ID of the uploader.
	 * @param {Object} attachment The attachment meta data.
	 */
	setMediaPlayerSource( id, attachment ) {
		const mediaPlayer = jQuery( `#${ id }-media-player` );
		mediaPlayer[ 0 ].setSrc( attachment.url );
	},

	/**
	 * On selecting attachment.
	 *
	 * @since 1.0.0
	 */
	onSelectAttachment() {
		eventListener( 'click', '.hacu-uploader__file-selector', function( e ) {
			e.preventDefault();
			const target = e.target;
			const definedId = target.getAttribute( 'data-id' );
			const definedType = target.getAttribute( 'data-type' );
			const definedMimes = target.getAttribute( 'data-mimes' );
			const elements = Uploader.elements( target, definedType );
			const validTypes = [ 'application', 'audio', 'image', 'video' ];
			if ( ! elements || ! definedId || ! definedMimes || ! validTypes.includes( definedType ) ) {
				return;
			}

			const { inputElem, uploaderElem, thumbnailElem } = elements;
			const currentValue = inputElem.value;
			const getParsedMimes = function( mimes ) {
				let parsedMimes;
				try {
					parsedMimes = JSON.parse( mimes );
				} catch ( error ) {
					parsedMimes = [];
				}

				return parsedMimes;
			};

			const getFileExtension = function( filename ) {
				return /(?:\.([^.]+))?$/.exec( filename )[ 1 ];
			};

			let mediaUploader;
			if ( mediaUploader ) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp.media( {
				title: `Select ${ definedType.charAt( 0 ).toUpperCase() + definedType.slice( 1 ) }`,
				button: {
					text: 'Select',
				},
				library: {
					type: getParsedMimes( definedMimes ),
				},
				multiple: false,
			} ).on( 'open', function() {
				if ( currentValue.length > 0 ) {
					const selection = mediaUploader.state().get( 'selection' );
					const attachment = wp.media.attachment( currentValue );
					selection.add( attachment ? [ attachment ] : [] );
				}
			} ).on( 'select', function() {
				const attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
				console.log( attachment );
				if ( ! attachment || attachment.id === parseInt( currentValue ) ) {
					return;
				}

				if ( definedType !== attachment.type ) {
					alert( `File type ${ attachment.type } is not allowed. Please select ${ definedType } file type only.` );
					return;
				}

				if ( getParsedMimes( definedMimes ).indexOf( attachment.mime ) === -1 ) {
					alert( `File extension ${ getFileExtension( attachment.filename ) } is not allowed` );
					return;
				}

				if ( definedType === 'image' ) {
					Uploader.setImageThumbnail( elements, attachment );
				}

				if ( [ 'audio', 'video' ].includes( definedType ) ) {
					Uploader.setMediaPlayerSource( definedId, attachment );
				}

				if ( [ 'audio', 'application' ].includes( definedType ) ) {
					Uploader.setFileThumbnail( elements, attachment );
				}

				uploaderElem.setAttribute( 'data-state', 'hidden' );
				thumbnailElem.setAttribute( 'data-state', 'visible' );
				updateFieldValue( inputElem, attachment.id );
			} );

			mediaUploader.open();
		} );
	},

	/**
	 * On removing current selected attachment.
	 *
	 * @since 1.0.0
	 */
	onRemoveAttachment() {
		eventListener( 'click', '.hacu-uploader__file-remove', function( e ) {
			e.preventDefault();
			const target = e.target;
			const elements = Uploader.elements( target, '' );
			if ( ! elements ) {
				return;
			}

			const { inputElem, uploaderElem, thumbnailElem } = elements;
			thumbnailElem.setAttribute( 'data-state', 'hidden' );
			uploaderElem.setAttribute( 'data-state', 'visible' );
			updateFieldValue( inputElem, '' );
		} );
	},
};

export default Uploader;
