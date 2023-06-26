<?php
namespace Handy\Modules\CheckboxMultiple\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\CheckboxMultiple\Control\CheckboxMultipleControl;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Checkbox Multiple.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CheckboxMultipleField extends Setting {

    /**
     * Return the validated default value. Validate default if exist in choices.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed for default validation.
     * $args = [
     *      'default' => (array) The default value to be validated.
     *      'choices' => (array) The list of choices.
     * ]
     * @return string
     */
    private function get_validated_default( $args = [] ) {
        return Helper::get_intersected( $args['default'], array_keys( $args['choices'] ) );
    }

    /**
     * Render Checkbox Multiple Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render checkbox multiple control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (array)   The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'choices'           => (array)   The list of choices.
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
            'choices'           => [
                'type'     => 'array',
                'required' => true
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( isset( $validated['choices'] ) ) {
            $validated['choices'] = array_unique( $validated['choices'] );
            if ( empty( $validated['choices'] ) ) {
                return;
            }
        }

        if ( isset( $validated['default'] ) ) {
            $validated['default'] = $this->get_validated_default([
                'default' => $validated['default'],
                'choices' => $validated['choices']
            ]);
        }

        if ( ! empty( $validated ) ) {
            $parameters = implode( ',', array_merge( array_keys( $validated['choices'] ), [ '__' ] ) );
            $validation = "values_in_choices[{$parameters}]";
            if ( isset( $validated['validations'] ) ) {
                array_unshift( $validated['validations'], $validation );
            } else {
                $validated['validations'] = [ $validation ];
            }
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'checkbox_multiple', $customize, $validated );
            $customize->add_control( new CheckboxMultipleControl( $customize, $config['settings'], $config ) );
        }
    }
}