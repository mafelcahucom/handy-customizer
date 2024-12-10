<?php
/**
 * App > Modules > Dropdown Custom Post > Field > Dropdown Custom Post Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\DropdownCustomPost\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\DropdownCustomPost\Control\DropdownCustomPostControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `DropdownCustomPostField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class DropdownCustomPostField extends Setting {

    /**
     * Return an array of custom post.
     *
     * @since 1.0.0
     *
     * @param  array $args Contains arguments to retrieve custom posts.
     * $args = [
     *     'post_type'   => (string) Contains the post type slug of custom post.
     *     'status'      => (array)  Contains the status of the custom post.
     *     'order'       => (string) Contains the order of custom posts.
     *     'placeholder' => (string) Contains the placeholder of the control.
     * ]
     * @return array
     */
    private function get_posts( $args = array() ) {
        $value  = array();
        $order  = Validator::get_sort_order( $args['order'] );
        $status = Validator::get_post_status( $args['status'] );
        $posts  = get_posts(array(
            'post_type'   => $args['post_type'],
            'post_status' => $status,
            'order'       => $order,
        ));

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
     * @param  array $validated Contains the validated arguments.
     * @return integer
     */
    private function get_validated_default( $validated ) {
        return array_key_exists( $validated['default'], $validated['choices'] ) ? $validated['default'] : 0;
    }

    /**
     * Return the predetermined default validations.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( array_keys( $validated['choices'] ), array( '__' ) ) );
        $validation  = "in_choices[{$parameters}]";
        $validations = array( $validation );
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
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render dropdown post control.
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
     *     'post_type'         => (string)  Contains the post type slug of custom post.
     *     'status'            => (array)   Contains the list of post status.
     *     'order'             => (string)  Contains the order of the posts
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
            'post_type'         => array(
                'type'     => 'string',
                'required' => true,
            ),
            'status'            => array(
                'type'     => 'array',
                'required' => false,
            ),
            'order'             => array(
                'type'     => 'string',
                'required' => false,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['choices'] = $this->get_posts(array(
                'post_type'   => isset( $validated['post_type'] ) ? $validated['post_type'] : '',
                'status'      => isset( $validated['status'] ) ? $validated['status'] : array( 'publish' ),
                'order'       => isset( $validated['order'] ) ? $validated['order'] : 'asc',
                'placeholder' => isset( $validated['placeholder'] ) ? $validated['placeholder'] : '',
            ));

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $validated = Helper::unset_keys( $validated, array( 'post_type', 'order', 'status', 'placeholder' ) );
        $config    = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'dropdown_custom_post', $customize, $validated );
            $customize->add_control( new DropdownCustomPostControl( $customize, $config['settings'], $config ) );
        }
    }
}
