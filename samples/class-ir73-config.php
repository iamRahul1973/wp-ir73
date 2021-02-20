<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 4:05 PM
 *
 * @package IR73
 */

/**
 * IR73 Configuration Class
 */
class IR73_Config {

	/**
	 * Whether the theme uses WordPress Custommizer.
	 *
	 * @var bool
	 */
	public $has_customizer = true;

	/**
	 * Whether the theme has Custom Post Types.
	 *
	 * @var bool
	 */
	public $has_custom_post_types = true;

	/**
	 * Whether the theme has any Custom Taxonomies.
	 *
	 * @var bool
	 */
	public $has_custom_tax = false;

	/**
	 * Whether the theme uses IR73 Repeater Fields.
	 *
	 * @var bool
	 */
	public $has_repeater_fields = true;

	/**
	 * Whether the theme uses IR73 Sortable feature.
	 *
	 * @var boolean
	 */
	public $has_sortable_objects = true;

	/**
	 * --------------------------------------------------
	 * LOADING THEME'S ASSETS
	 * --------------------------------------------------
	 *
	 * Configure all your theme's stylesheets in inc/
	 * ir73_stylesheets.php file and all your scripts
	 * in ir73_scripts.php
	 *
	 * --------------------------------------------------
	 */
	public function assets() {

		$stylesheets = include 'inc/ir73-stylesheets.php';
		$scripts     = include 'inc/ir73-scripts.php';

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
	public function customizer_options() {

		$social_links = new IR73_Customizer( 'social_links' );
		$social_links->set_items(
			array(
				array( 'id' => 'facebook' ),
				array( 'id' => 'twitter' ),
				array( 'id' => 'youtube' ),
				array( 'id' => 'instagram' ),
				array( 'id' => 'linkedin' ),
				array( 'id' => 'whatsapp' ),
			)
		)->do();

		$contact_details = new IR73_Customizer( 'contact_details' );
		$contact_details->set_items(
			array(
				array(
					'id'   => 'address',
					'type' => 'textarea',
				),
				array(
					'id'   => 'phone',
					'type' => 'text',
				),
				array(
					'id'   => 'phone_alt',
					'type' => 'text',
				),
				array(
					'id'   => 'email',
					'type' => 'text',
				),
				array(
					'id'   => 'email_alt',
					'type' => 'text',
				),
				array(
					'id'   => 'map_source',
					'type' => 'textarea',
				),
			)
		)->do();

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
	public function custom_post_types() {

		$initiatives = new IR73_CPT( 'testimonials' );

		$initiatives->set_post_plural_name( 'Testimonials' )
			->set_supports(
				array(
					'title',
					'thumbnail',
					'editor',
					'excerpt',
				)
			)->set_arguments(
				array(
					'rewrite' => array(
						'slug'       => 'insights',
						'with_front' => true,
					),
				)
			)->set_menu_icon( 'dashicons-microphone' )->do();

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
	public function custom_taxonomies() {
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
	 * @return array
	 *
	 * --------------------------------------------------
	 */
	public function repeater_fields(): array {
		$repeaters = include 'inc/ir73-repeaters.php';
		return $repeaters;
	}

	/**
	 * --------------------------------------------------
	 * DRAG & RE-ORDER SUPPORT FOR OBJECTS
	 * --------------------------------------------------
	 *
	 * Pass in the taxonomies & CPTs that you wanna make
	 * sortable.
	 *
	 * @return array
	 *
	 * --------------------------------------------------
	 */
	public function sortable_objects(): array {
		return array(
			'cpt'      => array( 'faq', 'partner' ),
			'taxonomy' => array(),
		);
	}

}

// Don’t cry because it’s over, smile because it happened.
