<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:16 PM
 *
 * @package IR73
 * @subpackage Customizer
 */

/**
 * Class IR73_Customizer
 */
class IR73_Customizer {

	/**
	 * @var string
	 */
	protected $customizer_id;

	/**
	 * @var string|null
	 */
	public $title = null;

	/**
	 * @var int
	 */
	public $priority = 210;

	/**
	 * @var string|null
	 */
	public $description = null;

	/**
	 * @var string
	 */
	protected $error_object = 'Customizer';

	/**
	 * @var array
	 */
	public $items;

	/**
	 * IR73_Customizer constructor.
	 *
	 * @param string $customizer_id
	 */
	public function __construct( $customizer_id ) {
		$this->customizer_id = $customizer_id;
	}

	public function set_items( array $items ) {
		$this->items = $items;
		return $this;
	}

	protected function check_n_fix_title() {
		if ( is_null( $this->title ) ) {
			$this->title = IR73_Helper::key_to_label( $this->customizer_id );
		}
	}

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
	 * @param $field_type
	 * @return string
	 */
	public function get_customizer_instance( $field_type ) {
		if ( $field_type == 'color' ) {
			$customizer_instance = 'WP_Customize_Color_Control';
		} elseif ( $field_type == 'upload' ) {
			$customizer_instance = 'WP_Customize_Upload_Control';
		} elseif ( $field_type == 'image' ) {
			$customizer_instance = 'WP_Customize_Image_Control';
		} else {
			$customizer_instance = 'WP_Customize_Control';
		}

		return $customizer_instance;
	}

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

	public function add_customizer() {
		$this->check_n_fix_title();

		// If no items have set return false
		if ( empty( $this->items ) || ! is_array( $this->items ) ) {
			return IR73_Helper::error_flash( $this->error_object, 'No Items Passed for ' . $this->title . ' Customizer' );
		}

		$this->add_section();
		$this->add_items();
	}

	public function do() {
		$this->add_customizer();
	}
}

// Don’t cry because it’s over, smile because it happened.
