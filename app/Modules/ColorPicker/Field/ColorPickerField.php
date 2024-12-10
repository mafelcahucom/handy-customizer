<?php
/**
 * App > Modules > Color Picker > Field > Color Picker Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ColorPicker\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\ColorPicker\Control\ColorPickerControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `ColorPickerField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class ColorPickerField extends Setting {

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $color   = '#ffffff';
        $format  = $validated['format'];
        $default = $validated['default'];
        if ( in_array( $format, array( 'hex', 'hexa' ), true ) ) {
            $is_valid = Validator::is_hexa( $default );
            $color    = $is_valid ? $default : ( $format === 'hex' ? '#ffffff' : '#ffffffff' );
        }

        if ( in_array( $format, array( 'hsl', 'hsla' ), true ) ) {
            $is_valid = Validator::is_hsla( $default );
            $color    = $is_valid ? $default : ( $format === 'hsl' ? 'hsl(0,0%,100%)' : 'hsla(0,0%,100%,1)' );
        }

        if ( in_array( $format, array( 'rgb', 'rgba' ), true ) ) {
            $is_valid = Validator::is_rgba( $default );
            $color    = $is_valid ? $default : ( $format === 'rgb' ? 'rgb(255,255,255)' : 'rgb(255,255,255,1)' );
        }

        return $color;
    }

    /**
     * Return the validated format value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_format( $validated ) {
        $formats         = array( 'hex', 'hexa', 'hsl', 'hsla', 'rgb', 'rgba' );
        $is_valid_format = isset( $validated['format'] ) && in_array( $validated['format'], $formats, true );
        return $is_valid_format ? $validated['format'] : 'hex';
    }

    /**
     * Return the predetermined default validations.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments
     * @return string
     */
    private function get_default_validations( $validated ) {
        $validation  = "valid_color[{$validated['format']}]";
        $validations = array( $validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Color Picker Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render color picker control.
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
     *     'format'            => (string)  Contains the color format [ hex, rgba, hsva, hsla, cmyk ]
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
            'format'            => array(
                'type'     => 'string',
                'required' => false,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['format'] = $this->get_validated_format( $validated );

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'color_picker', $customize, $validated );
            $customize->add_control( new ColorPickerControl( $customize, $config['settings'], $config ) );
        }
    }
}
