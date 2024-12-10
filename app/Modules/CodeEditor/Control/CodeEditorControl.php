<?php
/**
 * App > Modules > Code Editor > Control > Code Editor Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\CodeEditor\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `CodeEditorControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class CodeEditorControl extends \WP_Customize_Control {

    /**
     * Holds the allowed programming language.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $language;

    /**
     * Enqueue styles and scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue() {
        wp_enqueue_code_editor(array(
            'type' => 'text/html',
        ));
    }

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-code-editor-' . $this->id;
    }

    /**
     * Render Code Editor Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-code-editor">
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
                        'class' => 'hacu-code-editor__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value(),
                    ),
                ));
            ?>

            <textarea class="hacu-code-editor__textarea" data-language="<?php echo esc_attr( $this->language ); ?>"></textarea>
        </div>
        <?php
    }
}
