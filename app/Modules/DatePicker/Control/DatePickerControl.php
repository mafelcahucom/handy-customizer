<?php
/**
 * App > Modules > Date Picker > Control > Date Picker Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\DatePicker\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `DatePickerControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class DatePickerControl extends \WP_Customize_Control {

    /**
     * Holds the placeholder.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds flag whether to enable time.
     *
     * @since 1.0.0
     *
     * @var boolean
     */
    public $enable_time;

    /**
     * Holds the mode whether single or range.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $mode;

    /**
     * Enqueue styles and scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue() {
        if ( ! wp_style_is( 'flatpickr', 'enqueued' ) ) {
            wp_register_style( 'flatpickr', Helper::get_resource_src( 'flatpickr/flatpickr.min.css' ), array(), '1.0.0', 'all' );
            wp_enqueue_style( 'flatpickr' );
        }

        if ( ! wp_script_is( 'flatpickr', 'enqueued' ) ) {
            wp_register_script( 'flatpickr', Helper::get_resource_src( 'flatpickr/flatpickr.min.js' ), array(), '1.0.0', true );
            wp_enqueue_script( 'flatpickr' );
        }
    }

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-date-picker-' . $this->id;
    }

    /**
     * Return the imploded value.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function get_value() {
        $is_valid_value = ! empty( $this->value() ) && is_array( $this->value() );
        return $is_valid_value ? implode( ',', $this->value() ) : '';
    }

    /**
     * Render Date Picker Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-date-picker">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));

                echo Helper::get_hidden_input(array(
                    'key_link'   => $this->get_link(),
                    'attributes' => array(
                        'class' => 'hacu-date-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->get_value(),
                    ),
                ));
            ?>
            
            <div class="hacu-date-picker__container" data-state="hidden" data-time="<?php echo ( $this->enable_time ? 'enabled' : 'disabled' ); ?>">
                <div class="hacu-col__left">
                    <input type="text" class="hacu-date-picker__picker" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-dates="<?php echo esc_attr( $this->get_value() ); ?>" data-mode="<?php echo esc_attr( $this->mode ); ?>" data-enable-time="<?php echo esc_attr( $this->enable_time ); ?>" />
                </div>
                <div class="hacu-col__right">
                    <button type="button" class="hacu-date-picker__toggle-btn hacu-btn-small" data-state="default" title="<?php echo __( 'Open', 'handy-customizer' ); ?>">
                        <?php echo Icon::get( 'calendar' ); ?>
                    </button>
                </div>
            </div>
        <?php
    }
}
