<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:16 PM
 *
 * @package Smellycat
 * @subpackage Customizer
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * Class IR73_Customizer
 */
class IR73_Customizer {

	/**
	 * Unique id for the customizer.
	 *
	 * @var string
	 */
	protected string $customizer_id;

	/**
	 * Customizer Title.
	 *
	 * @var string|null
	 */
	public $title = null;

	/**
	 * Customizer Priority. Based on this it's position in
	 * the window will be adjusted.
	 *
	 * @var int
	 */
	public int $priority = 210;

	/**
	 * Describe your customizer.
	 *
	 * @var string|null
	 */
	public $description = null;

	/**
	 * Array of Items in the Customizer.
	 *
	 * @var array
	 */
	public array $items;

	/**
	 * IR73_Customizer constructor.
	 *
	 * @param string $customizer_id unique customizer id.
	 */
	public function __construct( string $customizer_id ) {
		$this->customizer_id = $customizer_id;
	}

	/**
	 * Items Setter.
	 *
	 * @param array $items items to be added to the customizer.
	 * @return self
	 */
	public function set_items( array $items ): self {
		$this->items = $items;
		return $this;
	}

	/**
	 * Title of the customizer is decided here.
	 *
	 * @return void
	 */
	protected function check_n_fix_title() {
		if ( is_null( $this->title ) ) {
			$this->title = IR73_Helper::key_to_label( $this->customizer_id );
		}
	}

	/**
	 * Add Section to the customizer.
	 *
	 * @return void
	 */
	public function add_section() {
		global $wp_customize;
		$args = array(
			'title'    => $this->title,
			'priority' => $this->priority,
		);
		if ( ! is_null( $this->description ) ) {
			$args['description'] = $this->description;
		}
		$wp_customize->add_section( $this->customizer_id, $args );
	}

	/**
	 * To get the customizer object field's instance.
	 *
	 * @param string $field_type type of customizer field to be added.
	 * @return string
	 */
	public function get_customizer_instance( string $field_type ): string {
		if ( 'color' === $field_type ) {
			$customizer_instance = 'WP_Customize_Color_Control';
		} elseif ( 'upload' === $field_type ) {
			$customizer_instance = 'WP_Customize_Upload_Control';
		} elseif ( 'image' === $field_type ) {
			$customizer_instance = 'WP_Customize_Image_Control';
		} else {
			$customizer_instance = 'WP_Customize_Control';
		}
		return $customizer_instance;
	}

	/**
	 * Add items to the customizer settings object.
	 *
	 * @return void
	 */
	public function add_items() {
		global $wp_customize;
		foreach ( $this->items as $item ) {
			$setting_params = array();
			if ( is_array( $item ) ) {
				$item_id      = $item['id'];
				$item['type'] = isset( $item['type'] ) ? $item['type'] : 'text';
				unset( $item['id'] );
				// Check for default value.
				if ( isset( $item['default'] ) ) {
					$setting_params['default'] = $item['default'];
					unset( $item['default'] );
				}
			} else {
				$item_id      = $item;
				$item['type'] = 'text';
			}

			$args = array(
				'label'   => IR73_Helper::key_to_label( $item_id ),
				'setting' => $item_id,
				'section' => $this->customizer_id,
			);

			$args                = array_merge( $item, $args );
			$customizer_instance = $this->get_customizer_instance( $item['type'] );

			$wp_customize->add_setting( $item_id, $setting_params );
			$wp_customize->add_control( new $customizer_instance( $wp_customize, $item_id, $args ) );
		}
	}

	/**
	 * This here, adds section and items to the customizer.
	 *
	 * @return void|bool
	 */
	public function add_customizer() {
		$this->check_n_fix_title();
		// If no items has set, return false.
		if ( empty( $this->items ) || ! is_array( $this->items ) ) {
			add_action(
				'admin_notices',
				function() {
					IR73_Helper::spit_admin_notice(
						'warning',
						'No Items Passed for ' . $this->title . ' Customizer'
					);
				}
			);
			return false;
		}
		$this->add_section();
		$this->add_items();
	}

	/**
	 * This actually does the trick.
	 *
	 * @return void
	 */
	public function do() {
		$this->add_customizer();
	}
}

// Don’t cry because it’s over, smile because it happened.
