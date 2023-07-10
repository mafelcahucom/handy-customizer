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
		const textareaElems = document.querySelectorAll( '.hacu-content-editor__textarea' );
		if ( textareaElems.length > 0 ) {
			textareaElems.forEach( function( textareaElem ) {
				const id = textareaElem.getAttribute( 'id' );
				if ( id.length > 0 ) {
					const getUploader = function() {
						const uploader = textareaElem.getAttribute( 'data-uploader' );
						return ( uploader.length === 0 || ! uploader ? false : true );
					};

					const getToolbars = function() {
						const toolbars = textareaElem.getAttribute( 'data-toolbars' );
						const defaultToolbars = 'bold italic bullist numlist alignleft aligncenter alignright link unlink wp_more spellchecker underline alignjustify forecolor formatselect';
						return ( toolbars.length === 0 ? defaultToolbars : toolbars );
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
	},

	/**
	 * Trigger event on editor updated or changed value.
	 *
	 * @since 1.0.0
	 */
	onEditorUpdate() {
		$( document ).on( 'tinymce-editor-init', function( event, editor ) {
			editor.on( 'change', function() {
				tinyMCE.triggerSave();
				$( `#${ editor.id }` ).trigger( 'change' );
			} );
		} );
	},
};

export default ContentEditor;
