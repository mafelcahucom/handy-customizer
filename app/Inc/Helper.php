<?php
namespace Handy\Inc;

use Handy\Lib\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Helper.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */

final class Helper {

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
     * Logs data in debug.txt.
     * 
     * @since 1.0.0
     *
     * @param mixed  $log  Contains the data to be log.
     */
    public static function log( $log ) {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }

    
}