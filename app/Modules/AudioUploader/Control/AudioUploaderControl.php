<?php
namespace Handy\Modules\AudioUploader\Control;

use Handy\Inc\Helper;
use Handy\Inc\Uploader;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Audio Uploader.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class AudioUploaderControl extends \WP_Customize_Control {

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
     * Enqueues style and scripts.
     * 
     * @since 1.0.0
     */
    public function enqueue() {
        if ( ! wp_style_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_style( 'wp-mediaelement' );
        }

        if ( ! wp_script_is( 'wp-mediaelement', 'enqueued' ) ) {
            wp_enqueue_script('wp-mediaelement');
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
        return  'hacu-audio-uploader-'. $this->id;
    }

    /**
     * Render Audio Uploader Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-audio-uploader">
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
                    'type'        => 'audio',
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