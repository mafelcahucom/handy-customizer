<?php
/**
 * App > Modules > Tagging Select > Control > Tagging Select Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\TaggingSelect\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `TaggingSelectControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class TaggingSelectControl extends \WP_Customize_Control {

    /**
     * Holds the placeholder.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds the maximum tag items.
     *
     * @since 1.0.0
     *
     * @var integer
     */
    public $maximum;

    /**
     * Holds the list of choices.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $choices;

    /**
     * Enqueue styles and scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue() {
        if ( ! wp_style_is( 'selectize', 'enqueued' ) ) {
            wp_register_style( 'selectize', Helper::get_resource_src( 'selectize/selectize.min.css' ), array(), '1.0.0', 'all' );
            wp_enqueue_style( 'selectize' );
        }

        if ( ! wp_script_is( 'selectize', 'enqueued' ) ) {
            wp_register_script( 'selectize', Helper::get_resource_src( 'selectize/selectize.min.js' ), array(), '1.0.0', true );
            wp_enqueue_script( 'selectize' );
        }
    }

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-tagging-select-' . $this->id;
    }

    /**
     * Return the imploded value.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function get_value() {
        $is_valid_value = ! empty( $this->value() ) && is_array( $this->value() );
        return $is_valid_value ? implode( ',', $this->value() ) : '';
    }

    /**
     * Return the selected attribute if option's value is found in current value.
     *
     * @since 1.0.0
     *
     * @param  string $value Contains the option's value
     * @return string
     */
    private function selected( $value ) {
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        $is_valid_value = ! empty( $this->value() ) && is_array( $this->value() ) && in_array( $value, $this->value() );
        return $is_valid_value ? 'selected' : '';
    }

    /**
     * Render Tagging Select Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-tagging-select">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));

                echo Helper::get_hidden_input(array(
                    'key_link'   => $this->get_link(),
                    'attributes' => array(
                        'class' => 'hacu-tagging-select__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->get_value(),
                    ),
                ));
            ?>

            <?php if ( ! empty( $this->choices ) ) : ?>
                <select class="hacu-tagging-select__selectize" value="<?php echo esc_attr( $this->get_value() ); ?>" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-maximum="<?php echo esc_attr( $this->maximum ); ?>" multiple>
                    <?php foreach ( $this->choices as $key => $value ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo $this->selected( $key ); ?>>
                            <?php echo esc_html( $value ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <?php
    }
}
