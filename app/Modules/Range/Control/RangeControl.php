<?php
namespace Handy\Modules\Range\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Range.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class RangeControl extends \WP_Customize_Control {

    /**
     * Holds the option settings.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    public $options;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-range-'. $this->id;
    }

    /**
     * Return the validated value.
     * 
     * @since 1.0.0
     *
     * @return double
     */
    private function get_value() {
        if ( ! is_numeric( $this->value() ) ) {
            return $this->options['min'];
        }

        $value = floatval( $this->value() );
        return ( $value >= $this->options['min'] && $value <= $this->options['max'] ? $value : $this->options['min'] );
    }

    /**
     * Render Range Control Content
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-range">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);
            ?>

            <div class="hacu-range__container">
                <div class="hacu-col__left">
                    <input type="range" class="hacu-range__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" min="<?php echo esc_attr( $this->options['min'] ); ?>" max="<?php echo esc_attr( $this->options['max'] ); ?>" step="<?php echo esc_attr( $this->options['step'] ); ?>" value="<?php echo esc_attr( $this->get_value() ); ?>" <?php $this->link(); ?> />
                </div>
                <div class="hacu-col__right">
                    <span class="hacu-range__output">
                        <?php echo esc_html( $this->get_value() ); ?>
                    </span>
                </div>
            </div>
        </div>
        <?php
    }
}