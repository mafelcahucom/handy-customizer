/**
 * Internal Dependencies
 */
import { queryElement, eventListener, updateFieldValue } from '../helpers';

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
		this.initMediaPlayer( 2000 );
		this.onSelectAttachment();
		this.onRemoveAttachment();
	},

	/**
	 * Return the required elements.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} target Contains the target element.
	 * @param {string} type   Contains the type of attachment.
	 * @return {Object|void} The required elements
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

			if ( 'image' === type ) {
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
	 *
	 * @param {number} delay Contains the delay of initialization.
	 */
	initMediaPlayer( delay = 0 ) {
		setTimeout( () => {
			const playerElems = document.querySelectorAll( '.hacu-media-player' );
			if ( 0 < playerElems.length ) {
				playerElems.forEach( ( playerElem ) => {
					const id = playerElem.getAttribute( 'data-id' );
					// eslint-disable-next-line @wordpress/no-unused-vars-before-return
					const src = playerElem.getAttribute( 'data-src' );
					const type = playerElem.getAttribute( 'data-type' );
					if ( ! id || ! [ 'audio', 'video' ].includes( type ) ) {
						return;
					}

					let player = `<audio id="${ id }-player" src="${ src }"></audio>`;
					if ( 'video' === type ) {
						player = `<video id="${ id }-player" src="${ src }" preload="true" style="width: 100%; height: 100%;"></video>`;
					}

					playerElem.innerHTML = player;
					jQuery( `#${ id }-player` ).mediaelementplayer();
				} );
			}
		}, delay );
	},

	/**
	 * Set the file thumbnail content.
	 *
	 * @since 1.0.0
	 *
	 * @param {Object} elements   Contains the required elements.
	 * @param {Object} attachment Contains the attachment meta data.
	 */
	setFileThumbnail( elements, attachment ) {
		const { iconElem, filenameElem, filemetaElem, fileThumbnailElem } = elements;
		const { icon, filename, filesizeHumanReadable, dateFormatted } = attachment;
		// eslint-disable-next-line require-jsdoc
		const getFilename = ( file ) => {
			if ( 24 < file.length ) {
				const rawFilename = file.replace( /\.[^/.]+$/, '' );
				const extension = file.replace( `${ rawFilename }.`, '' );
				return rawFilename.substring( 0, 16 ) + '...' + extension;
			}

			return file;
		};

		// eslint-disable-next-line require-jsdoc
		const getFilemeta = ( filesize, date ) => {
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
	 * @param {Object} elements   Contains the required elements.
	 * @param {Object} attachment Contains the attachment meta data.
	 */
	setImageThumbnail( elements, attachment ) {
		const { imageElem } = elements;
		const { title, sizes } = attachment;
		const imageUrl = 'medium' in sizes ? sizes.medium.url : sizes.full.url;

		imageElem.setAttribute( 'title', title );
		imageElem.setAttribute( 'src', imageUrl );
	},

	/**
	 * On selecting attachment.
	 *
	 * @since 1.0.0
	 */
	onSelectAttachment() {
		eventListener( 'click', '.hacu-uploader__file-selector', ( e ) => {
			e.preventDefault();
			const target = e.target;
			const definedId = target.getAttribute( 'data-id' );
			const definedType = target.getAttribute( 'data-type' );
			const definedMimes = target.getAttribute( 'data-mimes' );
			const elements = Uploader.elements( target, definedType );
			const validTypes = [ 'application', 'audio', 'image', 'video' ];
			if (
				! elements ||
				! definedId ||
				! definedMimes ||
				! validTypes.includes( definedType )
			) {
				return;
			}

			const { inputElem, uploaderElem, thumbnailElem, mediaPlayerElem } = elements;
			const currentValue = inputElem.value;
			// eslint-disable-next-line require-jsdoc
			const getParsedMimes = ( mimes ) => {
				let parsedMimes;
				try {
					parsedMimes = JSON.parse( mimes );
				} catch ( error ) {
					parsedMimes = [];
				}

				return parsedMimes;
			};

			// eslint-disable-next-line require-jsdoc
			const getFileExtension = ( filename ) => {
				return /(?:\.([^.]+))?$/.exec( filename )[ 1 ];
			};

			// eslint-disable-next-line prefer-const
			let mediaUploader;
			if ( mediaUploader ) {
				mediaUploader.open();
				return;
			}

			mediaUploader = wp.media.frames.file_frame = wp
				.media( {
					title: `Select ${
						definedType.charAt( 0 ).toUpperCase() + definedType.slice( 1 )
					}`,
					button: {
						text: 'Select',
					},
					library: {
						type: getParsedMimes( definedMimes ),
					},
					multiple: false,
				} )
				.on( 'open', () => {
					if ( 0 < currentValue.length ) {
						const selection = mediaUploader.state().get( 'selection' );
						const attachment = wp.media.attachment( currentValue );
						selection.add( attachment ? [ attachment ] : [] );
					}
				} )
				.on( 'close', () => {
					Uploader.initMediaPlayer( 0 );
				} )
				.on( 'selection:toggle', () => {
					Uploader.initMediaPlayer( 0 );
				} )
				.on( 'select', () => {
					const attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
					if ( ! attachment || attachment.id === parseInt( currentValue ) ) {
						return;
					}

					if ( definedType !== attachment.type ) {
						// eslint-disable-next-line no-alert
						alert(
							`File type ${ attachment.type } is not allowed. Please select ${ definedType } file type only.`
						);
						return;
					}

					if ( -1 === getParsedMimes( definedMimes ).indexOf( attachment.mime ) ) {
						// eslint-disable-next-line no-alert
						alert(
							`File extension ${ getFileExtension(
								attachment.filename
							) } is not allowed`
						);
						return;
					}

					if ( 'image' === definedType ) {
						Uploader.setImageThumbnail( elements, attachment );
					}

					if ( [ 'audio', 'video' ].includes( definedType ) ) {
						mediaPlayerElem.setAttribute( 'data-src', attachment.url );
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
		eventListener( 'click', '.hacu-uploader__file-remove', ( e ) => {
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
