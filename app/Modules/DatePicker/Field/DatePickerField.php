<?php
/**
 * App > Modules > Date Picker > Field > Date Picker Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\DatePicker\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\DatePicker\Control\DatePickerControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `DatePickerField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class DatePickerField extends Setting {

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $length      = $validated['mode'] === 'single' ? 1 : 2;
        $format      = $validated['enable_time'] === true ? 'Y-m-d H:i' : 'Y-m-d';
        $has_invalid = count( $validated['default'] ) !== $length ? true : false;
        if ( ! $has_invalid ) {
            foreach ( $validated['default'] as $date ) {
                if ( ! Validator::is_valid_date( $date, $format ) ) {
                    $has_invalid = true;
                }
            }
        }

        return ! $has_invalid ? $validated['default'] : array();
    }

    /**
     * Return the validated enable time value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_enable_time( $validated ) {
        return isset( $validated['enable_time'] ) ? $validated['enable_time'] : false;
    }

    /**
     * Return the validated mode value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_mode( $validated ) {
        $is_valid_mode = isset( $validated['mode'] ) && in_array( $validated['mode'], array( 'single', 'range' ), true );
        return $is_valid_mode ? $validated['mode'] : 'single';
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
        $format      = $validated['enable_time'] === true ? 'Y-m-d H:i' : 'Y-m-d';
        $validation  = "valid_dates[{$format}]";
        $validations = array( $validation );
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
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render date picker control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (array)   Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'placeholder'       => (string)  Contains the placeholder of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'enable_time'       => (boolean) Contains the flag to add time in date result.
     *     'mode'              => (string)  Contains the set type of mode of date picker whether single or range.
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
                'type'     => 'array',
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
            'placeholder'       => array(
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
            'enable_time'       => array(
                'type'     => 'boolean',
                'required' => false,
            ),
            'mode'              => array(
                'type'     => 'string',
                'required' => false,
            ),
        );

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
