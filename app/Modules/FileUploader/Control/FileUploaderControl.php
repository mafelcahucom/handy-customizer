<?php
/**
 * App > Modules > File Uploader > Control > File Uploader Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\FileUploader\Control;

use Handy\Inc\Helper;
use Handy\Inc\Uploader;

defined( 'ABSPATH' ) || exit;

/**
 * The `FileUploaderControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class FileUploaderControl extends \WP_Customize_Control {

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
     * Return the ID with prefix
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-file-uploader-' . $this->id;
    }

    /**
     * Render File Uploader Control Content.
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-file-uploader">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));

                echo Uploader::render(array(
                    'type'        => 'application',
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
