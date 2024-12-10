<?php
/**
 * App > Modules > Color Set > Field > Color Set Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ColorSet\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\ColorSet\Control\ColorSetControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `ColorSetField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class ColorSetField extends Setting {

    /**
     * Return the validated default value. Validate default if exist in colors.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        return in_array( $validated['default'], $validated['colors'] ) ? $validated['default'] : '';
    }

    /**
     * Return the validated array colors value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_colors( $validated ) {
        return array_unique( array_filter( $validated['colors'], function( $color ) {
            return Validator::is_valid_hexa_color( $color );
        }));
    }

    /**
     * Return the validated shape value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_shape( $validated ) {
        $is_valid_shape = isset( $validated['shape'] ) && in_array( $validated['shape'], array( 'square', 'round' ), true );
        return $is_valid_shape ? $validated['shape'] : 'round';
    }

    /**
     * Return the validated size value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_size( $validated ) {
        $units         = array( 'px', 'em', 'rem', 'ex' );
        $is_valid_size = isset( $validated['size'] ) && Validator::is_valid_size( $validated['size'], $units );
        return $is_valid_size ? $validated['size'] : '25px';
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
        $parameters  = implode( ',', array_merge( $validated['colors'], array( '__' ) ) );
        $validation  = "in_choices[{$parameters}]";
        $validations = array( $validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Color Set Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments needed to render color set control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (array)   Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'colors'            => (array)   Contains the list of color choices.
     *     'shape'             => (string)  Contains the shape of the color items [ square, round ].
     *     'size'              => (string)  Contains the size or width and height of the color items
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
            'colors'            => array(
                'type'     => 'array',
                'required' => true,
            ),
            'shape'             => array(
                'type'     => 'string',
                'required' => false,
            ),
            'size'              => array(
                'type'     => 'string',
                'required' => false,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['colors'] = $this->get_validated_colors( $validated );
            if ( empty( $validated['colors'] ) ) {
                return;
            }

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['shape']       = $this->get_validated_shape( $validated );
            $validated['size']        = $this->get_validated_size( $validated );
            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'color_set', $customize, $validated );
            $customize->add_control( new ColorSetControl( $customize, $config['settings'], $config ) );
        }
    }
}
