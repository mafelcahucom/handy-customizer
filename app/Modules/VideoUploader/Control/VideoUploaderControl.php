<?php
namespace Handy\Modules\VideoUploader\Control;

use Handy\Inc\Helper;
use Handy\Inc\Uploader;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Video Uploader.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
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
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-video-uploader-'. $this->id;
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
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);

                // Render Uploader.
                echo Uploader::render([
                    'type'        => 'video',
                    'id'          => $this->prefix_id(),
                    'name'        => $this->id,
                    'value'       => $this->value(),
                    'key_link'    => $this->get_link(),
                    'extensions'  => $this->extensions,
                    'placeholder' => $this->placeholder
                ]);
            ?>
        </div>
        <?php
    }
}