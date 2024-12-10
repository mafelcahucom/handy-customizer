<?php
/**
 * App > Modules > Time Picker > Control > Time Picker Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\TimePicker\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `TimePickerControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class TimePickerControl extends \WP_Customize_Control {

    /**
     * Holds the placeholder.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds the format whether civilian or military
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $format;

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
        return 'hacu-time-picker-' . $this->id;
    }

    /**
     * Render Time Picker Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-time-picker">
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
                        'class' => 'hacu-time-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value(),
                    ),
                ));
            ?>
            
            <div class="hacu-time-picker__container" data-state="hidden">
                <div class="hacu-col__left">
                    <input type="text" class="hacu-time-picker__picker" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-time="<?php echo esc_attr( $this->value() ); ?>" data-format="<?php echo esc_attr( $this->format ); ?>">
                </div>
                <div class="hacu-col__right">
                    <button type="button" class="hacu-time-picker__toggle-btn hacu-btn-small" data-state="default" title="<?php echo __( 'Open', 'handy-customizer' ); ?>">
                        <?php echo Icon::get( 'clock' ); ?>
                    </button>
                </div>
            </div>
        <?php
    }
}
