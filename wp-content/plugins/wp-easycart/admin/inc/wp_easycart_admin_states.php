<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_states' ) ) :

	final class wp_easycart_admin_states {

		protected static $_instance = null;

		public $states_list_file;
		public $states_details_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->states_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/country-state/states-list.php';
			$this->states_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/country-state/states-details.php';
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_state' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_state' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_state' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_states' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_disable_states' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_enable_states' ) );
		}

		public function process_add_new_state() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-states' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function process_update_state() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-states' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function process_delete_state() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'states' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-state' == $_GET['ec_admin_form_action'] && isset( $_GET['id_sta'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function process_bulk_delete_states() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'states' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-state' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_sta'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function process_bulk_disable_states() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'states' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'bulk-enable-state' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_sta'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_enable_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function process_bulk_enable_states() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'states' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'bulk-disable-state' == $_GET['ec_admin_form_action'] && ! isset( $_GET['id_sta'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_disable_states();
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'states', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'states-inserted' == $_GET['success'] ) {
				$messages[] = __( 'State successfully created', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'states-updated' == $_GET['success'] ) {
				$messages[] = __( 'State successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'states-deleted' == $_GET['success'] ) {
				$messages[] = __( 'State successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'states-bulk-enabled' == $_GET['success'] ) {
				$messages[] = __( 'States successfully enabled', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'states-bulk-disabled' == $_GET['success'] ) {
				$messages[] = __( 'States successfully disabled', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_states_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['id_sta'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_states.php' );
				$details = new wp_easycart_admin_details_states();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->states_list_file );
			}
		}

		public function insert_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-states-details' ) ) {
				return false;
			}

			global $wpdb;

			$idcnt_sta = (int) $_POST['idcnt_sta'];
			$code_sta = sanitize_text_field( wp_unslash( $_POST['code_sta'] ) );
			$name_sta = sanitize_text_field( wp_unslash( $_POST['name_sta'] ) );
			$sort_order = (int) $_POST['sort_order'];
			$group_sta = sanitize_text_field( wp_unslash( $_POST['group_sta'] ) );
			$ship_to_active = 0;
			if ( isset( $_POST['ship_to_active'] ) ) {
				$ship_to_active = 1;
			}
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_state( idcnt_sta, code_sta, name_sta, sort_order, group_sta, ship_to_active ) VALUES( %d, %s, %s, %d, %s, %d )', $idcnt_sta, $code_sta, $name_sta, $sort_order, $group_sta, $ship_to_active ) );
			$id_sta = $wpdb->insert_id;
			do_action( 'wpeasycart_state_added', $id_sta );

			return array( 'success' => 'states-inserted' );
		}


		public function update_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-states-details' ) ) {
				return false;
			}

			global $wpdb;

			$id_sta = (int) $_POST['id_sta'];
			$idcnt_sta = sanitize_text_field( wp_unslash( $_POST['idcnt_sta'] ) );
			$code_sta = sanitize_text_field( wp_unslash( $_POST['code_sta'] ) );
			$name_sta = sanitize_text_field( wp_unslash( $_POST['name_sta'] ) );
			$sort_order = (int) $_POST['sort_order'];
			$group_sta = sanitize_text_field( wp_unslash( $_POST['group_sta'] ) ); 
			$ship_to_active = 0;
			if ( isset( $_POST['ship_to_active'] ) ) {
				$ship_to_active = 1;
			}
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET idcnt_sta = %d, code_sta = %s, name_sta = %s, sort_order = %d, group_sta = %s, ship_to_active = %d WHERE id_sta = %s', $idcnt_sta, $code_sta, $name_sta, $sort_order, $group_sta, $ship_to_active, $id_sta ) );
			do_action( 'wpeasycart_state_updated', $id_sta );

			return array( 'success' => 'states-updated' );
		}


		public function delete_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-state' ) ) {
				return false;
			}

			global $wpdb;
			$id_sta = (int) $_GET['id_sta'];
			do_action( 'wpeasycart_state_deleting', $id_sta );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_state WHERE id_sta = %d', $id_sta ) );
			do_action( 'wpeasycart_state_deleted', $id_sta );
			return array( 'success' => 'states-deleted' );
		}

		public function bulk_delete_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-states' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_state_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_state WHERE id_sta = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_state_deleted', (int) $bulk_id );
			}

			return array( 'success' => 'states-deleted' );
		}

		public function bulk_enable_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-states' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = 1 WHERE id_sta = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_state_updated', (int) $bulk_id );
			}
			return array( 'success' => 'states-bulk-enabled' );
		}

		public function bulk_disable_states() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-states' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_state SET ship_to_active = 0 WHERE ec_state.id_sta = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_state_updated', (int) $bulk_id );
			}
			return array( 'success' => 'states-bulk-disabled' );
		}
	}
endif;

function wp_easycart_admin_states() {
	return wp_easycart_admin_states::instance();
}
wp_easycart_admin_states();
