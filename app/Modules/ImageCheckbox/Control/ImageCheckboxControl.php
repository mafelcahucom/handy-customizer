<?php
namespace Handy\Modules\ImageCheckbox\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Image Checkbox.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ImageCheckboxControl extends \WP_Customize_Control {

    /**
     * Holds the display.
     * 
     * @since 1.0.0
     * 
     * @var string
     */
    public $display;

    /**
     * Holds the width and height.
     * 
     * @since 1.0.0
     * 
     * @var array
     */
    public $size;

    /**
     * Holds the list of choices.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    public $choices;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-image-checkbox-'. $this->id;
    }

    /**
     * Return the value in array format.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function get_value() {
        return ( ! empty( $this->value() ) && is_array( $this->value() ) ? $this->value() : [] );
    }

    /**
     * Return the item state based on the current value.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The item value.
     * @return string
     */
    private function get_item_state( $value ) {
        return ( in_array( $value, $this->get_value() ) ? 'active' : 'default' );
    }

    /**
     * Render Image Checkbox Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-image-checkbox">
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
                        'class' => 'hacu-image-checkbox__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), array_keys( $this->choices ) )
                    ]
                ]);
            ?>

            <div class="hacu-image-checkbox__container" data-display="<?php echo esc_attr( $this->display ); ?>">
                <?php foreach ( $this->choices as $key => $value ): ?>
                    <div class="hacu-image-checkbox__item" title="<?php echo esc_attr( $value['title'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" data-state="<?php echo $this->get_item_state( $key ); ?>" style="width: <?php echo esc_attr( $this->size['width'] ); ?>; height: <?php echo esc_attr( $this->size['height'] ); ?>;">
                        <img class="hacu-image-checkbox__image" src="<?php echo esc_url( $value['image'] ); ?>"/>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}