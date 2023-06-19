<?php
namespace Handy\Fields;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Controls\MarkupControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Markup.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class MarkupField extends Setting {

    /**
     * Render Markup Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render markup control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'html'              => (string)  The HTML markup to be render.
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
            'priority'          => [
                'type'     => 'integer',
                'required' => false
            ],
            'active_callback'   => [
                'type'     => 'mixed',
                'required' => false
            ],
            'html'              => [
                'type'     => 'string',
                'required' => true
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        $config    = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'markup', $customize, $validated );
            $customize->add_control( new MarkupControl( $customize, $config['settings'], $config ) );
        }
    }
}