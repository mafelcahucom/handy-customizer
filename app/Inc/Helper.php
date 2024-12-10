<?php
/**
 * App > Inc > Helper.
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
 * The `Helper` class contains all the helper methods
 * solely for the plugin.
 *
 * @since 1.0.0
 */
final class Helper {

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
     * Logs data in debug.txt.
     *
     * @since 1.0.0
     *
     * @param  mixed $log Contains the data to be log.
     * @return void
     */
    public static function log( $log ) {
        if ( true === WP_DEBUG ) {
            if ( is_array( $log ) || is_object( $log ) ) {
                error_log( print_r( $log, true ) );
            } else {
                error_log( $log );
            }
        }
    }

    /**
     * Return the base url of public asset folder.
     *
     * @since 1.0.0
     *
     * @param  string $file_path Contains the relative path with filename.
     * @return string
     */
    public static function get_public_src( $file_path = '' ) {
        return HACU_PLUGIN_URL . 'public/' . $file_path;
    }

    /**
     * Return the base url of resources asset folder.
     *
     * @since 1.0.0
     *
     * @param  string $file_path Contains the relative path with filename.
     * @return string
     */
    public static function get_resource_src( $file_path = '' ) {
        return HACU_PLUGIN_URL . 'resources/' . $file_path;
    }

    /**
     * Checks if customizer can continue adding or register layouts and controls.
     *
     * @since 1.0.0
     *
     * @param  array $args Contains the necessary arguments for registering layout and controls.
     * @return boolean
     */
    public static function is_continue( $args ) {
        return is_customize_preview() && ! empty( $args ) && is_array( $args );
    }

    /**
     * Return the module directory, field and control class name based on the field name.
     *
     * @since 1.0.0
     *
     * @param  string $field Contains the name of the field.
     * @return array
     */
    public static function get_module( $field ) {
        if ( empty( $field ) ) {
            return;
        }

        $exploded = array_map( 'ucfirst', explode( '-', $field ) );
        $imploded = implode( '', $exploded );

        return array(
            'dir'     => $imploded,
            'field'   => $imploded . 'Field',
            'control' => $imploded . 'Control',
        );
    }

    /**
     * Return the exploded or array of an imploded value validated by choices.
     *
     * @since 1.0.0
     *
     * @param  string $value   Contains the imploded value by ",".
     * @param  array  $choices Contains the list of choices.
     * @return array
     */
    public static function get_exploded_value( $value, $choices ) {
        if ( strlen( $value ) === 0 || empty( $choices ) || ! is_array( $choices ) ) {
            return array();
        }

        $intersected = self::get_intersected( explode( ',', $value ), $choices );

        return $intersected;
    }

    /**
     * Return the imploded or string of an exploded value validated by choices.
     *
     * @since 1.0.0
     *
     * @param  array $value   Contains the imploded value by ",".
     * @param  array $choices Contains the list of choices.
     * @return mixed
     */
    public static function get_imploded_value( $value, $choices ) {
        $invalid_value   = ( empty( $value ) || ! is_array( $value ) );
        $invalid_choices = ( empty( $choices ) || ! is_array( $choices ) );
        if ( $invalid_value || $invalid_choices ) {
            return '';
        }

        $intersected = self::get_intersected( $value, $choices );

        return implode( ',', $intersected );
    }

    /**
     * Return the unique intersected values based on two arrays.
     *
     * @since 1.0.0
     *
     * @param  array $array1 Contains the array with master values to check.
     * @param  array $array2 Contains the array to compare value againts.
     * @return array
     */
    public static function get_intersected( $array1, $array2 ) {
        $invalid_array1 = ( empty( $array1 ) || ! is_array( $array1 ) );
        $invalid_array2 = ( empty( $array2 ) || ! is_array( $array2 ) );
        if ( $invalid_array1 || $invalid_array2 ) {
            return array();
        }

        $intersected = array_values( array_unique( array_intersect( $array1, $array2 ) ) );

        return ! empty( $intersected ) ? $intersected : array();
    }

    /**
     * Return the attachment file meta data filename and extension.
     *
     * @since 1.0.0
     *
     * @param  integer $attachment_id Contains the attachment id of file.
     * @return array
     */
    public static function get_file_meta( $attachment_id ) {
        $data = array();
        if ( ! empty( $attachment_id ) ) {
            $file = get_attached_file( $attachment_id );
            if ( $file ) {
                $data['file']      = $file;
                $data['filename']  = basename( $file );
                $data['raw_name']  = pathinfo( $data['filename'], PATHINFO_FILENAME );
                $data['extension'] = pathinfo( $data['filename'], PATHINFO_EXTENSION );
            }
        }

        return $data;
    }

    /**
     * Return the control label and description component.
     *
     * @param  array $args Contains the necessary arguments for creating control label.
     * $args = [
     *      'id'          => (string) Contains the id of the control.
     *      'class'       => (string) Contains the classname of control's label.
     *      'label'       => (string) Contains the title of control's label.
     *      'description' => (string) Contains the description of control's label.
     * ];
     * @return string
     */
    public static function get_control_title( $args = array() ) {
        $no_isset = ! isset( $args['label'] ) && ! isset( $args['description'] );
        $is_empty = empty( $args['label'] ) && empty( $args['description'] );
        if ( $no_isset || $is_empty ) {
            return;
        }

        $id    = isset( $args['id'] ) ? $args['id'] : '';
        $class = isset( $args['class'] ) ? $args['class'] : '';

        ob_start();
        ?>
        <label class="<?php echo esc_attr( $class ); ?>" for="<?php echo $id; ?>">
            <?php if ( ! empty( $args['label'] ) ) : ?>
                <span class="customize-control-title">
                    <?php echo esc_html( $args['label'] ); ?>
                </span>
            <?php endif; ?>
            <?php if ( ! empty( $args['description'] ) ) : ?>
                <span class="description customize-control-description">
                    <?php echo esc_html( $args['description'] ); ?>
                </span>
            <?php endif; ?>
        </label>
        <?php
        return ob_get_clean();
    }

    /**
     * Return the hidden input for control.
     *
     * @since 1.0.0
     *
     * @param  array $args Contains the necessary arguments for creating hidden input.
     * $args = [
     *      'attributes' => (array)  Contains the attributes for hidden input.
     *      'key_link'   => (string) Contains the data link parameter.
     * ];
     * @return string
     */
    public static function get_hidden_input( $args = array() ) {
        if ( empty( $args ) || ! isset( $args['attributes'] ) || ! isset( $args['key_link'] ) ) {
            return;
        }

        $output = '<input type="hidden" ';
        foreach ( $args['attributes'] as $key => $value ) {
            $value   = esc_attr( $value );
            $output .= $key . '="' . $value . '"';
        }

        $output .= $args['key_link'] . ' />';

        return $output;
    }

    /**
     * Unset a certain keys and return updated array value.
     *
     * @since 1.0.0
     *
     * @param  array $data Contains the array to be modify.
     * @param  array $keys Contains the list of array keys to be unset.
     * @return array
     */
    public static function unset_keys( $data, $keys ) {
        if ( ! empty( $data ) && is_array( $data ) ) {
            foreach ( $keys as $key ) {
                if ( isset( $data[ $key ] ) ) {
                    unset( $data[ $key ] );
                }
            }
        }

        return $data;
    }

    /**
     * Remove the empty value of an array and return modified array.
     *
     * @since 1.0.0
     *
     * @param  array $data Contains the array to be modify.
     * @return array
     */
    public static function array_remove_empty( $data = array() ) {
        if ( is_array( $data ) ) {
            return array_filter( $data, function( $value ) {
                return ! empty( $value ) || $value == 0;
            });
        }

        return array();
    }
}
