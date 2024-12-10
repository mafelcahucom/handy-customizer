<?php
/**
 * App > Modules > Code Editor > Field > Code Editor Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\CodeEditor\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\CodeEditor\Control\CodeEditorControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `CodeEditorField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class CodeEditorField extends Setting {

    /**
     * Return the validated language and return MIME.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_language( $validated ) {
        $mimes = array(
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
            'yaml'       => 'text/x-yaml',
        );

        $is_valid_language = isset( $validated['language'] ) && isset( $mimes[ $validated['language'] ] );
        return $is_valid_language ? $mimes[ $validated['language'] ] : 'text/html';
    }

    /**
     * Render Code Editor Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render code editor control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (string)  Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'language'          => (string)  Contains the type of programming language allowed
     * ]
     * @return void
     */
    public function render( $customize, $args = array() ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = array(
            'id'                => array(
                'type'     => 'string',
                'required' => true,
            ),
            'section'           => array(
                'type'     => 'string',
                'required' => true,
            ),
            'default'           => array(
                'type'     => 'string',
                'required' => false,
            ),
            'label'             => array(
                'type'     => 'string',
                'required' => false,
            ),
            'description'       => array(
                'type'     => 'string',
                'required' => false,
            ),
            'priority'          => array(
                'type'     => 'integer',
                'required' => false,
            ),
            'validations'       => array(
                'type'     => 'array',
                'required' => false,
            ),
            'active_callback'   => array(
                'type'     => 'mixed',
                'required' => false,
            ),
            'sanitize_callback' => array(
                'type'     => 'mixed',
                'required' => false,
            ),
            'language'          => array(
                'type'     => 'string',
                'required' => false,
            ),
        );

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
