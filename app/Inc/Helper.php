<?php
namespace Handy\Inc;

use Handy\Lib\Singleton;

defined( 'ABSPATH' ) || exit;

/**
 * Helper.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class Helper {

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
     * Logs data in debug.txt.
     * 
     * @since 1.0.0
     *
     * @param mixed  $log  Contains the data to be log.
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
     * Returns the base url of dist folder.
     *
     * @since 1.0.0
     * 
     * @param  string  $file  Target filename.
     * @return string
     */
    public static function get_asset_src( $file ) {
        return HACU_PLUGIN_URL . 'assets/dist/' . $file;
    }

    /**
     * Return the asset version.
     *
     * @since 1.0.0
     * 
     * @param  string  $file  Target filename.
     * @return string
     */
    public static function get_asset_version( $file ) {
        $version = '1.0.0';
        if ( ! empty( $file ) ) {
            $version = filemtime( HACU_PLUGIN_PATH . 'assets/dist/' . $file );
        }

        return $version;
    }

    /**
     * Checks if customizer can continue adding or register layouts and controls.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed for registering layout and controls.
     * @return boolean
     */
    public static function is_continue( $args ) {
        return ( is_customize_preview() && ! empty( $args ) && is_array( $args ) );
    }

    /**
     * Return the Fields classname based on the field name.
     * 
     * @since 1.0.0
     *
     * @param  string  $field  Contains the name of the field.
     * @return string
     */
    public static function get_field_classname( $field ) {
        if ( empty( $field ) ) {
            return '';
        }

        $exploded = array_map( 'ucfirst', explode( '-', $field ) );
        array_push( $exploded, 'Field' );

        return implode( '', $exploded );
    }

    /**
     * Return the control label and description component.
     *
     * @param  array  $args  Contains the arguments for creating control label.
     * $args = [
     *      'id'          => (string) The id of the control.
     *      'class'       => (string) The classname of control's label.
     *      'label'       => (string) The title of control's label.
     *      'description' => (string) The description of control's label.
     * ];
     * @return HTMLElement
     */
    public static function get_control_title( $args = [] ) {
        $no_isset = ( ! isset( $args['label'] ) && ! isset( $args['description'] ) );
        $is_empty = ( empty( $args['label'] ) && empty( $args['description'] ) );
        if ( $no_isset || $is_empty ) {
            return;
        }

        $id    = ( isset( $args['id'] ) ? $args['id'] : '' );
        $class = ( isset( $args['class'] ) ? $args['class'] : '' );

        ob_start();
        ?>
        <label class="<?php echo esc_attr( $class ); ?>" for="<?php echo $id; ?>">
            <?php if ( ! empty( $args['label'] ) ): ?>
                <span class="customize-control-title">
                    <?php echo esc_html( $args['label'] ); ?>
                </span>
            <?php endif; ?>
            <?php if ( ! empty( $args['description'] ) ): ?>
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
     * @param  array  $args  Contains the arguments for creating hidden input.
     * $args = [
     *      'attributes' => (array)  Contains the attributes for hidden input.
     *      'key_link'   => (string) Contains the data link parameter.
     * ];
     * @return HTMLElement
     */
    public static function get_hidden_input( $args = [] ) {
        if ( empty( $args ) || ! isset( $args['attributes'] ) || ! isset( $args['key_link'] ) ) {
            return;
        }

        $output = '<input type="hidden" ';
        foreach ( $args['attributes'] as $key => $value ) {
            $value   = esc_attr( $value );
            $output .= $key .'="'. $value .'"';
        }

        $output .= $args['key_link'] . " />";

        return $output;
    }
}