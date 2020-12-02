<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:13 PM
 */

/**
 * Class IR73
 */
Class IR73_Helper
{
    public function __construct() { }

    /**
     * @param $key
     * @return string
     */
    static function key_to_label( $key ): string
    {
        return ucwords( str_replace( ['_', '-'], ' ', $key ) );
    }

    /**
     * @param $path string
     * @return string
     */
    static function get_assets_url( $path ): string
    {
        return get_template_directory_uri() . '/assets/' . $path;
    }

    /**
     * @param $path string
     * @return string
     */
    static function get_plugin_assets_url( $path ): string
    {
        return IR73_PLUGIN_DIR . 'assets/' . $path;
    }

    /**
     * @param $function_name string
     */
    static function get_ajax_support( $function_name )
    {
        add_action( 'wp_ajax_' . $function_name, $function_name );
        add_action( 'wp_ajax_nopriv_' . $function_name, $function_name );
    }

    /**
     * @param $value with newline
     * @return string
     */
    static function first_item_in_multiline_textarea( $value ) :string
    {
        return preg_split( '/\r\n|\r|\n/', $value )[0];
    }

    /**
     * Generate img markup with alt & title
     * attributes taken from wp_media
     *
     * @param int $thumbnail_id
     * @param array $class_names
     */
    static function draw_image( int $thumbnail_id, array $class_names = array(), $attributes = array() )
    {
        $image_alt    = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
        $image_title  = get_the_title( $thumbnail_id );
        $image_url    = wp_get_attachment_image_src( $thumbnail_id, 'full' );
        ?>
        <img src="<?php echo $image_url[0] ?>"
            alt="<?php echo $image_alt ?>"
            title="<?php echo $image_title ?>"
            class="<?php echo !empty( $class_names ) ? implode( ' ', $class_names ) : '' ?>"
            <?php
            if ( ! empty( $attributes ) ):
                foreach ( $attributes as $attribute => $value ):
                    echo $attribute . '="' . $value . '"';
                endforeach;
            endif;
            ?>>
        <?php
    }

    /**
     * NOTICE : THIS WILL PREPARE ONLY MENUS WITH 2 LEVELS.
     * MODIFY THIS TO CREATE MULTI LEVEL MENUS.
     */
    static function prepare_two_level_menu( $menu_name )
    {
        $menu_items = wp_get_nav_menu_items( $menu_name );

        if ( false === $menu_items ) { return false; }

        $prepared = [];

        foreach ( $menu_items as $submenu ) {
            if ( $submenu->menu_item_parent == 0 ) {
                $prepared[$submenu->ID] = ['object' => $submenu];
            } else {
                if ( empty( $menu[$submenu->menu_item_parent]['has_children'] ) ) {
                    $prepared[$submenu->menu_item_parent]['has_children'] = true;
                }
                $prepared[$submenu->menu_item_parent]['children'][] = $submenu;
            }
        }

        return $prepared;
    }
}

// Don’t cry because it’s over, smile because it happened.
