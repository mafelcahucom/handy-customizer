<?php
/**
 * App > Core > Section.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Core;

use Handy\Inc\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * The `Section` class contains the WP_Customizer
 * core section.
 *
 * @since 1.0.0
 */
final class Section {

    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render section layout.
     * @args = [
     *      'id'          => (string)  Contains the unique slugin like string to be used as an id.
     *      'panel'       => (string)  Contains the id of the panel where section can be reside.
     *      'title'       => (string)  Contains the visible label or name of the section.
     *      'description' => (string)  Contains the discription of the section.
     *      'priority'    => (integer) Contains the order of sections appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public function __construct( $customize, $args = array() ) {
        if ( ! empty( $customize ) && ! empty( $args ) ) {
            $this->render( $customize, $args );
        }
    }

    /**
     * Render Section Layout.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render section layout.
     * @return void
     */
    public function render( $customize, $args ) {
        $schema = array(
            'id'           => array(
                'type'     => 'string',
                'required' => true,
            ),
            'panel'        => array(
                'type'     => 'string',
                'required' => false,
            ),
            'title'        => array(
                'type'     => 'string',
                'required' => true,
            ),
            'description'  => array(
                'type'     => 'string',
                'required' => false,
            ),
            'priority'     => array(
                'type'     => 'integer',
                'required' => false,
            ),
        );

        $validated     = Validator::get_validated_argument( $schema, $args );
        $configuration = Validator::get_configuration( 'section', $validated );
        if ( $validated && $configuration ) {
            $customize->add_section( $validated['id'], $configuration );
        }
    }
}
