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
        // Register styles and scripts.
        add_action( 'admin_enqueue_scripts', [ $this, 'register_styles' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ] );
    }

    /**
     * Register all styles.
     *
     * @since 1.0.0
     */
    public function register_styles() {
        if ( ! is_customize_preview() ) {
            return;
        }

        $source  = Helper::get_asset_src( 'css/main.min.css' );
        $version = Helper::get_asset_version( 'css/main.min.css' );

        wp_register_style( 'handy-main', $source, [], $version, 'all' );
        wp_enqueue_style( 'handy-main' );
    }

    /**
     * Register all scripts.
     *
     * @since 1.0.0
     */
    public function register_scripts() {
        if ( ! is_customize_preview() ) {
            return;
        }

        $dependency = [ 'jquery' ];
        $source     = Helper::get_asset_src( 'js/main.min.js' );
        $version    = Helper::get_asset_version( 'js/main.min.js' );

        wp_register_script( 'handy-main', $source, $dependency, $version, true );
        wp_enqueue_script( 'handy-main' );
    }
}