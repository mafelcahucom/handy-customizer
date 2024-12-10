<?php
/**
 * App > Modules > Color Picker > Control > Color Picker Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ColorPicker\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `ColorPickerControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class ColorPickerControl extends \WP_Customize_Control {

    /**
     * Holds the color format.
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
        if ( ! wp_style_is( 'pickr', 'enqueued' ) ) {
            wp_register_style( 'pickr', Helper::get_resource_src( 'pickr/pickr.min.css' ), array(), '1.0.0', 'all' );
            wp_enqueue_style( 'pickr' );
        }

        if ( ! wp_script_is( 'pickr', 'enqueued' ) ) {
            wp_register_script( 'pickr', Helper::get_resource_src( 'pickr/pickr.min.js' ), array(), '1.0.0', true );
            wp_enqueue_script( 'pickr' );
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
        return 'hacu-color-picker-' . $this->id;
    }

    /**
     * Render Color Picker Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-color-picker">
            <?php
                echo Helper::get_hidden_input(array(
                    'key_link'   => $this->get_link(),
                    'attributes' => array(
                        'class' => 'hacu-color-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value(),
                    ),
                ));
            ?>
            
            <div class="hacu-flex">
                <div class="hacu-col__left hacu-width-full hacu-pr-10">
                    <?php
                        echo Helper::get_control_title(array(
                            'class'       => 'hacu-ds-block',
                            'id'          => $this->prefix_id(),
                            'label'       => $this->label,
                            'description' => $this->description,
                        ));
                    ?>
                </div>
                <div class="hacu-col__right">
                    <div class="hacu-color-picker__picker" data-value="<?php echo esc_attr( $this->value() ); ?>" data-format="<?php echo esc_attr( $this->format ); ?>">
                        <div class="hacu-color-picker__picker__preview" style="background-color: <?php echo esc_attr( $this->value() ); ?>"></div>
                    </div>
                </div>
            </div>
            <div class="hacu-color-picker__container"></div>
        </div>
        <?php
    }
}
