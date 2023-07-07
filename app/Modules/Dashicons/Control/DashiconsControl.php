<?php
namespace Handy\Modules\Dashicons\Control;

use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Dashicons.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class DashiconsControl extends \WP_Customize_Control {

    /**
     * Holds all dashicons.
     *
     * @var array
     */
    public $icons;

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
        return  'hacu-dashicons-'. $this->id;
    }

    /**
     * Return a flag whether to display all dashicons.
     * 
     * @since 1.0.0
     *
     * @return boolean
     */
    private function is_display_all() {
        return ( count( $this->choices ) === 325 ? true : false );
    }

    /**
     * Return the item state based on the current value and item value.
     * 
     * @since 1.0.0
     *
     * @param  string  $value  The item button value.
     * @return string
     */
    private function get_item_state( $value ) {
        return ( $this->value() === $value ? 'active' : 'default' );
    }

    /**
     * Return the icon label in proper format.
     * 
     * @since 1.0.0
     *
     * @param  string  $key  The key index of icon group.
     * @return string
     */
    private function get_icon_label( $key ) {
        return implode( ' ', array_map( 'ucfirst', explode( '-', $key ) ) );
    }

    /**
     * Return the icon list component.
     * 
     * @since 1.0.0
     *
     * @param  array  $icons  The list of icons.
     * @return HTMLElement
     */
    private function get_icon_list_component( $icons ) {
        ob_start();
        ?>
        <?php if ( ! empty( $icons ) ): ?>
        <div class="hacu-dashicons__list">
            <?php foreach ( $icons as $icon ): ?>
                <div class="hacu-dashicons__item" data-value="<?php echo esc_attr( $icon ); ?>" data-state="<?php echo $this->get_item_state( $icon ); ?>" title="<?php echo esc_attr( $this->get_icon_label( $icon ) ); ?>">
                    <i class="dashicons dashicons-<?php echo esc_attr( $icon ); ?>"></i>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Dashicons Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-dashicons">
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
                        'class' => 'hacu-dashicons__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => $this->value()
                    ]
                ]);
            ?>

            <div class="hacu-dashicons__container">
                <?php if ( $this->is_display_all() ): ?>
                    <?php foreach ( $this->icons as $key => $icons ): ?>
                        <h4 class="hacu-dashicons__group-label">
                            <?php echo esc_html( $this->get_icon_label( $key ) ); ?>
                        </h4>
                        <?php echo $this->get_icon_list_component( $icons ); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php echo $this->get_icon_list_component( $this->choices ); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}