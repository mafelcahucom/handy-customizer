<?php
namespace Handy\Modules\ButtonSet\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Button Set.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ButtonSetControl extends \WP_Customize_Control {

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
        return  'hacu-button-set-'. $this->id;
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
        return ( $this->value() == $value ? 'active' : 'default' );
    }

    /**
     * Render Button Set Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-button-set">
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
                        'class' => 'hacu-button-set__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>

            <div class="hacu-button-set__container" role="group" aria-label="<?php echo esc_attr( $this->label ); ?>">
                <?php foreach ( $this->choices as $key => $value ): ?>
                    <button class="hacu-button-set__item-btn" data-value="<?php echo esc_attr( $key ); ?>" data-state="<?php echo $this->get_item_state( $key ); ?>">
                        <?php echo esc_html( $value ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}