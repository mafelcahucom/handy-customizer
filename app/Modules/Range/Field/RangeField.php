<?php
/**
 * App > Modules > Range > Field > Range Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Range\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\Range\Control\RangeControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `RangeField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class RangeField extends Setting {

    /**
     * Return the validated options.
     *
     * @since 1.0.0
     *
     * @param  array $options Contains the option settings.
     * $options = [
     *     'min'  => (number) Contains the minimum value of the counter.
     *     'max'  => (number) Contains the maximum value of the counter.
     *     'step' => (number) Contains the stepper of the counter.
     * ]
     * @return array
     */
    private function get_validated_options( $options ) {
        $is_valid_min = isset( $options['min'] ) && is_numeric( $options['min'] );
        $is_valid_max = isset( $options['max'] ) && is_numeric( $options['max'] );
        if ( ! $is_valid_min || ! $is_valid_max ) {
            return false;
        }

        $minimum = floatval( $options['min'] );
        $maximum = floatval( $options['max'] );
        if ( $minimum >= $maximum || $maximum <= $minimum ) {
            return false;
        }

        $is_valid_step = isset( $options['step'] ) && is_numeric( $options['step'] );
        $stepper       = $is_valid_step ? floatval( $options['step'] ) : 1;

        return array(
            'min'  => $minimum,
            'max'  => $maximum,
            'step' => $stepper,
        );
    }

    /**
     * Return the predetermined default validations.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_default_validations( $validated ) {
        $min_validation = "greater_than_equal_to[{$validated['options']['min']}]";
        $max_validation = "less_than_equal_to[{$validated['options']['max']}]";
        $validations    = array( $min_validation, $max_validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $min_validation );
            array_unshift( $validations, $max_validation );
        }

        return $validations;
    }

    /**
     * Render Range Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render range control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (number)  Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'options'           => (array)   Contains the set of options minimum, maximum and stepper
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
                'type'     => 'number',
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
            'options'           => array(
                'type'     => 'array',
                'required' => true,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['options'] = $this->get_validated_options( $validated['options'] );
            if ( ! $validated['options'] ) {
                return;
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'range', $customize, $validated );
            $customize->add_control( new RangeControl( $customize, $config['settings'], $config ) );
        }
    }
}
