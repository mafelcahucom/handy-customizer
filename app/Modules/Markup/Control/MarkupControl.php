<?php
/**
 * App > Modules > Markup > Control > Markup Control.
 *
 * @since   1.0.0
 *
 * @version 1.0.0
 * @author  Mafel John Cahucom
 * @package handy-customizer
 */

namespace Handy\Modules\Markup\Control;

defined( 'ABSPATH' ) || exit;

/**
 * The `MarkupControl` class contains the rendering
 * control's component and enqueueing resources.
 *
 * @since 1.0.0
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
     * Render Markup Control Content
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
