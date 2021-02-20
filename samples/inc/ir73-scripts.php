<?php
/**
 * Specify your stylesheets here.
 *
 * @package WP-Smellycat
 * @subpackage Enqueue Assets
 */

return array(

	'jquery'    => array( 'https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js', array(), '3.5.1' ),
	'popper'    => array( 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', array(), '1.12.9' ),
	'bootstrap' => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', array(), '4.0.0' ),
	'scripts'   => array( IR73_Helper::get_assets_url( 'js/scripts.js' ), array(), '1.0.1' ),

);
