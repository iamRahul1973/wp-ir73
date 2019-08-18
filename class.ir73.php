<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 7:30 PM
 */

class IR73
{
    /**
     * @var IR73_Config
     */
    protected $config;

    /**
     * IR73 constructor.
     */
    public function __construct()
    {
        $this->config = new IR73_Config();
        $this->check_n_enable_customizer();
        $this->check_n_activate_cpt();
        $this->check_n_activate_taxonomies();
    }

    /**
     * Enable and configure customizer options
     */
    private function check_n_enable_customizer()
    {
        if ($this->config->has_customizer == true) {
            add_action('customize_register', [$this->config, 'customizer_options']);
        }
    }

    private function check_n_activate_cpt()
    {
        if ($this->config->has_custom_post_types == true) {
            add_action('init', [$this->config, 'custom_post_types']);
        }
    }

    private function check_n_activate_taxonomies()
    {
        if ($this->config->has_custom_tax == true) {
            add_action('init', [$this->config, 'custom_taxonomies']);
        }
    }
}

// Don’t cry because it’s over, smile because it happened.
 