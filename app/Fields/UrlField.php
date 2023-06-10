<?php
namespace Handy\Fields;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Controls\UrlControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Url.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class UrlField extends Setting {

    /**
     * Return the validated default value. Validate by url.
     * 
     * @since 1.0.0
     *
     * @param  string  $default  The default value to be validated.
     * @return string
     */
    private function get_validated_default( $default ) {
        return ( filter_var( $default, FILTER_VALIDATE_URL ) ? $default : '' );
    }

    /**
     * Render Url Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render url control.
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
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( isset( $validated['default'] ) ) {
            $validated['default'] = $this->get_validated_default( $validated['default'] );
        }

        if ( isset( $validated['validations'] ) ) {
            array_unshift( $validated['validations'], 'valid_url' );
        } else {
            $validated['validations'] = [ 'valid_url' ];
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'url', $customize, $validated );
            $customize->add_control( new UrlControl( $customize, "{$config['settings']}_field", $config ) );
        }
    }
}