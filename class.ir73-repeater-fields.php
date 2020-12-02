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
class IR73_RepeaterFields extends IR73
{
    /**
     * @var string
     */
    protected $meta_key;

    /**
     * @var string
     */
    protected $meta_title;

    /**
     * @var string
     */
    protected $object;

    /**
     * @var int|array
     */
    protected $restrict_to = null;

    /**
     * @var string
     */
    protected $context = 'normal';

    /**
     * @var string
     */
    protected $priority = 'default';

    /**
     * @var array
     */
    protected $fields;

    /**
     * array
     */
    protected $save_params;

    public function __construct( $key )
    {
        $this->meta_key = $key;
    }

    public function set_title( $title )
    {
        $this->meta_title = $title;
    }

    public function set_object( $object )
    {
        $this->object = $object;
    }

    public function set_context( $context )
    {
        $this->context = $context;
    }

    public function set_priority( $priority )
    {
        $this->priority = $priority;
    }

    public function restrict( $identifier )
    {
        $this->restrict_to = $identifier;
    }

    public function assign_fields(array $fields)
    {
        $this->fields = $fields;
    }

    protected function markup_template( $values = [], $delete_row = false )
    {
        ?>
        <div class="row p-tb-1">
            <?php foreach ( $this->fields as $key => $field ): ?>

                <?php $field_id = $this->meta_key . '_' . $field['key'] ?>

                <div class="custom-form-group col-md-<?php echo $field['col']; ?>">
                    <label for="<?php echo $field_id; ?>_title">
                        <?php echo IR73_Helper::key_to_label( $field['key'] ) ?>
                    </label>

                    <?php if ( $field['type'] == 'textarea' ): ?>

                        <textarea name="<?php echo $field_id ?>[]" class="form-control"
                            id="<?php echo $field_id ?>" rows="5"><?php echo $values[$field['key']] ?? ''?></textarea>

                    <?php elseif ( $field['type'] == 'media' ): ?>

                        <button class="button ir73-media-button" data-ir73-media-uploader-target=".ir73-target-elem">Upload File</button>

                        <?php if ( ! empty( $values ) ): ?>
                            <?php $pathinfo = pathinfo( $values[$field['key']] ); ?>
                            <br><br>

                            <?php
                            switch( $pathinfo['extension'] ):
                                case 'jpg':
                                case 'jpeg':
                                case 'png':
                                    ?>
                                    <img src="<?php echo $values[$field['key']] ?>" width="250" alt="attachment">
                                    <?php
                                    break;
                                case 'mp4':
                                    echo "Video : " . $values[$field['key']];
                                    break;
                                case 'pdf':
                                    ?>
                                    <a href="<?php echo $values[$field['key']] ?>" target="_blank">
                                        <?php echo $values[$field['key']]; ?>
                                    </a>
                                    <?php
                            endswitch; ?>

                        <?php endif; ?>

                        <input class="ir73-target-elem" type="hidden"
                            name="<?php echo $field_id ?>[]" value="<?php echo $values[$field['key']] ?? '' ?>">

                    <?php else: ?>

                        <input type="<?php echo $field['type'] ?>" class="form-control"
                            name="<?php echo $field_id ?>[]" id="<?php echo $field_id ?>"
                            value="<?php echo $values[$field['key']] ?? ''?>">

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

            <?php if ( $delete_row == true ): ?>

                <div class="col-md-1 custom-form-group justify-center">
                    <a href="#" data-role="delete-row">
                        <span class="dashicons-before dashicons-trash text-danger"></span>
                    </a>
                </div>

            <?php endif; ?>

        </div>
        <?php
    }

    public function generate_markup()
    {
        global $post;
        $meta_data = get_post_meta( $post->ID, $this->meta_key, true );
        wp_nonce_field( basename( __FILE__ ), $this->meta_key . '-meta-nonce' );
        $meta_data = !empty( $meta_data ) ? unserialize( $meta_data ) : null;

        ?>

        <div class="meta-wrapper">
            <div id="<?php echo $this->meta_key ?>_wrapper">
                <?php
                if ( ! is_null( $meta_data ) ):
                    foreach ( $meta_data as $single_data_row ):
                        $this->markup_template( $single_data_row, true );
                    endforeach;
                endif;
                ?>
            </div>
            <div class="pt-1">
                <button type="button" class="button button-orange" data-action="add_ir73_repeator_item"
                    data-template="new_<?php echo $this->meta_key;?>_template"
                    data-wrapper="<?php echo $this->meta_key ?>_wrapper">
                    Add Feature
                </button>
            </div>
        </div>

        <?php
    }

    public function mustache_template()
    {
        ?>
        <script id="new_<?php echo $this->meta_key;?>_template" type="text/x-handlebars">
            <?php $this->markup_template() ?>
        </script>
        <?php
    }

    public function load_mustache_templates()
    {
        add_action( 'admin_footer', array( $this, 'mustache_template' ) );
    }

    public function add_meta_box()
    {
        add_meta_box(
            $this->meta_key,
            $this->meta_title ?? IR73_Helper::key_to_label( $this->meta_key ),
            array( $this, 'generate_markup' ),
            $this->object,
            $this->context,
            $this->priority
        );
    }

    public function do()
    {
        // Validate (WILL DO LATER. NOT TODAY)

        if ( $this->restrict_to !== null ) {

            $post_id = $_GET['post'] ?? '';

            if ( empty( $post_id ) ) {
                return;
            } elseif ( is_array( $this->restrict_to ) && ! in_array( $post_id, $this->restrict_to ) ) {
                return;
            } elseif ( $post_id != $this->restrict_to ) {
                return;
            }

        }

        // Add Meta Box
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

        // Load Mustache
        add_action( 'admin_enqueue_scripts', array( $this, 'load_mustache_templates' ) );
    }

    public function save_fields()
    {
        if ( !empty( $this->save_params['restricted_to'] ) && $this->save_params['restricted_to'] != $_POST['post_ID'] ) {
            return;
        }

        $nonce_key = $this->save_params['name'] . '-meta-nonce';

        // nonce verification
        if ( isset( $_POST[$nonce_key] ) && !wp_verify_nonce( $_POST[$nonce_key], basename( __FILE__ ) ) ) {
            return;
        }

        // exit('Hello World');

        foreach ( $this->save_params['fields'] as $field ) {
            // Checking if not empty
            if ( empty( $_POST[$this->save_params['name'] . '_' . $field] ) ) {
                echo "Nothing to update ! So Deleting It.";
                delete_post_meta( $_POST['post_ID'], $this->save_params['name'] );
                return;
            }
        }

        $total_item_count = count($_POST[$this->save_params['name'] . '_' . $this->save_params['fields'][0]]);
        $meta_data = [];

        /*
         * To contain the field pair that contains an
         * empty value so that we can subtract it from
         * the actual meta
         */
        $empty_value_fields = [];

        for ( $i = 0; $i < $total_item_count; $i++ ) {

            $meta_data[$i] = [];

            foreach ($this->save_params['fields'] as $value) {

                $field_value = $_POST[$this->save_params['name'] . '_' . $value][$i];

                if ( empty( $field_value ) || is_null( $field_value ) ) {
                    array_push( $empty_value_fields, $i );
                }

                $meta_data[$i][$value] = $field_value;

            }

        }

        if ( ! empty( $empty_value_fields ) ) {

            $empty_value_fields = array_unique($empty_value_fields);

            // Remove empty pairs that contain empty value
            foreach ( $empty_value_fields as $delete_key ) {
                unset( $meta_data[$delete_key] );
            }

        }

        if ( empty ( $meta_data ) ) {
            delete_post_meta( $_POST['post_ID'], $this->save_params['name'] );
            echo "deleted";
            return;
        }

        $meta_data = serialize( $meta_data );

        update_post_meta( $_POST['post_ID'], $this->save_params['name'], $meta_data );
    }

    public function save( $repeater_field )
    {
        if ( $_SERVER['REQUEST_METHOD'] !== 'POST' || empty ( $_POST['post_ID'] ) ) {
            return false;
        }

        $post_id = $_POST['post_ID'];

        // Return if the user doesn't have edit permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        $this->save_params = $repeater_field;

        if ( $_POST['post_type'] == $this->save_params['post_type'] ) {
            return $this->save_fields();
        }
    }
}
