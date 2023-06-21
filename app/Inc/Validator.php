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
     * Checks if the value is empty.
     * 
     * @since 1.0.0
     *
     * @param  mixed  $value  Contains the value to be check.
     * @return boolean
     */
    public static function is_empty( $value ) {
        return ( strlen( $value ) === 0 );
    }

    /**
     * Checks if the value is a valid size based on defined valid units.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The value size to be check.
     * @param  array   $units  The list of valid units.
     * @return boolean
     */
    public static function is_valid_size( $value = '', $units = [] ) {
        if ( ! is_string( $value ) || empty( $units ) || ! is_array( $units ) ) {
            return false;
        }

        $splited = preg_split( '/(?<=[0-9])(?=[a-z%]+)/i', $value );
        if ( count( $splited ) === 2 ) {
            if ( is_numeric( $splited[0] ) && in_array( $splited[1], $units ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the color string contains a valid hexadecimal color.
     * 
     * @since 1.0.0
     *
     * @param  string  $color  The string color to be check.
     * @return boolean
     */
    public static function is_valid_hexa_color( $color = '' ) {
        $is_valid = false;
        if ( is_string( $color ) ) {
            $is_valid = (bool) preg_match( '/^#([a-f0-9]{3}|[a-f0-9]{6}|[a-f0-9]{8})$/i', $color );
        }
        
        return $is_valid;
    }

    /**
     * Checks if the callback is invalid object or string type.
     * 
     * @since 1.0.0
     *
     * @param  mixed  $callback  The callback to be check.
     * @return boolean
     */
    public static function is_invalid_callback( $callback ) {
        $type            = gettype( $callback );
        $is_type_invalid = ( ! in_array( $type, [ 'string', 'object' ] ) );
        $is_empty        = ( $type === 'string' && empty( $callback ) );
        $is_not_found    = ( $type === 'string' && ! function_exists( $callback ) );

        return ( $is_type_invalid || $is_empty || $is_not_found );
    }

    /**
     * Return the validated arguments for panel, section and controls.
     * 
     * @since 1.0.0
     *
     * @param  array  $schema  Contains the argument schema or rules.
     * @param  array  $args    Contains the arguments to be validate.
     * @return array|boolean
     */
    public static function get_validated_argument( $schema, $args ) {
        if ( empty( $schema ) || ! is_array( $schema ) || empty( $args ) || ! is_array( $args ) ) {
            return false;
        }

        $validated = [];
        $has_invalid = false;
        foreach ( $schema as $key => $value ) {
            $is_required = $value['required'];
            if ( isset( $args[ $key ] ) ) {
                // Check if value is valid.
                $is_type_invalid = false;
                if ( $value['type'] !== 'mixed' && $value['type'] !== 'number' && $value['type'] !== gettype( $args[ $key ] ) ) {
                    $is_type_invalid = true;
                }

                if ( $value['type'] === 'number' ) {
                    if ( ! is_numeric( $args[ $key ] ) ) {
                        $is_type_invalid = true;
                    }
                }

                // If invalid type and required exit.
                if ( $is_type_invalid && $is_required ) {
                    $has_invalid = true;
                    break;
                }

                if ( ! $is_type_invalid ) {
                    // Check if empty.
                    $is_empty = false;
                    if ( in_array( $value['type'], [ 'string', 'number', 'integer', 'double' ] ) ) {
                        if ( strlen( $args[ $key ] ) === 0 ) {
                            $is_empty = true;
                        }
                    } elseif ( $value['type'] === 'array' ) {
                        if ( empty( $args[ $key ] ) ) {
                            $is_empty = true;
                        }
                    }

                    // If empty and required exit.
                    if ( $is_empty && $is_required ) {
                        $has_invalid = true;
                        break;
                    }

                    if ( ! $is_empty ) {
                        $validated[ $key ] = $args[ $key ];
                    }
                }
            } else {
                if ( $is_required ) {
                    $has_invalid = true;
                    break;
                }
            }
        }

        // Set "active_callback".
        if ( isset( $validated['active_callback'] ) ) {
            if ( self::is_invalid_callback( $validated['active_callback'] ) ) {
                unset( $validated['active_callback'] );
            }
        }

        // Set "sanitize_callback".
        if ( isset( $validated['sanitize_callback'] ) ) {
            if ( self::is_invalid_callback( $validated['sanitize_callback'] ) ) {
                unset( $validated['sanitize_callback'] );
            }
        }

        return ( $has_invalid || empty( $validated ) ? false : $validated );
    }

    /**
     * Return the panel, section and field configurations.
     * 
     * @since 1.0.0
     *
     * @param  string  $type  The type of component [panel, section, field].
     * @param  array   $args  Contains the arguments for rendering component.
     * @return array
     */
    public static function get_configuration( $type, $args = [] ) {
        if ( empty( $args ) || ! is_array( $args ) ) {
            return;
        }

        if ( $type === 'field' ) {
            $args['settings'] = $args['id'];
            unset( $args['id'] );
        }

        if ( in_array( $type, [ 'panel', 'section' ] ) ) {
            unset( $args['id'] );
        }

        return $args;
    }

    /**
     * Return the validated sort_order value.
     * 
     * @since 1.0.0
     *
     * @param  string  $order  The sort order value to be validate.
     * @return string
     */
    public static function get_sort_order( $order ) {
        $value = 'asc';
        $valid = [ 'asc', 'ASC', 'desc', 'DESC' ];
        if ( in_array( $order, $valid ) ) {
            $value = $order;
        }

        return $value;
    }

    /**
     * Return the validated post_status value.
     * 
     * @since 1.0.0
     *
     * @param  array  $status  The post status to be validate.
     * @return array
     */
    public static function get_post_status( $status ) {
        $value        = [ 'publish' ];
        $valid        = [ 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash', 'any' ];
        $valid_status = ( ! empty( $status ) && is_array( $status ) );
        if ( $valid_status ) {
            $intersected = array_values( array_unique( array_intersect( $status, $valid ) ) );
            $value       = ( ! empty( $intersected ) ? $intersected : $value );
        }

        return $value;
    } 
}