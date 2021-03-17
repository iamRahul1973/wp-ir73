<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:03 PM
 *
 * @package Smellycat
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/*
Plugin Name: WP-IR73
Plugin URI: https://iamrahul1973.github.io
Description: Custom WordPress extensions to make the life easier for creating Custom Post Types, Customizer Options, Meta Fields and Repeater Meta fields. This plugin works with Smellycat Theme.
Version: 1.1.0
Author: iamRahul1973
Author URI: https://iamrahul1973.github.io
License: GPLv2 or later
Text Domain: ir73
*/

define( 'IR73_VERSION', '1.1.0' );
define( 'IR73_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
define( 'IR73_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

require_once IR73_PLUGIN_DIR_PATH . 'vendor/autoload.php';

require_once IR73_PLUGIN_DIR_PATH . 'class-ir73.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-helper.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-assets.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-customizer.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-cpt.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-taxonomies.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-repeater-fields.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-setup-repeaters.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-sortable-objects.php';
require_once IR73_PLUGIN_DIR_PATH . 'class-ir73-sortable-save-order.php';

// Check for active theme.
if ( wp_get_theme()->get( 'TextDomain' ) !== 'smellycat' ) {
	add_action(
		'admin_notices',
		function() {
			IR73_Helper::spit_admin_notice(
				'warning',
				'Plugin WP-IR73 Works with the Smellycat theme only. You better remove it.'
			);
		}
	);
	return false;
}

// Check for config file in theme.
if ( ! file_exists( get_theme_file_path( 'class-smellycat-config.php' ) ) ) {
	add_action(
		'admin_notices',
		function() {
			IR73_Helper::spit_admin_notice(
				'warning',
				'Theme configuration missing !'
			);
		}
	);
	return false;
}

require_once get_theme_file_path( 'class-smellycat-config.php' );

new IR73();

// Don’t cry because it’s over, smile because it happened.
