<?php
namespace Handy\Fields;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Controls\TextControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Text.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class TextField extends Setting {

    /**
     * Render Text Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render text control.
     * $args = [
     *      'id'              => (string)  The unique slug like string to be used as an id.
     *      'section'         => (string)  The section where the control belongs to.
     *      'default'         => (mixed)   The default value of the control.
     *      'label'           => (string)  The label of the control.
     *      'description'     => (string)  The description of the control.
     *      'placeholder'     => (string)  The placeholder of the control.
     *      'priority'        => (integer) The order of control appears in the section. 
     *      'validations'     => (array)   Contains the list of built-in and custom validations.
     *      'active_callback' => (object)  Contains the callback function whether to show control, must always return true.
     * ]
     * @return void
     */
    public function render( $customize, $args = [] ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = [
            'id'              => [
                'type'     => 'string',
                'required' => true
            ],
            'section'         => [
                'type'     => 'string',
                'required' => true
            ],
            'default'      => [
                'type'     => 'mixed',
                'required' => false,
            ],
            'label'           => [
                'type'     => 'string',
                'required' => false,
            ],
            'description'     => [
                'type'     => 'string',
                'required' => false
            ],
            'placeholder'     => [
                'type'     => 'string',
                'required' => false
            ],
            'priority'     => [
                'type'     => 'integer',
                'required' => false
            ],
            'validations'     => [
                'type'     => 'array',
                'required' => false
            ],
            'active_callback' => [
                'type'     => 'mixed',
                'required' => false
            ]
        ];

        $validated     = Validator::get_validated_argument( $schema, $args );
        $configuration = Validator::get_configuration( 'field', $validated );
        if ( $validated && $configuration ) {
            $this->setting( $customize, $validated );
            $customize->add_control( new TextControl( $customize, "{$configuration['settings']}_control", $configuration ) );
        }
    }
}