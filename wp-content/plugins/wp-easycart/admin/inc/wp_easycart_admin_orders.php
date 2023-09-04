<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_orders' ) ) :

	final class wp_easycart_admin_orders {

		protected static $_instance = null;

		public $order_details;

		public $orders_list_file;
		public $export_orders_csv;
		public $export_orders_stamps_csv;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() { 
			$this->orders_list_file = EC_PLUGIN_DIRECTORY . '/admin/template/orders/orders/order-list.php';
			$this->export_orders_csv = apply_filters( 'wpeasycart_admin_order_export_file', EC_PLUGIN_DIRECTORY . '/admin/template/exporters/export-orders-csv.php' );
			$this->export_orders_stamps_csv = apply_filters( 'wpeasycart_admin_order_stamps_export_file', EC_PLUGIN_DIRECTORY . '/admin/template/exporters/export-orders-stamps-csv.php' );
			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_delete_order' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_delete_order' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_mark_viewed_order' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_bulk_mark_not_viewed_order' ) );	
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_mark_all_viewed_order' ) );	
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_mark_all_not_viewed_order' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_orders' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_export_orders_stamps' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_resend_email_receipt' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_resend_email_invoice' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_print_receipts' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_print_packing_slips' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_send_order_shipped_email' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_update_order_status' ) );
			add_action( 'wp_easycart_admin_order_details_left_content_end', array( $this, 'print_order_history' ) );
		}
		
		public function print_order_history( ){
			include( EC_PLUGIN_DIRECTORY . '/admin/template/orders/orders/order-history.php' );
		}

		public function process_delete_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-order' == $_GET['ec_admin_form_action'] && isset( $_GET['order_id'] ) && ! isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-action-delete-order' ) ) {
					$result = $this->delete_order();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_bulk_delete_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'delete-order' == $_GET['ec_admin_form_action'] && ! isset( $_GET['order_id'] ) && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->bulk_delete_order();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_bulk_mark_viewed_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'mark-orders-viewed' == $_GET['ec_admin_form_action'] && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->bulk_mark_order_viewed();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_bulk_mark_not_viewed_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'mark-orders-not-viewed' == $_GET['ec_admin_form_action'] && isset( $_GET['bulk'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->bulk_mark_order_not_viewed();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_mark_all_viewed_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'mark-all-orders-viewed' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->mark_all_order_viewed();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_mark_all_not_viewed_order() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'mark-all-orders-not-viewed' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->mark_all_order_not_viewed();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_export_orders() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && ( 'export-orders-csv' == $_GET['ec_admin_form_action'] || 'export-orders-csv-all' == $_GET['ec_admin_form_action'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					include( $this->export_orders_csv );
					die();
				}
			}
		}

		public function process_export_orders_stamps() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && ( 'export-orders-stamps-csv' == $_GET['ec_admin_form_action'] || 'export-orders-stamps-csv-all' == $_GET['ec_admin_form_action'] ) ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					include( $this->export_orders_stamps_csv );
					die();
				}
			}
		}

		public function process_resend_email_receipt() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'resend-email' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->resend_receipts();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_resend_email_invoice() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'resend-invoice' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->resend_invoices();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_print_receipts() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'print-receipt' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$this->print_receipts();
					die();
				}
			}
		}

		public function process_print_packing_slips() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'print-packing-slip' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$this->print_packing_slips();
					die();
				}
			}
		}

		public function process_send_order_shipped_email() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'send-shipped-email' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->send_order_shipped_emails();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function process_update_order_status() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_orders' ) ) {
				return;
			}

			if ( isset( $_GET['ec_admin_form_action'] ) && 'change-order-status' == $_GET['ec_admin_form_action'] ) {
				if ( wp_easycart_admin_verification()->verify_access( 'wp-easycart-bulk-' . ( ( isset( $_GET['subpage'] ) ) ? 'orders' : '' ) ) ) {
					$result = $this->bulk_update_order_status();
					wp_easycart_admin()->redirect( 'wp-easycart-orders', 'orders', $result );
				}
			}
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && 'order-updated' == $_GET['success'] ) {
				$messages[] = __( 'Order(s) successfully updated', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'order-deleted' == $_GET['success'] ) {
				$messages[] = __( 'Order(s) successfully deleted', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'order-viewed' == $_GET['success'] ) {
				$messages[] = __( 'Order(s) marked as viewed', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'order-not-viewed' == $_GET['success'] ) {
				$messages[] = __( 'Order(s) marked as NOT viewed', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'receipt-sent' == $_GET['success'] ) {
				$messages[] = __( 'Order(s) receipt was resent to the customer(s)', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && 'shipping-email-sent' == $_GET['success'] ) {
				$messages[] = __( 'Shipping email(s) were sent to the customer(s)', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && 'order-updated-error' == $_GET['error'] ) {
				$messages[] = __( 'Order failed to update', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'order-deleted-error' == $_GET['error'] ) {
				$messages[] = __( 'Order failed to delete', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'order-viewed-error' == $_GET['error'] ) {
				$messages[] = __( 'Order failed to mark as viewed', 'wp-easycart' );
			} else if ( isset( $_GET['error'] ) && 'order-duplicate' == $_GET['error'] ) {
				$messages[] = __( 'Order failed to create due to duplicate', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_orders_list() {
			if ( isset( $_GET['ec_admin_form_action'] ) && ( ( isset( $_GET['order_id'] ) && 'edit' == $_GET['ec_admin_form_action'] ) || 'add-new' == $_GET['ec_admin_form_action'] ) ) {
				include( EC_PLUGIN_DIRECTORY . '/admin/inc/wp_easycart_admin_details_orders.php' );
				$this->order_details = new wp_easycart_admin_details_orders();
				$this->order_details->output( sanitize_key( $_GET['ec_admin_form_action'] ) );
			} else {
				include( $this->orders_list_file );
			}
		}

		public function update_notes() {
			global $wpdb;

			$order_id = (int) $_POST['order_id'];
			$order_customer_notes = sanitize_textarea_field( wp_unslash( $_POST['order_customer_notes'] ) );
			
			do_action( 'wpeasycart_admin_order_customer_notes_update', $order_id, $order_customer_notes );

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET order_customer_notes = %s, last_updated = NOW() WHERE order_id = %d', $order_customer_notes, $order_id ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-customer-notes-update" )', $order_id ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "order_customer_notes", %s )', $order_log_id, $order_id, $order_customer_notes ) );

			do_action( 'wpeasycart_order_updated', $order_id );
		}

		public function update_orderstatus() {
			global $wpdb;

			$order_id = (int) $_POST['order_id'];
			$orderstatus_id = (int) $_POST['orderstatus_id'];

			/* Check for Applicable Stock Adjustments */
			$order = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_order.*, ec_orderstatus.is_approved FROM ec_order LEFT JOIN ec_orderstatus ON ec_orderstatus.status_id = ec_order.orderstatus_id WHERE ec_order.order_id = %d', $order_id ) );
			$orderstatus = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_orderstatus.* FROM ec_orderstatus WHERE ec_orderstatus.status_id = %d', $orderstatus_id ) );
			$orderdetails = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ec_orderdetail WHERE ec_orderdetail.order_id = %d', $order_id ) );

			if ( ! $order->is_approved && $orderstatus->is_approved ) { // Take out of stock
				$ec_db = new ec_db();
				foreach ( $orderdetails as $orderdetail ) {
					if ( ! $orderdetail->stock_adjusted ) {
						$product = $wpdb->get_row( $wpdb->prepare( 'SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d', $orderdetail->product_id ) );
						if ( $product ) {
							if ( $product->use_optionitem_quantity_tracking ) {
								$ec_db->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
							}
							$ec_db->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
							$ec_db->update_details_stock_adjusted( $orderdetail->orderdetail_id );
						}
					}
				}
			}
			/* END Stock Adjustment Check */
			$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET orderstatus_id = %s, last_updated = NOW() WHERE order_id = %d', $orderstatus_id, $order_id ) );
			do_action( 'wpeasycart_order_status_update', $order_id, $orderstatus_id );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-status-update" )', $order_id ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, $orderstatus_id ) );

			if ( '3' == $orderstatus_id || '6' == $orderstatus_id || '10' == $orderstatus_id || '15' == $orderstatus_id ) {
				do_action( 'wpeasycart_order_paid', $order_id );
			} else if ( '2' == $orderstatus_id ) {
				do_action( 'wpeasycart_order_shipped', $order_id );
			} else if ( '16' == $orderstatus_id ) {
				do_action( 'wpeasycart_full_order_refund', $order_id );
			} else if ( '17' == $orderstatus_id ) {
				do_action( 'wpeasycart_partial_order_refund', $order_id );
			}
			do_action( 'wpeasycart_order_updated', $order_id );
		}

		public function update_order_info() {
			global $wpdb;

			$order_id = (int) $_POST['order_id'];
			$order_weight = sanitize_text_field( wp_unslash( $_POST['order_weight'] ) );
			$giftcard_id = sanitize_text_field( wp_unslash( $_POST['giftcard_id'] ) );
			$promo_code = sanitize_text_field( wp_unslash( $_POST['promo_code'] ) );
			$order_notes = sanitize_textarea_field( wp_unslash( $_POST['order_notes'] ) );

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET order_weight = %s, giftcard_id = %s, promo_code = %s, order_notes = %s, last_updated = NOW() WHERE order_id = %d', $order_weight, $giftcard_id, $promo_code, $order_notes, $order_id ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-info-update" )', $order_id ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "order_weight", %s )', $order_log_id, $order_id, $order_weight ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "giftcard_id", %s )', $order_log_id, $order_id, $giftcard_id ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "promo_code", %s )', $order_log_id, $order_id, $promo_code ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "order_notes", %s )', $order_log_id, $order_id, $order_notes ) );

			do_action( 'wpeasycart_order_updated', $order_id );
		}

		public function update_shipping_method_info() {
			global $wpdb;

			$order_id = (int) $_POST['order_id'];
			$use_expedited_shipping = ( isset( $_POST['use_expedited_shipping'] ) && $_POST['use_expedited_shipping'] == '1' ) ? 1 : 0;
			$shipping_method = sanitize_text_field( wp_unslash( $_POST['shipping_method'] ) );
			$shipping_carrier = sanitize_text_field( wp_unslash( $_POST['shipping_carrier'] ) );
			$tracking_number = sanitize_text_field( wp_unslash( $_POST['tracking_number'] ) );

			do_action( 'wpeasycart_tracking_info_update', $order_id, $use_expedited_shipping, $shipping_method, $shipping_carrier, $tracking_number );

			$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET use_expedited_shipping = %d, shipping_method = %s, shipping_carrier = %s, tracking_number = %s, last_updated = NOW() WHERE order_id = %d', $use_expedited_shipping, $shipping_method, $shipping_carrier, $tracking_number, $order_id ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-shipping-method-update" )', $order_id ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "use_expedited_shipping", %s )', $order_log_id, $order_id, $use_expedited_shipping ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_method", %s )', $order_log_id, $order_id, $shipping_method ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_carrier", %s )', $order_log_id, $order_id, $shipping_carrier ) );
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "tracking_number", %s )', $order_log_id, $order_id, $tracking_number ) );

			do_action( 'wpeasycart_order_updated', $order_id );
		}

		public function send_customer_shipping_email( $order_id, $trackingnumber, $shipcarrier ) {
			global $wpdb;

			$order = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_order.*, billing_country.name_cnt AS billing_country_name, shipping_country.name_cnt AS shipping_country_name FROM ec_order LEFT JOIN ec_country AS billing_country ON billing_country.iso2_cnt = ec_order.billing_country LEFT JOIN ec_country AS shipping_country ON shipping_country.iso2_cnt = ec_order.shipping_country WHERE order_id = %d', $order_id ) );
			$orderdetails = $wpdb->get_results( $wpdb->prepare( 'SELECT ec_orderdetail.* FROM ec_orderdetail WHERE order_id = %d ORDER BY product_id', $order_id ) );
			$email_logo_url = get_option( 'ec_option_email_logo' );
			$orderfromemail = stripslashes( get_option( 'ec_option_order_from_email' ) );

			$storepageid = get_option('ec_option_storepage');
			if ( function_exists( 'icl_object_id' ) ) {
				$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			}
			$store_page = get_permalink( $storepageid );
			if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$store_page = $https_class->makeUrlHttps( $store_page );
			}

			if ( substr_count( $store_page, '?' ) ) {
				$permalink_divider = "&";
			} else {
				$permalink_divider = "?";
			}

			ob_start();
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_shipping_email.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_shipping_email.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_shipping_email.php';
			}
			$message = ob_get_clean();

			$headers = array( );
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/html; charset=utf-8';
			$headers[] = 'From: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'Reply-To: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'X-Mailer: PHP/' . phpversion();

			$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );

			if ( get_option( 'ec_option_use_wp_mail' ) ) {
				wp_mail( $order[0]->user_email, wp_easycart_language()->get_text( 'ec_shipping_email', 'shipping_email_title' ) . ' ' . $order_id, $message, $headers );
				if ( '' != $order[0]->email_other ) {
					wp_mail( $order[0]->email_other, wp_easycart_language()->get_text( 'ec_shipping_email', 'shipping_email_title' ) . ' ' . $order_id, $message, $headers );
				}
				wp_mail( $admin_email, wp_easycart_language()->get_text( 'ec_shipping_email', 'shipping_email_title' ) . ' ' . $order_id, $message, $headers );
			} else {
				$to = $order[0]->user_email;
				$subject = wp_easycart_language()->get_text( 'ec_shipping_email', 'shipping_email_title' ) . ' ' . $order_id;
				$mailer = new wpeasycart_mailer();
				$mailer->send_order_email( $to, $subject, $message );
				if ( '' != $order[0]->email_other ) {
					$mailer->send_order_email( $order[0]->email_other, $subject, $message );
				}
				$mailer->send_order_email( $admin_email, $subject, $message );
			}
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-shipping-email" )', $order_id ) );
			$order_log_id = $wpdb->insert_id;
			$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "email", %s )', $order_log_id, $order_id, $order[0]->user_email ) );
			if ( '' != $order[0]->email_other ) {
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "email_other", %s )', $order_log_id, $order_id, $order[0]->email_other ) );
			}
		}

		public function delete_order() {
			global $wpdb;

			$order_id = (int) $_GET['order_id'];
			do_action( 'wpeasycart_order_deleting', $order_id );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_order WHERE order_id = %d', $order_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_orderdetail WHERE order_id = %d', $order_id ) );
			$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_download WHERE order_id = %d', $order_id ) );
			do_action( 'wpeasycart_order_deleted', $order_id );

			return array( 'success' => 'order-deleted' );
		}

		public function bulk_delete_order() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				do_action( 'wpeasycart_order_deleting', (int) $bulk_id );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_order WHERE order_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_orderdetail WHERE order_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_download WHERE order_id = %d', (int) $bulk_id ) );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-deleted" )', (int) $bulk_id ) );
				do_action( 'wpeasycart_order_deleted', (int) $bulk_id );
			}

			return array( 'success' => 'order-deleted' );
		}

		public function bulk_update_order_status() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.
			$orderstatus_id = (int) $_GET['bulk_order_status'];

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET orderstatus_id = %d WHERE order_id = %d', $orderstatus_id, (int) $bulk_id ) );
				do_action( 'wpeasycart_order_status_update', (int) $bulk_id, $orderstatus_id );
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-status-update" )', (int) $bulk_id ) );
				$order_log_id = $wpdb->insert_id;
				$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, (int) $bulk_id, $orderstatus_id ) );
			}

			return array( 'success' => 'order-status-updated' );
		}

		public function bulk_mark_order_viewed() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET order_viewed = 1, last_updated = NOW() WHERE order_id = %d', (int) $bulk_id ) );
			}

			return array( 'success' => 'order-viewed' );
		}

		public function bulk_mark_order_not_viewed() {
			global $wpdb;
			$bulk_ids = (array) $_GET['bulk']; // XSS OK. Forced array and each item sanitized.

			foreach ( $bulk_ids as $bulk_id ) {
				$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET order_viewed = 0, last_updated = NOW() WHERE order_id = %d', (int) $bulk_id ) );
			}

			return array( 'success' => 'order-not-viewed' );
		}

		public function mark_all_order_viewed() {
			global $wpdb;
			$wpdb->query( 'UPDATE ec_order SET order_viewed = 1, last_updated = NOW()' );
			return array( 'success' => 'order-viewed' );
		}

		public function mark_all_order_not_viewed() {
			global $wpdb;
			$wpdb->query( 'UPDATE ec_order SET order_viewed = 0, last_updated = NOW()' );
			return array( 'success' => 'order-not-viewed' );
		}

		public function resendgiftcardemail() {
			global $wpdb;

			$order_id = (int) $_POST['order_id'];
			$orderdetail_id  = (int) $_POST['orderdetail_id'];
			$cart_item = $wpdb->get_row( $wpdb->prepare( 'SELECT giftcard_id, gift_card_message, gift_card_from_name, gift_card_to_name, gift_card_email, title, unit_price, is_deconetwork, deconetwork_image_link, image1, 0 AS image1_optionitem FROM ec_orderdetail WHERE orderdetail_id = %d', $orderdetail_id ) );

			$this->send_gift_card_email( $cart_item, $cart_item->giftcard_id );		
		 }

		private function send_gift_card_email( $cart_item, $giftcard_id ) {

			$email_logo_url = get_option( 'ec_option_email_logo' );

			$headers   = array();
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-Type: text/html; charset=utf-8';
			$headers[] = 'From: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'Reply-To: ' . stripslashes( get_option( 'ec_option_order_from_email' ) );
			$headers[] = 'X-Mailer: PHP/' . phpversion();

			ob_start();
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_email_giftcard.php' ) )	
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_email_giftcard.php';
			else
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_email_giftcard.php';

			$message = ob_get_clean();

			$email_send_method = get_option( 'ec_option_use_wp_mail' );
			$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

			if ( $email_send_method == '1' ) {
				wp_mail( $cart_item->gift_card_email, wp_easycart_language()->get_text( 'cart_success', 'cart_giftcard_receipt_title' ), $message, implode( "\r\n", $headers ) );
				wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( 'cart_success', 'cart_giftcard_receipt_title' ), $message, implode( "\r\n", $headers ) );

			} else if ( $email_send_method == '0' ) {
				$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
				$to = $cart_item->gift_card_email;
				$subject = wp_easycart_language()->get_text( 'cart_success', 'cart_giftcard_receipt_title' );
				$mailer = new wpeasycart_mailer();
				$mailer->send_order_email( $to, $subject, $message );
				$mailer->send_order_email( $admin_email, $subject, $message );

			} else {
				do_action( 'wpeasycart_custom_gift_card_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $cart_item->gift_card_email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( 'cart_success', 'cart_giftcard_receipt_title' ), $message );

			}

		}

		 public function resend_receipts() {
			 if ( isset( $_GET['bulk'] ) && is_array( $_GET['bulk'] ) ) {
				$bulk_count = count( $_GET['bulk'] );
				for ( $i = 0; $i < $bulk_count; $i++ ) {
					$this->resend_receipt( (int) $_GET['bulk'][ $i ] );
				}
				return array( 'success' => 'receipt-sent' );
			} else if ( isset( $_GET['bulk'] ) ) {
				$this->resend_receipt( (int) $_GET['bulk'] );
				return array( 'success' => 'receipt-sent' );
			} else {
				$this->resend_receipt( (int) $_GET['order_id'] );
				return array( 'success' => 'receipt-sent', 'order_id' => (int) $_GET['order_id'], 'ec_admin_form_action' => 'edit' );
			}
		 }

		 public function resend_invoices() {
			 if ( isset( $_GET['bulk'] ) && is_array( $_GET['bulk'] ) ) {
				$bulk_count = count( $_GET['bulk'] );
				for ( $i = 0; $i < $bulk_count; $i++ ) {
					if ( $i > 0 )
						echo '<div class="ec_admin_page_break"></div>';
					$this->resend_invoice( (int) $_GET['bulk'][ $i ] );
				}
				return array( 'success' => 'invoice-sent' );
			} else if ( isset( $_GET['bulk'] ) ) {
				$this->resend_invoice( (int) $_GET['bulk'] );
				return array( 'success' => 'invoice-sent' );
			} else {
				$this->resend_invoice( (int) $_GET['order_id'] );
				return array( 'success' => 'invoice-sent', 'order_id' => (int) $_GET['order_id'], 'ec_admin_form_action' => 'edit' );
			}
		 }

		 public function print_receipts() {
			 if ( isset( $_GET['bulk'] ) && is_array( $_GET['bulk'] ) ) {
				$bulk_count = count( $_GET['bulk'] );
				for ( $i = 0; $i < $bulk_count; $i++ ) {
					if ( $i > 0 )
						echo '<div class="ec_admin_page_break"></div>';
					$this->print_receipt( (int) $_GET['bulk'][ $i ] );
				}
			} else if ( isset( $_GET['bulk'] ) ) {
				$this->print_receipt( (int) $_GET['bulk'] );
			} else {
				$this->print_receipt( (int) $_GET['order_id'] );
			}
		 }

		 public function resend_receipt( $order_id ) {
			$mysqli = new ec_db_admin();
			$order_row = $mysqli->get_order_row_admin( $order_id );
			if ( $order_row ) {
				$order_display = new ec_orderdisplay( $order_row, true, true );
				$order_display->send_email_receipt();
				return true;
			} else {
				return false;
			}
		 }

		 public function resend_invoice( $order_id ) {
			$mysqli = new ec_db_admin();
			$order_row = $mysqli->get_order_row_admin( $order_id );
			if ( $order_row ) {
				$order_display = new ec_orderdisplay( $order_row, true, true );
				$order_display->send_invoice();
				return true;
			} else {
				return false;
			}
		 }

		 public function send_order_shipped_emails() {
			global $wpdb;
			if ( isset( $_GET['bulk'] ) && is_array( $_GET['bulk'] ) ) {
				$bulk_count = count( $_GET['bulk'] );
				for ( $i = 0; $i < $bulk_count; $i++ ) {
					$order = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, tracking_number, shipping_carrier FROM ec_order WHERE order_id = %d', (int) $_GET['bulk'][ $i ] ) );
					$this->send_customer_shipping_email( $order->order_id, $order->tracking_number, $order->shipping_carrier );
				}
				return array( 'success' => 'shipping-email-sent' );
			} else if ( isset( $_GET['bulk'] ) ) {
				$order = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, tracking_number, shipping_carrier FROM ec_order WHERE order_id = %d', (int) $_GET['bulk'] ) );
				$this->send_customer_shipping_email( $order->order_id, $order->tracking_number, $order->shipping_carrier );
				return array( 'success' => 'shipping-email-sent' );
			} else {
				$order = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, tracking_number, shipping_carrier FROM ec_order WHERE order_id = %d', (int) $_GET['order_id'] ) );
				$this->send_customer_shipping_email( $order->order_id, $order->tracking_number, $order->shipping_carrier );
				return array( 'success' => 'shipping-email-sent', 'order_id' => (int) $_GET['order_id'], 'ec_admin_form_action' => 'edit' );
			}
		 }

		 public function print_receipt( $order_id ) {
			$mysqli = new ec_db_admin();
			$order = $mysqli->get_order_row_admin( $order_id );
			$bill_country = $mysqli->get_country_name( $order->billing_country );
			$ship_country = $mysqli->get_country_name( $order->shipping_country );
			if ( $bill_country ) {
				$order->billing_country = $bill_country;
			}
			if ( $ship_country ) {
				$order->shipping_country = $ship_country;
			}
			$order_details = $mysqli->get_order_details_admin( $order_id );
			$country_list = $mysqli->get_countries();
			$tax_struct = new ec_tax( 0, 0, 0, '', '' );
			$total = $GLOBALS['currency']->get_currency_display( $order->grand_total );
			$subtotal = $GLOBALS['currency']->get_currency_display( $order->sub_total );
			$tip = $GLOBALS['currency']->get_currency_display( $order->tip_total );
			$tax = $GLOBALS['currency']->get_currency_display( $order->tax_total );
			if ( $order->duty_total > 0 ) {
				$has_duty = true;
			} else {
				$has_duty = false;
			}
			$duty = $GLOBALS['currency']->get_currency_display( $order->duty_total );
			$vat = $GLOBALS['currency']->get_currency_display( $order->vat_total );
			$shipping = $GLOBALS['currency']->get_currency_display( $order->shipping_total );
			$discount = $GLOBALS['currency']->get_currency_display( $order->discount_total );
			$refund = $GLOBALS['currency']->get_currency_display( $order->refund_total );

			if ( $order->vat_rate > 0 ) {
				$vat_rate = number_format( $order->vat_rate, 0, '', '' );
			} else if ( ( $order->grand_total - $order->vat_total ) > 0 ) {
				$vat_rate = number_format( ( $order->vat_total / ( $order->grand_total - $order->vat_total ) ) * 100, 0, '', '' );
			} else {
				$vat_rate = number_format( 0, 0, '', '' );
			}
			$gst = $order->gst_total;
			$pst = $order->pst_total;
			$hst = $order->hst_total;

			$gst_rate = $order->gst_rate;
			$pst_rate = $order->pst_rate;
			$hst_rate = $order->hst_rate;

			if ( floor( $gst_rate ) == $gst_rate ) {
				$gst_rate = number_format( $gst_rate, 0, '', '' );
			}
			if ( floor( $pst_rate ) == $pst_rate ) {
				$pst_rate = number_format( $pst_rate, 0, '', '' );
			}
			if ( floor( $hst_rate ) == $hst_rate ) {
				$hst_rate = number_format( $hst_rate, 0, '', '' );
			}
			$order_fees = $mysqli->get_order_fees( $order->order_id );

			$storepageid = get_option('ec_option_storepage');
			if ( function_exists( 'icl_object_id' ) ) {
				$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			}
			$store_page = get_permalink( $storepageid );
			if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$store_page = $https_class->makeUrlHttps( $store_page );
			}

			if ( substr_count( $store_page, '?' ) ) {
				$permalink_divider = "&";
			} else {
				$permalink_divider = "?";
			}
			$email_logo_url = get_option( 'ec_option_email_logo' );

			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_receipt.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_account_print_receipt.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_account_print_receipt.php';
			}
		 }

		 public function print_packing_slips() {
			if ( isset( $_GET['bulk'] ) && is_array( $_GET['bulk'] ) ) {
				$bulk_count = count( $_GET['bulk'] );
				for ( $i = 0; $i < $bulk_count; $i++ ) {
					if ( $i > 0 ) {
						echo '<div class="ec_admin_page_break"></div>';
					}
					$this->print_packing_slip( (int) $_GET['bulk'][ $i ] );
				}
			} else if ( isset( $_GET['bulk'] ) ) {
				$this->print_packing_slip( (int) $_GET['bulk'] );
			} else {
				$this->print_packing_slip( (int) $_GET['order_id'] );
			}
		 }

		 public function print_packing_slip( $order_id ) {
			$db = new ec_db_admin();
			$mysqli = new ec_db_admin();
			$order = $db->get_order_row_admin( $order_id );
			$order_details = $db->get_order_details_admin( $order_id );

			$country_list = $db->get_countries();

			$total = $GLOBALS['currency']->get_currency_display( $order->grand_total );
			$subtotal = $GLOBALS['currency']->get_currency_display( $order->sub_total );
			$tax = $GLOBALS['currency']->get_currency_display( $order->tax_total );
			$tip = $GLOBALS['currency']->get_currency_display( $order->tip_total );
			if ( $order->duty_total > 0 ) { $has_duty = true; } else { $has_duty = false; }
			$duty = $GLOBALS['currency']->get_currency_display( $order->duty_total );
			$vat = $GLOBALS['currency']->get_currency_display( $order->vat_total );
			$vat_rate = number_format( $order->vat_rate, 0, '', '' );
			$shipping = $GLOBALS['currency']->get_currency_display( $order->shipping_total );
			$discount = $GLOBALS['currency']->get_currency_display( $order->discount_total );
			$gst_total = $GLOBALS['currency']->get_currency_display( $order->gst_total );
			$pst_total = $GLOBALS['currency']->get_currency_display( $order->pst_total );
			$hst_total = $GLOBALS['currency']->get_currency_display( $order->hst_total );
			$gst_rate = $order->gst_rate;
			$pst_rate = $order->pst_rate;
			$hst_rate = $order->hst_rate;

			$email_logo_url = get_option( 'ec_option_email_logo' );

			 $storepageid = get_option('ec_option_storepage');
			if ( function_exists( 'icl_object_id' ) ) {
				$storepageid = icl_object_id( $storepageid, 'page', true, ICL_LANGUAGE_CODE );
			}
			$store_page = get_permalink( $storepageid );
			if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$store_page = $https_class->makeUrlHttps( $store_page );
			}

			if ( substr_count( $store_page, '?' ) ) {
				$permalink_divider = "&";
			} else {
				$permalink_divider = "?";
			}

			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_admin_packaging_slip.php' ) ) {
				include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_admin_packaging_slip.php';
			} else {
				include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_admin_packaging_slip.php';
			}
		}
	}
endif; // End if class_exists check

function wp_easycart_admin_orders() {
	return wp_easycart_admin_orders::instance();
}
wp_easycart_admin_orders();
add_action( 'wp_ajax_ec_admin_ajax_edit_order_info', 'ec_admin_ajax_edit_order_info' );
function ec_admin_ajax_edit_order_info() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	wp_easycart_admin_orders()->update_order_info();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_edit_shipping_method_info', 'ec_admin_ajax_edit_shipping_method_info' );
function ec_admin_ajax_edit_shipping_method_info() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	wp_easycart_admin_orders()->update_shipping_method_info();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_edit_orderstatus', 'ec_admin_ajax_edit_orderstatus' );
function ec_admin_ajax_edit_orderstatus() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	wp_easycart_admin_orders()->update_orderstatus();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_edit_customer_notes', 'ec_admin_ajax_edit_customer_notes' );
function ec_admin_ajax_edit_customer_notes() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	wp_easycart_admin_orders()->update_notes();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_resend_giftcard_email', 'ec_admin_ajax_resend_giftcard_email' );
function ec_admin_ajax_resend_giftcard_email() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	wp_easycart_admin_orders()->resendgiftcardemail();
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_order_details_send_order_shipped_email', 'ec_admin_ajax_order_details_send_order_shipped_email' );
function ec_admin_ajax_order_details_send_order_shipped_email() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	global $wpdb;
	$order_id = (int) $_POST['order_id'];
	$order = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, tracking_number, shipping_carrier FROM ec_order WHERE order_id = %d', $order_id ) );
	wp_easycart_admin_orders()->send_customer_shipping_email( $order->order_id, $order->tracking_number, $order->shipping_carrier );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_order_quick_edit', 'ec_admin_ajax_get_order_quick_edit' );
function ec_admin_ajax_get_order_quick_edit() {
	global $wpdb;
	$order_id = (int) $_POST['order_id'];
	$order = $wpdb->get_row( $wpdb->prepare( 'SELECT order_id, orderstatus_id, use_expedited_shipping, shipping_method, shipping_carrier, tracking_number, shipping_first_name, shipping_last_name, shipping_company_name, shipping_address_line_1, shipping_address_line_2, shipping_city, shipping_state, shipping_country, shipping_zip, shipping_phone FROM ec_order WHERE order_id = %d', $order_id ) );
	$items = $wpdb->get_results( $wpdb->prepare( 'SELECT title, model_number, quantity FROM ec_orderdetail WHERE order_id = %d ORDER BY orderdetail_id ASC', $order_id ) );
	$order->items = $items;
	echo json_encode( (object) array( 'order' => $order ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_update_order_quick_edit', 'ec_admin_ajax_update_order_quick_edit' );
function ec_admin_ajax_update_order_quick_edit() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-quick-edit' ) ) {
		return false;
	}

	global $wpdb;
	$order_id = (int) $_POST['order_id'];
	$orderstatus_id = (int) $_POST['orderstatus_id'];
	$use_expedited_shipping = (int) $_POST['use_expedited_shipping'];
	$shipping_method = sanitize_text_field( wp_unslash( $_POST['shipping_method'] ) );
	$shipping_carrier = sanitize_text_field( wp_unslash( $_POST['shipping_carrier'] ) );
	$tracking_number = sanitize_text_field( wp_unslash( $_POST['tracking_number'] ) );
	$send_tracking_email = (int) $_POST['send_tracking_email'];

	$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET orderstatus_id = %d, use_expedited_shipping = %d, shipping_method = %s, shipping_carrier = %s, tracking_number = %s WHERE order_id = %d', $orderstatus_id, $use_expedited_shipping, $shipping_method, $shipping_carrier, $tracking_number, $order_id ) );

	do_action( 'wpeasycart_order_status_update', $order_id, $orderstatus_id );
	do_action( 'wpeasycart_tracking_info_update', $order_id, $use_expedited_shipping, $shipping_method, $shipping_carrier, $tracking_number );
	
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-quick-edit" )', $order_id ) );
	$order_log_id = $wpdb->insert_id;
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $order_id, $orderstatus_id ) );
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "use_expedited_shipping", %s )', $order_log_id, $order_id, $use_expedited_shipping ) );
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_method", %s )', $order_log_id, $order_id, $shipping_method ) );
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "shipping_carrier", %s )', $order_log_id, $order_id, $shipping_carrier ) );
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "tracking_number", %s )', $order_log_id, $order_id, $tracking_number ) );

	if ( $send_tracking_email ) {
		wp_easycart_admin_orders()->send_customer_shipping_email( $order_id, $tracking_number, $shipping_carrier );
	}
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_order_users', 'ec_admin_ajax_get_order_users' );
function ec_admin_ajax_get_order_users() {
	global $wpdb;
	$guest = array(
		(object) array(
			'text' => esc_attr__( 'Guest', 'wp-easycart' ),
			'id' => '0',
		),
	);
	$users = $wpdb->get_results( $wpdb->prepare( 'SELECT CONCAT( ec_user.last_name, ", ", ec_user.first_name, "(", ec_user.user_id, ")" ) AS text, user_id AS id FROM ec_user WHERE last_name LIKE %s OR first_name LIKE %s ORDER BY last_name ASC, first_name ASC LIMIT 100', '%' . sanitize_text_field( wp_unslash( $_POST['q'] ) ) . '%', '%' . sanitize_text_field( wp_unslash( $_POST['q'] ) ) . '%' ) );
	if ( $users ) {
		$results = (object) array(
			'items' => array_merge( $users, $guest ),
		);
	} else {
		$results = (object) array(
			'items' => $guest,
		);
	}
	echo json_encode( $results );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_update_order_user', 'ec_admin_ajax_update_order_user' );
function ec_admin_ajax_update_order_user() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	global $wpdb;
	$order_id = (int) $_POST['order_id'];
	$user_id = (int) $_POST['user_id'];
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET user_id = %d WHERE order_id = %d', $user_id, $order_id ) );
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-user-update" )', $order_id ) );
	$order_log_id = $wpdb->insert_id;
	$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "user_id", %s )', $order_log_id, $order_id, $user_id ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_get_download_keys', 'ec_admin_ajax_get_download_keys' );
function ec_admin_ajax_get_download_keys() {
	global $wpdb;
	$users = $wpdb->get_results( $wpdb->prepare( 'SELECT download_id AS text, download_id AS id FROM ec_download WHERE download_id LIKE %s ORDER BY download_id ASC LIMIT 100', '%' . sanitize_text_field( wp_unslash( $_POST['q'] ) ) . '%', (int) $_POST['page'] - 1 ) );
	if ( $users ) {
		$results = (object) array(
			'items' => $users,
		);
	} else {
		$results = (object) array(
			'items' => array(),
		);
	}
	echo json_encode( $results );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_update_order_download_key', 'ec_admin_ajax_update_order_download_key' );
function ec_admin_ajax_update_order_download_key() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	global $wpdb;
	$orderdetail_id = (int) $_POST['orderdetail_id'];
	$download_key = sanitize_text_field( wp_unslash( $_POST['download_key'] ) );
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_orderdetail SET download_key = %s WHERE orderdetail_id = %d', $download_key, $orderdetail_id ) );
	die();
}
add_action( 'wp_ajax_ec_admin_ajax_enable_download_item', 'ec_admin_ajax_enable_download_item' );
function ec_admin_ajax_enable_download_item() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-order-details' ) ) {
		return false;
	}

	global $wpdb;
	$orderdetail_id = (int) $_POST['orderdetail_id'];
	$wpdb->query( $wpdb->prepare( 'UPDATE ec_order_option SET optionitem_allow_download = 1 WHERE orderdetail_id = %d', $orderdetail_id ) );
	die();
}
