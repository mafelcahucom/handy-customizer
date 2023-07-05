<?php
namespace Handy\Modules\TimePicker\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Time Picker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
     * Holds the format whether civilian or military.
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
     */
    public function enqueue() {
        if ( ! wp_style_is( 'flatpickr-css', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'flatpickr/flatpickr.min.css' );
            $version = Helper::get_asset_version( 'flatpickr/flatpickr.min.css' );

            wp_enqueue_style( 'flatpickr-css', $source, [], $version );
        }

        if ( ! wp_script_is( 'flatpickr-js', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'flatpickr/flatpickr.min.js' );
            $version = Helper::get_asset_version( 'flatpickr/flatpickr.min.js' );

            wp_enqueue_script( 'flatpickr-js', $source, [], $version, true );
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
        return  'hacu-time-picker-'. $this->id;
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
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);

                // Input Hidden
                echo Helper::get_hidden_input([
                    'key_link'   => $this->get_link(),
                    'attributes' => [
                        'class' => 'hacu-time-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>
            
            <div class="hacu-time-picker__container" data-state="hidden">
                <div class="hacu-col__left">
                    <input
                        type="text"
                        class="hacu-time-picker__picker"
                        placeholder="<?php echo esc_attr( $this->placeholder ); ?>"
                        data-time="<?php echo esc_attr( $this->value() ); ?>"
                        data-format="<?php echo esc_attr( $this->format ); ?>"
                    />
                </div>
                <div class="hacu-col__right">
                    <button type="button" class="hacu-time-picker__toggle-btn hacu-btn-small" data-state="default" title="Open">
                        <?php echo Icon::get( 'clock' ); ?>
                    </button>
                </div>
            </div>
        <?php
    }
}