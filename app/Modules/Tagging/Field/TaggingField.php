<?php
/**
 * App > Modules > Tagging > Field > Tagging Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Tagging\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\Tagging\Control\TaggingControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `TaggingField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class TaggingField extends Setting {

    /**
     * Return the validated default value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return array_unique( $validated['default'] );
    }

    /**
     * Return the validated maximum value.
     *
     * @since 1.0.0
     *
     * @param  array $validated Contains the validated arguments.
     * @return integer
     */
    private function get_validated_maximum( $validated ) {
        $is_valid_maximum = isset( $validated['maximum'] ) && $validated['maximum'] > 0;
        return $is_valid_maximum ? $validated['maximum'] : 0;
    }

    /**
     * Render Tagging Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render tagging control.
     * $args = [
     *     'id'                => (string)  Contains the unique slug like string to be used as an id.
     *     'section'           => (string)  Contains the section where the control belongs to.
     *     'default'           => (array)   Contains the default value of the control.
     *     'label'             => (string)  Contains the label of the control.
     *     'description'       => (string)  Contains the description of the control.
     *     'placeholder'       => (string)  Contains the placeholder of the control.
     *     'priority'          => (integer) Contains the order of control appears in the section.
     *     'validations'       => (array)   Contains the list of built-in and custom validations.
     *     'active_callback'   => (object)  Contains the callback function whether to show control, must always return true.
     *     'sanitize_callback' => (object)  Contains the callback function to sanitize the value before saving in database.
     *     'maximum'           => (integer) Contains the total limit of tag items
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
                'type'     => 'array',
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
            'maximum'           => array(
                'type'     => 'integer',
                'required' => false,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['maximum'] = $this->get_validated_maximum( $validated );

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'tagging', $customize, $validated );
            $customize->add_control( new TaggingControl( $customize, $config['settings'], $config ) );
        }
    }
}
