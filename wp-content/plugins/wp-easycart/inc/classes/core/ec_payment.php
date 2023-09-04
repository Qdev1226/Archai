<?php

class ec_payment {
	protected $mysqli;

	private $process_method;
	private $third_party_type;

	public $credit_card;
	private $cart_page;
	private $account_page;
	private $permalink_divider;

	public $payment_type;
	public $third_party;

	public $is_3d_auth = false;

	public $post_url = '';
	public $post_id_input_name = '';
	public $post_id = '';
	public $post_message_input_name = '';
	public $post_message = '';
	public $post_return_url_input_name = '';

	function __construct( $credit_card, $payment_type ) {
		$this->mysqli = new ec_db();
		if ( 'credit_card' == $payment_type ) {
			$this->payment_type = $credit_card->payment_method;
		} else {
			$this->payment_type = $payment_type;
		}
		$this->process_method = get_option( 'ec_option_payment_process_method' );
		$this->third_party_type = get_option( 'ec_option_payment_third_party' );
		$this->credit_card = $credit_card;
		$this->third_party = $this->get_third_party();

		$cart_page_id = get_option('ec_option_cartpage');
		$account_page_id = get_option('ec_option_accountpage');
		if ( function_exists( 'icl_object_id' ) ) {
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
			$account_page_id = icl_object_id( $account_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}
		$this->cart_page = get_permalink( $cart_page_id );
		$this->account_page = get_permalink( $account_page_id );
		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS( );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );
		}

		if ( substr_count( $this->cart_page, '?' ) ) {
			$this->permalink_divider = "&";
		} else {
			$this->permalink_divider = "?";
		}

		$use_proxy = get_option( 'ec_option_use_proxy' );
		$proxy_address = get_option( 'ec_option_proxy_address' );

		if ( $use_proxy ) {
			define( 'WP_PROXY_HOST', $proxy_address );
		}
	}

	public function show_paypal_iframe( $amount ) {
		$this->third_party->display_iframe( $amount );
	}

	public function process_payment( $cart, $user, $shipping, $tax, $discount, $order_totals, $order_id ) {
		if ( ! class_exists( 'ec_' . $this->process_method ) && ! class_exists( 'ec_customgateway' ) ) {
			return "Setup error, upgrade your PRO plugin to fix."; 
		}

		if( 'affirm' == $this->payment_type ) {
			$gateway = new ec_affirm( );
		} else if ( 'authorize' == $this->process_method ) {
			$gateway = new ec_authorize();
		} else if ( 'beanstream' == $this->process_method ) {
			$gateway = new ec_beanstream();
		} else if( 'braintree' == $this->process_method ) {
			$gateway = new ec_braintree();
		} else if( 'cardpointe' == $this->process_method ) {
			$gateway = new ec_cardpointe();
		} else if( 'chronopay' == $this->process_method ) {
			$gateway = new ec_chronopay();
		} else if( 'eway' == $this->process_method ) {
			$gateway = new ec_eway();
		} else if( 'firstdata' == $this->process_method ) {
			$gateway = new ec_firstdata();
		} else if( 'goemerchant' == $this->process_method ) {
			$gateway = new ec_goemerchant();
		} else if( 'intuit' == $this->process_method ) {
			$gateway = new ec_intuit();
		} else if( 'migs' == $this->process_method ) {
			$gateway = new ec_migs();
		} else if( 'moneris_ca' == $this->process_method ) {
			$gateway = new ec_moneris_ca();
		} else if( 'moneris_us' == $this->process_method ) {
			$gateway = new ec_moneris_us();
		} else if( 'nmi' == $this->process_method ) {
			$gateway = new ec_nmi();
		} else if( 'payline' == $this->process_method ) {
			$gateway = new ec_payline();
		} else if( 'paymentexpress' == $this->process_method ) {
			$gateway = new ec_paymentexpress();
		} else if( 'paypal_payments_pro' == $this->process_method ) {
			$gateway = new ec_paypal_payments_pro();
		} else if( 'paypal_pro' == $this->process_method ) {
			$gateway = new ec_paypal_pro();
		} else if( 'paypoint' == $this->process_method ) {
			$gateway = new ec_paypoint();
		} else if( 'psigate' == $this->process_method ) {
			$gateway = new ec_psigate();
		} else if( 'realex' == $this->process_method ) {
			$gateway = new ec_realex();
		} else if( 'sagepay' == $this->process_method ) {
			$gateway = new ec_sagepay();
		} else if( 'sagepay3d' == $this->process_method ) {
			$gateway = new ec_sagepay3d();
		} else if( 'sagepayus' == $this->process_method ) {
			$gateway = new ec_sagepayus();
		} else if( 'securenet' == $this->process_method ) {
			$gateway = new ec_securenet();
		} else if( 'securepay' == $this->process_method ) {
			$gateway = new ec_securepay();
		} else if( 'stripe' == $this->process_method ) {
			$gateway = new ec_stripe();
		} else if( 'stripe_connect' == $this->process_method ) {
			$gateway = new ec_stripe_connect();
		} else if( 'square' == $this->process_method ) {
			$gateway = new ec_square();
		} else if( 'virtualmerchant' == $this->process_method ) {
			$gateway = new ec_virtualmerchant();
		} else if( 'custom' == $this->process_method && class_exists( "ec_customgateway" ) ) {
			$gateway = new ec_customgateway();
		} else {
			return "Setup error, no payment gateway selected."; 
		}

		$gateway->initialize( $cart, $user, $shipping, $tax, $discount, $this->credit_card, $order_totals, $order_id );

		if ( $gateway->process_credit_card() ) {
			if ( $gateway->is_3d_auth ) {
				$this->is_3d_auth = true;
				$this->post_url = $gateway->post_url;
				$this->post_id_input_name = $gateway->post_id_input_name;
				$this->post_id = $gateway->post_id;
				$this->post_message_input_name = $gateway->post_message_input_name;
				$this->post_message = $gateway->post_message;
				$this->post_return_url_input_name = $gateway->post_return_url_input_name;
			}

			if( $gateway->held_for_review ) {
				return '2';
			} else {
				return '1';
			}
		} else {
			return $gateway->get_response_message();
		}
	}

	private function get_third_party() {
		if ( '2checkout_thirdparty' == $this->third_party_type ) {
			return new ec_2checkout_thirdparty( );
		} else if ( 'cashfree' == $this->third_party_type ) {
			return new ec_cashfree( );
		} else if ( 'dwolla_thirdparty' == $this->third_party_type ) {
			return new ec_dwolla_thirdparty( );
		} else if ( 'nets' == $this->third_party_type ) {
			return new ec_nets( );
		} else if ( 'payfast_thirdparty' == $this->third_party_type ) {
			return new ec_payfast_thirdparty( );
		} else if ( 'payfort' == $this->third_party_type ) {
			return new ec_payfort( );
		} else if ( 'paypal' == $this->third_party_type ) {
			return new ec_paypal( );
		} else if ( 'sagepay_paynow_za' == $this->third_party_type ) {
			return new ec_sagepay_paynow_za( );
		} else if ( 'paypal_advanced' == $this->third_party_type ) {
			return new ec_paypal_advanced( );
		} else if ( 'skrill' == $this->third_party_type ) {
			return new ec_skrill( );
		} else if ( 'realex_thirdparty' == $this->third_party_type ) {
			return new ec_realex_thirdparty( );
		} else if ( 'redsys' == $this->third_party_type ) {
			return new ec_redsys( );
		} else if ( 'paymentexpress_thirdparty' == $this->third_party_type ) {
			return new ec_paymentexpress_thirdparty( );
		} else if ( 'custom_thirdparty' == $this->third_party_type && class_exists( "ec_custom_thirdparty" ) ) {
			return new ec_custom_thirdparty( );
		}
	}
}
