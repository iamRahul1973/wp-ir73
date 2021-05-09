<?php
/**
 * Created by PhpStorm.
 * User: iamRahul1995
 * Author URL : https://iamrahul1973.github.io/
 * Date: 17-Aug-19
 * Time: 1:13 PM
 *
 * @package Smellycat
 * @subpackage IR73 Helper
 * @author iamRahul1973 rahulkr1973@gmail.com
 */

/**
 * Class IR73_Helper
 */
class IR73_Helper {

	/**
	 * Convert Key to Label
	 *
	 * @param string $key string to convert.
	 * @return string
	 */
	public static function key_to_label( string $key ): string {
		return ucwords( str_replace( array( '_', '-' ), ' ', $key ) );
	}

	/**
	 * Return theme's assets url.
	 *
	 * @param string $path path to the asset. (Image, CSS, JS or anything).
	 * @return string
	 */
	public static function get_assets_url( string $path ): string {
		return get_template_directory_uri() . '/assets/' . $path;
	}

	/**
	 * Return plugin's assets url.
	 *
	 * @param string $path path to the asset.
	 * @return string
	 */
	public static function get_plugin_assets_url( string $path ): string {
		return IR73_PLUGIN_DIR . 'assets/' . $path;
	}

	/**
	 * Add a custom function to the ajax hook.
	 *
	 * @param string $function_name function to be added.
	 */
	public static function get_ajax_support( string $function_name ) {
		add_action( 'wp_ajax_' . $function_name, $function_name );
		add_action( 'wp_ajax_nopriv_' . $function_name, $function_name );
	}

	/**
	 * In order to retrieve the first line from a textarea,
	 * which we used to store multiple items line by line.
	 *
	 * @param string $value textarea's value with newline.
	 * @return string
	 */
	public static function first_item_in_multiline_textarea( string $value ): string {
		return preg_split( '/\r\n|\r|\n/', $value )[0];
	}

	/**
	 * Generate img markup with alt & title ttributes taken from wp_media
	 *
	 * @param int   $thumbnail_id thumbnail's id from which we'll get the meta details.
	 * @param array $class_names array of class names to add to the img tag.
	 * @param array $attributes array of attributes to add to the img tag.
	 */
	public static function draw_image( int $thumbnail_id, array $class_names = array(), array $attributes = array() ) {
		$image_alt   = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
		$image_title = get_the_title( $thumbnail_id );
		$image_url   = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		?>
		<img src="<?php echo esc_url( $image_url[0] ); ?>"
			alt="<?php echo esc_attr( $image_alt ); ?>"
			title="<?php echo esc_attr( $image_title ); ?>"
			class="<?php echo ! empty( $class_names ) ? esc_attr( implode( ' ', $class_names ) ) : ''; ?>"
			<?php
			if ( ! empty( $attributes ) ) :
				foreach ( $attributes as $attribute => $value ) :
					echo esc_html( $attribute ) . '="' . esc_attr( $value ) . '"';
				endforeach;
			endif;
			?>
			>
		<?php
	}

	/**
	 * NOTICE : THIS WILL PREPARE ONLY MENUS WITH 2 LEVELS.
	 * MODIFY THIS TO CREATE MULTI LEVEL MENUS.
	 *
	 * @param string $menu_name Name of the menu to retrieve.
	 * @return bool|array
	 */
	public static function prepare_two_level_menu( string $menu_name ) {

		$menu_items = wp_get_nav_menu_items( $menu_name );

		if ( false === $menu_items ) {
			return false;
		}

		$prepared = array();

		foreach ( $menu_items as $submenu ) {
			if ( 0 === $submenu->menu_item_parent ) {
				$prepared[ $submenu->ID ] = array( 'object' => $submenu );
			} else {
				if ( empty( $menu[ $submenu->menu_item_parent ]['has_children'] ) ) {
					$prepared[ $submenu->menu_item_parent ]['has_children'] = true;
				}
				$prepared[ $submenu->menu_item_parent ]['children'][] = $submenu;
			}
		}

		return $prepared;
	}

	/**
	 * Admin Notice Wrapper Markup.
	 *
	 * @param string $class notice' class. Can be any of, error, warning, success & info.
	 * @param string $message message to display.
	 * @return void
	 */
	public static function spit_admin_notice( string $class, string $message ) {
		?>
		<div class="notice notice-<?php echo esc_attr( $class ); ?> is-dismissible">
			<p><strong>SMELLYCAT SAYS :</strong> <?php echo wp_kses_post( $message ); ?></p>
		</div>
		<?php
	}

	/**
	 * Generate Uniw=que Username.
	 *
	 * @param string $username The selected username.
	 * @return string
	 */
	public static function generate_unique_username( string $username ) {
		$username = sanitize_title( $username );

		static $i;
		if ( null === $i ) {
			$i = 1;
		} else {
			$i++;
		}

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		$new_username = sprintf( '%s-%s', $username, $i );

		if ( ! username_exists( $new_username ) ) {
			return $new_username;
		} else {
			return call_user_func( __FUNCTION__, $username );
		}
	}

	/**
	 * Return the site's url without the http protocols.
	 *
	 * @return string
	 */
	public static function site_url_without_protocols(): string {
		$protocols = array( 'https://', 'https://www.', 'www.' );
		return str_replace( $protocols, '', get_bloginfo( 'wpurl' ) );
	}

	/**
	 * Get custom logo's url
	 *
	 * @return string
	 */
	public static function get_custom_logo_url() {
		$logo_id = get_theme_mod( 'custom_logo' );
		$image   = wp_get_attachment_image_url( $logo_id, 'full' );
		return $image;
	}

	/**
	 * Custom CF7 Admin Area Styling. I didn't like the default one.
	 *
	 * @return void|bool
	 */
	public static function add_custom_cf7_styles() {
		if ( ! is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
			return false;
		}
		?>
		<style type="text/css">
			#wpcf7-admin-form-element #contact-form-editor, 
			#wpcf7-admin-form-element #contact-form-editor textarea#wpcf7-form, 
			#wpcf7-admin-form-element #contact-form-editor .form-table input, 
			#wpcf7-admin-form-element #contact-form-editor .form-table textarea, 
			#wpcf7-admin-form-element #contact-form-editor #messages-panel input, 
			#wpcf7-admin-form-element #contact-form-editor textarea#wpcf7-additional-settings {
				font-family: Tahoma !important;
			}
			#wpcf7-admin-form-element #contact-form-editor textarea#wpcf7-form, 
			#wpcf7-admin-form-element #contact-form-editor textarea#wpcf7-additional-settings {
				font-size: 16px;
				line-height: 28px;
			}
			/* Flamingo */
			#inboundfieldsdiv, #inboundmetadiv {
				font-family: Tahoma;
			}
		</style>
		<?php
	}

}

// Don’t cry because it’s over, smile because it happened.
