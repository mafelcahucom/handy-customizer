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
     * Return the control label and description component.
     *
     * @param  array  $args  Contains the arguments for render control label.
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
            return '';
        }

        $id    = ( isset( $args['id'] ) ? $args['id'] : '' );
        $class = ( isset( $args['class'] ) ? $args['class'] : '' );

        ob_start();
        ?>
        <label class="<?php echo esc_attr( $id ); ?>" for="<?php echo $id; ?>">
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
}