<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:14 PM
 *
 * @package Smellycat
 * @subpackage IR73 CPT
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * Undocumented class
 */
class IR73_CPT {

	/**
	 * Name of the Custom Post Type.
	 *
	 * @var string
	 */
	protected string $post_name;

	/**
	 * Post type arguments to pass to register_post_type().
	 *
	 * @var array
	 */
	public array $arguments = array();

	/**
	 * Post type labels.
	 *
	 * @var array
	 */
	public array $extra_labels = array();

	/**
	 * Post Type Singular Name.
	 *
	 * @var string|null
	 */
	protected $post_singular_name = null;

	/**
	 * Post Type Plural Name.
	 *
	 * @var string|null
	 */
	protected $post_plural_name = null;

	/**
	 * The support objects Post type is looking for.
	 *
	 * @var array
	 */
	protected array $supports = array( 'title', 'editor', 'thumbnail' );

	/**
	 * Menu Icon for the CPT.
	 *
	 * @var string|null
	 */
	protected $menu_icon = null;

	/**
	 * IR73_CPT constructor.
	 *
	 * @param string $post_name name of CPT.
	 */
	public function __construct( string $post_name ) {
		$this->post_name = $post_name;
	}

	/**
	 * Sets singular name of the Post Type.
	 *
	 * @param string $post_singular_name singular name for the CPT.
	 */
	public function set_post_singular_name( string $post_singular_name ): self {
		$this->post_singular_name = $post_singular_name;
		return $this;
	}

	/**
	 * Sets, plural name of the CPT if provided.
	 *
	 * @param string $post_plural_name post plural name.
	 */
	public function set_post_plural_name( string $post_plural_name ): self {
		$this->post_plural_name = $post_plural_name;
		return $this;
	}

	/**
	 * Sets the menu icon for the CPT.
	 *
	 * @param string $menu_icon icon class.
	 */
	public function set_menu_icon( string $menu_icon ): self {
		$this->menu_icon = $menu_icon;
		return $this;
	}

	/**
	 * Set supports needed for the CPT.
	 *
	 * @param array $supports array of supports object.
	 */
	public function set_supports( array $supports ): self {
		$this->supports = $supports;
		return $this;
	}

	/**
	 * Set Extra Post Type Arguments.
	 *
	 * @param array $args array of arguments.
	 * @return self
	 */
	public function set_arguments( array $args ): self {
		$this->arguments = $args;
		return $this;
	}

	/**
	 * Preparing arguments for the CPT
	 *
	 * @return array
	 */
	protected function prepare_args(): array {
		$default_args = array(
			'public'            => $this->arguments['public'] ?? true,
			'has_archive'       => $this->arguments['has_archive'] ?? true,
			'show_ui'           => $this->arguments['show_ui'] ?? true,
			'show_in_menu'      => $this->arguments['show_in_menu'] ?? true,
			'menu_position'     => $this->arguments['menu_position'] ?? null,
			'show_in_nav_menus' => $this->arguments['show_in_nav_menus'] ?? true,
			'show_in_admin_bar' => $this->arguments['show_in_admin_bar'] ?? true,
			'show_in_rest'      => $this->arguments['show_in_rest'] ?? false,
		);

		$default_args['supports'] = $this->supports;
		if ( ! empty( $this->menu_icon ) ) {
			$default_args['menu_icon'] = $this->menu_icon; }

		// If provided array contains anything other than the default args
		// Then, append it to the result!

		$map_args     = array_map( 'json_encode', $this->arguments );
		$map_new_args = array_map( 'json_encode', $default_args );
		$array_diff   = array_diff_assoc( $map_args, $map_new_args );

		$array_diff = array_map(
			function( $value ) {
				return json_decode( $value, true );
			},
			$array_diff
		);

		if ( ! empty( $array_diff ) ) {
			$default_args = array_merge( $default_args, $array_diff );
		}

		return $default_args;
	}

	/**
	 * Preparing CPT labels.
	 *
	 * @return array
	 */
	protected function prepare_labels(): array {
		if ( is_null( $this->post_singular_name ) ) {
			$this->post_singular_name = IR73_Helper::key_to_label( $this->post_name );
		}

		if ( is_null( $this->post_plural_name ) ) {
			$this->post_plural_name = $this->post_singular_name . 's';
		}

		$labels = array(
			'name'               => $this->post_singular_name,
			'singular_name'      => $this->post_singular_name,
			'menu_name'          => $this->post_plural_name,
			'name_admin_bar'     => $this->post_plural_name,
			'add_new'            => __( 'Add New', 'smellycat' ),
			// translators: %s: singular postname.
			'add_new_item'       => sprintf( __( 'Add New %s', 'smellycat' ), $this->post_singular_name ),
			'edit'               => __( 'Edit', 'smellycat' ),
			// translators: %s: singular postname.
			'edit_item'          => sprintf( __( 'Edit %s', 'smellycat' ), $this->post_singular_name ),
			// translators: %s: singular postname.
			'new_item'           => sprintf( __( 'New %s', 'smellycat' ), $this->post_singular_name ),
			// translators: %s: plural postname.
			'all_items'          => sprintf( __( 'All %s', 'smellycat' ), $this->post_plural_name ),
			'view'               => __( 'View', 'smellycat' ),
			// translators: %s: singular postname.
			'view_item'          => sprintf( __( 'View %s', 'smellycat' ), $this->post_singular_name ),
			// translators: %s: plural postname.
			'search_items'       => sprintf( __( 'Search %s', 'smellycat' ), $this->post_plural_name ),
			// translators: %s: plural postname.
			'not_found'          => sprintf( __( 'No %s found', 'smellycat' ), $this->post_plural_name ),
			// translators: %s: plural postname.
			'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'smellycat' ), $this->post_plural_name ),
		);

		if ( ! empty( $this->extra_labels ) && is_array( $this->extra_labels ) ) {
			$labels = array_merge( $labels, $this->extra_labels );
		}

		return $labels;
	}

	/**
	 * Register the CPT.
	 *
	 * @return void
	 */
	protected function register() {
		$args           = $this->prepare_args();
		$args['labels'] = $this->prepare_labels();
		register_post_type( $this->post_name, $args );
	}

	/**
	 * Call the register method finally. After every shit is done.
	 *
	 * @return void
	 */
	public function do() {
		$this->register();
	}
}

// Don’t cry because it’s over, smile because it happened.
