<?php
namespace Handy\Inc;

use Handy\Lib\Singleton;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Validator.
 *
 * @since 	1.0.0
 * @version 1.0.0
 * @author Mafel John Cahucom
 */
final class Validator {

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
     * Return the validated arguments for panel, section and controls.
     * 
     * @since 1.0.0
     *
     * @param  array  $schema  Contains the argument schema or rules.
     * @param  array  $args    Contains the arguments to be validate.
     * @return array|boolean
     */
    public static function validate_arguments( $schema, $args ) {
        if ( empty( $schema ) || ! is_array( $schema ) || empty( $args ) || ! is_array( $args ) ) {
            return false;
        }

        $validated   = [];
        $has_invalid = false;
        foreach ( $schema as $key => $value ) {
            $is_isset    = ( isset( $args[ $key ] ) );
            $is_empty    = ( strlen( $args[ $key ] ) === 0 );
            $is_required = $value['required'];

            if ( ( ! $is_isset && $is_required ) || ( $is_empty && $is_required ) ) {
                $has_invalid = true;
                break;
            }

            $is_invalid_value = false;
            switch ( $value['type'] ) {
                case 'string':
                    $is_invalid_value = ( ! is_string( $args[ $key ] ) );
                    break;
                case 'integer':
                    $is_invalid_value = ( ! is_int( $args[ $key ] ) );
                    break;
                case 'boolean':
                    $is_invalid_value = ( ! is_bool( $args[ $key ] ) );
                    break;
            }

            if ( $is_invalid_value && $is_required ) {
                $has_invalid = true;
                break;
            }

            $is_push = true;
            if ( ! $is_required && ( $is_invalid_value || $is_empty ) ) {
                $is_push = false;
            }

            if ( $is_push ) {
                $validated[ $key ] = $args[ $key ];
            }
        }

        return ( $has_invalid ? false : $validated );
    }

    /**
     * Check whether the variable is valid string type.
     * 
     * @since 1.0.0
     *
     * @param  string  $data  The variable to be checked.
     * @return boolean
     */
    public static function is_string( $data ) {
        return is_string( $data );
    }

    /**
     * Check whether the variable is valid integer type.
     * 
     * @since 1.0.0
     *
     * @param  string  $data  The variable to be checked.
     * @return boolean
     */
    public static function is_integer( $data ) {
        return is_int( $data );
    }
}