<?php
namespace Handy\Modules\ImageRadio\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\ImageRadio\Control\ImageRadioControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Image Radio.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ImageRadioField extends Setting {

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     * 
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return ( array_key_exists( $validated['default'], $validated['choices'] ) ? $validated['default'] : '' );
    }

    /**
     * Return the validated size value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_size( $validated ) {
        if ( ! isset( $validated['size'] ) || empty( $validated['size'] ) ) {
            return [
                'width'  => 'max-content',
                'height' => 'auto'
            ];
        }

        return [
            'width'  => ( isset( $validated['width'] ) ? $validated['width'] : 'max-content' ),
            'height' => ( isset( $validated['height'] ) ? $validated['height'] : 'auto' )
        ];
    }

    /**
     * Return the validated display value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_display( $validated ) {
        $is_valid_display = ( isset( $validated['display'] ) && in_array( $validated['display'], [ 'inline', 'block' ] ) );
        return ( $is_valid_display ? $validated['display'] : 'inline' );
    }

    /**
     * Return the validated choices value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
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
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( array_keys( $validated['choices'] ), [ '__' ] ) );
        $validation  = "in_choices[{$parameters}]";
        $validations = [ $validation ];
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
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render image radio control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (string)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'display'           => (string)  The display of item choices, can contain block and inline only.
     *      'size'              => (array)   The size of each item choices, must contain width and height.
     *      'choices'           => (array)   The list of choices must contain image and title.
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
            'display'           => [
                'type'     => 'string',
                'required' => false
            ],
            'size'              => [
                'type'     => 'array',
                'required' => false
            ],
            'choices'           => [
                'type'     => 'array',
                'required' => true
            ]
        ];

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