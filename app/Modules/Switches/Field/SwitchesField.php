<?php
namespace Handy\Modules\Switches\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\Switches\Control\SwitchesControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Switches.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class SwitchesField extends Setting {

    /**
     * Return the validated choices value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_choices( $validated ) {
        $value = [
            'on'  => 'On',
            'off' => 'Off'
        ];

        if ( isset( $validated['choices'] ) && is_array( $validated['choices'] ) ) {
            $choices = $validated['choices'];
            $value   = [
                'on'  => ( isset( $choices['on'] ) && strlen( $choices['on'] ) > 0 ? $choices['on'] : 'On' ),
                'off' => ( isset( $choices['off'] ) && strlen( $choices['off'] ) > 0 ? $choices['off'] : 'Off' )
            ];
        }

        return $value;
    }

    /**
     * Render Switch Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render switches control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (boolean) The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'choices'           => (array)   The choices label of the control.
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
                'type'     => 'boolean',
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
            'choices'           => [
                'type'     => 'array',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = $this->get_validated_choices( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'switches', $customize, $validated );
            $customize->add_control( new SwitchesControl( $customize, $config['settings'], $config ) );
        }
    }
}