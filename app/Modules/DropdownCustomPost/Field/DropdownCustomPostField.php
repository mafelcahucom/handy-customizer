<?php
namespace Handy\Modules\DropdownCustomPost\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\DropdownCustomPost\Control\DropdownCustomPostControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Dropdown Custom Post.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class DropdownCustomPostField extends Setting {

    /**
     * Return an array of custom post.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains arguments to retrieve custom posts.
     * $args = [
     *      'post_type'   => (string) The post type slug of custom post.
     *      'status'      => (array)  The status of the custom post.
     *      'order'       => (string) The order of custom posts.
     *      'placeholder' => (string) The placeholder of the control.
     * ]
     * @return array
     */
    private function get_posts( $args = [] ) {
        $value  = [];
        $order  = Validator::get_sort_order( $args['order'] );
        $status = Validator::get_post_status( $args['status'] );
        $posts  = get_posts([
            'post_type'   => $args['post_type'],
            'post_status' => $status,
            'order'       => $order
        ]);

        if ( strlen( $args['placeholder'] ) > 0 ) {
            $value[0] = $args['placeholder'];
        }

        if ( ! empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $value[ $post->ID ] = $post->post_title;
            }
        }

        return $value;
    }

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return integer
     */
    private function get_validated_default( $validated ) {
        return ( array_key_exists( $validated['default'], $validated['choices'] ) ? $validated['default'] : 0 );
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
        $validation  = "in_choices[{$parameters}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Dropdown Post Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render dropdown post control.
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
     *      'post_type'         => (string)  The post type slug of custom post.
     *      'status'            => (array)   The list of post status.
     *      'order'             => (string)  The order of the posts
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
            'post_type'         => [
                'type'     => 'string',
                'required' => true
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
            $validated['choices'] = $this->get_posts([
                'post_type'   => ( isset( $validated['post_type'] ) ? $validated['post_type'] : '' ),
                'status'      => ( isset( $validated['status'] ) ? $validated['status'] : [ 'publish' ] ),
                'order'       => ( isset( $validated['order'] ) ? $validated['order'] : 'asc' ),
                'placeholder' => ( isset( $validated['placeholder'] ) ? $validated['placeholder'] : '' )
            ]);

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $validated = Helper::unset_keys( $validated, [ 'post_type', 'order', 'status', 'placeholder' ] );
        $config    = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'dropdown_custom_post', $customize, $validated );
            $customize->add_control( new DropdownCustomPostControl( $customize, $config['settings'], $config ) );
        }
    }
}