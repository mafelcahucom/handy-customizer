<?php
namespace Handy\Modules\ColorPicker\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\ColorPicker\Control\ColorPickerControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Color Picker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ColorPickerField extends Setting {

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $color   = '#ffffff';
        $format  = $validated['format'];
        $default = $validated['default'];
        if ( in_array( $format, [ 'hex', 'hexa' ] ) ) {
            $is_valid = Validator::is_hexa( $default );    
            $color    = ( $is_valid ? $default : ( $format === 'hex' ? '#ffffff' : '#ffffffff' ) );
        }

        if ( in_array( $format, [ 'hsl', 'hsla' ] ) ) {
            $is_valid = Validator::is_hsla( $default );
            $color    = ( $is_valid ? $default : ( $format === 'hsl' ? 'hsl(0,0%,100%)' : 'hsla(0,0%,100%,1)' ) );
        }

        if ( in_array( $format, [ 'rgb', 'rgba' ] ) ) {
            $is_valid = Validator::is_rgba( $default );
            $color    = ( $is_valid ? $default : ( $format === 'rgb' ? 'rgb(255,255,255)' : 'rgb(255,255,255,1)' ) );
        }

        return $color;
    }

    /**
     * Return the validated format value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_format( $validated ) {
        $formats         = [ 'hex', 'hexa', 'hsl', 'hsla', 'rgb', 'rgba' ];
        $is_valid_format = ( isset( $validated['format'] ) && in_array( $validated['format'], $formats ) );
        return ( $is_valid_format ? $validated['format'] : 'hex' );
    }

    /**
     * Return the predetermined default validations.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments
     * @return string
     */
    private function get_default_validations( $validated ) {
        $validation  = "valid_color[{$validated['format']}]";
        $validations = [ $validation ];
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
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render color picker control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (string)   The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'format'            => (string)  The color format [ hex, rgba, hsva, hsla, cmyk ]
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
            'placeholder'       => [
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
            'format'            => [
                'type'     => 'string',
                'required' => false
            ]
        ];

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