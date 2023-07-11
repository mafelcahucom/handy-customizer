<?php
namespace Handy\Modules\ColorPicker\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Color Picker.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
     */
    public function enqueue() {
        if ( ! wp_style_is( 'pickr-css', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'pickr/pickr.min.css' );
            $version = Helper::get_asset_version( 'pickr/pickr.min.css' );

            wp_enqueue_style( 'pickr-css', $source, [], $version );
        }

        if ( ! wp_script_is( 'pickr-js', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'pickr/pickr.min.js' );
            $version = Helper::get_asset_version( 'pickr/pickr.min.js' );

            wp_enqueue_script( 'pickr-js', $source, [], $version, true );
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
        return  'hacu-color-picker-'. $this->id;
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
                // Input Hidden.
                echo Helper::get_hidden_input([
                    'key_link'   => $this->get_link(),
                    'attributes' => [
                        'class' => 'hacu-color-picker__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]); 
            ?>
            
            <div class="hacu-flex">
                <div class="hacu-col__left hacu-width-full hacu-pr-10">
                    <?php
                        // Label & Description.
                        echo Helper::get_control_title([
                            'class'       => 'hacu-ds-block',
                            'id'          => $this->prefix_id(),
                            'label'       => $this->label,
                            'description' => $this->description
                        ]);
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