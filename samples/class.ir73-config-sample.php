<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 4:05 PM
 */

class IR73_Config
{
    /**
     * @var bool
     */
    public $has_customizer = true;

    /**
     * @var bool
     */
    public $has_custom_post_types = true;

    /**
     * @var bool
     */
    public $has_custom_tax = true;

    /**
     * @var bool
     */
    public $has_repeater_fields = true;

    /**
     * --------------------------------------------------
     * LOADING THEME'S ASSETS
     * --------------------------------------------------
     *
     * configure all your theme's stylesheets in inc/
     * ir73_stylesheets.php file and all your scripts
     * in ir73_scripts.php
     *
     * --------------------------------------------------
     */
    public function assets()
    {
        
        $stylesheets = include 'inc/ir73_stylesheets.php';
        $scripts = include 'inc/ir73_scripts.php';

        $assets = new IR73_Assets( $stylesheets, $scripts );
        $assets->enqueue();
        
    }

    /**
     * --------------------------------------------------
     * CUSTOMIZER OPTIONS
     * --------------------------------------------------
     *
     * You should initialize your Customizer options here.
     * Also remember to turn the @has_customizer param to
     * true before configuring your options
     *
     * --------------------------------------------------
     */
    public function customizer_options()
    {

        /*
        $items = array(

            array( 'id' => 'facebook' ),
            array( 'id' => 'twitter' ),
            array( 'id' => 'youtube' ),
            array( 'id' => 'instagram' ),
            array( 'id' => 'google_plus' )

        );

        $social_links = new IR73_Customizer('social_links');
        $social_links->items = $items;
        $social_links->do();

        $items = array(

            array( 'id' => 'address', 'type' => 'textarea' ),
            array( 'id' => 'phone_number', 'type' => 'text' ),
            array( 'id' => 'email_id', 'type' => 'text' )
            
        );

        $contact_details = new IR73_Customizer('contact_details');
        $contact_details->items = $items;
        $contact_details->do();
        */

    }

    /**
     * --------------------------------------------------
     * CUSTOM POST TYPES
     * --------------------------------------------------
     *
     * Configure all your custom post types and it's
     * options here. Also, turn the @has_custom_post_types
     * param to true.
     *
     * --------------------------------------------------
     */
    public function custom_post_types()
    {

        /*
        $food = new IR73_CPT( 'food' );
        $food->set_menu_icon( 'dashicons-carrot' );
        $food->do();
        */

    }

    /**
     * --------------------------------------------------
     * CUSTOM TAXONOMIES
     * --------------------------------------------------
     *
     * Configure all our custom taxonomies and it's
     * options below. Remember to turn the @has_custom_tax
     * property to true to render the below configurations
     *
     * --------------------------------------------------
     */
    public function custom_taxonomies()
    {

        /*
        $food_category = new IR73_Taxonomies( 'food-category' );
        $food_category->set_plural_label( 'Food Categories' );
        $food_category->set_associated_object_type( 'food' );
        $food_category->set_rewrite_slug( 'food-menu' );
        $food_category->do();

        $food_group = new IR73_Taxonomies( 'food-group' );
        $food_group->set_associated_object_type( 'food' );
        $food_group->do();
        */

    }

    /**
     * --------------------------------------------------
     * CUSTOM REPEATER FIELDS
     * --------------------------------------------------
     *
     * Pass in your repeater field configuration array
     * in here. The default location is, inc/ir73_repeaters
     * inside your theme directory. But you can place it
     * wherever you want to. All we need is a WELL-FORMED
     * piece of array
     *
     * --------------------------------------------------
     */
    public function repeater_fields()
    {
        /*
        $repeaters = include 'inc/ir73_repeaters.php';
        return $repeaters;
        */
    }

}

// Don’t cry because it’s over, smile because it happened.
