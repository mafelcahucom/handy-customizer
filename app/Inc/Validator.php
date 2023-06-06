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
                if ( $value['type'] !== 'mixed' && $value['type'] !== gettype( $args[ $key ] ) ) {
                    $is_type_invalid = true;
                }

                // If invalid type and required exit.
                if ( $is_type_invalid && $is_required ) {
                    $has_invalid = true;
                    break;
                }

                if ( ! $is_type_invalid ) {
                    // Check if empty.
                    $is_empty = false;
                    if ( in_array( $value['type'], [ 'string', 'integer' ] ) ) {
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

        // Set "active_callback" if isset.
        if ( isset( $validated['active_callback'] ) ) {
            $callback_type            = gettype( $validated['active_callback'] );
            $is_callback_type_invalid = ( ! in_array( $callback_type, [ 'string', 'object' ] ) );
            $is_callback_empty        = ( $callback_type === 'string' && empty( $validated['active_callback'] ) );
            if ( $is_callback_type_invalid || $is_callback_empty ) {
                unset( $validated['active_callback'] );
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
}