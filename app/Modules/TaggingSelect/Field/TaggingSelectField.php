<?php
namespace Handy\Modules\TaggingSelect\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\TaggingSelect\Control\TaggingSelectControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Tagging Select.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class TaggingSelectField extends Setting {

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return array
     */
    private function get_validated_default( $validated ) {
        return Helper::get_intersected( $validated['default'], array_keys( $validated['choices'] ) );
    }

    /**
     * Return the validated maximum value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return integer
     */
    private function get_validated_maximum( $validated ) {
        $is_valid_maximum = ( isset( $validated['maximum'] ) && $validated['maximum'] > 0 );
        return ( $is_valid_maximum ? $validated['maximum'] : 0 );
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
        $parameters  = implode( ',', array_merge( array_keys( $validated['choices'] ), [ '__' ] ) );
        $validation  = "values_in_choices[{$parameters}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Tagging Select Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render tagging select control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (array)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'placeholder'       => (string)  The placeholder of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'maximum'           => (integer) The total limit of tag items.
     *      'choices'           => (array)   The list of choices
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
                'type'     => 'array',
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
            'maximum'           => [
                'type'     => 'integer',
                'required' => false
            ],
            'choices'           => [
                'type'     => 'array',
                'required' => true
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = array_unique( $validated['choices'] );
            if ( empty( $validated['choices'] ) ) {
                return;
            }
            
            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['maximum']     = $this->get_validated_maximum( $validated );
            $validated['validations'] = $this->get_default_validations( $validated );
        }
        
        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'tagging_select', $customize, $validated );
            $customize->add_control( new TaggingSelectControl( $customize, $config['settings'], $config ) );
        }
    }
}