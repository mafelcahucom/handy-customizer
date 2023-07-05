<?php
namespace Handy\Modules\DatePicker\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Date Picker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
        return  'hacu-date-picker-'. $this->id;
    }

    /**
     * Return the imploded value.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function get_value() {
        $is_valid_value = ( ! empty( $this->value() ) && is_array( $this->value() ) );
        return ( $is_valid_value ? implode( ',', $this->value() ) : '' );
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
                        'class' => 'hacu-date-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->get_value()
                    ]
                ]);
            ?>
            
            <div class="hacu-date-picker__container" data-state="hidden" data-time="<?php echo ( $this->enable_time ? 'enabled' : 'disabled' ); ?>">
                <div class="hacu-col__left">
                    <input type="text" class="hacu-date-picker__picker" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-dates="<?php echo esc_attr( $this->get_value() ); ?>" data-mode="<?php echo esc_attr( $this->mode ); ?>" data-enable-time="<?php echo esc_attr( $this->enable_time ); ?>" />
                </div>
                <div class="hacu-col__right">
                    <button type="button" class="hacu-date-picker__toggle-btn hacu-btn-small" data-state="default" title="Open">
                        <?php echo Icon::get( 'calendar' ); ?>
                    </button>
                </div>
            </div>
        <?php
    }
}