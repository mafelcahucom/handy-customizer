<?php
/**
 * App > Modules > Image Radio > Control > Image Radio Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ImageRadio\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `ImageRadioControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class ImageRadioControl extends \WP_Customize_Control {

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
        return 'hacu-image-radio-' . $this->id;
    }

    /**
     * Return the item state based on the current value.
     *
     * @since 1.0.0
     *
     * @param  string $value Contains the item value.
     * @return string
     */
    private function get_item_state( $value ) {
        return $this->value() == $value ? 'active' : 'default';
    }

    /**
     * Render Image Radio Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-image-radio">
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
                        'class' => 'hacu-image-radio__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value(),
                    ),
                ));
            ?>

            <div class="hacu-image-radio__container" data-display="<?php echo esc_attr( $this->display ); ?>">
                <?php foreach ( $this->choices as $key => $value ) : ?>
                    <div class="hacu-image-radio__item" title="<?php echo esc_attr( $value['title'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" data-state="<?php echo $this->get_item_state( $key ); ?>" style="width: <?php echo esc_attr( $this->size['width'] ); ?>; height: <?php echo esc_attr( $this->size['height'] ); ?>;">
                        <img class="hacu-image-radio__image" src="<?php echo esc_url( $value['image'] ); ?>"/>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
