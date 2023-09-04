<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_users' ) ) :

	final class wp_easycart_admin_users {

		protected static $_instance = null;

		public $users_list_file;
		public $users_details_file;
		public $export_accounts_csv;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->users_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/users/user-list.php';
			$this->users_details_file = EC_PLUGIN_DIRECTORY . '/admin/template/users/users/user-details.php';
			$this->export_accounts_csv = EC_PLUGIN_DIRECTORY . '/admin/template/exporters/export-accounts-csv.php';

			/* Process Admin Messages */
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			/* Process Form Actions */
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_add_new_user' ) );
			add_action( 'wp_easycart_process_post_form_action', array( $this, 'process_update_user' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_login_as_user' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_user' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_user' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_users' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_force_password_reset' ) );
		}

		public function process_add_new_user() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'add-new-user' == $_POST['ec_admin_form_action'] ) {
				$result = $this->insert_user();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function process_update_user() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_POST['ec_admin_form_action'] ) && 'update-user' == $_POST['ec_admin_form_action'] ) {
				$result = $this->update_user();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function process_login_as_user() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && 'user-login-override' == $_GET['ec_admin_form_action'] && isset( $_GET['user_id'] ) && ! isset( $_GET['bulk'] ) ) {
				$result = $this->login_as_user();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function process_delete_user() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && isset( $_GET['user_id'] ) && ! isset( $_GET['bulk'] ) && 'delete-account' == $_GET['ec_admin_form_action'] ) {
				$result = $this->delete_user();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function process_bulk_delete_user() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && ! isset( $_GET['user_id'] ) && isset( $_GET['bulk'] ) && 'delete-account' == $_GET['ec_admin_form_action'] ) {
				$result = $this->bulk_delete_user();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function process_export_users() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && ( 'export-accounts-csv' == $_GET['ec_admin_form_action'] || 'export-accounts-csv-all' == $_GET['ec_admin_form_action'] ) ) {
				include( $this->export_accounts_csv );
				die();
			}
		}

		public function process_force_password_reset() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_users' ) ) {
				return false;
			}
			if ( isset( $_GET['ec_admin_form_action'] ) && ! isset( $_GET['user_id'] ) && isset( $_GET['bulk'] ) && 'accounts-force-password-reset' == $_GET['ec_admin_form_action'] ) {
				$result = $this->bulk_force_password_reset();
				wp_easycart_admin()->redirect( 'wp-easycart-users', 'accounts', $result );
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'user-inserted' == $_GET['success'] ) {
				$messages[] = __( 'User successfully inserted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-updated' == $_GET['success'] ) {
				$messages[] = __( 'User successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Users(s) successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-logged-in' == $_GET['success'] ) {
				$messages[] = __( 'You are now logged in as this user. Please use caution when viewing the store.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'user-password-reset' == $_GET['success'] ) {
				$messages[] = __( 'User(s) passwords were successfully reset and emailed with information to update their password.', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'user-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'User email already exists', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_users_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['user_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_user.php' );
				$details = new wp_easycart_admin_details_user();
				$details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->users_list_file );
			}
		}

		public function insert_user() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-user-details' ) ) {
				return false;
			}

			if ( ! isset( $_POST['email'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['password'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['first_name'] ) ) {
				return false;
			}

			if ( ! isset( $_POST['last_name'] ) ) {
				return false;
			}

			global $wpdb;

			$email = sanitize_email( wp_unslash( $_POST['email'] ) );
			$email_other = ( isset( $_POST['email_other'] ) ) ? sanitize_email( wp_unslash( $_POST['email_other'] ) ) : '';
			$password = md5( wp_unslash( $_POST['password'] ) ); // XSS OK, Do not sanitize passwords.
			$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
			$last_name = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );

			$user_level = ( isset( $_POST['user_level'] ) ) ? sanitize_text_field( wp_unslash( $_POST['user_level'] ) ) : 'shopper';
			$user_notes = ( isset( $_POST['user_notes'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['user_notes'] ) ) : '';
			$vat_registration_number = ( isset( $_POST['vat_registration_number'] ) ) ? sanitize_text_field( wp_unslash( $_POST['vat_registration_number'] ) ) : '';

			$is_subscriber = ( isset( $_POST['is_subscriber'] ) ) ? 1 : 0;
			$exclude_tax = ( isset( $_POST['exclude_tax'] ) ) ? 1 : 0;
			$exclude_shipping = ( isset( $_POST['exclude_shipping'] ) ) ? 1 : 0;

			$billing_first_name = ( isset( $_POST['billing_first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
			$billing_last_name = ( isset( $_POST['billing_last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : '';
			$billing_company_name = ( isset( $_POST['billing_company_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_company_name'] ) ) : '';
			$billing_address_line_1 = ( isset( $_POST['billing_address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_line_1'] ) ) : '';
			$billing_address_line_2 = ( isset( $_POST['billing_address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_line_2'] ) ) : '';
			$billing_city = ( isset( $_POST['billing_city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) : '';
			$billing_state = ( isset( $_POST['billing_state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_state'] ) ) : '';
			$billing_zip = ( isset( $_POST['billing_zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_zip'] ) ) : '';
			$billing_country = ( isset( $_POST['billing_country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : '';
			$billing_phone = ( isset( $_POST['billing_phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';

			$shipping_first_name = ( isset( $_POST['shipping_first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_first_name'] ) ) : '';
			$shipping_last_name = ( isset( $_POST['shipping_last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_last_name'] ) ) : '';
			$shipping_company_name = ( isset( $_POST['shipping_company_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_company_name'] ) ) : '';
			$shipping_address_line_1 = ( isset( $_POST['shipping_address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_line_1'] ) ) : '';
			$shipping_address_line_2 = ( isset( $_POST['shipping_address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_line_2'] ) ) : '';
			$shipping_city = ( isset( $_POST['shipping_city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_city'] ) ) : '';
			$shipping_state = ( isset( $_POST['shipping_state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_state'] ) ) : '';
			$shipping_zip = ( isset( $_POST['shipping_zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_zip'] ) ) : '';
			$shipping_country = ( isset( $_POST['shipping_country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_country'] ) ) : '';
			$shipping_phone = ( isset( $_POST['shipping_phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_phone'] ) ) : '';

			$duplicate = $wpdb->query( $wpdb->prepare( 'SELECT * FROM ec_user WHERE ec_user.email = %s', $email ) );

			if ( ! $duplicate ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_user( email, password, first_name, last_name, user_level, is_subscriber, exclude_tax, exclude_shipping, user_notes, vat_registration_number, email_other ) VALUES( %s, %s, %s, %s, %s, %d, %d, %d, %s, %s, %s )', $email, $password, $first_name, $last_name, $user_level, $is_subscriber, $exclude_tax, $exclude_shipping, $user_notes, $vat_registration_number, $email_other ) );
				$user_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_address( user_id, first_name, last_name, company_name, address_line_1, address_line_2, city, state, zip, country, phone ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $user_id, $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone ) );
				$billing_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_address( user_id, first_name, last_name, company_name, address_line_1, address_line_2, city, state, zip, country, phone ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $user_id, $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone ) );
				$shipping_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET default_billing_address_id = %d, default_shipping_address_id = %d WHERE user_id = %d', $billing_id, $shipping_id, $user_id ) );

				if ( $is_subscriber ) {
					$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE ec_subscriber.email = %s', $email ) );
					$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_subscriber( email, first_name, last_name ) VALUES( %s, %s, %s )', $email, $first_name, $last_name ) );
				} else {
					$remove_subscriber = $wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE email = %s', $email ) );
				}

				if ( function_exists( 'mymail' ) ) {
					mymail( 'subscribers' )->add(
						array(
							'firstname' => $first_name,
							'lastname' => $last_name,
							'email' => $email,
							'status' => 1,
						),
						false
					);
				}

				if ( file_exists( '../../../../wp-easycart-quickbooks/QuickBooks.php' ) ) {
					$quickbooks = new ec_quickbooks();
					$quickbooks->add_user( $user_id );
				}

				do_action( 'wpeasycart_account_added', $user_id, $email, wp_unslash( $_POST['password'] ) ); // XSS OK. Do not sanitize password.

				return array( 'success' => 'user-inserted' );

			} else {
				return array( 'error' => 'user-duplicate' );
			}
		}

		public function update_user() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-user-details' ) ) {
				return false;
			}

			global $wpdb;

			$user_id = ( isset( $_POST['user_id'] ) ) ? (int) $_POST['user_id'] : 0;
			$first_name = ( isset( $_POST['first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
			$last_name = ( isset( $_POST['last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
			$email = ( isset( $_POST['email'] ) ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
			$email_other = ( isset( $_POST['email_other'] ) ) ? sanitize_email( wp_unslash( $_POST['email_other'] ) ) : '';
			$user_level = ( isset( $_POST['user_level'] ) ) ? sanitize_text_field( wp_unslash( $_POST['user_level'] ) ) : 'shopper';
			$password = ( isset( $_POST['password'] ) ) ? wp_unslash( $_POST['password'] ) : ''; // XSS OK. Do not sanitize passwords.

			$user_notes = ( isset( $_POST['user_notes'] ) ) ? sanitize_textarea_field( wp_unslash( $_POST['user_notes'] ) ) : '';
			$vat_registration_number = ( isset( $_POST['vat_registration_number'] ) ) ? sanitize_text_field( wp_unslash( $_POST['vat_registration_number'] ) ) : '';
			$is_subscriber = ( isset( $_POST['is_subscriber'] ) ) ? 1 : 0;
			$exclude_tax = ( isset( $_POST['exclude_tax'] ) ) ? 1 : 0;
			$exclude_shipping = ( isset( $_POST['exclude_shipping'] ) ) ? 1 : 0;

			$default_billing_address_id = ( isset( $_POST['default_billing_address_id'] ) ) ? (int) $_POST['default_billing_address_id'] : 0;
			$billing_first_name = ( isset( $_POST['billing_first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
			$billing_last_name = ( isset( $_POST['billing_last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : '';
			$billing_company_name = ( isset( $_POST['billing_company_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_company_name'] ) ) : '';
			$billing_address_line_1 = ( isset( $_POST['billing_address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_line_1'] ) ) : '';
			$billing_address_line_2 = ( isset( $_POST['billing_address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_line_2'] ) ) : '';
			$billing_city = ( isset( $_POST['billing_city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) : '';
			$billing_state = ( isset( $_POST['billing_state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_state'] ) ) : '';
			$billing_zip = ( isset( $_POST['billing_zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_zip'] ) ) : '';
			$billing_country = ( isset( $_POST['billing_country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : '';
			$billing_phone = ( isset( $_POST['billing_phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) : '';

			$default_shipping_address_id = ( isset( $_POST['default_shipping_address_id'] ) ) ? (int) $_POST['default_shipping_address_id'] : 0;
			$shipping_first_name = ( isset( $_POST['shipping_first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_first_name'] ) ) : '';
			$shipping_last_name = ( isset( $_POST['shipping_last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_last_name'] ) ) : '';
			$shipping_company_name = ( isset( $_POST['shipping_company_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_company_name'] ) ) : '';
			$shipping_address_line_1 = ( isset( $_POST['shipping_address_line_1'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_line_1'] ) ) : '';
			$shipping_address_line_2 = ( isset( $_POST['shipping_address_line_2'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_address_line_2'] ) ) : '';
			$shipping_city = ( isset( $_POST['shipping_city'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_city'] ) ) : '';
			$shipping_state = ( isset( $_POST['shipping_state'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_state'] ) ) : '';
			$shipping_zip = ( isset( $_POST['shipping_zip'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_zip'] ) ) : '';
			$shipping_country = ( isset( $_POST['shipping_country'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_country'] ) ) : '';
			$shipping_phone = ( isset( $_POST['shipping_phone'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shipping_phone'] ) ) : '';

			$old_email = $wpdb->get_var( $wpdb->prepare( 'SELECT email FROM ec_user WHERE user_id = %d', $user_id ) );

			if ( strtolower( $old_email ) != strtolower( $email ) ) {
				$duplicate = $wpdb->query( $wpdb->prepare( 'SELECT * FROM ec_user WHERE ec_user.email = %s', $email ) );
				if ( $duplicate ) {
					return array( 'error' => 'user-duplicate' );
				}
			}

			if ( 0 == $default_billing_address_id ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_address( user_id, first_name, last_name,  company_name, address_line_1, address_line_2, city, state, zip, country, phone ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $user_id, $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone ) );
				$billing_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET default_billing_address_id = %d WHERE user_id = %d', $billing_id, $user_id ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_address SET first_name = %s, last_name = %s, company_name = %s, address_line_1 = %s, address_line_2 = %s, city = %s, state = %s, zip = %s, country = %s, phone = %s WHERE address_id = %d', $billing_first_name, $billing_last_name, $billing_company_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $default_billing_address_id ) );
			}

			if ( 0 == $default_shipping_address_id ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_address( user_id, first_name, last_name,  company_name, address_line_1, address_line_2, city, state, zip, country, phone ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )', $user_id, $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone ) );
				$shipping_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET default_shipping_address_id = %d WHERE user_id = %d', $shipping_id, $user_id ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_address SET first_name = %s, last_name = %s, company_name = %s, address_line_1 = %s, address_line_2 = %s, city = %s, state = %s, zip = %s, country = %s, phone = %s WHERE address_id = %d', $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone, $default_shipping_address_id ) );
			}

			if ( $is_subscriber ) {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE ec_subscriber.email = %s', $email ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_subscriber( email, first_name , last_name ) VALUES( %s, %s, %s )', $email, $first_name, $last_name ) );
			} else {
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_subscriber WHERE ec_subscriber.email = %s', $email ) );
			}

			if ( '' == $password ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET email = %s, first_name = %s, last_name = %s, user_level = %s, is_subscriber = %d, exclude_tax = %d, exclude_shipping = %d, user_notes = %s, vat_registration_number = %s, email_other = %s WHERE ec_user.user_id = %d', $email, $first_name, $last_name, $user_level, $is_subscriber, $exclude_tax, $exclude_shipping, $user_notes, $vat_registration_number, $email_other, $user_id ) );

			} else {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET email = %s, password = %s, first_name = %s, last_name = %s, user_level = %s, is_subscriber = %d, exclude_tax = %d, exclude_shipping = %d, user_notes = %s, vat_registration_number = %s, email_other = %s WHERE user_id = %d', $email, md5( $password ), $first_name, $last_name, $user_level, $is_subscriber, $exclude_tax, $exclude_shipping, $user_notes, $vat_registration_number, $email_other, $user_id ) );
			}

			if ( file_exists( '../../../../wp-easycart-quickbooks/QuickBooks.php' ) ) {
				$quickbooks = new ec_quickbooks();
				$quickbooks->update_user_admin( $user_id );
			}

			do_action( 'wpeasycart_account_updated', $user_id );

			return array( 'success' => 'user-updated' );
		}

		public function login_as_user() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-login-as-user' ) ) {
				return false;
			}

			if ( ! isset( $_GET['user_id'] ) ) {
				return;
			}

			global $wpdb;
			wpeasycart_session()->handle_session();

			$user_id = (int) $_GET['user_id'];
			$user = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ec_user WHERE user_id = %d', $user_id ) );
			$GLOBALS['ec_cart_data']->cart_data->user_id = (int) $user->user_id;
			$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $user->email );
			$GLOBALS['ec_cart_data']->cart_data->email_other = sanitize_email( $user->email_other );
			$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( wp_unslash( $user->first_name ) ) . ' ' . sanitize_text_field( wp_unslash( $user->last_name ) );
			$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( wp_unslash( $user->first_name ) );
			$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( wp_unslash( $user->last_name ) );
			$GLOBALS['ec_cart_data']->cart_data->is_guest = '';
			$GLOBALS['ec_cart_data']->cart_data->guest_key = '';
			$GLOBALS['ec_cart_data']->save_session_to_db();

			wp_cache_flush();
			do_action( 'wpeasycart_login_success', sanitize_email( $user->email ) );

			return array(
				'ec_admin_form_action' => 'edit',
				'user_id' => $user_id,
				'success' => 'user-logged-in',
			);
		}

		public function delete_user() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-account' ) ) {
				return false;
			}

			if ( ! isset( $_GET['user_id'] ) ) {
				return;
			}

			global $wpdb;
			$user_id = (int) $_GET['user_id'];
			do_action( 'wpeasycart_account_deleting', $user_id );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_address WHERE user_id = %d', $user_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_user WHERE user_id = %d', $user_id ) );
			do_action( 'wpeasycart_account_deleted', $user_id );
			return array( 'success' => 'user-deleted' );
		}

		public function bulk_delete_user() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-accounts' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;

			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_account_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_address WHERE user_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_user WHERE user_id = %d', (int) $bulk_id ) );
				do_action( 'wpeasycart_account_deleted', (int) $bulk_id );
			}

			return array( 'success' => 'user-deleted' );
		}

		public function bulk_force_password_reset() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-accounts' ) ) {
				return false;
			}

			if ( ! isset( $_GET['bulk'] ) ) {
				return false;
			}

			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$user = $wpdb->get_row( $wpdb->prepare( 'SELECT email, first_name, last_name FROM ec_user WHERE user_id = %d', (int) $bulk_id ) );
				if ( $user ) {
					$new_password = $this->get_random_password();
					$password = md5( $new_password );
					$password = apply_filters( 'wpeasycart_password_hash', $password, $new_password );
					$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET password = %s WHERE user_id = %d', $password, (int) $bulk_id ) );
					$this->send_new_password_email( $user, $new_password );
				}
			}

			return array( 'success' => 'user-password-reset' );
		}

		private function send_new_password_email( $user, $new_password ) {

			$email = $user->email;
			$email_logo_url = get_option( 'ec_option_email_logo' );

			$storepageid = get_option( 'ec_option_storepage' );
			if ( function_exists( 'icl_object_id' ) ) {
				$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			}
			$store_page = get_permalink( $storepageid );
			if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$store_page = $https_class->makeUrlHttps( $store_page );
			}

			if ( substr_count( $store_page, '?' ) ) {
				$permalink_divider = '&';
			} else {
				$permalink_divider = '?';
			}

			// Get receipt
			ob_start();
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_retrieve_password_email.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_retrieve_password_email.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_retrieve_password_email.php' );
			}
			$message = ob_get_contents();
			ob_end_clean();

			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/html; charset=utf-8';
			$headers[] = 'From: ' . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = 'Reply-To: ' . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = 'X-Mailer: PHP/' . phpversion();

			$email_send_method = get_option( 'ec_option_use_wp_mail' );
			$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

			if ( '1' == $email_send_method ) {
				wp_mail( $email, wp_easycart_language()->get_text( 'account_forgot_password_email', 'account_forgot_password_email_title' ), $message, implode( "\r\n", $headers ) );

			} else if ( '0' == $email_send_method ) {
				$to = $email;
				$subject = wp_easycart_language()->get_text( 'account_forgot_password_email', 'account_forgot_password_email_title' );
				$mailer = new wpeasycart_mailer();
				$mailer->send_customer_email( $to, $subject, $message );

			} else {
				do_action( 'wpeasycart_custom_forgot_password_email', stripslashes( get_option( 'ec_option_password_from_email' ) ), $email, '', wp_easycart_language()->get_text( 'account_forgot_password_email', 'account_forgot_password_email_title' ), $message );

			}

		}

		private function get_random_password() {
			$rand_chars = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J' );
			$rand_password = $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . $rand_chars[ rand( 0, 9 ) ] . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 ) . rand( 0, 9 );
			return $rand_password;
		}

		public function check_existing_email() {
			global $wpdb;
			if ( isset( $_POST['email'] ) ) {
				$email = sanitize_email( wp_unslash( $_POST['email'] ) );
				$user_id = ( isset( $_POST['user_id'] ) ) ? (int) $_POST['user_id'] : 0;
				$emails = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_user WHERE ec_user.email = %s AND ec_user.user_id != %d', $email, $user_id ) );

				if ( count( $emails ) > 0 ) {
					esc_attr_e( 'Email Already Exist', 'wp-easycart' );
				} else {
					esc_attr_e( 'OK', 'wp-easycart' );
				}
			}
		}

		public function run_importer() {

			global $wpdb;
			$error_list = '';
			$email_index = -1;
			$first_name_index = -1;
			$last_name_index = -1;
			$user_level_index = -1;

			$billing_first_name_index = -1;
			$billing_last_name_index = -1;
			$billing_company_name_index = -1;
			$billing_address_line_1_index = -1;
			$billing_address_line_2_index = -1;
			$billing_city_index = -1;
			$billing_state_index = -1;
			$billing_zip_index = -1;
			$billing_country_index = -1;
			$billing_phone_index = -1;

			$shipping_first_name_index = -1;
			$shipping_last_name_index = -1;
			$shipping_company_name_index = -1;
			$shipping_address_line_1_index = -1;
			$shipping_address_line_2_index = -1;
			$shipping_city_index = -1;
			$shipping_state_index = -1;
			$shipping_zip_index = -1;
			$shipping_country_index = -1;
			$shipping_phone_index = -1;

			$limit = 20;

			ini_set( 'auto_detect_line_endings', '1' );

			require_once( 'Encoding.php' );

			if ( isset( $_POST['import_file_url'] ) ) {

				set_time_limit( 500 );

				$file_path = get_attached_file( (int) $_POST['import_file_url'] );
				if ( ! $file_path ) {
					echo esc_attr__( 'Invalid file path.', 'wp-easycart' );
					return;
				}

				$file_type = wp_check_filetype( $file_path );
				if ( ! $file_type || ! isset( $file_type['ext'] ) || ! isset( $file_type['type'] ) || 'csv' != $file_type['ext'] || 'text/csv' != $file_type['type'] ) {
					echo esc_attr__( 'Invalid file type.', 'wp-easycart' );
					return;
				}

				$file =  fopen( esc_url_raw( $file_path ), 'r' );
				if ( ! $file ) {
					echo esc_attr__( 'Could not open your import file.', 'wp-easycart' );
					return;
				}

				$valid_headers_result = $wpdb->get_results( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_NAME`='ec_user'", ARRAY_N );
				$valid_headers = array();
				foreach ( $valid_headers_result as $header ) {
					$valid_headers[] = $header[0];
				}
				$headers = fgetcsv( $file );

				$headers_count = count( $headers );
				for ( $i = 0; $i < $headers_count; $i++ ) {

					$headers[ $i ] = trim( $headers[ $i ] );

					if ( 'email' == $headers[ $i ] ) {
						$email_index = $i;

					} else if ( 'user_level' == $headers[ $i ] ) {
						$user_level_index = $i;

					} else if ( 'first_name' == $headers[ $i ] ) {
						$first_name_index = $i;

					} else if ( 'last_name' == $headers[ $i ] ) {
						$last_name_index = $i;

					} else if ( 'billing_first_name' == $headers[ $i ] ) {
						$billing_first_name_index = $i;

					} else if ( 'billing_last_name' == $headers[ $i ] ) {
						$billing_last_name_index = $i;

					} else if ( 'billing_company_name' == $headers[ $i ] ) {
						$billing_company_name_index = $i;

					} else if ( 'billing_address_line_1' == $headers[ $i ] ) {
						$billing_address_line_1_index = $i;

					} else if ( 'billing_address_line_2' == $headers[ $i ] ) {
						$billing_address_line_2_index = $i;

					} else if ( 'billing_city' == $headers[ $i ] ) {
						$billing_city_index = $i;

					} else if ( 'billing_state' == $headers[ $i ] ) {
						$billing_state_index = $i;

					} else if ( 'billing_zip' == $headers[ $i ] ) {
						$billing_zip_index = $i;

					} else if ( 'billing_country' == $headers[ $i ] ) {
						$billing_country_index = $i;

					} else if ( 'billing_phone' == $headers[ $i ] ) {
						$billing_phone_index = $i;

					} else if ( 'shipping_first_name' == $headers[ $i ] ) {
						$shipping_first_name_index = $i;

					} else if ( 'shipping_last_name' == $headers[ $i ] ) {
						$shipping_last_name_index = $i;

					} else if ( 'shipping_company_name' == $headers[ $i ] ) {
						$shipping_company_name_index = $i;

					} else if ( 'shipping_address_line_1' == $headers[ $i ] ) {
						$shipping_address_line_1_index = $i;

					} else if ( 'shipping_address_line_2' == $headers[ $i ] ) {
						$shipping_address_line_2_index = $i;

					} else if ( 'shipping_city' == $headers[ $i ] ) {
						$shipping_city_index = $i;

					} else if ( 'shipping_state' == $headers[ $i ] ) {
						$shipping_state_index = $i;

					} else if ( 'shipping_zip' == $headers[ $i ] ) {
						$shipping_zip_index = $i;

					} else if ( 'shipping_country' == $headers[ $i ] ) {
						$shipping_country_index = $i;

					} else if ( 'shipping_phone' == $headers[ $i ] ) {
						$shipping_phone_index = $i;

					} else if ( ! in_array( $headers[ $i ], $valid_headers ) ) {
						if ( 'billing_address_id' != $headers[ $i ] && 'billing_user_id' != $headers[ $i ] && 'shipping_address_id' != $headers[ $i ] && 'shipping_user_id' != $headers[ $i ] && 'customer_value' != $headers[ $i ] ) {
							/* translators: %1$d is replaced with a column header index, %2$s is replaced with the column header value. */
							echo sprintf( esc_attr__( 'You have an invalid column header at column %1$d (value %2$s), please remove or correct the label of that column to continue.', 'wp-easycart' ), esc_attr( $i ), esc_attr( $headers[ $i ] ) );
						}
					}
				}

				if ( -1 == $email_index ) {
					esc_attr_e( 'Missing `email` Key field! Unique values are required.', 'wp-easycart' );
				}

				if ( -1 == $first_name_index ) {
					esc_attr_e( 'Missing `first_name` Key field! Some value is required.', 'wp-easycart' );
				}

				if ( -1 == $last_name_index ) {
					esc_attr_e( 'Missing `last_name` Key field! Some value is required.', 'wp-easycart' );
				}

				$current_iteration = 0;
				$eof_reached = false;

				while ( ! feof( $file ) && ! $eof_reached ) {
					$rows = array();
					for ( $current_row = 0; ! feof( $file ) && ! $eof_reached && $current_row < $limit; $current_row++ ) {
						$this_row = fgetcsv( $file );
						if ( ! is_array( $this_row ) || ! isset( $this_row[ $email_index ] ) || strlen( trim( $this_row[ $email_index ] ) ) <= 0 ) {
							$eof_reached = true;
						} else {
							$rows[] = $this_row;
						}
					}

					$rows_count = count( $rows );
					for ( $i = 0; $i < $rows_count; $i++ ) {
						$wpdb->query(
							$wpdb->prepare(
								'INSERT INTO ec_user( `email`, `password`, `first_name`, `last_name`, `user_level` ) VALUES( %s, %s, %s, %s, %s)',
								$rows[ $i ][ $email_index ],
								md5( rand( 999999999999, 999999999999999 ) ),
								$rows[ $i ][ $first_name_index ],
								$rows[ $i ][ $last_name_index ],
								( ( -1 != $user_level_index && '' != $rows[ $i ][ $user_level_index ] ) ? $rows[ $i ][ $user_level_index ] : 'shopper' )
							)
						);
						$user_id = $wpdb->insert_id;
						$billing_address_id = 0;
						$shipping_address_id = 0;

						if ( $user_id ) {
							$wpdb->query(
								$wpdb->prepare(
									'INSERT INTO ec_address( `user_id`, `first_name`, `last_name`, `company_name`, `address_line_1`, `address_line_2`, `city`, `state`, `zip`, `country`, `phone` ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )',
									$user_id,
									( ( -1 != $billing_first_name_index ) ? $rows[ $i ][ $billing_first_name_index ] : '' ),
									( ( -1 != $billing_last_name_index ) ? $rows[ $i ][ $billing_last_name_index ] : '' ),
									( ( -1 != $billing_company_name_index ) ? $rows[ $i ][ $billing_company_name_index ] : '' ),
									( ( -1 != $billing_address_line_1_index ) ? $rows[ $i ][ $billing_address_line_1_index ] : '' ),
									( ( -1 != $billing_address_line_2_index ) ? $rows[ $i ][ $billing_address_line_2_index ] : '' ),
									( ( -1 != $billing_city_index ) ? $rows[ $i ][ $billing_city_index ] : '' ),
									( ( -1 != $billing_state_index ) ? $rows[ $i ][ $billing_state_index ] : '' ),
									( ( -1 != $billing_zip_index ) ? $rows[ $i ][ $billing_zip_index ] : '' ),
									( ( -1 != $billing_country_index ) ? $rows[ $i ][ $billing_country_index ] : '' ),
									( ( -1 != $billing_phone_index ) ? $rows[ $i ][ $billing_phone_index ] : '' )
								)
							);
							$billing_address_id = $wpdb->insert_id;
							$wpdb->query(
								$wpdb->prepare(
									'INSERT INTO ec_address( `user_id`, `first_name`, `last_name`, `company_name`, `address_line_1`, `address_line_2`, `city`, `state`, `zip`, `country`, `phone` ) VALUES( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s )',
									$user_id,
									( ( -1 != $shipping_first_name_index ) ? $rows[ $i ][ $shipping_first_name_index ] : '' ),
									( ( -1 != $shipping_last_name_index ) ? $rows[ $i ][ $shipping_last_name_index ] : '' ),
									( ( -1 != $shipping_company_name_index ) ? $rows[ $i ][ $shipping_company_name_index ] : '' ),
									( ( -1 != $shipping_address_line_1_index ) ? $rows[ $i ][ $shipping_address_line_1_index ] : '' ),
									( ( -1 != $shipping_address_line_2_index ) ? $rows[ $i ][ $shipping_address_line_2_index ] : '' ),
									( ( -1 != $shipping_city_index ) ? $rows[ $i ][ $shipping_city_index ] : '' ),
									( ( -1 != $shipping_state_index ) ? $rows[ $i ][ $shipping_state_index ] : '' ),
									( ( -1 != $shipping_zip_index ) ? $rows[ $i ][ $shipping_zip_index ] : '' ),
									( ( -1 != $shipping_country_index ) ? $rows[ $i ][ $shipping_country_index ] : '' ),
									( ( -1 != $shipping_phone_index ) ? $rows[ $i ][ $shipping_phone_index ] : '' )
								)
							);
							$shipping_address_id = $wpdb->insert_id;
							$wpdb->query( $wpdb->prepare( 'UPDATE ec_user SET default_billing_address_id = %d, default_shipping_address_id = %d WHERE user_id = %d', $billing_address_id, $shipping_address_id, $user_id ) );
						}
					}
					unset( $rows );
					$current_iteration++;
				}
				unset( $headers );
				fclose( $file );
				if ( '' == $error_list ) {
					echo 'success';
				} else {
					echo esc_attr( $error_list );
				}
			} else {
				echo esc_attr__( 'No URL', 'wp-easycart' );
			}
			die();
		}
	}
endif;

function wp_easycart_admin_users() {
	return wp_easycart_admin_users::instance();
}
wp_easycart_admin_users();

add_action( 'wp_ajax_ec_admin_check_email_exists', 'ec_admin_check_email_exists' );
function ec_admin_check_email_exists() {
	$users = new wp_easycart_admin_users();
	$users->check_existing_email();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_import_users', 'ec_admin_ajax_import_users' );
function ec_admin_ajax_import_users() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-start-import' ) ) {
		return false;
	}

	$import_results = wp_easycart_admin_users()->run_importer();
	die();
}
