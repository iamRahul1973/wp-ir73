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
        $items = [
            ['id' => 'facebook'],
            ['id' => 'twitter'],
            ['id' => 'youtube'],
            ['id' => 'instagram']
        ];

        $social_links = new IR73_Customizer('social_links');
        $social_links->items = $items;
        $social_links->do();

        $items = [
            ['id' => 'theme_primary_color', 'type' => 'color'],
            ['id' => 'designer_logo', 'type' => 'image'],
            ['id' => 'banner_video', 'type' => 'upload']
        ];

        $custom_options = new IR73_Customizer('custom_options');
        $custom_options->items = $items;
        $custom_options->do();
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
        $cpt_one = new IR73_CPT('testimonial');
        $cpt_one->set_menu_icon('dashicons-editor-help');
        $cpt_one->set_supports(['title', 'thumbnail']);
        $cpt_one->arguments = ['taxonomies' => ['post_tag', 'category']];
        $cpt_one->do();

        $cpt_two = new IR73_CPT('news');
        $cpt_two->set_post_plural_name('News');
        $cpt_two->do();
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
        $news_tax = new IR73_Taxonomies('news-category');
        $news_tax->set_plural_label('News Categories');
        $news_tax->set_associated_object_type(['post', 'news']);
        $news_tax->set_rewrite_slug('columns');
        $news_tax->do();
    }
}

// Don’t cry because it’s over, smile because it happened.
 