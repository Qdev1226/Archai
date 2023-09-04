<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_checkout' ) ) :

	final class wp_easycart_admin_checkout {

		protected static $_instance = null;

		public $checkout_file;
		public $cart_settings_file;
		public $checkout_order_statuses_file;
		public $checkout_form_settings_file;
		public $checkout_settings_file;
		public $checkout_stock_control_file;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			$this->checkout_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/checkout.php';
			$this->cart_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/cart-settings.php';
			$this->checkout_order_statuses_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/order-statuses.php';
			$this->checkout_form_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/checkout-form-settings.php';
			$this->checkout_settings_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/checkout-settings.php';
			$this->checkout_stock_control_file = EC_PLUGIN_DIRECTORY . '/admin/template/settings/checkout/checkout-stock-control.php';

			add_action( 'wpeasycart_admin_checkout_success', array( $this, 'load_success_messages' ) );
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'load_cart_settings' ) );
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'load_order_status_settings' ) );
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'load_checkout_form_settings' ) );
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'load_stock_control_settings' ) );
			add_action( 'wpeasycart_admin_checkout_settings', array( $this, 'load_checkout_settings' ) );

			add_action( 'wpeasycart_admin_checkout_form_fields_end', array( $this, 'add_additional_email_option' ) );
		}

		public function load_checkout() {
			include( $this->checkout_file );
		}

		public function load_success_messages() {
			include( $this->success_messages_file );
		}

		public function load_order_status_settings() {
			include( $this->checkout_order_statuses_file );
		}

		public function load_cart_settings() {
			include( $this->cart_settings_file );
		}

		public function load_checkout_form_settings() {
			include( $this->checkout_form_settings_file );
		}

		public function load_checkout_settings() {
			include( $this->checkout_settings_file );
		}

		public function load_stock_control_settings() {
			include( $this->checkout_stock_control_file );
		}

		public function add_additional_email_option() {
			wp_easycart_admin( )->load_toggle_group( 'ec_option_enable_extra_email', 'show_pro_required(\'\'); jQuery( document.getElementById( \'ec_option_enable_extra_email\' ) ).prop( \'checked\', false ); return false;', get_option( 'ec_option_enable_extra_email' ), __( 'Additional Email', 'wp-easycart' ), __( 'Enable this to allow customers to enter an optional secondary email that will receive order email communications.', 'wp-easycart' ) );
		}

		public function save_cart_settings() {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-checkout' ) ) {
				return false;
			}

			$options = array( 'ec_option_onepage_checkout', 'ec_option_onepage_checkout_tabbed', 'ec_option_onepage_checkout_cart_first', 'ec_option_load_ssl', 'ec_option_cache_prevent', 'ec_option_enable_tips', 'ec_option_use_estimate_shipping', 'ec_option_estimate_shipping_zip', 'ec_option_estimate_shipping_country', 'ec_option_show_giftcards', 'ec_option_gift_card_shipping_allowed', 'ec_option_show_coupons', 'ec_option_display_country_top', 'ec_option_use_address2', 'ec_option_collect_user_phone', 'ec_option_user_phone_required', 'ec_option_enable_company_name', 'ec_option_collect_vat_registration_number', 'ec_option_user_order_notes', 'ec_option_require_terms_agreement', 'ec_option_use_contact_name', 'ec_option_show_card_holder_name', 'ec_option_skip_shipping_page', 'ec_option_allow_guest', 'ec_option_enable_extra_email', 'ec_option_use_state_dropdown', 'ec_option_use_smart_states', 'ec_option_use_country_dropdown', 'ec_option_send_low_stock_emails', 'ec_option_send_out_of_stock_emails', 'ec_option_show_card_holder_name', 'ec_option_addtocart_return_to_product' );

			$options_text = array( 'ec_option_return_to_store_page_url', 'ec_option_enable_metric_unit_display', 'ec_option_minimum_order_total', 'ec_option_terms_link', 'ec_option_privacy_link', 'ec_option_default_country', 'ec_option_low_stock_trigger_total', 'ec_option_default_payment_type' );

			if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
				$val = wp_easycart_admin_verification()->filter_checkbox( 'val' );
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), (int) $val );

			} else if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options_text ) ) {
				if ( $_POST['update_var'] == 'ec_option_return_to_store_page_url' || $_POST['update_var'] == 'ec_option_terms_link' || $_POST['update_var'] == 'ec_option_privacy_link' ) {
					$val = esc_url_raw( wp_unslash( $_POST['val'] ) );
				} else if ( $_POST['update_var'] == 'ec_option_enable_metric_unit_display' ) {
					$val = wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['val'] ) ), array( 0, 1 ) );
				} else if ( $_POST['update_var'] == 'ec_option_minimum_order_total' ) {
					$val = wp_easycart_admin_verification()->filter_float( sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
				} else if ( $_POST['update_var'] == 'ec_option_default_country' ) {
					$val = wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['val'] ) ), 2 );
				} else if ( $_POST['update_var'] == 'ec_option_low_stock_trigger_total' ) {
					$val = wp_easycart_admin_verification()->filter_int( sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
				} else if ( $_POST['update_var'] == 'ec_option_default_payment_type' ) {
					$val = wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['val'] ) ), array( 'manual_bill', 'third_party', 'credit_card' ) );
				}
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );

			} else if ( isset( $_POST['update_var'] ) && $_POST['update_var'] == 'ec_option_default_tips' ) {
				$ec_option_default_tips = preg_replace( '/[^0-9\.\,]/', '', sanitize_text_field( wp_unslash( $_POST['val'] ) ) );
				$tips_arr_final = array();
				$tips_arr_exploded = explode( ',', $ec_option_default_tips );
				foreach ( $tips_arr_exploded as $tip_rate ) {
					if ( (float) $tip_rate > 0 ) {
						$tips_arr_final[] = $tip_rate;
					}
				}
				$ec_option_default_tips = implode( ',', $tips_arr_final );
				update_option( 'ec_option_default_tips', $ec_option_default_tips );

			} else if ( isset( $_POST['update_var'] ) && 'ec_option_current_order_id' == sanitize_text_field( wp_unslash( $_POST['update_var'] ) ) ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( 'ALTER TABLE ec_order AUTO_INCREMENT = %d', wp_easycart_admin_verification()->filter_int( sanitize_text_field( $_POST['val'] ) ) ) );
			}
		}

		public function add_order_status( $order_status, $is_approved ) {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-checkout' ) ) {
				return false;
			}

			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_orderstatus( order_status, is_approved ) VALUES( %s, %d )',  wp_easycart_admin_verification()->min_filter( $order_status ), wp_easycart_admin_verification()->filter_bool_int( $is_approved ) ) );
			$status_id = $wpdb->insert_id;
			do_action( 'wpeasycart_order_status_added', $status_id );
			return $status_id;
		}

		public function update_order_status( $status_id, $order_status ) {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-checkout' ) ) {
				return false;
			}

			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_orderstatus SET order_status = %s WHERE status_id = %d', wp_easycart_admin_verification()->min_filter( $order_status ), wp_easycart_admin_verification()->filter_int( $status_id ) ) );
			do_action( 'wpeasycart_order_status_updated', $status_id );
		}

		public function update_order_status_approved( $status_id, $is_approved ) {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-checkout' ) ) {
				return false;
			}

			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_orderstatus SET is_approved = %d WHERE status_id = %d', wp_easycart_admin_verification()->filter_bool_int( $is_approved ), wp_easycart_admin_verification()->filter_int( $status_id ) ) );
			do_action( 'wpeasycart_order_status_updated', $status_id );
		}

		public function archieve_order_status( $status_id ) {
			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-checkout' ) ) {
				return false;
			}

			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_orderstatus SET is_archieved = 1 WHERE status_id = %d', wp_easycart_admin_verification()->filter_int( $status_id ) ) );
			do_action( 'wpeasycart_order_status_deleted', $status_id );
		}

	}
endif;

function wp_easycart_admin_checkout() {
	return wp_easycart_admin_checkout::instance();
}
wp_easycart_admin_checkout();

add_action( 'wp_ajax_ec_admin_ajax_save_cart_settings', 'ec_admin_ajax_save_cart_settings' );
function ec_admin_ajax_save_cart_settings() {
	wp_easycart_admin_checkout()->save_cart_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_add_orderstatus', 'ec_admin_ajax_add_orderstatus' );
function ec_admin_ajax_add_orderstatus() {
	$insert_id = wp_easycart_admin_checkout()->add_order_status( sanitize_text_field( wp_unslash( $_POST['order_status'] ) ), (int) $_POST['is_approved'] );
	echo esc_attr( $insert_id );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_orderstatus', 'ec_admin_ajax_save_orderstatus' );
function ec_admin_ajax_save_orderstatus() {
	wp_easycart_admin_checkout()->update_order_status( (int) $_POST['status_id'], sanitize_text_field( wp_unslash( $_POST['order_status'] ) ) );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_orderstatus_approved', 'ec_admin_ajax_save_orderstatus_approved' );
function ec_admin_ajax_save_orderstatus_approved() {
	wp_easycart_admin_checkout()->update_order_status_approved( (int) $_POST['status_id'], (int) $_POST['is_approved'] );
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_archieve_orderstatus', 'ec_admin_ajax_archieve_orderstatus' );
function ec_admin_ajax_archieve_orderstatus() {
	wp_easycart_admin_checkout()->archieve_order_status( (int) $_POST['status_id'] );
	die();
}
