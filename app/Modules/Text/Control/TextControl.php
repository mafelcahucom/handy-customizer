<?php
/**
 * App > Modules > Text > Control > Text Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Text\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `TextControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class TextControl extends \WP_Customize_Control {

    /**
     * Holds placeholder.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-text-' . $this->id;
    }

    /**
     * Render Text Control Content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-text">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));
            ?>

            <input type="text" class="hacu-text__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" <?php $this->link(); ?> />
        </div>
        <?php
    }
}
