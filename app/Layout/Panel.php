<?php
namespace Handy\Layout;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Layout > Panel.
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
     * @param  array  $args  Contains the arguments needed to render panel.
     * $args = [
     *      'id'          => (string)  The unique slug like string to be used as an id.
     *      'title'       => (string)  The visible label or name of the panel.
     *      'description' => (string)  The discription of the panel.
     *      'priority'    => (integer) The order of panels appears in the Theme Customizer Sizebar.
     * ]
     */
    public function __construct( $args = [] ) {
        if ( empty( $args ) ) {
            return;
        }
    }

    /**
     * Return the WP_Customize_Manager.
     * 
     * @since 1.0.0
     *
     * @return object
     */
    private function wp_customize() {
        global $wp_customize;
        return $wp_customize;
    }

    /**
     * Render Panel Wrapper.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed to render panel.
     * $args = [
     *      'id'          => (string)  The unique slug like string to be used as an id.
     *      'title'       => (string)  The visible label or name of the panel.
     *      'description' => (string)  The discription of the panel.
     *      'priority'    => (integer) The order of panels appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    private function render( $args = [] ) {
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
    }
}