<?php
namespace Handy\Inc;

use Handy\Lib\Singleton;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Uploader.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class Uploader {

    /**
     * Inherit Singleton.
     */
    use Singleton;

    /**
     * Holds the uploader data.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    private static $data;

    /**
     * Protected class constructor to prevent direct object creation.
     *
     * @since 1.0.0
     */
    protected function __construct() {}

    /**
     * Render file uploader component.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the arguments needed for rendering uploader component.
     * $args = [
     *      'type'        => (string)  The type of attachment allowed [ application, image, video, audio ].
     *      'id'          => (string)  The unique string slug used in attribute id in hidden input.
     *      'name'        => (string)  The unique string used in attribute name in hidden input.
     *      'value'       => (integer) The value of the control, contains the attachment id.
     *      'key_link'    => (string)  The data link parameter in hidden input.
     *      'extensions'  => (array)   The defined allowed extensions.
     *      'placeholder' => (string)  The placeholder of the uploader.
     * ]
     * @return void
     */
    public static function render( $args = [] ) {
        $valid_types          = [ 'application', 'image', 'video', 'audio' ];
        $is_valid_value       = ( isset( $args['value'] ) );
        $is_valid_type        = ( isset( $args['type'] ) && in_array( $args['type'], $valid_types ) );
        $is_valid_id          = ( isset( $args['id'] ) && ! empty( $args['id'] ) );
        $is_valid_name        = ( isset( $args['name'] ) && ! empty( $args['name'] ) );
        $is_valid_link        = ( isset( $args['key_link'] ) && ! empty( $args['key_link'] ) );
        $is_valid_extension   = ( isset( $args['extensions'] ) && ! empty( $args['extensions'] ) );
        $is_valid_placeholder = ( isset( $args['placeholder'] ) );
        if ( ! $is_valid_value || ! $is_valid_type || ! $is_valid_id || ! $is_valid_name || ! $is_valid_link || ! $is_valid_extension || ! $is_valid_placeholder ) {
            return;
        }

        self::$data = $args;
        return self::get_uploader_component();
    }

    /** 
     * Return the allowed attachment extension and each Multipurpose Internet
     * Mail Extension (MIME).
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private static function get_extensions() {
        $extensions = [
            'audio'      => [
                'mp3' => [ 'audio/mpeg3', 'audio/mpeg' ],
                'mpg' => [ 'audio/mpeg3', 'audio/mpeg' ],
                'm4a' => [ 'audio/m4a' ],
                'ogg' => [ 'audio/ogg' ],
                'wav' => [ 'audio/wav' ]
            ],
            'image'       => [
                'jpg'  => [ 'image/jpeg', 'image/pjpeg' ],
                'jpeg' => [ 'image/jpeg', 'image/pjpeg' ],
                'png'  => [ 'image/png' ],
                'gif'  => [ 'image/gif' ],
                'ico'  => [ 'image/x-icon' ],
                'svg'  => [ 'image/svg+xml' ],
                'webp' => [ 'image/webp' ]
            ],
            'video'       => [
                'mp4'  => [ 'video/mp4' ],
                'm4v'  => [ 'video/x-m4v' ],
                'mov'  => [ 'video/quicktime' ],
                'wmv'  => [ 'video/x-ms-wmv' ],
                'avi'  => [ 'video/avi' ],
                'mpg'  => [ 'video/mpeg' ],
                'ogv'  => [ 'video/ogg' ],
                '3gp'  => [ 'video/3gpp' ],
                '3g2'  => [ 'video/3gpp2' ],
                'webm' => [ 'video/webm' ],
                'mkv'  => [ 'video/x-matroska' ]
            ],
            'application' => [
                'pdf'  => [ 'application/pdf' ],
                'doc'  => [ 'application/msword' ],
                'psd'  => [ 'application/octet-stream' ],
                'odt'  => [ 'application/vnd.oasis.opendocument.text' ],
                'docx' => [ 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ],
                'ppt'  => [ 'application/mspowerpoint', 'application/powerpoint', 'application/vnd.ms-powerpoint', 'application/x-mspowerpoint' ],
                'pptx' => [ 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ],
                'pps'  => [ 'application/mspowerpoint', 'application/vnd.ms-powerpoint' ],
                'ppsx' => [ 'application/vnd.openxmlformats-officedocument.presentationml.slideshow' ],
                'xls'  => [ 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel' ],
                'xlsx' => [ 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' ]
            ]
        ];

        return $extensions;
    }

    /**
     * Return the json encoded Multipurpose Internet Mail Extension (MIME)
     * based on defined extensions.
     * 
     * @since 1.0.0
     *
     * @return json
     */
    private static function get_mimes() {
        $mimes = [];
        foreach ( self::$data['extensions'] as $extension ) {
            $source = self::get_extensions()[ self::$data['type'] ];
            if ( isset( $source[ $extension ] ) ) {
                foreach ( $source[ $extension ] as $mime ) {
                    if ( ! in_array( $mime, $mimes ) ) {
                        array_push( $mimes, $mime );
                    }
                }
            }
        }

        return json_encode( $mimes );
    }

    /**
     * Return the appropriate dashicon based on uploader type.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private static function get_icon() {
        $icons = [
            'audio'       => 'dashicons-format-audio',
            'image'       => 'dashicons-format-image',
            'video'       => 'dashicons-video-alt3',
            'application' => 'dashicons-media-default'
        ];

        return $icons[ self::$data['type'] ];
    }

    /**
     * Return the placeholder with appropriate attachment type.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private static function get_placeholder() {
        $placeholder = self::$data['placeholder'];
        if ( empty( $placeholder ) ) {
            $placeholder = 'No Selected '. ucfirst( self::$data['type'] );
        }

        return $placeholder;
    }

    /**
     * Return the converted bytes in to more human readable filesize format.
     * 
     * @since 1.0.0
     *
     * @param  integer  $bytes    The specified filesize in bytes.
     * @param  integer  $decimal  The specified number of decimal places.
     * @return string
     */
    private static function get_converted_filesize( $bytes, $decimal = 1 ) {
        $size   = [ 'B','KB','MB','GB','TB','PB','EB','ZB','YB' ];
        $factor = floor( ( strlen( $bytes ) - 1 ) / 3 );

        return sprintf("%.{$decimal}f", $bytes / pow( 1024, $factor ) ) . $size[ $factor ];
    }

    /**
     * Return the attachment meta data.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private static function get_attachment() {
        $attachment    = [];
        $attachment_id = self::$data['value'];
        $file          = Helper::get_file_meta( $attachment_id );
        
        if ( ! empty( $file ) ) {
            $attachment['filename']  = $file['filename'];
            $attachment['raw_name']  = $file['raw_name'];
            $attachment['extension'] = $file['extension'];
            $attachment['filesize']  = self::get_converted_filesize( filesize( $file['file'] ) );
            $attachment['type']      = self::get_attachment_type( $attachment['extension'] );
            $attachment['image']     = self::get_attachment_image();
            $attachment['url']       = wp_get_attachment_url( $attachment_id );
            $attachment['date']      = get_the_date( 'M j, Y', $attachment_id );
        }

        return $attachment;
    }

    /**
     * Return the attachment type based on file extension.
     * 
     * @since 1.0.0
     *
     * @param  string  $extension  The extension of the attachment.
     * @return string
     */
    private static function get_attachment_type( $extension ) {
        $file_type = '';
        if ( $extension ) {
            foreach ( self::get_extensions() as $type => $extensions ) {
                if ( in_array( $extension, array_keys( $extensions ) ) ) {
                    $file_type = $type;
                    break;
                }
            }
        }

        return $file_type;
    }

    /**
     * Return the attachment thumbnail or image url based on attachment type.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private static function get_attachment_image() {
        $image = wp_get_attachment_image_url( self::$data['value'], 'thumbnail', true );
        if ( self::$data['type'] === 'image' ) {
            $image = wp_get_attachment_image_url( self::$data['value'], 'medium' );
        }

        return $image;
    }

    /**
     * Checks if the current attachment is valid by checking on attachment's
     * file type and extension.
     * 
     * @since 1.0.0
     *
     * @return boolean
     */
    private static function is_valid_attachment() {
        $attachment = self::get_attachment();
        if ( ! empty( $attachment ) ) {
            $is_valid_type      = ( $attachment['type'] === self::$data['type'] );
            $is_valid_extension = ( in_array( $attachment['extension'], self::$data['extensions'] ) );
            if ( $is_valid_type && $is_valid_extension ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return the state of selector and thumbnail component.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private static function get_state() {
        return [
            'selector'  => ( self::is_valid_attachment() ? 'hidden' : 'visible' ),
            'thumbnail' => ( self::is_valid_attachment() ? 'visible' : 'hidden' )
        ];
    }

    /**
     * Return the necessary data source for thumbnail based on attachment type.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private static function get_source() {
        $data = [
            'url'       => '',
            'filename'  => '',
            'raw_name'  => '',
            'extension' => '',
            'filesize'  => '',
            'type'      => '',
            'image'     => '',
            'date'      => ''
        ];
        
        return ( self::is_valid_attachment() ? self::get_attachment() : $data );
    }
    
    /**
     * Return the uploader component.
     * 
     * @since 1.0.0
     *
     * @return HTMLElement
     */
    private static function get_uploader_component() {
        $source = self::get_source();
        ob_start();
        ?>
        <div class="hacu-uploader" data-type="<?php echo esc_attr( self::$data['type'] ); ?>">
            <?php
                // Input Hidden
                echo Helper::get_hidden_input([
                    'key_link'   => self::$data['key_link'],
                    'attributes' => [
                        'class'  => 'hacu-uploader__input',
                        'id'     => self::$data['id'],
                        'name'   => self::$data['name'],
                        'value'  => self::$data['value']
                    ]
                ]);
            ?>

            <div class="hacu-uploader__container">
                <div class="hacu-uploader__file-selector hacu-uploader__uploader" data-id="<?php echo esc_attr( self::$data['id'] ); ?>" data-type="<?php echo esc_attr( self::$data['type'] ); ?>" data-mimes="<?php echo esc_attr( self::get_mimes() ); ?>" data-state="<?php echo esc_attr( self::get_state()['selector'] ); ?>">
                    <i class="dashicons <?php echo esc_attr( self::get_icon() ); ?>"></i>
                    <p class="hacu-m-0"><?php echo esc_html( self::get_placeholder() ); ?></p>
                </div>

                <div class="hacu-uploader__thumbnail" data-state="<?php echo esc_attr( self::get_state()['thumbnail'] ); ?>">
                    <?php if ( self::$data['type'] === 'application' ): ?>
                        <div class="hacu-uploader__application-thumbnail">
                            <?php echo self::get_file_thumbnail_component( $source ); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ( self::$data['type'] === 'image' ): ?>
                        <div class="hacu-uploader__image-thumbnail">
                            <img class="hacu-uploader__image-thumbnail__image" src="<?php echo esc_attr( $source['image'] ); ?>" title="<?php echo esc_attr( $source['raw_name'] ); ?>"/>
                        </div>
                    <?php endif; ?>
                    <?php if ( self::$data['type'] === 'audio' ): ?>
                        <div class="hacu-uploader__audio-thumbnail">
                            <?php echo self::get_file_thumbnail_component( $source ); ?>
                            <audio class="hacu-media-player" id="<?php echo esc_attr( self::$data['id'] .'-media-player' ); ?>" src="<?php echo esc_attr( $source['url'] ); ?>"></audio>
                        </div>
                    <?php endif; ?>
                    <?php if ( self::$data['type'] === 'video' ): ?>
                        <div class="hacu-uploader__video-thumbnail">
                            <video class="hacu-media-player" id="<?php echo esc_attr( self::$data['id'] .'-media-player' ); ?>" src="<?php echo esc_attr( $source['url'] ); ?>" preload="true" style="width: 100%; height: 100%;"></video>
                        </div>
                    <?php endif; ?>
                    <div class="hacu-uploader__thumbnail__controls">
                        <button type="button" class="hacu-uploader__file-remove hacu-btn">Remove</button>
                        <button type="button" class="hacu-uploader__file-selector hacu-btn" data-id="<?php echo esc_attr( self::$data['id'] ); ?>" data-type="<?php echo esc_attr( self::$data['type'] ); ?>" data-mimes="<?php echo esc_attr( self::get_mimes() ); ?>">Change</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Return the file information thumbnail component.
     * 
     * @since 1.0.0
     *
     * @param  array  $args  Contains the necessary arguments needed for rendering file thumbnail.
     * $args = [
     *      'filename'  => (string) The filename of the attachment.
     *      'raw_name'  => (string) The raw file name of the attachment.
     *      'extension' => (string) The file extension of the attachment.
     *      'filesize'  => (string) The readable file size of attachment.
     *      'image'     => (string) The icon url source of the attachment.  
     *      'date'      => (string) The date attachment uploaded.
     * ];
     * @return void
     */
    private static function get_file_thumbnail_component( $args = [] ) {
        $filemeta = $args['filesize'] .' - '. $args['date'];
        $filename = $args['filename'];
        if ( strlen( $filename ) > 24 ) {
            $filename = mb_strimwidth( $args['raw_name'], 0, 22, '...'. $args['extension'] );
        }
        ob_start();
        ?>
        <div class="hacu-uploader__file-thumbnail" title="<?php echo esc_attr( $args['filename'] ); ?>">
            <div class="hacu-col__left">
                <img class="hacu-uploader__file-thumbnail__icon" src="<?php echo esc_attr( $args['image'] ); ?>"/>
            </div>
            <div class="hacu-col__right">
                <p class="hacu-uploader__file-thumbnail__filename">
                    <?php echo esc_html( $filename ); ?>
                </p>
                <p class="hacu-uploader__file-thumbnail__filemeta">
                    <?php echo esc_html( $filemeta ); ?>
                </p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}