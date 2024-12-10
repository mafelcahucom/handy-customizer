<?php
/**
 * App > Modules > Dropdown Page > Control > Dropdown Page Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\DropdownPage\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * The `DropdownPageControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
 */
final class DropdownPageControl extends \WP_Customize_Control {

    /**
     * Holds the list of choices.
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $choices;

    /**
     * Return the ID with prefix.
     *
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return 'hacu-dropdown-page-' . $this->id;
    }

    /**
     * Render Dropdown Page Control Content
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-dropdown-page">
            <?php
                echo Helper::get_control_title(array(
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description,
                ));
            ?>

            <select class="hacu-dropdown-page__input" id="<?php echo esc_attr( $this->prefix_id() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> >
                <?php foreach ( $this->choices as $key => $value ) : ?>
                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $this->value() ); ?>>
                        <?php echo esc_html( $value ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
    }
}
