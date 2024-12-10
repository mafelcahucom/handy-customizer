<?php
/**
 * App > Modules > Switches > Field > Switches Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Switches\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\Switches\Control\SwitchesControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `SwitchesField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class SwitchesField extends Setting {

    /**
     * Return the validated choices value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_choices( $validated ) {
        $value = array(
            'on'  => 'On',
            'off' => 'Off',
        );

        if ( isset( $validated['choices'] ) && is_array( $validated['choices'] ) ) {
            $choices = $validated['choices'];
            $value   = array(
                'on'  => isset( $choices['on'] ) && strlen( $choices['on'] ) > 0 ? $choices['on'] : 'On',
                'off' => isset( $choices['off'] ) && strlen( $choices['off'] ) > 0 ? $choices['off'] : 'Off',
            );
        }

        return $value;
    }

    /**
     * Render Switch Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render switches control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (boolean) Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'placeholder'       => (string)  Contains the placeholder of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'choices'           => (array)   Contains the choices label of the control
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
                'type'     => 'boolean',
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
            'choices'           => array(
                'type'     => 'array',
                'required' => false,
            ),
        );

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
