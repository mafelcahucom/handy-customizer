<?php
namespace Handy\Modules\Size\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\Size\Control\SizeControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Size.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class SizeField extends Setting {

    /**
     * Return the list of valid units.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function valid_units() {
        return [
            'px', '%', 'pt', 'pc', 'in', 'q', 'mm', 'cm', 'em', 'ex', 'ch', 'rem', 'lh', 'rlh', 
            'vw', 'vh', 'vmin', 'vmax', 'vb', 'vi', 'swv', 'svh', 'lvw', 'lvh', 'dvw', 'dvh'
        ];
    }

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     * 
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return ( Validator::is_valid_size( $validated['default'], $validated['units'] ) ? $validated['default'] : '' );
    }

    /**
     * Return the validated units.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_units( $validated ) {
        $units = [];
        if ( isset( $validated['units'] ) ) {
            $units = Helper::get_intersected( $validated['units'], $this->valid_units() );
        }

        return ( ! empty( $units ) ? $units : $this->valid_units() );
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
        $validation  = 'valid_size['. implode( ',', $validated['units'] ) .']';
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Size Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render size control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (string)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'units'             => (array)   The list of allowed unit size.
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
            'units'             => [
                'type'     => 'array',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['units'] = $this->get_validated_units( $validated );

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }
        
        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'size', $customize, $validated );
            $customize->add_control( new SizeControl( $customize, $config['settings'], $config ) );
        }
    }
}