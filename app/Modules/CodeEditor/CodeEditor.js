/**
 * Internal Dependencies
 */
import { queryElement, updateFieldValue } from '../../../resources/scripts/helpers';

/**
 * Code Editor Field.
 *
 * @since 1.0.0
 *
 * @type {Object}
 */
const CodeEditor = {
	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 */
	init() {
		this.initCodeMirror();
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
						selector: '.hacu-code-editor__input',
					},
				},
			} );
		}
	},

	/**
	 * Initialize code editor using defaut wordpress code editor (code mirror)
	 *
	 * @since 1.0.0
	 */
	initCodeMirror() {
		jQuery( () => {
			const textareaElems = document.querySelectorAll( '.hacu-code-editor__textarea' );
			if ( 0 < textareaElems.length ) {
				textareaElems.forEach( ( textareaElem ) => {
					const elements = CodeEditor.elements( textareaElem );
					if ( elements ) {
						const { inputElem } = elements;
						const mime = textareaElem.getAttribute( 'data-language' );
						const editorSettings = wp.codeEditor.defaultSettings
							? _.clone( wp.codeEditor.defaultSettings )
							: {};
						editorSettings.codemirror = _.extend( {}, editorSettings.codemirror, {
							mode: 0 < mime.length ? mime : 'text/html',
						} );

						const editor = wp.codeEditor.initialize( textareaElem, editorSettings );
						editor.codemirror.setValue( inputElem.value );
						editor.codemirror.on( 'blur', () => {
							updateFieldValue( inputElem, editor.codemirror.getValue() );
						} );
					}
				} );
			}
		} );
	},
};

export default CodeEditor;
