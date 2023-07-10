<?php
/**
 * Plugin Name:          Handy Customizer Framework
 * Description:          Handy Customizer Framework is a tool for WordPress Theme Developer to develop theme using WordPress Customizer API while writing clean and minimal code.
 * Author:               Mafel John Cahucom
 * Author URI:           https://www.facebook.com/mafeljohn.cahucom
 * Version:              1.0.0
 * Text Domain:          handy-customizer
 * Domain Path:          /languages
 * Requires at least:    4.9
 * License:              GPLv2 or later
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Include the autoloader.
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

// Define plugin domain.
if ( ! defined( 'HACU_PLUGIN_DOMAIN' ) ) {
    define( 'HACU_PLUGIN_DOMAIN', 'handy-customizer' );
}

// Define plugin version.
if ( ! defined( 'HACU_PLUGIN_VERSION' ) ) {
    define( 'HACU_PLUGIN_VERSION', '1.0.0' );
}

// Define plugin basename.
if ( ! defined( 'HACU_PLUGIN_BASENAME' ) ) {
    define( 'HACU_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

// Define plugin url.
if ( ! defined( 'HACU_PLUGIN_URL' ) ) {
    define( 'HACU_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Define plugin path.
if ( ! defined( 'HACU_PLUGIN_PATH' ) ) {
    define( 'HACU_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}

// Installer.
if ( class_exists( 'Handy\\Inc\\Installer' ) ) {
    // Plugin Activation.
    register_activation_hook( __FILE__, [ 'Handy\\Inc\\Installer', 'activate' ] );

    // Plugin Deactivation.
    register_deactivation_hook( __FILE__, [ 'Handy\\Inc\\Installer', 'deactivate' ] );
}

// Setup.
if ( class_exists( 'Handy\\Setup' ) ) {
    call_user_func( [ 'Handy\\Setup', 'get_instance' ] );
}

// Load Main Class.
require dirname( __FILE__ ) .'/app/Handy.php';