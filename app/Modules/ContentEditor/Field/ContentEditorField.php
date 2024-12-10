<?php
/**
 * App > Modules > Content Editor > Field > Content Editor Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ContentEditor\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\ContentEditor\Control\ContentEditorControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `ContentEditorField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class ContentEditorField extends Setting {

    /**
     * Return the validated uploader value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_uploader( $validated ) {
        return isset( $validated['uploader'] ) ? $validated['uploader'] : false;
    }

    /**
     * Return the validated and imploded toolbars value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_toolbars( $validated ) {
        $toolbars = array(
            'bold',
'italic',
'strikethrough',
'bullist',
'numlist',
'blockquote',
'hr',
'alignleft',
'aligncenter',
            'alignright',
'link',
'unlink',
'wp_more',
'spellchecker',
'fullscreen',
'wp_adv',
'formatselect',
'underline',
            'alignjustify',
'forecolor',
'pastetext',
'removeformat',
'charmap',
'outdent',
'indent',
'undo',
'redo',
'wp_help',
        );

        $intersected = isset( $validated['toolbars'] ) ? Helper::get_intersected( $validated['toolbars'], $toolbars ) : array();
        return implode( ' ', $intersected );
    }

    /**
     * Render Content Editor Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render checkbox multiple control.
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
     *     'uploader'          => (boolean) Contains the flag whether to show Add Media button.
     *     'toolbars'          => (array)   Contains the list of allowed controls in toolbar
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
            'uploader'          => array(
                'type'     => 'boolean',
                'required' => false,
            ),
            'toolbars'          => array(
                'type'     => 'array',
                'required' => false,
            ),
        );

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
