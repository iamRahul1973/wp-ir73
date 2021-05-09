<?php
/**
 * Created by VS Code.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 02-Dec-20
 * Time: 22:15
 *
 * @package Smellycat
 * @subpackage Sortable Save Order
 */

/**
 * Sortable Objects - Save the Order, while initialization.
 * TODO: This bitch isn't completed. Need to finish this.
 */
class IR73_Sortable_Save_Order {

	/**
	 * The objects for which we are saving the order.
	 *
	 * @var array
	 */
	public array $objects;

	/**
	 * Constructor
	 *
	 * @param array $objects Objects which are going to be sorted.
	 */
	public function __construct( array $objects ) {
		$this->objects = $objects;
	}

	/**
	 * Save items with order meta on save.
	 *
	 * @return void
	 */
	public function add_order_on_save() {

		if ( ! empty( $this->objects['cpt'] ) ) {
			$this->save_order_for_cpt( $this->objects['cpt'] );
		}

		if ( ! empty( $this->objects['taxonomy'] ) ) {
			$this->save_order_for_taxonomies( $this->objects['taxonomy'] );
		}

	}

	/**
	 * Save order of the Posts
	 *
	 * @param array $cpts Posts.
	 * @return void
	 */
	public function save_order_for_cpt( array $cpts ) {}

	/**
	 * Save order for the taxonomies
	 *
	 * @param array $taxonomies Terms.
	 * @return void
	 */
	public function save_order_for_taxonomies( array $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			add_action( "created_{$taxonomy}", array( $this, 'add_term_meta', 10, 2 ) );
		}
	}

	/**
	 * Add Term Meta
	 *
	 * @param int $term_id term id.
	 * @return void
	 */
	public function add_term_meta( int $term_id ) {
		add_term_meta( $term_id, 'ir73_order', '999', true );
		exit( 'here' );
	}
}
