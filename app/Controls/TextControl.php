<?php
namespace Handy\Controls;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Controls > Text.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
     * Render Text Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        Helper::log( 'CONSTRUCT TEXTCONTROL' );
        ?>
        <div class="hacu-text">
            <?php
                echo Helper::get_control_title([
                    'id'          => 'hacu-text-'. $this->id,
                    'class'       => 'hacu-text__label',
                    'label'       => $this->label,
                    'description' => $this->description
                ]);
            ?>

            <input 
                type="text" 
                class="hacu-text__input" 
                id="hacu-text-<?php echo esc_attr( $this->id ); ?>"
                name="<?php echo esc_attr( $this->id ); ?>"
                value="<?php echo esc_attr( $this->value() ); ?>"
                placeholder="<?php echo esc_attr( $this->placeholder ); ?>"
            />
        </div>
        <?php
    }
}