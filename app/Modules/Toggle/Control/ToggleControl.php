<?php
/**
 * App > Modules > Toggle > Control > Toggle Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Toggle\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `ToggleControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class ToggleControl extends \WP_Customize_Control {

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-toggle-' . $this->id;
    }

    /**
     * Render Toggle Control Content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-toggle">
            <div class="hacu-flex">
                <div class="hacu-col__left hacu-width-full">
                    <?php
                        echo Helper::get_control_title(array(
                            'class'       => 'hacu-ds-block',
                            'id'          => $this->prefix_id(),
                            'label'       => $this->label,
                            'description' => $this->description,
                        ));
                    ?>
                </div>
                <div class="hacu-col__right hacu-pl-20">
                    <input type="checkbox" class="hacu-toggle-checkbox__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), true ); ?> />
                    <label class="hacu-toggle-checkbox" for="<?php echo esc_attr( $this->prefix_id() ); ?>"></label>
                </div>
            </div>
        </div>
        <?php
    }
}
