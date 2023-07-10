<?php
namespace Handy\Modules\Dashicons\Field;

use Handy\Core\Setting;
use Handy\Inc\Helper;
use Handy\Inc\Validator;
use Handy\Modules\Dashicons\Control\DashiconsControl;

defined( 'ABSPATH' ) || exit;

/**
 * Field > Dashicons.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class DashiconsField extends Setting {

    /**
     * Return the all dashicons.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function get_icons( $type = 'detailed' ) {
        $icons = [
            'admin-menu' => [
                'menu', 'menu-alt', 'menu-alt2', 'menu-alt3', 'admin-site', 'admin-site-alt', 'admin-site-alt2', 'admin-site-alt3', 'dashboard', 'admin-post', 
                'admin-media', 'admin-links', 'admin-page', 'admin-comments', 'admin-appearance', 'admin-plugins', 'plugins-checked', 'admin-users', 'admin-tools', 
                'admin-settings', 'admin-network', 'admin-home', 'admin-generic', 'admin-collapse', 'filter', 'admin-customizer', 'admin-multisite' 
            ],
            'welcome-screen' => [ 
                'welcome-write-blog', 'welcome-add-page', 'welcome-view-site', 'welcome-widgets-menus', 'welcome-comments', 'welcome-learn-more' 
            ],
            'post-formats' => [ 
                'format-aside', 'format-image', 'format-gallery', 'format-video', 'format-status', 'format-quote', 'format-chat', 'format-audio', 'camera', 'camera-alt', 
                'images-alt', 'images-alt2', 'video-alt', 'video-alt2', 'video-alt3' 
            ],
            'media' => [ 
                'media-archive', 'media-audio', 'media-code', 'media-default', 'media-document', 'media-interactive', 'media-spreadsheet', 'media-text', 'media-video', 'playlist-audio', 
                'playlist-video', 'controls-play', 'controls-pause', 'controls-forward', 'controls-skipforward', 'controls-back', 'controls-skipback', 'controls-repeat', 'controls-volumeon', 'controls-volumeoff' 
            ],
            'image-editing' => [ 
                'image-crop', 'image-rotate', 'image-rotate-left', 'image-rotate-right', 'image-flip-vertical', 'image-flip-horizontal', 'image-filter', 'undo', 'redo' 
            ],
            'databases' => [
                'database-add', 'database', 'database-export', 'database-import', 'database-remove', 'database-view'
            ],
            'block-editor' => [
                'align-full-width', 'align-pull-left', 'align-pull-right', 'align-wide', 'block-default', 'button', 'cloud-saved', 'cloud-upload', 'columns', 'cover-image', 
                'ellipsis', 'embed-audio', 'embed-generic', 'embed-photo', 'embed-post', 'embed-video', 'exit', 'heading', 'html', 'info-outline', 'insert', 'insert-after',
                'insert-before', 'remove', 'saved', 'shortcode', 'table-col-after', 'table-col-before', 'table-col-delete', 'table-row-after', 'table-row-before', 'table-row-delete'
            ],
            'tinymce' => [ 
                'editor-bold', 'editor-italic', 'editor-ul', 'editor-ol', 'editor-ol-rtl', 'editor-quote', 'editor-alignleft', 'editor-aligncenter', 'editor-alignright', 'editor-insertmore', 
                'editor-spellcheck', 'editor-expand', 'editor-contract', 'editor-kitchensink', 'editor-underline', 'editor-justify', 'editor-textcolor', 'editor-paste-word', 'editor-paste-text', 
                'editor-removeformatting', 'editor-video', 'editor-customchar', 'editor-outdent', 'editor-indent', 'editor-help', 'editor-strikethrough', 'editor-unlink', 'editor-rtl', 'editor-ltr', 
                'editor-break', 'editor-code', 'editor-paragraph', 'editor-table' 
            ],
            'posts-screen' => [ 
                'align-left', 'align-right', 'align-center', 'align-none', 'lock', 'unlock', 'calendar', 'calendar-alt', 'visibility', 'hidden', 'post-status', 'edit', 'trash', 'sticky' 
            ],
            'sorting' => [ 
                'external', 'arrow-up', 'arrow-down', 'arrow-right', 'arrow-left', 'arrow-up-alt', 'arrow-down-alt', 'arrow-right-alt', 'arrow-left-alt', 'arrow-up-alt2', 
                'arrow-down-alt2', 'arrow-right-alt2', 'arrow-left-alt2', 'sort', 'leftright', 'randomize', 'list-view', 'exerpt-view', 'grid-view', 'move' 
            ],
            'social' => [ 
                'share', 'share-alt', 'share-alt2', 'twitter', 'rss', 'email', 'email-alt', 'email-alt2', 'facebook', 'facebook-alt', 'googleplus', 'networking', 'instagram' 
            ],
            'wordpress_org' =>  [ 
                'hammer', 'art', 'migrate', 'performance', 'universal-access', 'universal-access-alt', 'tickets', 'nametag', 'clipboard', 'heart', 'megaphone', 'schedule', 'tide', 'rest-api', 'code-standards' 
            ],
            'buddicons' => [
                'buddicons-activity', 'buddicons-bbpress-logo', 'buddicons-buddypress-logo', 'buddicons-community', 'buddicons-forums', 'buddicons-friends', 'buddicons-groups', 'buddicons-pm',
                'buddicons-replies', 'buddicons-topics', 'buddicons-tracking'
            ],
            'products' =>  [ 
                'wordpress', 'wordpress-alt', 'pressthis', 'update', 'update-alt', 'screenoptions', 'info', 'cart', 'feedback', 'cloud', 'translation' 
            ],
            'taxonomies' => [
                'tag', 'category'
            ],
            'widgets' => [
                'archive', 'tagcloud', 'text'
            ],
            'notifications' => [ 
                'yes', 'yes-alt', 'no', 'no-alt', 'plus', 'plus-alt', 'minus', 'dismiss', 'marker', 'star-filled', 'star-half', 'star-empty', 'flag', 'warning' 
            ],
            'miscellaneous' => [
                'location', 'location-alt', 'vault', 'shield', 'shield-alt', 'sos', 'search', 'slides', 'text-page', 'analytics', 'chart-pie', 'chart-bar', 'chart-line', 'chart-area', 'groups', 
                'businessman', 'businesswoman', 'businessperson', 'id', 'id-alt', 'products', 'awards', 'forms', 'testimonial', 'portfolio', 'book', 'book-alt', 'download', 'upload', 'backup', 
                'clock', 'lightbulb', 'microphone', 'desktop', 'laptop', 'tablet', 'smartphone', 'phone', 'index-card', 'carrot', 'building', 'store', 'album', 'palm-tree', 'tickets-alt', 'money', 
                'money-alt', 'smiley', 'thumbs-up', 'thumbs-down', 'layout', 'paperclip', 'color-picker', 'edit-large', 'edit-page', 'airplane', 'bank', 'beer', 'calculator', 'car', 'coffee', 'drumstick', 
                'food', 'fullscreen-alt', 'fullscreen-exit-alt', 'games', 'hourglass', 'open-folder', 'pdf', 'pets', 'printer', 'privacy', 'superhero', 'superhero-alt'
            ]
        ];

        if ( $type === 'icons' ) {
            $all_icons = [];
            foreach ( $icons as $icon ) {
                $all_icons = array_merge( $all_icons, $icon );
            }
            
            $icons = $all_icons;
        }

        return $icons;
    }

    /**
     * Return the validated default value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_default( $validated ) {
        return ( in_array( $validated['default'], $validated['choices'] ) ? $validated['default'] : '' );
    }

    /**
     * Return the validated choices value.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments.
     * @return string
     */
    private function get_validated_choices( $validated ) {
        $icons = $this->get_icons( 'icons' );
        if ( isset( $validated['choices'] ) ) {
            $intersected = Helper::get_intersected( $validated['choices'], $icons );
            $icons       = ( ! empty( $intersected ) ? $intersected : $icons );
        }

        return $icons;
    }

    /**
     * Return the predetermined default validations.
     * 
     * @since 1.0.0
     *
     * @param  array  $validated  Contains the validated arguments
     * @return string
     */
    private function get_default_validations( $validated ) {
        $parameters  = implode( ',', array_merge( $validated['choices'], [ '__' ] ) );
        $validation  = "in_choices[{$parameters}]";
        $validations = [ $validation ];
        if ( isset( $validated['validations'] ) ) {
            $validations = $validated['validations'];
            array_unshift( $validations, $validation );
        }

        return $validations;
    }

    /**
     * Render Dashicons Control.
     * 
     * @since 1.0.0
     *
     * @param  object  $customize  Contain the instance of WP_Customize_Manager.
     * @param  array   $args       Contains the arguments needed to render dashicons control.
     * $args = [
     *      'id'                => (string)  The unique slug like string to be used as an id.
     *      'section'           => (string)  The section where the control belongs to.
     *      'default'           => (string)  The default value of the control.
     *      'label'             => (string)  The label of the control.
     *      'description'       => (string)  The description of the control.
     *      'priority'          => (integer) The order of control appears in the section. 
     *      'validations'       => (array)   The list of built-in and custom validations.
     *      'active_callback'   => (object)  The callback function whether to show control, must always return true.
     *      'sanitize_callback' => (object)  The callback function to sanitize the value before saving in database.
     *      'choices'           => (array)   The list of choices.
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
                'type'     => 'string',
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
            'choices'           => [
                'type'     => 'array',
                'required' => false
            ]
        ];

        $validated = Validator::get_validated_argument( $schema, $args );
        if ( ! empty( $validated ) ) {
            $validated['icons']   = $this->get_icons( 'detailed' );
            $validated['choices'] = $this->get_validated_choices( $validated );

            if ( isset( $validated['default'] ) ) {
                $validated['default'] = $this->get_validated_default( $validated );
            }

            $validated['validations'] = $this->get_default_validations( $validated );
        }

        $config = Validator::get_configuration( 'field', $validated );
        if ( $validated && $config ) {
            $this->setting( 'dashicons', $customize, $validated );
            $customize->add_control( new DashiconsControl( $customize, $config['settings'], $config ) );
        }
    }
}