<?php
/**
 * App > Modules > Markup > Field > Markup Field.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Markup\Field;

use Handy\Core\Setting;
use Handy\Inc\Validator;
use Handy\Modules\Markup\Control\MarkupControl;

defined( 'ABSPATH' ) || exit;

/**
 * The `MarkupField` class contains the settings,
 * sanitization and validation.
 *
 * @since 1.0.0
 */
final class MarkupField extends Setting {

    /**
     * Render Markup Control.
     *
     * @since 1.0.0
     *
     * @param  object $customize Contains the instance of WP_Customize_Manager.
     * @param  array  $args      Contains the necessary arguments to render markup control.
     * $args = [
     *     'id'              => (string)  Contains the unique slug like string to be used as an id.
     *     'section'         => (string)  Contains the section where the control belongs to.
     *     'priority'        => (integer) Contains the order of control appears in the section.
     *     'active_callback' => (object)  Contains the callback function whether to show control, must always return true.
     *     'html'            => (string)  Contains the HTML markup to be render
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
            'priority'          => array(
                'type'     => 'integer',
                'required' => false,
            ),
            'active_callback'   => array(
                'type'     => 'mixed',
                'required' => false,
            ),
            'html'              => array(
                'type'     => 'string',
                'required' => true,
            ),
        );

        $validated = Validator::get_validated_argument( $schema, $args );
        $config    = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'markup', $customize, $validated );
            $customize->add_control( new MarkupControl( $customize, $config['settings'], $config ) );
        }
    }
}
