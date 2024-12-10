<?php
/**
 * App > Modules > Image Checkbox > Field > Image Checkbox Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ImageCheckbox\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\ImageCheckbox\Control\ImageCheckboxControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `ImageCheckboxField` class contains the settings,
 * sanitization and validation of the video uploader.
 *
 * @since 1.0.0
 */
final class ImageCheckboxField extends Setting {

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return Helper::get_intersected( $validated['default'], array_keys( $validated['choices'] ) );
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
     * @param  array $validated Contains the validated arguments
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
        $validation  = "values_in_choices[{$parameters}]";
        $validations = array( $validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Image Checkbox Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render image checkbox control.
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
     *     'display'           => (string)  Contains the display of item choices, can contain block and inline only.
     *     'size'              => (array)   Contains the size of each item choices, must contain width and height.
     *     'choices'           => (array)   Contains the list of choices must contain image and title.
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
                'type'     => 'array',
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
            $this->setting( 'image_checkbox', $customize, $validated );
            $customize->add_control( new ImageCheckboxControl( $customize, $config['settings'], $config ) );
        }
    }
}
