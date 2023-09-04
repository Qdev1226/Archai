<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'wp_easycart_admin_payments' ) ) :

	final class wp_easycart_admin_payments {

		protected static $_instance = null;

		public $payments_file;
		public $payment_free_header_file;
		public $payment_free_foooter_file;
		public $manual_bill_file;
		public $amazonpay_file;
		public $paypal_file;
		public $stripe_file;
		public $square_file;
		public $upgrade_file;
		public $payments_dir;

		public $third_party_gateways;
		public $live_gateways;

		public $cart_page;
		public $permalink_divider;

		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			// Setup File Names 
			$this->payments_file 				= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/payment.php';
			$this->payment_free_header_file 	= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/payment-free-header.php';
			$this->payment_free_foooter_file 	= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/payment-free-footer.php';
			$this->manual_bill_file 			= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/manual-bill.php';
			$this->amazonpay_file 				= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/amazonpay.php';
			$this->paypal_file 					= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/paypal.php';
			$this->stripe_file 					= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/stripe_connect.php';
			$this->square_file 					= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/square.php';
			$this->upgrade_file 				= EC_PLUGIN_DIRECTORY . '/admin/template/upgrade/upgrade-simple.php';
			$this->payments_dir					= EC_PLUGIN_DIRECTORY . '/admin/template/settings/payments/';

			// Link Information
			$cart_page_id = get_option('ec_option_cartpage');
			if ( function_exists( 'icl_object_id' ) ) {
				$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
			}
			$this->cart_page = get_permalink( $cart_page_id );
			if ( class_exists( 'WordPressHTTPS' ) && isset( $_SERVER['HTTPS'] ) ) {
				$https_class = new WordPressHTTPS();
				$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			}
			if ( substr_count( $this->cart_page, '?' ) )					$this->permalink_divider = '&';
			else														$this->permalink_divider = '?';

			// Setup Default Payment Options
			$this->third_party_gateways = array(
				'2checkout_thirdparty' => '2Checkout',
				'cashfree' => 'CashFree',
				'dwolla_thirdparty' => 'Dwolla',
				'nets' => 'Nets Nexaxept',
				'payfast_thirdparty' => 'PayFast',
				'payfort' => 'Payfort',
				'paymentexpress_thirdparty' => 'Payment Express PxPay 2.0',
				'realex_thirdparty' => 'Realex',
				'redsys' => 'Redsys',
				'sagepay_paynow_za' => 'SagePay Pay Now South Africa',
				'skrill' => 'Skrill',
				'custom_thirdparty' => __( 'Custom Gateway', 'wp-easycart' ),
			);
			$this->live_gateways = array(
				'authorize' => 'Authorize.net',
				'beanstream' => 'Bambora',
				'braintree' => 'Braintree S2S',
				'cardpointe' => 'Cardpointe',
				'chronopay' => 'Chronopay',
				'virtualmerchant' => 'Converge (Virtual Merchant)',
				'eway' => 'Eway',
				'firstdata' => 'First Data Payeezy (e4)',
				'goemerchant' => 'GoeMerchant',
				'intuit' => 'Intuit Payments',
				'migs' => 'MIGS', 
				'moneris_ca' => 'Moneris Canada',
				'moneris_us' => 'Moneris USA',
				'nmi' => 'Network Merchants (NMI)',
				'sagepay' => 'Opayo by Elavon (Formerly Sagepay)',
				'sagepayus' => 'Paya (Previously Sagepay US)',
				'payline' => 'Payline',
				'paymentexpress' => 'Payment Express PxPost',
				'paypal_pro' => 'PayPal PayFlow Pro',
				'paypal_payments_pro' => 'PayPal Payments Pro',
				'paypoint' => 'PayPoint', 
				'realex' => 'Realex',
				'securepay' => 'SecurePay',
				'stripe' => 'Stripe (v1)',
				'securenet' => 'WorldPay',
				'custom' => __( 'Custom Payment Gateway', 'wp-easycart' ),
			);

			add_filter( 'wp_easycart_admin_success_messages', array( $this, 'add_success_messages' ) );
			add_filter( 'wp_easycart_admin_error_messages', array( $this, 'add_failure_messages' ) );

			// Actions
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_header' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_bill_later' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_paypal' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_square' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_stripe' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_amazonpay' ) );
			add_action( 'wp_easycart_admin_payment_options_top', array( $this, 'load_free_footer' ) );

			add_action( 'wpeasycart_admin_load_third_party_select_options', array( $this, 'load_third_party_combo' ) );
			add_action( 'wpeasycart_admin_load_third_party_settings', array( $this, 'load_third_party_settings' ) );
			add_action( 'wpeasycart_admin_load_live_gateway_select_options', array( $this, 'load_live_gateway_combo' ) );
			add_action( 'wpeasycart_admin_load_live_gateway_settings', array( $this, 'load_live_gateway_settings' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'disconnect_paypal' ) );
			add_action( 'wp_easycart_process_get_form_action', array( $this, 'onboard_stripe' ) );

			add_action( 'wp_easycart_process_get_form_action', array( $this, 'process_square_app' ) );
		}

		public function add_success_messages( $messages ) {
			if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-sandbox-connected' ) {
				$messages[] = __( 'Connected to Stripe Sandbox Successfully!', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-live-connected' ) {
				$messages[] = __( 'Connected to Stripe Successfully! You can now process live transactions.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-sandbox-mode' ) {
				$messages[] = __( 'Stripe is now in Sandbox Mode, test orders only!', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-live-mode' ) {
				$messages[] = __( 'Stripe is now in Live Mode, you can process live transactions', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-sandbox-disconnected' ) {
				$messages[] = __( 'Sandbox keys have been removed from your site. You will still have to revoke access from your Stripe account.', 'wp-easycart' );
			} else if ( isset( $_GET['success'] ) && $_GET['success'] == 'stripe-live-disconnected' ) {
				$messages[] = __( 'Live keys have been removed from your site. You will still have to revoke access from your Stripe account.', 'wp-easycart' );
			}
			return $messages;
		}

		public function add_failure_messages( $messages ) {
			if ( isset( $_GET['error'] ) && $_GET['error'] == 'stripe-onboarding-error' ) {
				$messages[] = __( 'An error occured during the authorization of your Stripe account. Please try again or contact WP EasyCart for assistence.', 'wp-easycart' );
			}
			return $messages;
		}

		public function load_payments() {
			include( $this->payments_file );
		}

		public function load_free_header() {
			include( $this->payment_free_header_file );
		}

		public function load_free_bill_later() {
			include( $this->manual_bill_file );
		}

		public function load_free_amazonpay() {
			include( $this->amazonpay_file );
		}

		public function load_free_paypal() {
			include( $this->paypal_file );
		}

		public function load_free_stripe() {
			include( $this->stripe_file );
		}

		public function load_free_square() {
			include( $this->square_file );
		}

		public function load_free_footer() {
			include( $this->payment_free_foooter_file );
		}

		public function load_third_party_combo() {
			$third_party_gateways = apply_filters( 'wp_easycart_admin_third_party_gateways', $this->third_party_gateways );
			foreach ( $third_party_gateways as $gateway => $gateway_name ) { 
				echo '<option value="' . esc_attr( $gateway ) . '" ';
				if ( get_option( 'ec_option_payment_third_party' ) == $gateway ) { 
					echo ' selected'; 
				}
				echo '>' . esc_attr( $gateway_name ) . '</option>';
			}
		}

		public function load_third_party_settings() {
			$third_party_gateways = apply_filters( 'wp_easycart_admin_third_party_gateways', $this->third_party_gateways );
			foreach ( $this->third_party_gateways as $gateway => $gateway_name ) {
				$this->load_third_party_payment_form( $gateway );
			}
		}

		public function load_free_paypal_credit_field() {
			echo '<div style="font-weight:bold; margin:15px 0 0;">' . esc_attr__( 'Add PayPal Express', 'wp-easycart' ) . '</div>';
			echo '<div>' . esc_attr__( 'Enable PayPal Express', 'wp-easycart' ) . ' <span class="dashicons dashicons-lock" style="color:#FC0; float:left; margin-top:5px;"></span><select onchange="show_pro_required(); return false;">';
			echo '<option value="0"';
			if ( get_option( 'ec_option_paypal_enable_pay_now' ) == '0' ) 
				echo ' selected';
			echo '>' . esc_attr__( 'Keep PayPal Standard, Redirect Users to PayPal for Payment', 'wp-easycart' ) . '</option>';
			echo '<option value="1"';
			if ( get_option( 'ec_option_paypal_enable_pay_now' ) == '1' )
				echo ' selected';
			echo '>' . esc_attr__( 'YES! Enable PayPal Express and Keep Customers on My Site', 'wp-easycart' ) . '</option>';
			echo '</select></div>';

			echo '<div>' . esc_attr__( 'Advertise PayPal Credit', 'wp-easycart' ) . ' <span class="dashicons dashicons-lock" style="color:#FC0; float:left; margin-top:5px;"></span><select onchange="show_pro_required(); return false;">';
			echo '<option value="0"';
			if ( get_option( 'ec_option_paypal_enable_credit' ) == '0' ) 
				echo ' selected';
			echo '>' . esc_attr__( 'Do Not Advertise PayPal Credit', 'wp-easycart' ) . '</option>';
			echo '<option value="1"';
			if ( get_option( 'ec_option_paypal_enable_credit' ) == '1' )
				echo ' selected';
			echo '>' . esc_attr__( 'Advertise PayPal Credit', 'wp-easycart' ) . '</option>';
			echo '</select></div>';
		}

		public function load_live_gateway_combo() {
			$live_gateways = apply_filters( 'wp_easycart_admin_live_gateways', $this->live_gateways );
			foreach ( $this->live_gateways as $gateway => $gateway_name ) { 
				echo '<option value="' . esc_attr( $gateway ) . '" ';
				if ( get_option( 'ec_option_payment_process_method' ) == $gateway ) { 
					echo ' selected'; 
				}
				echo '>' . esc_attr( $gateway_name ) . '</option>';
			}
		}

		public function load_live_gateway_settings() {
			$live_gateways = apply_filters( 'wp_easycart_admin_live_gateways', $this->live_gateways );
			foreach ( $live_gateways as $gateway => $gateway_name ) {
				$this->load_live_payment_form( $gateway );
			}

		}

		public function load_third_party_payment_form( $payment_type ) {
			$file = apply_filters( 'wp_easycart_admin_payment_file', $this->payments_dir . $payment_type . '.php', $payment_type );
			if ( file_exists( $file ) ) {
				include( $file );
			} else {
				echo '<div class="ec_admin_settings_input ec_admin_settings_third_party_section ec_admin_settings_';
				if ( get_option( 'ec_option_payment_third_party' ) == $payment_type ) {
					echo 'show';
				} else {
					echo 'hide';
				}
				echo '" id="' . esc_attr( $payment_type ) . '">';
				$upgrade_icon = "dashicons-lock";
				$upgrade_title = __( 'Enable ', 'wp-easycart' ) . $this->third_party_gateways[$payment_type];
				$upgrade_subtitle = '';
				$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( ' Select Box to Enable ', 'wp-easycart' ) . $this->third_party_gateways[$payment_type];
				$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
				include( $this->upgrade_file );
				echo '</div>';
			}
		}

		public function load_live_payment_form( $payment_type ) {
			$file = apply_filters( 'wp_easycart_admin_payment_file', $this->payments_dir . $payment_type . '.php', $payment_type );
			if ( file_exists( $file ) ) {
				include( $file );
			} else {
				echo '<div class="ec_admin_settings_input ec_admin_settings_live_payment_section ec_admin_settings_';
				if ( get_option( 'ec_option_payment_process_method' ) == $payment_type ) {
					echo 'show';
				} else {
					echo 'hide';
				}
				echo '" id="' . esc_attr( $payment_type ) . '">';
				$upgrade_icon = "dashicons-lock";
				$upgrade_title = __( 'Enable ', 'wp-easycart' ) . $this->live_gateways[$payment_type];
				$upgrade_subtitle = '';
				$upgrade_checkbox_label = apply_filters( 'wp_easycart_admin_lock_icon', ' <span class="dashicons dashicons-lock" style="color:#FC0; margin-top:5px;"></span>' ) . __( ' Select Box to Enable ', 'wp-easycart' ) . $this->live_gateways[$payment_type];
				$upgrade_button_label = __( 'Save Setup', 'wp-easycart' );
				include( $this->upgrade_file );
				echo '</div>';
			}
		}

		public function update_manual_billing_settings() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
				return false;
			}

			update_option( 'ec_option_use_direct_deposit', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_use_direct_deposit'] ) );
			update_option( 'ec_option_direct_deposit_message', sanitize_textarea_field( wp_unslash( $_POST['ec_option_direct_deposit_message'] ) ) );

			if ( isset( $_POST['ec_language_field']['cart_payment_information_manual_payment'] ) ) {
				$_POST['ec_language_field']['cart_payment_information_manual_payment'] = sanitize_text_field( wp_unslash( $_POST['ec_language_field']['cart_payment_information_manual_payment'] ) );
			}

			wp_easycart_language()->update_language_data();
			do_action( 'wpeasycart_manual_billing_updated', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_use_direct_deposit'] ) );
		}

		public function update_third_party_selection() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			update_option( 'ec_option_payment_third_party', sanitize_text_field( wp_unslash( $_POST['ec_option_payment_third_party'] ) ) );
			do_action( 'wpeasycart_third_party_payment_updated', sanitize_text_field( wp_unslash( $_POST['ec_option_payment_third_party'] ) ) );
		}

		public function update_paypal() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( !wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
				return false;
			}

			$paypal_email = ( isset( $_POST['ec_option_paypal_email'] ) ) ? sanitize_email( wp_unslash( $_POST['ec_option_paypal_email'] ) ) : '';
			update_option( 'ec_option_paypal_email', $paypal_email );

			update_option( 'ec_option_paypal_enable_pay_now', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_enable_pay_now'] ) );
			update_option( 'ec_option_paypal_enable_credit', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_enable_credit'] ) );
			update_option( 'ec_option_paypal_sandbox_access_token_expires', 0 );
			update_option( 'ec_option_paypal_production_access_token_expires', 0 );

			update_option( 'ec_option_paypal_currency_code', wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_currency_code'] ) ), 3 ) );
			update_option( 'ec_option_paypal_use_selected_currency', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_use_selected_currency'] ) );
			update_option( 'ec_option_paypal_use_venmo', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_use_venmo'] ) );
			update_option( 'ec_option_paypal_lc', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_lc'] ) ) );
			update_option( 'ec_option_paypal_charset', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_charset'] ) ) );
			update_option( 'ec_option_paypal_weight_unit', wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_weight_unit'] ) ), array( 'lbs', 'kgs' ) ) );
			update_option( 'ec_option_paypal_use_sandbox', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_use_sandbox'] ) );
			update_option( 'ec_option_paypal_collect_shipping', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_collect_shipping'] ) );

			update_option( 'ec_option_paypal_button_color', wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_button_color'] ) ), array( 'gold', 'blue', 'silver', 'black' ) ) );
			update_option( 'ec_option_paypal_button_shape', wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_button_shape'] ) ), array( 'pill', 'rect' ) ) );
			update_option( 'ec_option_paypal_express_page1_checkout', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_express_page1_checkout'] ) );

			if ( isset( $_POST['ec_option_paypal_marketing_solution_cid_sandbox'] ) ) {
				update_option( 'ec_option_paypal_marketing_solution_cid_sandbox', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_marketing_solution_cid_sandbox'] ) );
			}
			if ( isset( $_POST['ec_option_paypal_marketing_solution_cid_production'] ) ) {
				update_option( 'ec_option_paypal_marketing_solution_cid_production', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_paypal_marketing_solution_cid_production'] ) );
			}

			do_action( 'wp_easycart_paypal_standard_updated' );
		}

		public function update_pro_paypal() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			update_option( 'ec_option_paypal_email', sanitize_email( wp_unslash( $_POST['ec_option_paypal_email'] ) ) );

			update_option( 'ec_option_paypal_enable_pay_now', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_enable_pay_now'] ) ) );
			update_option( 'ec_option_paypal_enable_credit', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_enable_credit'] ) ) );
			update_option( 'ec_option_paypal_sandbox_access_token_expires', 0 );
			update_option( 'ec_option_paypal_production_access_token_expires', 0 );

			update_option( 'ec_option_paypal_sandbox_app_id', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_sandbox_app_id'] ) ) );
			update_option( 'ec_option_paypal_sandbox_secret', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_sandbox_secret'] ) ) );

			update_option( 'ec_option_paypal_production_app_id', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_production_app_id'] ) ) );
			update_option( 'ec_option_paypal_production_secret', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_production_secret'] ) ) );

			update_option( 'ec_option_paypal_currency_code', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_currency_code'] ) ) );
			update_option( 'ec_option_paypal_use_selected_currency', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_use_selected_currency'] ) ) );
			update_option( 'ec_option_paypal_lc', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_lc'] ) ) );
			update_option( 'ec_option_paypal_charset', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_charset'] ) ) );
			update_option( 'ec_option_paypal_weight_unit', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_weight_unit'] ) ) );
			update_option( 'ec_option_paypal_use_sandbox', (int) $_POST['ec_option_paypal_use_sandbox'] );
			update_option( 'ec_option_paypal_collect_shipping', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_collect_shipping'] ) ) );

			update_option( 'ec_option_paypal_button_color', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_button_color'] ) ) );
			update_option( 'ec_option_paypal_button_shape', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_button_shape'] ) ) );
			update_option( 'ec_option_paypal_express_page1_checkout', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_express_page1_checkout'] ) ) );

			update_option( 'ec_option_paypal_marketing_solution_cid_sandbox', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_marketing_solution_cid_sandbox'] ) ) );
			update_option( 'ec_option_paypal_marketing_solution_cid_production', sanitize_text_field( wp_unslash( $_POST['ec_option_paypal_marketing_solution_cid_production'] ) ) );

			do_action( 'wp_easycart_paypal_standard_updated' );
		}

		public function disconnect_paypal() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( $_GET['ec_admin_form_action'] == 'paypal-express-sandbox-disconnect' ) {
				update_option( 'ec_option_paypal_sandbox_webhook_id', '' );
				update_option( 'ec_option_paypal_sandbox_merchant_id', '' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', $result );

			} else if ( $_GET['ec_admin_form_action'] == 'paypal-express-production-disconnect' ) {
				update_option( 'ec_option_paypal_production_webhook_id', '' );
				update_option( 'ec_option_paypal_production_merchant_id', '' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', $result );

			} else if ( $_GET['ec_admin_form_action'] == 'paypal-marketing-sandbox-disconnect' ) {
				update_option( 'ec_option_paypal_marketing_solution_cid_sandbox', '' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', $result );

			} else if ( $_GET['ec_admin_form_action'] == 'paypal-marketing-production-disconnect' ) {
				update_option( 'ec_option_paypal_marketing_solution_cid_production', '' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', $result );

			}
		}

		public function save_stripe_connect() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
				return false;
			}

			update_option( 'ec_option_stripe_connect_use_sandbox', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_connect_use_sandbox'] ) );
			update_option( 'ec_option_payment_process_method', wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['ec_option_payment_process_method'] ) ), array( 'stripe_connect' ) ) );
			update_option( 'ec_option_stripe_currency', wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_option_stripe_currency'] ) ), 3 ) );
			update_option( 'ec_option_stripe_company_country', wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_option_stripe_company_country'] ) ), 2 ) );
			update_option( 'ec_option_stripe_payment_theme', sanitize_text_field( wp_unslash( $_POST['ec_option_stripe_payment_theme'] ) ) );
			update_option( 'ec_option_stripe_payment_layout', sanitize_text_field( wp_unslash( $_POST['ec_option_stripe_payment_layout'] ) ) );
			update_option( 'ec_option_stripe_address_autocomplete', sanitize_text_field( (int) $_POST['ec_option_stripe_address_autocomplete'] ) );
			
			update_option( 'ec_option_stripe_affirm', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_affirm'] ) );
			update_option( 'ec_option_stripe_afterpay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_afterpay'] ) );
			update_option( 'ec_option_stripe_klarna', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_klarna'] ) );

			update_option( 'ec_option_stripe_enable_apple_pay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_enable_apple_pay'] ) );
			update_option( 'ec_option_stripe_disable_wallet_first', wp_easycart_admin_verification()->filter_bool_int( ( ( isset( $_POST['ec_option_stripe_disable_wallet_first'] ) ) ? (int) $_POST['ec_option_stripe_disable_wallet_first'] : 0 ) ) );
			update_option( 'ec_option_stripe_alipay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_alipay'] ) );
			update_option( 'ec_option_stripe_grabpay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_grabpay'] ) );
			update_option( 'ec_option_stripe_wechat', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_wechat'] ) );
			update_option( 'ec_option_stripe_link', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_link'] ) );

			update_option( 'ec_option_stripe_bancontact', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_bancontact'] ) );
			update_option( 'ec_option_stripe_blik', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_blik'] ) );
			update_option( 'ec_option_stripe_eps', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_eps'] ) );
			update_option( 'ec_option_stripe_fpx', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_fpx'] ) );
			update_option( 'ec_option_stripe_giropay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_giropay'] ) );
			update_option( 'ec_option_stripe_enable_ideal', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_enable_ideal'] ) );
			update_option( 'ec_option_stripe_p24', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_p24'] ) );
			update_option( 'ec_option_stripe_sofort', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_sofort'] ) );

			update_option( 'ec_option_stripe_bacs', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_bacs'] ) );
			update_option( 'ec_option_stripe_becs', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_becs'] ) );
			update_option( 'ec_option_stripe_sepa', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_sepa'] ) );

			update_option( 'ec_option_stripe_pix', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_pix'] ) );
			update_option( 'ec_option_stripe_paynow', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_paynow'] ) );
			update_option( 'ec_option_stripe_promptpay', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_promptpay'] ) );

			update_option( 'ec_option_stripe_boleto', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_boleto'] ) );
			update_option( 'ec_option_stripe_konbini', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_konbini'] ) );
			update_option( 'ec_option_stripe_oxxo', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_stripe_oxxo'] ) );
		}

		public function save_stripe_connect_option() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
				return false;
			}

			$options = array( 'ec_option_stripe_affirm', 'ec_option_stripe_afterpay', 'ec_option_stripe_klarna', 'ec_option_stripe_enable_apple_pay', 'ec_option_stripe_disable_wallet_first', 'ec_option_stripe_alipay', 'ec_option_stripe_grabpay', 'ec_option_stripe_wechat', 'ec_option_stripe_link', 'ec_option_stripe_bancontact', 'ec_option_stripe_blik', 'ec_option_stripe_eps', 'ec_option_stripe_fpx', 'ec_option_stripe_giropay', 'ec_option_stripe_enable_ideal', 'ec_option_stripe_p24', 'ec_option_stripe_sofort', 'ec_option_stripe_bacs', 'ec_option_stripe_becs', 'ec_option_stripe_sepa', 'ec_option_stripe_pix', 'ec_option_stripe_paynow', 'ec_option_stripe_promptpay', 'ec_option_stripe_boleto', 'ec_option_stripe_konbini', 'ec_option_stripe_oxxo' );
			
			if ( isset( $_POST['update_var'] ) && in_array( $_POST['update_var'], $options ) ) {
				$val = ( isset( $_POST['val'] ) && $_POST['val'] == '1' ) ? 1 : 0;
				update_option( sanitize_text_field( wp_unslash( $_POST['update_var'] ) ), $val );
			}
		}

		public function onboard_stripe() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-stripe' ) ) {
				return false;
			}

			if ( $_GET['ec_admin_form_action'] == 'stripe_onboard' && isset( $_GET['error'] ) ) {
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&error=stripe-onboarding-error' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'error' => 'stripe-onboarding-error' ) );
				}
				die();

			}

			if ( $_GET['ec_admin_form_action'] == 'stripe_onboard' && $_GET['env'] == 'sandbox' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 1 );
				update_option( 'ec_option_stripe_connect_sandbox_access_token', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['access_token'] ) ) ) );
				update_option( 'ec_option_stripe_connect_sandbox_refresh_token', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['refresh_token'] ) ) ) );
				update_option( 'ec_option_stripe_connect_sandbox_publishable_key', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['stripe_publishable_key'] ) ) ) );
				update_option( 'ec_option_stripe_connect_sandbox_user_id', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['stripe_user_id'] ) ) ) );
				update_option( 'ec_option_payment_process_method', 'stripe_connect' );
				update_option( 'ec_option_default_payment_type', 'credit_card' );
				do_action( 'wpeasycart_live_gateway_updated', get_option( 'ec_option_payment_process_method' ) );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-sandbox-connected' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-sandbox-connected' ) );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'stripe_onboard' && $_GET['env'] == 'production' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 0 );
				update_option( 'ec_option_stripe_connect_production_access_token', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['access_token'] ) ) ) );
				update_option( 'ec_option_stripe_connect_production_refresh_token', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['refresh_token'] ) ) ) );
				update_option( 'ec_option_stripe_connect_production_publishable_key', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['stripe_publishable_key'] ) ) ) );
				update_option( 'ec_option_stripe_connect_production_user_id', wp_easycart_admin_verification()->min_filter( sanitize_text_field( wp_unslash( $_GET['stripe_user_id'] ) ) ) );
				update_option( 'ec_option_payment_process_method', 'stripe_connect' );
				update_option( 'ec_option_default_payment_type', 'credit_card' );
				do_action( 'wpeasycart_live_gateway_updated', get_option( 'ec_option_payment_process_method' ) );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-live-connected' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-live-connected' ) );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'stripe-connect-use-sandbox' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 1 );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-sandbox-mode' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-sandbox-mode' ) );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'stripe-connect-use-production' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 0 );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-live-mode' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-live-mode' ) );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'stripe-connect-sandbox-disconnect' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 0 );
				update_option( 'ec_option_stripe_connect_sandbox_access_token', '' );
				update_option( 'ec_option_stripe_connect_sandbox_refresh_token', '' );
				update_option( 'ec_option_stripe_connect_sandbox_publishable_key', '' );
				update_option( 'ec_option_stripe_connect_sandbox_user_id', '' );
				update_option( 'ec_option_payment_process_method', '0' );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-sandbox-disconnected' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-sandbox-disconnected' ) );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'stripe-connect-production-disconnect' ) {
				update_option( 'ec_option_stripe_connect_use_sandbox', 0 );
				update_option( 'ec_option_stripe_connect_production_access_token', '' );
				update_option( 'ec_option_stripe_connect_production_refresh_token', '' );
				update_option( 'ec_option_stripe_connect_production_publishable_key', '' );
				update_option( 'ec_option_stripe_connect_production_user_id', '' );
				update_option( 'ec_option_payment_process_method', '0' );
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=stripe-live-disconnected' );
				} else {
					wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array( 'success' => 'stripe-live-disconnected' ) );
				}
				die();

			}
		}

		public function process_square_app() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( $_GET['ec_admin_form_action'] == 'handle-square' && isset( $_GET['wpeasycart_square_failed'] ) ) {
				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&error=square-failed-to-connect' );
				} else {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment&error=square-failed-to-connect' );
				}
				die();

			// Handle a Successful Connect Attempt
			} else if ( $_GET['ec_admin_form_action'] == 'handle-square' && isset( $_GET['wpeasycart_square_state'] ) ) {
				if ( ! isset( $_GET['wpeasycart_square_state'] ) ) {
					return false;
				}
				if ( false === wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['wpeasycart_square_state'] ) ), 'wp-easycart-square' ) ) {
					return false;
				}
				$access_token = ( isset( $_GET['access_token'] ) ) ? preg_replace( '/[^A-Za-z0-9 \-\._\~\+\/]/', '', sanitize_text_field( wp_unslash( $_GET['access_token'] ) ) ) : '';
				$refresh_token = ( isset( $_GET['refresh_token'] ) ) ? preg_replace( '/[^A-Za-z0-9 \-\._\~\+\/]/', '', sanitize_text_field( wp_unslash( $_GET['refresh_token'] ) ) ) : '';
				$expires = ( isset( $_GET['expires'] ) ) ? preg_replace( '/[^A-Za-z0-9 \-\:]/', '', sanitize_text_field( wp_unslash( $_GET['expires'] ) ) ) : '';

				update_option( 'ec_option_payment_process_method', 'square' );
				if ( isset( $_GET['sandbox'] ) ) {
					update_option( 'ec_option_square_is_sandbox', 1 );
					update_option( 'ec_option_square_sandbox_application_id', '' );			
					update_option( 'ec_option_square_sandbox_access_token', $access_token );
					update_option( 'ec_option_square_sandbox_refresh_token', $refresh_token );
					update_option( 'ec_option_square_sandbox_token_expires', $expires );

				} else {
					update_option( 'ec_option_square_is_sandbox', 0 );
					update_option( 'ec_option_square_application_id', '' );			
					update_option( 'ec_option_square_access_token', $access_token );
					update_option( 'ec_option_square_refresh_token', $refresh_token );
					update_option( 'ec_option_square_token_expires', $expires );
				}
				do_action( 'wpeasycart_live_gateway_updated', get_option( 'ec_option_payment_process_method' ) );

				$square = new ec_square();
				$square->set_currency();

				if ( !wp_next_scheduled( 'wp_easycart_square_renew_token' ) ) {
					wp_schedule_event( time(), 'daily', 'wp_easycart_square_renew_token' );
				}

				if ( isset( $_GET['goto'] ) && $_GET['goto'] == 'wizard' ) {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=setup-wizard&step=3&success=square-connected' );
				} else {
					wp_redirect( 'admin.php?page=wp-easycart-settings&subpage=payment&success=square-connected' );
				}
				die();

			} else if ( $_GET['ec_admin_form_action'] == 'square-disconnect' ) {
				if ( isset( $_GET['sandbox'] ) ) {
					$access_token = get_option( 'ec_option_square_sandbox_access_token' );
					$request = new WP_Http;
					$response = $request->request( 
						'https://connect.wpeasycart.com/square-sandbox/disconnect.php?access_token=' . $access_token, 
						array( 
							'method' => 'GET',
							'timeout' => 5
						)
					);
					update_option( 'ec_option_payment_process_method', '0' );
					update_option( 'ec_option_square_application_id', '' );
					update_option( 'ec_option_square_access_token', '' );
					update_option( 'ec_option_square_refresh_token', '' );
					update_option( 'ec_option_square_token_expires', '' );

				} else {
					$access_token = get_option( 'ec_option_square_access_token' );
					$request = new WP_Http;
					$response = $request->request( 
						'https://connect.wpeasycart.com/square/disconnect.php?access_token=' . $access_token, 
						array( 
							'method' => 'GET',
							'timeout' => 5
						)
					);
					update_option( 'ec_option_payment_process_method', '0' );
					update_option( 'ec_option_square_application_id', '' );
					update_option( 'ec_option_square_access_token', '' );
					update_option( 'ec_option_square_refresh_token', '' );
					update_option( 'ec_option_square_token_expires', '' );
				}
				wp_clear_scheduled_hook( 'wp_easycart_square_renew_token' );
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array() );

			} else if ( $_GET['ec_admin_form_action'] == 'square-renew' ) {
				$square = new ec_square();
				$square->renew_token();
				wp_clear_scheduled_hook( 'wp_easycart_square_renew_token' );
				if ( !wp_next_scheduled( 'wp_easycart_square_renew_token' ) ) {
					wp_schedule_event( time(), 'daily', 'wp_easycart_square_renew_token' );
				}
				wp_easycart_admin()->redirect( 'wp-easycart-settings', 'payment', array() );
			}
		}

		public function update_square() {
			if ( ! current_user_can( 'manage_options' ) && ! current_user_can( 'wpec_settings' ) ) {
				return;
			}

			if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
				return false;
			}

			update_option( 'ec_option_payment_process_method', wp_easycart_admin_verification()->filter_list( sanitize_text_field( wp_unslash( $_POST['payment_method'] ) ), array( 'square' ) ) );
			if ( get_option( 'ec_option_square_is_sandbox' ) ) {
				update_option( 'ec_option_square_sandbox_location_id', sanitize_text_field( wp_unslash( $_POST['ec_option_square_location_id'] ) ) );

			} else {
				update_option( 'ec_option_square_location_id', sanitize_text_field( wp_unslash( $_POST['ec_option_square_location_id'] ) ) );

			}
			update_option( 'ec_option_square_location_country', wp_easycart_admin_verification()->filter_chars( sanitize_text_field( wp_unslash( $_POST['ec_option_square_location_country'] ) ), 2 ) );
			update_option( 'ec_option_square_digital_wallet', wp_easycart_admin_verification()->filter_bool_int( (int) $_POST['ec_option_square_digital_wallet'] ) );
			update_option( 'ec_option_square_merchant_name', sanitize_text_field( wp_unslash( $_POST['ec_option_square_merchant_name'] ) ) );

			$square = new ec_square();
			$square->set_currency();

			if ( !get_option( 'ec_option_square_is_sandbox' ) && isset( $_POST['ec_option_square_digital_wallet'] ) && (int) $_POST['ec_option_square_digital_wallet'] ) {
				$square->register_domain();
			}
		}

	}
endif; // End if class_exists check

function wp_easycart_admin_payments() {
	return wp_easycart_admin_payments::instance();
}
wp_easycart_admin_payments();

add_action( 'wp_ajax_ec_admin_ajax_save_third_party_selection', 'ec_admin_ajax_save_third_party_selection' );
function ec_admin_ajax_save_third_party_selection() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->update_third_party_selection();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_direct_deposit', 'ec_admin_ajax_save_direct_deposit' );
function ec_admin_ajax_save_direct_deposit() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->update_manual_billing_settings();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_paypal', 'ec_admin_ajax_save_paypal' );
function ec_admin_ajax_save_paypal() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->update_third_party_selection();
	wp_easycart_admin_payments()->update_paypal();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_pro_paypal', 'ec_admin_ajax_save_pro_paypal' );
function ec_admin_ajax_save_pro_paypal() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->update_third_party_selection();
	wp_easycart_admin_payments()->update_pro_paypal();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_stripe_connect', 'ec_admin_ajax_save_stripe_connect' );
function ec_admin_ajax_save_stripe_connect() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->save_stripe_connect();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_stripe_connect_option', 'ec_admin_ajax_save_stripe_connect_option' );
function ec_admin_ajax_save_stripe_connect_option() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->save_stripe_connect_option();
	die();
}

add_action( 'wp_ajax_ec_admin_ajax_save_square_free', 'ec_admin_ajax_save_square_free' );
function ec_admin_ajax_save_square_free() {
	if ( ! wp_easycart_admin_verification()->verify_access( 'wp-easycart-settings-payment' ) ) {
		return false;
	}

	wp_easycart_admin_payments()->update_square();
	die();
}