<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:14 PM
 */

Class IR73_CPT
{
    /**
     * @var string
     */
    protected $post_name;

    /**
     * @var array
     */
    public $arguments = [];

    /**
     * @var array
     */
    public $extra_labels = [];

    /**
     * @var string|null
     */
    protected $post_singular_name = null;

    /**
     * @var string|null
     */
    protected $post_plural_name = null;

    /**
     * @var array
     */
    protected $supports = ['title', 'editor', 'thumbnail'];

    /**
     * @var string|null
     */
    protected $menu_icon = null;

    /**
     * IR73_CPT constructor.
     * @param $post_name
     */
    public function __construct($post_name)
    {
        $this->post_name = $post_name;
    }

    /**
     * @param string $post_singular_name
     */
    public function set_post_singular_name($post_singular_name)
    {
        $this->post_singular_name = $post_singular_name;
    }

    /**
     * @param string $post_plural_name
     */
    public function set_post_plural_name($post_plural_name)
    {
        $this->post_plural_name = $post_plural_name;
    }

    /**
     * @param string $menu_icon
     */
    public function set_menu_icon($menu_icon)
    {
        $this->menu_icon = $menu_icon;
    }

    /**
     * @param array $supports
     */
    public function set_supports(array $supports)
    {
        $this->supports = $supports;
    }

    /**
     * Preparing arguments for the CPT
     *
     * @return array
     */
    protected function prepare_args()
    {
        $default_args = [
            'public'                => $this->arguments['public'] ?? true,
            'has_archive'           => $this->arguments['has_archive'] ?? true,
            'show_ui'               => $this->arguments['show_ui'] ?? true,
            'show_in_menu'          => $this->arguments['show_in_menu'] ?? true,
            'menu_position'         => $this->arguments['menu_position'] ?? null,
            'show_in_nav_menus'     => $this->arguments['show_in_nav_menus'] ?? true,
            'show_in_admin_bar'     => $this->arguments['show_in_admin_bar'] ?? true,
            'show_in_rest'          => $this->arguments['show_in_rest'] ?? false,
        ];

        $default_args['supports'] = $this->supports;
        if (!empty($this->menu_icon)) { $default_args['menu_icon'] = $this->menu_icon; }

        // If provided array contains anything other than the default args
        // Then, append it to the result

        $map_args = array_map('json_encode', $this->arguments);
        $map_new_args = array_map('json_encode', $default_args);
        $array_diff = array_diff_assoc($map_args, $map_new_args);

        $array_diff = array_map(function($value) {
            return json_decode($value, true);
        }, $array_diff);

        if (!empty($array_diff)) {
            $default_args = array_merge($default_args, $array_diff);
        }

        return $default_args;
    }

    protected function prepare_labels()
    {
        if (is_null($this->post_singular_name)) {
            $this->post_singular_name = IR73_Helper::key_to_label($this->post_name);
        }

        if (is_null($this->post_plural_name)) {
            $this->post_plural_name = $this->post_singular_name . 's';
        }

        $labels = [
            'name'                  => _x($this->post_singular_name, 'post type general name', IR73_TEXT_DOMAIN),
            'singular_name'         => _x($this->post_singular_name, 'post type singular name', IR73_TEXT_DOMAIN),
            'menu_name'             => __($this->post_plural_name, IR73_TEXT_DOMAIN),
            'name_admin_bar'        => __($this->post_plural_name, IR73_TEXT_DOMAIN),
            'add_new'               => __('Add New', IR73_TEXT_DOMAIN),
            'add_new_item'          => __("Add New {$this->post_singular_name}", IR73_TEXT_DOMAIN),
            'edit'                  => __('Edit', IR73_TEXT_DOMAIN),
            'edit_item'             => __("Edit {$this->post_singular_name}", IR73_TEXT_DOMAIN),
            'new_item'              => __("New {$this->post_singular_name}", IR73_TEXT_DOMAIN),
            'all_items'             => __("All {$this->post_plural_name}", IR73_TEXT_DOMAIN),
            'view'                  => __("View", IR73_TEXT_DOMAIN),
            'view_item'             => __("View {$this->post_singular_name}", IR73_TEXT_DOMAIN),
            'search_items'          => __("Search {$this->post_plural_name}", IR73_TEXT_DOMAIN),
            'not_found'             => __("No {$this->post_plural_name} found", IR73_TEXT_DOMAIN),
            'not_found_in_trash'    => __("No {$this->post_plural_name} found in trash", IR73_TEXT_DOMAIN)
        ];

        if (!empty($this->extra_labels) && is_array($this->extra_labels)) {
            $labels = array_merge($labels, $this->extra_labels);
        }

        return $labels;
    }

    protected function register()
    {
        $args = $this->prepare_args();
        $args['labels'] = $this->prepare_labels();
        register_post_type($this->post_name, $args);
    }

    public function do()
    {
        $this->register();
    }
}

// Don’t cry because it’s over, smile because it happened.
