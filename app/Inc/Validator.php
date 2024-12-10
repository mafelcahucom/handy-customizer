<?php
/**
 * App > Inc > Validator.
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
 * The `Validator` class contains the all control
 * or field data validation methods.
 *
 * @since 1.0.0
 */
final class Validator {

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
     * Checks whether the value is empty.
     *
     * @since 1.0.0
     *
     * @param  mixed $value Contains the value to be check.
     * @return boolean
     */
    public static function is_empty( $value ) {
        return strlen( $value ) === 0;
    }

    /**
     * Checks whether the value is a valid size based on defined valid units.
     *
     * @since 1.0.0
     *
     * @param  string $value Contains the value size to be check.
     * @param  array  $units Contains the list of valid units.
     * @return boolean
     */
    public static function is_valid_size( $value = '', $units = array() ) {
        if ( ! is_string( $value ) || empty( $units ) || ! is_array( $units ) ) {
            return false;
        }

        $splited = preg_split( '/(?<=[0-9])(?=[a-z%]+)/i', $value );
        if ( count( $splited ) === 2 ) {
            // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
            if ( is_numeric( $splited[0] ) && in_array( $splited[1], $units ) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check whether the color string contains a valid hexadecimal color.
     *
     * @since 1.0.0
     *
     * @param  string $color Contains the string color to be check.
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
     * Check whether the color string contains a valid HEXA color.
     *
     * @since 1.0.0
     *
     * @param  string $color Contains the string color to be check.
     * @return boolean
     */
    public static function is_hexa( $color = '' ) {
        $is_valid = false;
        if ( is_string( $color ) ) {
            $is_valid_hex  = (bool) preg_match( '/^#([a-f0-9]{6})$/i', $color );
            $is_valid_hexa = (bool) preg_match( '/^#([a-f0-9]{8})$/i', $color );
            $is_valid      = ( $is_valid_hex || $is_valid_hexa ? true : false );
        }

        return $is_valid;
    }

    /**
     * Check whether the color string contains a valid HSLA color.
     *
     * @since 1.0.0
     *
     * @param  string $color Contains the string color to be check.
     * @return boolean
     */
    public static function is_hsla( $color = '' ) {
        $is_valid = false;
        if ( is_string( $color ) ) {
            $is_valid_hsl  = (bool) preg_match( '/^hsl\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)\)/', $color );
            $is_valid_hsla = (bool) preg_match( '/^(hsla\(\s*((\d{1,2}|[1-2]\d{2}|3([0-5]\d|60)))\s*,\s*((\d{1,2}|100)\s*%)\s*,\s*((\d{1,2}|100)\s*%)(,\s*(0\.\d+|1))\))$/', $color );
            $is_valid      = $is_valid_hsl || $is_valid_hsla ? true : false;
        }

        return $is_valid;
    }

    /**
     * Check whether the color string contains a valid RGBA color.
     *
     * @since 1.0.0
     *
     * @param  string $color Contains the string color to be check.
     * @return boolean
     */
    public static function is_rgba( $color = '' ) {
        $is_valid = false;
        if ( is_string( $color ) ) {
            $is_valid_rgb  = (bool) preg_match( '/^rgb\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*)/', $color );
            $is_valid_rgba = (bool) preg_match( '/^(rgba\(((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*,\s*){2}((\d{1,2}|1\d\d|2([0-4]\d|5[0-5]))\s*(,\s*(0\.\d+|1))\)))$/', $color );
            $is_valid      = $is_valid_rgb || $is_valid_rgba ? true : false;
        }

        return $is_valid;
    }

    /**
     * Check whether the date string contains a valid date based on defined format.
     *
     * @since 1.0.0
     *
     * @param  string $date   Contains the string date to be check.
     * @param  string $format Contains the date format used as referrence.
     * @return boolean
     */
    public static function is_valid_date( $date, $format ) {
        if ( empty( $date ) || empty( $format ) ) {
            return false;
        }

        $created_date = \DateTime::createFromFormat( $format, $date );
        return $created_date && $created_date->format( $format ) === $date;
    }

    /**
     * Checks whether the callback is invalid object or string type.
     *
     * @since 1.0.0
     *
     * @param  mixed $callback Contains the callback to be check.
     * @return boolean
     */
    public static function is_invalid_callback( $callback ) {
        $type            = gettype( $callback );
        $is_type_invalid = ! in_array( $type, array( 'string', 'object' ), true );
        $is_empty        = $type === 'string' && empty( $callback );
        $is_not_found    = $type === 'string' && ! function_exists( $callback );

        return $is_type_invalid || $is_empty || $is_not_found;
    }

    /**
     * Return the validated arguments for panel, section and controls.
     *
     * @since 1.0.0
     *
     * @param  array $schema Contains the argument schema or rules.
     * @param  array $args   Contains the arguments to be validate.
     * @return array|boolean
     */
    public static function get_validated_argument( $schema, $args ) {
        if ( empty( $schema ) || ! is_array( $schema ) || empty( $args ) || ! is_array( $args ) ) {
            return false;
        }

        $validated = array();
        $has_invalid = false;
        foreach ( $schema as $key => $value ) {
            $is_required = $value['required'];
            if ( isset( $args[ $key ] ) ) {
                $is_type_invalid = false;
                if ( $value['type'] !== 'mixed' && $value['type'] !== 'number' && $value['type'] !== gettype( $args[ $key ] ) ) {
                    $is_type_invalid = true;
                }

                if ( $value['type'] === 'number' ) {
                    if ( ! is_numeric( $args[ $key ] ) ) {
                        $is_type_invalid = true;
                    }
                }

                if ( $is_type_invalid && $is_required ) {
                    $has_invalid = true;
                    break;
                }

                if ( ! $is_type_invalid ) {
                    $is_empty = false;
                    if ( in_array( $value['type'], array( 'string', 'number', 'integer', 'double' ), true ) ) {
                        if ( strlen( $args[ $key ] ) === 0 ) {
                            $is_empty = true;
                        }
                    } elseif ( $value['type'] === 'array' ) {
                        if ( empty( $args[ $key ] ) ) {
                            $is_empty = true;
                        }
                    }

                    if ( $is_empty && $is_required ) {
                        $has_invalid = true;
                        break;
                    }

                    if ( ! $is_empty ) {
                        $validated[ $key ] = $args[ $key ];
                    }
                }
            } elseif ( $is_required ) {
                    $has_invalid = true;
                    break;
            }
        }

        if ( isset( $validated['active_callback'] ) ) {
            if ( self::is_invalid_callback( $validated['active_callback'] ) ) {
                unset( $validated['active_callback'] );
            }
        }

        if ( isset( $validated['sanitize_callback'] ) ) {
            if ( self::is_invalid_callback( $validated['sanitize_callback'] ) ) {
                unset( $validated['sanitize_callback'] );
            }
        }

        return $has_invalid || empty( $validated ) ? false : $validated;
    }

    /**
     * Return the panel, section and field configurations.
     *
     * @since 1.0.0
     *
     * @param  string $type Contains the type of component [panel, section, field].
     * @param  array  $args Contains the necessary arguments for rendering component.
     * @return array
     */
    public static function get_configuration( $type, $args = array() ) {
        if ( empty( $args ) || ! is_array( $args ) ) {
            return;
        }

        if ( $type === 'field' ) {
            $args['settings'] = $args['id'];
            unset( $args['id'] );
        }

        if ( in_array( $type, array( 'panel', 'section' ), true ) ) {
            unset( $args['id'] );
        }

        return $args;
    }

    /**
     * Return the validated sort_order value.
     *
     * @since 1.0.0
     *
     * @param  string $order Contains the sort order value to be validate.
     * @return string
     */
    public static function get_sort_order( $order ) {
        $value = 'asc';
        $valid = array( 'asc', 'ASC', 'desc', 'DESC' );
        if ( in_array( $order, $valid, true ) ) {
            $value = $order;
        }

        return $value;
    }

    /**
     * Return the validated post_status value.
     *
     * @since 1.0.0
     *
     * @param  array $status Contains the post status to be validate.
     * @return array
     */
    public static function get_post_status( $status ) {
        $value        = array( 'publish' );
        $valid        = array( 'publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash', 'any' );
        $valid_status = ! empty( $status ) && is_array( $status );
        if ( $valid_status ) {
            $intersected = array_values( array_unique( array_intersect( $status, $valid ) ) );
            $value       = ! empty( $intersected ) ? $intersected : $value;
        }

        return $value;
    }
}
