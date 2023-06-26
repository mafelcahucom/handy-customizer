<?php
namespace Handy\Modules\CheckboxPill\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Checkbox Pill.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
        return  'hacu-checkbox-pill-'. $this->id;
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
     * Return the item button state based on the current value and item value.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The item button value.
     * @return string
     */
    private function get_item_state( $value ) {
        return ( in_array( $value, $this->get_value() ) ? 'active' : 'default' );
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
                        'class' => 'hacu-checkbox-pill__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), array_keys( $this->choices ) )
                    ]
                ]);
            ?>

            <div class="hacu-checkbox-pill__container" data-display="<?php echo esc_attr( $this->display ); ?>">
                <?php foreach ( $this->choices as $key => $value ): ?>
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