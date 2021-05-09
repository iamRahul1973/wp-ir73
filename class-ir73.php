<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 7:30 PM
 *
 * @package Smellycat
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * Smellycat IR73
 */
class IR73 {

	/**
	 * Theme configuration object.
	 *
	 * @var Smellycat_Config
	 */
	protected Smellycat_Config $config;

	/**
	 * IR73 constructor.
	 */
	public function __construct() {
		$this->config = new Smellycat_Config();
		$this->enqueue_assets();
		$this->check_n_enable_customizer();
		$this->check_n_activate_cpt();
		$this->check_n_activate_taxonomies();
		$this->check_n_activate_repeater_fields();
		$this->check_n_make_sortable();
		$this->other_customizations();
	}

	/**
	 * Enqueue Assets
	 *
	 * @return void
	 */
	private function enqueue_assets() {
		$this->config->assets();
	}

	/**
	 * Enable and configure customizer options
	 *
	 * @return void
	 */
	private function check_n_enable_customizer() {
		if ( true === $this->config->has_customizer ) {
			add_action( 'customize_register', array( $this->config, 'customizer_options' ) );
		}
	}

	/**
	 * If CPT is enabled, activate 'em.
	 *
	 * @return void
	 */
	private function check_n_activate_cpt() {
		if ( true === $this->config->has_custom_post_types ) {
			add_action( 'init', array( $this->config, 'custom_post_types' ) );
		}
	}

	/**
	 * If Taxonomy support is enabled, activate 'em.
	 *
	 * @return void
	 */
	private function check_n_activate_taxonomies() {
		if ( true === $this->config->has_custom_tax ) {
			add_action( 'init', array( $this->config, 'custom_taxonomies' ) );
		}
	}

	/**
	 * If Repeater support is enabled, activate 'em.
	 *
	 * @return void
	 */
	private function check_n_activate_repeater_fields() {
		if ( true === $this->config->has_repeater_fields ) {
			$setup = new IR73_Setup_Repeaters( $this->config->repeater_fields() );
			add_action( 'init', array( $setup, 'make_repeater_showable' ) );
			add_action( 'save_post', array( $setup, 'make_repeater_saveable' ) );
		}
	}

	/**
	 * If Sortable support is enabled, activate 'em.
	 *
	 * @return void
	 */
	private function check_n_make_sortable() {
		if ( true === $this->config->has_sortable_objects ) {
			$sortable = new IR73_Sortable_Objects( $this->config->sortable_objects() );
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

	/**
	 * Define other customizations here.
	 *
	 * @return void
	 */
	private function other_customizations() {
		// Custom CF7 CSS.
		add_action( 'admin_head', array( 'IR73_Helper', 'add_custom_cf7_styles' ) );
	}

}

// Don’t cry because it’s over, smile because it happened.
