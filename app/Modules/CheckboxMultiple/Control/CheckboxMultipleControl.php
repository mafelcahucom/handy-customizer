<?php
/**
 * App > Modules > Checkbox Multiple > Control > Checkbox Multiple Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\CheckboxMultiple\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `CheckboxMultipleControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class CheckboxMultipleControl extends \WP_Customize_Control {

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
        return 'hacu-checkbox-multiple-' . $this->id;
    }

    /**
     * Return the value in array format.
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function get_value() {
        return ( ! empty( $this->value() ) && is_array( $this->value() ) ? $this->value() : array() );
    }

    /**
     * Render Checkbox Multiple Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-checkbox-multiple">
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
                        'class' => 'hacu-checkbox-multiple__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), array_keys( $this->choices ) ),
                    ),
                ));
            ?>

            <?php foreach ( $this->choices as $key => $value ) : ?>
                <div class="hacu-mb-5">
                    <label>
                        <input type="checkbox" class="hacu-checkbox-multiple__box" id="<?php echo esc_attr( $this->prefix_id() . '-' . $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, $this->get_value() ), 1 ); // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict ?> />
                        <?php echo esc_html( $value ); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}
