<?php
/**
 * Created by VS Code.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 02-Dec-20
 * Time: 22:15
 *
 * @package IR73
 * @subpackage Soratble
 */

/**
 * Sortable Support for Objects.
 */
class SortableObjects {

	/**
	 * The objects for which we must enable the sorting Feature
	 *
	 * @var array
	 */
	public array $objects;

	/**
	 * Constructor.
	 *
	 * @param array $objects
	 */
	public function __construct( array $objects ) {
		$this->objects = $objects;
	}

	public function create_sortable_pages() {
		add_menu_page(
			__( 'ir73 Drag & Order', IR73_TEXT_DOMAIN ),
			'Drag & Order',
			'manage_options',
			'ir73-drag-n-order',
			array( $this, 'page_markup' ),
			'dashicons-move'
		);
	}

	public function re_order_taxonomies() {

		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['term_id'] ) ) {

			// echo '<pre>', print_r( $_POST['term_id'] ), '</pre>';

			foreach ( $_POST['term_id'] as $key => $term ) {
				update_term_meta( $term, 'ir73_order', ( $key + 1 ) );
				echo $term . ' - ' . ( $key + 1 );
				echo '<br />';
			}

			session_start();
			$_SESSION['ir73_status']  = true;
			$_SESSION['ir73_message'] = 'Items Re-Arranged Successfully';
		}

		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	public function re_order_posts() {

		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && ! empty( $_POST['post_id'] ) ) {
			foreach( $_POST['post_id'] as $key => $post ) {
				update_post_meta( $post, 'ir73_order', ( $key + 1 ) );
			}
			session_start();
			$_SESSION['ir73_status']  = true;
			$_SESSION['ir73_message'] = 'Items Re-Arranged Successfully';
		}

		wp_safe_redirect( wp_get_referer() );
		exit;

	}

	public function page_markup() {
		?>
		<div class="wrap">

			<h1 class="wp-heading-inline">IR73 Drag & Order</h1>

			<?php
			if ( ! empty( $_SESSION['ir73_status'] ) && true === $_SESSION['ir73_status'] ) :
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo esc_html( $_SESSION['ir73_message'] ); ?></p>
				</div>
				<?php
				unset( $_SESSION['ir73_status'] );
				unset( $_SESSION['ir73_message'] );
			endif;
			?>

			<div class="row">
				<div class="col-md-3">
					<ul class="ir73-sortale-objects-list">

						<?php if ( ! empty( $this->objects['cpt'] ) ) : ?>

							<li class="object-title">Post Types</li>

							<?php foreach ( $this->objects['cpt'] as $cpt ) : ?>

								<?php
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

							<?php endforeach; ?>

						<?php endif; ?>

						<?php if ( ! empty( $this->objects['taxonomy'] ) ) : ?>

							<li class="object-title">Taxonomies</li>

							<?php foreach ( $this->objects['taxonomy'] as $taxonomy ) : ?>

								<?php
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

							<?php endforeach; ?>

						<?php endif; ?>

					</ul>
				</div>
				<div class="col-md-6">
					<?php
					if ( ! empty( $_REQUEST['tax'] ) ) {
						$taxonomy = get_taxonomy( $_REQUEST['tax'] );
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
							<h1><?php echo esc_html( $title ) ?></h1>
						</div>
						<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="POST" id="ir73_reOrderForm">

							<input type="hidden" name="action" value="ir73_reorder_tax">

							<ul data-action="sortable">

								<?php foreach ( $items as $item ) : ?>

									<li class="ui-state-default">
										<div>
											<h4> <i class="ui-icon ui-icon-arrowthick-2-n-s"></i> <?php echo $item->name; ?></h4>
											<input type="hidden" name="term_id[]" value="<?php echo $item->term_id; ?>">
										</div>
									</li>

								<?php endforeach; ?>

							</ul>

							<input type="submit" value="Submit" class="button button-primary">

						</form>

						<?php

					} elseif ( ! empty( $_REQUEST['cpt'] ) ) {
						$post_type = get_post_type_object( $_REQUEST['cpt'] );
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
							<h1><?php echo esc_html( $title ) ?></h1>
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

							<input type="submit" value="Submit" class="button button-primary">

						</form>
						<?php
					}
					?>

				</div>
				<div class="col-md-3"></div>
			</div>

		</div>
		<?php
	}
}
