<?php
namespace Handy\Core;

use Handy\Inc\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * Core > Section.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class Section {

    /**
     * Initialize.
     * 
     * @since 1.0.0
     * 
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render section layout.
     * @args = [
     *      'id'          => (string)  The unique slugin like string to be used as an id.
     *      'panel'       => (string)  The id of the panel where section can be reside.
     *      'title'       => (string)  The visible label or name of the section.
     *      'description' => (string)  The discription of the section.
     *      'priority'    => (integer) The order of sections appears in the Theme Customizer Sizebar.
     * ]
     * @return object
     */
    public function __construct( $customize, $args = [] ) {
        if ( ! empty( $customize ) && ! empty( $args ) ) {
            $this->render( $customize, $args );
        }
    }

    /**
     * Render Section Layout.
     * 
     * @since 1.0.0
     * 
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render section layout.
     * @return void
     */
    public function render( $customize, $args ) {
        $schema = [
            'id'           => [
                'type'     => 'string',
                'required' => true
            ],
            'panel'        => [
                'type'     => 'string',
                'required' => false,
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
        $configuration = Validator::get_configuration( 'section', $validated );
        if ( $validated && $configuration ) {
            $customize->add_section( $validated['id'], $configuration );
        }
    }
}