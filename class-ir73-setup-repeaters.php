<?php
/**
 * Created by Atom.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 11-Oct-20
 * Time: 12:38 PM
 *
 * @package Smellycat
 * @subpackage IR73 Repeater Fields
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * Setup Repeater Fields from the config file.
 */
class IR73_Setup_Repeaters {

	/**
	 * Repeater Fields.
	 *
	 * @var array $repeators
	 */
	protected array $repeaters;

	/**
	 * Constructor
	 *
	 * @param array $repeaters Repeater Fields.
	 */
	public function __construct( array $repeaters ) {
		$this->repeaters = $repeaters;
	}

	/**
	 * Print the Repeater Fields.
	 *
	 * @return void
	 */
	public function make_repeater_showable() {
		foreach ( $this->repeaters as $value ) {

			( new IR73_Repeater_Fields( $value['ID'] ) )
				->set_title( $value['title'] )
				->set_object( $value['object'] )
				->restrict( $value['restrict_to'] ?? null )
				->assign_fields( $value['fields'] )
				->do();

		}
	}

	/**
	 * Save Repeater Fields.
	 *
	 * @return void
	 */
	public function make_repeater_saveable() {
		foreach ( $this->repeaters as $value ) {
			$fields_names = array();
			foreach ( $value['fields'] as $field ) {
				array_push( $fields_names, $field['key'] );
			}
			// Save Repeater Fields.
			$fields = array(
				'name'          => $value['ID'],
				'post_type'     => $value['object'],
				'restricted_to' => $value['restrict_to'] ?? false,
				'fields'        => $fields_names,
			);
			( new IR73_Repeater_Fields( $value['ID'] ) )->save( $fields );
		}
	}
}
