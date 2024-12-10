<?php
/**
 * App > Modules > Content Editor > Control > Content Editor Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\ContentEditor\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `ContentEditorControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class ContentEditorControl extends \WP_Customize_Control {

    /**
     * Holds the flag whether to display media uploader.
     *
     * @since 1.0.0
     *
     * @var boolean
     */
    public $uploader;

    /**
     * Holds the imploded allowed controls in toolbar.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $toolbars;

    /**
     * Enqueue styles and scripts.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue() {
        wp_enqueue_editor();
    }

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-content-editor-' . $this->id;
    }

    /**
     * Render Content Editor Control Content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-content-editor">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));
            ?>

            <textarea class="hacu-content-editor__textarea" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" data-uploader="<?php echo esc_attr( $this->uploader ); ?>" data-toolbars="<?php echo esc_attr( $this->toolbars ); ?>" <?php $this->link(); ?>>
                <?php echo $this->value(); ?>
            </textarea>
        </div>
        <?php
    }
}
