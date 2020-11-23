<?php
/**
 * Created by Atom.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 11-Oct-20
 * Time: 12:38 PM
 */

 /**
  *
  */
class SetupRepeaters
{
    /**
     * @var array $repeators
     */
    protected $repeaters;

    public function __construct( $repeaters )
    {
        $this->repeaters = $repeaters;
    }

    public function make_repeater_showable()
    {
        foreach ( $this->repeaters as $value ) {

            $repeater = new IR73_RepeaterFields( $value['ID'] );

            $repeater->set_title( $value['title'] );
            $repeater->set_object( $value['object'] );
            $repeater->restrict( $value['restrict_to'] );
            $repeater->assign_fields( $value['fields'] );
            $repeater->do();

        }
    }

    public function make_repeater_saveable()
    {
        foreach ( $this->repeaters as $value ) {

            $repeater = new IR73_RepeaterFields( $value['ID'] );

            $fields_names = array();

            foreach ( $value['fields'] as $field ) {
                array_push( $fields_names, $field['key'] );
            }

            // Save Repeater Fields
            $fields = array(
                'name'          => $value['ID'],
                'post_type'     => $value['object'],
                'restricted_to' => $value['restrict_to'],
                'fields'        => $fields_names
            );

            $repeater->save( $fields );
        }
    }
}
