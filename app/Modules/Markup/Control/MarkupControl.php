<?php
namespace Handy\Modules\Markup\Control;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Markup.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class MarkupControl extends \WP_Customize_Control {

    /**
     * Holds HTML Markup.
     * 
     * @since 1.0.0
     *
     * @var string
     */
    public $html;

    /**
     * Render Markup Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-markup">
            <?php echo $this->html; ?>
        </div>
        <?php
    }
}