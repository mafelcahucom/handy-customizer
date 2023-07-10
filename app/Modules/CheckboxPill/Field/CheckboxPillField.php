<?php
namespace Handy\Modules\CheckboxPill\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\CheckboxPill\Control\CheckboxPillControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Checkbox Pill.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CheckboxPillField extends Setting {

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_default( $validated ) {
        return Helper::get_intersected( $validated['default'], array_keys( $validated['choices'] ) );
    }

    /**
     * Return the validated shape value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_shape( $validated ) {
        $is_valid_shape = ( isset( $validated['shape'] ) && in_array( $validated['shape'], [ 'square', 'round' ] ) );
        return ( $is_valid_shape ? $validated['shape'] : 'square' );
    }

    /**
     * Return the validated display value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments
     * @return string
     */
    private function get_validated_display( $validated ) {
        $is_valid_display = ( isset( $validated['display'] ) && in_array( $validated['display'], [ 'inline', 'block' ] ) );
        return ( $is_valid_display ? $validated['display'] : 'inline' );
    }

    /**
     * Return the predetermined default validations.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( array_keys( $validated['choices'] ), [ '__' ] ) );
        $validation  = "values_in_choices[{$parameters}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
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
     *      'shape'             => (string)  The shape of the checkbox pills [ square, round ].
     *      'display'           => (string)  The display of the checkbox pills [ block, inline ].
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
            ],
            'shape'             => [
                'type'     => 'string',
                'required' => false
            ],
            'display'           => [
                'type'     => 'string',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = array_unique( $validated['choices'] );
            if ( empty( $validated['choices'] ) ) {
                return;
            }

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['shape']       = $this->get_validated_shape( $validated );
            $validated['display']     = $this->get_validated_display( $validated );
            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'checkbox_pill', $customize, $validated );
            $customize->add_control( new CheckboxPillControl( $customize, $config['settings'], $config ) );
        }
    }
}