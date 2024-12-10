<?php
/**
 * App > Modules > Tagging > Control > Tagging Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Tagging\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `TaggingControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class TaggingControl extends \WP_Customize_Control {

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
        return 'hacu-tagging-' . $this->id;
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
     * Render Tagging Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-tagging">
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
                        'class' => 'hacu-tagging__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->get_value(),
                    ),
                ));
            ?>

            <input type="text" class="hacu-tagging__selectize" value="<?php echo esc_attr( $this->get_value() ); ?>" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-maximum="<?php echo esc_attr( $this->maximum ); ?>" />
        </div>
        <?php
    }
}
