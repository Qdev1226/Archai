<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_miscellaneous' ) ) :

	final class wp_easycart_admin_miscellaneous {

		protected static $_instance = null;

		public $miscellaneous_file;
		public $settings_file;
		public $search_file;
		public $admin_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			// Setup File Names 
			$this->miscellaneous_file	 = EC_PLUGIN_DIRECTORY . '/admin/template/settings/miscellaneous/miscellaneous.php';
			$this->settings_file		 = EC_PLUGIN_DIRECTORY . '/admin/template/settings/miscellaneous/settings.php';
			$this->search_file		 	 = EC_PLUGIN_DIRECTORY . '/admin/template/settings/miscellaneous/search.php';
			$this->admin_file		 	 = EC_PLUGIN_DIRECTORY . '/admin/template/settings/miscellaneous/admin.php';

			// Actions
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_enable_usage_tracking' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_disable_usage_tracking' ) );

			// Loaders
			add_action( 'wpeasycart_admin_miscellaneous', array( $this, 'load_miscellaneous_settings' ) );
			add_action( 'wpeasycart_admin_miscellaneous', array( $this, 'load_search_settings' ) );
			add_action( 'wpeasycart_admin_miscellaneous', array( $this, 'load_admin_settings' ) );
		}

		public function process_enable_usage_tracking() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( $_GET['ec_admin_form_action'] == "allow-usage-tracking" ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-enable-usage-tracking' ) ) {
					update_option( 'ec_option_allow_tracking', '1' );
					if ( !function_exists( 'wp_easycart_admin_tracking' ) ) {
						include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_tracking.php' );
					}
					do_action( 'wpeasycart_admin_usage_tracking_accepted' );
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'initial-setup', array( 'success' => 'tracking-enabled' ) );
				}
			}
		}

		public function process_disable_usage_tracking() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( $_GET['ec_admin_form_action'] == "deny-usage-tracking" ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-disable-usage-tracking' ) ) {
					update_option( 'ec_option_allow_tracking', '-1' );
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'miscellaneous', array( 'success' => 'tracking-disabled' ) );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'tracking-enabled' ) {
				$messages[] = __( 'Thank you for enabling usage data, we really appreciate it!', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'tracking-disabled' ) {
				$messages[] = __( 'Usage data has been disabled. If you change your mind you can always enable it here in the additional settings.', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_miscellaneous() {
			include( $this->miscellaneous_file );
		}

		public function load_miscellaneous_settings() {
			include( $this->settings_file );
		}

		public function load_search_settings() {
			include( $this->search_file );
		}

		public function load_admin_settings() {
			include( $this->admin_file );
		}

		public function save_miscellaneous_options() {
			$options = array( 'ec_option_admin_product_show_stock_option', 'ec_option_admin_product_show_shipping_option', 'ec_option_admin_product_show_tax_option', 'ec_option_admin_product_show_variant_option', 'ec_option_enable_push_notifications', 'ec_option_use_live_search', 'ec_option_search_title', 'ec_option_search_model_number', 'ec_option_search_manufacturer', 'ec_option_search_description', 'ec_option_search_short_description', 'ec_option_search_menu', 'ec_option_search_by_or', 'ec_option_show_menu_cart_icon', 'ec_option_hide_cart_icon_on_empty', 'ec_option_enable_newsletter_popup', 'ec_option_enable_gateway_log', 'ec_option_use_inquiry_form', 'ec_option_allow_tracking', 'ec_option_deconetwork_allow_blank_products', 'ec_option_enable_debugging_mode', 'ec_option_disable_easycart_ad' );
			$options_text = array( 'ec_option_abandoned_cart_days' );

			if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );

			} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), (int) $_POST['val'] );

			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_use_old_linking_style' ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 0 : 1;
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );

			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_cart_menu_id' ) {
				if ( isset( $_POST['val'] ) ) {
					update_option( 'ec_option_show_menu_cart_icon', 1 );
					$clean_vals = array();
					if ( is_array( $_POST['val'] ) ) {
						foreach ( (array) $_POST['val'] as $val ) { // XSS OK. Forced array and each item sanitized.
							$clean_vals[] = sanitize_text_field( wp_unslash( $val ) );
						}
					}
					update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), implode( '***', $clean_vals ) );
				} else {
					update_option( 'ec_option_show_menu_cart_icon', 0 );
					update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), '' );
				}
			}
		}

		public function clear_stats() {
			global $wpdb;
			$results = $wpdb->query( $wpdb->prepare( 'UPDATE ec_menulevel1, ec_menulevel2, ec_menulevel3 SET ec_menulevel1.clicks = 0, ec_menulevel2.clicks = 0, ec_menulevel3.clicks = 0' ) );
			$results = $wpdb->query( $wpdb->prepare( 'UPDATE ec_product SET ec_product.views = 0' ) );
		}

		public function delete_gateway_log() {
			global $wpdb;
			$wpdb->query( 'DELETE FROM ec_webhook' );
			$wpdb->query( 'DELETE FROM ec_response' );
		}

		public function download_gateway_log() {
			global $wpdb;
			$results = $wpdb->get_results( 'SELECT * FROM ec_response ORDER BY ec_response.response_id ASC', ARRAY_A );

			header( 'Content-type: application/vnd.ms-excel' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Content-Disposition: attachment; filename=gatewaylog.xls' );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );

			if ( count( $results ) > 0 ) {
				$keys = array_keys( $results[0] );
				foreach ( $keys as $key ) {
					echo esc_attr( $key ) . "\t";
				}
				foreach ( $results as $result ) {
					foreach ( $result as $value ) {
						if ( !isset( $value ) || $value == "" ) {
							echo "\t";
						} else {
							echo '"' . esc_attr( utf8_decode( str_replace( "\r", "", str_replace( '"', '""', $value ) ) ) ) . '"' . "\t";
						}
					}
					echo "\n";
				}
			} else {
				echo "\n" . esc_attr__( 'no matching records found', 'wp-easycart' ) . "\n";
			}
			die();
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_miscellaneous() {
	return wp_easycart_admin_miscellaneous::instance();
}
wp_easycart_admin_miscellaneous();

add_action( 'wp_ajax_ec_admin_ajax_clear_stats', 'ec_admin_ajax_clear_stats' );
function ec_admin_ajax_clear_stats() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-miscellaneous-settings' ) ) {
		return false;
	}

	wp_easycart_admin_miscellaneous()->clear_stats();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_additional_settings', 'ec_admin_ajax_save_additional_settings' );
function ec_admin_ajax_save_additional_settings() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-miscellaneous-settings' ) ) {
		return false;
	}

	wp_easycart_admin_miscellaneous()->save_miscellaneous_options();
	die();
}
