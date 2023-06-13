<?php
namespace Handy\Inc;

use Handy\Lib\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Icon.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class Icon {

    /**
     * Inherit Singleton.
     */
    use Singleton;

    /**
     * Protected class constructor to prevent direct object creation.
     *
     * @since 1.0.0
     */
    protected function __construct() {}

    /**
     * Return the svg icon.
     *
     * @since 1.0.0
     * 
     * @param  string  $type       The type of icon.
     * @param  string  $classname  Additional class.
     * @return string
     */
    public static function get( $type, $class = '' ) {
        $output  = '';
        $e_class = esc_attr( $class );
        switch ( $type ) {
            case 'plus':
                $output = "<svg class='". $class ."' xmlns='http://www.w3.org/2000/svg' fill='currentColor' class='bi bi-plus' viewBox='0 0 16 16'><path d='M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z'/></svg>";
                break;
            case 'dash':
                $output = "<svg class='". $class ."' xmlns='http://www.w3.org/2000/svg' fill='currentColor' class='bi bi-dash' viewBox='0 0 16 16'><path d='M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z'/></svg>";
                break;
        }

        return $output;
    }
}