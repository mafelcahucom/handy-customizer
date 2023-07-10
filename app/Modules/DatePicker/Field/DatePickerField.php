<?php
namespace Handy\Modules\DatePicker\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\DatePicker\Control\DatePickerControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Date Picker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class DatePickerField extends Setting {

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $length      = ( $validated['mode'] === 'single' ? 1 : 2 );
        $format      = ( $validated['enable_time'] === true ? 'Y-m-d H:i' : 'Y-m-d' );
        $has_invalid = ( count( $validated['default'] ) !== $length ? true : false );
        if ( ! $has_invalid ) {
            foreach ( $validated['default'] as $date ) {
                if ( ! Validator::is_valid_date( $date, $format ) ) {
                    $has_invalid = true;
                }
            }
        }

        return ( ! $has_invalid ? $validated['default'] : [] );
    }

    /**
     * Return the validated enable time value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_enable_time( $validated ) {
        return ( isset( $validated['enable_time'] ) ? $validated['enable_time'] : false );
    }

    /**
     * Return the validated mode value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_mode( $validated ) {
        $is_valid_mode = ( isset( $validated['mode'] ) && in_array( $validated['mode'], [ 'single', 'range' ] ) );
        return ( $is_valid_mode ? $validated['mode'] : 'single' );
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
        $format      = ( $validated['enable_time'] === true ? 'Y-m-d H:i' : 'Y-m-d' );
        $validation  = "valid_dates[{$format}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Date Picker Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render date picker control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (array)   The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'enable_time'       => (boolean) The flag to add time in date result.
     *      'mode'              => (string)  The set type of mode of date picker whether single or range.
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
                'type'     => 'array',
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
            'enable_time'       => [
                'type'     => 'boolean',
                'required' => false
            ],
            'mode'              => [
                'type'     => 'string',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['enable_time'] = $this->get_validated_enable_time( $validated );
            $validated['mode']        = $this->get_validated_mode( $validated );
            
            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'date_picker', $customize, $validated );
            $customize->add_control( new DatePickerControl( $customize, $config['settings'], $config ) );
        }
    }
}