<?php
namespace Handy\Controls;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Counter.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class CounterControl extends \WP_Customize_Control {

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
        return  'hacu-counter-'. $this->id;
    }

    /**
     * Return the control button state based on value and option max and min.
     * 
     * @since 1.0.0
     *
     * @param  string  $event  The type of event [-,+].
     * @return string
     */
    private function get_control_state( $event ) {
        $value   = floatval( $this->value() );
        $options = $this->options;

        switch ( $event ) {
            case '-':
                if ( is_numeric( $options['min'] ) ) {
                    $min = floatval( $options['min'] );
                    if ( $value <= $min ) {
                        return 'disabled';
                    }
                }
                break;
            case '+':
                if ( is_numeric( $options['max'] ) ) {
                    $max = floatval( $options['max'] );
                    if ( $value >= $max ) {
                        return 'disabled';
                    }
                }
                break;
        }

        return 'default';
    }

    /**
     * Render Counter Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-counter">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);
            ?>

            <div class="hacu-counter__container">
                <button class="hacu-counter__control-btn" data-event="-" data-state="<?php echo $this->get_control_state( '-' ); ?>" title="Decrement">
                    <?php echo Icon::get( 'dash' ); ?>
                </button>
                <input type="text" class="hacu-counter__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" data-min="<?php echo esc_attr( $this->options['min'] ); ?>" data-max="<?php echo esc_attr( $this->options['max'] ); ?>" data-step="<?php echo esc_attr( $this->options['step'] ); ?>" <?php $this->link(); ?> />
                <button class="hacu-counter__control-btn" data-event="+" data-state="<?php echo $this->get_control_state( '+' ); ?>" title="Increment">
                    <?php echo Icon::get( 'plus' ); ?>
                </button>
            </div>
        </div>
        <?php
    }
}