<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 07-Jan-20
 * Time: 8:39 AM
 *
 * @package Smellycat
 * @subpackage IR73 Assets
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * THEME ASSETS
 */
class IR73_Assets {

	/**
	 * Theme verision number
	 *
	 * @var string
	 */
	protected string $theme_version;

	/**
	 * CSS Files to enqueue
	 *
	 * @var array
	 */
	protected array $stylesheets;

	/**
	 * Scripts to enqueue
	 *
	 * @var array
	 */
	protected array $scripts;

	/**
	 * Constructor
	 *
	 * @param array $stylesheets stylesheets to enqueue.
	 * @param array $scripts scripts to enqueue.
	 */
	public function __construct( array $stylesheets, array $scripts ) {
		$this->theme_version = wp_get_theme()->get( 'Version' );
		$this->stylesheets   = $stylesheets;
		$this->scripts       = $scripts;
	}

	/**
	 * Register & enqueue user defined stylesheets
	 *
	 * @return void
	 */
	protected function register_styles() {
		// Default Theme Stylesheet.
		wp_enqueue_style( 'ir73-style', get_stylesheet_uri(), array(), "{$this->theme_version}" );

		// Enqueue the rest.
		foreach ( $this->stylesheets as $key => $stylesheet ) {
			if ( is_array( $stylesheet ) ) {
				wp_register_style(
					$key,
					$stylesheet[0],
					$stylesheet[1] ?? array(),
					$stylesheet[2] ?? "{$this->theme_version}"
				);
			} else {
				wp_register_style( $key, $stylesheet, array(), $this->theme_version );
			}
			// Now enqueue.
			wp_enqueue_style( $key );
		}
	}

	/**
	 * Register & enqueue user defined scripts
	 *
	 * @return void
	 */
	protected function register_scripts() {
		// Enqueue the rest.
		foreach ( $this->scripts as $key => $script ) {
			if ( is_array( $script ) ) {
				wp_register_script(
					$key,
					$script[0],
					$script[1] ?? array(),
					$script[2] ?? "{$this->theme_version}",
					$script[3] ?? true,
				);
			} else {
				wp_register_script( $key, $script, array(), $this->theme_version, true );
			}
			// Now enqueue.
			wp_enqueue_script( $key );
		}
	}

	/**
	 * Admin Assets.
	 *
	 * @return void
	 */
	public function load_admin_assets() {
		// Bootstrap Grid.
		wp_register_style( 'bootstrap-grid', IR73_Helper::get_plugin_assets_url( 'css/bootstrap-grid.min.css' ), array(), '4.1.3' );
		wp_enqueue_style( 'bootstrap-grid' );
		// Custom Admin CSS.
		wp_register_style( 'custom-admin-css', IR73_Helper::get_plugin_assets_url( 'css/ir73.css' ), array(), '1.0' );
		wp_enqueue_style( 'custom-admin-css' );
		// jQuery UI.
		wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), '1.12.1' );
		wp_enqueue_style( 'jquery-ui' );
		// Mustache JS.
		wp_register_script( 'mustache-js', IR73_Helper::get_plugin_assets_url( 'js/mustache.min.js' ), array(), '3.1.0', true );
		wp_enqueue_script( 'mustache-js' );
		// Bootstrap JS.
		wp_register_script( 'bootstrap-js', IR73_Helper::get_plugin_assets_url( 'js/bootstrap.js' ), array(), '4.4.1', true );
		wp_enqueue_script( 'bootstrap-js' );
		// jQuery UI.
		wp_register_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array( 'jquery' ), '1.12.1', true );
		wp_enqueue_script( 'jquery-ui' );
		// Custom Admin Scripts.
		wp_register_script( 'custom-admin-script', IR73_Helper::get_plugin_assets_url( 'js/ir73.js' ), array(), '1.0', true );
		wp_enqueue_script( 'custom-admin-script' );
	}

	/**
	 * Registering & enqueueing stylesheets & scripts
	 *
	 * @return void
	 */
	public function load() {
		$this->register_styles();
		$this->register_scripts();
	}

	/**
	 * Hooking
	 *
	 * @return void
	 */
	public function enqueue() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_assets' ) );
	}

}

// Don’t cry because it’s over, smile because it happened.
