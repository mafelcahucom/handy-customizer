<?php
namespace Handy\Modules\Sortable\Control;

use Handy\Inc\Icon;
use Handy\Inc\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Control > Sortable.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Mafel John Cahucom
 */
final class SortableControl extends \WP_Customize_Control {

    /**
     * Holds the list of choices.
     * 
     * @since 1.0.0
     *
     * @var array
     */
    public $choices;

    /**
     * Holds flag whether to enable handle.
     *
     * @var boolean
     */
    public $enable_handle;

    /**
     * Return the ID with prefix.
     * 
     * @since 1.0.0
     *
     * @return string
     */
    private function prefix_id() {
        return  'hacu-sortable-'. $this->id;
    }

    /**
     * Return the value in array format.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function get_value() {
        return ( ! empty( $this->value() ) && is_array( $this->value() ) ? $this->value() : [] );
    }
    
    /**
     * Return the choices items with correspoding order and status.
     * 
     * @since 1.0.0
     *
     * @return array
     */
    private function get_items() {
        $items = [];
        $intersected = array_intersect( $this->get_value(), array_keys( $this->choices ) );
        if ( ! empty( $intersected ) ) {
            foreach ( $intersected as $value ) {
                $items[ $value ] = [
                    'status' => 'enabled',
                    'title'  => $this->choices[ $value ] 
                ];
            }
        }

        $difference = array_diff( array_keys( $this->choices ), $intersected );
        if ( ! empty( $difference ) ) {
            $status = ( empty( $intersected ) ? 'enabled' : 'disabled' );
            foreach ( $difference as $value ) {
                $items[ $value ] = [
                    'status' => $status,
                    'title'  => $this->choices[ $value ] 
                ];
            }
        }

        return $items;
    }

    /**
     * Return appropriate draggable attribute value based on item' status.
     * 
     * @since 1.0.0
     *
     * @param  string  $status  The current item's status.
     * @return boolean
     */
    private function is_draggable( $status ) {
        return ( $status === 'enabled' ? 'true' : 'false' );
    }

    /**
     * Return the item control button's title attribute value based on item's status.
     * 
     * @since 1.0.0
     *
     * @param  string  $status  The current item's status.
     * @return string
     */
    private function get_control_title( $status ) {
        return ( $status === 'enabled' ? 'Disable' : 'Enable' );
    }

    /**
     * Render Sortable Control Content.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="hacu hacu-sortable">
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
                        'class' => 'hacu-sortable__input',
                        'id'    => $this->prefix_id(),
                        'name'  => $this->id,
                        'value' => Helper::get_imploded_value( $this->get_value(), array_keys( $this->choices ) )
                    ]
                ]);
            ?>

            <div class="hacu-sortable__container">
                <?php foreach ( $this->get_items() as $key => $item ): ?>
                    <div class="hacu-sortable__item" data-dragging="no" data-value="<?php echo esc_attr( $key ); ?>" data-state="<?php echo esc_attr( $item['status'] ); ?>" draggable="<?php echo $this->is_draggable( $item['status'] ); ?>">
                        <?php if ( $this->enable_handle ): ?>
                            <div class="hacu-sortable__item__handle">
                                <?php echo Icon::get( 'drag' ); ?>
                            </div>
                        <?php endif; ?>
                        <div class="hacu-sortable__item__title">
                            <span><?php echo esc_html( $item['title'] ); ?></span>
                        </div>
                        <div class="hacu-sortable__item__control">
                            <button type="button" class="hacu-sortable__item__toggle-btn" data-state="<?php echo esc_attr( $item['status'] ); ?>" title="<?php echo $this->get_control_title( $item['status'] ); ?>">
                                <?php
                                    echo Icon::get( 'eye-on', 'hacu-sortable__eye-on' );
                                    echo Icon::get( 'eye-off', 'hacu-sortable__eye-off' );
                                ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}