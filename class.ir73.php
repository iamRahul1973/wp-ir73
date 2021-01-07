<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 7:30 PM
 */

class IR73 {

	/**
	 * @var IR73_Config
	 */
	protected $config;

	/**
	 * IR73 constructor.
	 */
	public function __construct() {
		$this->config = new IR73_Config();
		$this->enqueue_assets();
		$this->check_n_enable_customizer();
		$this->check_n_activate_cpt();
		$this->check_n_activate_taxonomies();
		$this->check_n_activate_repeater_fields();
		$this->check_n_make_sortable();
	}

	private function enqueue_assets() {
		$this->config->assets();
	}

	/**
	 * Enable and configure customizer options
	 */
	private function check_n_enable_customizer() {
		if ( $this->config->has_customizer == true ) {
			add_action( 'customize_register', array( $this->config, 'customizer_options' ) );
		}
	}

	private function check_n_activate_cpt() {
		if ( $this->config->has_custom_post_types == true ) {
			add_action( 'init', array( $this->config, 'custom_post_types' ) );
		}
	}

	private function check_n_activate_taxonomies() {
		if ( $this->config->has_custom_tax == true ) {
			add_action( 'init', array( $this->config, 'custom_taxonomies' ) );
		}
	}

	private function check_n_activate_repeater_fields() {
		if ( $this->config->has_repeater_fields == true ) {

			$setup = new SetupRepeaters( $this->config->repeater_fields() );

			add_action( 'init', array( $setup, 'make_repeater_showable' ) );
			add_action( 'save_post', array( $setup, 'make_repeater_saveable' ) );

		}
	}

	private function check_n_make_sortable() {
		if ( $this->config->has_sortable_objects == true ) {

			$sortable = new SortableObjects( $this->config->sortable_objects() );

			add_action( 'admin_menu', array( $sortable, 'create_sortable_pages' ) );

			// Save Taxonomy Order after re-arrangement.
			add_action( 'admin_post_nopriv_ir73_reorder_tax', array( $sortable, 're_order_taxonomies' ) );
			add_action( 'admin_post_ir73_reorder_tax', array( $sortable, 're_order_taxonomies' ) );

			// Save CPT Order after re-arrangement.
			add_action( 'admin_post_nopriv_ir73_reorder_cpt', array( $sortable, 're_order_posts' ) );
			add_action( 'admin_post_ir73_reorder_cpt', array( $sortable, 're_order_posts' ) );

			// $save_order = new SortableSaveOrder( $this->config->sortable_objects() );
			// $save_order->add_order_on_save();

		}
	}

}

// Don’t cry because it’s over, smile because it happened.
