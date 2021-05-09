<?php
/**
 * Created by VS Code.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 02-Dec-20
 * Time: 22:15
 *
 * @package Smellycat
 * @subpackage Soratble
 */

/**
 * Sortable Support for Objects.
 */
class IR73_Sortable_Objects {

	/**
	 * The objects for which we must enable the sorting Feature
	 *
	 * @var array
	 */
	public array $objects;

	/**
	 * Constructor.
	 *
	 * @param array $objects Items that needs sortable support.
	 */
	public function __construct( array $objects ) {
		$this->objects = $objects;
	}

	/**
	 * Create admin pages for sorting the items.
	 *
	 * @return void
	 */
	public function create_sortable_pages() {

		add_menu_page(
			__( 'ir73 Drag & Order', 'ir73' ),
			'Drag & Order',
			'manage_options',
			'ir73-drag-n-order',
			array( $this, 'page_markup' ),
			'dashicons-move'
		);

	}

	/**
	 * Re-Order Taxonomies
	 *
	 * @return void
	 */
	public function re_order_taxonomies() {

		// nonce verification.
		if ( isset( $_POST['ir73_sortable-taxonomies'] ) && ! wp_verify_nonce( sanitize_key( $_POST['ir73_sortable-taxonomies'] ), basename( __FILE__ ) ) ) {
			return;
		}

		if ( empty( $_SERVER['REQUEST_METHOD'] ) && 'POST' !== $_SERVER['REQUEST_METHOD'] && empty( $_POST['term_id'] ) ) {
			return;
		}

		$term_ids = isset( $_POST['term_id'] )
			? (array) array_map( 'sanitize_text_field', wp_unslash( $_POST['term_id'] ) )
			: array();

		foreach ( $term_ids as $key => $term ) {
			update_term_meta( $term, 'ir73_order', ( $key + 1 ) );
		}

		session_start();
		$_SESSION['ir73_status']  = true;
		$_SESSION['ir73_message'] = 'Items Re-Arranged Successfully';

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * Re-Order Posts
	 *
	 * @return void
	 */
	public function re_order_posts() {

		// nonce verification.
		if ( isset( $_POST['ir73_sortable-cpts'] ) && ! wp_verify_nonce( sanitize_key( $_POST['ir73_sortable-cpts'] ), basename( __FILE__ ) ) ) {
			return;
		}

		if ( empty( $_SERVER['REQUEST_METHOD'] ) && 'POST' !== $_SERVER['REQUEST_METHOD'] && empty( $_POST['post_id'] ) ) {
			return;
		}

		$post_ids = isset( $_POST['post_id'] )
			? (array) array_map( 'sanitize_text_field', wp_unslash( $_POST['post_id'] ) )
			: array();

		foreach ( $post_ids as $key => $post ) {
			update_post_meta( $post, 'ir73_order', ( $key + 1 ) );
		}
		session_start();
		$_SESSION['ir73_status']  = true;
		$_SESSION['ir73_message'] = 'Items Re-Arranged Successfully';

		wp_safe_redirect( wp_get_referer() );
		exit;

	}

	/**
	 * Print Messages.
	 *
	 * @return void
	 */
	protected function print_message() {
		if ( ! empty( $_SESSION['ir73_status'] ) && true === $_SESSION['ir73_status'] ) :
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo wp_kses_post( $_SESSION['ir73_message'] ); ?></p>
			</div>
			<?php
			unset( $_SESSION['ir73_status'] );
			unset( $_SESSION['ir73_message'] );
		endif;
	}

	/**
	 * List Custom Post Types.
	 *
	 * @return void
	 */
	protected function list_cpts() {

		if ( ! empty( $this->objects['cpt'] ) ) :
			?>
			<li class="object-title">Post Types</li>
			<?php
			foreach ( $this->objects['cpt'] as $cpt ) :
				$url = add_query_arg(
					array( 'cpt' => $cpt ),
					admin_url( 'admin.php?page=ir73-drag-n-order' )
				);
				?>
				<li class="ir73-sortable-item">
					<a href="<?php echo esc_url( $url ); ?>">
						<?php echo esc_html( ucwords( str_replace( '-', ' ', $cpt ) ) ); ?>
					</a>
				</li>
				<?php
			endforeach;
		endif;

	}

	/**
	 * List Posts of a certain Post Type
	 *
	 * @param string $post_type The post type of which we are going to fetch the posts of.
	 * @return void
	 */
	protected function list_posts( string $post_type ) {

		$post_type = get_post_type_object( $post_type );
		$title     = $post_type->label;
		$items     = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => $post_type->name,
				'meta_key'    => 'ir73_order',
				'orderby'     => 'meta_value_num',
				'order'       => 'asc',
			)
		);
		?>
		<div class="reordering-object-title">
			<h1><?php echo esc_html( $title ); ?></h1>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="ir73_reOrderForm">
			<input type="hidden" name="action" value="ir73_reorder_cpt">
			<ul data-action="sortable">
				<?php foreach ( $items as $item ) : ?>
					<li class="ui-state-default">
						<div>
							<h4><i class="ui-icon ui-icon-arrowthick-2-n-s"></i> <?php echo esc_html( $item->post_title ); ?></h4>
							<input type="hidden" name="post_id[]" value="<?php echo esc_attr( $item->ID ); ?>">
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php wp_nonce_field( basename( __FILE__ ), 'ir73_sortable-cpts' ); ?>
			<input type="submit" value="Submit" class="button button-primary">
		</form>
		<?php

	}

	/**
	 * List Taxonomies.
	 *
	 * @return void
	 */
	protected function list_taxonomies() {

		if ( ! empty( $this->objects['taxonomy'] ) ) :
			?>
			<li class="object-title">Taxonomies</li>
			<?php
			foreach ( $this->objects['taxonomy'] as $taxonomy ) :
				$url = add_query_arg(
					array( 'tax' => $taxonomy ),
					admin_url( 'admin.php?page=ir73-drag-n-order' )
				);
				?>
				<li class="ir73-sortable-item">
					<a href="<?php echo esc_url( $url ); ?>">
						<?php echo esc_html( ucwords( str_replace( '-', ' ', $taxonomy ) ) ); ?>
					</a>
				</li>
				<?php
			endforeach;
		endif;

	}

	/**
	 * List Taxonomy's Terms
	 *
	 * @param string $taxonomy the taxonomy of which we are getting the terms of.
	 * @return void
	 */
	protected function list_taxonomy_items( string $taxonomy ) {

		$taxonomy = get_taxonomy( $taxonomy );
		$title    = $taxonomy->label;
		$items    = get_terms(
			array(
				'taxonomy'   => $taxonomy->name,
				'hide_empty' => false,
				'meta_key'   => 'ir73_order',
				'orderby'    => 'meta_value_num',
				'order'      => 'asc',
			)
		);
		?>

		<div class="reordering-object-title">
			<h1><?php echo esc_html( $title ); ?></h1>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="ir73_reOrderForm">
			<input type="hidden" name="action" value="ir73_reorder_tax">
			<ul data-action="sortable">
				<?php foreach ( $items as $item ) : ?>
					<li class="ui-state-default">
						<div>
							<h4><i class="ui-icon ui-icon-arrowthick-2-n-s"></i> <?php echo esc_html( $item->name ); ?></h4>
							<input type="hidden" name="term_id[]" value="<?php echo esc_attr( $item->term_id ); ?>">
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php wp_nonce_field( basename( __FILE__ ), 'ir73_sortable-taxonomies' ); ?>
			<input type="submit" value="Submit" class="button button-primary">
		</form>
		<?php

	}

	/**
	 * Sortable Admin Page Markup.
	 *
	 * @return void
	 */
	public function page_markup() {
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline">IR73 Drag & Order</h1>
			<?php $this->print_message(); ?>
			<div class="row">
				<div class="col-md-3">
					<ul class="ir73-sortale-objects-list">
						<?php $this->list_cpts(); ?>
						<?php $this->list_taxonomies(); ?>
					</ul>
				</div>
				<div class="col-md-6">
					<?php
					// TODO: Check how to check for nonce here & see if it is mandatory.
					if ( ! empty( $_REQUEST['tax'] ) ) {
						$this->list_taxonomy_items( sanitize_key( $_REQUEST['tax'] ) );
					} elseif ( ! empty( $_REQUEST['cpt'] ) ) {
						$this->list_posts( sanitize_key( $_REQUEST['cpt'] ) );
					}
					?>
				</div>
				<div class="col-md-3"></div>
			</div>
		</div>
		<?php
	}
}
