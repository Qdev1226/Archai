<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_user_role' ) ) :

	final class wp_easycart_admin_user_role {

		protected static $_instance = null;

		public $user_role_list_file;
		public $user_role_details_file;

		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		public function __construct() {
			$this->user_role_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/user-roles/user-role-list.php';
			$this->user_role_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/user-role/user-role-details.php';

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_user_role' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_user_role' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_user_role' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_user_role' ) );
		}

		public function process_add_new_user_role() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-user-role' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_user_role();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'user-roles', $result );
			}
		}

		public function process_update_user_role() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-user-role' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_user_role();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'user-roles', $result );
			}
		}

		public function process_delete_user_role() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-user-role' == $_GET['ec_admin_form_action'] && isset( $_GET['role_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_user_role();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'user-roles', $result );
			}
		}

		public function process_bulk_delete_user_role() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-user-role' == $_GET['ec_admin_form_action'] && ! isset( $_GET['role_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_user_role();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'user-roles', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'user-role-inserted' == $_GET['success'] ) {
				$messages[] = __( 'User role successfully inserted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-role-updated' == $_GET['success'] ) {
				$messages[] = __( 'User role successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-role-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Users role(s) successfully deleted', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'user-role-edit-master-error' == $_GET['error'] ) {
				$messages[] = __( 'You cannot edit the original admin or shopper roles', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'user-role-deleted-master-error' == $_GET['error'] ) {
				$messages[] = __( 'You cannot delete the original admin or shopper roles', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_user_role_list() {
			if ( ( isset( $_GET['role_id'] ) && isset( $_GET['ec_admin_form_action'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || ( isset( $_GET['ec_admin_form_action'] ) && 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_user_role.php' );
				$details = new wp_easycart_admin_details_user_role();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );

			} else {
				include( $this->user_role_list_file );
			}
		}

		public function insert_user_role() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-user-role-details' ) ) {
				return false;
			}

			global $wpdb;

			$role_label = sanitize_text_field( wp_unslash( $_POST['role_label'] ) );
			$admin_access = 0;
			if ( isset( $_POST['admin_access'] ) ) {
				$admin_access = 1;
			}

			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_role( role_label, admin_access ) VALUES( %s, %d )', $role_label, $admin_access ) );
			$role_id = $wpdb->insert_id;
			if ( $admin_access ) {
				$this->update_user_remote_access( $role_label );
			}
			do_action( 'wpeasycart_user_role_added', $role_id );
			return array( 'success' => 'user-role-inserted' );
		}

		public function update_user_role() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-user-role-details' ) ) {
				return false;
			}

			global $wpdb;

			$role_id = ( isset( $_POST['role_id'] ) ) ? (int) $_POST['role_id'] : 0;
			$old_role_label = ( isset( $_POST['old_role_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['old_role_label'] ) ) : '';
			$role_label = ( isset( $_POST['role_label'] ) ) ? sanitize_text_field( wp_unslash( $_POST['role_label'] ) ) : '';
			$admin_access = ( isset( $_POST['admin_access'] ) ) ? 1 : 0;

			if ( 1 == $role_id || 2 == $role_id ) {
				return array( 'error' => 'user-role-edit-master-error' );

			} else {
				if ( $old_label != $role_label ) {
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_roleaccess SET role_label = %s WHERE role_label = %s', $role_label, $old_role_label ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_roleprice SET role_label = %s WHERE role_label = %s', $role_label, $old_role_label ) );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET user_level = %s WHERE user_level = %s', $role_label, $old_role_label ) );
				}
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleaccess WHERE role_label = %s', $role_label ) );
				if ( $admin_access ) {
					$this->update_user_remote_access( $role_label );
				}
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_role SET role_label = %s, admin_access = %d WHERE role_id = %d', $role_label, $admin_access, $role_id ) );
				do_action( 'wpeasycart_user_role_updated', $role_id );
				return array( 'success' => 'user-role-updated' );
			}
		}

		private function update_user_remote_access( $role_label ) {
			global $wpdb;
			$panels = array();
			if ( isset( $_POST['orders_access'] ) && '1' == $_POST['orders_access'] ) {
				$panels[] = 'orders';
			}
			if ( isset( $_POST['downloads_access'] ) && '1' == $_POST['downloads_access'] ) {
				$panels[] = 'downloads';
			}
			if ( isset( $_POST['subscriptions_access'] ) && '1' == $_POST['subscriptions_access'] ) {
				$panels[] = 'subscriptions';
			}
			if ( isset( $_POST['products_access'] ) && '1' == $_POST['products_access'] ) {
				$panels[] = 'products';
			}
			if ( isset( $_POST['options_access'] ) && '1' == $_POST['options_access'] ) {
				$panels[] = 'options';
			}
			if ( isset( $_POST['menus_access'] ) && '1' == $_POST['menus_access'] ) {
				$panels[] = 'menus';
			}
			if ( isset( $_POST['manufacturers_access'] ) && '1' == $_POST['manufacturers_access'] ) {
				$panels[] = 'manufacturers';
			}
			if ( isset( $_POST['categories_access'] ) && '1' == $_POST['categories_access'] ) {
				$panels[] = 'categories';
			}
			if ( isset( $_POST['reviews_access'] ) && '1' == $_POST['reviews_access'] ) {
				$panels[] = 'reviews';
			}
			if ( isset( $_POST['plans_access'] ) && '1' == $_POST['plans_access'] ) {
				$panels[] = 'plans';
			}
			if ( isset( $_POST['users_access'] ) && '1' == $_POST['users_access'] ) {
				$panels[] = 'users';
			}
			if ( isset( $_POST['giftcards_access'] ) && '1' == $_POST['giftcards_access'] ) {
				$panels[] = 'giftcards';
			}
			if ( isset( $_POST['news_access'] ) && '1' == $_POST['news_access'] ) {
				$panels[] = 'news';
			}
			if ( isset( $_POST['newsletter_access'] ) && '1' == $_POST['newsletter_access'] ) {
				$panels[] = 'newsletter';
			}
			if ( isset( $_POST['coupons_access'] ) && '1' == $_POST['coupons_access'] ) {
				$panels[] = 'coupons';
			}
			if ( isset( $_POST['promotions_access'] ) && '1' == $_POST['promotions_access'] ) {
				$panels[] = 'promotions';
			}

			foreach ( $panels as $admin_panel ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_roleaccess( role_label, admin_panel ) VALUES( %s, %s )', $role_label, $admin_panel ) );
			}
		}

		public function delete_user_role() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-user-role' ) ) {
				return false;
			}

			global $wpdb;
			$role_id = ( isset( $_GET['role_id'] ) ) ? (int) $_GET['role_id'] : 0;
			if ( 1 == $role_id || 2 == $role_id ) {
				return array( 'error' => 'user-role-deleted-master-error' );

			} else {
				$role = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_role WHERE role_id = %d', $role_id ) );
				if ( $role ) {
					do_action( 'wpeasycart_user_role_deleting', $role->role_id );
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_role WHERE role_id = %d', $role->role_id ) );
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleaccess WHERE role_label = %s', $role->role_label ) );
					do_action( 'wpeasycart_user_role_deleted', $role->role_id );
				}
				return array( 'success' => 'user-role-deleted' );
			}
		}

		public function bulk_delete_user_role() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-user-roles' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$has_master = 0;
			foreach ( $bulk_ids as $bulk_id ) {
				if ( 1 == (int) $bulk_id || 2 == (int) $bulk_id ) {
					$has_master++;
				} else {
					$role = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_role WHERE role_id = %d', $bulk_id ) );
					if ( $role ) {
						do_action( 'wpeasycart_user_role_deleting', $role->role_id );
						$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_role WHERE role_id = %d', $role->role_id ) );
						$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_roleaccess WHERE role_label = %s', $role->role_label ) );
						do_action( 'wpeasycart_user_role_deleted', $role->role_id );
					}
				}
			}

			if ( $has_master ) {
				return array( 'error' => 'user-role-deleted-master-error' );
			} else {
				return array( 'success' => 'user-role-deleted' );
			}
		}
	}
endif;

function wp_easycart_admin_user_role() {
	return wp_easycart_admin_user_role::instance();
}
wp_easycart_admin_user_role();
