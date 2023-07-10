<?php
namespace Handy\Modules\FileUploader\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\FileUploader\Control\FileUploaderControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > File Uploader.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class FileUploaderField extends Setting {

    /**
     * Return the list of allowed extensions.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function allowed_extensions() {
        return [ 
            'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 
            'ppsx', 'odt', 'xls', 'xlsx', 'psd' 
        ];
    }

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        $file = Helper::get_file_meta( $validated['default'] );
        return ( ! empty( $file ) && in_array( $file['extension'], $validated['extensions'] ) ? $validated['default'] : '' );
    }

    /**
     * Return the validated extensions.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_extensions( $validated ) {
        $extensions = [];
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
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( $validated['extensions'], [ '__' ] ) );
        $validation  = "valid_attachment[{$parameters}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render File Uploader Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render file uploader control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (number)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'extensions'        => (array)   The defined extensions that are allowed
     * ]
     * @return void
     */
    public function render( $customize, $args = [] ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = [
            'id'                => [
                'type'     => 'string',
                'required' => true
            ],
            'section'           => [
                'type'     => 'string',
                'required' => true
            ],
            'default'           => [
                'type'     => 'number',
                'required' => false,
            ],
            'label'             => [
                'type'     => 'string',
                'required' => false,
            ],
            'description'       => [
                'type'     => 'string',
                'required' => false
            ],
            'placeholder'       => [
                'type'     => 'string',
                'required' => false
            ],
            'priority'          => [
                'type'     => 'integer',
                'required' => false
            ],
            'validations'       => [
                'type'     => 'array',
                'required' => false
            ],
            'active_callback'   => [
                'type'     => 'mixed',
                'required' => false
            ],
            'sanitize_callback' => [
                'type'     => 'mixed',
                'required' => false
            ],
            'extensions'        => [
                'type'     => 'array',
                'required' => false
            ]
        ];

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
            $this->setting( 'file_uploader', $customize, $validated );
            $customize->add_control( new FileUploaderControl( $customize, $config['settings'], $config ) );
        }
    }
}