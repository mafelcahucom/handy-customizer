<?php
/**
 * App > Modules > Size > Field > Size Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Size\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\Size\Control\SizeControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `SizeField` class contains the settings,
 * sanitization and validation .
 *
 * @since 1.0.0
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
        return array(
            'px',
'%',
'pt',
'pc',
'in',
'q',
'mm',
'cm',
'em',
'ex',
'ch',
'rem',
'lh',
'rlh',
            'vw',
'vh',
'vmin',
'vmax',
'vb',
'vi',
'swv',
'svh',
'lvw',
'lvh',
'dvw',
'dvh',
        );
    }

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return Validator::is_valid_size( $validated['default'], $validated['units'] ) ? $validated['default'] : '';
    }

    /**
     * Return the validated units.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_units( $validated ) {
        $units = array();
        if ( isset( $validated['units'] ) ) {
            $units = Helper::get_intersected( $validated['units'], $this->valid_units() );
        }

        return ! empty( $units ) ? $units : $this->valid_units();
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
        $validation  = 'valid_size[' . implode( ',', $validated['units'] ) . ']';
        $validations = array( $validation );
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
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments needed to render size control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (string)  Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'placeholder'       => (string)  Contains the placeholder of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'units'             => (array)   Contains the list of allowed unit size
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
                'type'     => 'string',
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
            'units'             => array(
                'type'     => 'array',
                'required' => false,
            ),
        );

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
