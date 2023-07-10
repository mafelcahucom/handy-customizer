<?php
namespace Handy\Modules\CodeEditor\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Code Editor.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
     */
    public function enqueue() {
        wp_enqueue_code_editor([
            'type' => 'text/html'
        ]);
    }

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-code-editor-'. $this->id;
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
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);

                // Input Hidden.
                echo Helper::get_hidden_input([
                    'key_link'   => $this->get_link(),
                    'attributes' => [
                        'class' => 'hacu-code-editor__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>

            <textarea class="hacu-code-editor__textarea" data-language="<?php echo esc_attr( $this->language ); ?>"></textarea>
        </div>
        <?php
    }
}