<?php
/**
 * App > Inc > Installer.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Inc;

use Handy\Inc\Traits\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * The `Installer` class contains helper methods for
 * setting plugin's status and version.
 *
 * @since 1.0.0
 */
final class Installer {

    /**
     * Inherit Singleton.
     *
     * @since 1.0.0
     */
    use Singleton;

    /**
     * Protected class constructor to prevent direct object creation.
     *
     * @since 1.0.0
     */
    protected function __construct() {}

    /**
     * Plugin Activation.
     *
     * @since 1.0.0
     *
     * @return void
     */
	public static function activate() {
        flush_rewrite_rules();

        /**
         * Set plugin's version.
         */
        update_option( '_handy_plugin_version', HACU_PLUGIN_VERSION );
    }

    /**
     * Plugin Deactivation.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
