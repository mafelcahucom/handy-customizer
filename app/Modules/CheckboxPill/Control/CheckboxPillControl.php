<?php
/**
 * App > Modules > Checkbox Pill > Control > Checkbox Pill Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\CheckboxPill\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `CheckboxPillControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class CheckboxPillControl extends \WP_Customize_Control {

    /**
     * Holds the list of choices.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $choices;

    /**
     * Holds the shape.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $shape;

    /**
     * Holds the display.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $display;

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-checkbox-pill-' . $this->id;
    }

    /**
     * Return the value in array format.
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function get_value() {
        return ! empty( $this->value() ) && is_array( $this->value() ) ? $this->value() : array();
    }

    /**
     * Return the item button state based on the current value and item value.
     *
     * @since 1.0.0
     *
     * @param  string $value Contains the item button value.
     * @return string
     */
    private function get_item_state( $value ) {
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        return in_array( $value, $this->get_value() ) ? 'active' : 'default';
    }

    /**
     * Render Checkbox Pill Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-checkbox-pill">
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
                        'class' => 'hacu-checkbox-pill__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), array_keys( $this->choices ) ),
                    ),
                ));
            ?>

            <div class="hacu-checkbox-pill__container" data-display="<?php echo esc_attr( $this->display ); ?>">
                <?php foreach ( $this->choices as $key => $value ) : ?>
                    <button type="button" class="hacu-checkbox-pill__item-btn" data-value="<?php echo esc_attr( $key ); ?>" data-shape="<?php echo esc_attr( $this->shape ); ?>" data-state="<?php echo $this->get_item_state( $key ); ?>">
                        <?php echo Icon::get( 'check' ); ?>
                        <?php echo esc_html( $value ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
