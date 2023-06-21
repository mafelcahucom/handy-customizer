<?php
namespace Handy\Controls;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Color Set.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class ColorSetControl extends \WP_Customize_Control {

    /**
     * Holds the default value.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $default;

    /**
     * Holds the list of colors.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    public $colors;

    /**
     * Holds the shape.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $shape;

    /**
     * Holds the size.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $size;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-color-set-'. $this->id;
    }

    /**
     * Return the value with alternative value.
     * 
     * @sinc 1.0.0
     *
     * @return string
     */
    private function get_value() {
        return ( ! empty( $this->value() ) ? $this->value() : '#ffffff' );
    }

    /**
     * Return the item state based on the current color and item color.
     * 
     * @since 1.0.0
     *
     * @param  string  $color  The item color.
     * @return string
     */
    private function get_item_state( $color ) {
        return ( $this->value() === $color ? 'active' : 'default' );
    }

    /**
     * Render Color Set Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-color-set">
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
                        'class' => 'hacu-color-set__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>

            <div class="hacu-accordion">
                <div class="hacu-accordion__head" data-state="closed">
                    <div class="hacu-accordion__title">
                        <div class="hacu-flex hacu-flex-ai-c">
                            <div class="hacu-color-set__preview" data-shape="<?php echo esc_attr( $this->shape ); ?>" style="background-color: <?php echo esc_attr( $this->get_value() ); ?>"></div>
                            <span class="hacu-color-set__label">
                                <?php echo esc_attr( $this->value() ); ?>
                            </span>
                        </div>
                    </div>
                    <div class="hacu-accordion__control">
                        <?php echo Icon::get( 'caret-down' ); ?>
                    </div>
                </div>
                <div class="hacu-accordion__body" data-state="closed">
                    <div class="hacu-accordion__content">
                        <div class="hacu-accordion__main">
                            <div class="hacu-color-set__container" data-shape="<?php echo esc_attr( $this->shape ); ?>">
                                <?php foreach ( $this->colors as $color ): ?>
                                    <div class="hacu-color-set__item" data-value="<?php echo esc_attr( $color ); ?>" data-shape="<?php echo esc_attr( $this->shape ); ?>" data-state="<?php echo $this->get_item_state( $color ); ?>" style="width: <?php echo esc_attr( $this->size ); ?>; height: <?php echo esc_attr( $this->size ); ?>">
                                        <div class="hacu-color-set__color" style="background-color: <?php echo esc_attr( $color ); ?>"></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php if ( ! empty( $this->default ) ): ?>
                            <div class="hacu-accordion__footer">
                                <button type="button" class="hacu-color-set__default-btn hacu-btn" data-value="<?php echo esc_attr( $this->default ); ?>">Default</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}