<?php
/**
 * App > Core > Panel.
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
 * The `Panel` class contains the WP_Customizer
 * core panel.
 *
 * @since 1.0.0
 */
final class Panel {

    /**
     * Initialize.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render panel.
     * $args = [
     *      'id'          => (string)  Contains the unique slug like string to be used as an id.
     *      'title'       => (string)  Contains the visible label or name of the panel.
     *      'description' => (string)  Contains the discription of the panel.
     *      'priority'    => (integer) Contains the order of panels appears in the Theme Customizer Sizebar.
     * ]
     * @return void
     */
    public function __construct( $customize, $args = array() ) {
        if ( ! empty( $customize ) && ! empty( $args ) ) {
            $this->render( $customize, $args );
        }
    }

    /**
     * Render Panel Layout.
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render panel.
     * @return void
     */
    public function render( $customize, $args ) {
        $schema = array(
            'id'           => array(
                'type'     => 'string',
                'required' => true,
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
        $configuration = Validator::get_configuration( 'panel', $validated );
        if ( $validated && $configuration ) {
            $customize->add_panel( $validated['id'], $configuration );
        }
    }
}
