<?php
namespace Handy;

use Handy\Lib\Singleton;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Setup.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */

final class Setup {

    /**
     * Inherit Singleton.
     */
    use Singleton;

    /**
     * Protected class constructor to prevent direct object creation.
     *
     * @since 1.0.0
     */
    protected function __construct() {
        Helper::log( 'HELLO FUCKING WORKD' );
    }

    public static function get() {

    }
}