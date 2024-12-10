<?php
/**
 * App > Modules > Video Uploader > Control > Video Uploader Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\VideoUploader\Control;

use Handy\Inc\Helper;
use Handy\Inc\Uploader;

defined( 'ABSPATH' ) || exit;

/**
 * The `VideoUploaderControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class VideoUploaderControl extends \WP_Customize_Control {

    /**
     * Holds placeholder.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds defined extensions.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $extensions;

    /**
     * Enqueues style and scripts
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function enqueue() {
        if ( ! wp_style_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_style( 'wp-mediaelement' );
        }

        if ( ! wp_script_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_script( 'wp-mediaelement' );
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
        return 'hacu-video-uploader-' . $this->id;
    }

    /**
     * Render Video Uploader Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-video-uploader">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));

                echo Uploader::render(array(
                    'type'        => 'video',
                    'id'          => $this->prefix_id(),
                    'name'        => $this->id,
                    'value'       => $this->value(),
                    'key_link'    => $this->get_link(),
                    'extensions'  => $this->extensions,
                    'placeholder' => $this->placeholder,
                ));
            ?>
        </div>
        <?php
    }
}
