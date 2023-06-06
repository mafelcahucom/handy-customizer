<?php
namespace Handy\Core;

use Handy\Inc\Validator;

defined( 'ABSPATH' ) || exit;

/**
 * Core > Setting.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
class Setting {

    /**
     * Register Control Settings.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contains the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed for setting.
     * @return void
     */
    public function setting( $customize, $args ) {
        if ( empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = [
            'id'           => [
                'type'     => 'string',
                'required' => true,
            ],
            'default'      => [
                'type'     => 'mixed',
                'required' => false
            ],
            'validations'  => [
                'type'     => 'array',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( $validated ) {
            $default = ( isset( $validated['default'] ) ? $validated['default'] : '' );
            $customize->add_setting( $validated['id'], [
                'default' => $default
            ]);
        }
    }
}