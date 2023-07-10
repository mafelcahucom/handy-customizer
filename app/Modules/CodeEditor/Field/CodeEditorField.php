<?php
namespace Handy\Modules\CodeEditor\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\CodeEditor\Control\CodeEditorControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Code Editor.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CodeEditorField extends Setting {

    /**
     * Return the validated language and return MIME.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_language( $validated ) {
        $mimes = [
            'html'       => 'text/html',
            'xml'        => 'application/xml',
            'javascript' => 'text/javascript',
            'jsx'        => 'text/jsx',
            'json'       => 'application/json',
            'css'        => 'text/css',
            'sass'       => 'text/x-sass',
            'scss'       => 'text/x-sass',
            'php'        => 'text/x-php',
            'http'       => 'message/http',
            'markdown'   => 'text/x-markdown',
            'yaml'       => 'text/x-yaml'
        ];
        
        $is_valid_language = ( isset( $validated['language'] ) && isset( $mimes[ $validated['language'] ] ) );
        return ( $is_valid_language ? $mimes[ $validated['language'] ] : 'text/html' );
    }

    /**
     * Render Code Editor Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render code editor control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (string)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'language'          => (string)  The type of programming language allowed
     * ]
     * @return void
     */
    public function render( $customize, $args = [] ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = [
            'id'                => [
                'type'     => 'string',
                'required' => true
            ],
            'section'           => [
                'type'     => 'string',
                'required' => true
            ],
            'default'           => [
                'type'     => 'string',
                'required' => false,
            ],
            'label'             => [
                'type'     => 'string',
                'required' => false,
            ],
            'description'       => [
                'type'     => 'string',
                'required' => false
            ],
            'priority'          => [
                'type'     => 'integer',
                'required' => false
            ],
            'validations'       => [
                'type'     => 'array',
                'required' => false
            ],
            'active_callback'   => [
                'type'     => 'mixed',
                'required' => false
            ],
            'sanitize_callback' => [
                'type'     => 'mixed',
                'required' => false
            ],
            'language'          => [
                'type'     => 'string',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['language'] = $this->get_validated_language( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'code_editor', $customize, $validated );
            $customize->add_control( new CodeEditorControl( $customize, $config['settings'], $config ) );
        }
    }
}