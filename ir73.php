<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:03 PM
 */

/*
Plugin Name: IR73
Plugin URI: https://iamrahul1973.github.io
Description: Custom WordPress extensions to make the life easier for creating Custom Post Types, Customizer Options, Meta Fields and Repeater Meta fields. The Plug-in is in Development stage and surely need to improve drastically.
Version: 1.1.0
Author: iamRahul1973
Author URI: https://iamrahul1973.github.io
License: GPLv2 or later
Text Domain: ir73
*/

define('IR73_VERSION', '1.1.0');
define('IR73_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('IR73_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('IR73_TEXT_DOMAIN', 'ir73');

require_once IR73_PLUGIN_DIR_PATH . 'class.ir73.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-helper.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-assets.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-customizer.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-cpt.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-taxonomies.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-metafields.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-repeater-fields.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-setup-repeaters.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-sortable-objects.php';
require_once IR73_PLUGIN_DIR_PATH . 'class.ir73-sortable-save-order.php';

// require customization files from the theme directory

if (!file_exists(get_theme_file_path('class.ir73-config.php'))) {
    // return IR73_Helper::error_flash('IR73', 'No Customizations found for your theme !');
    return false;
}

require_once get_theme_file_path('class.ir73-config.php');

new IR73();

// Don’t cry because it’s over, smile because it happened.
