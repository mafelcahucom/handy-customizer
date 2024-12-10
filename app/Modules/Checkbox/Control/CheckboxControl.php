<?php
/**
 * App > Modules > Checkbox > Control > Checkbox Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Checkbox\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `CheckboxControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class CheckboxControl extends \WP_Customize_Control {

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-checkbox-' . $this->id;
    }

    /**
     * Render Checkbox Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-checkbox">
            <div class="hacu-flex">
                <div class="hacu-col__left hacu-mr-5">
                    <input type="checkbox" class="hacu-checkbox__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
                </div>
                <div class="hacu-col__right">
                    <?php
                        echo Helper::get_control_title(array(
                            'class'       => 'hacu-ds-block',
                            'id'          => $this->prefix_id(),
                            'label'       => $this->label,
                            'description' => $this->description,
                        ));
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}
