/**
 * Internal Dependencies
 */
import {
	queryElement,
	updateFieldValue,
} from '../../../assets/src/js/helpers';

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
		jQuery( function() {
			const textareaElems = document.querySelectorAll( '.hacu-code-editor__textarea' );
			if ( textareaElems.length > 0 ) {
				textareaElems.forEach( function( textareaElem ) {
					const elements = CodeEditor.elements( textareaElem );
					if ( elements ) {
						const { inputElem } = elements;
						const mime = textareaElem.getAttribute( 'data-language' );
						const editorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {};
						editorSettings.codemirror = _.extend( {}, editorSettings.codemirror, {
							mode: ( mime.length > 0 ? mime : 'text/html' ),
						} );

						const editor = wp.codeEditor.initialize( textareaElem, editorSettings );
						editor.codemirror.setValue( inputElem.value );
						editor.codemirror.on( 'blur', function() {
							updateFieldValue( inputElem, editor.codemirror.getValue() );
						} );
					}
				} );
			}
		} );
	},
};

export default CodeEditor;
