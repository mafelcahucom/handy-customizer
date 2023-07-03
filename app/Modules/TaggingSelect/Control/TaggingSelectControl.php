<?php
namespace Handy\Modules\TaggingSelect\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Tagging Select.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class TaggingSelectControl extends \WP_Customize_Control {

    /**
     * Holds the placeholder.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds the maximum tag items.
     * 
     * @since 1.0.0
     *
     * @var integer
     */
    public $maximum;

    /**
     * Holds the list of choices.
     *
     * @since 1.0.0
     * 
     * @var array
     */
    public $choices;

    /**
     * Enqueue styles and scripts.
     * 
     * @since 1.0.0
     */
    public function enqueue() {
        if ( ! wp_style_is( 'selectize-css', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'selectize/selectize.min.css' );
            $version = Helper::get_asset_version( 'selectize/selectize.min.css' );

            wp_enqueue_style( 'selectize-css', $source, [], $version );
        }

        if ( ! wp_script_is( 'selectize-js', 'enqueued' ) ) {
            $source  = Helper::get_asset_src( 'selectize/selectize.min.js' );
            $version = Helper::get_asset_version( 'selectize/selectize.min.js' );

            wp_enqueue_script( 'selectize-js', $source, [], $version, false );
        }
    }

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-tagging-select-'. $this->id;
    }

    /**
     * Return the imploded value.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function get_value() {
        $is_valid_value = ( ! empty( $this->value() ) && is_array( $this->value() ) );
        return ( $is_valid_value ? implode( ',', $this->value() ) : '' );
    }

    /**
     * Return the selected attribute if option's value is found in current value.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The option's value.
     * @return string
     */
    private function selected( $value ) {
        $is_valid_value = ( ! empty( $this->value() ) && is_array( $this->value() ) && in_array( $value, $this->value() ) );
        return ( $is_valid_value ? 'selected' : '' );
    }

    /**
     * Render Tagging Select Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-tagging-select">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);

                // Input Hidden
                echo Helper::get_hidden_input([
                    'key_link'   => $this->get_link(),
                    'attributes' => [
                        'class' => 'hacu-tagging-select__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->get_value()
                    ]
                ]);
            ?>

            <?php if ( ! empty( $this->choices ) ): ?>
                <select class="hacu-tagging-select__selectize" value="<?php echo esc_attr( $this->get_value() ); ?>" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" data-maximum="<?php echo esc_attr( $this->maximum ); ?>" multiple>
                    <?php foreach ( $this->choices as $key => $value ): ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php echo $this->selected( $key ); ?>>
                            <?php echo esc_html( $value ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
        <?php
    }
}