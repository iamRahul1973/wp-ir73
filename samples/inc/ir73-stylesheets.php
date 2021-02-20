<?php
/**
 * Specify your stylesheets here.
 *
 * @package WP-Smellycat
 * @subpackage Enqueue Assets
 */

return array(

	'bootstrap'     => array( 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', array(), '4.0.0' ),
	'font-awesome'  => array( 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css', array(), '4.7.0' ),
	'custom'        => array( IR73_Helper::get_assets_url( 'css/custom.css' ), array(), '1.0.1' ),

	// Google Fonts.
	'gfont-poppins' => 'https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap',

);
