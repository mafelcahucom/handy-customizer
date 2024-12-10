<?php
/**
 * App > Setup.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy;

use Handy\Inc\Traits\Singleton;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `Setup` class will register or instantiate all available
 * services and resources. This will act as an initialization.
 *
 * @since 1.0.0
 */
final class Setup {

    /**
     * Inherit Singleton.
     *
     * @since 1.0.0
     */
    use Singleton;

    /**
     * Initailize.
     *
     * @since 1.0.0
     */
    protected function __construct() {
        /**
         * Register styles and scripts.
         */
        add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
    }

    /**
     * Register all styles.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_styles() {
        if ( ! is_customize_preview() ) {
            return;
        }

        if ( ! wp_style_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_style( 'wp-mediaelement' );
        }

        $asset        = include HACU_PLUGIN_PATH . 'public/styles/hacu-app.asset.php';
        $asset['src'] = Helper::get_public_src( 'styles/hacu-app.css' );
        wp_register_style( 'hacu-app', $asset['src'], $asset['dependencies'], $asset['version'], 'all' );
        wp_enqueue_style( 'hacu-app' );
    }

    /**
     * Register all scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function register_scripts() {
        if ( ! is_customize_preview() ) {
            return;
        }

        if ( ! wp_script_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_script( 'wp-mediaelement' );
        }

        $asset                 = include HACU_PLUGIN_PATH . 'public/scripts/hacu-app.asset.php';
        $asset['src']          = Helper::get_public_src( 'scripts/hacu-app.js' );
        $asset['dependencies'] = array( 'jquery' );
        wp_register_script( 'hacu-app', $asset['src'], $asset['dependencies'], $asset['version'], true );
        wp_enqueue_script( 'hacu-app' );
    }
}
