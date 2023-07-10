<?php
namespace Handy\Core;

use Handy\Inc\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * Core > Panel.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class Panel {

    /**
     * Initialize.
     * 
     * @since 1.0.0
     * 
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render panel.
     * $args = [
     *      'id'          => (string)  The unique slug like string to be used as an id.
     *      'title'       => (string)  The visible label or name of the panel.
     *      'description' => (string)  The discription of the panel.
     *      'priority'    => (integer) The order of panels appears in the Theme Customizer Sizebar.
     * ]
     * @return object
     */
    public function __construct( $customize, $args = [] ) {
        if ( ! empty( $customize ) && ! empty( $args ) ) {
            $this->render( $customize, $args );
        }
    }

    /**
     * Render Panel Layout.
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render panel.
     * @return void
     */
    public function render( $customize, $args ) {
        $schema = [
            'id'           => [
                'type'     => 'string',
                'required' => true
            ],
            'title'        => [
                'type'     => 'string',
                'required' => true
            ],
            'description'  => [
                'type'     => 'string',
                'required' => false
            ],
            'priority'     => [
                'type'     => 'integer',
                'required' => false
            ]
        ];

        $validated     = Validator::get_validated_argument( $schema, $args );
        $configuration = Validator::get_configuration( 'panel', $validated );
        if ( $validated && $configuration ) {
            $customize->add_panel( $validated['id'], $configuration );
        }
    }
}