<?php
namespace Handy\Controls;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Controls > Checkbox Multiple.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CheckboxMultipleControl extends \WP_Customize_Control {

    /**
     * Holds placeholder.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

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
        return  'hacu-checkbox-multiple-'. $this->id;
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
     * Render Select Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-checkbox-multiple">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);

                // Hidden Input.
                echo Helper::get_hidden_input([
                    'key_link'   => $this->get_link(),
                    'attributes' => [
                        'class' => 'hacu-checkbox-multiple__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), $this->choices )
                    ]
                ]);
            ?>

            <?php foreach ( $this->choices as $key => $value ): ?>
                <div class="hacu-mb-5">
                    <label>
                        <input 
                            type="checkbox"
                            class="hacu-checkbox-multiple__box"
                            id="<?php echo esc_attr( $this->prefix_id() .'-'. $key ); ?>"
                            value="<?php echo esc_attr( $key ); ?>"
                            <?php checked( in_array( $key, $this->get_value() ), 1 ); ?>
                        />
                        <?php echo esc_html( $value ); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }
}