<?php
namespace Handy\Modules\Radio\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Radio.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class RadioControl extends \WP_Customize_Control {

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
        return  'hacu-radio-'. $this->id;
    }

    /**
     * Render Radio Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-radio">
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
                        'class' => 'hacu-radio__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>

            <?php foreach ( $this->choices as $key => $value ): ?>
                <div class="hacu-mb-5">
                    <label>
                        <input type="radio" class="hacu-radio__box" id="<?php echo esc_attr( $this->prefix_id() .'-'. $key ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( ( $this->value() === $key ), true ); ?> />
                        <?php echo esc_html( $value ); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}