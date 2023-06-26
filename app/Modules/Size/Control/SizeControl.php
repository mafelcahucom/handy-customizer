<?php
namespace Handy\Modules\Size\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Size.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class SizeControl extends \WP_Customize_Control {

    /**
     * Holds placeholder.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Holds the list of unit size.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    public $units;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-size-'. $this->id;
    }

    /**
     * Return the value in array format [0] - number, [1] - size.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function get_value() {
        $value = [ '', $this->units[0] ];
        if ( ! empty( $this->value() ) && is_string( $this->value() ) ) {
            $splited = preg_split( '/(?<=[0-9])(?=[a-z%]+)/i', $this->value() );
            if ( count( $splited ) === 2 ) {
                if ( is_numeric( $splited[0] ) && is_string( $splited[1] ) ) {
                    $value = [ $splited[0], $splited[1] ];
                }
            }
        }
        
        return $value;
    }

    /**
     * Return the list "li" element state.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The list assigned value.
     * @return string
     */
    private function get_list_state( $value ) {
        return ( $this->get_value()[1] === $value ? 'active' : 'default' );
    }

    /**
     * Render Size Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-size">
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
                        'class'     => 'hacu-size__input',
                        'id'        => $this->prefix_id(),
                        'name'      => $this->id,
                        'value'     => $this->value(),
                        'data-size' => $this->get_value()[0],
                        'data-unit' => $this->get_value()[1]
                    ]
                ]);
            ?>

            <div class="hacu-size__container">
                <div class="hacu-size__col-left">
                    <input type="text" class="hacu-size__number" placeholder="<?php echo esc_attr( $this->placeholder ); ?>" value="<?php echo esc_attr( $this->get_value()[0] ); ?>" />
                </div>
                <div class="hacu-size__col-right">
                    <button class="hacu-size__dropdown-btn" data-state="closed" title="Open">
                        <?php echo esc_html( $this->get_value()[1] ); ?>
                    </button>
                    <ul class="hacu-size__dropdown" data-state="closed">
                        <?php foreach ( $this->units as $value ): ?>
                            <li class="hacu-size__dropdown__li" data-value="<?php echo esc_attr( $value ) ?>" data-state="<?php echo $this->get_list_state( $value ); ?>">
                                <?php echo esc_html( $value ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
}