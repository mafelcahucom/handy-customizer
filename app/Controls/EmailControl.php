<?php
namespace Handy\Controls;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Controls > Email.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class EmailControl extends \WP_Customize_Control {

    /**
     * Holds placeholder.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $placeholder;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-email-'. $this->id;
    }

    /**
     * Render Email Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-email">
            <?php
                // Label & Description.
                echo Helper::get_control_title([
                    'class'       => 'hacu-ds-block',
                    'id'          => $this->prefix_id(),
                    'label'       => $this->label,
                    'description' => $this->description
                ]);
            ?>

            <input 
                type="email" 
                class="hacu-email__input" 
                id="<?php echo esc_attr( $this->prefix_id() ); ?>"
                name="<?php echo esc_attr( $this->id ); ?>"
                value="<?php echo esc_attr( $this->value() ); ?>"
                placeholder="<?php echo esc_attr( $this->placeholder ); ?>"
                <?php $this->link(); ?>
            />
        </div>
        <?php
    }
}