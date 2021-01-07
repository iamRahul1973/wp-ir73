<?php
/**
 * Created by VS Code.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 02-Dec-20
 * Time: 22:15
 */

class SortableSaveOrder {

	public array $objects;

	public function __construct( array $objects ) {
		$this->objects = $objects;
	}

	public function add_order_on_save() {
		if ( ! empty( $this->objects['cpt'] ) ) {
			$this->save_order_for_cpt( $this->objects['cpt'] );
		}

		if ( ! empty( $this->objects['taxonomy'] ) ) {
			$this->save_order_for_taxonomies( $this->objects['taxonomy'] );
		}
	}

	public function save_order_for_cpt( array $cpts ) {
	}

	public function save_order_for_taxonomies( array $taxonomies ) {
		foreach ( $taxonomies as $taxonomy ) {
			add_action( "created_{$taxonomy}", array( $this, 'add_term_meta', 10, 2 ) );
		}
	}

	public function add_term_meta( $term_id ) {
		exit( 'here' );
		add_term_meta( $term_id, 'ir73_order', '999', true );
	}
}
