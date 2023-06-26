<?php
namespace Handy\Modules\Switches\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Switches.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class SwitchesControl extends \WP_Customize_Control {

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
        return  'hacu-switches-'. $this->id;
    }

    /**
     * Render Switch Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-switches">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);
            ?>

            <div class="hacu-flex hacu-flex-ai-c">
                <div class="hacu-col__left">
                    <input type="checkbox" class="hacu-toggle-checkbox__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), true ); ?> />
                    <label class="hacu-toggle-checkbox" for="<?php echo esc_attr( $this->prefix_id() ); ?>">
                        <span class="hacu-switches__choice" data-type="on">
                            <?php echo esc_html( $this->choices['on'] ); ?>
                        </span>
                        <span class="hacu-switches__choice" data-type="off">
                            <?php echo esc_html( $this->choices['off'] ); ?>
                        </span>
                    </label>
                </div>
            </div>
        </div>
        <?php
    }
}