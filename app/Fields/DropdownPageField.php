<?php
namespace Handy\Fields;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Controls\DropdownPageControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Dropdown Page.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class DropdownPageField extends Setting {

    /**
     * Return an array of pages.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains arguments to retrieve pages.
     * $args = [
     *      'status'      => (array)  The status of the pages.
     *      'order'       => (string) The order of pages.
     *      'placeholder' => (string) The placeholder of the control.
     * ]
     * @return array
     */
    private function get_pages( $args = [] ) {
        $value  = [];
        $order  = Validator::get_sort_order( $args['order'] );
        $status = Validator::get_post_status( $args['status'] );
        $pages  = get_pages([
            'post_status' => $status,
            'sort_order'  => $order
        ]);

        if ( strlen( $args['placeholder'] ) > 0 ) {
            $value[0] = $args['placeholder'];
        }

        if ( ! empty( $pages ) ) {
            foreach ( $pages as $page ) {
                $value[ $page->ID ] = $page->post_title;
            }
        }

        return $value;
    }

    /**
     * Return the validated default value. Validate default if exist in pages.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed for default validation.
     * $args = [
     *      'default' => (number) The default value to be validated.
     *      'choices' => (array)  The list of pages.
     * ]
     * @return integer
     */
    private function get_validated_default( $args = [] ) {
        return ( array_key_exists( $args['default'], $args['choices'] ) ? $args['default'] : 0 );
    }

    /**
     * Render Dropdown Page Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render dropdown page control.
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
     *      'status'            => (array)   The list of page status.
     *      'order'             => (string)  The order of the pages.
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
            'status'            => [
                'type'     => 'array',
                'required' => false
            ],
            'order'             => [
                'type'     => 'string',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = $this->get_pages([
                'order'       => ( isset( $validated['order'] ) ? $validated['order'] : 'asc' ),
                'status'      => ( isset( $validated['status'] ) ? $validated['status'] : [ 'publish' ] ),
                'placeholder' => ( isset( $validated['placeholder'] ) ? $validated['placeholder'] : '' )
            ]);
        }

        if ( isset( $validated['default'] ) ) {
            $validated['default'] = $this->get_validated_default([
                'default' => $validated['default'],
                'choices' => $validated['choices']
            ]);
        }

        if ( ! empty( $validated ) ) {
            $parameters = implode( ',', array_merge( array_keys( $validated['choices'] ), [ '__' ] ) );
            $validation = "in_choices[{$parameters}]";
            if ( isset( $validated['validations'] ) ) {
                array_unshift( $validated['validations'], $validation );
            } else {
                $validated['validations'] = [ $validation ];
            }
        }

        $validated = Helper::unset_keys( $validated, [ 'order', 'status', 'placeholder' ] );
        $config    = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'dropdown_page', $customize, $validated );
            $customize->add_control( new DropdownPageControl( $customize, $config['settings'], $config ) );
        }
    }
}