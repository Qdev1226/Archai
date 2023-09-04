<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_manufacturers' ) ) :

	final class wp_easycart_admin_manufacturers {

		protected static $_instance = null;

		public $manufacturers_list_file;
		public $manufacturers_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->manufacturers_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/manufacturers/manufacturer-list.php';
			$this->manufacturers_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/products/manufacturers/manufacturer-details.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_manufacturer' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_manufacturer' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_manufacturer' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_manufacturer' ) );
		}

		public function process_add_new_manufacturer() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-manufacturer' == $_POST['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
					$result = $this->insert_manufacturer();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'manufacturers', $result );
				}
			}
		}

		public function process_update_manufacturer() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-manufacturer' == $_POST['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
					$result = $this->update_manufacturer();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'manufacturers', $result );
				}
			}
		}

		public function process_delete_manufacturer() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'manufacturers' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-manufacturer' == $_GET['ec_admin_form_action'] && isset( $_GET['manufacturer_id'] ) && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-manufacturer' ) ) {
					$result = $this->delete_manufacturer();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'manufacturers', $result );
				}
			}
		}

		public function process_bulk_delete_manufacturer() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_products' ) ) {
				return;
			}

			if ( isset( $_GET['subpage'] ) && 'manufacturers' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-manufacturer' == $_GET['ec_admin_form_action'] && ! isset( $_GET['manufacturer_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-manufacturers' ) ) {
					$result = $this->bulk_delete_manufacturer();
					wp_easycart_admin()->redirect( 'wp-easycart-products', 'manufacturers', $result );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'manufacturer-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Manufacturer successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'manufacturer-updated' == $_GET['success'] ) {
				$messages[] = __( 'Manufacturer successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'manufacturer-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Manufacturer successfully deleted', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'manufacturer-inserted-error' == $_GET['error'] ) {
				$messages[] = __( 'Manufacturer failed to create', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'manufacturer-updated-error' == $_GET['error'] ) {
				$messages[] = __( 'Manufacturer failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'manufacturer-deleted-error' == $_GET['error'] ) {
				$messages[] = __( 'Manufacturer failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'manufacturer-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'Manufacturer failed to create due to duplicate', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_manufacturers_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['manufacturer_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
					include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_manufacturer.php' );
					$details = new wp_easycart_admin_details_manufacturer();
					$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->manufacturers_list_file );

			}
		}

		public function insert_manufacturer() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
				return false;
			}

			global $wpdb;

			$name = sanitize_text_field( wp_unslash( $_POST['manufacturer_name'] ) );
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', stripslashes_deep( strtolower( $name ) ) ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_manufacturer( `name` ) VALUES( %s )', $name ) );
			$manufacturer_id = $wpdb->insert_id;
			$post_excerpt = ( isset( $_POST['post_excerpt'] ) ) ? sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) ) : '';
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			// Get URL
			$store_page = get_permalink( get_option( 'ec_option_storepage' ) );
			if ( strstr( $store_page, '?' ) )									$guid = $store_page . '&manufacturer=' . $manufacturer_id;
			else if ( substr( $store_page, strlen( $store_page ) - 1 ) == '/' )	$guid = $store_page . $post_slug;
			else																$guid = $store_page . '/' . $post_slug;

			$guid = strtolower( $guid );
			$post_slug_orig = $post_slug;
			$guid_orig = $guid;
			$guid = $guid . '/';

			/* Fix for Duplicate GUIDs */
			$i=1;
			while( $guid_check = $wpdb->get_row( $wpdb->prepare( 'SELECT ' . $wpdb->prefix . 'posts.guid FROM ' . $wpdb->prefix . 'posts WHERE ' . $wpdb->prefix . 'posts.guid = %s', $guid ) ) ) {
				$guid = $guid_orig . '-' . $i . '/';
				$post_slug = $post_slug_orig . '-' . $i;
				$i++;
			} 

			/* Manually Insert Post */
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ' . $wpdb->prefix . 'posts( post_content, post_status, post_title, post_name, guid, post_type, post_excerpt, comment_status, post_date, post_date_gmt, post_modified, post_modified_gmt ) VALUES( %s, %s, %s, %s, %s, %s, %s, "closed", NOW(), UTC_TIMESTAMP(), NOW(), UTC_TIMESTAMP() )', '[ec_store manufacturerid="' . $manufacturer_id . '"]', 'publish', wp_easycart_language()->convert_text( $name ), $post_slug, $guid, 'ec_store', $post_excerpt ) );
			$post_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_manufacturer SET post_id = %d WHERE manufacturer_id = %d', $post_id, $manufacturer_id ) );
			if ( 0 != $featured_image ) {
				set_post_thumbnail( $post_id, $featured_image );
			}
			do_action( 'wpeasycart_manufacturer_added', $manufacturer_id );

			return array( 'success' => 'manufacturer-inserted', 'manufacturer_id' => $manufacturer_id );
		}

		public function update_manufacturer() {	
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
				return false;
			}

			global $wpdb;

			$manufacturer_id = (int) $_POST['manufacturer_id'];			
			$name = sanitize_text_field( wp_unslash( $_POST['manufacturer_name'] ) );
			$post_slug = preg_replace( '/[^A-Za-z0-9\-]/', '', str_replace( ' ', '-', sanitize_text_field( wp_unslash( $_POST['post_slug'] ) ) ) );
			$post_id = (int) $_POST['post_id'];
			$post_excerpt = sanitize_text_field( wp_unslash( $_POST['post_excerpt'] ) );
			$featured_image = ( isset( $_POST['featured_image'] ) && '' != $_POST['featured_image'] ) ? (int) $_POST['featured_image'] : 0;

			$post = array(	
				'ID'			=> $post_id,
				'post_content'	=> '[ec_store manufacturerid="' . $manufacturer_id . '"]',
				'post_status'	=> 'publish',
				'post_title'	=> wp_easycart_language()->convert_text( $name ),
				'post_type'		=> 'ec_store',
				'post_name'		=> $post_slug,
				'post_excerpt'  => $post_excerpt
			);
			wp_update_post( $post );
			if ( 0 == $featured_image ) {
				delete_post_thumbnail( $post_id );
			} else {
				set_post_thumbnail( $post_id, $featured_image );
			}

			$wpdb->query( $wpdb->prepare( 'UPDATE ' . $wpdb->prefix . 'posts SET ' . $wpdb->prefix . 'posts.guid = %s WHERE ' . $wpdb->prefix . 'posts.ID = %d', get_permalink( $post_id ), $post_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_manufacturer SET name = %s WHERE manufacturer_id = %d', $name, $manufacturer_id ) );
			do_action( 'wpeasycart_manufacturer_updated', $manufacturer_id );

			return array( 'success' => 'manufacturer-updated' );
		}

		public function delete_manufacturer() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-manufacturer' ) ) {
				return false;
			}

			global $wpdb;
			$manufacturer_id = (int) $_GET['manufacturer_id'];
			do_action( 'wpeasycart_manufacturer_deleting', $manufacturer_id );
			$post_id = $wpdb->get_var( $wpdb->prepare( 'SELECT post_id FROM ec_manufacturer WHERE manufacturer_id = %d', $manufacturer_id ) );
			wp_delete_post( $post_id, true );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_manufacturer WHERE manufacturer_id = %d', $manufacturer_id ) );
			do_action( 'wpeasycart_manufacturer_deleted', $manufacturer_id );
			return array( 'success' => 'manufacturer-deleted' );
		}

		public function bulk_delete_manufacturer() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-manufacturers' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_manufacturer_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_manufacturer WHERE manufacturer_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_manufacturer_deleted', (int) $bulk_id );
			}
			return array( 'success' => 'manufacturer-deleted' );
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_manufacturers() {
	return wp_easycart_admin_manufacturers::instance();
}
wp_easycart_admin_manufacturers();

add_action( 'wp_ajax_ec_admin_ajax_create_new_manufacturer', 'ec_admin_ajax_create_new_manufacturer' );
function ec_admin_ajax_create_new_manufacturer() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-manufacturer-details' ) ) {
		return false;
	}

	$result = wp_easycart_admin_manufacturers()->insert_manufacturer();
	global $wpdb;
	$manufacturer_list = $wpdb->get_results( 'SELECT ec_manufacturer.manufacturer_id AS value, ec_manufacturer.name AS label FROM ec_manufacturer ORDER BY ec_manufacturer.name ASC' );

	echo '<option value="0">' . esc_attr__( 'Select One', 'wp-easycart' ) . '</option>';
	foreach( $manufacturer_list as $manufacturer ) {
		echo '<option value="' . esc_attr( $manufacturer->value ) . '"' . ( ( $manufacturer->value == $result['manufacturer_id'] ) ? ' selected="selected"' : '' ) . '>' . esc_attr( $manufacturer->label ) . '</option>';
	}
	echo '';
	die();
}
