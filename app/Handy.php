<?php
/**
 * App > Handy.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

use Handy\Inc\Traits\Utilities;
use Handy\Inc\Helper;
use Handy\Core\Panel;
use Handy\Core\Section;

defined( 'ABSPATH' ) || exit;

/**
 * The `Handy` class contains the three major component of
 * WP_Customizer. This will act as the plugin main class,
 * and can use to create a customizer components.
 *
 * @since 1.0.0
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
     * @param  array $args Contains the necessary arguments to render panel layout.
     * $args = [
     *      'id'          => (string)  Contains the unique slug like string to be used as an id.
     *      'title'       => (string)  Contains the visible label or name of the panel.
     *      'description' => (string)  Contains the discription of the panel.
     *      'priority'    => (integer) Contains the order of panels appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public static function panel( $args = array() ) {
        if ( Helper::is_continue( $args ) ) {
            new Panel( self::wp_customize(), $args );
        }
    }

    /**
     * Render Section Layout.
     *
     * @since 1.0.0
     *
     * @param  array $args Contains the necessary arguments to render section layout.
     * @args = [
     *      'id'          => (string)  Contains the unique slugin like string to be used as an id.
     *      'panel'       => (string)  Contains the id of the panel where section can be reside.
     *      'title'       => (string)  Contains the visible label or name of the section.
     *      'description' => (string)  Contains the discription of the section.
     *      'priority'    => (integer) Contains the order of sections appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public static function section( $args = array() ) {
        if ( Helper::is_continue( $args ) ) {
            new Section( self::wp_customize(), $args );
        }
    }

    /**
     * Render Control Field.
     *
     * @since 1.0.0
     *
     * @param  string $type Contains the type of control to be render.
     * @param  array  $args Contains the necessary arguments to render a certain control.
     * @return void
     */
    public static function control( $type, $args = array() ) {
        if ( empty( $type ) || ! Helper::is_continue( $args ) ) {
            return;
        }

        $module = Helper::get_module( $type );
        if ( ! empty( $module ) ) {
            $class_field = "Handy\\Modules\\{$module['dir']}\\Field\\{$module['field']}";
            if ( class_exists( $class_field ) ) {
                ( new $class_field() )->render( self::wp_customize(), $args );
            }
        }
    }
}
