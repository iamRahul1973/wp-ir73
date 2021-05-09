<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 5:34 PM
 *
 * @package Smellycat
 * @subpackage Customizer
 */

/**
 * Class IR73_Taxonomies
 */
class IR73_Taxonomies {

	/**
	 * Name of the taxonomy.
	 *
	 * @var string
	 */
	protected string $taxonomy_name;

	/**
	 * Arguments to create the taxonomy.
	 *
	 * @var array
	 */
	public array $arguments = array();

	/**
	 * Rewrite slug.
	 *
	 * @var string|null
	 */
	protected $rewrite_slug = null;

	/**
	 * Singular Label.
	 *
	 * @var string|null
	 */
	protected $singular_label = null;

	/**
	 * Plural Label for the taxonomy.
	 *
	 * @var string|null
	 */
	protected $plural_label = null;

	/**
	 * The post type to which we are adding the taxonomy to.
	 *
	 * @var string|null
	 */
	protected $associated_object_type = null;

	/**
	 * IR73_Taxonomies constructor.
	 *
	 * @param string $taxonomy_name name of taxonomy.
	 * @return void
	 */
	public function __construct( string $taxonomy_name ) {
		$this->taxonomy_name = $taxonomy_name;
	}

	/**
	 * Set Taxonomy's singular label.
	 *
	 * @param string|null $singular_label Singular Label.
	 * @return $this
	 */
	public function set_singular_label( string $singular_label ): self {
		$this->singular_label = $singular_label;
		return $this;
	}

	/**
	 * Set Taxonomy's plural label.
	 *
	 * @param string|null $plural_label Plural Label.
	 * @return $this
	 */
	public function set_plural_label( string $plural_label ): self {
		$this->plural_label = $plural_label;
		return $this;
	}

	/**
	 * Set Associated Post Type.
	 *
	 * @param string|array $associated_object_type Post Type.
	 * @return $this
	 */
	public function set_associated_object_type( $associated_object_type ): self {
		$this->associated_object_type = $associated_object_type;
		return $this;
	}

	/**
	 * Set Rewrite Slug
	 *
	 * @param string $rewrite_slug Rewrite Slug.
	 * @return $this
	 */
	public function set_rewrite_slug( string $rewrite_slug ): self {
		$this->rewrite_slug = $rewrite_slug;
		return $this;
	}

	/**
	 * Prepare Arguments for creating the raxonomy.
	 *
	 * @return array
	 */
	protected function prepare_args(): array {

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

	/**
	 * Prepare Taxonomy Labels
	 *
	 * @return array
	 */
	protected function prepare_labels(): array {

		if ( is_null( $this->singular_label ) ) {
			$this->singular_label = IR73_Helper::key_to_label( $this->taxonomy_name );
		}

		if ( is_null( $this->plural_label ) ) {
			$this->plural_label = $this->singular_label . 's';
		}

		$labels = array(
			'name'              => $this->plural_label,
			'singular_name'     => $this->singular_label,
			// translators: %s: Plural Label.
			'search_items'      => sprintf( __( 'Search %s', 'smellycat' ), $this->plural_label ),
			// translators: %s: Plural Label.
			'all_items'         => sprintf( __( 'All %s', 'smellycat' ), $this->plural_label ),
			// translators: %s: Singular Label.
			'parent_item'       => sprintf( __( 'Parent %s', 'smellycat' ), $this->singular_label ),
			// translators: %s: Singular Label.
			'parent_item_colon' => sprintf( __( 'Parent %s:', 'smellycat' ), $this->singular_label ),
			// translators: %s: Singular Label.
			'edit_item'         => sprintf( __( 'Edit %s', 'smellycat' ), $this->singular_label ),
			// translators: %s: Singular Label.
			'update_item'       => sprintf( __( 'Update %s', 'smellycat' ), $this->singular_label ),
			// translators: %s: Singular Label.
			'add_new_item'      => sprintf( __( 'Add New %s', 'smellycat' ), $this->singular_label ),
			// translators: %s: Singular Label.
			'new_item_name'     => sprintf( __( 'New %s Name', 'smellycat' ), $this->singular_label ),
			'menu_name'         => $this->plural_label,
			// translators: %s: Singular Label.
			'not_found'         => sprintf( __( '%s Not Found', 'smellycat' ), $this->singular_label ),
			// translators: %s: Plural Label.
			'no_terms'          => sprintf( __( 'No %s', 'smellycat' ), $this->plural_label ),
		);

		return $labels;

	}

	/**
	 * Register the taxonomy
	 *
	 * @return bool|void
	 */
	protected function register() {

		if ( is_null( $this->associated_object_type ) ) {
			// TODO: Throw custom exception here.
			return false;
		}

		$args           = $this->prepare_args();
		$args['labels'] = $this->prepare_labels();
		register_taxonomy( $this->taxonomy_name, $this->associated_object_type, $args );

	}

	/**
	 * Call the register function.
	 *
	 * @return void
	 */
	public function do() {
		$this->register();
	}
}

// Don’t cry because it’s over, smile because it happened.
