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
 * Repeater Fields
 */
class IR73_Repeater_Fields extends IR73 {

	/**
	 * Meta Field Key.
	 *
	 * @var string
	 */
	protected string $meta_key;

	/**
	 * Meta Field Title.
	 *
	 * @var string
	 */
	protected string $meta_title;

	/**
	 * Post Type.
	 *
	 * @var string
	 */
	protected string $object;

	/**
	 * Restrict the repeater fields to the passed in page or post.
	 *
	 * @var int|array
	 */
	protected $restrict_to = null;

	/**
	 * Meta Box Context.
	 *
	 * @var string
	 */
	protected string $context = 'normal';

	/**
	 * Meta Box Priority.
	 *
	 * @var string
	 */
	protected string $priority = 'default';

	/**
	 * The fields, the real heroes.
	 *
	 * @var array
	 */
	protected array $fields;

	/**
	 * Repeater Field Params for Saving the data.
	 *
	 * @var array
	 */
	protected array $save_params;

	/**
	 * Constructor.
	 *
	 * @param string $key Repeater meta box key.
	 */
	public function __construct( $key ) {
		$this->meta_key = $key;
	}

	/**
	 * Set the repeater meta's title.
	 *
	 * @param string $title Title for the Repeater.
	 * @return $this
	 */
	public function set_title( $title ) {
		$this->meta_title = $title;
		return $this;
	}

	/**
	 * Assign repeater meta to an object. Post, Page, etc.
	 *
	 * @param string $object post or page to assign to.
	 * @return $this
	 */
	public function set_object( $object ) {
		$this->object = $object;
		return $this;
	}

	/**
	 * Metabox context.
	 *
	 * @param string $context metabox context.
	 * @return void
	 */
	public function set_context( $context ) {
		$this->context = $context;
	}

	/**
	 * Metabox Priority
	 *
	 * @param string $priority metabox priority.
	 * @return void
	 */
	public function set_priority( $priority ) {
		$this->priority = $priority;
	}

	/**
	 * To restrict the repeater meta to specific page or post Ids.
	 *
	 * @param array|int $identifier page or post id or array of ids.
	 * @return $this
	 */
	public function restrict( $identifier ) {
		$this->restrict_to = $identifier;
		return $this;
	}

	/**
	 * Assign multiple fields to the Metabox.
	 *
	 * @param array $fields Fields to add to the meta box.
	 * @return $this
	 */
	public function assign_fields( array $fields ) {
		$this->fields = $fields;
		return $this;
	}

	/**
	 * Repeter Markup - Single Row.
	 *
	 * @param array   $values Values to fill in the fields.
	 * @param boolean $delete_row Makes the row deletable.
	 * @return void
	 */
	protected function markup_template( $values = array(), $delete_row = false ) {
		?>
		<div class="row p-tb-1">
			<?php foreach ( $this->fields as $key => $field ) : ?>

				<?php $field_id = $this->meta_key . '_' . $field['key']; ?>

				<div class="custom-form-group col-md-<?php echo esc_attr( $field['col'] ); ?>">
					<label for="<?php echo esc_attr( $field_id ); ?>_title">
						<?php echo wp_kses_post( IR73_Helper::key_to_label( $field['key'] ) ); ?>
					</label>

					<?php if ( 'textarea' === $field['type'] ) : ?>

						<textarea name="<?php echo esc_attr( $field_id ); ?>[]" class="form-control"
							id="<?php echo esc_attr( $field_id ); ?>" rows="5"><?php echo wp_kses_post( $values[ $field['key'] ] ?? '' ); ?></textarea>

					<?php elseif ( 'media' === $field['type'] ) : ?>

						<button class="button ir73-media-button" data-ir73-media-uploader-target=".ir73-target-elem">Upload File</button>

						<?php if ( ! empty( $values ) ) : ?>
							<?php $pathinfo = pathinfo( $values[ $field['key'] ] ); ?>
							<br><br>
							<?php
							switch ( $pathinfo['extension'] ) :
								case 'jpg':
								case 'jpeg':
								case 'png':
									?>
									<img src="<?php echo esc_url( $values[ $field['key'] ] ); ?>" width="250" alt="attachment">
									<?php
									break;
								case 'mp4':
									echo 'Video : ' . wp_kses_post( $values[ $field['key'] ] );
									break;
								case 'pdf':
									?>
									<a href="<?php echo esc_url( $values[ $field['key'] ] ); ?>" target="_blank">
										<?php echo esc_url( $values[ $field['key'] ] ); ?>
									</a>
									<?php
							endswitch;
							?>

						<?php endif; ?>

						<input class="ir73-target-elem" type="hidden" name="<?php echo esc_attr( $field_id ); ?>[]"
							value="<?php echo esc_attr( $values[ $field['key'] ] ?? '' ); ?>">

					<?php else : ?>

						<input type="<?php echo esc_attr( $field['type'] ); ?>" class="form-control"
							name="<?php echo esc_attr( $field_id ); ?>[]" id="<?php echo esc_attr( $field_id ); ?>"
							value="<?php echo esc_attr( $values[ $field['key'] ] ?? '' ); ?>">

					<?php endif; ?>

				</div>

			<?php endforeach; ?>

			<?php if ( true === $delete_row ) : ?>

				<div class="col-md-1 custom-form-group justify-center">
					<a href="#" data-role="delete-row">
						<span class="dashicons-before dashicons-trash text-danger"></span>
					</a>
				</div>

			<?php endif; ?>

		</div>
		<?php
	}

	/**
	 * Generate Repeater Field Markup.
	 *
	 * @return void
	 */
	public function generate_markup() {

		global $post;

		$meta_data = get_post_meta( $post->ID, $this->meta_key, true );
		wp_nonce_field( basename( __FILE__ ), $this->meta_key . '-meta-nonce' );

		// For Backword Compatibility.
		// You know, i used to store it as serialized string. WTF Huh !!
		$meta_data = ! empty( $meta_data ) ? maybe_unserialize( $meta_data ) : null;

		?>

		<div class="meta-wrapper">
			<div id="<?php echo esc_attr( $this->meta_key ); ?>_wrapper">
				<?php
				if ( ! is_null( $meta_data ) ) :
					foreach ( $meta_data as $single_data_row ) :
						$this->markup_template( $single_data_row, true );
					endforeach;
				endif;
				?>
			</div>
			<div class="pt-1">
				<button type="button" class="button button-orange" data-action="add_ir73_repeator_item"
					data-template="new_<?php echo esc_attr( $this->meta_key ); ?>_template"
					data-wrapper="<?php echo esc_attr( $this->meta_key ); ?>_wrapper">
					Add Feature
				</button>
			</div>
		</div>

		<?php
	}

	/**
	 * Making it mustache compatible.
	 *
	 * @return void
	 */
	public function mustache_template() {
		?>
		<script id="new_<?php echo esc_attr( $this->meta_key ); ?>_template" type="text/x-handlebars">
			<?php $this->markup_template(); ?>
		</script>
		<?php
	}

	/**
	 * Load template to the footer.
	 *
	 * @return void
	 */
	public function load_mustache_templates() {
		add_action( 'admin_footer', array( $this, 'mustache_template' ) );
	}

	/**
	 * Restrict Metabox to only the ones in $this->restrict_to.
	 *
	 * @param int $post_id Current Post ID.
	 * @return bool
	 */
	public function should_restrict( int $post_id ) {

		if ( is_array( $this->restrict_to ) ) {
			if ( ! in_array( $post_id, $this->restrict_to, true ) ) {
				return true;
			}
		} elseif ( $post_id !== $this->restrict_to ) {
			return true;
		}

		return false;
	}

	/**
	 * Add Repeater Metabox
	 *
	 * @return void
	 */
	public function add_meta_box() {

		global $post;

		if ( null !== $this->restrict_to && $this->should_restrict( $post->ID ) === true ) {
			return;
		}

		add_meta_box(
			$this->meta_key,
			$this->meta_title ?? IR73_Helper::key_to_label( $this->meta_key ),
			array( $this, 'generate_markup' ),
			$this->object,
			$this->context,
			$this->priority
		);

		// Load Mustache.
		add_action( 'admin_enqueue_scripts', array( $this, 'load_mustache_templates' ) );

	}

	/**
	 * Restricts and shows the repeter meta on appropriate places.
	 *
	 * @return void
	 */
	public function do() {
		// Add Meta Box.
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
	}

	/**
	 * Save Fields.
	 *
	 * @param array $save_params repeater field config array.
	 * @return void|bool
	 */
	public function save( array $save_params ) {

		if ( empty( $_SERVER['REQUEST_METHOD'] ) && 'POST' !== $_SERVER['REQUEST_METHOD'] ) {
			return;
		}

		// nonce verification.
		$nonce_key = $save_params['name'] . '-meta-nonce';
		if ( isset( $_POST[ $nonce_key ] ) && ! wp_verify_nonce( sanitize_key( $_POST[ $nonce_key ] ), basename( __FILE__ ) ) ) {
			return;
		}

		// If no post id present, get the fuck out of here.
		if ( empty( $_POST['post_ID'] ) ) {
			return false;
		}

		$post_id = intval( $_POST['post_ID'] );

		// Return if the user doesn't have edit permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( empty( $_POST['post_type'] ) || $_POST['post_type'] !== $save_params['post_type'] ) {
			return;
		}

		$this->restrict_to = $save_params['restricted_to'];

		// If this is not the intended post, get the hell outta here.
		if ( ! empty( $this->restrict_to ) && $this->should_restrict( $post_id ) === true ) {
			return;
		}

		foreach ( $save_params['fields'] as $field ) {
			// Checking if not empty.
			if ( empty( $_POST[ $save_params['name'] . '_' . $field ] ) ) {
				echo 'Nothing to update ! So Deleting It.';
				delete_post_meta( $post_id, $save_params['name'] );
				return;
			}
		}

		$total_item_count = isset( $_POST[ $save_params['name'] . '_' . $save_params['fields'][0] ] )
			? count( $_POST[ $save_params['name'] . '_' . $save_params['fields'][0] ] ) : 0;
		$meta_data        = array();

		/*
		 * To contain the field pair that contains an
		 * empty value so that we can subtract it from
		 * the actual meta
		 */
		$empty_value_fields = array();

		for ( $i = 0; $i < $total_item_count; $i++ ) {

			$meta_data[ $i ] = array();

			foreach ( $save_params['fields'] as $value ) {

				$field_value = ! empty( $_POST[ $save_params['name'] . '_' . $value ][ $i ] )
					? sanitize_text_field( wp_unslash( $_POST[ $save_params['name'] . '_' . $value ][ $i ] ) ) : '';

				if ( empty( $field_value ) || is_null( $field_value ) ) {
					array_push( $empty_value_fields, $i );
				}

				$meta_data[ $i ][ $value ] = $field_value;

			}
		}

		if ( ! empty( $empty_value_fields ) ) {

			$empty_value_fields = array_unique( $empty_value_fields );

			// Remove empty pairs that contain empty value.
			foreach ( $empty_value_fields as $delete_key ) {
				unset( $meta_data[ $delete_key ] );
			}
		}

		if ( empty( $meta_data ) ) {
			delete_post_meta( $post_id, $save_params['name'] );
			echo 'deleted';
			return;
		}

		update_post_meta( $post_id, $save_params['name'], $meta_data );
	}
}
