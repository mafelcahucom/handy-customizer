<?php
/**
 * Plugin Name:          Handy Customizer Framework
 * Description:          Handy Customizer Framework is a tool for WordPress Theme Developer to develop theme using WordPress Customizer API while writing clean and minimal code.
 * Author:               Mafel John Cahucom
 * Author URI:           https://www.mafeljohncahucom.site
 * Version:              1.0.0
 * Text Domain:          handy-customizer
 * Domain Path:          /languages
 * Requires at least:    4.9
 * License:              GPLv3 or later
 */

defined( 'ABSPATH' ) || exit;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

if ( ! function_exists( 'hacu_is_plugin_directory' ) ) {

    /**
     * Check whether the current location of the handy is in plugin directory.
     *
     * @since 1.0.0
     *
     * @return boolean
     */
    function hacu_is_plugin_directory() {
        return ( strpos( __FILE__, 'wp-content\plugins' ) !== false ? true : false );
    }
}

if ( ! function_exists( 'hacu_theme_dir_url' ) ) {

    /**
     * Return the URL directory path of handy inside theme.
     *
     * @since 1.0.0
     *
     * @return string
     */
    function hacu_theme_dir_url() {
        $theme_dir             = explode( '/', get_template_directory_uri() );
        $theme_folder          = $theme_dir[ count( $theme_dir ) - 1 ];
        $current_location_dir  = explode( '\\', __DIR__ );
        $theme_folder_index    = array_search( $theme_folder, $current_location_dir ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        $current_location_path = implode( '/', array_slice( $current_location_dir, $theme_folder_index + 1 ) );
        return get_template_directory_uri() . '/' . $current_location_path . '/';
    }
}

if ( ! defined( 'HACU_PLUGIN_DOMAIN' ) ) {
    define( 'HACU_PLUGIN_DOMAIN', 'handy-customizer' );
}

if ( ! defined( 'HACU_PLUGIN_VERSION' ) ) {
    define( 'HACU_PLUGIN_VERSION', '1.0.0' );
}

if ( ! defined( 'HACU_PLUGIN_BASENAME' ) ) {
    $basename = ( hacu_is_plugin_directory() ? plugin_basename( __FILE__ ) : wp_basename( __FILE__ ) );
    define( 'HACU_PLUGIN_BASENAME', $basename );
}

if ( ! defined( 'HACU_PLUGIN_URL' ) ) {
    $dir_url = ( hacu_is_plugin_directory() ? plugin_dir_url( __FILE__ ) : hacu_theme_dir_url() );
    define( 'HACU_PLUGIN_URL', $dir_url );
}

if ( ! defined( 'HACU_PLUGIN_PATH' ) ) {
    define( 'HACU_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( class_exists( 'Handy\\Inc\\Installer' ) ) {
    register_activation_hook( __FILE__, array( 'Handy\\Inc\\Installer', 'activate' ) );

    register_deactivation_hook( __FILE__, array( 'Handy\\Inc\\Installer', 'deactivate' ) );
}

if ( class_exists( 'Handy\\Setup' ) ) {
    call_user_func( array( 'Handy\\Setup', 'get_instance' ) );
}

require __DIR__ . '/app/Handy.php';
