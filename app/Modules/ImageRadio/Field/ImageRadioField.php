<?php
/**
 * App > Modules > Image Radio > Field > Image Radio Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ImageRadio\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\ImageRadio\Control\ImageRadioControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `ImageRadioField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class ImageRadioField extends Setting {

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return array_key_exists( $validated['default'], $validated['choices'] ) ? $validated['default'] : '';
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
        if ( ! isset( $validated['size'] ) || empty( $validated['size'] ) ) {
            return array(
                'width'  => 'max-content',
                'height' => 'auto',
            );
        }

        return array(
            'width'  => isset( $validated['size']['width'] ) ? $validated['size']['width'] : 'max-content',
            'height' => isset( $validated['size']['height'] ) ? $validated['size']['height'] : 'auto',
        );
    }

    /**
     * Return the validated display value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_display( $validated ) {
        $is_valid_display = isset( $validated['display'] ) && in_array( $validated['display'], array( 'inline', 'block' ), true );
        return $is_valid_display ? $validated['display'] : 'inline';
    }

    /**
     * Return the validated choices value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_choices( $validated ) {
        return array_unique( array_filter( $validated['choices'], function( $choice ) {
            return ( is_array( $choice ) && isset( $choice['image'] ) && isset( $choice['title'] ) );
        } ), SORT_REGULAR );
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
        $parameters  = implode( ',', array_merge( array_keys( $validated['choices'] ), array( '__' ) ) );
        $validation  = "in_choices[{$parameters}]";
        $validations = array( $validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Image Radio Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render image radio control.
     * $args = [
     *     'id'                => (string)  Contais the unique slug like string to be used as an id.
     *     'section'           => (string)  Contais the section where the control belongs to.
     *     'default'           => (string)  Contais the default value of the control.
     *     'label'             => (string)  Contais the label of the control.
     *     'description'       => (string)  Contais the description of the control.
     *     'priority'          => (integer) Contais the order of control appears in the section.
     *     'validations'       => (array)   Contais the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contais the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contais the callback function to sanitize the value before saving in database.
     *     'display'           => (string)  Contais the display of item choices, can contain block and inline only.
     *     'size'              => (array)   Contais the size of each item choices, must contain width and height.
     *     'choices'           => (array)   Contais the list of choices must contain image and title
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
            'display'           => array(
                'type'     => 'string',
                'required' => false,
            ),
            'size'              => array(
                'type'     => 'array',
                'required' => false,
            ),
            'choices'           => array(
                'type'     => 'array',
                'required' => true,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = $this->get_validated_choices( $validated );
            if ( empty( $validated['choices'] ) ) {
                return;
            }

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['display']     = $this->get_validated_display( $validated );
            $validated['size']        = $this->get_validated_size( $validated );
            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'image_radio', $customize, $validated );
            $customize->add_control( new ImageRadioControl( $customize, $config['settings'], $config ) );
        }
    }
}
