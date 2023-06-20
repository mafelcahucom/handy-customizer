<?php
namespace Handy\Fields;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Controls\CounterControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Counter.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CounterField extends Setting {

    /**
     * Return the validated options. Validate the value of minimum, maximum and stepper.
     * 
     * @since 1.0.0
     *
     * @param  array  $options  Contains the option settings.
     * $options = [
     *      'min'  => (number) The minimum value of the counter.
     *      'max'  => (number) The maximum value of the counter.
     *      'step' => (number) The stepper of the counter.
     * ]
     * @return array
     */
    private function get_validated_options( $options ) {
        $value = [
            'min'  => '∞',
            'max'  => '∞',
            'step' => 1
        ];

        $is_valid_min = ( isset( $options['min'] ) && is_numeric( $options['min'] ) );
        $is_valid_max = ( isset( $options['max'] ) && is_numeric( $options['max'] ) );
        if ( $is_valid_min && $is_valid_max ) {
            $min = floatval( $options['min'] );
            $max = floatval( $options['max'] );
            if ( $min < $max ) {
                $value['min'] = $min;
                $value['max'] = $max;
            }
        }

        if ( isset( $options['step'] ) && is_numeric( $options['step'] ) ) {
            $step = floatval( $options['step'] );
            if ( $step > 0 ) {
                $value['step'] = $step;
            }
        }

        return $value;
    }

    /**
     * Render Counter Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render counter control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (number)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'options'           => (array)   The set of options minimum, maximum and stepper.
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
                'type'     => 'number',
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
            'options'           => [
                'type'     => 'array',
                'required' => true
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( isset( $validated['options'] ) ) {
            $validated['options'] = $this->get_validated_options( $validated['options'] );
        }

        if ( ! empty( $validated ) ) {
            if ( isset( $validated['validations'] ) ) {
                array_unshift( $validated['validations'], 'is_number' );
            } else {
                $validated['validations'] = [ 'is_number' ];
            }
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'counter', $customize, $validated );
            $customize->add_control( new CounterControl( $customize, $config['settings'], $config ) );
        }
    }
}