<?php
class wp_easycart_admin_email_settings {

	public $settings_file;
	public $email_settings_file;
	public $account_email_file;
	public $order_receipt_file;
	public $order_receipt_language_file;

	public function __construct() {

		// Setup File Names 
		$this->settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/email-setup/settings.php';
		$this->email_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/email-setup/email-settings.php';
		$this->account_email_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/email-setup/account-emails.php';
		$this->order_receipt_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/email-setup/order-receipt.php';
		$this->order_receipt_language_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/email-setup/order-receipt-language.php';

		add_action( 'wpeasycart_admin_email_settings', array( $this, 'load_email_settings' ) );
		add_action( 'wpeasycart_admin_email_settings', array( $this, 'load_order_receipt' ) );
		add_action( 'wpeasycart_admin_email_settings', array( $this, 'load_account_email' ) );

		add_action( 'wpeasycart_admin_email_settings', array( $this, 'load_order_receipt_language' ) );
	}

	public function load_email() {
		include( $this->settings_file );
	}

	public function load_order_receipt_language() {
		include( $this->order_receipt_language_file );
	}

	public function load_order_receipt() {
		include( $this->order_receipt_file );
	}

	public function load_account_email() {
		include( $this->account_email_file );
	}

	public function load_email_settings() {
		include( $this->email_settings_file );
	}

	public function wpeasycart_smtp_test1() {
		$to = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
		$subject = __( 'WP EasyCart Order Receipt Email Test', 'wp-easycart' );
		$message = __( 'This is a simple test from WP EasyCart to make sure your email setup is correct. If you receive this your order type emails should be working properly!', 'wp-easycart' );

		if ( get_option( 'ec_option_use_wp_mail' ) == '0' ) {
			$mailer = new wpeasycart_mailer();
			return $mailer->send_order_email( $to, $subject, $message );
		} else {
			$headers = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/html; charset=utf-8';
			$headers[] = 'From: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'Reply-To: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'X-Mailer: PHP/' . phpversion();
			wp_mail( $to, $subject, $message, implode( "\r\n", $headers ) );
			return false;
		}
	}

	public function wpeasycart_smtp_test2() {

		$to = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
		$subject = __( 'WP EasyCart Account Test Email', 'wp-easycart' );
		$message = __( 'This is a simple test from WP EasyCart to make sure your email setup is correct. If you receive this your account type emails should be working properly!', 'wp-easycart' );

		if ( get_option( 'ec_option_use_wp_mail' ) == '0' ) {
			$mailer = new wpeasycart_mailer();
			return $mailer->send_customer_email( $to, $subject, $message );
		} else {
			$headers   = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/html; charset=utf-8';
			$headers[] = 'From: ' . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = 'Reply-To: ' . stripslashes( get_option( 'ec_option_password_from_email' ) );
			$headers[] = 'X-Mailer: PHP/'.phpversion();

			wp_mail( $to, $subject, $message, implode("\r\n", $headers) );
			return false;
		}

	}

	public function save_email_settings() {
		$options = array( 'ec_option_password_use_smtp', 'ec_option_order_use_smtp', 'ec_option_show_email_on_receipt', 'ec_option_show_image_on_receipt', 'ec_option_use_wp_mail', 'ec_option_send_signup_email' );
		$email_fields = array( 'ec_option_password_from_email', 'ec_option_order_from_email' );
		$options_text = array( 'ec_option_password_from_smtp_host', 'ec_option_password_from_smtp_encryption_type', 'ec_option_password_from_smtp_port', 'ec_option_password_from_smtp_username', 'ec_option_password_from_smtp_password', 'ec_option_order_from_smtp_host', 'ec_option_order_from_smtp_encryption_type', 'ec_option_order_from_smtp_port', 'ec_option_order_from_smtp_username', 'ec_option_order_from_smtp_password', 'ec_option_email_logo', 'ec_option_bcc_email_addresses' );

		if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $email_fields ) ) {
			preg_match( '/^(.*)<(.*)>$/', wp_unslash( $_POST['val'] ), $email_arr );
			if ( 3 == count( $email_arr ) ) {
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( $email_arr[1] ) . ' <' . sanitize_email( $email_arr[2] ) . '>' );
			} else {
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
			}
		} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
			$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), (int) $val );
		} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
			update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
		} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_current_order_id' ) {
			global $wpdb;
			$prevent_max = ( (int) $_POST['val'] > 2140000000 ) ? 2140000000 : (int) $_POST['val'];
			$wpdb->query( $wpdb->prepare( 'ALTER TABLE ec_order AUTO_INCREMENT = %d', (int) $prevent_max ) );
		}
	}

	public function ec_send_test_email() {
		$order_id = (int) $_POST['ec_order_id'];
		$mysqli = new ec_db_admin();

		// send email
		$order_row = $mysqli->get_order_row_admin( $order_id );
		if ( $order_row ) {
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();
			return true;
		} else {
			return false;
		}
	}

	public function save_language_text() {
		$key = sanitize_key( $_POST['update_var'] ) ;
		$value = wp_easycart_escape_html( wp_unslash( $_POST['val'] ) );
		$language_file = sanitize_key( wp_unslash( $_POST['file_name'] ) );
		$language_section = sanitize_key( wp_unslash( $_POST['key_section'] ) );
		wp_easycart_language()->update_language_item( $language_file, $language_section, $key, $value );
	}

}

add_action( 'wp_ajax_ec_admin_ajax_send_test_email', 'ec_admin_ajax_send_test_email' );
function ec_admin_ajax_send_test_email() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-email-settings' ) ) {
		return false;
	}

	$email_settings = new wp_easycart_admin_email_settings();
	$email_settings->ec_send_test_email();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_email_settings', 'ec_admin_ajax_save_email_settings' );
function ec_admin_ajax_save_email_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-email-settings' ) ) {
		return false;
	}

	$email_settings = new wp_easycart_admin_email_settings();
	$email_settings->save_email_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_email_language_settings', 'ec_admin_ajax_save_email_language_settings' );
function ec_admin_ajax_save_email_language_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-email-settings' ) ) {
		return false;
	}

	$email_settings = new wp_easycart_admin_email_settings();
	$email_settings->save_language_text();
	die();
}
