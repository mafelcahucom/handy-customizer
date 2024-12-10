/**
 * Content Editor Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const ContentEditor = {
	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.initTinyMCE();
		this.onEditorUpdate();
	},

	/**
	 * Initialize TinyMCE content editor using wp_content_editor.
	 *
	 * @since 1.0.0
	 */
	initTinyMCE() {
		jQuery( () => {
			const textareaElems = document.querySelectorAll( '.hacu-content-editor__textarea' );
			if ( 0 < textareaElems.length ) {
				textareaElems.forEach( ( textareaElem ) => {
					const id = textareaElem.getAttribute( 'id' );
					if ( 0 < id.length ) {
						// eslint-disable-next-line require-jsdoc
						const getUploader = () => {
							const uploader = textareaElem.getAttribute( 'data-uploader' );
							return 0 === uploader.length || ! uploader ? false : true;
						};

						// eslint-disable-next-line require-jsdoc
						const getToolbars = () => {
							const toolbars = textareaElem.getAttribute( 'data-toolbars' );
							const defaultToolbars =
								'bold italic bullist numlist alignleft aligncenter alignright link unlink wp_more spellchecker underline alignjustify forecolor formatselect';
							return 0 === toolbars.length ? defaultToolbars : toolbars;
						};

						wp.editor.initialize( id, {
							quicktags: true,
							mediaButtons: getUploader(),
							tinymce: {
								wpautop: true,
								toolbar1: getToolbars(),
								toolbar2: '',
							},
						} );
					}
				} );
			}
		} );
	},

	/**
	 * On trigger event on editor updated or changed value.
	 *
	 * @since 1.0.0
	 */
	onEditorUpdate() {
		jQuery( document ).on( 'tinymce-editor-init', ( event, editor ) => {
			editor.on( 'change', () => {
				// eslint-disable-next-line no-undef
				tinyMCE.triggerSave();
				jQuery( `#${ editor.id }` ).trigger( 'change' );
			} );
		} );
	},
};

export default ContentEditor;
