<?php
namespace Handy\Modules\ContentEditor\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\ContentEditor\Control\ContentEditorControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Content Editor.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ContentEditorField extends Setting {

    /**
     * Return the validated uploader value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_uploader( $validated ) {
        return ( isset( $validated['uploader'] ) ? $validated['uploader'] : false );
    }

    /**
     * Return the validated and imploded toolbars value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_toolbars( $validated ) {
        $toolbars = [ 
            'bold', 'italic', 'strikethrough', 'bullist', 'numlist', 'blockquote', 'hr', 'alignleft', 'aligncenter', 
            'alignright', 'link', 'unlink', 'wp_more', 'spellchecker', 'fullscreen', 'wp_adv', 'formatselect', 'underline',  
            'alignjustify', 'forecolor', 'pastetext', 'removeformat', 'charmap', 'outdent', 'indent', 'undo', 'redo', 'wp_help' 
        ];

        $intersected = ( isset( $validated['toolbars'] ) ? Helper::get_intersected( $validated['toolbars'], $toolbars ) : [] ); 
        return implode( ' ', $intersected );
    }

    /**
     * Render Content Editor Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render checkbox multiple control.
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
     *      'uploader'          => (boolean) The flag whether to show Add Media button.
     *      'toolbars'          => (array)   The list of allowed controls in toolbar.
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
            'uploader'          => [
                'type'     => 'boolean',
                'required' => false
            ],
            'toolbars'          => [
                'type'     => 'array',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['uploader'] = $this->get_validated_uploader( $validated );
            $validated['toolbars'] = $this->get_validated_toolbars( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'content_editor', $customize, $validated );
            $customize->add_control( new ContentEditorControl( $customize, $config['settings'], $config ) );
        }
    }
}