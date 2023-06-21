<?php
use Handy\Inc\Helper;
use Handy\Inc\Utilities;
use Handy\Core\Panel;
use Handy\Core\Section;

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
     * Inherit Utilities.
     * 
     * @since 1.0.0
     */
    use Utilities;

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
     * Render Panel Layout.
     * 
     * @since 1.0.0
     *
     * 
     * @param  array  $args  Contains the arguments needed to render panel layout.
     * $args = [
     *      'id'          => (string)  The unique slug like string to be used as an id.
     *      'title'       => (string)  The visible label or name of the panel.
     *      'description' => (string)  The discription of the panel.
     *      'priority'    => (integer) The order of panels appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public static function panel( $args = [] ) {
        if ( Helper::is_continue( $args ) ) {
            new Panel( self::wp_customize(), $args );
        }
    }

    /**
     * Render Section Layout.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed to render section layout.
     * @args = [
     *      'id'          => (string)  The unique slugin like string to be used as an id.
     *      'panel'       => (string)  The id of the panel where section can be reside.
     *      'title'       => (string)  The visible label or name of the section.
     *      'description' => (string)  The discription of the section.
     *      'priority'    => (integer) The order of sections appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public static function section( $args = [] ) {
        if ( Helper::is_continue( $args ) ) {
            new Section( self::wp_customize(), $args );
        }
    }

    /**
     * Render Field.
     * 
     * @since 1.0.0
     *
     * @param  string  $type  The type of field to be render.
     * @param  array   $args  Contains the arguments needed to render a certain field.
     * @return void
     */
    public static function field( $type, $args = [] ) {
        if ( empty( $type ) || ! Helper::is_continue( $args ) ) {
            return;
        }

        $classname = Helper::get_field_classname( $type );
        if ( empty( $classname ) ) {
            return;
        }

        $class_field = "Handy\\Fields\\{$classname}";
        if ( class_exists( $class_field ) ) {
            ( new $class_field )->render( self::wp_customize(), $args );
        }
    }
}