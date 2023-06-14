<?php
namespace Handy\Controls;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Checkbox.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
        return  'hacu-checkbox-'. $this->id;
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
                        // Label & Description.
                        echo Helper::get_control_title([
                            'class'       => 'hacu-ds-block',
                            'id'          => $this->prefix_id(),
                            'label'       => $this->label,
                            'description' => $this->description
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <?php
    }
}