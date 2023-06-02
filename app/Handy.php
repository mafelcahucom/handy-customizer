<?php
use Handy\Inc\Helper;
use Handy\Inc\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * Handy.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */

final class Handy {

    /**
     * Return the WP_Customize_Manager.
     * 
     * @since 1.0.0
     *
     * @return object
     */
    private static function wp_customize() {
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
    public static function panel( $args = [] ) {
        if ( ! is_customize_preview() || empty( $args ) ) {
            return;
        }

        $schema = [
            'id' => [
                'type'     => 'string',
                'required' => true
            ],
            'title' => [
                'type'     => 'string',
                'required' => true,
            ],
            'description' => [
                'type'     => 'string',
                'required' => false
            ],
            'priority' => [
                'type' => 'integer',
                'required' => false
            ]
        ];

        Helper::log( Validator::validate_arguments( $schema, $args ) );
    }
}