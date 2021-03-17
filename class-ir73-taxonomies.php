<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 5:34 PM
 *
 * @package IR73
 * @subpackage Customizer
 */

/**
 * Class IR73_Taxonomies
 */
class IR73_Taxonomies {

	/**
	 * @var string
	 */
	protected $taxonomy_name;

	/**
	 * @var array
	 */
	public $arguments = array();

	/**
	 * @var string|null
	 */
	protected $rewrite_slug = null;

	/**
	 * @var string|null
	 */
	protected $singular_label = null;

	/**
	 * @var string|null
	 */
	protected $plural_label = null;

	/**
	 * @var string|null
	 */
	protected $associated_object_type = null;

	/**
	 * IR73_Taxonomies constructor.
	 *
	 * @param $taxonomy_name
	 */
	public function __construct( $taxonomy_name ) {
		$this->taxonomy_name = $taxonomy_name;
	}

	/**
	 * @param null $singular_label
	 */
	public function set_singular_label( $singular_label ) {
		$this->singular_label = $singular_label;
		return $this;
	}

	/**
	 * @param null $plural_label
	 */
	public function set_plural_label( $plural_label ) {
		$this->plural_label = $plural_label;
		return $this;
	}

	/**
	 * @param string|array $associated_object_type
	 */
	public function set_associated_object_type( $associated_object_type ) {
		$this->associated_object_type = $associated_object_type;
		return $this;
	}

	/**
	 * @param string $rewrite_slug
	 */
	public function set_rewrite_slug( $rewrite_slug ) {
		$this->rewrite_slug = $rewrite_slug;
		return $this;
	}

	protected function prepare_args() {
		$default_args = array(
			'public'            => $this->arguments['public'] ?? true,
			'hierarchical'      => $this->arguments['hierarchical'] ?? true,
			'show_ui'           => $this->arguments['show_ui'] ?? true,
			'show_admin_column' => $this->arguments['show_admin_column'] ?? true,
			'query_var'         => $this->arguments['query_var'] ?? true,
			'show_in_rest'      => $this->arguments['show_in_rest'] ?? true, // To show in gutenberg.
			'show_in_menu'      => true,
		);

		if ( ! is_null( $this->rewrite_slug ) ) {
			$args['rewrite'] = array(
				'slug'       => $this->rewrite_slug,
				'with_front' => true,
			);
		}

		// If provided array contains anything other than the default args
		// Then, append it to the result.

		$map_args     = array_map( 'json_encode', $this->arguments );
		$map_new_args = array_map( 'json_encode', $default_args );
		$array_diff   = array_diff_assoc( $map_args, $map_new_args );
		$array_diff   = array_map( 'json_decode', $array_diff );

		if ( ! empty( $array_diff ) ) {
			$default_args = array_merge( $default_args, $array_diff );
		}

		return $default_args;
	}

	protected function prepare_labels() {
		if ( is_null( $this->singular_label ) ) {
			$this->singular_label = IR73_Helper::key_to_label( $this->taxonomy_name );
		}

		if ( is_null( $this->plural_label ) ) {
			$this->plural_label = $this->singular_label . 's';
		}

		$labels = array(
			'name'              => _x( $this->plural_label, 'taxonomy general name', IR73_TEXT_DOMAIN ),
			'singular_name'     => _x( $this->singular_label, 'taxonomy singular name', IR73_TEXT_DOMAIN ),
			'search_items'      => __( 'Search ' . $this->plural_label, IR73_TEXT_DOMAIN ),
			'all_items'         => __( 'All ' . $this->plural_label, IR73_TEXT_DOMAIN ),
			'parent_item'       => __( 'Parent ' . $this->singular_label, IR73_TEXT_DOMAIN ),
			'parent_item_colon' => __( 'Parent ' . $this->singular_label . ':', IR73_TEXT_DOMAIN ),
			'edit_item'         => __( 'Edit ' . $this->singular_label, IR73_TEXT_DOMAIN ),
			'update_item'       => __( 'Update ' . $this->singular_label, IR73_TEXT_DOMAIN ),
			'add_new_item'      => __( 'Add New ' . $this->singular_label, IR73_TEXT_DOMAIN ),
			'new_item_name'     => __( 'New ' . $this->singular_label . ' Name', IR73_TEXT_DOMAIN ),
			'menu_name'         => __( $this->plural_label, IR73_TEXT_DOMAIN ),
			'not_found'         => __( $this->singular_label . ' Not Found', IR73_TEXT_DOMAIN ),
			'no_terms'          => __( 'No ' . $this->plural_label, IR73_TEXT_DOMAIN ),
		);

		return $labels;
	}

	protected function register() {
		if ( is_null( $this->associated_object_type ) ) {
			return IR73_Helper::error_flash( 'Taxonomy', 'No Associated Object Specified !' );
		}

		$args           = $this->prepare_args();
		$args['labels'] = $this->prepare_labels();
		register_taxonomy( $this->taxonomy_name, $this->associated_object_type, $args );
	}

	public function do() {
		$this->register();
	}
}

// Don’t cry because it’s over, smile because it happened.
