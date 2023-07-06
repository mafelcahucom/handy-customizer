<?php
namespace Handy\Core;

use Handy\Inc\Helper;
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
     * Holds the name of field.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    private $field = '';

    /**
     * Holds the set of validations.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    private $validations = [];

    /**
     * Register Control Settings.
     * 
     * @since 1.0.0
     * 
     * @param  string  $field      Contains the name of the field.
     * @param  object  $customize  Contains the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed for setting.
     * @return void
     */
    public function setting( $field, $customize, $args ) {
        if ( empty( $field ) || empty( $customize ) || empty( $args ) ) {
            return;
        }

        $schema = [
            'id'                => [
                'type'     => 'string',
                'required' => true,
            ],
            'default'           => [
                'type'     => 'mixed',
                'required' => false
            ],
            'validations'       => [
                'type'     => 'array',
                'required' => false
            ],
            'sanitize_callback' => [
                'type'     => 'mixed',
                'required' => false
            ]
        ];
        
        $validated = Validator::get_validated_argument( $schema, $args );
        if ( $validated ) {
            // Set "validate_callback".
            if ( isset( $validated['validations'] ) ) {
                $validations = array_unique( $validated['validations'] );
                if ( ! empty( $validations ) ) {
                    $this->validations           = $validations;
                    $config['validate_callback'] = function( $validity, $value ) {
                        return $this->perform_validation([
                            'value'       => $value,
                            'validity'    => $validity
                        ]);
                    };
                }
            }

            // Set default "sanitize_callback" if sanitize_callback is not isset.
            if ( isset( $validated['sanitize_callback'] ) ) {
                $config['sanitize_callback'] = $validated['sanitize_callback'];
            } else {
                $this->field                 = $field;
                $config['sanitize_callback'] = function( $input, $setting ) {
                    return $this->perform_sanitization([
                        'input'   => $input,
                        'setting' => $setting
                    ]);
                };
            }

            // Set "default".
            $config['default'] = ( isset( $validated['default'] ) ? $validated['default'] : '' );

            // Register Setting.
            $customize->add_setting( $validated['id'], $config );
        }
    }

    /**
     * Perform a sanitization based on the field.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments to perform sanitization.
     * $args = [
     *      'input'   => (mixed)  Contains the value to sanitize.
     *      'setting' => (object) Contains WP_Customize_Setting instance.
     * ]
     * @return mixed
     */
    private function perform_sanitization( $args = [] ) {
        if ( empty( $this->field ) ) {
            return $args['input'];
        }

        $field = [
            'audio_uploader'       => 'sanitize_attachment',
            'button_set'           => 'sanitize_choices',
            'checkbox'             => 'sanitize_boolean',
            'checkbox_multiple'    => 'sanitize_multiple',
            'checkbox_pill'        => 'sanitize_multiple',
            'color_set'            => 'sanitize_color_set',
            'counter'              => 'sanitize_counter',
            'date_picker'          => 'sanitize_date_picker',
            'dropdown_custom_post' => 'sanitize_choices',
            'dropdown_page'        => 'sanitize_choices',
            'dropdown_post'        => 'sanitize_choices',
            'email'                => 'sanitize_email',
            'file_uploader'        => 'sanitize_attachment',
            'image_checkbox'       => 'sanitize_multiple',
            'image_radio'          => 'sanitize_choices',
            'image_uploader'       => 'sanitize_attachment',
            'number'               => 'sanitize_number',
            'radio'                => 'sanitize_choices',
            'range'                => 'sanitize_range',
            'select'               => 'sanitize_choices',
            'size'                 => 'sanitize_size',
            'sortable'             => 'sanitize_multiple',
            'switches'             => 'sanitize_boolean',
            'tagging'              => 'sanitize_tagging',
            'tagging_select'       => 'sanitize_tagging_select',
            'text'                 => 'sanitize_text',
            'textarea'             => 'sanitize_textarea',
            'time_picker'          => 'sanitize_time_picker',
            'toggle'               => 'sanitize_boolean',
            'url'                  => 'sanitize_url',
            'video_uploader'       => 'sanitize_attachment'
        ];

        if ( isset( $field[ $this->field ] ) ) {
            $sanitization = $field[ $this->field ];
            if ( method_exists( __CLASS__, $sanitization ) ) {
                return call_user_func( [ __CLASS__, $sanitization ], $args['input'], $args['setting'] );
            }
        }

        return $args['input'];
    }

    /**
     * Return the sanitized text value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_text( $input, $setting ) {
        return sanitize_text_field( $input );
    }

    /**
     * Return the sanitized textarea value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_textarea( $input, $setting ) {
        return sanitize_textarea_field( $input );
    }

    /**
     * Return the sanitized email value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_email( $input, $setting ) {
        return sanitize_email( $input );
    }

    /**
     * Return the sanitized url value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_url( $input, $setting ) {
        return esc_url_raw( $input );
    }

    /**
     * Return the sanitized boolean value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_boolean( $input, $setting ) {
        return ( $input ? true : false );
    }

    /**
     * Return the sanitized number value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_number( $input, $setting ) {
        if ( ! is_numeric( $input ) ) {
            return '';
        }

        return floatval( $input );
    }

    /**
     * Return the sanitized size value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_size( $input, $setting ) {
        $value = sanitize_text_field( $input );
        $units = $setting->manager->get_control( $setting->id )->units;

        return ( Validator::is_valid_size( $value, $units ) ? $value : '' );
    }

    /**
     * Return the sanitized choices value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_choices( $input, $setting ) {
        $value   = sanitize_text_field( $input );
        $choices = $setting->manager->get_control( $setting->id )->choices;
        
        return ( array_key_exists( $value, $choices ) ? $value : '' );
    }

    /**
     * Return the sanitized multiple value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_multiple( $input, $setting ) {
        if ( strlen( $input ) === 0 ) {
            return [];
        }

        $choices  = $setting->manager->get_control( $setting->id )->choices;
        $exploded = Helper::get_exploded_value( $input, array_keys( $choices ) );

        return array_map( 'sanitize_text_field', $exploded );
    }

    /**
     * Return the sanitized color set value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_color_set( $input, $setting ) {
        $value  = sanitize_text_field( $input );
        $colors = $setting->manager->get_control( $setting->id )->colors;

        return ( Validator::is_valid_hexa_color( $value ) && in_array( $value, $colors ) ? $value : '' );
    }

    /**
     * Return the sanitized counter value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_counter( $input, $setting ) {
        $options          = $setting->manager->get_control( $setting->id )->options;
        $is_valid_min_max = ( is_numeric( $options['min'] ) && is_numeric( $options['max'] ) );

        $value = 0;
        if ( is_numeric( $input ) ) {
            $value = floatval( $input );
            if ( $is_valid_min_max ) {
                $min   = floatval( $options['min'] );
                $max   = floatval( $options['max'] );

                if ( $value < $min ) {
                    $value = $min;
                }

                if ( $value > $max ) {
                    $value = $max;
                }
            }
        }

        return $value;
    }

    /**
     * Return the sanitized date picker value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_date_picker( $input, $setting ) {
        if ( strlen( $input ) === 0 ) {
            return [];
        }

        $enable_time = $setting->manager->get_control( $setting->id )->enable_time;
        $mode        = $setting->manager->get_control( $setting->id )->mode;
        $length      = ( $mode === 'single' ? 1 : 2 );
        $format      = ( $enable_time ? 'Y-m-d H:i' : 'Y-m-d' );
        $exploded    = explode( ',', $input );
        
        $has_invalid = false;
        if ( count( $exploded ) !== $length ) {
            $has_invalid = true;
        }

        if ( ! $has_invalid ) {
            foreach ( $exploded as $value ) {
                if ( ! Validator::is_valid_date( $value, $format ) ) {
                    $has_invalid = true;
                }
            }
        }

        return ( ! $has_invalid ? $exploded : [] );
    }

    /**
     * Return the sanitized range value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_range( $input, $setting ) {
        $options = $setting->manager->get_control( $setting->id )->options;
        $value   = $options['min'];
        if ( is_numeric( $input ) ) {
            $value = floatval( $input );
            if ( $value < $options['min'] ) {
                $value = $options['min'];
            }

            if ( $value > $options['max'] ) {
                $value = $options['max'];
            }
        }
        
        return $value;
    }

    /**
     * Return the sanitize attachment value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_attachment( $input, $setting ) {
        $value = sanitize_text_field( $input );
        if ( strlen( $value ) === 0 ) {
            return '';
        }

        $file = Helper::get_file_meta( $value );
        if ( empty( $file ) ) {
            return '';
        }

        $extensions = $setting->manager->get_control( $setting->id )->extensions;

        return ( in_array( $file['extension'], $extensions ) ? $value : '' );
    }

    /**
     * Return the sanitized tagging value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_tagging( $input, $setting ) {
        if ( strlen( $input ) === 0 ) {
            return [];
        }

        $maximum  = $setting->manager->get_control( $setting->id )->maximum;
        $exploded = array_filter( array_unique( explode( ',', $input ) ) );
        if ( $maximum > 0 && count( $exploded ) > $maximum ) {
            $exploded = array_slice( $exploded, 0, $maximum );
        }
        
        return array_map( 'sanitize_text_field', $exploded );
    }

    /**
     * Return the sanitized tagging select value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_tagging_select( $input, $setting ) {
        if ( strlen( $input ) === 0 ) {
            return [];
        }

        $maximum  = $setting->manager->get_control( $setting->id )->maximum;
        $choices  = $setting->manager->get_control( $setting->id )->choices;
        $exploded = Helper::get_exploded_value( $input, array_keys( $choices ) );
        if ( $maximum > 0 && count( $exploded ) > $maximum ) {
            $exploded = array_slice( $exploded, 0, $maximum );
        }
        
        return array_map( 'sanitize_text_field', $exploded );
    }

    /**
     * Return the sanitized time picker value.
     * 
     * @since 1.0.0
     *
     * @param  mixed   $input    The value to sanitize.
     * @param  object  $setting  WP_Customize_Setting instance.
     * @return string
     */
    private function sanitize_time_picker( $input, $setting ) {
        if ( strlen( $input ) === 0 ) {
            return '';
        }

        $format      = $setting->manager->get_control( $setting->id )->format;
        $time_format = ( $format === 'civilian' ? 'h:i A' : 'H:i' );

        return ( Validator::is_valid_date( $input, $time_format ) ? $input : '' );
    }

    /**
     * Perform a validation based on set of validations.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments to perform validation.
     * $args = [
     *      'value'    => (mixed)  Contains the value of the field.
     *      'validity' => (object) Contains the validation prompt.
     * ]
     * @return object
     */
    private function perform_validation( $args = [] ) {
        $validations = [
            'required'                 => [
                'has_param' => false
            ],
            'valid_email'              => [
                'has_param' => false
            ],
            'valid_url'                => [
                'has_param' => false
            ],
            'valid_ip'                 => [
                'has_param' => false
            ],
            'valid_size'               => [
                'has_param' => true
            ],
            'is_number'                => [
                'has_param' => false
            ],
            'is_integer'               => [
                'has_param' => false
            ],
            'is_float'                 => [
                'has_param' => false
            ],
            'alpha'                    => [
                'has_param' => false
            ],
            'alpha_numeric'            => [
                'has_param' => false
            ],
            'min_length'               => [
                'has_param' => true
            ],
            'max_length'               => [
                'has_param' => true
            ],
            'exact_length'             => [
                'has_param' => true
            ],
            'greater_than'             => [
                'has_param' => true
            ],
            'greater_than_equal_to'    => [
                'has_param' => true
            ],
            'less_than'                => [
                'has_param' => true
            ],
            'less_than_equal_to'       => [
                'has_param' => true
            ],
            'in_choices'               => [
                'has_param' => true
            ],
            'not_in_choices'           => [
                'has_param' => true
            ],
            'values_in_choices'        => [
                'has_param' => true
            ],
            'values_not_in_choices'    => [
                'has_param' => true
            ],
            'total_words'              => [
                'has_param' => true
            ],
            'total_words_greater_than' => [
                'has_param' => true
            ],
            'total_words_less_than'    => [
                'has_param' => true
            ],
            'equal_to_setting'         => [
                'has_param' => true
            ],
            'not_equal_to_setting'     => [
                'has_param' => true
            ],
            'valid_attachment'         => [
                'has_param' => true
            ],
            'valid_dates'              => [
                'has_param' => true
            ],
            'valid_time'               => [
                'has_param' => true
            ]
        ];

        $validity      = $args['validity'];
        $current_value = $args['value'];
        if ( empty( $this->validations ) ) {
            return $validity;
        }

        foreach ( $this->validations as $value ) {
            $validation = $this->get_validation_name( $value );
            if ( ! empty( $validation ) ) {
                if ( isset( $validations[ $validation ] ) ) {
                    if ( $validations[ $validation ]['has_param'] ) {
                        if ( $this->validation_has_parameter( $value ) ) {
                            $parameter = $this->get_validation_parameter( $value );
                            if ( strlen( $parameter ) !== 0 ) {
                                if ( method_exists( __CLASS__, $validation ) ) {
                                    call_user_func( [ __CLASS__, $validation ], $validity, $current_value, $parameter );
                                }
                            }
                        }
                    } else {
                        if ( method_exists( __CLASS__, $validation ) ) {
                            call_user_func( [ __CLASS__, $validation ], $validity, $current_value );
                        }
                    }
                } else {
                    if ( function_exists( $validation ) ) {
                        call_user_func( $validation, $validity, $current_value );
                    }
                }
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is empty.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function required( $validity, $value ) {
        if ( Validator::is_empty( $value ) ) {
            $validity->add( 'error', $this->__p( 'Required field.' ) );
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid email address.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function valid_email( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( filter_var( $value, FILTER_VALIDATE_EMAIL ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid email address' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid url.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function valid_url( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( filter_var( $value, FILTER_VALIDATE_URL ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid url' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid ip address.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function valid_ip( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( filter_var( $value, FILTER_VALIDATE_IP ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid IP address' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid size.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $units     Contains the list of valid size units.
     * @return object
     */
    private function valid_size( $validity, $value, $units ) {
        if ( ! Validator::is_empty( $value ) ) {
            $exploded = explode( ',', $units );
            if ( ! Validator::is_valid_size( $value, $exploded ) ) {
                $validity->add( 'error', $this->__p( 'Invalid size.' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid number.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function is_number( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( is_numeric( $value ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid number.' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid integer.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function is_integer( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( filter_var( $value, FILTER_VALIDATE_INT ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid integer number.' ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the value is an invalid float.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
     */
    private function is_float( $validity, $value ) {
        if ( ! Validator::is_empty( $value ) ) {
            if ( filter_var( $value, FILTER_VALIDATE_FLOAT ) === false ) {
                $validity->add( 'error', $this->__p( 'Invalid float number.' ) );
            }
        }

        return $validity;
    }

    /**
	 * Print an error message if the value contains a none alphabetical character.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
	 */
	private function alpha( $validity, $value ) {
		if ( ! Validator::is_empty( $value ) ) {
			if ( ctype_alpha( $value ) === false ) {
				$validity->add( 'error', $this->__p( 'Must contain only alphabetical characters.' ) );
			}
		}

		return $validity;
	}

    /**
	 * Print an error message if the value contains a none alphabetical and numeric character.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @return object
	 */
	private function alpha_numeric( $validity, $value ) {
		if ( ! Validator::is_empty( $value ) ) {
			if ( ctype_alnum( $value ) === false ) {
				$validity->add( 'error', $this->__p( 'Must contain only numeric and alphabetical characters.' ) );
			}
		}

		return $validity;
	}

    /**
	 * Print an error message if the value length is less than the minimum.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $minimum   Contains the minimum length. 
     * @return object
	 */
	private function min_length( $validity, $value, $minimum ) {
        if ( ctype_digit( $minimum ) ) {
            if ( strlen( $value ) < intval( $minimum ) ) {
                $validity->add( 'error', $this->__p( "Total characters must not be less than {$minimum}." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value length is greater than the maximum.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $maximum   Contains the maximum length. 
	 */
	private function max_length( $validity, $value, $maximum ) {
        if ( ctype_digit( $maximum ) ) {
            if ( strlen( $value ) > intval( $maximum ) ) {
                $validity->add( 'error', $this->__p( "Total characters must not exceed {$maximum}." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value length is not equal to length.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $length    Contains the exact length. 
	 * @return object
	 */
	private function exact_length( $validity, $value, $length ) {
        if ( ctype_digit( $length ) ) {
            if ( strlen( $value ) !== intval( $length ) ) {
                $validity->add( 'error', $this->__p( "Total characters must be exact {$length}." ) );
            }
        }

        return $validity;
	}

    /**
	 * Print an error message if the value is less than or equal to number.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  number  $number 	  Number set to be condition.
	 * @return object
	 */
	private function greater_than( $validity, $value, $number ) {
        if ( is_numeric( $value ) && is_numeric( $number ) ) {
            if ( floatval( $value ) <= floatval( $number ) ) {
                $validity->add( 'error', $this->__p( "Value must be greater than {$number}." ) );
            }
        } else {
            if ( ! is_numeric( $value ) ) {
                $validity->add( 'error', $this->__p( "Invalid number." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value is less than number.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  number  $number 	  Number set to be condition.
	 * @return object
	 */
	private function greater_than_equal_to( $validity, $value, $number ) {
        if ( is_numeric( $value ) && is_numeric( $number ) ) {
            if ( floatval( $value ) < floatval( $number ) ) {
                $validity->add( 'error', $this->__p( "Value must be greater than or equal to {$number}." ) );
            }
        } else {
            if ( ! is_numeric( $value ) ) {
                $validity->add( 'error', $this->__p( "Invalid number." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value is greater than or equal to number.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  number  $number 	  Number set to be condition.
	 * @return object
	 */
	private function less_than( $validity, $value, $number ) {
        if ( is_numeric( $value ) && is_numeric( $number ) ) {
            if ( floatval( $value ) >= floatval( $number ) ) {
                $validity->add( 'error', $this->__p( "Value must be less than {$number}." ) );
            }
        } else {
            if ( ! is_numeric( $value ) ) {
                $validity->add( 'error', $this->__p( "Invalid number." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value is greater than number.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  number  $number 	  Number set to be condition.
	 * @return object
	 */
	private function less_than_equal_to( $validity, $value, $number ) {
        if ( is_numeric( $value ) && is_numeric( $number ) ) {
            if ( floatval( $value ) > floatval( $number ) ) {
                $validity->add( 'error', $this->__p( "Value must be less than or equal to {$number}." ) );
            }
        } else {
            if ( ! is_numeric( $value ) ) {
                $validity->add( 'error', $this->__p( "Invalid number." ) );
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value is not found in predetermined choices.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $choices   Set of predetermined choices.
	 * @return object
	 */
	private function in_choices( $validity, $value, $choices ) {
        if ( ! Validator::is_empty( $choices ) ) {
            $exploded = Helper::array_remove_empty( explode( ',', $choices ) );
            $imploded = implode( ', ', $exploded );
            if ( ! empty( $exploded ) ) {
                if ( ! in_array( $value, $exploded ) ) {
                    $keys = ( ! in_array( '__', $exploded ) ? "[{$imploded}]" : '' );
                    $validity->add( 'error', $this->__p( "Value not found in the choices <strong><em>{$keys}</em></strong>." ) );
                }
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value is found in predetermined choices.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $choices   Set of predetermined choices.
	 * @return object
	 */
	private function not_in_choices( $validity, $value, $choices ) {
        if ( ! Validator::is_empty( $choices ) ) {
            $exploded = Helper::array_remove_empty( explode( ',', $choices ) );
            $imploded = implode( ', ', $exploded );
            if ( ! empty( $exploded ) ) {
                if ( in_array( $value, $exploded ) ) {
                    $keys = ( ! in_array( '__', $exploded ) ? "[{$imploded}]" : '' );
                    $validity->add( 'error', $this->__p( "Value must not exists in the choices <strong><em>{$keys}</em></strong>." ) );
                }
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if a certain value in array values is not found in predetermined choices.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  string  $values    Contains the values of the field.
	 * @param  string  $choices   Set of predetermined choices.
	 * @return object
	 */
	private function values_in_choices( $validity, $values, $choices ) {
        if ( ! Validator::is_empty( $values ) && ! Validator::is_empty( $choices ) ) {
            $values  = Helper::array_remove_empty( explode( ',', $values ) );
            $choices = Helper::array_remove_empty( explode( ',', $choices ) );
            if ( ! empty( $values ) && ! empty( $choices ) ) {
                $imploded = implode( ', ', $choices );
                $keys     = ( ! in_array( '__', $choices ) ? "[{$imploded}]" : '' );
                foreach ( $values as $value ) {
                    if ( ! in_array( $value, $choices ) ) {
                        $validity->add( 'error', $this->__p( "A certain value is not found in the choices <strong><em>{$keys}</em></strong>." ) );
                        break;
                    }
                }
            }
        }

		return $validity;
	}

    /**
     * Print an error message if a certain value in array values is found in predetermined choices.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  string  $values    Contains the values of the field.
	 * @param  string  $choices   Set of predetermined choices.
	 * @return object
	 */
	private function values_not_in_choices( $validity, $values, $choices ) {
        if ( ! Validator::is_empty( $values ) && ! Validator::is_empty( $choices ) ) {
            $values  = Helper::array_remove_empty( explode( ',', $values ) );
            $choices = Helper::array_remove_empty( explode( ',', $choices ) );
            if ( ! empty( $values ) && ! empty( $choices ) ) {
                $imploded = implode( ', ', $choices );
                $keys     = ( ! in_array( '__', $choices ) ? "[{$imploded}]" : '' );
                foreach ( $values as $value ) {
                    if ( in_array( $value, $choices ) ) {
                        $validity->add( 'error', $this->__p( "A certain value must not exists in the choices <strong><em>{$keys}</em></strong>." ) );
                        break;
                    }
                }
            }
        }

		return $validity;
	}

    /**
	 * Print an error message if the value total words count is not equal to number.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  number  $number 	  Total count of words.
	 * @return object
	 */
	private function total_words( $validity, $value, $number ) {
        if ( is_numeric( $number ) ) {
            if ( str_word_count( $value ) !== intval( $number ) ) {
                $validity->add( 'error', $this->__p( "Total count of words must be exact {$number}." ) );
            }
        }

        return $validity;
	}

    /**
	 * Print an error message if the value total words count is less than the maximum.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $maximum   Contains maximum words count.
	 * @return object
	 */
	private function total_words_greater_than( $validity, $value, $maximum ) {
        if ( is_numeric( $maximum ) ) {
            if ( str_word_count( $value ) < intval( $maximum ) ) {
                $validity->add( 'errpr', $this->__p( "Total count of words must be greater than or equal to {$maximum}." ) );
            }
        }

        return $validity;
	}

    /**
	 * Print an error message if the value total words count is greater than the minimum.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $minimum   Contains minimum words count.
	 * @return object
	 */
	private function total_words_less_than( $validity, $value, $minimum ) {
        if ( is_numeric( $minimum ) ) {
            if ( str_word_count( $value ) > intval( $minimum ) ) {
                $validity->add( 'errpr', $this->__p( "Total count of words must be less than or equal to {$minimum}." ) );
            }
        }

        return $validity;
	}

    /**
	 * Print an error message if the value is not equal to a value of a certain setting.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $setting   Contains the theme modification name.
	 * @return object
	 */
	private function equal_to_setting( $validity, $value, $setting ) {
        if ( ! Validator::is_empty( $setting ) ) {
            if ( get_theme_mod( $setting ) !== $value ) {
                $validity->add( 'error', $this->__p( "Value must be equal to the value of setting: <strong><em>{$setting}</em></strong>." ) );
            }
        }

        return $validity;
	}

    /**
	 * Print an error message if the value is equal to a value of a certain setting.
	 *
	 * @since 1.0.0
	 * 
	 * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
	 * @param  string  $setting   Contains the theme modification name.
	 * @return object
	 */
	private function not_equal_to_setting( $validity, $value, $setting ) {
        if ( ! Validator::is_empty( $setting ) ) {
            if ( get_theme_mod( $setting ) === $value ) {
                $validity->add( 'error', $this->__p( "Value must not be equal to the value of setting: <strong><em>{$setting}</em></strong>." ) );
            }
        }

        return $validity;
	}

    /**
     * Print an error message if the attachment file type and extension is
     * not found in predetermined extensions.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity    Contains the validation prompt.
     * @param  mixed   $value       Contains the value of the field.
     * @param  string  $extensions  Set of predetermined extensions.
     * @return object
     */
    private function valid_attachment( $validity, $value, $extensions ) {
        if ( ! Validator::is_empty( $value ) && ! Validator::is_empty( $extensions ) ) {
            $file = Helper::get_file_meta( $value );
            if ( ! empty( $file ) ) {
                $exploded  = Helper::array_remove_empty( explode( ',', $extensions ) );
                $imploded  = implode( ', ', $exploded );
                if ( ! empty( $exploded ) ) {
                    if ( ! in_array( $file['extension'], $exploded ) ) {
                        $validity->add( 'error', $this->__p( "The specified file <strong><em>{$file['filename']}</em></strong> is not allowed. Only files with the following extensions are allowed: <strong><em>{$imploded}</em></strong>." ) );
                    }
                }
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the dates contains an invalid date
     * based in predetermined date format.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $format    Set of predetermined date format.
     * @return object
     */
    private function valid_dates( $validity, $value, $format ) {
        if ( ! Validator::is_empty( $value ) && ! Validator::is_empty( $format ) ) {
            $has_invalid = false;
            $exploded    = explode( ',', $value );
            foreach ( $exploded as $date ) {
                if ( ! Validator::is_valid_date( $date, $format ) ) {
                    $has_invalid = true;
                }
            }

            if ( $has_invalid === true ) {
                $validity->add( 'error', $this->__p( "The selected dates contains an invalid date format. Only date with format <strong><em>{$format}</em></strong> are only allowed." ) );
            }
        }

        return $validity;
    }

    /**
     * Print an error message if the time contains an invalid time
     * based in predetermined time format.
     * 
     * @since 1.0.0
     *
     * @param  object  $validity  Contains the validation prompt.
     * @param  mixed   $value     Contains the value of the field.
     * @param  string  $format    Set of predetermined time format.
     * @return object
     */
    private function valid_time( $validity, $value, $format ) {
        if ( ! Validator::is_empty( $value ) && ! Validator::is_empty( $format ) ) {
            if ( ! Validator::is_valid_date( $value, $format ) ) {
                $validity->add( 'error', $this->__p( "The selected time contains an invalid time format. Only time with format <strong><em>{$format}</em></strong> are only allowed." ) );
            }
        }

        return $validity;
    }

    /**
     * Return a text wrapped in <p> tag.
     * 
     * @since 1.0.0
     *
     * @param  string  $string  Contains the string to be wrapped.
     * @return HTMLElement
     */
    private function __p( $string ) {
        return '<p>'. $string .'</p>';
    }

    /**
     * Checks if the validation contains a parameter or bracket "[" and "]".
     * 
     * @since 1.0.0
     *
     * @param  string  $validation  Contains the validation name.
     * @return boolean
     */
    private function validation_has_parameter( $validation ) {
        return ( strpos( $validation, '[' ) || strpos( $validation, ']' ) );
    }

    /**
     * Return the validation name without bracket for validation that
     * contains a paramaters.
     *
     * @param  string  $validation  Contains the validation name.
     * @return string
     */
    private function get_validation_name( $validation ) {
        if ( $this->validation_has_parameter( $validation ) ) {
            $exploded = explode( '[', $validation, 2 );
            return $exploded[0];
        }

        return $validation;
    }

    /**
     * Return the parameters inside braket [].
     * 
     * @since 1.0.0
     *
     * @param  string  $validation  Contains the validation name.
     * @return string
     */
    private function get_validation_parameter( $validation ) {
        preg_match( '#\[(.*?)\]#', $validation, $match );
		return ( isset( $match[1] ) ? $match[1] : [] );
    }
}