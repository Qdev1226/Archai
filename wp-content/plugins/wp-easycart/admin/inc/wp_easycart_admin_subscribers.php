<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_subscribers' ) ) :

	final class wp_easycart_admin_subscribers {

		protected static $_instance = null;

		public $subscriber_list_file;
		public $subscriber_details_file;
		public $export_subscriber_csv;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->subscriber_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/subscribers/subscribers-list.php';
			$this->subscriber_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/subscriber/subscribers-details.php';
			$this->export_subscriber_csv = EC_PLUGIN_DIRECTORY . '/admin/template/exporters/export-subscribers-csv.php';

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_subscriber' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_subscriber' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_subscriber' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_subscriber' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_subscribers' ) );
		}

		public function process_add_new_subscriber() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-subscriber' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_subscriber();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'subscribers', $result );
			}
		}

		public function process_update_subscriber() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-subscriber' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_subscriber();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'subscribers', $result );
			}
		}

		public function process_delete_subscriber() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'subscribers' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-subscriber' == $_GET['ec_admin_form_action'] && isset( $_GET['subscriber_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->delete_subscriber();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'subscribers', $result );
			}
		}

		public function process_bulk_delete_subscriber() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['subpage'] ) && 'subscribers' == $_GET['subpage'] && isset( $_GET['ec_admin_form_action'] ) && 'delete-subscriber' == $_GET['ec_admin_form_action'] && ! isset( $_GET['subscriber_id'] ) && isset( $_GET['bulk'] ) ) {
				$result = $this->bulk_delete_subscriber();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'subscribers', $result );
			}
		}

		public function process_export_subscribers() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && ( 'export-subscribers-csv' == $_GET['ec_admin_form_action'] || 'export-subscribers-csv-all' == $_GET['ec_admin_form_action'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-subscribers' ) ) {
					include( $this->export_subscriber_csv );
					die();
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'subscriber-inserted' == $_GET['success'] ) {
				$messages[] = __( 'Subscriber successfully inserted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'subscriber-updated' == $_GET['success'] ) {
				$messages[] = __( 'Subscriber successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'subscriber-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Subscriber(s) successfully deleted', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'user-role-edit-master-error' == $_GET['error'] ) {
				$messages[] = __( 'You cannot edit the original admin or shopper roles', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_subscriber_list() {
			if ( ( isset( $_GET['subscriber_id'] ) && isset( $_GET['ec_admin_form_action'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || ( isset( $_GET['ec_admin_form_action'] ) && 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_subscribers.php' );
				$details = new wp_easycart_admin_details_subscribers();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->subscriber_list_file );
			}
		}

		public function insert_subscriber() {
			if( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-subscriber-details' ) ) {
				return false;
			}

			global $wpdb;

			$email = sanitize_email( wp_unslash( $_POST['email'] ) );
			$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
			$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE email = %s', $email ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_subscriber( email, first_name, last_name ) VALUES( %s, %s, %s )', $email, $first_name, $last_name ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET is_subscriber = 1 WHERE email = %s', $email) );

			return array( 'success' => 'subscriber-inserted' );
		}

		public function update_subscriber() {
			if( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-subscriber-details' ) ) {
				return false;
			}

			global $wpdb;

			$email = sanitize_email( wp_unslash( $_POST['email'] ) );
			$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
			$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE email = %s', $email ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_subscriber( email, first_name, last_name ) VALUES( %s, %s, %s )', $email, $first_name, $last_name ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET is_subscriber = 1 WHERE email = %s', $email) );
			do_action( 'wpeasycart_subscriber_updated', $email );

			return array( 'success' => 'subscriber-updated' );
		}

		public function delete_subscriber() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-subscriber' ) ) {
				return false;
			}

			global $wpdb;
			$subscriber_id = (int) $_GET['subscriber_id'];
			$email = $wpdb->get_var( $wpdb->prepare( 'SELECT email FROM ec_subscriber WHERE subscriber_id = %d', $subscriber_id ) );
			do_action( 'wpeasycart_subscriber_deleting', $subscriber_id );

			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE email = %s', $email ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET is_subscriber = 0 WHERE email = %s', $email ) );
			do_action( 'wpeasycart_subscriber_deleted', $email );

			array( 'success' => 'subscriber-deleted' );
		}

		public function bulk_delete_subscriber() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-subscribers' ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$email = $wpdb->get_var( $wpdb->prepare( 'SELECT email FROM ec_subscriber WHERE subscriber_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_subscriber_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE email = %s', $email ) );
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET is_subscriber = 0 WHERE email = %s', $email ) );
				do_action( 'wpeasycart_subscriber_deleted', $email );
			}

			array( 'success' => 'subscriber-deleted' );
		}
	}
endif;

function wp_easycart_admin_subscribers() {
	return wp_easycart_admin_subscribers::instance();
}
wp_easycart_admin_subscribers();
