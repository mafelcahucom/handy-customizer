<?php
/**
 * App > Modules > Video Uploader > Field > Video Uploader Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\VideoUploader\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\VideoUploader\Control\VideoUploaderControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `VideoUploaderField` class contains the settings,
 * sanitization and validation of the video uploader.
 *
 * @since 1.0.0
 */
final class VideoUploaderField extends Setting {

    /**
     * Return the list of allowed extensions.
     *
     * @since 1.0.0
     *
     * @return array
     */
    private function allowed_extensions() {
        return array(
            'mp4',
            'm4v',
            'mov',
            'wmv',
            'avi',
            'mpg',
            'ogv',
            '3gp',
            '3g2',
            'webm',
            'mkv',
        );
    }

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $file = Helper::get_file_meta( $validated['default'] );
        // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
        return ( ! empty( $file ) && in_array( $file['extension'], $validated['extensions'] ) ? $validated['default'] : '' );
    }

    /**
     * Return the validated extensions. Validate if defined extensions are valid.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return array
     */
    private function get_validated_extensions( $validated ) {
        $extensions = array();
        if ( isset( $validated['extensions'] ) ) {
            $extensions = Helper::get_intersected( $validated['extensions'], $this->allowed_extensions() );
        }

        return ( ! empty( $extensions ) ? $extensions : $this->allowed_extensions() );
    }

    /**
     * Return the predetermined default validations.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( $validated['extensions'], array( '__' ) ) );
        $validation  = "valid_attachment[{$parameters}]";
        $validations = array( $validation );
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Video Uploader Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render video uploader control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (number)  Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'placeholder'       => (string)  Contains the placeholder of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'extensions'        => (array)   Contains the defined extensions that are allowed.
     * ]
     * @return void
     */
    public function render( $customize, $args = array() ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = array(
            'id'                => array(
                'type'     => 'string',
                'required' => true,
            ),
            'section'           => array(
                'type'     => 'string',
                'required' => true,
            ),
            'default'           => array(
                'type'     => 'number',
                'required' => false,
            ),
            'label'             => array(
                'type'     => 'string',
                'required' => false,
            ),
            'description'       => array(
                'type'     => 'string',
                'required' => false,
            ),
            'placeholder'       => array(
                'type'     => 'string',
                'required' => false,
            ),
            'priority'          => array(
                'type'     => 'integer',
                'required' => false,
            ),
            'validations'       => array(
                'type'     => 'array',
                'required' => false,
            ),
            'active_callback'   => array(
                'type'     => 'mixed',
                'required' => false,
            ),
            'sanitize_callback' => array(
                'type'     => 'mixed',
                'required' => false,
            ),
            'extensions'        => array(
                'type'     => 'array',
                'required' => false,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['extensions']  = $this->get_validated_extensions( $validated );
            $validated['validations'] = $this->get_default_validations( $validated );

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'video_uploader', $customize, $validated );
            $customize->add_control( new VideoUploaderControl( $customize, $config['settings'], $config ) );
        }
    }
}
