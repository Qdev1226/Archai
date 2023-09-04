<?php

class ec_cartpage {
	protected $mysqli;
	public $cart;
	public $user;
	public $tax;
	public $shipping;
	public $discount;
	public $order_totals;
	public $payment;
	public $order;
	public $coupon;
	public $giftcard;
	public $coupon_code;
	public $gift_card;
	public $subscription_option1;
	public $subscription_option2;
	public $subscription_option3;
	public $subscription_option4;
	public $subscription_option5;
	public $subscription_option1_name;
	public $subscription_option2_name;
	public $subscription_option3_name;
	public $subscription_option4_name;
	public $subscription_option5_name;
	public $subscription_option1_label;
	public $subscription_option2_label;
	public $subscription_option3_label;
	public $subscription_option4_label;
	public $subscription_option5_label;
	public $subscription_advanced_options;
	public $has_downloads;
	public $store_page;
	public $cart_page;
	public $account_page;
	public $permalink_divider;
	private $analytics;
	private $is_affirm;
	public $shipping_address_allowed;

	function __construct( $is_affirm = false ) {
		add_action( 'wp_easycart_live_rate_pre_rates', array( $this, 'maybe_add_free_shipping' ) );
		add_action( 'wp_easycart_method_rate_pre_rates', array( $this, 'maybe_add_free_shipping' ) );
		add_filter( 'wp_easycart_trigger_rate', array( $this, 'maybe_set_trigger_rate_free' ), 10, 1 );

		$this->is_affirm = $is_affirm;
		$this->shipping_address_allowed = true;

		$this->mysqli = new ec_db();
		$this->cart = new ec_cart( $GLOBALS['ec_cart_data']->ec_cart_id );
		if ( get_option( 'ec_option_ship_to_billing_global' ) ) {
			$this->shipping_address_allowed = false;
		} else {
			foreach ( $this->cart->cart as $cart_item ) {
				if ( $cart_item->ship_to_billing ) {
					$this->shipping_address_allowed = false;
				}
			}
		}
		$this->user =& $GLOBALS['ec_user'];
		// For the cart, alter the user to use the saved data only.
		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest == "" ) {

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip ) )
				$estimate_shipping_zip = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip;
			else
				$estimate_shipping_zip = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country ) )
				$estimate_shipping_country = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country;
			else
				$estimate_shipping_country = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_zip ) )
				$billing_zip = $GLOBALS['ec_cart_data']->cart_data->billing_zip;
			else
				$billing_zip = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) )
				$shipping_zip = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
			else
				$shipping_zip = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) )
				$billing_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
			else
				$billing_country = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) )
				$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
			else
				$shipping_country = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_state ) )
				$billing_state = $GLOBALS['ec_cart_data']->cart_data->billing_state;
			else
				$billing_state = "";

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) )
				$shipping_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
			else
				$shipping_state = "";

			if ( $billing_zip == "" )
				$billing_zip = $estimate_shipping_zip;
			if ( $shipping_zip == "" )
				$shipping_zip = $estimate_shipping_zip;
			if ( $billing_country == "" )
				$billing_country = $estimate_shipping_country;
			if ( $shipping_country == "" )
				$shipping_country = $estimate_shipping_country;

		} else if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_zip ) )
				$billing_zip = $GLOBALS['ec_cart_data']->cart_data->billing_zip;
			else
				$billing_zip = "";
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) )
				$shipping_zip = $GLOBALS['ec_cart_data']->cart_data->shipping_zip;
			else
				$shipping_zip = "";
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) )
				$billing_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
			else
				$billing_country = "";
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) )
				$shipping_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
			else
				$shipping_country = "";
		}

		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest ) {

			$billing_first_name = $billing_last_name = $billing_company = $billing_address_line_1 = $billing_address_line_2 = $billing_city = $billing_state = $billing_phone = "";
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) )
				$billing_first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) )
				$billing_last_name = $GLOBALS['ec_cart_data']->cart_data->billing_last_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) )
				$billing_company = $GLOBALS['ec_cart_data']->cart_data->billing_company_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) )
				$billing_address_line_1 = $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) )
				$billing_address_line_2 = $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_city ) )
				$billing_city = $GLOBALS['ec_cart_data']->cart_data->billing_city;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_state ) )
				$billing_state = $GLOBALS['ec_cart_data']->cart_data->billing_state;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_phone ) )
				$billing_phone = $GLOBALS['ec_cart_data']->cart_data->billing_phone;
			$this->user->setup_billing_info_data( $billing_first_name, $billing_last_name, $billing_address_line_1, $billing_address_line_2, $billing_city, $billing_state, $billing_country, $billing_zip, $billing_phone, $billing_company );

			$shipping_first_name = $shipping_last_name = $shipping_company = $shipping_address_line_1 = $shipping_address_line_2 = $shipping_city = $shipping_state = $shipping_phone = "";
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ) )
				$shipping_first_name = $GLOBALS['ec_cart_data']->cart_data->shipping_first_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ) )
				$shipping_last_name = $GLOBALS['ec_cart_data']->cart_data->shipping_last_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) )
				$shipping_company = $GLOBALS['ec_cart_data']->cart_data->shipping_company_name;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) )
				$shipping_address_line_1 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) )
				$shipping_address_line_2 = $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_city ) )
				$shipping_city = $GLOBALS['ec_cart_data']->cart_data->shipping_city;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) )
				$shipping_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
			if ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) )
				$shipping_phone = $GLOBALS['ec_cart_data']->cart_data->shipping_phone;
			$this->user->setup_shipping_info_data( $shipping_first_name, $shipping_last_name, $shipping_address_line_1, $shipping_address_line_2, $shipping_city, $shipping_state, $shipping_country, $shipping_zip, $shipping_phone, $shipping_company );

		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->coupon_code ) && $GLOBALS['ec_cart_data']->cart_data->coupon_code != "" ) {
			$this->coupon_code = $GLOBALS['ec_cart_data']->cart_data->coupon_code;
			$coupon_result = $GLOBALS['ec_coupons']->redeem_coupon_code( $this->coupon_code );
			if ( $coupon_result ) {
				$this->coupon = $coupon_result;
			}
		} else {
			$this->coupon_code = "";
		}

		if ( isset( $GLOBALS['ec_cart_data']->cart_data->giftcard ) && $GLOBALS['ec_cart_data']->cart_data->giftcard != "" ) {
			$this->gift_card = $GLOBALS['ec_cart_data']->cart_data->giftcard;
			$this->giftcard = $this->mysqli->redeem_gift_card( $this->gift_card );
			if ( !$this->giftcard )
				$this->gift_card = "";
		} else {
			$this->gift_card = "";
		}

		// Create Promotion and apply free shipping if necessary.
		$promotion = new ec_promotion();
		$promotion->apply_free_shipping( $this->cart );

		// Shipping
		$sales_tax_discount = new ec_discount( $this->cart, $this->cart->discountable_subtotal, 0.00, $this->coupon_code, "", 0 );
		$GLOBALS['wpeasycart_current_coupon_discount'] = $sales_tax_discount->coupon_discount;
		$this->shipping = new ec_shipping( $this->cart->shipping_subtotal, $this->cart->weight, $this->cart->shippable_total_items, 'RADIO', $GLOBALS['ec_user']->freeshipping, $this->cart->length, $this->cart->width, $this->cart->height, $this->cart->cart );
		$shipping_price = $this->shipping->get_shipping_price( $this->cart->get_handling_total() );
		// Tax (no VAT here)
		$sales_tax_discount = new ec_discount( $this->cart, $this->cart->discountable_subtotal, 0.00, $this->coupon_code, "", 0 );
		$this->tax = new ec_tax( $this->cart->subtotal, $this->cart->taxable_subtotal - $sales_tax_discount->coupon_discount, 0, $shipping_state, $shipping_country, $GLOBALS['ec_user']->taxfree, $shipping_price, $this->cart );
		// Duty (Based on Product Price) - already calculated in tax
		// Get Total Without VAT, used only breifly
		if ( get_option( 'ec_option_no_vat_on_shipping' ) ) {
			$total_without_vat_or_discount = $this->cart->vat_subtotal + $this->tax->tax_total + $this->tax->duty_total;
		} else {
			$total_without_vat_or_discount = $this->cart->vat_subtotal + $shipping_price + $this->tax->tax_total + $this->tax->duty_total;
		}
		//If a discount used, and no vatable subtotal, we need to set to 0
		if ( $total_without_vat_or_discount < 0 )
			$total_without_vat_or_discount = 0;
		// Discount for Coupon
		$this->discount = new ec_discount( $this->cart, $this->cart->discountable_subtotal, $shipping_price, $this->coupon_code, $this->gift_card, $total_without_vat_or_discount );
		// Amount to Apply VAT on
		$promotion = new ec_promotion();
		$vatable_subtotal = $total_without_vat_or_discount - $this->tax->tax_total - $this->discount->coupon_discount - $promotion->get_discount_total( $this->cart->subtotal );
		// If for some reason this is less than zero, we should correct
		if ( $vatable_subtotal < 0 )
			$vatable_subtotal = 0;
		// Get Tax Again For VAT
		$this->tax = new ec_tax( $this->cart->subtotal, $this->cart->taxable_subtotal - $sales_tax_discount->coupon_discount, $vatable_subtotal, $shipping_state, $shipping_country, $GLOBALS['ec_user']->taxfree, $shipping_price, $this->cart );
		// Discount for Gift Card
		$grand_total = ( $this->cart->subtotal + $this->tax->tax_total + $shipping_price + $this->tax->duty_total );
		$this->discount = new ec_discount( $this->cart, $this->cart->discountable_subtotal, $shipping_price, $this->coupon_code, $this->gift_card, $grand_total );
		// Order Totals
		$this->order_totals = new ec_order_totals( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount );
		$GLOBALS['ec_order_grand_total' ] = $this->order_totals->grand_total;

		// Credit Card
		if ( isset( $_POST['ec_expiration_month'] ) && isset( $_POST['ec_expiration_year'] ) ) {
			$exp_month = sanitize_text_field( $_POST['ec_expiration_month'] );
			$exp_year = sanitize_text_field( $_POST['ec_expiration_year'] );

		} else if ( isset( $_POST['ec_cc_expiration'] ) ) {
			$exp_date = sanitize_text_field( $_POST['ec_cc_expiration'] );
			$exp_month = substr( $exp_date, 0, 2 );
			$exp_year = substr( $exp_date, 5 );
			if ( strlen( $exp_year ) == 2 ) {
				$exp_year = "20" . $exp_year;
			}
		}
		if ( isset( $_POST['ec_cart_payment_type'] ) )
			$credit_card = new ec_credit_card( sanitize_text_field( $_POST['ec_cart_payment_type'] ), stripslashes( sanitize_text_field( $_POST['ec_card_holder_name'] ) ), $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ), $exp_month, $exp_year, sanitize_text_field( $_POST['ec_security_code'] ) );
		else if ( isset( $_POST['ec_card_number'] ) )
			$credit_card = new ec_credit_card( $this->get_payment_type( $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ) ), stripslashes( sanitize_text_field( $_POST['ec_card_holder_name'] ) ),  $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ), $exp_month, $exp_year, sanitize_text_field( $_POST['ec_security_code'] ) );
		else
			$credit_card = new ec_credit_card( "", "", "", "", "", "" );

		// Payment
		if ( isset( $_POST['ec_cart_payment_selection'] ) )
			$this->payment = new ec_payment( $credit_card, sanitize_text_field( $_POST['ec_cart_payment_selection'] ) );
		else if ( $is_affirm )
			$this->payment = new ec_payment( $credit_card, "affirm" );
		else
			$this->payment = new ec_payment( $credit_card, "" );

		// Order
		$this->order = new ec_order( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount, $this->order_totals, $this->payment );

		$store_page_id = get_option('ec_option_storepage');
		$cart_page_id = get_option('ec_option_cartpage');
		$account_page_id = get_option('ec_option_accountpage');

		if ( function_exists( 'icl_object_id' ) ) {
			$store_page_id = icl_object_id( $store_page_id, 'page', true, ICL_LANGUAGE_CODE );
			$cart_page_id = icl_object_id( $cart_page_id, 'page', true, ICL_LANGUAGE_CODE );
			$account_page_id = icl_object_id( $account_page_id, 'page', true, ICL_LANGUAGE_CODE );
		}

		$this->store_page = get_permalink( $store_page_id );
		$this->cart_page = get_permalink( $cart_page_id );
		$this->account_page = get_permalink( $account_page_id );

		if ( class_exists( "WordPressHTTPS" ) && isset( $_SERVER['HTTPS'] ) ) {
			$https_class = new WordPressHTTPS();
			$this->store_page = $https_class->makeUrlHttps( $this->store_page );
			$this->cart_page = $https_class->makeUrlHttps( $this->cart_page );
			$this->account_page = $https_class->makeUrlHttps( $this->account_page );

		} else if ( get_option( 'ec_option_load_ssl' ) ) {
			$this->store_page = str_replace( 'http://', 'https://', $this->store_page );
			$this->cart_page = str_replace( 'http://', 'https://', $this->cart_page );
			$this->account_page = str_replace( 'http://', 'https://', $this->account_page );

		}

		if ( substr_count( $this->cart_page, '?' ) )					$this->permalink_divider = "&";
		else														$this->permalink_divider = "?";

		// Subscription Options
		$this->subscription_option1 = $this->subscription_option2 = $this->subscription_option3 = $this->subscription_option4 = $this->subscription_option5 = 0;

		if ( ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option1 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option1 != "" ) || 
			( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option2 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option2 != "" ) || 
			( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option3 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option3 != "" ) || 
			( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option4 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option4 != "" ) || 
			( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option5 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option5 != "" ) ) {

			$optionitem_list = $GLOBALS['ec_options']->get_all_optionitems();

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option1 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option1 != "" ) {
				$this->subscription_option1 = $GLOBALS['ec_cart_data']->cart_data->subscription_option1;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option2 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option2 != "" ) {
				$this->subscription_option2 = $GLOBALS['ec_cart_data']->cart_data->subscription_option2;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option3 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option3 != "" ) {
				$this->subscription_option3 = $GLOBALS['ec_cart_data']->cart_data->subscription_option3;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option4 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option4 != "" ) {
				$this->subscription_option4 = $GLOBALS['ec_cart_data']->cart_data->subscription_option4;
			}

			if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_option5 ) && $GLOBALS['ec_cart_data']->cart_data->subscription_option5 != "" ) {
				$this->subscription_option5 = $GLOBALS['ec_cart_data']->cart_data->subscription_option5;
			}

			foreach( $optionitem_list as $option_item ) {
				if ( $option_item->optionitem_id == $this->subscription_option1 ) {
					$this->subscription_option1_name = $option_item->optionitem_name;
					$this->subscription_option1_label = $option_item->option_label;

				}

				if ( $option_item->optionitem_id == $this->subscription_option2 ) {
					$this->subscription_option2_name = $option_item->optionitem_name;
					$this->subscription_option2_label = $option_item->option_label;

				}

				if ( $option_item->optionitem_id == $this->subscription_option3 ) {
					$this->subscription_option3_name = $option_item->optionitem_name;
					$this->subscription_option3_label = $option_item->option_label;

				}

				if ( $option_item->optionitem_id == $this->subscription_option4 ) {
					$this->subscription_option4_name = $option_item->optionitem_name;
					$this->subscription_option4_label = $option_item->option_label;

				}

				if ( $option_item->optionitem_id == $this->subscription_option5 ) {
					$this->subscription_option5_name = $option_item->optionitem_name;
					$this->subscription_option5_label = $option_item->option_label;
				}
			}

		}

		// Subscription Advanced Options
		if ( isset( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option ) && $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option != "" )
			$this->subscription_advanced_options = maybe_unserialize( $GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option );
		else
			$this->subscription_advanced_options = "";

		// Check for downloads in cart
		$this->has_downloads = false;
		foreach( $this->cart->cart as $cart_item ) {
			if ( $cart_item->is_download ) {
				$this->has_downloads = true;
				break;
			}
		}

		add_filter( 'wp_easycart_shipping_price_display', array( $this, 'apply_promotions_to_shipping' ) );
		add_filter( 'wp_easycart_express_shipping_price_display', array( $this, 'apply_promotions_to_shipping' ) );

		$this->cart_page = apply_filters( 'wp_easycart_cart_page_url', $this->cart_page );
		$this->account_page = apply_filters( 'wp_easycart_account_page_url', $this->account_page );

	}

	public function maybe_add_free_shipping() {
		$count = 0;
		for( $i=0; $i<count( $this->cart->cart ); $i++ ) {
			if ( $this->cart->cart[$i]->is_shippable && !$this->cart->cart[$i]->exclude_shippable_calculation ) {
				$count++;
			}
		}
		if ( ! $count ) {
			$promotion = new ec_promotion();
			if ( count( $promotion->promotions ) ) {
				echo '<div class="ec_cart_shipping_method_row"><input type="radio" class="no_wrap" name="ec_cart_shipping_method" value="promo_free" onchange="ec_cart_shipping_method_change(\'promo_free\', 0, \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );"';
				if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method == 'promo_free' ) {
					echo ' checked="checked"';
				}
				echo '> ' . esc_attr( $promotion->get_free_shipping_promo_label( $this->cart ) ) . ' (' . esc_attr( $GLOBALS['currency']->get_currency_display( apply_filters( 'wp_easycart_shipping_price_display', 0, 'free' ) ) ) . ')</div>';
			}
		}
	}

	public function maybe_set_trigger_rate_free( $rate ) {
		$count = 0;
		for( $i=0; $i<count( $this->cart->cart ); $i++ ) {
			if ( $this->cart->cart[$i]->is_shippable && !$this->cart->cart[$i]->exclude_shippable_calculation ) {
				$count++;
			}
		}
		if ( !$count ) {
			$rate = 0;
		}
		return $rate;
	}

	public function apply_promotions_to_shipping( $rate ) {
		$new_rate = $GLOBALS['ec_promotions']->apply_promotions_to_shipping( $this->order_totals->sub_total, $rate );
		return ( $new_rate >= 0 ) ? $new_rate : 0;
	}

	public function display_cart_success( $success_code = '' ) {
		$success_notes = array(	"account_created" => wp_easycart_language()->get_text( "ec_success", "cart_account_created" ) );

		if ( isset( $_GET['ec_cart_success'] ) ) {
			echo "<div class=\"ec_cart_success\"><div>" . esc_attr( $success_notes[ sanitize_key( $_GET['ec_cart_success'] ) ] ) . "</div></div>";
		} else if ( $success_code != '' ) {
			echo "<div class=\"ec_cart_success\"><div>" . esc_attr( $success_notes[ sanitize_key( $success_code ) ] ) . "</div></div>";
		}
	}

	public function display_cart_error( $error_code = '' ) {
		$error_notes = apply_filters( 'wpeasycart_cart_errors', array( 
			"email_exists"              => wp_easycart_language()->get_text( "ec_errors", "email_exists_error" ),
			"login_failed"              => wp_easycart_language()->get_text( "ec_errors", "login_failed" ),
			"3dsecure_failed"           => wp_easycart_language()->get_text( "ec_errors", "3dsecure_failed" ),
			"manualbill_failed"         => wp_easycart_language()->get_text( "ec_errors", "manualbill_failed" ),
			"thirdparty_failed"         => wp_easycart_language()->get_text( "ec_errors", "thirdparty_failed" ),
			"payment_failed"            => wp_easycart_language()->get_text( "ec_errors", "payment_failed" ),
			"card_error"                => wp_easycart_language()->get_text( "ec_errors", "payment_failed" ),
			"already_subscribed"        => wp_easycart_language()->get_text( "ec_errors", "already_subscribed" ),
			"not_activated"             => wp_easycart_language()->get_text( "ec_errors", "not_activated" ),
			"subscription_not_found"    => wp_easycart_language()->get_text( "ec_errors", "subscription_not_found" ),
			"user_insert_error"         => wp_easycart_language()->get_text( "ec_errors", "user_insert_error" ),
			"subscription_added_failed" => wp_easycart_language()->get_text( "ec_errors", "subscription_added_failed" ),
			"subscription_failed"       => wp_easycart_language()->get_text( "ec_errors", "subscription_failed" ),
			"invalid_address"           => wp_easycart_language()->get_text( "ec_errors", "invalid_address" ),
			"session_expired"           => wp_easycart_language()->get_text( "ec_errors", "session_expired" ),
			"invalid_vat_number"        => wp_easycart_language()->get_text( "ec_errors", "invalid_vat_number" ),
			"stock_invalid"             => wp_easycart_language()->get_text( "ec_errors", "cart_stock_invalid" ),
			"shipping_method"           => wp_easycart_language()->get_text( "ec_errors", "missing_shipping_method" ),
			"invalid_cart_shipping"     => wp_easycart_language()->get_text( "ec_errors", "cart_location_error" )
		) );
		if ( isset( $_GET['ec_cart_error'] ) && $GLOBALS['ec_cart_data']->cart_data->card_error != '' ) {
			echo "<div class=\"ec_cart_error\"><div>" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->card_error ) . "</div></div>";
		} else if ( isset( $_GET['ec_cart_error'] ) ) {
			echo "<div class=\"ec_cart_error\"><div>" . esc_attr( $error_notes[ sanitize_key( $_GET['ec_cart_error'] ) ] ) . "</div></div>";
		} else if ( $error_code != '' ) {
			echo "<div class=\"ec_cart_error\"><div>" . esc_attr( $error_notes[ sanitize_key( $error_code ) ] ) . "</div></div>";
		}
	}

	public function display_cart_success_page( $order_id, $success_code = false, $error_code = false ) {
		global $wpdb;

		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
			$order_row = $this->mysqli->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
		} else {
			$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		}

		if ( !$order_row ) {
			$this->display_cart_page();
			return false;
		}

		do_action( 'wpeasycart_order_success' );

		$order = new ec_orderdisplay( $order_row, true );

		if ( $GLOBALS['ec_cart_data']->cart_data->guest_key != "" ) {
			$order_details = $this->mysqli->get_guest_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );

		} else {
			$order_details = $this->mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		}

		$GLOBALS['ec_user']->setup_billing_info_data( $order->billing_first_name, $order->billing_last_name, $order->billing_address_line_1, $order->billing_address_line_2, $order->billing_city, $order->billing_state, $order->billing_country, $order->billing_zip, $order->billing_phone, $order->billing_company_name );

		$GLOBALS['ec_user']->setup_shipping_info_data( $order->shipping_first_name, $order->shipping_last_name, $order->shipping_address_line_1, $order->shipping_address_line_2, $order->shipping_city, $order->shipping_state, $order->shipping_country, $order->shipping_zip, $order->shipping_phone, $order->shipping_company_name );

		$tax_struct = $this->tax;

		$total = $GLOBALS['currency']->get_currency_display( $order->grand_total );
		$subtotal = $GLOBALS['currency']->get_currency_display( $order->sub_total );
		$tax = $GLOBALS['currency']->get_currency_display( $order->tax_total );
		$duty = $GLOBALS['currency']->get_currency_display( $order->duty_total );
		$vat = $GLOBALS['currency']->get_currency_display( $order->vat_total );
		if ( ( $order->grand_total - $order->vat_total ) > 0 )
			$vat_rate = number_format( $this->tax->vat_rate, 0, '', '' );
		else
			$vat_rate = number_format( 0, 0, '', '' );
		$shipping = $GLOBALS['currency']->get_currency_display( $order->shipping_total );
		$discount = $GLOBALS['currency']->get_currency_display( $order->discount_total );

		//google analytics
		$this->analytics = new ec_googleanalytics($order_details, $order->shipping_total, $order->tax_total , $order->grand_total, $order_id);
		$google_urchin_code = get_option('ec_option_googleanalyticsid');
		$google_wp_url = sanitize_text_field( $_SERVER['SERVER_NAME'] );
		//end google analytics
		$this->display_cart_error();

		//Backwards compatibility for an error... Don't want the button showing if user didn't create an account.
		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
			$GLOBALS['ec_cart_data']->cart_data->email = "guest";
		}

		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_success.php' ) )	{
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_success.php' );
		} else {
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_success.php' );
		}

		// Update Cart Success Print Variable
		$wpdb->query( $wpdb->prepare( 'UPDATE ec_order SET success_page_shown = 1 WHERE ec_order.order_id = %d', $order_id ) );
	}

	public function print_google_transaction() {
		$this->analytics->print_transaction_js();
		$this->analytics->print_item_js();
	}

	public function display_cart_page() {
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
			if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
				$stripe = new ec_stripe();
			} else {
				$stripe = new ec_stripe_connect();
			}

			$stripe_pi_response = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );
			if ( $stripe_pi_response && in_array( $stripe_pi_response->status, array( 'succeeded', 'processing', 'requires_capture', 'canceled' ) ) ) {
				global $wpdb;
				$ec_db_admin = new ec_db_admin();
				$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $stripe_pi_response->id . ':' . $stripe_pi_response->client_secret ) );
				if ( $order ) {
					$order_id = $order->order_id;
				} else {
					$source = array(
						'id' => $stripe_pi_response->id,
						'client_secret' => $stripe_pi_response->client_secret,
					);
					$order_id = $this->insert_ideal_order( $source, $stripe_pi_response );
					$stripe->update_payment_intent_description( $stripe_pi_response->id, $order_id );

					global $wpdb;
					$order_status = 6;
					if ( $stripe_pi_response->status == 'succeeded' ) {
						$order_status = 3;
					} else if ( $stripe_pi_response->status == 'requires_capture' ) {
						$order_status = 12;
					} else if ( $stripe_pi_response->status == 'processing' ) {
						$order_status = 12;
					} else if ( $stripe_pi_response->status == 'canceled' ) {
						$order_status = 19;
					}
					$wpdb->get_row( $wpdb->prepare( "UPDATE ec_order SET orderstatus_id = %d WHERE order_id = %d", $order_status, (int) $order_id ) );
				}
				$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
				$GLOBALS['ec_cart_data']->checkout_session_complete();
				$GLOBALS['ec_cart_data']->save_session_to_db();
				echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
					echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">';
						echo '<div style="text-align:center; font-size:20px;" class="wpeasycart-stripe-already-paid-note">' . wp_easycart_language( )->get_text( 'cart_payment_information', 'payment_processed' ) . '</div>';
						echo '<div style="text-align:center; padding-top:20px;" class="wpeasycart-stripe-already-paid-button-row"><a href="' . esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $order_id ) . '">' . wp_easycart_language( )->get_text( 'cart_payment_information', 'payment_processed_view' ) . '</a></div>';
					echo '</div>';
				echo '</div>';
				return;

			} else if ( $stripe_pi_response && ( $stripe_pi_response->status == 'requires_action' || $stripe_pi_response->status == 'requires_source_action' ) ) {
				if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'redirect_to_url' == $stripe_pi_response->next_action->type ) {
					echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
						echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
					echo '</div>';
					echo '<script>window.location.href = "' . $stripe_pi_response->next_action->redirect_to_url->url . '";</script>';

				} else if ( isset( $stripe_pi_response->next_source_action ) && isset( $stripe_pi_response->next_source_action->type ) && 'authorize_with_url' == $stripe_pi_response->next_source_action->type ) {
					echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
						echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
					echo '</div>';
					echo '<script>window.location.href = "' . $stripe_pi_response->next_source_action->authorize_with_url->url . '";</script>';

				} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'use_stripe_sdk' == $stripe_pi_response->next_action->type ) {
					if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
						$pkey = get_option( 'ec_option_stripe_public_api_key' );
					} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
						$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
					} else {
						$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
					}
					echo '<script>
					try {
						var stripe = Stripe( "' . esc_attr( $pkey ) . '" );
						stripe.handleNextAction( {
							clientSecret: "' . esc_attr( $stripe_pi_response->client_secret ) . '"
						} ).then( function( result ) {
							alert( "There is a problem handling your payment: " + err.message + ". Contact Support for assistance." );
						} );
					} catch( err ) {
						alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
					}
					</script>';
				} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'alipay_handle_redirect' == $stripe_pi_response->next_action->type ) {
					/* Handle Later? */
				} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'oxxo_display_details' == $stripe_pi_response->next_action->type ) {
					/* Handle Later? */
				}
				return;
			}
		} else if ( $stripe_pi_response && ( $stripe_pi_response->status == 'requires_confirmation' || $stripe_pi_response->status == 'pending' ) ) {
			echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
				echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
			echo '</div>';
			echo '<script>
				ec_stripe_check_order_status( "' . esc_attr( $stripe_pi_response->id ) . '", "' . esc_attr( wp_create_nonce( 'wp-easycart-create-stripe-ideal-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '" );
			</script>';
			return;
		}

		if ( get_option( 'ec_option_googleanalyticsid' ) != "UA-XXXXXXX-X" && get_option( 'ec_option_googleanalyticsid' ) != "" ) {
			echo "<script>
			(function(i,s,o,g,r,a,m) {i['GoogleAnalyticsObject']=r;i[r]=i[r]||function() {
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', '" . esc_attr( get_option( 'ec_option_googleanalyticsid' ) ) . "', 'auto');
			ga('send', 'pageview');
			ga('require', 'ec');
			function ec_google_removeFromCart( model_number, title, quantity, price ) {
			  ga('ec:addProduct', {
				'id': model_number,
				'name': title,
				'price': price,
				'quantity': quantity
			  });
			  ga('ec:setAction', 'remove');
			  ga('send', 'event', 'UX', 'click', 'remove from cart');     // Send data using an event.
			}";

			// Setup Cart
			for( $i=0; $i < count( $this->cart->cart ); $i++ ) {
				echo "
				ga( 'ec:addProduct', {
				  'id': '" . esc_js( $this->cart->cart[$i]->model_number ) . "',
				  'name': '" . esc_js( $this->cart->cart[$i]->title ) . "',
				  'price': '" . esc_js( $this->cart->cart[$i]->unit_price ) . "',
				  'quantity': '" . esc_js( $this->cart->cart[$i]->quantity ) . "'
				});";
			}

			// View of Cart
			if ( !isset( $_GET['ec_page'] )  ) {
				echo "
				ga('ec:setAction','checkout', {
					'step': 1,
					'option': 'Cart View'
				});
				ga('send', 'pageview');";

			// View of Checkout Info
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_info" ) {
				echo "
				ga('ec:setAction','checkout', {
					'step': 2,
					'option': 'Checkout Info'
				});
				ga('send', 'pageview');";

			// View of Payment Method
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_payment" ) {
				echo "
				ga('ec:setAction','checkout', {
					'step': 3,
					'option': 'Checkout Payment'
				});
				ga('send', 'pageview');";

			// View of thankyou page
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) {
				echo "
				ga('ec:setAction','checkout', {
					'step': 4,
					'option': 'Checkout Success'
				});
				ga('send', 'pageview');";

			}

			echo "</script>";
		}

		if ( '' != get_option( 'ec_option_google_ga4_property_id' ) && ! get_option( 'ec_option_onepage_checkout' ) ) {
			$ga4_event = false;
			if ( ! isset( $_GET['ec_page'] ) ) {
				$ga4_event = 'view_cart';
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_info" ) {
				$ga4_event = 'begin_checkout';
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_shipping" ) {
				$ga4_event = 'add_shipping_info';
			} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_payment" ) {
				$ga4_event = 'add_payment_info';
			}

			if ( $ga4_event ) {
				if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
					echo '<script>
					jQuery( document ).ready( function() {
						dataLayer.push( { ecommerce: null } );
						dataLayer.push( {
							event: "' . $ga4_event . '",
							ecommerce: {
								currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
								value: ' . esc_attr( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ',
								coupon_code: "' . esc_attr( $this->coupon_code ) . '",';
					if ( 'add_shipping_info' == $ga4_event ) {
						echo '
								shipping_tier: "' . esc_attr( trim( strip_tags( $this->shipping->get_selected_shipping_method() ) ) ) . '",';
					}
					echo '
								items: [ ';
								for( $i=0; $i < count( $this->cart->cart ); $i++ ) {
									echo '{
										item_id: "' . esc_attr( $this->cart->cart[$i]->model_number ) . '",
										item_name: "' . esc_attr( $this->cart->cart[$i]->title ) . '",
										index: ' . $i . ',
										price: ' . esc_attr( number_format( $this->cart->cart[$i]->unit_price, 2, '.', '' ) ) . ',
										item_brand: "' . esc_attr( $this->cart->cart[$i]->manufacturer_name ) . '",
										quantity: ' . esc_attr( number_format( $this->cart->cart[$i]->quantity, 2, '.', '' ) ) . '
									}, ';
								}
								echo ' ]
							}
						} );
					} );
					</script>';
				} else {
					echo '<script>
					jQuery( document ).ready( function() {
						gtag( "event", "' . $ga4_event . '", {
							currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
							value: ' . esc_attr( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ',
							coupon_code: "' . esc_attr( $this->coupon_code ) . '",';
					if ( 'add_shipping_info' == $ga4_event ) {
					echo '
							shipping_tier: "' . esc_attr( trim( strip_tags( $this->shipping->get_selected_shipping_method() ) ) ) . '",';
					}
					echo '
							items: [ ';
							for( $i=0; $i < count( $this->cart->cart ); $i++ ) {
								echo '{
									item_id: "' . esc_attr( $this->cart->cart[$i]->model_number ) . '",
									item_name: "' . esc_attr( $this->cart->cart[$i]->title ) . '",
									index: ' . $i . ',
									price: ' . esc_attr( number_format( $this->cart->cart[$i]->unit_price, 2, '.', '' ) ) . ',
									item_brand: "' . esc_attr( $this->cart->cart[$i]->manufacturer_name ) . '",
									quantity: ' . esc_attr( number_format( $this->cart->cart[$i]->quantity, 2, '.', '' ) ) . '
								}, ';
							}
							echo ' ]
						} );
					} );
					</script>';
				}
			}
		}

		echo "<div class=\"ec_cart_page\">";
		if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) {
			do_action( 'wpeasycart_order_success' );
			$order_id = (int) $_GET['order_id'];
			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
				$order_row = $this->mysqli->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
			} else {
				$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
			}
			$order = new ec_orderdisplay( $order_row, true );

			if ( $GLOBALS['ec_cart_data']->cart_data->guest_key != "" ) {
				$order_details = $this->mysqli->get_guest_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );

			} else {
				$order_details = $this->mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
			}

			$GLOBALS['ec_user']->setup_billing_info_data( $order->billing_first_name, $order->billing_last_name, $order->billing_address_line_1, $order->billing_address_line_2, $order->billing_city, $order->billing_state, $order->billing_country, $order->billing_zip, $order->billing_phone, $order->billing_company_name );

			$GLOBALS['ec_user']->setup_shipping_info_data( $order->shipping_first_name, $order->shipping_last_name, $order->shipping_address_line_1, $order->shipping_address_line_2, $order->shipping_city, $order->shipping_state, $order->shipping_country, $order->shipping_zip, $order->shipping_phone, $order->shipping_company_name );

			$tax_struct = $this->tax;

			$total = $GLOBALS['currency']->get_currency_display( $order->grand_total );
			$subtotal = $GLOBALS['currency']->get_currency_display( $order->sub_total );
			$tax = $GLOBALS['currency']->get_currency_display( $order->tax_total );
			$duty = $GLOBALS['currency']->get_currency_display( $order->duty_total );
			$vat = $GLOBALS['currency']->get_currency_display( $order->vat_total );
			if ( ( $order->grand_total - $order->vat_total ) > 0 )
				$vat_rate = number_format( $this->tax->vat_rate, 0, '', '' );
			else
				$vat_rate = number_format( 0, 0, '', '' );
			$shipping = $GLOBALS['currency']->get_currency_display( $order->shipping_total );
			$discount = $GLOBALS['currency']->get_currency_display( $order->discount_total );

			//google analytics
			$this->analytics = new ec_googleanalytics($order_details, $order->shipping_total, $order->tax_total , $order->grand_total, $order_id);
			$google_urchin_code = get_option('ec_option_googleanalyticsid');
			$google_wp_url = sanitize_text_field( $_SERVER['SERVER_NAME'] );
			//end google analytics
			$this->display_cart_error();

			//Backwards compatibility for an error... Don't want the button showing if user didn't create an account.
			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest )
				$GLOBALS['ec_cart_data']->cart_data->email = "guest";

			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_success.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_success.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_success.php' );

		} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "third_party" ) {
			$order_id = (int) $_GET['order_id'];

			if ( $GLOBALS['ec_cart_data']->cart_data->is_guest != "" && $GLOBALS['ec_cart_data']->cart_data->is_guest ) {
				$order = $this->mysqli->get_guest_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
				$order_details = $this->mysqli->get_guest_order_details( $this->order_id, $GLOBALS['ec_cart_data']->cart_data->guest_key );
			} else {
				$order = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
				$order_details = $this->mysqli->get_order_details( $this->order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
			}

			//google analytics
			$this->analytics = new ec_googleanalytics($order_details, $order->shipping_total, $order->tax_total , $order->grand_total, $order_id);
			$google_urchin_code = get_option('ec_option_googleanalyticsid');
			$google_wp_url = sanitize_text_field( $_SERVER['SERVER_NAME'] );
			//end google analytics
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_third_party.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_third_party.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_third_party.php' );

		} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "subscription_info" ) {

			$this->display_subscription_page();

		} else if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "invoice" ) {
			global $wpdb;
			$invoice = $invoice = $wpdb->get_row( $wpdb->prepare( "SELECT ec_order.* FROM ec_order, ec_orderstatus WHERE guest_key = %s AND ec_orderstatus.status_id = ec_order.orderstatus_id AND ec_orderstatus.is_approved = 0", sanitize_key( $_GET['ec_guest_key'] ) ) );
			if ( !$invoice ) {


			} else {
				$invoice_items = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_orderdetail WHERE order_id = %d", $invoice->order_id ) );
				if ( !$invoice_items ) {


				} else {
					for( $i=0; $i<count( $invoice_items ); $i++ ) {
						if ( $invoice_items[$i]->use_advanced_optionset ) {
							$invoice_items[$i]->advanced_options = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM ec_order_option WHERE orderdetail_id = %d ORDER BY option_order ASC", $invoice_items[$i]->orderdetail_id ) );
					   }
					}
					if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_invoice.php' ) )	
						include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_invoice.php' );
					else
						include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_invoice.php' );
				}
			}

		} else {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_page.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_page.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_page.php' );
		}
		echo "</div>";
	}

	public function display_subscription_page( $subscription_product_id = false ) {
		$this->display_cart_error();

		$subscription_found = false;

		if ( isset( $_GET['subscription'] ) || $subscription_product_id ) {

			wpeasycart_session()->handle_session();

			global $wpdb;
			$subscription_cart = array();
			$model_number = ( isset( $_GET['subscription'] ) ) ? sanitize_text_field( $_GET['subscription'] ) : '';
			if ( $subscription_product_id ) {
				$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.product_id = %d", $subscription_product_id ), "", "", "" );
			} else {
				$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s", $model_number ), "", "", "" );
			}

			if ( count( $products ) > 0 && ! $products[0]['allow_multiple_subscription_purchases'] && $GLOBALS['ec_user']->has_active_subscription( $products[0]['product_id'] ) ) {
				echo '<div class="ec_subscription_purchased">' . wp_easycart_language()->get_text( 'cart_login', 'cart_subscription_already_purchased' ) . '</div>';
				return;
			}

			if ( count( $products ) > 0 ) {
				$model_number = $products[0]['model_number'];
				$subscription_found = true;
				$product = new ec_product( $products[0], 0, 1, 0 );
				$this->cart->cart = array( $product );

				if ( !get_option( 'ec_option_subscription_one_only' ) && $GLOBALS['ec_cart_data']->cart_data->subscription_quantity != "" ) { 
					$subscription_quantity = $GLOBALS['ec_cart_data']->cart_data->subscription_quantity;
				} else { 
					$subscription_quantity = 1; 
				}

				// Get option item price adjustments
				$option_promotion_multiplier = 1;
				$option_promotion_discount = 0;
				$promotions = $GLOBALS['ec_promotions']->promotions;
				for( $i=0; $i<count( $promotions ); $i++ ) {
					if ( $product->promotion_text == $promotions[$i]->promotion_name ) {
						if ( $promotions[$i]->price1 == 0 ) {
							$option_promotion_multiplier = round( $promotions[$i]->percentage1 / 100, 2 );
						} else if ( $promotions[$i]->price1 != 0 ) {
							$option_promotion_discount = $promotions[$i]->price1;
						}
					}
				}

				$option_total = 0;
				$option_total_onetime = 0;
				$option_weight = 0;
				$option_weight_onetime = 0;
				if ( $this->subscription_option1 != 0 ) {
					$subscription_option1 = $GLOBALS['ec_options']->get_optionitem( $this->subscription_option1 );
					if ( $subscription_option1->optionitem_price > 0 ) {
						$option_total += $subscription_option1->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $subscription_option1->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
					if ( $subscription_option1->optionitem_weight > 0 ) {
						$option_weight += $subscription_option1->optionitem_weight;
					}
				}
				if ( $this->subscription_option2 != 0 ) {
					$subscription_option2 = $GLOBALS['ec_options']->get_optionitem( $this->subscription_option2 );
					if ( $subscription_option2->optionitem_price > 0 ) {
						$option_total += $subscription_option2->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $subscription_option2->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
					if ( $subscription_option2->optionitem_weight > 0 ) {
						$option_weight += $subscription_option2->optionitem_weight;
					}
				}
				if ( $this->subscription_option3 != 0 ) {
					$subscription_option3 = $GLOBALS['ec_options']->get_optionitem( $this->subscription_option3 );
					if ( $subscription_option3->optionitem_price > 0 ) {
						$option_total += $subscription_option3->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $subscription_option3->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
					if ( $subscription_option3->optionitem_weight > 0 ) {
						$option_weight += $subscription_option3->optionitem_weight;
					}
				}
				if ( $this->subscription_option4 != 0 ) {
					$subscription_option4 = $GLOBALS['ec_options']->get_optionitem( $this->subscription_option4 );
					if ( $subscription_option4->optionitem_price > 0 ) {
						$option_total += $subscription_option4->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $subscription_option4->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
					if ( $subscription_option4->optionitem_weight > 0 ) {
						$option_weight += $subscription_option4->optionitem_weight;
					}
				}
				if ( $this->subscription_option5 != 0 ) {
					$subscription_option5 = $GLOBALS['ec_options']->get_optionitem( $this->subscription_option5 );
					if ( $subscription_option5->optionitem_price > 0 ) {
						$option_total += $subscription_option5->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $subscription_option5->optionitem_price * $subscription_quantity, 2 ),
							'item_discount' => 0,
						);
					}
					if ( $subscription_option5->optionitem_weight > 0 ) {
						$option_weight += $subscription_option5->optionitem_weight;
					}
				}
				if ( $this->subscription_advanced_options ) {
					foreach( $this->subscription_advanced_options as $option ) {
						$optionitem = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
						if ( $optionitem->optionitem_disallow_shipping ) {
							$product->is_shippable = false;
						}
						if ( $optionitem && $optionitem->optionitem_price > 0 ) {
							if ( 'number' == $option['option_type'] ) {
								$option_total += ( $optionitem->optionitem_price * (int) $option['optionitem_value'] );
								$subscription_cart[] = (object) array(
									'vat_enabled' => ( $product->vat_rate != 0 ),
									'is_taxable' => $product->is_taxable,
									'item_total' => round( ( $optionitem->optionitem_price * (int) $option['optionitem_value'] ) * $subscription_quantity, 2 ),
									'item_discount' => 0,
								);
							} else {
								$option_total += $optionitem->optionitem_price;
								$subscription_cart[] = (object) array(
									'vat_enabled' => ( $product->vat_rate != 0 ),
									'is_taxable' => $product->is_taxable,
									'item_total' => round( $optionitem->optionitem_price * $subscription_quantity, 2 ),
									'item_discount' => 0,
								);
							}
						} else if ( $optionitem && $optionitem->optionitem_price_onetime > 0 ) {
							if ( 'number' == $option['option_type'] ) {
								$option_total_onetime += ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] );
								$subscription_cart[] = (object) array(
									'vat_enabled' => ( $product->vat_rate != 0 ),
									'is_taxable' => $product->is_taxable,
									'item_total' => round( ( $optionitem->optionitem_price_onetime * (int) $option['optionitem_value'] ), 2 ),
									'item_discount' => 0,
								);
							} else {
								$option_total_onetime += $optionitem->optionitem_price_onetime;
								$subscription_cart[] = (object) array(
									'vat_enabled' => ( $product->vat_rate != 0 ),
									'is_taxable' => $product->is_taxable,
									'item_total' => round( $optionitem->optionitem_price_onetime, 2 ),
									'item_discount' => 0,
								);
							}
						} else if ( $optionitem && $optionitem->optionitem_price_override > -1 ) {
							$product->price = $optionitem->optionitem_price_override;
						}
						if ( $optionitem && $optionitem->optionitem_weight > 0 ) {
							if ( 'number' == $option['option_type'] ) {
								$option_weight += ( $optionitem->optionitem_weight * (int) $option['optionitem_value'] );
							} else {
								$option_weight += $optionitem->optionitem_weight;
							}
						} else if ( $optionitem && $optionitem->optionitem_weight_onetime > 0 ) {
							if ( 'number' == $option['option_type'] ) {
								$option_weight_onetime += ( $optionitem->optionitem_weight_onetime * (int) $option['optionitem_value'] );
							} else {
								$option_weight_onetime += $optionitem->optionitem_weight_onetime;
							}
						} else if ( $optionitem && $optionitem->optionitem_weight_override > -1 ) {
							$product->weight = $optionitem->optionitem_weight_override;
						}
					}
				}

				$subscription_cart[] = (object) array(
					'vat_enabled' => ( $product->vat_rate != 0 ),
					'is_taxable' => $product->is_taxable,
					'item_total' => round( $product->price * $subscription_quantity, 2 ),
					'item_discount' => 0,
				);

				if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
					$ship_price_total = ( $product->price + $option_total ) * $subscription_quantity + $option_total_onetime;
					$ship_weight_total = ( $product->weight + $option_weight ) * $subscription_quantity + $option_weight_onetime;
					$ship_quantity = $subscription_quantity;
				} else {
					$ship_price_total = 0;
					$ship_weight_total = 0;
					$ship_quantity = 0;
				}

				$product->weight = $ship_weight_total;
				$product->quantity = $ship_quantity;
				do_action( 'wpeasycart_cart_subscription_updated', $product, $subscription_quantity );

				if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
					$this->shipping = new ec_shipping( $ship_price_total, $ship_weight_total, $ship_quantity, 'RADIO', $GLOBALS['ec_user']->freeshipping, $product->length, $product->width, $product->height * $ship_quantity, array( $product ) );
					$this->shipping->change_shipping_js_func = 'ec_cart_subscription_shipping_method_change';
					$this->cart->shippable_total_items = $subscription_quantity;
					$handling_total = $product->handling_price + ( $product->handling_price_each * $subscription_quantity );
					$shipping_total = floatval( $this->shipping->get_shipping_price( $handling_total ) );
					$shipping_total = floatval( $this->shipping->get_shipping_price( $handling_total ) );
					$subscription_cart[] = (object) array(
						'vat_enabled' => ! get_option( 'ec_option_no_vat_on_shipping' ),
						'is_taxable' => get_option( 'ec_option_collect_tax_on_shipping' ),
						'item_total' => round( $shipping_total, 2 ),
						'item_discount' => 0,
					);
				} else {
					$handling_total = 0;
					$shipping_total = 0;
				}

				// get discount amount
				$discount_amount = 0;
				$is_dollar_discount = false;
				if ( isset( $this->coupon ) ) { // Invalid Coupon
					$is_valid_coupon = false;
					if ( $this->coupon->by_product_id ) { // validate product id match
						if ( $this->coupon->product_id == $product->product_id ) {
							$is_valid_coupon = true;
						}
					} else if ( $this->coupon->by_manufacturer_id ) { // validate manufacturer id match
						if ( $this->coupon->manufacturer_id == $product->manufacturer_id ) {
							$is_valid_coupon = true;
						}
					} else if ( $this->coupon->by_category_id ) { // validate category id match
						if ( $has_categories = $wpdb->get_results( $wpdb->prepare( "SELECT categoryitem_id FROM ec_categoryitem WHERE category_id = %d AND product_id = %d", $this->coupon->category_id, $product->product_id ) ) ) {
							$is_valid_coupon = true;
						}
					} else {
						$is_valid_coupon = true;
					}
					if ( $is_valid_coupon ) {
						if ( $this->coupon->is_percentage_based ) {
							$coupon_percentage = round( ( $this->coupon->promo_percentage / 100 ), 2  );
							for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
								$subscription_cart[ $i ]->item_discount = round( (float) $subscription_cart[ $i ]->item_total * $coupon_percentage, 2 );
								$discount_amount += $subscription_cart[ $i ]->item_discount;
							}
						} else if ( $this->coupon->is_dollar_based ) {
							$is_dollar_discount = true;
							$discount_amount = $this->coupon->promo_dollar;
						}
						if ( $discount_amount > ( $product->price + $option_total ) * $subscription_quantity + $option_total_onetime + $shipping_total ) {
							$discount_amount = ( $product->price + $option_total ) * $subscription_quantity + $option_total_onetime + $shipping_total;
						}
						$discount_amount = round( $discount_amount, 2 );
					} else {
						unset( $this->coupon );
					}
				} else if ( $option_promotion_multiplier < 1 ) {
					for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
						$subscription_cart[ $i ]->item_discount = round( (float) $subscription_cart[ $i ]->item_total * $option_promotion_multiplier, 2 );
						$discount_amount += $subscription_cart[ $i ]->item_discount;
					}
				} else if ( $option_promotion_discount > 0 ) {
					$is_dollar_discount = true;
					$discount_amount = round( $option_promotion_discount, 2 );
				}

				do_action( 'wpeasycart_cart_subscription_pre_tax', $product, $subscription_quantity, $shipping_total, $handling_total, $discount_amount );

				wpeasycart_taxcloud()->setup_subscription_for_tax( $product, $subscription_quantity, $discount_amount, $option_total, $option_total_onetime );
				if ( function_exists( 'wpeasycart_taxjar' ) ) {
					wpeasycart_taxjar()->setup_subscription_for_tax( $product, $subscription_quantity, $discount_amount, $option_total, $option_total_onetime );
				}

				$sub_total = ( ( $product->price + $option_total ) * $subscription_quantity ) + $option_total_onetime;
				if ( $is_dollar_discount ) {
					for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
						$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - round( ( $subscription_cart[$i]->item_total / ( $sub_total + $shipping_total ) ) * $discount_amount, 2 );
					}
				} else {
					for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
						$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - $subscription_cart[$i]->item_discount;
					}
				}

				$tax_subtotal = ( $product->is_taxable ) ? $sub_total - ( $product->subscription_signup_fee * $subscription_quantity ) : 0;
				$vat_subtotal = ( $product->vat_rate > 0 ) ? $sub_total - ( $product->subscription_signup_fee * $subscription_quantity ) : 0;
				$ec_tax = new ec_tax( $sub_total, $tax_subtotal, $vat_subtotal, ( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_state : $GLOBALS['ec_user']->shipping->state, ( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_country : $GLOBALS['ec_user']->shipping->country, $GLOBALS['ec_user']->taxfree, 0, $subscription_cart, true );

				$tax_total = round( $ec_tax->tax_total, 2 );
				$vat_rate = $ec_tax->vat_rate;
				$vat_total = round( $ec_tax->vat_total, 2 );

				$hst_total = round( $ec_tax->hst, 2 );
				$pst_total = round( $ec_tax->pst, 2 );
				$gst_total = round( $ec_tax->gst, 2 );

				$hst_rate = $ec_tax->hst_rate;
				$pst_rate = $ec_tax->pst_rate;
				$gst_rate = $ec_tax->gst_rate;

				if ( $product->trial_period_days > 0 ) {
					$grand_total = ( ( $product->subscription_signup_fee ) * $subscription_quantity );
				} else if ( $ec_tax->vat_included ) {
					$grand_total = ( ( $product->price + $option_total + $product->subscription_signup_fee ) * $subscription_quantity ) + $option_total_onetime - $discount_amount + $tax_total + $hst_total + $gst_total + $pst_total + $shipping_total;
				} else {
					$grand_total = ( ( $product->price + $option_total + $product->subscription_signup_fee ) * $subscription_quantity ) + $option_total_onetime - $discount_amount + $vat_total + $tax_total + $hst_total + $pst_total + $gst_total + $shipping_total;
				}

				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_subscription.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_subscription.php' );
				}
			}
		}
	}

	public function display_cart_process() {
		if (	$this->cart->total_items > 0 || ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_process.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_process.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_process.php' );
		}
	}

	public function display_cart_process_cart_link( $link_text ) {
		if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) {
			echo esc_attr( $link_text );
		} else {
			echo "<a href=\"" . esc_attr( $this->cart_page ) . "\" class=\"ec_process_bar_link\">" . esc_attr( $link_text ) . "</a>";
		}
	}

	public function display_cart_process_shipping_link( $link_text ) {
		if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) {
			echo esc_attr( $link_text );
		} else {
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_info\" class=\"ec_process_bar_link\">" . esc_attr( $link_text ) . "</a>";
		}
	}

	public function display_cart_process_review_link( $link_text ) {
		if ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_success" ) {
			echo esc_attr( $link_text );
		} else if ( $GLOBALS['ec_cart_data']->cart_data->billing_first_name != "" ) {
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment\" class=\"ec_process_bar_link\">" . esc_attr( $link_text ) . "</a>";
		} else {
			echo esc_attr( $link_text );
		}
	}

	public function display_cart_dynamic( $cart_page, $success_code, $error_code ) {
		$ec_db = new ec_db();
		$cart_count = $ec_db->get_cart_count( $GLOBALS['ec_cart_data']->ec_cart_id );

		if ( $cart_count > 0 ) {
			if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
				if ( 'stripe' == get_option( 'ec_option_payment_process_method' ) ) {
					$stripe = new ec_stripe();
				} else {
					$stripe = new ec_stripe_connect();
				}

				$stripe_pi_response = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );
				if ( $stripe_pi_response && in_array( $stripe_pi_response->status, array( 'succeeded', 'processing', 'requires_capture', 'canceled' ) ) ) {
					global $wpdb;
					$ec_db_admin = new ec_db_admin();
					$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $stripe_pi_response->id . ':' . $stripe_pi_response->client_secret ) );
					if ( $order ) {
						$order_id = $order->order_id;
					} else {
						$source = array(
							'id' => $stripe_pi_response->id,
							'client_secret' => $stripe_pi_response->client_secret,
						);
						$order_id = $this->insert_ideal_order( $source, $stripe_pi_response );
						$stripe->update_payment_intent_description( $stripe_pi_response->id, $order_id );

						global $wpdb;
						$order_status = 6;
						if ( $stripe_pi_response->status == 'succeeded' ) {
							$order_status = 3;
						} else if ( $stripe_pi_response->status == 'requires_capture' ) {
							$order_status = 12;
						} else if ( $stripe_pi_response->status == 'processing' ) {
							$order_status = 12;
						} else if ( $stripe_pi_response->status == 'canceled' ) {
							$order_status = 19;
						}
						$wpdb->get_row( $wpdb->prepare( "UPDATE ec_order SET orderstatus_id = %d WHERE order_id = %d", $order_status, (int) $order_id ) );
					}
					$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
					$GLOBALS['ec_cart_data']->checkout_session_complete();
					$GLOBALS['ec_cart_data']->save_session_to_db();
					echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
						echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">';
							echo '<div style="text-align:center; font-size:20px;" class="wpeasycart-stripe-already-paid-note">Your payment has been processed and you may view your order now</div>';
							echo '<div style="text-align:center; padding-top:20px;" class="wpeasycart-stripe-already-paid-button-row"><a href="' . esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $order_id ) . '">View Order</a></div>';
						echo '</div>';
					echo '</div>';
					return;
					
				} else if ( $stripe_pi_response && ( $stripe_pi_response->status == 'requires_action' || $stripe_pi_response->status == 'requires_source_action' ) ) {
					if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'redirect_to_url' == $stripe_pi_response->next_action->type ) {
						echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
							echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
						echo '</div>';
						echo '<script>window.location.href = "' . $stripe_pi_response->next_action->redirect_to_url->url . '";</script>';

					} else if ( isset( $stripe_pi_response->next_source_action ) && isset( $stripe_pi_response->next_source_action->type ) && 'authorize_with_url' == $stripe_pi_response->next_source_action->type ) {
						echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
							echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
						echo '</div>';
						echo '<script>window.location.href = "' . $stripe_pi_response->next_source_action->authorize_with_url->url . '";</script>';

					} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'use_stripe_sdk' == $stripe_pi_response->next_action->type ) {
						if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
							$pkey = get_option( 'ec_option_stripe_public_api_key' );
						} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
							$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
						} else {
							$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
						}
						echo '<script>
						try {
							stripe.handleNextAction( {
								clientSecret: "' . esc_attr( $stripe_pi_response->client_secret ) . '"
							} ).then( function( result ) {
								alert( "There is a problem handling your payment: " + err.message + ". Contact Support for assistance." );
							} );
						} catch( err ) {
							alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
						}
						</script>';
					} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'alipay_handle_redirect' == $stripe_pi_response->next_action->type ) {
						/* Handle Later? */
					} else if ( isset( $stripe_pi_response->next_action ) && isset( $stripe_pi_response->next_action->type ) && 'oxxo_display_details' == $stripe_pi_response->next_action->type ) {
						/* Handle Later? */
					}
					return;
				} else if ( $stripe_pi_response && ( $stripe_pi_response->status == 'requires_confirmation' || $stripe_pi_response->status == 'pending' ) ) {
					echo '<div class="wpeasycart-stripe-already-paid" style="position:fixed;top:0;left:0;width:100%;height:100%;z-index:999999;background:rgba(0,0,0,.8);">';
						echo '<div class="wpeasycart-stripe-already-paid-container" style="position:fixed; left:50%; top:50%; margin-left:-250px; margin-top:-80px; width:500px; max-width:100%; max-height:100%; background:#EFEFEF; padding:35px; border-radius:10px; text-align:center;">Just a moment, please wait.</div>';
					echo '</div>';
					echo '<script>
						ec_stripe_check_order_status( "' . esc_attr( $stripe_pi_response->id ) . '", "' . esc_attr( wp_create_nonce( 'wp-easycart-create-stripe-ideal-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '" );
					</script>';
					return;
				}
			}

			if ( '' != get_option( 'ec_option_google_ga4_property_id' ) && ! get_option( 'ec_option_onepage_checkout' ) ) {
				$ga4_event = 'view_cart';
				if ( 1 == $cart_page ) {
					$ga4_event = 'view_cart';
				} else if ( 2 == $cart_page ) {
					$ga4_event = 'begin_checkout';
				} else if ( 3 == $cart_page ) {
					$ga4_event = 'add_shipping_info';
				} else if ( 4 == $cart_page ) {
					$ga4_event = 'add_payment_info';
				}

				if ( $ga4_event ) {
					if ( get_option( 'ec_option_google_ga4_tag_manager' ) ) {
						echo '<script>
						jQuery( document ).ready( function() {
							dataLayer.push( { ecommerce: null } );
							dataLayer.push( {
								event: "' . $ga4_event . '",
								ecommerce: {
									currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
									value: ' . esc_attr( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ',
									coupon_code: "' . esc_attr( $this->coupon_code ) . '",';
						if ( 'add_shipping_info' == $ga4_event ) {
							echo '
									shipping_tier: "' . esc_attr( trim( strip_tags( $this->shipping->get_selected_shipping_method() ) ) ) . '",';
						}
						echo '
									items: [ ';
									for( $i=0; $i < count( $this->cart->cart ); $i++ ) {
										echo '{
											item_id: "' . esc_attr( $this->cart->cart[$i]->model_number ) . '",
											item_name: "' . esc_attr( $this->cart->cart[$i]->title ) . '",
											index: ' . $i . ',
											price: ' . esc_attr( number_format( $this->cart->cart[$i]->unit_price, 2, '.', '' ) ) . ',
											item_brand: "' . esc_attr( $this->cart->cart[$i]->manufacturer_name ) . '",
											quantity: ' . esc_attr( number_format( $this->cart->cart[$i]->quantity, 2, '.', '' ) ) . '
										}, ';
									}
									echo ' ]
								}
							} );
						} );
						</script>';
					} else {
						echo '<script>
						jQuery( document ).ready( function() {
							gtag( "event", "' . $ga4_event . '", {
								currency: "' . esc_attr( $GLOBALS['currency']->get_currency_code( ) ) . '",
								value: ' . esc_attr( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . ',
								coupon_code: "' . esc_attr( $this->coupon_code ) . '",';
						if ( 'add_shipping_info' == $ga4_event ) {
							echo '
								shipping_tier: "' . esc_attr( trim( strip_tags( $this->shipping->get_selected_shipping_method() ) ) ) . '",';
						}
						echo '
								items: [ ';
								for( $i=0; $i < count( $this->cart->cart ); $i++ ) {
									echo '{
										item_id: "' . esc_attr( $this->cart->cart[$i]->model_number ) . '",
										item_name: "' . esc_attr( $this->cart->cart[$i]->title ) . '",
										index: ' . $i . ',
										price: ' . esc_attr( number_format( $this->cart->cart[$i]->unit_price, 2, '.', '' ) ) . ',
										item_brand: "' . esc_attr( $this->cart->cart[$i]->manufacturer_name ) . '",
										quantity: ' . esc_attr( number_format( $this->cart->cart[$i]->quantity, 2, '.', '' ) ) . '
									}, ';
								}
								echo ' ]
							} );
						} );
						</script>';
					}
				}
			}
		}

		if ( $cart_count == 0 && (int) substr( $cart_page, 0, 1 ) < 5 ) {
			$this->display_cart_top( 1, $success_code, $error_code );
			$this->display_cart_page();

		} else if ( $cart_count > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
			$this->display_cart_page();

		} else if ( preg_match( '/4\-paypal\-(PAYID-[a-zA-Z0-9]+)\-([a-zA-Z0-9]+)/', $cart_page, $matches ) ) {
			$pid = $matches[1];
			$pyid = $matches[2];
			$this->display_payment_paypal_express( $pid, $pyid );

		} else if ( preg_match( '/4\-paypal\-([a-zA-Z0-9]+)\-([a-zA-Z0-9]+)/', $cart_page, $matches ) ) {
			$oid = $matches[1];
			$pyid = $matches[2];
			$this->display_payment_paypal_express( false, $pyid, $oid );

		} else if ( preg_match( '/4\-ideal\-([a-zA-Z0-9\_]+)\-([a-zA-Z0-9\_]+)/', $cart_page, $matches ) ) {
			$ideal_source = $matches[1];
			$ideal_client_secret = $matches[2];
			$this->display_cart_top( 3, $success_code, $error_code );
			$this->display_payment( $ideal_source, $ideal_client_secret );

		} else if ( $cart_page == 1 ) {
			$this->display_cart_top( 1, $success_code, $error_code );
			$this->display_cart( '' );

		} else if ( $cart_page == 2 ) {
			$this->display_cart_top( 2, $success_code, $error_code );
			$this->display_checkout_details();

		} else if ( $cart_page == 3 ) {
			$this->display_cart_top( 2, $success_code, $error_code );
			$this->display_shipping_method();

		} else if ( $cart_page == 4 ) {
			$this->display_cart_top( 3, $success_code, $error_code );
			$this->display_payment();

		} else if ( preg_match( '/5\-sub\-([0-9]+)/', $cart_page, $matches ) ) {
			$this->display_cart_error( $error_code );
			$this->display_subscription_page( $matches[1] );

		} else if ( substr( $cart_page, 0, 1 ) == 6 ) {
			$this->display_cart_success_page( substr( $cart_page, 2, strlen( $cart_page ) - 1  ), $success_code, $error_code );

		}
	}

	public function display_cart_top( $page_num, $success_code, $error_code ) {
		if ( apply_filters( 'wp_easycart_onepage_checkout', false ) ) {
			
		} else {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_dynamic_top.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_dynamic_top.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_dynamic_top.php' );
			}
		}
	}

	public function load_cart_total_lines() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_totals.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_totals.php' );
		} else {
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_totals.php' );
		}
	}

	public function display_cart( $empty_cart_string ) {
		if ( apply_filters( 'wp_easycart_onepage_checkout', false ) ) {
			$current_screen = 'cart';
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_checkout_v2.php' );
			}
		} else {
			if ( $this->cart->total_items > 0 ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart.php' );
				}

				echo '<input type="hidden" name="ec_cart_page" id="ec_cart_page" value="' . esc_attr( $this->cart_page ) . '" />';
				echo '<input type="hidden" name="ec_cart_base_path" id="ec_cart_base_path" value="' . esc_attr( plugins_url() ) . '" />';
			} else {
				echo esc_attr( $empty_cart_string );
			}
		}
	}

	public function display_login() {
		if ( $this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_login.php' );
			}
		}
	}

	public function display_login_complete() {
		if ( $this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login_complete.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login_complete.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_login_complete.php' );
			}
		}
	}

	public function display_subscription_login_complete() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login_complete.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_login_complete.php' );
		} else if ( file_exists( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_login_complete.php' ) ) {
			include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_login_complete.php' );
		}
	}

	public function page_allowed( $page ) {
		$shipping = $payment = true;
		if ( get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ) {
			$shipping_address = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 : '';
			$shipping_address .= ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ) ? ' ' . $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 : '';
			$shipping_city = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_city ) ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_city : '';
			$shipping_state = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_state ) ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_state : '';
			$shipping_zip = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_zip : '';
			$shipping_country = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_country ) ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_country : '';

			$is_shippable = ( $this->cart->shippable_total_items > 0 ) ? true : false;
			$has_shipping_rate = ( $this->shipping->has_shipping_option() );

			if ( get_option( 'ec_option_onepage_checkout_tabbed' ) && '' == $GLOBALS['ec_cart_data']->cart_data->email ) {
				$shipping = false;
			} else if ( '0' == $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
				$shipping = false;
			} else if ( '' == $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ) {
				$shipping = false;
			} else if ( '' == $GLOBALS['ec_cart_data']->cart_data->shipping_city ) {
				$shipping = false;
			} else if ( $is_shippable && ! $this->shipping->validate_address( $shipping_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country ) ) {
				$shipping= false;
			} else if ( ! $this->validate_vat_registration_number( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) ) {
				$shipping = false;
			}
			if ( ! $shipping || ! $this->validate_cart_shipping() || ! $has_shipping_rate ) {
				$payment = false;
			}
		} else if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
			$billing_address = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 : '';
			$billing_address .= ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ) ? ' ' . $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 : '';
			$billing_city = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_city ) ) ? $GLOBALS['ec_cart_data']->cart_data->billing_city : '';
			$billing_state = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_state ) ) ? $GLOBALS['ec_cart_data']->cart_data->billing_state : '';
			$billing_zip = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_zip ) ) ? $GLOBALS['ec_cart_data']->cart_data->billing_zip : '';
			$billing_country = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_country ) ) ? $GLOBALS['ec_cart_data']->cart_data->billing_country : '';

			if ( '' == $GLOBALS['ec_cart_data']->cart_data->email ) {
				$shipping = false;
			} else if ( '0' == $GLOBALS['ec_cart_data']->cart_data->billing_country ) {
				$payment = false;
			} else if ( '' == $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) {
				$payment = false;
			} else if ( '' == $GLOBALS['ec_cart_data']->cart_data->billing_city ) {
				$payment = false;
			} else if ( ! $this->validate_vat_registration_number( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) ) {
				$payment = false;
			}
		}

		if ( 'shipping' == $page ) {
			return $shipping;
		} else if ( 'payment' == $page ) {
			return $payment;
		} else {
			return true;
		}
	}

	public function should_display_cart() {
		// Check minimum order amount
		if ( (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
			return true;
		} else if ( apply_filters( 'wpeasycart_restrict_cart_only', false ) ) {
			return true;
		}

		if ( !$this->should_display_login() )
			return true;
		else
			return false;
	}

	public function should_display_login() {
		return ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_login" && ( $GLOBALS['ec_cart_data']->cart_data->email == "" || $GLOBALS['ec_cart_data']->cart_data->is_guest == "" || $GLOBALS['ec_cart_data']->cart_data->is_guest ) );
	}

	public function payment_processor_requires_billing() {
		if ( get_option( 'ec_option_payment_process_method' ) == "skrill" ) {
			return false;	
		}
	}

	public function should_hide_shipping_panel() {
		return ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "" || ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "false" ) );
	}

	public function should_display_page_one() {
		// Check minimum order amount
		if ( (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
			return false;
		} else if ( apply_filters( 'wpeasycart_restrict_cart_only', false ) ) {
			return false;
		}

		return ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_info" );
	}

	public function display_page_one_form_start() {
		$next_page = "checkout_shipping";
		if ( !get_option( 'ec_option_use_shipping' ) || $this->order_totals->shipping_total <= 0 )
			$next_page = "checkout_payment";

		echo "<form action=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=" . esc_attr( $next_page ) . "\" method=\"POST\" id=\"wpeasycart_checkout_details_form\"";
		do_action( 'wp_easycart_checkout_form_inner' );
		echo ">";
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"save_checkout_info\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-checkout-info-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
	}

	public function display_page_one_form_end() {
		echo "</form>";
	}

	public function should_display_page_two() {
		// Check minimum order amount
		if ( (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
			return false;
		}

		return ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_shipping" && $GLOBALS['ec_cart_data']->cart_data->email != "" );
	}

	public function display_page_two_form_start() {
		echo "<form action=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment\" method=\"post\" id=\"wpeasycart_payment_shipping_method_form\">";
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"save_checkout_shipping\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
	}

	public function display_page_two_form_end() {
		echo "</form>";
	}

	public function should_display_page_three() {
		// Check minimum order amount
		if ( (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
			return false;
		}

		return ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_payment" && $GLOBALS['ec_cart_data']->cart_data->email != "" );
	}

	public function display_page_three_form_start() {
		if ( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ) {
			echo "<form data-eway-encrypt-key=\"" . esc_attr( get_option( 'ec_option_eway_client_key' ) ) . "\" action=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_submit_order\" method=\"post\" id=\"ec_submit_order_form\">";
		} else {
			echo "<form action=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_submit_order\" method=\"post\" id=\"ec_submit_order_form\">";
		}
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"submit_order\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-submit-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
	}

	public function display_page_three_form_end() {
		echo "</form>";
	}

	public function display_subscription_form_start( $model_number ) {
		echo "<form action=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_submit_order\" id=\"ec_submit_order_form\" method=\"post\">";
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"insert_subscription\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-insert-subscription-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_model_number\" id=\"ec_cart_model_number\" value=\"" . esc_attr( $model_number ) . "\" />";
	}

	public function display_subscription_form_end() {
		echo "</form>";
	}

	/* START CART FUNCTIONS */
	public function is_cart_type_one() {
		return ( !isset( $_GET['ec_page'] ) || ( isset( $_GET['ec_page'] ) && $GLOBALS['ec_cart_data']->cart_data->email == "" ) );
	}

	public function is_cart_type_two() {
		return ( !isset( $_GET['ec_page'] ) || ( isset( $_GET['ec_page'] ) && $_GET['ec_page'] == "checkout_payment" ) || $GLOBALS['ec_cart_data']->cart_data->email == "" );
	}

	public function is_cart_type_three() {
		return ( ( $this->shipping->shipping_method == "live" ) && $this->cart->weight > 0 && ( !isset( $_GET['ec_page'] ) || $GLOBALS['ec_cart_data']->cart_data->email == "" ) );
	}

	public function display_total_items() {
		echo "<span id=\"ec_cart_total_items\">" . esc_attr( $this->cart->get_total_items() ) . "</span>";
	}

	public function display_cart_items() {
		$this->cart->display_cart_items( $this->tax->vat_enabled, $this->tax->vat_country_match );	
	}

	public function has_cart_total_promotion() {
		if ( $this->cart->cart_total_promotion )
			return true;
		else
			return false;
	}

	public function display_cart_total_promotion() {
		echo esc_attr( $this->cart->cart_total_promotion );
	}

	public function has_cart_shipping_promotion() {
		if ( $this->shipping->get_shipping_promotion_text() )
			return true;
		else
			return false;
	}

	public function display_cart_shipping_promotion() {
		echo esc_attr( $this->shipping->get_shipping_promotion_text() );
	}

	public function get_selected_country( $type = 'billing' ) {
		if ( 'billing' == $type ) {
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_country && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_country ) {
				$selected_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
			} else if ( 0 != $GLOBALS['ec_user']->billing->get_value( 'country2' ) ) {
				$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country2' );
			} else if ( 1 == count( $countries ) ) {
				$selected_country = $countries[0]->iso2_cnt;
			} else if ( get_option( 'ec_option_default_country' ) ) {
				$selected_country = get_option( 'ec_option_default_country' );
			} else {
				$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country2' );
			}
		} else {
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country && '0' != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
				$selected_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
			} else if ( 0 != $GLOBALS['ec_user']->shipping->get_value( 'country2' ) ) {
				$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country2' );
			} else if ( 1 == count( $countries ) ) {
				$selected_country = $countries[0]->iso2_cnt;
			} else if ( get_option( 'ec_option_default_country' ) ) {
				$selected_country = get_option( 'ec_option_default_country' );
			} else {
				$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country2' );
			}
		}
		return $selected_country;
	}

	public function display_shipping_costs_input( $label, $button_text, $label2 = 'Country:', $select_label = 'Select One' ) {

		if ( get_option( 'ec_option_estimate_shipping_country' ) ) {

			$countries = $GLOBALS['ec_countries']->countries;

			if ( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country != "" )
				$selected_country = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country;
			else if ( count( $countries ) == 1 )
				$selected_country = $countries[0]->iso2_cnt;
			else if ( get_option( 'ec_option_default_country' ) )
				$selected_country = get_option( 'ec_option_default_country' );
			else
				$selected_country = "0";

			echo "<div class=\"ec_estimate_shipping_country\"><span>" . esc_attr( $label2 ) . "</span><select name=\"ec_cart_country\" id=\"ec_cart_country\" class=\"no_wrap\">";
			echo "<option value=\"0\"";
			if ( $selected_country == "0" )
				echo " selected=\"selected\"";
			echo ">" . esc_attr( $select_label ) . "</option>";
			foreach( $countries as $country ) {
				echo "<option value=\"" . esc_attr( $country->iso2_cnt ) . "\"";
				if ( $country->iso2_cnt == $selected_country )
					echo " selected=\"selected\"";
				echo ">" . esc_attr( $country->name_cnt ) . "</option>";
			}
			echo "</select></div>";
		} else {
			echo "<input type=\"hidden\" name=\"ec_cart_country\" id=\"ec_cart_country\" value=\"0\" />";
		}
		echo "<div class=\"ec_estimate_shipping_zip\"><span>" . esc_attr( $label ) . "</span><input type=\"text\" name=\"ec_cart_zip_code\" id=\"ec_cart_zip_code\" value=\"";
		if ( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip != "" )
			echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip );
		echo "\" /><a href=\"#\" onclick=\"return ec_estimate_shipping_click();\">" . esc_attr( $button_text ) . "</a></div>";
	}

	public function display_estimate_shipping_country_select() {

		$countries = $GLOBALS['ec_countries']->countries;

		if ( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country != "" )
			$selected_country = $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country;
		else if ( count( $countries ) == 1 )
			$selected_country = $countries[0]->iso2_cnt;
		else if ( get_option( 'ec_option_default_country' ) )
			$selected_country = get_option( 'ec_option_default_country' );
		else
			$selected_country = "0";

		echo "<select name=\"ec_estimate_country\" id=\"ec_estimate_country\" class=\"no_wrap\">";
		echo "<option value=\"0\""; if ( $selected_country == "0" ) { echo " selected=\"selected\""; } echo ">" . wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_select_one' ) . "</option>";
		foreach( $countries as $country ) {
			echo "<option value=\"" . esc_attr( $country->iso2_cnt ) . "\"";
			if ( $country->iso2_cnt == $selected_country )
				echo " selected=\"selected\"";
			echo ">" . esc_attr( $country->name_cnt ) . "</option>";
		}
		echo "</select>";
	}

	public function display_shipping_costs_input_text( $label ) {
		echo "<span>" . esc_attr( $label ) . "</span><input type=\"text\" name=\"ec_cart_zip_code\" id=\"ec_cart_zip_code\" value=\"";
		if ( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip != "" )
			echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip );
		echo "\" />";
	}

	public function display_shipping_costs_input_button( $button_text ) {
		echo "<a href=\"#\" onclick=\"return ec_estimate_shipping_click();\">" . esc_attr( $button_text ) . "</a>";
	}

	public function display_estimate_shipping_loader() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif" ) )	
			echo "<div class=\"ec_estimate_shipping_loader\" id=\"ec_estimate_shipping_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" /></div>";	
		else
			echo "<div class=\"ec_estimate_shipping_loader\" id=\"ec_estimate_shipping_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DIRECTORY ) ) . "\" /></div>";
	}

	public function display_subtotal() {
		echo "<span id=\"ec_cart_subtotal\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->get_converted_sub_total(), false ) ) . "</span>";	
	}

	public function get_subtotal() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->get_converted_sub_total(), false );
	}

	public function display_tax_total() {
		echo "<span id=\"ec_cart_tax\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->tax_total ) ) . "</span>";	
	}

	public function get_tax_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->tax_total );	
	}

	public function has_duty() {
		if ( $this->tax->duty_total > 0 )			return true;
		else										return false;	
	}

	public function display_duty_total() {
		echo "<span id=\"ec_cart_duty\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->duty_total ) ) . "</span>";	
	}

	public function get_duty_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->duty_total );	
	}

	public function get_vat_total() {
		return $this->tax->vat_total;
	}

	public function get_vat_total_formatted() {
		return $GLOBALS['currency']->get_currency_display( $this->tax->vat_total );
	}

	public function get_vat_rate_formatted( $vat_rate = false ) {
		if ( ! $vat_rate ) {
			$vat_rate = $this->tax->vat_rate;
		}
		$vat_rate_formatted = $vat_rate;
		if ( round( $vat_rate_formatted, 0 ) == $vat_rate ) {
			$vat_rate_formatted = number_format( round( $vat_rate_formatted, 0 ), 0, '', '' );

		} else if ( round( $vat_rate_formatted, 1 ) == $vat_rate ) {
			$vat_rate_formatted = number_format( $vat_rate_formatted, 1, '.', '' );

		} else if ( round( $vat_rate_formatted, 2 ) == $vat_rate ) {
			$vat_rate_formatted = number_format( $vat_rate_formatted, 2, '.', '' );

		} else if ( round( $vat_rate_formatted, 3 ) == $vat_rate ) {
			$vat_rate_formatted = number_format( $vat_rate_formatted, 3, '.', '' );

		}
		return apply_filters( 'wpeasycart_format_vat_rate', '(' . $vat_rate_formatted . '%)', $vat_rate );
	}

	public function display_vat_total() {
		echo "<span id=\"ec_cart_vat\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->vat_total ) ) . "</span>";	
	}

	public function display_shipping_total() {
		echo "<span id=\"ec_cart_shipping\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->shipping_total ) ) . "</span>";
	}

	public function get_shipping_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->shipping_total );
	}

	public function display_discount_total() {
		echo "<span id=\"ec_cart_discount\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->discount_total ) ) . "</span>";
	}

	public function get_discount_total() {
		return $GLOBALS['currency']->get_currency_display( (-1) * $this->order_totals->discount_total );
	}

	public function get_gst_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->gst_total );	
	}

	public function get_pst_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->pst_total );	
	}

	public function get_hst_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->hst_total );	
	}

	public function get_tip_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->tip_total );	
	}

	public function display_grand_total() {
		echo "<span id=\"ec_cart_grandtotal\">" . esc_attr( $GLOBALS['currency']->get_currency_display( $this->order_totals->get_converted_grand_total(), false ) ) . "</span>"; 	
	}

	public function get_grand_total() {
		return $GLOBALS['currency']->get_currency_display( $this->order_totals->get_converted_grand_total(), false ); 	
	}

	public function display_continue_shopping_button( $button_text ) {
		echo "<a href=\"" . esc_attr( $this->store_page );

		echo "\" class=\"ec_cart_continue_shopping_link\">" . esc_attr( $button_text ) . "</a>";
	}

	public function display_checkout_button( $button_text ) {
		$checkout_page = "checkout_login";
		if ( $GLOBALS['ec_cart_data']->cart_data->email != "" ) {
			$checkout_page = "checkout_info";

		} else if ( get_option( 'ec_option_skip_cart_login' ) ) {
			$checkout_page = "checkout_info";
			$GLOBALS['ec_cart_data']->cart_data->email = "guest";
			$GLOBALS['ec_cart_data']->cart_data->username = "guest";
		}
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/admin_panel.php" ) )
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=" . esc_attr( $checkout_page ) . "\" class=\"ec_cart_checkout_link\">" . esc_attr( $button_text ) . "</a>";
		else
			echo "<a href=\"" . esc_attr( $this->cart_page ) . "\" class=\"ec_cart_checkout_link\">" . esc_attr( $button_text ) . "</a>";
	}
	/* END CART FUNCTIONS */

	// Forward the page to the cart page minus form submission with success note
	private function forward_cart_success() {

	}

	// Forward the page to the last product page, plus a failed note
	private function forward_product_failed() {

	}

	/* Login Form Functions */
	public function display_cart_login_form_start() {
		echo "<form action=\"". esc_attr( $this->cart_page ) . "\" method=\"post\">";	
	}

	public function display_cart_login_form_start_subscription() {
		echo "<form action=\"". esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=subscription_info&subscription=" . esc_attr( sanitize_text_field( $_GET['subscription'] ) ) . "\" method=\"post\">";
	}

	public function display_cart_login_form_end() {
		if ( isset( $_GET['subscription'] ) ) {
			echo "<input type=\"hidden\" name=\"ec_cart_subscription\" value=\"" . esc_attr( sanitize_text_field( $_GET['subscription'] ) ) . "\" />";
		}
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"login_user\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-login-user-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
		echo "</form>";
	}

	public function display_cart_login_form_guest_start() {
		echo "<form action=\"". esc_attr( $this->cart_page ) . "\" method=\"post\">";
	}

	public function display_cart_login_form_guest_end() {
		if ( isset( $_GET['subscription'] ) ) {
			echo "<input type=\"hidden\" name=\"ec_cart_subscription\" value=\"" . esc_attr( sanitize_text_field( $_GET['subscription'] ) ) . "\" />";
		}
		echo "<input type=\"hidden\" name=\"ec_cart_form_action\" value=\"login_user\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_form_nonce\" value=\"" . esc_attr( wp_create_nonce( 'wp-easycart-cart-login-user-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_login_email\" value=\"guest\" />";
		echo "<input type=\"hidden\" name=\"ec_cart_login_password\" value=\"guest\" />";
		echo "</form>";
	}

	public function display_cart_login_email_input() {
		echo "<input type=\"email\" id=\"ec_cart_login_email\" name=\"ec_cart_login_email\" class=\"ec_cart_login_input\" autocorrect=\"off\" autocapitalize=\"off\" />";
	}

	public function display_cart_login_password_input() {
		echo "<input type=\"password\" id=\"ec_cart_login_password\" name=\"ec_cart_login_password\" class=\"ec_cart_login_input\" />";
	}

	public function display_cart_login_login_button( $input ) {
		echo "<input type=\"submit\" id=\"ec_cart_login_login_button\" name=\"ec_cart_login_login_button\" class=\"ec_cart_login_button\" value=\"" . esc_attr( $input ) . "\" />";
	}

	public function display_cart_login_forgot_password_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=forgot_password\" class=\"ec_cart_login_complete_logout_link\">" . esc_attr( $link_text ) . "</a>";
	}

	public function display_cart_login_guest_button( $input ) {
		echo "<input type=\"submit\" id=\"ec_cart_login_guest_button\" name=\"ec_cart_login_guest_button\" class=\"ec_cart_login_button\" value=\"" . esc_attr( $input ) . "\" />";
	}

	public function display_cart_login_complete_user_name( $input ) {
		echo "<input type=\"hidden\" id=\"ec_cart_login_guest_text\" value=\"" . esc_attr( $input ) . "\" /><span id=\"ec_cart_login_complete_username\">";
		if ( $GLOBALS['ec_cart_data']->cart_data->username != "guest" )			
			echo esc_attr( $GLOBALS['ec_cart_data']->cart_data->username );
		else
			echo esc_attr( $input );
		echo "</span>";
	}

	public function display_cart_login_complete_signout_link( $input ) {
		if ( isset( $_GET['subscription'] ) ) {
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_cart_action=logout&subscription=" . esc_attr( sanitize_text_field( $_GET['subscription'] ) ) . "\" class=\"ec_cart_login_complete_logout_link\">" . esc_attr( $input ) . "</a>";
		} else {
			echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_cart_action=logout\" class=\"ec_cart_login_complete_logout_link\">" . esc_attr( $input ) . "</a>";
		}
	}

	/* END LOGIN/LOGOUT FUNCTIONS */

	/* START BILLING FUNCTIONS */
	public function display_checkout_details() {
		do_action( 'wp_easycart_display_checkout_details_pre' );
		if ( apply_filters( 'wp_easycart_onepage_checkout', false ) ) {
			$current_screen = 'information';
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_checkout_v2.php' );
			}
		} else {
			if ( $this->cart->total_items > 0 && apply_filters( 'wp_easycart_allow_checkout_details', 1 ) ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_details.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_details.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_checkout_details.php' );
				}
			}
		}
		do_action( 'wp_easycart_display_checkout_details_post' );
	}

	public function display_billing() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_billing.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_billing.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_billing.php' );
		}
	}

	public function display_billing_input( $name ) {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		if ( 'country' == $name ) {
			if ( get_option( 'ec_option_use_country_dropdown' ) || 'square' == get_option( 'ec_option_payment_process_method' ) || 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) || 'intuit' == get_option( 'ec_option_payment_process_method' ) || 'live' == $GLOBALS['ec_setting']->get_shipping_method() ) {
				$countries = $GLOBALS['ec_countries']->countries;
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_country && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_country ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
				} else if ( 0 != $GLOBALS['ec_user']->billing->get_value( 'country2' ) ) {
					$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country2' );
				} else if ( 1 == count( $countries ) ) {
					$selected_country = $countries[0]->iso2_cnt;
				} else if ( get_option( 'ec_option_default_country' ) ) {
					$selected_country = get_option( 'ec_option_default_country' );
				} else {
					$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country2' );
				}

				echo '<select name="ec_cart_billing_country" id="ec_cart_billing_country" class="ec_cart_billing_input_text no_wrap' . esc_attr( $auto_validate_css ) . '" onchange="wpeasycart_cart_billing_country_update();">';
				echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_country' ) . '</option>';
				foreach ( $countries as $country) {
					echo '<option value="' . esc_attr( $country->iso2_cnt ) . '"';
					if ( $country->iso2_cnt == $selected_country ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_attr( $country->name_cnt ) . '</option>';
				}
				echo '</select>';

			} else {
				if ( $GLOBALS['ec_cart_data']->cart_data->billing_country && '' != $GLOBALS['ec_cart_data']->cart_data->billing_country && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_country  ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
				} else {
					$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country' );
				}
				echo '<input type="text" name="ec_cart_billing_country" id="ec_cart_billing_country" class="ec_cart_billing_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_country, ENT_QUOTES ) ) . '" />';
			}

		} else if ( 'state' == $name ) {
			if ( get_option( 'ec_option_use_country_dropdown' ) || 'square' == get_option( 'ec_option_payment_process_method' ) || 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) || 'intuit' == get_option( 'ec_option_payment_process_method' ) || 'live' == $GLOBALS['ec_setting']->get_shipping_method() ) {
				$states = $this->mysqli->get_states();
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_state && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_state ) {
					$selected_state = $GLOBALS['ec_cart_data']->cart_data->billing_state;
				} else {
					$selected_state = $GLOBALS['ec_user']->billing->get_value( 'state' );
				}
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_country && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_country ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->billing_country;
				} else {
					$selected_country = $GLOBALS['ec_user']->billing->get_value( 'country2' );
				}
				$current_country = '';
				$close_last_state = false;
				$state_found = false;
				$current_state_group = '';
				$close_last_state_group = false;

				foreach ( $states as $state ) {
					if ( isset( $state->iso2_cnt ) ) {
						if ( $current_country != $state->iso2_cnt ) {
							if ( $close_last_state ) {
								echo "</select>";
							}
							echo '<select name="ec_cart_billing_state_' . esc_attr( $state->iso2_cnt ) . '" id="ec_cart_billing_state_' . esc_attr( $state->iso2_cnt ) . '" class="ec_cart_billing_input_text ec_billing_state_dropdown no_wrap' . esc_attr( $auto_validate_css ) . '"';
							if ( $state->iso2_cnt != $selected_country ) {
								echo ' style="display:none;"';
							} else {
								$state_found = true;
							}
							echo '>';

							if ( 'CA' == $state->iso2_cnt ) {
								echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_province' ) . '</option>';
							} else if ( 'GB' == $state->iso2_cnt ) {
								echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_county' ) . '</option>';
							} else if ( 'US' == $state->iso2_cnt ) {
								echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_state' ) . '</option>';
							} else {
								echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_other' ) . '</option>';
							}

							$current_country = $state->iso2_cnt;
							$close_last_state = true;
						}

						if ( $current_state_group != $state->group_sta && '' != $state->group_sta ) {
							if ( $close_last_state_group ) {
								echo '</optgroup>';
							}
							echo '<optgroup label="' . esc_attr( $state->group_sta ) . '">';
							$current_state_group = $state->group_sta;
							$close_last_state_group = true;
						}

						echo '<option value="' . esc_attr( $state->code_sta ) . '"';
						if ( $state->code_sta == $selected_state ) {
							echo ' selected="selected"';
						}
						echo '>' . esc_attr( $state->name_sta ) . '</option>';
					}
				}

				if ( $close_last_state_group ) {
					echo '</optgroup>';
				}

				echo '</select>';

				echo '<input type="text" name="ec_cart_billing_state" id="ec_cart_billing_state" class="ec_cart_billing_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . '"';
				if ( $state_found ) {
					echo ' style="display:none;"';
				}
				echo ' />';

			} else {
				if ( get_option( 'ec_option_use_state_dropdown' ) ) {
					$states = $this->mysqli->get_states();
					if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_state && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_state ) {
						$selected_state = $GLOBALS['ec_cart_data']->cart_data->billing_state;
					} else {
						$selected_state = $GLOBALS['ec_user']->billing->get_value( 'state' );
					}
					echo '<select name="ec_cart_billing_state" id="ec_cart_billing_state" class="ec_cart_billing_input_text no_wrap' . esc_attr( $auto_validate_css ) . '">';
					echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_billing_information', 'cart_billing_information_select_state' ) . '</option>';
					foreach ( $states as $state ) {
						echo '<option value="' . esc_attr( $state->code_sta ) . '"';
						if ( $state->code_sta == $selected_state ) {
							echo ' selected="selected"';
						}
						echo '>' . esc_attr( $state->name_sta ) . '</option>';
					}
					echo '</select>';
				} else {
					if ( '' != $GLOBALS['ec_cart_data']->cart_data->billing_state && '0' != $GLOBALS['ec_cart_data']->cart_data->billing_state ) {
						$selected_state = $GLOBALS['ec_cart_data']->cart_data->billing_state;
					} else {
						$selected_state = $GLOBALS['ec_user']->billing->get_value( 'state' );
					}
					echo '<input type="text" name="ec_cart_billing_state" id="ec_cart_billing_state" class="ec_cart_billing_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . '" />';
				}
			}

		} else {
			if ( 'first_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_first_name : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'last_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_last_name : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'company_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->billing_company_name : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'address' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'address2' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'city' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_city ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_city ) ? $GLOBALS['ec_cart_data']->cart_data->billing_city : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'zip' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_zip ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_zip ) ? $GLOBALS['ec_cart_data']->cart_data->billing_zip : $GLOBALS['ec_user']->billing->get_value( $name );
			} else if ( 'phone' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->billing_phone ) && '' != $GLOBALS['ec_cart_data']->cart_data->billing_phone ) ? $GLOBALS['ec_cart_data']->cart_data->billing_phone : $GLOBALS['ec_user']->billing->get_value( $name );
			}
			echo '<input type="text" name="ec_cart_billing_' . esc_attr( $name ) . '" id="ec_cart_billing_' . esc_attr( $name ) . '" class="ec_cart_billing_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $value, ENT_QUOTES ) ) . '" />';
		}
	}

	public function display_vat_registration_number_input() {
		if ( isset( $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) && '' != $GLOBALS['ec_cart_data']->cart_data->vat_registration_number ) {
			$value = $GLOBALS['ec_cart_data']->cart_data->vat_registration_number;
		} else {
			$value = $GLOBALS['ec_user']->vat_registration_number;
		}
		echo '<input type="text" name="ec_cart_billing_' . esc_attr( 'vat_registration_number' ) . '" id="ec_cart_billing_' . esc_attr( 'vat_registration_number' ) . '" class="ec_cart_billing_input_text" value="' . esc_attr( htmlspecialchars( $value, ENT_QUOTES ) ) . '" />';
	}
	/* END BILLING FUNCTIONS */

	/* START SHIPPING FUNCTIONS */
	public function display_shipping() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping.php' );
		}
	}

	public function display_shipping_selector( $first_opt, $second_opt ) {
		if ( $this->cart->shipping_subtotal > 0 )
			echo "<div class=\"ec_cart_shipping_selector_row\">";
		else
			echo "<div class=\"ec_cart_shipping_selector_row_hidden\">";

		echo "<input type=\"radio\" name=\"ec_shipping_selector\" id=\"ec_cart_use_billing_for_shipping\" value=\"false\"";
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "" || ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "false" ) )
		echo " checked=\"checked\"";
		echo " onchange=\"ec_cart_use_billing_for_shipping_change(); return false;\" />" . esc_attr( $first_opt );
		echo "</div>";

		if ( get_option( 'ec_option_use_shipping' ) ) {
			if ( $this->cart->shipping_subtotal > 0 )
				echo "<div class=\"ec_cart_shipping_selector_row\">";
			else
				echo "<div class=\"ec_cart_shipping_selector_row_hidden\">";

			echo "<input type=\"radio\" name=\"ec_shipping_selector\" id=\"ec_cart_use_shipping_for_shipping\" value=\"true\"";
			if ( $GLOBALS['ec_cart_data']->cart_data->shipping_selector != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_selector == "true" )
			echo " checked=\"checked\"";
			echo " onchange=\"ec_cart_use_shipping_for_shipping_change(); return false;\" />" . esc_attr( $second_opt );
			echo "</div>";
		} else {
			echo "<script>jQuery('.ec_cart_shipping_selector_row').hide();</script>";	
		}
	}

	public function display_shipping_input( $name ) {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		if ( 'country' == $name ) {
			if ( get_option( 'ec_option_use_country_dropdown' ) || 'square' == get_option( 'ec_option_payment_process_method' ) || 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) || 'intuit' == get_option( 'ec_option_payment_process_method' ) || 'live' == $GLOBALS['ec_setting']->get_shipping_method() ) {
				$countries = $GLOBALS['ec_countries']->countries;
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country && '0' != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
				} else if ( 0 != $GLOBALS['ec_user']->shipping->get_value( 'country2' ) ) {
					$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country2' );
				} else if ( 1 == count( $countries ) ) {
					$selected_country = $countries[0]->iso2_cnt;
				} else if ( get_option( 'ec_option_default_country' ) ) {
					$selected_country = get_option( 'ec_option_default_country' );
				} else {
					$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country2' );
				}
				echo '<select name="ec_cart_shipping_country" id="ec_cart_shipping_country" class="ec_cart_shipping_input_text no_wrap' . esc_attr( $auto_validate_css ) . '">';
				echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_country' ) . '</option>';
				foreach ( $countries as $country ) {
					echo '<option value="' . esc_attr( $country->iso2_cnt ) . '"';
					if ( $country->iso2_cnt == $selected_country ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_attr( $country->name_cnt ) . '</option>';
				}
				echo '</select>';

			} else {
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country && 0 != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
				} else {
					$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country' );
				}
				echo '<input type="text" name="ec_cart_shipping_country" id="ec_cart_shipping_country" class="ec_cart_shipping_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_country, ENT_QUOTES ) ) . '" />';
			}

		} else if ( 'state' == $name ) {
			if ( get_option( 'ec_option_use_country_dropdown' ) || 'square' == get_option( 'ec_option_payment_process_method' ) || 'stripe' == get_option( 'ec_option_payment_process_method' ) || 'stripe_connect' == get_option( 'ec_option_payment_process_method' ) || 'intuit' == get_option( 'ec_option_payment_process_method' ) || 'live' == $GLOBALS['ec_setting']->get_shipping_method() ) {
				$states = $this->mysqli->get_states();
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state && 0 != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) {
					$selected_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
				} else {
					$selected_state = $GLOBALS['ec_user']->shipping->get_value( 'state' );
				}
				if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_country && 0 != $GLOBALS['ec_cart_data']->cart_data->shipping_country ) {
					$selected_country = $GLOBALS['ec_cart_data']->cart_data->shipping_country;
				} else {
					$selected_country = $GLOBALS['ec_user']->shipping->get_value( 'country2' );
				}
				$current_country = '';
				$close_last_state = false;
				$state_found = false;
				$current_state_group = '';
				$close_last_state_group = false;

				foreach ( $states as $state ) {
					if ( $current_country != $state->iso2_cnt ) {
						if ( $close_last_state ) {
							echo '</select>';
						}
						echo '<select name="ec_cart_shipping_state_' . esc_attr( $state->iso2_cnt ) . '" id="ec_cart_shipping_state_' . esc_attr( $state->iso2_cnt ) . '" class="ec_cart_shipping_input_text ec_shipping_state_dropdown no_wrap' . esc_attr( $auto_validate_css ) . '"';
						if ( $state->iso2_cnt != $selected_country ) {
							echo ' style="display:none;"';
						} else {
							$state_found = true;
						}
						echo '>';

						if ( 'CA' == $state->iso2_cnt ) {
							echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_province' ) . '</option>';
						} else if ( 'GB' == $state->iso2_cnt ) {
							echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_county' ) . '</option>';
						} else if ( 'US' == $state->iso2_cnt ) {
							echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_state' ) . '</option>';
						} else {
							echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_other' ) . '</option>';
						}

						$current_country = $state->iso2_cnt;
						$close_last_state = true;
					}

					if ( $current_state_group != $state->group_sta && '' != $state->group_sta ) {
						if ( $close_last_state_group ) {
							echo '</optgroup>';
						}
						echo '<optgroup label="' . esc_attr( $state->group_sta ) . '">';
						$current_state_group = $state->group_sta;
						$close_last_state_group = true;
					}

					echo '<option value="' . esc_attr( $state->code_sta ) . '"';
					if ( $state->code_sta == $selected_state ) {
						echo ' selected="selected"';
					}
					echo '>' . esc_attr( $state->name_sta ) . '</option>';
				}

				if ( $close_last_state_group ) {
					echo "</optgroup>";
				}

				echo '</select>';

				echo '<input type="text" name="ec_cart_shipping_state" id="ec_cart_shipping_state" class="ec_cart_shipping_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . '"';
				if ( $state_found ) {
					echo ' style="display:none;"';
				}
				echo ' />';

			} else {
				if ( get_option( 'ec_option_use_state_dropdown' ) ) {
					$states = $this->mysqli->get_states();
					if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state && 0 != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) {
						$selected_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
					} else {
						$selected_state = $GLOBALS['ec_user']->shipping->get_value( 'state' );
					}
					echo '<select name="ec_cart_shipping_state" id="ec_cart_shipping_state" class="ec_cart_shipping_input_text no_wrap' . esc_attr( $auto_validate_css ) . '">';
					echo '<option value="0">' . wp_easycart_language()->get_text( 'cart_shipping_information', 'cart_shipping_information_select_state' ) . '</option>';
					foreach ( $states as $state ) {
						echo '<option value="' . esc_attr( $state->code_sta ) . '"';
						if ( $state->code_sta == $selected_state ) {
							echo ' selected="selected"';
						}
						echo '>' . esc_attr( $state->name_sta ) . '</option>';
					}
					echo '</select>';

				} else {
					if ( '' != $GLOBALS['ec_cart_data']->cart_data->shipping_state && 0 != $GLOBALS['ec_cart_data']->cart_data->shipping_state ) {
						$selected_state = $GLOBALS['ec_cart_data']->cart_data->shipping_state;
					} else {
						$selected_state = $GLOBALS['ec_user']->shipping->get_value( 'state' );
					}
					echo '<input type="text" name="ec_cart_shipping_state" id="ec_cart_shipping_state" class="ec_cart_shipping_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $selected_state, ENT_QUOTES ) ) . '" />';
				}
			}

		} else {
			if ( 'first_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_first_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_first_name : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'last_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_last_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_last_name : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'company_name' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_company_name ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_company_name : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'address' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'address2' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'city' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_city ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_city ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_city : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'zip' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_zip ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_zip : $GLOBALS['ec_user']->shipping->get_value( $name );
			} else if ( 'phone' == $name ) {
				$value = ( isset( $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) && '' != $GLOBALS['ec_cart_data']->cart_data->shipping_phone ) ? $GLOBALS['ec_cart_data']->cart_data->shipping_phone : $GLOBALS['ec_user']->shipping->get_value( $name );
			}
			echo '<input type="text" name="ec_cart_shipping_' . esc_attr( $name ) . '" id="ec_cart_shipping_' . esc_attr( $name ) . '" class="ec_cart_shipping_input_text' . esc_attr( $auto_validate_css ) . '" value="' . esc_attr( htmlspecialchars( $value, ENT_QUOTES ) ) . '" />';
		}
	}
	/* END SHIPPING FUNCTIONS */

	/* START SHIPPING METHOD FUNCTIONS */
	public function display_shipping_method() {
		do_action( 'wp_easycart_display_shipping_method_pre' );
		if ( apply_filters( 'wp_easycart_onepage_checkout', false ) ) {
			$current_screen = 'shipping';
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_checkout_v2.php' );
			}
		} else {
			if ( $this->cart->total_items > 0 && apply_filters( 'wp_easycart_allow_shipping_method', 1 ) ) {
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_method.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_shipping_method.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_shipping_method.php' );
				}
			}
		}
		do_action( 'wp_easycart_display_shipping_method_post' );
	}

	public function ec_cart_display_shipping_methods( $standard_text, $express_text, $ship_method ) {
		$this->shipping->print_shipping_options( $standard_text, $express_text );
	}

	public function ec_cart_display_shipping_methods_stripe_dynamic( $standard_text, $express_text ) {
		return $this->shipping->get_shipping_rate_data( $standard_text, $express_text );	
	}

	public function ec_cart_display_shipping_methods_square_dynamic( $standard_text, $express_text ) {
		return $this->shipping->get_shipping_rate_data( $standard_text, $express_text, 1 );	
	}
	
	public function get_stripe_express_cart_items() {
		$return_arr = array();
		for ( $i = 0; $i < count( $this->cart->cart ); $i++ ) {
			$return_arr[] = (object) array(
				'name' => esc_attr( $this->cart->cart[$i]->title ),
				'amount' => (int) esc_attr( number_format( $this->cart->cart[$i]->total_price * 100, 0, '', '' ) ),
			);
		}
		if ( $this->order_totals->tax_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tax' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->tax_total * 100, 0, '.', '' ) ),
			);
		}

		if ( $this->order_totals->tip_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tip' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->tip_total * 100, 0, '.', '' ) ),
			);
		}

		if ( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) && $this->order_totals->shipping_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_shipping' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->shipping_total * 100, 0, '.', '' ) ),
			);
		}

		if ( $this->order_totals->discount_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_discounts' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->discount_total * 100, 0, '.', '' ) ),
			);
		}

		if ( $this->tax->is_duty_enabled() && $this->order_totals->duty_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_duty' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->duty_total * 100, 0, '.', '' ) ),
			);
		}

		if ( $this->tax->is_vat_enabled() && $this->order_totals->vat_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_vat' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->vat_total * 100, 0, '.', '' ) ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->gst_total > 0  ) {
			$return_arr[] = (object) array(
				'name'     => 'GST' . ( ( $this->tax->gst_rate > 0 ) ? ' ' .$this->tax->gst_rate . '%' : '' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->gst_total * 100, 0, '.', '' ) ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->pst_total > 0  ) {
			$return_arr[] = (object) array(
				'name'     => 'PST' . ( ( $this->tax->pst_rate > 0 ) ? ' ' .$this->tax->pst_rate . '%' : '' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->pst_total * 100, 0, '.', '' ) ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->hst_total > 0 ) {
			$return_arr[] = (object) array(
				'name'     => 'HST' . ( ( $this->tax->hst_rate > 0 ) ? ' ' .$this->tax->hst_rate . '%' : '' ),
				'amount'    => (int) esc_attr( number_format( $this->order_totals->hst_total * 100, 0, '.', '' ) ),
			);
		}
		return $return_arr;
	}

	public function get_stripe_express_shipping_items( $standard_text, $express_text ) {
		$shipping_rates = $this->shipping->get_shipping_rate_data( $standard_text, $express_text );
		$rate_items = array();
		for ( $i = 0; $i < count( $shipping_rates ); $i++ ) {
			$rate_items[] = (object) array(
				'id' => (string) esc_attr( $shipping_rates[ $i ]->id ),
				'displayName' => (string) esc_attr( $shipping_rates[ $i ]->label ),
				'amount' => (int) esc_attr( number_format( $shipping_rates[ $i ]->amount, 0, '', '' ) ),
			);
		}
		return $rate_items;
	}

	public function get_stripe_intent_client_secret( $order_totals = false ) {
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' || get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' ) {
			if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
				$stripe = new ec_stripe();
			else
				$stripe = new ec_stripe_connect();

			if ( !$order_totals ) {
				$order_totals = $this->order_totals;
			}

			if ( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id == '' || $GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret == '' ) {
				$response = $stripe->create_payment_intent( $order_totals );
				if ( $response ) {
					$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = $response->id;
					$GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret = $response->client_secret;
					$GLOBALS['ec_cart_data']->save_session_to_db();
				}
				do_action( 'wpeasycart_cart_updated' );
			} else {
				$response = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );
				if ( ! $response || $response->status == 'succeeded' || $response->status == 'canceled' ) {
					$response = $stripe->create_payment_intent( $order_totals );
					if ( $response ) {
						$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = $response->id;
						$GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret = $response->client_secret;
						$GLOBALS['ec_cart_data']->save_session_to_db();
					}
					do_action( 'wpeasycart_cart_updated' );
				} else {
					return $GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret;
				}
			}

			if ( $response ) {
			  return $response->client_secret;
			}
		}

		return '';
	}

	public function get_stripe_intent_client_secret_subscription( $grand_total ) {
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();

		$order_totals = (object) array( 'grand_total' => $grand_total );

		if ( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id == '' ) {
			$response = $stripe->create_payment_intent( $order_totals );
			$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = $response->id;
			$GLOBALS['ec_cart_data']->save_session_to_db();
			do_action( 'wpeasycart_cart_updated' );
		} else {
			$response = $stripe->get_payment_intent( $GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id );
			if ( ! $response || $response->status == 'succeeded' || $response->status == 'canceled' ) {
				$response = $stripe->create_payment_intent( $order_totals );
				$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = $response->id;
				$GLOBALS['ec_cart_data']->save_session_to_db();
				do_action( 'wpeasycart_cart_updated' );
			}
		}

		return $response->client_secret;
	}

	public function print_square_payment_script( $is_payment = false ) {
		if( ! $is_payment && ! get_option( 'ec_option_square_digital_wallet' ) ) {
			return;
		}
	
		if ( get_option( 'ec_option_square_application_id' ) != '' ) { 
			$app_id = get_option( 'ec_option_square_application_id' );
		} else { 
			$app_id = ( get_option( 'ec_option_square_is_sandbox' ) ) ? 'sandbox-sq0idb-khAAob2bNi889KPQSVsF6Q' : 'sq0idp-H8Mnz1zzbv1mOyeWyKpF6Q';
		}
		$location_id = ( get_option( 'ec_option_square_is_sandbox' ) ) ? get_option( 'ec_option_square_sandbox_location_id' ) : get_option( 'ec_option_square_location_id' );
		if ( $location_id == '' ) {
			$square = new ec_square();
			$location_id = $square->get_location_id();
		}
		echo '<div id="square-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
			<style>
			@keyframes rotation{
				0%  { transform:rotate(0deg); }
				100%{ transform:rotate(359deg); }
			}
			</style>
			<div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'>
				<div class="paypal-checkout-loader">
					<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
				</div>
			</div>
		</div>';
		echo '<script type="text/javascript">
			jQuery( document.getElementById( "square-success-cover" ) ).appendTo( document.body );
			const wpEasyCartAppId = "' . esc_attr( $app_id ) . '";
			const wpEasyCartLocationId = "' . esc_attr( $location_id ) . '";';
		if( $is_payment ) {
		echo '
			async function wpEasyCartInitializeCard( payments ) {
				try {
					if ( jQuery( document.getElementById( "wp-easycart-square-card-container" ) ).length ) {
						const wpEasyCartCard = await payments.card();
						try {
							await wpEasyCartCard.attach( \'#wp-easycart-square-card-container\' );
							return wpEasyCartCard;
						} catch ( e ) {
							console.error( \'Square could not attach card\', e );
						}
					}
				} catch ( e ) {
					console.error( \'Square could not initialize card\', e );
				}
			}';
		}
		echo '
			let wpEasyCartSquareInit = async function () {
				if ( ! window.Square ) {
					console.log( \'Square.js failed to load properly\' );
				}
				const wpEasyCartSquarePayments = window.Square.payments( wpEasyCartAppId, wpEasyCartLocationId );';
		if ( $is_payment ) {
		echo '
				let wpEasyCartCard;
				try {
					wpEasyCartCard = await wpEasyCartInitializeCard( wpEasyCartSquarePayments );
				} catch ( e ) {
					console.error( \'Initializing Card failed\', e );
					return;
				}';
		}
		if ( get_option( 'ec_option_square_digital_wallet' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
		echo '
				let wpEasyCartApplePay;
				try {
					wpEasyCartApplePay = await wpEasyCartInitializeApplePay( wpEasyCartSquarePayments );
				} catch ( e ) {
					console.error( \'Initializing Apple Pay failed\', e );
				}
				let wpEasyCartGooglePay;
				try {
					wpEasyCartGooglePay = await wpEasyCartInitializeGooglePay( wpEasyCartSquarePayments );
				} catch ( e ) {
					console.error( \'Initializing Google Pay failed\', e );
				}';
		}
		if ( get_option( 'ec_option_square_gift_cards' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
		echo '
				let wpEasyCartGiftCard;
				try {
					wpEasyCartGiftCard = await wpEasyCartInitializeGiftCard( wpEasyCartSquarePayments );
				} catch ( e ) {
					console.error( \'Initializing Gift Card failed\', e );
					return;
				}';
		}
		echo '
				async function wpEasyCartCreatePayment( tokenResult, verificationToken ) {
					var body = new FormData()
					body.append( \'action\', \'ec_ajax_square_complete_payment\' );
					body.append( \'sourceId\', tokenResult.token );';
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
					if ( "Card" != tokenResult.details.method ) {';
		}
		if ( ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
		echo '
					body.append( \'shipping_address_line_1\', tokenResult.details.shipping.contact.addressLines[0] );
					body.append( \'shipping_address_line_2\', tokenResult.details.shipping.contact.addressLines[1] );
					body.append( \'shipping_address_city\', tokenResult.details.shipping.contact.city );
					body.append( \'shipping_address_state\', tokenResult.details.shipping.contact.state );
					body.append( \'shipping_address_region\', tokenResult.details.shipping.contact.region );
					body.append( \'shipping_address_dependentLocality\', tokenResult.details.shipping.contact.dependentLocality );
					body.append( \'shipping_address_zip\', tokenResult.details.shipping.contact.postalCode );
					body.append( \'shipping_address_country\', tokenResult.details.shipping.contact.countryCode );
					body.append( \'shipping_address_phone\', tokenResult.details.shipping.contact.phone );
					body.append( \'shipping_address_email\', tokenResult.details.shipping.contact.email );
					body.append( \'shipping_address_first_name\', tokenResult.details.shipping.contact.givenName );
					body.append( \'shipping_address_last_name\', tokenResult.details.shipping.contact.familyName );';
		}
		if ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
					body.append( \'billing_address_line_1\', tokenResult.details.billing.addressLines[0] );
					body.append( \'billing_address_line_2\', tokenResult.details.billing.addressLines[1] );
					body.append( \'billing_address_city\', tokenResult.details.billing.city );
					body.append( \'billing_address_state\', tokenResult.details.billing.state );
					body.append( \'billing_address_region\', tokenResult.details.billing.region );
					body.append( \'billing_address_dependentLocality\', tokenResult.details.billing.dependentLocality );
					body.append( \'billing_address_zip\', tokenResult.details.billing.postalCode );
					body.append( \'billing_address_country\', tokenResult.details.billing.countryCode );
					body.append( \'billing_address_phone\', tokenResult.details.billing.phone );
					body.append( \'billing_address_email\', tokenResult.details.billing.email );
					body.append( \'billing_address_first_name\', tokenResult.details.billing.givenName );
					body.append( \'billing_address_last_name\', tokenResult.details.billing.familyName );';
		}
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
					}';
		}
		echo '
					body.append( \'card_type\', tokenResult.details.card.brand );
					body.append( \'last_4\', tokenResult.details.card.last4 );
					body.append( \'exp_month\', tokenResult.details.card.expMonth );
					body.append( \'exp_year\', tokenResult.details.card.expYear );';
		if ( get_option( 'ec_option_require_terms_agreement' ) ) {
		echo '
					body.append( \'ec_terms_agree\', 1 );';
		}
		echo '
					if( verificationToken !== undefined ) {
						body.append( \'buyerVerificationToken\', verificationToken );
					}
					body.append( \'easycartnonce\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-square-complete-payment-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );
					const paymentResponse = await fetch( wpeasycart_ajax_object.ajax_url, {
						method: \'POST\',
						body: body,
					} );
					if ( paymentResponse.ok ) {
						return paymentResponse.json();
					}
					const errorBody = await paymentResponse.error;
					throw new Error( errorBody );
				}
				async function wpEasyCartVerifyBuyer( wpEasyCartSquarePayments, token ) {';
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
			echo "
					var given_name = jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val();
					var family_name = jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val();
					var address1 = jQuery( document.getElementById( 'ec_cart_billing_address' ) ).val();
					var address2 = jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).val();
					var addressLines = Array( address1 );
					if ( address2.length > 0 ) {
						addressLines.push( address2 );
					}
					var city = jQuery( document.getElementById( 'ec_cart_billing_city' ) ).val();
					var country = jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val();
					var state = ( jQuery( document.getElementById( 'ec_cart_billing_state_' + country ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_state_' + country ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_state' ) ).val();
					var zip = jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).val();
					var phone = jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).val();
					if ( jQuery( document.getElementById( 'billing_address_type_same' ) ).length && jQuery( document.getElementById( 'billing_address_type_same' ) ).is ( ':checked' ) ){
						given_name = jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).val();
						family_name = jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).val();
						address1 = jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).val();
						address2 = jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).val();
						addressLines = Array( address1 );
						if ( address2.length > 0 ) {
							addressLines.push( address2 );
						}
						city = jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).val();
						country = jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val();
						state = ( jQuery( document.getElementById( 'ec_cart_shipping_state_' + country ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_state_' + country ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).val();
						zip = jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).val();
						phone = jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).val();
					}";
			if ( ! class_exists( 'Email_Encoder' ) && ! function_exists( 'eae_encode_emails' ) ) {
				echo "
					var email = jQuery( document.getElementById( 'ec_contact_email' ) ).val();";
			}
		} else {
			echo '
					var given_name = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ) . '\';
					var family_name = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ) . '\';
					var address1 = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ) . '\';
					var address2 = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ) . '\';
					var addressLines = Array( address1 );
					if ( address2.length > 0 ) {
						addressLines.push( address2 );
					}
					var city = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ) . '\';
					var state = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ) . '\';
					var zip = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ) . '\';
					var country = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ) . '\';
					var phone = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ) . '\';';
			if ( ! class_exists( 'Email_Encoder' ) && ! function_exists( 'eae_encode_emails' ) ) {
				echo '
					var email = \'' . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ) . '\';';
			}
		}
		echo '
					var billing_contact = {
						familyName: family_name,
						givenName: given_name,';
		if ( ! class_exists( 'Email_Encoder' ) && ! function_exists( 'eae_encode_emails' ) ){
		echo '
						email: email,';
		}
		echo '
						countryCode: country,
						city: city,
						addressLines: addressLines,
						phone: phone
					};
					if ( country != \'AU\' ) {
						billing_contact.postalCode = zip;
					}
					const verificationDetails = {
						amount: \'' . esc_attr( number_format( $this->order_totals->grand_total, 2, '.', '' ) ) . '\',
						intent: \'CHARGE\',
						billingContact: billing_contact,
						currencyCode: \'' . esc_attr( get_option( 'ec_option_square_currency' ) ) . '\'
					};
					const verificationResults = await wpEasyCartSquarePayments.verifyBuyer(
						token,
						verificationDetails
					);
					return verificationResults.token;
				}
				async function wpEasyCartTokenize( paymentMethod ) {
					const tokenResult = await paymentMethod.tokenize();
					if ( tokenResult.status === \'OK\' ) {
						return tokenResult;
					} else {
						let errorMessage = `Tokenization failed-status: ${tokenResult.status}`;
						if (tokenResult.errors) {
							errorMessage += ` and errors: ${JSON.stringify(
								tokenResult.errors
							)}`;
						}
						throw new Error(errorMessage);
					}
				}
				function wpEasyCartDisplayPaymentResults( status, message ) {
					if ( jQuery( document.getElementById( \'wp-easycart-square-payment-status-container\' ) ).length ) {
						jQuery( document.getElementById( \'wp-easycart-square-payment-status-container\' ) ).html( message );
						const statusContainer = document.getElementById(
							\'wp-easycart-square-payment-status-container\'
						);
						if ( status === \'SUCCESS\' ) {
							statusContainer.classList.remove( \'is-failure\' );
							statusContainer.classList.add( \'is-success\' );
						} else {
							statusContainer.classList.remove( \'is-success\' );
							statusContainer.classList.add( \'is-failure\' );
						}
						statusContainer.style.visibility = \'visible\';
					}
				}
				async function wpEasyCartHandlePaymentMethodSubmission( event, paymentMethod, shouldVerify = false ) {
					var payment_method = "credit_card";
					if( jQuery( \'input:radio[name=ec_cart_payment_selection]:checked\' ).length ) {
						payment_method = jQuery( \'input:radio[name=ec_cart_payment_selection]:checked\' ).val( );
					}
					if( payment_method != \'credit_card\' ){
						return;
					}';
		if ( get_option( 'ec_option_require_terms_agreement' ) ) {
			echo '
					if( jQuery( document.getElementById( \'ec_terms_agree\' ) ).length && ! jQuery( document.getElementById( \'ec_terms_agree\' ) ).is( \':checked\' ) ) {
						if ( ! confirm( \'' . esc_js( wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_review_agree' ) ) . '.\' ) ) {
							return;
						} else {
							jQuery( document.getElementById( \'ec_terms_agree\' ) ).prop( \'checked\', true );
						}
					}';
		}
		if ( get_option( 'ec_option_onepage_checkout' ) ) {
			echo '
					if ( "Card" == paymentMethod.methodType && ! ec_validate_submit_order() ) {
						return;
					}
			';
		}
		echo '
					event.preventDefault();
					try {';
		if( $is_payment ) {
			echo '
						wpEasyCartCardButton.disabled = true;';
		}
		echo '
						jQuery( document.getElementById( \'square-success-cover\' ) ).show();
						const tokenResult = await wpEasyCartTokenize( paymentMethod );
						let verificationToken;
						if ( shouldVerify ) {
							verificationToken = await wpEasyCartVerifyBuyer(
								wpEasyCartSquarePayments,
								tokenResult.token
							);
						}
						const paymentResults = await wpEasyCartCreatePayment( tokenResult, verificationToken );
						if( paymentResults.ok ) {
							wpEasyCartDisplayPaymentResults( \'SUCCESS\', \'\' );
							jQuery( location ).attr( \'href\', paymentResults.goto );
						} else {';
		if( $is_payment ) {
			echo '
						wpEasyCartCardButton.disabled = false;';
		}
		echo '
							if ( "stock_error" == paymentResults.error ) {
								window.location.href = "' . esc_attr( $this->cart_page . $this->permalink_divider ) . 'ec_page=checkout_payment&ec_cart_error=stock_invalid";
							} else {
								wpEasyCartDisplayPaymentResults( \'FAILURE\', paymentResults.error );
								jQuery( document.getElementById( \'ec_cart_submit_order\' ) ).show( );
								jQuery( document.getElementById( \'ec_cart_submit_order_working\' ) ).hide( );
								ec_show_error( \'ec_submit_order\' );
								jQuery( document.getElementById( \'square-success-cover\' ) ).hide();
							}
						}
					} catch (e) {';
		if( $is_payment ) {
			echo '
						wpEasyCartCardButton.disabled = false;';
		}
		echo '
						wpEasyCartDisplayPaymentResults( \'SUCCESS\', \'\' );
						jQuery( document.getElementById( \'ec_cart_submit_order\' ) ).show( );
						jQuery( document.getElementById( \'ec_cart_submit_order_working\' ) ).hide( );
						ec_show_error( \'ec_submit_order\' );
						jQuery( document.getElementById( \'square-success-cover\' ) ).hide();
						console.log( e.message );
					}
				}
				function wpEasyCartBuildPaymentRequest( wpEasyCartSquarePayments ) {';
		if ( ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
		echo '
					const defaultShippingOptions = ' . json_encode( $this->ec_cart_display_shipping_methods_square_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ) );
		}
		echo '
					let lineItems = ' . json_encode( $this->get_dynamic_square_line_items() ) . ';
					let total = wpEasyCartSquareCalculateTotal( lineItems );
					const paymentRequestDetails = {
						countryCode: \'' . esc_attr( get_option( 'ec_option_square_location_country' ) ) . '\',
						currencyCode: \'' . esc_attr( get_option( 'ec_option_square_currency' ) ) . '\',
						lineItems: ' . json_encode( $this->get_dynamic_square_line_items() ) . ',
						requestBillingContact: true,';
		if ( ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
		echo '
						shippingOptions: defaultShippingOptions,';
		}
		if ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
						requestShippingContact: true,';
		}
		echo '
						total,
					};
					return wpEasyCartSquarePayments.paymentRequest( paymentRequestDetails );
				}
				function wpEasyCartSquareCalculateTotal( lineItems ) {
					const amount = lineItems.reduce( ( total, lineItem ) => {
						return total + parseFloat( lineItem.amount );
					}, 0.0).toFixed( 2 );
					return { amount, label: \'Total\' };
				}
				async function wpEasyCartSquareShippingMethodUpdate( shipping_option ) {
					var body = new FormData()
					body.append( \'action\', \'ec_ajax_update_square_shipping_option_dynamic\' );
					body.append( \'shippingAddress\', shipping_option.id );
					body.append( \'language\', wpeasycart_ajax_object.current_language );
					body.append( \'nonce\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-square-shipping-option-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );
					const shippingResponse = await fetch( wpeasycart_ajax_object.ajax_url, {
						method: \'POST\',
						body: body,
					} )
						.then( ( shippingResponse ) => shippingResponse.json() )
						.then( ( json_result ) => {
							ec_update_cart( json_result.cart_data );
							return {
								lineItems: json_result.display_items,
								total: {
									label: "' . esc_attr( get_option( 'ec_option_square_merchant_name' ) ) . '",
									amount: json_result.total,
								}
							}
						} );
					return shippingResponse;
				}
				async function wpEasyCartSquareShippingContactUpdate( contact ) {
					var body = new FormData()
					body.append( \'action\', \'ec_ajax_update_square_shipping_address_dynamic\' );
					body.append( \'shippingAddress[city]\', contact.city );
					body.append( \'shippingAddress[countryCode]\', contact.countryCode );
					body.append( \'shippingAddress[postalCode]\', contact.postalCode );
					body.append( \'shippingAddress[state]\', contact.state );
					body.append( \'language\', wpeasycart_ajax_object.current_language );
					body.append( \'nonce\', \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-square-shipping-address-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );
					const shippingResponse = await fetch( wpeasycart_ajax_object.ajax_url, {
						method: \'POST\',
						body: body,
					} )
						.then( ( shippingResponse ) => shippingResponse.json() )
						.then( ( json_result ) => {
							ec_update_cart( json_result.cart_data );
							return {
								lineItems: json_result.display_items,
								shippingOptions: json_result.shipping_options,
								total: {
									label: "' . esc_attr( get_option( 'ec_option_square_merchant_name' ) ) . '",
									amount: json_result.total,
								}
							};
						} );
					return shippingResponse;
				}';
		if ( get_option( 'ec_option_square_digital_wallet' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
		echo '
				async function wpEasyCartInitializeApplePay( payments ) {
					const paymentRequest = wpEasyCartBuildPaymentRequest( payments );';
		
		if ( ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
		echo '
					paymentRequest.addEventListener( \'shippingoptionchanged\', wpEasyCartSquareShippingMethodUpdate );';
		}
		if ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
					paymentRequest.addEventListener( \'shippingcontactchanged\', wpEasyCartSquareShippingContactUpdate );';
		}
		echo '
					const wpEasyCartApplePay = await payments.applePay( paymentRequest );
					return wpEasyCartApplePay;
				}
				async function wpEasyCartInitializeGooglePay( payments ) {
					const paymentRequest = wpEasyCartBuildPaymentRequest( payments );';
		if ( ( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
		echo '
					paymentRequest.addEventListener( \'shippingoptionchanged\', wpEasyCartSquareShippingMethodUpdate );';
		}
		if( ! $is_payment || get_option( 'ec_option_onepage_checkout' ) ) {
		echo '
					paymentRequest.addEventListener( \'shippingcontactchanged\', wpEasyCartSquareShippingContactUpdate );';
		}
		echo '
					const wpEasyCartGooglePay = await payments.googlePay( paymentRequest );
					await wpEasyCartGooglePay.attach( \'#wp-easycart-square-google-pay-button\' );
					return wpEasyCartGooglePay;
				}';
		}
		if ( get_option( 'ec_option_square_gift_cards' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
		echo '
				async function wpEasyCartInitializeGiftCard( payments ) {
					const wpEasyCartGiftCard = await payments.giftCard();
					await wpEasyCartGiftCard.attach( \'#wp-easycart-square-gift-card-container\' );
					return wpEasyCartGiftCard;
				}';
		}
		if ( $is_payment ) {
		echo '
				const wpEasyCartCardButton = document.getElementById(
					\'ec_cart_submit_order\'
				);
				wpEasyCartCardButton.addEventListener( \'click\', async function (event) {
					await wpEasyCartHandlePaymentMethodSubmission( event, wpEasyCartCard, true );
				} );';
		}
		if ( get_option( 'ec_option_square_digital_wallet' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
		echo '
				if ( wpEasyCartGooglePay !== undefined ) {
					const wpEasyCartGooglePayButton = document.getElementById( \'wp-easycart-square-google-pay-button\' );
					wpEasyCartGooglePayButton.addEventListener( \'click\', async function ( event ) {
						await wpEasyCartHandlePaymentMethodSubmission( event, wpEasyCartGooglePay );
					} );
				}
				if ( wpEasyCartApplePay !== undefined ) {
					const wpEasyCartApplePayButton = document.getElementById( \'wp-easycart-square-apple-pay-button\' );
					wpEasyCartApplePayButton.addEventListener( \'click\', async function ( event ) {
						await wpEasyCartHandlePaymentMethodSubmission( event, wpEasyCartApplePay );
					} );
				}';
		}
		if ( get_option( 'ec_option_square_gift_cards' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
		echo '
				const wpEasyCartGiftCardButton = document.getElementById( \'wp-easycart-square-gift-card-button\' );
				wpEasyCartGiftCardButton.addEventListener( \'click\', async function ( event ) {
					await wpEasyCartHandlePaymentMethodSubmission( event, wpEasyCartGiftCardButton );
				} );';
		}
		echo '
			}
		</script>';
	}

	public function print_square_payment_express() {
		if ( get_option( 'ec_option_square_digital_wallet' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
			echo '<div class="wp-easycart-square-express-checkout">';
				echo '<div id="wp-easycart-square-apple-pay-button"></div>';
				echo '<div id="wp-easycart-square-google-pay-button"></div>';
			echo '</div>';
		}
		
		if ( get_option( 'ec_option_onepage_checkout_tabbed' ) ) {
			echo '<script>
				wpEasyCartSquareInit();
			</script>';
		}
	}

	public function print_square_payment_card() {
		if ( get_option( 'ec_option_square_gift_cards' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
			echo '<div id="wp-easycart-square-gift-card-container"></div>';
			echo '<button id="wp-easycart-square-gift-card-button" type="button">Pay with Gift Card</button>';
		}

		echo '<div id="wp-easycart-square-card-container"></div>';
		echo '<div id="wp-easycart-square-payment-status-container"></div>';

		echo '<script>
			wpEasyCartSquareInit();
		</script>';
	}

	public function print_square_payment_button( $is_payment = false ) {
		if ( ! $is_payment && ! get_option( 'ec_option_square_digital_wallet' ) ) {
			return;
		}

		if ( get_option( 'ec_option_square_gift_cards' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) ) {
			echo '<div id="wp-easycart-square-gift-card-container"></div>';
			echo '<button id="wp-easycart-square-gift-card-button" type="button">Pay with Gift Card</button>';
		}

		if ( get_option( 'ec_option_square_digital_wallet' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
			echo '<div id="wp-easycart-square-apple-pay-button"></div>';
			echo '<div id="wp-easycart-square-google-pay-button"></div>';
		}

		if ( $is_payment ) {
			echo '<div id="wp-easycart-square-card-container"></div>';
		}

		echo '<div id="wp-easycart-square-payment-status-container"></div>';

		echo '<script>
			wpEasyCartSquareInit();
		</script>';
	}

	public function get_dynamic_square_line_items() {
		$return_arr     = array( (object) array(
			'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_subtotal' ),
			'amount'    => number_format( $this->order_totals->sub_total, 2, '.', '' ),
		) );

		if ( $this->order_totals->tax_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_tax' ),
				'amount'    => number_format( $this->order_totals->tax_total, 2, '.', '' ),
			);
		}

		if ( get_option( 'ec_option_use_shipping' ) && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 ) && $this->order_totals->shipping_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_shipping' ),
				'amount'    => number_format( $this->order_totals->shipping_total, 2, '.', '' ),
			);
		}

		if ( $this->order_totals->discount_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_discounts' ),
				'amount'    => number_format( $this->order_totals->discount_total, 2, '.', '' ),
			);
		}

		if ( $this->tax->is_duty_enabled() && $this->order_totals->duty_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_duty' ),
				'amount'    => number_format( $this->order_totals->duty_total, 2, '.', '' ),
			);
		}

		if ( $this->tax->is_vat_enabled() && $this->order_totals->vat_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_vat' ),
				'amount'    => number_format( $this->order_totals->vat_total, 2, '.', '' ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->gst_total > 0  ) {
			$return_arr[] = (object) array(
				'label'     => 'GST' . ( ( $this->tax->gst_rate > 0 ) ? ' ' .$this->tax->gst_rate . '%' : '' ),
				'amount'    => number_format( $this->order_totals->gst_total, 2, '.', '' ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->pst_total > 0  ) {
			$return_arr[] = (object) array(
				'label'     => 'PST' . ( ( $this->tax->pst_rate > 0 ) ? ' ' .$this->tax->pst_rate . '%' : '' ),
				'amount'    => number_format( $this->order_totals->pst_total, 2, '.', '' ),
			);
		}

		if ( get_option( 'ec_option_enable_easy_canada_tax' ) && $this->order_totals->hst_total > 0 ) {
			$return_arr[] = (object) array(
				'label'     => 'HST' . ( ( $this->tax->hst_rate > 0 ) ? ' ' .$this->tax->hst_rate . '%' : '' ),
				'amount'    => number_format( $this->order_totals->hst_total, 2, '.', '' ),
			);
		}

		return $return_arr;
	}
	
	public function print_stripe_script_v2( $is_payment = false ) {
		$mount_items = array();
		$link_enabled = ( get_option( 'ec_option_stripe_link' ) && '' == $GLOBALS['ec_cart_data']->cart_data->user_id );
		$shipping_enabled = ( get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) );
		$stripe_address_enabled = get_option( 'ec_option_stripe_address_autocomplete' );
		$enable_express = false;
		echo "<script>
		try {
			jQuery( document ).ready( function() {
				var clientSecret = '" . esc_attr( $this->get_stripe_intent_client_secret() ) . "';
				const appearance = {
					theme: '" . esc_attr( get_option( 'ec_option_stripe_payment_theme' ) ) . "',
				};
				const options = {
					clientSecret: clientSecret,
					appearance: appearance,
				};
				const elements = stripe.elements( options );";

		if ( $link_enabled && ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) || ! $is_payment ) ) {
			$this->print_stripe_script_link_auth_v2();
			$mount_items[] = 'linkAuthenticationElement.mount( "#link-authentication-element" );';
		}
		if ( $stripe_address_enabled && $shipping_enabled && ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) || ! $is_payment ) ) {
			$this->print_stripe_script_address_v2( true );
			$mount_items[] = 'shippingaddressElement.mount( "#shipping-address-element" );';
		}
		if ( $stripe_address_enabled && ( ( get_option( 'ec_option_onepage_checkout_tabbed' ) && ! $shipping_enabled && ! $is_payment ) || ( get_option( 'ec_option_onepage_checkout_tabbed' ) && $shipping_enabled && $is_payment ) || ! get_option( 'ec_option_onepage_checkout_tabbed' ) ) ) {
			$this->print_stripe_script_address_v2( false, ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) && $shipping_enabled ) );
			$mount_items[] = 'billingaddressElement.mount( "#billing-address-element" );';
		}
		if ( $enable_express && ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) || ! $is_payment ) ) {
			$this->print_stripe_script_payment_express_v2();
			$mount_items[] = 'expressCheckoutElement.mount( "#wpec-express-checkout-element" );';
		}
		if ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) || $is_payment ) {
			$this->print_stripe_script_payment_v2();
			$mount_items[] = 'paymentElement.mount( "#ec_stripe_card_row" );';
		}
		foreach ( $mount_items as $mount_item ) {
			echo '
			' . $mount_item;
		}
		echo '
			} );
		} catch( err ) {
			alert( "Your WP EasyCart with Stripe has a problem: " + err.message + ". Contact WP EasyCart for assistance." );
		}
		</script>';
	}

	public function print_stripe_script_link_auth_v2() {
		echo "
		var emailAddressTimer;
		const linkAuthenticationElement = elements.create( 'linkAuthentication', {
			defaultValues: {
				email: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->email ) . "'
			}
		} );
		linkAuthenticationElement.on('change', (event) => {
			jQuery( document.getElementById( 'ec_contact_email' ) ).val( event.value.email );
			if ( event.complete ) {
				jQuery( document.getElementById( 'ec_contact_email_complete' ) ).val( '1' );
				jQuery( document.getElementById( 'ec_email_order1_error' ) ).hide();
				if ( jQuery( document.getElementById( 'ec_email_order2_error' ) ).length ) {
					jQuery( document.getElementById( 'ec_email_order2_error' ) ).hide();
				}
				clearTimeout( emailAddressTimer );
				emailAddressTimer = setTimeout( function() {
					wp_easycart_update_contact_email_v2();
				}, 800 );
			} else {
				if ( jQuery( document.getElementById( 'link-authentication-element' ) ).hasClass( 'ec_cart_stripe_address_is_init' ) ) {
					jQuery( document.getElementById( 'link-authentication-element' ) ).removeClass( 'ec_cart_stripe_address_is_init' );
				} else {
					jQuery( document.getElementById( 'ec_contact_email_complete' ) ).val( '0' );
					jQuery( document.getElementById( 'ec_email_order1_error' ) ).show();
					if ( jQuery( document.getElementById( 'ec_email_order2_error' ) ).length ) {
						jQuery( document.getElementById( 'ec_email_order2_error' ) ).show();
					}
				}
			}
		} );";
	}

	public function print_stripe_script_address_v2( $is_shipping = true, $custom_elements = false ) {
		$country_list = $GLOBALS['ec_countries']->countries;
		$country_string = '';
		$is_first_country = true;
		foreach ( $country_list as $country ) {
			if ( ! $is_first_country ) {
				$country_string .= ',';
			}
			$country_string .= "'" . $country->iso2_cnt . "'";
			$is_first_country = false;
		}
		$type = ( $is_shipping ) ? 'shipping' : 'billing';
		echo "var " . $type . "AddressTimer;";
		if ( $custom_elements ) {
		echo "
		const billingOptions = {
			clientSecret: clientSecret,
			appearance: appearance,
		};
		const billingElements = stripe.elements( billingOptions );
		const billingaddressElement = billingElements.create( 'address', {";
		} else {
		echo "
		const " . $type . "addressElement = elements.create( 'address', {";
		}
		echo "
			mode: '" . $type . "',
			allowedCountries:[" . $country_string . "],";
		if ( get_option( 'ec_option_collect_user_phone' ) ) {
		echo "
			fields: {
				phone: 'always',
			},
			validation: {
				phone: {
					required: 'always',
				},
			},";
		}
		echo "
			defaultValues: {";
		if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_first_name' } || '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_last_name' } ) {
		echo "
				name: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_first_name' } ) . " " . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_last_name' } ) . "',";
		}
		if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_phone' } ) {
		echo "
				phone: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_phone' } ) . "',";
		}
		if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_address_line_1' } || '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_city' } || '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_state' } || '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_zip' } || '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_country' } ) {
			echo "
				address: {";
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_address_line_1' } ) {
			echo "
					line1: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_address_line_1' } ) . "',";
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_address_line_2' } ) {
			echo "
					line2: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_address_line_2' } ) . "',";
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_city' } ) {
			echo "
					city: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_city' } ) . "',";
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_state' } ) {
			echo "
					state: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_state' } ) . "',";
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_zip' } ) {
			echo "
					postal_code: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_zip' } ) . "',";
			}
			if ( '' != $GLOBALS['ec_cart_data']->cart_data->{ $type . '_country' } ) {
			echo "
					country: '" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->{ $type . '_country' } ) . "',";
			}
			echo "
				},";
		}
		echo "
			},
		} ).on( 'change', (event) => {
			jQuery( document.getElementById( 'ec_" . $type . "_complete' ) ).val( ( ( event.complete ) ? '1' : '0' ) );
			jQuery( document.getElementById( 'ec_" . $type . "_address_line_1' ) ).val( event.value.address.line1 );
			jQuery( document.getElementById( 'ec_" . $type . "_address_line_2' ) ).val( event.value.address.line2 );
			jQuery( document.getElementById( 'ec_" . $type . "_city' ) ).val( event.value.address.city );
			jQuery( document.getElementById( 'ec_" . $type . "_state' ) ).val( event.value.address.state );
			jQuery( document.getElementById( 'ec_" . $type . "_zip' ) ).val( event.value.address.postal_code );
			jQuery( document.getElementById( 'ec_" . $type . "_country' ) ).val( event.value.address.country );
			jQuery( document.getElementById( 'ec_" . $type . "_name' ) ).val( event.value.name );
			jQuery( document.getElementById( 'ec_" . $type . "_phone' ) ).val( event.value.phone );
			if ( jQuery( document.getElementById( '" . $type . "-address-element' ) ).hasClass( 'ec_cart_stripe_address_is_init' ) ) {
				jQuery( document.getElementById( '" . $type . "-address-element' ) ).removeClass( 'ec_cart_stripe_address_is_init' );
			} else {
				if ( ! event.complete ) {
					" . $type . "addressElement.getValue();
				}";
		if ( ! get_option( 'ec_option_onepage_checkout_tabbed' ) && 'shipping' == $type ) {
			echo " else {
					clearTimeout( " . $type . "AddressTimer );
					" . $type . "AddressTimer = setTimeout( function() {
						wp_easycart_goto_shipping_v2( true );
						if ( jQuery( document.getElementById( 'ec_shipping_order_error' ) ).length ) {
							jQuery( document.getElementById( 'ec_shipping_order_error' ) ).hide();
						}
					}, 800 );
				}";
		}
		if ( 'billing' == $type ) {
			echo " else {
					clearTimeout( billingAddressTimer );
					billingAddressTimer = setTimeout( function() {
						var shipping_selector = ( jQuery( '#billing_address_type_different' ).is( ':checked' ) ) ? '1' : '0';
						ec_update_billing_address_display( shipping_selector, '" . esc_attr( wp_create_nonce( 'wp-easycart-update-billing-address-type-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "' );
						jQuery( document.getElementById( 'ec_billing_order_error' ) ).hide();
					}, 800 );
				}";
		}
		echo"
			}
		} );
		if ( jQuery( document.getElementById( 'ec_cart_submit_order' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_submit_order' ) ).on( 'click', function() {
				" . $type . "addressElement.getValue();
			} );
		}";
	}

	public function print_stripe_script_payment_v2() {
		echo "
			const paymentElement = elements.create( 'payment', {";
		if ( 'accordion' == get_option( 'ec_option_stripe_payment_layout' ) ) {
		echo "
				layout: {
					type: 'accordion',
					defaultCollapsed: false,
					radios: false,
					spacedAccordionItems: false
				},";
		} else {
		echo "
				layout: {
					type: 'tabs',
					defaultCollapsed: false
				},";
		}
		echo "
			} );
			paymentElement.addEventListener( 'change', function( event ){
				var displayError = document.getElementById( 'ec_card_errors' );
				if( event.error ){
					displayError.textContent = event.error.message;
				}else{
					displayError.textContent = '';
				}
			} );
			var form = ( jQuery( document.getElementById( 'ec_submit_order_form' ) ).length ) ? document.getElementById( 'ec_submit_order_form' ) : document.getElementById( 'wpeasycart_checkout_details_form' );
			form.addEventListener( 'submit', function( event ){
				var payment_method = 'credit_card';
				if ( jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).length ) {
					payment_method = jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).val();
				}
				if ( payment_method != 'credit_card' ) {
					jQuery( document.getElementById( 'ec_submit_order_error' ) ).hide();
				} else {
					event.preventDefault();
					jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
					jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
					jQuery( document.getElementById( 'stripe-success-cover' ) ).show( );
					jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).hide( );
					jQuery( document.getElementById( 'ec_card_errors' ) ).hide( );";
			if ( get_option( 'ec_option_onepage_checkout_tabbed' ) || ! get_option( 'ec_option_stripe_address_autocomplete' ) ) {
		echo "
					var name = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) ) . " " . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) ) . "';
					var address1 = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) ) . "';
					var address2 = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) ) . "';
					var city = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) ) . "';
					var state = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) ) . "';
					var zip = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) ) . "';
					var country = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) ) . "';
					var phone = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) ) . "';
					var shipping_name = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) ) . " " . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) ) . "';
					var shipping_address1 = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) ) . "';
					var shipping_address2 = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) ) . "';
					var shipping_city = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) ) . "';
					var shipping_state = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) ) . "';
					var shipping_zip = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) ) . "';
					var shipping_country = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country, ENT_QUOTES ) ) . "';
					var shipping_phone = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) ) . "';";
			}
			if ( ! class_exists( 'Email_Encoder' ) && ! function_exists( 'eae_encode_emails' ) ) {
		echo "
					var email = '" . esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->email, ENT_QUOTES ) ) . "';";
			}
		echo "
					var ec_terms_agree = 0;
					if ( jQuery( document.getElementById( 'ec_terms_agree' ) ).length && jQuery( document.getElementById( 'ec_terms_agree' ) ).is( ':checked' ) ) {
						ec_terms_agree = 1;
					}
					var ec_cart_is_subscriber = 0;
					if( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).length && jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ){
						ec_cart_is_subscriber = 1;
					}";
		if ( get_option( 'ec_option_onepage_checkout_tabbed' ) || ! get_option( 'ec_option_stripe_address_autocomplete' ) ) {
		echo "
					var additionalData = {
						name: name,
						address_line1: address1,
						address_city: city,
						address_state: state,
						address_zip: zip
					};";
		}
		echo "
					stripe.confirmPayment( {
						elements,
						confirmParams: {
							return_url: '" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_payment&stripe=returning" . "',";
		if ( get_option( 'ec_option_onepage_checkout_tabbed' ) || ! get_option( 'ec_option_stripe_address_autocomplete' ) ) {
		echo "
							shipping: {
								address: {
									line1: shipping_address1,
									city: shipping_city,
									country: shipping_country,
									line2: shipping_address2,
									postal_code: shipping_zip,
									state: shipping_state
								},
								name: shipping_name,
								phone: shipping_phone
							},
							payment_method_data: {
								billing_details: {
									address: {
										city: city,
										country: country,
										line1: address1,
										line2: address2,
										postal_code: zip,
										state: state
									},";
		if ( ! class_exists( 'Email_Encoder' ) && !function_exists( 'eae_encode_emails' ) ){
		echo "
									email: email,";
		}
		echo "
									name: name";
		if ( $GLOBALS['ec_cart_data']->cart_data->billing_phone != '' ) {
		echo ",
									phone: phone";
		}
		echo "
								}
							}";
		}
		echo "
						},
						redirect: 'if_required'
					} ).then( function( result ){
						if( result.error ){
							jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
							jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
							jQuery( document.getElementById( 'stripe-success-cover' ) ).fadeOut( );
							jQuery( document.getElementById( 'ec_stripe_dynamic_error' ) ).fadeIn( ).find( 'div' ).html( result.error.message );
							jQuery( document.getElementById( 'ec_card_errors' ) ).fadeIn( ).html( result.error.message );
						}else{
							if ( 'succeeded' == result.paymentIntent.status ) {
								var data = {
									action: 'ec_ajax_get_stripe_complete_payment_main',
									language: wpeasycart_ajax_object.current_language,
									ec_terms_agree: ec_terms_agree,
									ec_cart_is_subscriber: ec_cart_is_subscriber,
									nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-main-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "'
								};
								jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ){
									jQuery( location ).attr( 'href', result );
								} } );
							} else {
								ec_create_ideal_order_redirect( result.paymentIntent.id, '" . esc_attr( wp_create_nonce( 'wp-easycart-create-stripe-ideal-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "' );
							}
						}
					} );
				}
			} );";
	}

	public function print_stripe_script_payment_express_v2() {
		$payment_method_types = array();
		if ( get_option( 'ec_option_stripe_enable_apple_pay' ) ) {
			$payment_method_types[] = "'card'";
		}
		if ( get_option( 'ec_option_stripe_cashapp' ) ) {
			$payment_method_types[] = "'cashapp'";
		}
		if ( get_option( 'ec_option_stripe_alipay' ) ) {
			$payment_method_types[] = "'alipay'";
		}
		if ( get_option( 'ec_option_stripe_grabpay' ) ) {
			$payment_method_types[] = "'grabpay'";
		}
		if ( get_option( 'ec_option_stripe_wechat' ) ) {
			$payment_method_types[] = "'wechat_pay'";
		}
		if ( get_option( 'ec_option_stripe_link' ) ) {
			$payment_method_types[] = "'link'";
		}
		echo "const expressCheckoutElement = elements.create( 'expressCheckout' );
		expressCheckoutElement.on('click', (event) => {
			const options = {
				emailRequired: true,
				phoneNumberRequired: true,
				shippingAddressRequired: true,
				lineItems: <?php echo json_encode( $this->get_stripe_express_cart_items() ); ?>,
				shippingRates: <?php echo json_encode( $this->get_stripe_express_shipping_items( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ), wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ) ); ?>
			};
			event.resolve( options );
		} );
		expressCheckoutElement.on( 'shippingaddresschange', function( ev ) {
			var data = {
				action: 'ec_ajax_get_stripe_express_shipping_dynamic',
				shippingAddress: ev.address,
				language: wpeasycart_ajax_object.current_language,
				nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "'
			};
			jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
				var json_result = JSON.parse( result );
				ec_update_cart( json_result.cart_data );
				if( json_result.shipping_rates.length > 0 ) {
					ev.resolve( {
						shippingRates: json_result.shipping_rates,
						lineItems: json_result.line_items,
					} );
				}
			} } );
		} );
		expressCheckoutElement.on( 'shippingratechange', function( ev ) {
			var data = {
				action: 'ec_ajax_get_stripe_express_shipping_rate_dynamic',
				shippingRate: ev.shippingRate.id,
				language: wpeasycart_ajax_object.current_language,
				nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "'
			};
			jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
				var json_result = JSON.parse( result );
				ec_update_cart( json_result.cart_data );
				if( json_result.shipping_rates.length > 0 ) {
					ev.resolve( {
						shippingRates: json_result.shipping_rates,
						lineItems: json_result.line_items,
					} );
				}
			} } );
		} );
		expressCheckoutElement.on( 'paymentmethod', function( ev ) {
			var data = {
				action: 'ec_ajax_get_stripe_shipping_dynamic',
				shippingAddress: ev.shippingAddress,
				language: wpeasycart_ajax_object.current_language,
				nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "',
			};
			jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
				var json_result = JSON.parse( result );
				ec_update_cart( json_result.cart_data );
				stripe.confirmPaymentIntent(
					clientSecret,
					{
						payment_method: ev.paymentMethod.id,
					}
				).then(
					function( confirmResult ) {
						if ( confirmResult.error ) {
							ev.complete( 'fail' );
						} else if ( 'succeeded' == confirmResult.paymentIntent.status ) {
							var data = {
								action: 'ec_ajax_get_stripe_complete_payment',
								payment_id: ev.paymentMethod.id,
								shipping_address: ev.shippingAddress,
								shipping_method: ev.shippingOption.id,
								billing_address: ev.paymentMethod.billing_details.address,
								billing_name: ev.paymentMethod.billing_details.name,
								billing_phone: ev.paymentMethod.billing_details.phone,
								billing_email: ev.paymentMethod.billing_details.email,
								card_type: ev.paymentMethod.card.brand,
								last_4: ev.paymentMethod.card.last4,
								exp_month: ev.paymentMethod.card.exp_month,
								exp_year: ev.paymentMethod.card.exp_year,
								email: ev.payerEmail,
								phone: ev.payerPhone,
								clientSecret: clientSecret,
								language: wpeasycart_ajax_object.current_language,
								nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "',
							};
							jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
								ev.complete( 'success' );
								jQuery( location ).attr( 'href', result );
							} } );
						} else {
							ev.complete( 'success' );
							stripe.handleCardPayment( clientSecret ).then( function( result ) {
								if ( result.error ) {
									// error
								} else {
									var data = {
										action: 'ec_ajax_get_stripe_complete_payment',
										payment_id: ev.paymentMethod.id,
										shipping_address: ev.shippingAddress,
										shipping_method: ev.shippingOption.id,
										billing_address: ev.paymentMethod.billing_details.address,
										billing_name: ev.paymentMethod.billing_details.name,
										billing_phone: ev.paymentMethod.billing_details.phone,
										billing_email: ev.paymentMethod.billing_details.email,
										card_type: ev.paymentMethod.card.brand,
										last_4: ev.paymentMethod.card.last4,
										exp_month: ev.paymentMethod.card.exp_month,
										exp_year: ev.paymentMethod.card.exp_year,
										email: ev.payerEmail,
										phone: ev.payerPhone,
										clientSecret:clientSecret,
										language: wpeasycart_ajax_object.current_language,
										nonce: '" . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . "'
									};
									jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ) {
										jQuery( location ).attr( 'href', result );
									} } );
								}
							} );
						}
					}
				);
			} } );
		} );";
	}

	public function print_stripe_payment_button( $is_payment = false ) {
		if( get_option( 'ec_option_stripe_disable_wallet_first' ) && ! $is_payment ) {
			return false;
		}

		if ( get_option( 'ec_option_stripe_enable_apple_pay' ) && apply_filters( 'wp_easycart_allow_paypal_express', false ) && (int) ( $this->order_totals->get_converted_grand_total() * 100 ) >= 50 ) {
			if ( (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > 0 && (float) apply_filters( 'wpeasycart_minimum_order_total', get_option( 'ec_option_minimum_order_total' ) ) > $this->cart->subtotal ) {
				echo '<div id="ec-stripe-wallet-button"></div>';
			} else {
				$client_secret = $this->get_stripe_intent_client_secret();
				if ( $is_payment ) {
					echo '<div class="ec_cart_option_row" id="ec_apple_pay_row">
						<input type="radio" class="no_wrap" name="ec_cart_payment_selection" id="ec_payment_apple" value="apple_pay"';
						if ( $this->get_selected_payment_method() == "apple_pay" ) {
							echo ' checked="checked"';
						}
						echo 'onChange="ec_update_payment_display( \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );" /> ' . wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_apple_pay' ) . '
					</div>
					<div id="ec_apple_pay_form"';
					if ( $this->get_selected_payment_method() == "apple_pay" ) {
						echo ' style="display:block;"';
					} else {
						echo ' style="display:none;"';
					}
					echo '><div class="ec_cart_box_section">';
				} else {
					echo '<div id="ec-stripe-wallet-button">';
				}
				echo '<div id="payment-request-button" style="float:left; width:100%;">
				  <!-- A Stripe Element will be inserted here. -->
				</div>';
				if ( $is_payment ) {
					echo '</div></div>';
				}
				echo '<div id="stripe-success-cover" style="display:none; cursor:default; position:fixed; top:0; left:0; width:100%; height:100%; z-index:999999; background-color: rgba(0, 0, 0, 0.8); color:#FFF;">
					<style>
					@keyframes rotation{
						0%  { transform:rotate(0deg); }
						100%{ transform:rotate(359deg); }
					}
					</style>
					<div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'>
						<div class="paypal-checkout-loader">
							<div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div>
						</div>
					</div>
				</div>';
				if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
					$pkey = get_option( 'ec_option_stripe_public_api_key' );
				} else if ( get_option( 'ec_option_payment_process_method' ) == 'stripe_connect' && get_option( 'ec_option_stripe_connect_use_sandbox' ) ) {
					$pkey = get_option( 'ec_option_stripe_connect_sandbox_publishable_key' );
				} else {
					$pkey = get_option( 'ec_option_stripe_connect_production_publishable_key' );
				}
				echo '<script type="text/javascript">
					jQuery( document.getElementById( \'stripe-success-cover\' ) ).appendTo( document.body );
					var stripe = Stripe( \'' . esc_attr( $pkey ) . '\' );
					var clientSecret = \'' . esc_attr( $client_secret ) . '\';
					var paymentRequest = stripe.paymentRequest({
						country: \'' . esc_attr( get_option( 'ec_option_stripe_company_country' ) ) . '\',
						currency: \'' . esc_attr( strtolower( get_option( 'ec_option_stripe_currency' ) ) ) . '\',
						displayItems:' . json_encode( wpeasycart_get_cart_display_items( $this->cart, $this->order_totals, $this->tax ) ) . ',
						total: {
							label: \'' . wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_grand_total' ) . '\',
							amount: ' . (int) ( $this->order_totals->get_converted_grand_total() * 100 ) . ',
						},';
				if ( !$is_payment ) {
					if( get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
						echo '
						requestShipping: true,';
					}
					echo '
						requestPayerPhone: true,';
				}
				if ( !$is_payment && !$GLOBALS['ec_user']->user_id ) {
				echo '		
						requestPayerEmail: true,';
				}
				if ( !$is_payment && get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
				echo '
						shippingOptions: ' . json_encode( $this->ec_cart_display_shipping_methods_stripe_dynamic( wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_standard' ),wp_easycart_language()->get_text( 'cart_estimate_shipping', 'cart_estimate_shipping_express' ) ) ) . ',';
				}
				echo '
					});
					paymentRequest.on( \'paymentmethod\', function(ev) {
						jQuery( document.getElementById( \'stripe-success-cover\' ) ).show();
						jQuery( document.getElementById( \'ec_stripe_error\' ) ).fadeOut();';
					if ( !$is_payment ) {
					echo '
						var data = {
							action: \'ec_ajax_get_stripe_shipping_dynamic\',
							shippingAddress: ev.shippingAddress,
							language: wpeasycart_ajax_object.current_language,
							nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\'
						};
						jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: \'post\', data: data, success: function( result ) {
							var json_result = JSON.parse( result );
							ec_update_cart( json_result.cart_data );';
					}
					echo '
							stripe.confirmPaymentIntent( clientSecret, {
								payment_method: ev.paymentMethod.id,
							} ).then( function( confirmResult ) {
								if ( confirmResult.error ) {
									jQuery( document.getElementById( \'stripe-success-cover\' ) ).fadeOut();
									jQuery( document.getElementById( \'ec_stripe_error\' ) ).fadeIn().find( \'div\' ).html( confirmResult.error.message );
									ev.complete( \'fail\' );
								} else if ( confirmResult.paymentIntent.status == \'succeeded\' ) {
									var data = {
										action: \'ec_ajax_get_stripe_complete_payment\',';
					if ( !$is_payment ) {
					echo '
										payment_id: ev.paymentMethod.id,';
						if( get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
							echo '
										shipping_address: ev.shippingAddress,
										shipping_method: ev.shippingOption.id,';
						}
						echo '
										billing_address: ev.paymentMethod.billing_details.address,
										billing_name: ev.paymentMethod.billing_details.name,
										billing_phone: ev.paymentMethod.billing_details.phone,
										billing_email: ev.paymentMethod.billing_details.email,';
					}
					echo '
										card_type: ev.paymentMethod.card.brand,
										last_4: ev.paymentMethod.card.last4,
										exp_month: ev.paymentMethod.card.exp_month,
										exp_year: ev.paymentMethod.card.exp_year,
										email: ev.payerEmail,
										phone: ev.payerPhone,';
					if ( get_option( 'ec_option_require_terms_agreement' ) ) {
					echo '
										ec_terms_agree: 1,';	
					}					
					echo '
										clientSecret:clientSecret,
										language: wpeasycart_ajax_object.current_language,
										nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\'
									};
									jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: \'post\', data: data, success: function( result ) {
										ev.complete( \'success\' );
										jQuery( location ).attr( \'href\', result );
									} } );
								} else {
									ev.complete( \'success\' );
									stripe.handleCardPayment( clientSecret ).then( function( result ) {
										if ( result.error ) {
											jQuery( document.getElementById( \'stripe-success-cover\' ) ).fadeOut();
											jQuery( document.getElementById( \'ec_stripe_error\' ) ).fadeIn().find( \'div\' ).html( result.error.message );
										} else {
											var data = {
												action: \'ec_ajax_get_stripe_complete_payment\',
												payment_id: ev.paymentMethod.id,';
							if ( !$is_payment ) {
								if( get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
									echo '
												shipping_address: ev.shippingAddress,
												shipping_method: ev.shippingOption.id,';
								}
								echo '
												billing_address: ev.paymentMethod.billing_details.address,
												billing_name: ev.paymentMethod.billing_details.name,
												billing_phone: ev.paymentMethod.billing_details.phone,
												billing_email: ev.paymentMethod.billing_details.email,';
							}
							echo '
												card_type: ev.paymentMethod.card.brand,
												last_4: ev.paymentMethod.card.last4,
												exp_month: ev.paymentMethod.card.exp_month,
												exp_year: ev.paymentMethod.card.exp_year,
												email: ev.payerEmail,
												phone: ev.payerPhone,';
							if ( get_option( 'ec_option_require_terms_agreement' ) ) {
							echo '
												ec_terms_agree: 1,';	
							}
							echo '
												clientSecret:clientSecret,
												language: wpeasycart_ajax_object.current_language,
												nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-complete-payment-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\'
											};
											jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: \'post\', data: data, success: function( result ) {
												jQuery( location ).attr( \'href\', result );
											} } );
										}
									} );
								}
							} );';
					if ( !$is_payment ) {
					echo '
						} } );';
					}
					echo '
					} );';
					if( get_option( 'ec_option_use_shipping' ) && $this->cart->shippable_total_items > 0 ) {
						echo '
					paymentRequest.on( \'shippingaddresschange\', function(ev) {
						var data = {
							action: \'ec_ajax_get_stripe_shipping_dynamic\',
							shippingAddress: ev.shippingAddress,
							language: wpeasycart_ajax_object.current_language,
							nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\'
						};
						jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: \'post\', data: data, success: function( result ) {
							var json_result = JSON.parse( result );
							ec_update_cart( json_result.cart_data );
							if( json_result.shipping_options.length > 0 ) {
								ev.updateWith( {
									status: \'success\',
									shippingOptions: json_result.shipping_options,
									displayItems: json_result.display_items,
									total: {
										label: \'' . wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_grand_total' ) . '\',
										amount: json_result.total,
									  },
								} );
							}
						} } );
					} );
					paymentRequest.on( \'shippingoptionchange\', function(ev) {
						var data = {
							action: \'ec_ajax_get_stripe_shipping_option_dynamic\',
							billingAddress: ev.billingAddress,
							shippingAddress: ev.shippingAddress,
							shippingOption: ev.shippingOption,
							language: wpeasycart_ajax_object.current_language,
							nonce: \'' . esc_attr( wp_create_nonce( 'wp-easycart-get-stripe-shipping-option-dynamic-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\'
						};
						jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: \'post\', data: data, success: function( result ) {
							var json_result = JSON.parse( result );
							ec_update_cart( json_result.cart_data );
							ev.updateWith( {
								status: \'success\',
								shippingOptions: json_result.shipping_options,
								displayItems: json_result.display_items,
								total: {
								  label: \'' . wp_easycart_language()->get_text( 'cart_totals', 'cart_totals_grand_total' ) . '\',
								  amount: json_result.total,
								}
							} );
						} } );
					} );';
					}
					echo '
					paymentRequest.on( \'cancel\', function( ev ) {
						jQuery( document.getElementById( \'stripe-success-cover\' ) ).fadeOut();
					} );
					var elements = stripe.elements();
					var prButton = elements.create( \'paymentRequestButton\', {
						paymentRequest: paymentRequest
					} );';
				if ( get_option( 'ec_option_require_terms_agreement' ) ) {
				echo '
					prButton.on( \'click\', function( ev ) {
						if ( !confirm( \'' . esc_js( wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_review_agree' ) ) . '.\' ) ) {
							paymentRequest.trigger( \'cancel\' );
						}
					} );';
				}
				echo '
					paymentRequest.canMakePayment().then( function( result ) {
						if ( result ) {';
				if ( $is_payment ) {
					echo '
					ec_update_payment_display( \'' . esc_attr( wp_create_nonce( 'wp-easycart-update-payment-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) . '\' );';
				}
				echo '
							prButton.mount( \'#payment-request-button\' );';
				if ( get_option( 'ec_option_onepage_checkout' ) ) {
					echo '
							jQuery( \'.ec_cart_express_checkout\' ).show();';
				}
				echo '
						} else {';
				if ( $is_payment ) {
					echo '
							document.getElementById( \'ec_apple_pay_row\' ).style.display = \'none\';
							document.getElementById( \'ec_apple_pay_form\' ).style.display = \'none\';';
				}
				if ( get_option( 'ec_option_onepage_checkout' ) ) {
					echo '
							jQuery( \'.ec_cart_express_checkout\' ).hide();';
				}
				echo '
							document.getElementById( \'payment-request-button\' ).style.display = \'none\';
						}
					});
				</script>';
				if ( !$is_payment ) {
					echo '</div>';
				}
			}
		}
	}
	/* END SHIPPING METHOD FUNCTIONS */

	/* START COUPON FUNCTIONS */
	public function display_coupon() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_coupon.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_coupon.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_coupon.php' );
		}
	}

	public function display_coupon_input( $redeem_text ) {
		echo "<input type=\"text\" name=\"ec_cart_coupon_code\" id=\"ec_cart_coupon_code\" class=\"ec_cart_coupon_input_text\" value=\"";
		if ( $this->coupon_code != "" )
			echo esc_attr( $this->coupon_code );
		echo "\" /><div class=\"ec_cart_coupon_code_redeem_button\"><a href=\"#\" onclick=\"ec_cart_coupon_code_redeem(); return false;\">" . esc_attr( $redeem_text ) . "</a></div>";
	}

	public function display_coupon_input_text() {
		echo "<input type=\"text\" name=\"ec_cart_coupon_code\" id=\"ec_cart_coupon_code\" class=\"ec_cart_coupon_input_text\" value=\"";
		if ( $this->coupon_code != "" )
			echo esc_attr( $this->coupon_code );

		echo "\" />";
	}

	public function display_coupon_input_button( $redeem_text ) {
		echo "<div class=\"ec_cart_coupon_code_redeem_button\"><a href=\"#\" onclick=\"ec_cart_coupon_code_redeem(); return false;\">" . esc_attr( $redeem_text ) . "</a></div>";
	}

	public function display_coupon_loader() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif" ) )	
			echo "<div class=\"ec_cart_coupon_loader\" id=\"ec_cart_coupon_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" /></div>";	
		else
			echo "<div class=\"ec_cart_coupon_loader\" id=\"ec_cart_coupon_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DIRECTORY ) ) . "\" /></div>";
	}

	public function display_coupon_message() {
		if ( isset( $this->coupon ) )
			echo esc_attr( $this->coupon->message );
		else if ( $this->coupon_code != "" )
			echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' );
	}
	/* END COUPON FUNCTIONS */

	/* START GIFT CARD FUNCTIONS */
	public function display_gift_card() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_gift_card.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_gift_card.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_gift_card.php' );
		}
	}

	public function display_gift_card_input( $redeem_text ) {
		echo "<input type=\"text\" name=\"ec_cart_gift_card\" id=\"ec_cart_gift_card\" class=\"ec_cart_gift_card_input_text\" value=\"";
		if ( $this->gift_card != "" )
			echo esc_attr( $this->gift_card );
		echo "\" /><div class=\"ec_cart_gift_card_redeem_button\"><a href=\"#\" onclick=\"ec_cart_gift_card_redeem(); return false;\">" . esc_attr( $redeem_text ) . "</a></div>";
	}

	public function display_gift_card_input_text() {
		echo "<input type=\"text\" name=\"ec_cart_gift_card\" id=\"ec_cart_gift_card\" class=\"ec_cart_gift_card_input_text\" value=\"";
		if ( $this->gift_card != "" )
			echo esc_attr( $this->gift_card );

		echo "\" />";
	}

	public function display_gift_card_input_button( $redeem_text ) {
		echo "<div class=\"ec_cart_gift_card_redeem_button\"><a href=\"#\" onclick=\"ec_cart_gift_card_redeem(); return false;\">" . esc_attr( $redeem_text ) . "</a></div>";
	}

	public function display_gift_card_loader() {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif" ) )	
			echo "<div class=\"ec_cart_gift_card_loader\" id=\"ec_cart_gift_card_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" /></div>";
		else
			echo "<div class=\"ec_cart_gift_card_loader\" id=\"ec_cart_gift_card_loader\"><img src=\"" . esc_attr( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_page/loader.gif", EC_PLUGIN_DIRECTORY ) ) . "\" /></div>";

	}

	public function display_gift_card_message() {
		if ( isset( $this->giftcard ) )
			echo esc_attr( $this->giftcard->message );
		else if ( $this->gift_card != "" )
			echo wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_giftcard' );
	}
	/* END GIFT CARD FUNCTIONS */

	public function display_continue_to_shipping_button( $button_text ) {
		echo "<input type=\"submit\" class=\"ec_cart_continue_to_shipping_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cart_validate_checkout_info();\" />";
	}

	/* START CONTINUE TO PAYMENT FUNCTIONS */
	public function display_continue_to_payment_button( $button_text ) {
		echo "<input type=\"submit\" class=\"ec_cart_continue_to_payment_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cart_validate_checkout_shipping();\" />";
	}
	/* END CONTINUE TO PAYMENT FUNCTIONS */

	public function display_submit_order_button( $button_text ) {

		if ( isset( $_GET['subscription'] ) ) {
			echo "<input type=\"submit\" id=\"ec_submit_payment_button\" class=\"ec_cart_submit_order_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cart_validate_subscription_order();\" />";
		} else {
			echo "<input type=\"submit\" id=\"ec_submit_payment_button\" class=\"ec_cart_submit_order_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cart_validate_checkout_submit_order();\" />";
		}

	}

	public function display_cancel_order_button( $button_text ) {
		echo "<input type=\"button\" id=\"ec_cancel_payment_button\" class=\"ec_cart_submit_order_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_cart_cancel_order();\" />";
	}

	public function display_order_review_button( $button_text ) {
		echo "<input type=\"button\" id=\"ec_review_payment_button\" class=\"ec_cart_submit_order_button\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"if ( ec_cart_validate_checkout_submit_order() ) { ec_cart_show_review_panel(); } return false;\" />";
	}

	/* START ADDRESS REVIEW FUNCTIONS */
	public function display_address_review() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_address_review.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_address_review.php' );
			else	
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_address_review.php' );
		}

		if ( !get_option( 'ec_option_use_shipping' ) )
			echo "<script>jQuery('.ec_cart_address_review_middle').html('');</script>";
	}

	public function display_edit_address_link( $link_text ) {
		echo "<a href=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=checkout_info\">" . esc_attr( $link_text ) . "</a>";	
	}

	public function display_review_billing( $name ) {
		if ( $name == "first_name" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_first_name, ENT_QUOTES ) );
		else if ( $name == "last_name" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_last_name, ENT_QUOTES ) );
		else if ( $name == "address" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1, ENT_QUOTES ) );
		else if ( $name == "address2" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_address_line_2, ENT_QUOTES ) );
		else if ( $name == "city" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_city, ENT_QUOTES ) );
		else if ( $name == "state" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_state, ENT_QUOTES ) );
		else if ( $name == "zip" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_zip, ENT_QUOTES ) );
		else if ( $name == "country" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_country, ENT_QUOTES ) );
		else if ( $name == "phone" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->billing_phone, ENT_QUOTES ) );

	}

	public function has_billing_address_line2() {
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 != "" ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_review_shipping( $name ) {
		if ( $name == "first_name" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_first_name, ENT_QUOTES ) );
		else if ( $name == "last_name" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_last_name, ENT_QUOTES ) );
		else if ( $name == "address" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1, ENT_QUOTES ) );
		else if ( $name == "address2" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2, ENT_QUOTES ) );
		else if ( $name == "city" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_city, ENT_QUOTES ) );
		else if ( $name == "state" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_state, ENT_QUOTES ) );
		else if ( $name == "zip" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_zip, ENT_QUOTES ) );
		else if ( $name == "country" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_country, ENT_QUOTES ) );
		else if ( $name == "phone" )
			echo esc_attr( htmlspecialchars( $GLOBALS['ec_cart_data']->cart_data->shipping_phone, ENT_QUOTES ) );
	}

	public function has_shipping_address_line2() {
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 != "" ) {
			return true;
		} else {
			return false;
		}
	}

	public function display_selected_shipping_method() {
		echo wp_easycart_escape_html( $this->shipping->get_selected_shipping_method() ); // XSS OK.
	}
	/* END ADDRESS REVIEW FUNCTIONS */

	/* START PAYMENT INFORMATION FUNCTIONS */
	public function display_payment( $ideal_source = '', $ideal_client_secret = '' ) {
		do_action( 'wp_easycart_display_payment_pre' );
		if ( apply_filters( 'wp_easycart_onepage_checkout', false ) ) {
			$current_screen = 'payment';
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_checkout_v2.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_checkout_v2.php' );
			}
		} else {
			if ( $this->cart->total_items > 0 && apply_filters( 'wp_easycart_allow_payment', 1 ) ) {
				if ( isset( $_GET['PID'] ) && isset( $_GET['PYID'] ) && file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_express.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_express.php' );
				} else if ( isset( $_GET['PID'] ) && isset( $_GET['PYID'] ) ) {
						include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_paypal_express.php' );
				} else if ( get_option( 'ec_option_payment_third_party' ) == "paypal_advanced" ) {
					$this->payment->show_paypal_iframe( $this->order_totals->grand_total );
				} else if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment.php' ) ) {
					include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment.php' );
				} else {
					include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment.php' );
				}
			}
		}
		do_action( 'wp_easycart_display_payment_post' );
	}

	public function display_payment_paypal_express( $pid = false, $pyid = false, $oid = false ) {
		do_action( 'wp_easycart_display_payment_pre' );
		if (	$this->cart->total_items > 0 && apply_filters( 'wp_easycart_allow_payment', 1 ) ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_express.php' ) ) {
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_express.php' );
			} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_paypal_express.php' );
			}
		}
		do_action( 'wp_easycart_display_payment_post' );
	}

	public function display_payment_information() {
		if (	$this->cart->total_items > 0 && $this->order_totals->grand_total > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_information.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_payment_information.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_payment_information.php' );

			echo "<script>jQuery(\"input[name=ec_cart_payment_selection][value='" . esc_attr( get_option( 'ec_option_default_payment_type' ) ) . "']\").attr('checked', 'checked');";
			if ( get_option( 'ec_option_default_payment_type' ) == "manual_bill" ) {
				echo "jQuery('#ec_cart_pay_by_manual_payment').show();";
			} else if ( get_option( 'ec_option_default_payment_type' ) == "affirm" ) {
				echo "jQuery('#ec_cart_pay_by_affirm').show();";
			} else if ( get_option( 'ec_option_default_payment_type' ) == "third_party" ) {
				echo "jQuery('#ec_cart_pay_by_third_party').show();";
			} else if ( get_option( 'ec_option_default_payment_type' ) == "credit_card" ) {
				echo "jQuery('#ec_cart_pay_by_credit_card_holder').show();";
			}
			echo "</script>";
		}
	}

	public function use_manual_payment() {
		if ( get_option( 'ec_option_use_direct_deposit' ) )
			return true;
		else
			return false;
	}

	public function display_manual_payment_text() {
		echo nl2br( esc_attr( wp_easycart_language()->convert_text( get_option( 'ec_option_direct_deposit_message' ) ) ) );
	}

	public function use_third_party() {
		if ( get_option( 'ec_option_payment_third_party' ) )
			return true;
		else
			return false;
	}

	public function ec_cart_display_third_party_form_start() {
		$this->payment->third_party->initialize( (int) $_GET['order_id'] );
		$this->payment->third_party->display_form_start();
	}

	public function ec_cart_display_third_party_form_end() {
		echo "</form>";
	}

	public function display_third_party_submit_button( $button_text ) {
		echo "<input type=\"submit\" class=\"ec_cart_submit_third_party\" value=\"" . esc_attr( $button_text ) . "\" />";
	}

	public function ec_cart_display_current_third_party_name() {
		if ( get_option( 'ec_option_payment_third_party' ) == "2checkout_thirdparty" )
			echo "2Checkout";
		else if ( get_option( 'ec_option_payment_third_party' ) == "cashfree" )
			echo "Cashfree";
		else if ( get_option( 'ec_option_payment_third_party' ) == "dwolla_thirdparty" )
			echo "Dwolla";
		else if ( get_option( 'ec_option_payment_third_party' ) == "nets" )
			echo "Nets Netaxept";
		else if ( get_option( 'ec_option_payment_third_party' ) == "payfast_thirdparty" )
			echo "Payfast";
		else if ( get_option( 'ec_option_payment_third_party' ) == "payfort" )
			echo "Payfort";
		else if ( get_option( 'ec_option_payment_third_party' ) == "paypal" )
			echo "PayPal";
		else if ( get_option( 'ec_option_payment_third_party' ) == "sagepay_paynow_za" )
			echo "SagePay Pay Now";
		else if ( get_option( 'ec_option_payment_third_party' ) == "skrill" )
			echo "Skrill";
		else if ( get_option( 'ec_option_payment_third_party' ) == "realex_thirdparty" )
			echo "Realex Payments";
		else if ( get_option( 'ec_option_payment_third_party' ) == "redsys" )
			echo "Redsys";
		else if ( get_option( 'ec_option_payment_third_party' ) == "paymentexpress_thirdparty" )
			echo "Payment Express";
		else
			echo esc_attr( get_option( 'ec_option_custom_third_party' ) );
	}

	public function ec_cart_get_current_third_party_name() {
		if ( get_option( 'ec_option_payment_third_party' ) == "dwolla_thirdparty" )
			return "Dwolla";
		else if ( get_option( 'ec_option_payment_third_party' ) == "nets" )
			return "Nets Netaxept";
		else if ( get_option( 'ec_option_payment_third_party' ) == "paypal" )
			return "PayPal";
		else if ( get_option( 'ec_option_payment_third_party' ) == "sagepay_paynow_za" )
			echo "SagePay Pay Now";
		else if ( get_option( 'ec_option_payment_third_party' ) == "skrill" )
			return "Skrill";
		else if ( get_option( 'ec_option_payment_third_party' ) == "realex_thirdparty" )
			return "Realex Payments";
		else if ( get_option( 'ec_option_payment_third_party' ) == "redsys" )
			return "Redsys";
		else if ( get_option( 'ec_option_payment_third_party' ) == "paymentexpress_thirdparty" )
			return "Payment Express";
		else
			return get_option( 'ec_option_custom_third_party' );
	}

	public function ec_cart_display_third_party_logo() {
		if ( get_option( 'ec_option_payment_third_party' ) == "paypal" ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/paypal.jpg" ) )	
				echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/paypal.jpg", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" alt=\"PayPal\" />";
			else
				echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/paypal.jpg", EC_PLUGIN_DIRECTORY ) ) . "\" alt=\"PayPal\" />";
		} else if ( get_option( 'ec_option_payment_third_party' ) == "skrill" ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/layout/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/skrill-logo.gif" ) )	
				echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/skrill-logo.gif", EC_PLUGIN_DATA_DIRECTORY ) ) . "\" alt=\"Skrill\" />";
			else
				echo "<img src=\"" . esc_attr( plugins_url( "wp-easycart/design/theme/" . get_option( 'ec_option_base_theme' ) . "/ec_cart_payment_information/skrill-logo.gif", EC_PLUGIN_DIRECTORY ) ) . "\" alt=\"Skrill\" />";
		}
	}

	public function use_payment_gateway() {
		if ( get_option( 'ec_option_payment_process_method' ) )
			return true;
		else
			return false;
	}

	public function ec_cart_display_credit_card_images() {
		/* Fall Back only */
	}

	public function ec_cart_display_payment_method_input( $select_one_text ) {
		echo "<select name=\"ec_cart_payment_type\" id=\"ec_cart_payment_type\" class=\"ec_cart_payment_information_input_select no_wrap\">";

		echo "<option value=\"0\">" . esc_attr( $select_one_text ) . "</option>";

		if ( get_option('ec_option_use_visa') )
		echo "<option value=\"visa\">Visa</option>";

		if ( get_option('ec_option_use_delta') )
		echo "<option value=\"delta\">Visa Debit/Delta</option>";

		if ( get_option('ec_option_use_uke') )
		echo "<option value=\"uke\">Visa Electron</option>";

		if ( get_option('ec_option_use_discover') )
		echo "<option value=\"discover\">Discover</option>";

		if ( get_option('ec_option_use_mastercard') )
		echo "<option value=\"mastercard\">Mastercard</option>";

		if ( get_option('ec_option_use_mcdebit') )
		echo "<option value=\"mcdebit\">Debit Mastercard</option>";

		if ( get_option('ec_option_use_amex') )
		echo "<option value=\"amex\">American Express</option>";

		if ( get_option('ec_option_use_jcb') )
		echo "<option value=\"jcb\">JCB</option>";

		if ( get_option('ec_option_use_diners') )
		echo "<option value=\"diners\">Diners</option>";

		if ( get_option('ec_option_use_laser') )
		echo "<option value=\"laser\">Laser</option>";

		if ( get_option('ec_option_use_maestro') )
		echo "<option value=\"maestro\">Maestro</option>";

		echo "</select>";
	}

	public function ec_cart_display_card_holder_name_input() {
		echo "<input type=\"text\" name=\"ec_card_holder_name\" id=\"ec_card_holder_name\" class=\"ec_cart_payment_information_input_text\" value=\"\" />";
	}

	public function ec_cart_display_card_holder_name_hidden_input() {
		echo "<input type=\"hidden\" name=\"ec_card_holder_name\" id=\"ec_card_holder_name\" class=\"ec_cart_payment_information_input_text\" value=\"" . esc_attr( htmlspecialchars( $GLOBALS['ec_user']->billing->first_name, ENT_QUOTES ) . " " . htmlspecialchars( $GLOBALS['ec_user']->billing->last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_card_number_input() {
		if ( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ) {
			echo "<input type=\"text\" name=\"ec_card_number\" data-eway-encrypt-name=\"ec_card_number\" id=\"ec_card_number\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
		} else {
			echo "<input type=\"text\" name=\"ec_card_number\" id=\"ec_card_number\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
		}
	}

	public function ec_cart_display_card_expiration_month_input( $select_text ) {
		echo "<select name=\"ec_expiration_month\" id=\"ec_expiration_month\" class=\"ec_cart_payment_information_input_select no_wrap\" autocomplete=\"off\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for( $i=1; $i<=12; $i++ ) {
			echo "<option value=\"";
			if ( $i<10 )										$month = "0" . $i;
			else											$month = $i;
			echo esc_attr( $month ) . "\">" . esc_attr( $month ) . "</option>";
		}
		echo "</select>";
	}

	public function ec_cart_display_card_expiration_year_input( $select_text ) {
		echo "<select name=\"ec_expiration_year\" id=\"ec_expiration_year\" class=\"ec_cart_payment_information_input_select no_wrap\" autocomplete=\"off\">";
		echo "<option value=\"0\">" . esc_attr( $select_text ) . "</option>";
		for( $i=date( 'Y' ); $i < date( 'Y' ) + 15; $i++ ) {
			echo "<option value=\"" . esc_attr( $i ) . "\">" . esc_attr( $i ) . "</option>";	
		}
		echo "</select>";
	}

	public function ec_cart_display_card_security_code_input() {
		if ( get_option( 'ec_option_payment_process_method' ) == "eway" && get_option( 'ec_option_eway_use_rapid_pay' ) ) {
			echo "<input type=\"text\" name=\"ec_security_code\" data-eway-encrypt-name=\"ec_security_code\" id=\"ec_security_code\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
		} else {
			echo "<input type=\"text\" name=\"ec_security_code\" id=\"ec_security_code\" class=\"ec_cart_payment_information_input_text\" value=\"\" autocomplete=\"off\" />";
		}
	}
	/* END PAYMENT INFORMATION FUNCTIONS */

	/* START CONTACT INFORMATION FUNCTIONS */
	public function display_contact_information() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_contact_information.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_contact_information.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_contact_information.php' );
		}
	}

	public function ec_cart_display_contact_first_name_input() {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		if ( $GLOBALS['ec_cart_data']->cart_data->first_name != "" )
			$first_name = $GLOBALS['ec_cart_data']->cart_data->first_name;
		else
			$first_name = $GLOBALS['ec_user']->first_name;

		if ( $first_name == "guest" )
			$first_name = "";

		echo "<input type=\"text\" name=\"ec_contact_first_name\" id=\"ec_contact_first_name\" class=\"ec_cart_contact_information_input_text" . esc_attr( $auto_validate_css ) . "\" value=\"" . esc_attr( htmlspecialchars( $first_name, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_contact_last_name_input() {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		if ( $GLOBALS['ec_cart_data']->cart_data->last_name != "" )
			$last_name = $GLOBALS['ec_cart_data']->cart_data->last_name;
		else
			$last_name = $GLOBALS['ec_user']->last_name;

		if ( $last_name == "guest" )
			$last_name = "";

		echo "<input type=\"text\" name=\"ec_contact_last_name\" id=\"ec_contact_last_name\" class=\"ec_cart_contact_information_input_text" . esc_attr( $auto_validate_css ) . "\" value=\"" . esc_attr( htmlspecialchars( $last_name, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_contact_email_input() {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		if ( $GLOBALS['ec_cart_data']->cart_data->email != "" )
			$email = $GLOBALS['ec_cart_data']->cart_data->email;
		else
			$email = $GLOBALS['ec_user']->email;

		if ( $email == "guest" )
			$email = "";

		echo "<input type=\"text\" name=\"ec_contact_email\" id=\"ec_contact_email\" class=\"ec_cart_contact_information_input_text" . esc_attr( $auto_validate_css ) . "\" value=\"" . esc_attr( htmlspecialchars( $email, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_contact_email_retype_input() {
		if ( $GLOBALS['ec_cart_data']->cart_data->email != "" )
			$email = $GLOBALS['ec_cart_data']->cart_data->email;
		else
			$email = $GLOBALS['ec_user']->email;

		if ( $email == "guest" )
			$email = "";

		echo "<input type=\"text\" name=\"ec_contact_email_retype\" id=\"ec_contact_email_retype\" class=\"ec_cart_contact_information_input_text\" value=\"" . esc_attr( htmlspecialchars( $email, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_contact_email_other_input() {
		if ( '' != $GLOBALS['ec_cart_data']->cart_data->email_other ) {
			$email_other = $GLOBALS['ec_cart_data']->cart_data->email_other;
		} else if( '' != $GLOBALS['ec_user']->email_other ) {
			$email_other = $GLOBALS['ec_user']->email_other;
		} else {
			$email_other = '';
		}

		echo "<input type=\"text\" name=\"ec_email_other\" id=\"ec_email_other\" class=\"ec_cart_contact_information_input_text\" value=\"" . esc_attr( htmlspecialchars( $email_other, ENT_QUOTES ) ) . "\" />";
	}

	public function ec_cart_display_contact_create_account_box() {
		echo "<input type=\"checkbox\" name=\"ec_contact_create_account\" id=\"ec_contact_create_account\" onchange=\"ec_contact_create_account_change();\"";
		if ( $GLOBALS['ec_cart_data']->cart_data->create_account != "" )
			echo " checked=\checked\"";
		echo " />";

		if ( !get_option( 'ec_option_allow_guest' ) ) {
			echo "<script>jQuery('#ec_contact_create_account').hide(); jQuery('#ec_contact_create_account').attr('checked', true);</script>";
		}
	}

	public function ec_cart_display_contact_password_input() {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		echo "<input type=\"password\" name=\"ec_contact_password\" id=\"ec_contact_password\" class=\"ec_cart_contact_information_input_text" . esc_attr( $auto_validate_css ) . "\" />";
	}

	public function ec_cart_display_contact_password_retype_input() {
		$auto_validate_css = ( get_option( 'ec_option_onepage_checkout' ) ) ? ' ec_cart_auto_validate_v2' : '';
		echo "<input type=\"password\" name=\"ec_contact_password_retype\" id=\"ec_contact_password_retype\" class=\"ec_cart_contact_information_input_text" . esc_attr( $auto_validate_css ) . "\" />";
	}

	public function ec_cart_display_contact_is_subscriber_input() {
		echo "<input type=\"checkbox\" name=\"ec_contact_is_subscriber\" id=\"ec_contact_is_subscriber\" />";
	}
	/* END CONTACT INFORMATION FUNCTIONS */

	/* START SUBMIT ORDER DISPLAY FUNCTIONS */
	public function display_submit_order() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_submit_order.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_submit_order.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_submit_order.php' );
		}
	}

	public function display_customer_order_notes() {
		if ( get_option( 'ec_option_user_order_notes' ) ) {
			echo "<div class=\"ec_cart_payment_information_title\">" . wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_title' ) . "</div>";
			echo "<div class=\"ec_cart_submit_order_message\">" . wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_information_order_notes_message' ) . "</div>";	
			echo "<div class=\"ec_cart_payment_information_row\"><textarea name=\"ec_order_notes\" id=\"ec_order_notes\">";
			if ( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" )
				echo esc_textarea( $GLOBALS['ec_cart_data']->cart_data->order_notes );

			echo "</textarea></div><hr />";
		}
	}

	public function display_order_finalize_panel() {
		if (	$this->cart->total_items > 0 ) {
			if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_finalize_order.php' ) )	
				include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_finalize_order.php' );
			else
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_finalize_order.php' );
		}
	}

	public function display_ajax_loader( $img ) {
		/* Fall back only */
	}
	/* END SUBMIT ORDER DISPLAY FUNCTIONS */

	/* START SUCCESS PAGE FUNCTIONS */
	public function display_print_receipt_link( $link_text, $order_id ) {
		if ( substr_count( $this->account_page, '?' ) )				$permalink_divider = "&";
		else														$permalink_divider = "?";

		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest == "" ) {
			echo "<a href=\"" . esc_attr( $this->account_page . $permalink_divider ) . "ec_page=print_receipt&order_id=" . esc_attr( $order_id ) . "\" target=\"_blank\">" . wp_easycart_escape_html( $link_text ) . "</a>";
		} else {
			echo "<a href=\"" . esc_attr( $this->account_page . $permalink_divider ) . "ec_page=print_receipt&order_id=" . esc_attr( $order_id ) . "&guest_key=" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->guest_key ) . "\" target=\"_blank\">" . wp_easycart_escape_html( $link_text ) . "</a>";
		}
	}

	public function get_printer_icon( $image_name ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/theme/' . get_option( 'ec_option_base_theme' ) . '/images/' . $image_name ) )	
			return plugins_url( 'wp-easycart-data/design/theme/' . get_option( 'ec_option_base_themet' ) . '/images/' . $image_name, EC_PLUGIN_DATA_DIRECTORY );
		else
			return plugins_url( 'wp-easycart/design/theme/' . get_option( 'ec_option_latest_theme' ) . '/images/' . $image_name, EC_PLUGIN_DIRECTORY );
	}

	public function display_success_account_create_form_start( $order_id, $email ) {
		echo "<form action=\"" . esc_attr( $this->account_page . $this->permalink_divider ) . "ec_page=order_details&order_id=" . esc_attr( $order_id ) . "\" method=\"POST\">";
		echo "<input type=\"hidden\" value=\"order_create_account\" name=\"ec_account_form_action\" />";
		echo "<input type=\"hidden\" value=\"" . esc_attr( $order_id ) . "\" name=\"order_id\" />";
		echo "<input type=\"hidden\" value=\"" . esc_attr( $email ) . "\" name=\"email_address\" />";
	}

	public function display_success_create_password() {
		echo "<input type=\"password\" name=\"ec_password\" id=\"ec_password\" />";
	}

	public function display_success_verify_password() {
		echo "<input type=\"password\" name=\"ec_verify_password\" id=\"ec_verify_password\" />";
	}

	public function display_success_account_create_submit_button( $button_text ) {
		echo "<input type=\"submit\" value=\"" . esc_attr( $button_text ) . "\" onclick=\"return ec_check_success_passwords();\" />";
	}

	public function display_success_account_create_form_end() {
		echo "</form>";
	}
	/* END SUCCESS PAGE FUNCTIONS */

	/* START FORM PROCESSING FUNCTIONS */
	// Process the cart page form action
	public function process_form_action( $action ) {
		wpeasycart_session()->handle_session();
		if ( $action == "add_to_cart" )								$this->process_add_to_cart();
		else if ( $action == "add_to_cart_v3" )						$this->process_add_to_cart_v3();
		else if ( $action == "ec_update_action" )					$this->process_update_cartitem( sanitize_text_field( $_POST['ec_update_cartitem_id'] ), (int) $_POST['ec_cartitem_quantity_' . (int) $_POST['ec_update_cartitem_id'] ] );
		else if ( $action == "ec_delete_action" )					$this->process_delete_cartitem( sanitize_text_field( $_POST['ec_delete_cartitem_id'] ) );
		else if ( $action == "submit_order" )						$this->process_submit_order();
		else if ( $action == "3dsecure" )							$this->process_3dsecure_response();
		else if ( $action == "3ds" )									$this->process_3ds_response();
		else if ( $action == "3dsprocess" )							$this->process_3ds_final();
		else if ( $action == "third_party_forward" )					$this->process_third_party_forward();
		else if ( $action == "login_user" )							$this->process_login_user();
		else if ( $action == "save_checkout_info" )					$this->process_save_checkout_info();
		else if ( $action == "save_checkout_shipping" )				$this->process_save_checkout_shipping();
		else if ( $action == "logout" )								$this->process_logout_user();
		else if ( $action == "realex_redirect" )						$this->process_realex_redirect();
		else if ( $action == "realex_response" )						$this->process_realex_response();
		else if ( $action == "paymentexpress_thirdparty_response" )	$this->process_paymentexpress_thirdparty_response();
		else if ( $action == "purchase_subscription" )				$this->process_purchase_subscription();
		else if ( $action == "insert_subscription" )					$this->process_insert_subscription();
		else if ( $action == "send_inquiry" )						$this->process_send_inquiry();
		else if ( $action == "deconetwork_add_to_cart" )				$this->process_deconetwork_add_to_cart();
		else if ( $action == "subscribe_v3" )						$this->process_subscribe_v3();
		else if ( $action == "process_update_subscription_quantity" )$this->process_update_subscription_quantity();
		else if ( $action == "stripe_redirect_action" )				$this->process_stripe_redirect_action();
	}

	// Process the add to cart form submission
	private function process_add_to_cart() {

		if ( !$this->check_quantity( (int) $_POST['product_id'], (int) $_POST['product_quantity'] ) ) {
			header( "location: " . $this->store_page . $this->permalink_divider . "model_number=" . sanitize_text_field( $_POST['model_number'] ) . "&ec_store_error=minquantity" );

		} else {

			//add_to_cart_replace Hook
			if ( isset( $GLOBALS['ec_hooks']['add_to_cart_replace'] ) ) {
				$class_args = array( "cart_page" => $this->cart_page, "permalink_divider" => $this->permalink_divider );
				for( $i=0; $i<count( $GLOBALS['ec_hooks']['add_to_cart_replace'] ); $i++ ) {
					ec_call_hook( $GLOBALS['ec_hooks']['add_to_cart_replace'][$i], $class_args );
				}
			} else {
				//Product Info
				$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;
				$product_id = (int) $_POST['product_id'];
				if ( isset( $_POST['product_quantity'] ) )
					$quantity = (int) $_POST['product_quantity'];
				else
					$quantity = 1;

				$model_number = stripslashes( sanitize_text_field( $_POST['model_number'] ) );

				//Optional Gift Card Info
				$gift_card_message = "";
				if ( isset( $_POST['ec_gift_card_message'] ) )
					$gift_card_message = stripslashes( sanitize_textarea_field( $_POST['ec_gift_card_message'] ) );

				$gift_card_to_name = "";
				if ( isset( $_POST['ec_gift_card_to_name'] ) )
					$gift_card_to_name = stripslashes( sanitize_text_field( $_POST['ec_gift_card_to_name'] ) );

				$gift_card_from_name = "";
				if ( isset( $_POST['ec_gift_card_from_name'] ) )
					$gift_card_from_name = stripslashes( sanitize_text_field( $_POST['ec_gift_card_from_name'] ) );

				// Optional Donation Price
				$donation_price = 0.000;
				if ( isset( $_POST['ec_product_input_price'] ) )
					$donation_price = sanitize_text_field( $_POST['ec_product_input_price'] );

				$use_advanced_optionset = false;
				//Product Options
				if ( isset( $_POST['ec_use_advanced_optionset'] ) && (bool) $_POST['ec_use_advanced_optionset'] ) {
					$option1 = "";
					$option2 = "";
					$option3 = "";
					$option4 = "";
					$option5 = "";
					$use_advanced_optionset = true;
				} else {
					$option1 = "";
					if ( isset( $_POST['ec_option1'] ) )
						$option1 = (int) $_POST['ec_option1'];

					$option2 = "";
					if ( isset( $_POST['ec_option2'] ) )
						$option2 = (int) $_POST['ec_option2'];

					$option3 = "";
					if ( isset( $_POST['ec_option3'] ) )
						$option3 = (int) $_POST['ec_option3'];

					$option4 = "";
					if ( isset( $_POST['ec_option4'] ) )
						$option4 = (int) $_POST['ec_option4'];

					$option5 = "";
					if ( isset( $_POST['ec_option5'] ) )
						$option5 = (int) $_POST['ec_option5'];

				}

				$tempcart_id = $this->mysqli->add_to_cart( $product_id, $session_id, $quantity, $option1, $option2, $option3, $option4, $option5, $gift_card_message, $gift_card_to_name, $gift_card_from_name, $donation_price, $use_advanced_optionset, false );

				$option_vals = array();
				// Now insert the advanced option set tempcart table if needed
				if ( $use_advanced_optionset ) {

					$optionsets = $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $product_id );
					$grid_quantity = 0;

					foreach( $optionsets as $optionset ) {
						if ( $optionset->option_type == "checkbox" ) {
							$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								if ( isset( $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] ) ) {
									$option_vals[] = array( 
										"option_id" => $optionset->option_id, 
										"optionitem_id" => $optionitem->optionitem_id, 
										"option_name" => $optionitem->option_name, 
										"optionitem_name" => $optionitem->optionitem_name, 
										"option_type" => $optionitem->option_type, 
										"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] ) ), 
										"optionitem_model_number" => $optionitem->optionitem_model_number
									);
								}
							}
						} else if ( $optionset->option_type == "grid" ) {
							$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								if ( isset( $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] ) && $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] > 0 ) {
									$grid_quantity = $grid_quantity + (int) $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id];
									$option_vals[] = array( 
										"option_id" => $optionset->option_id, 
										"optionitem_id" => $optionitem->optionitem_id, 
										"option_name" => $optionitem->option_name, 
										"optionitem_name" => $optionitem->optionitem_name, 
										"option_type" => $optionitem->option_type, 
										"optionitem_value" => sanitize_text_field( $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] ), 
										"optionitem_model_number" => $optionitem->optionitem_model_number 
									);
								}
							}
						} else if ( $optionset->option_type == "combo" || $optionset->option_type == "swatch" || $optionset->option_type == "radio" ) {
							$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								if ( $optionitem->optionitem_id == $_POST['ec_option_' . $optionset->option_id] ) {
									$option_vals[] = array( 
										"option_id" => $optionset->option_id, 
										"optionitem_id" => $optionitem->optionitem_id, 
										"option_name" => $optionitem->option_name, 
										"optionitem_name" => $optionitem->optionitem_name, 
										"option_type" => $optionitem->option_type, 
										"optionitem_value" => $optionitem->optionitem_name, 
										"optionitem_model_number" => $optionitem->optionitem_model_number 
									);
								}
							}
						} else if ( $optionset->option_type == "file" ) {
							$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								$option_vals[] = array( 
									"option_id" => $optionset->option_id, 
									"optionitem_id" => $optionitem->optionitem_id, 
									"option_name" => $optionitem->option_name, 
									"optionitem_name" => $optionitem->optionitem_name, 
									"option_type" => $optionitem->option_type, 
									"optionitem_value" => stripslashes( sanitize_text_field( $_FILES['ec_option_' . $optionset->option_id]['name'] ) ), 
									"optionitem_model_number" => $optionitem->optionitem_model_number 
								);
							}
						} else {
							$optionitems = $this->mysqli->get_advanced_optionitems( $optionset->option_id );
							foreach( $optionitems as $optionitem ) {
								$option_vals[] = array( 
									"option_id" => $optionset->option_id, 
									"optionitem_id" => $optionitem->optionitem_id, 
									"option_name" => $optionitem->option_name, 
									"optionitem_name" => $optionitem->optionitem_name, 
									"option_type" => $optionitem->option_type, 
									"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_' . $optionset->option_id] ) ), 
									"optionitem_model_number" => $optionitem->optionitem_model_number 
								);
							}
						}

						if ( $optionset->option_type == "file" ) {
							//upload the file
							$this->upload_customer_file( $tempcart_id, 'ec_option_' . $optionset->option_id );
						}
					}
				}

				for( $i=0; $i<count( $option_vals ); $i++ ) {
					$this->mysqli->add_option_to_cart( $tempcart_id, $GLOBALS['ec_cart_data']->ec_cart_id, $option_vals[$i] );
				}

				if ( $grid_quantity > 0 ) {
					$this->mysqli->update_tempcart_grid_quantity( $tempcart_id, $grid_quantity );
				}

				if ( get_option( 'ec_option_addtocart_return_to_product' ) ) {
					$return_url = esc_url_raw( $_SERVER['HTTP_REFERER'] );
					$return_url = str_replace( "ec_store_success=addtocart", "", $return_url );
					$divider = "?";
					if ( substr_count( $return_url, '?' ) )
						$divider = "&";

					do_action( 'wpeasycart_cart_updated' );


					header( "location: " . $return_url . $divider . "ec_store_success=addtocart&model=" . sanitize_text_field( $_POST['model_number'] ) );
				} else {
					header( "location: " . $this->cart_page );
				}
			}
		}
	}

	private function send_inquiry( $product ) {
		$inquiry_name = ( isset( $_POST['ec_inquiry_name'] ) ) ? stripslashes( sanitize_text_field( $_POST['ec_inquiry_name'] ) ) : "";
		$inquiry_email = ( isset( $_POST['ec_inquiry_email'] ) ) ? stripslashes( sanitize_email( $_POST['ec_inquiry_email'] ) ) : "";
		$inquiry_message = ( isset( $_POST['ec_inquiry_message'] ) ) ? stripslashes( sanitize_textarea_field( $_POST['ec_inquiry_message'] ) ) : "";
		$send_copy = ( isset( $_POST['ec_inquiry_send_copy'] ) ) ? true : false;
		$has_product_options = false;

		$option1 = $option2 = $option3 = $option4 = $option5 = "";
		$optionitem_list = (array) $GLOBALS['ec_options']->optionitems;

		if ( ! $product->use_advanced_optionset ) {
			if ( isset( $_POST['ec_option1'] ) ) {
				$option1 = (int) $_POST['ec_option1'];
			}
			if ( isset( $_POST['ec_option2'] ) ) {
				$option2 = (int) $_POST['ec_option2'];
			}
			if ( isset( $_POST['ec_option3'] ) ) {
				$option3 = (int) $_POST['ec_option3'];
			}
			if ( isset( $_POST['ec_option4'] ) ) {
				$option4 = (int) $_POST['ec_option4'];
			}
			if ( isset( $_POST['ec_option5'] ) ) {
				$option5 = (int) $_POST['ec_option5'];
			}

			if ( isset( $_POST['ec_option1'] ) || isset( $_POST['ec_option2'] ) || isset( $_POST['ec_option3'] ) || isset( $_POST['ec_option4'] ) || isset( $_POST['ec_option5'] ) ) {
				$has_product_options = true;
			}
		}

		foreach ( $optionitem_list as $optionitem ) {
			if ( $option1 == $optionitem->optionitem_id ) {
				$option1 = wp_easycart_escape_html( $optionitem->optionitem_name );
			} else if ( $option2 == $optionitem->optionitem_id ) {
				$option2 = wp_easycart_escape_html( $optionitem->optionitem_name );
			} else if ( $option3 == $optionitem->optionitem_id ) {
				$option3 = wp_easycart_escape_html( $optionitem->optionitem_name );
			} else if ( $option4 == $optionitem->optionitem_id ) {
				$option4 = wp_easycart_escape_html( $optionitem->optionitem_name );
			} else if ( $option5 == $optionitem->optionitem_id ) {
				$option5 = wp_easycart_escape_html( $optionitem->optionitem_name );
			}
		}

		if ( $product->use_advanced_optionset ) {
			$file_temp_num = rand( 1000000, 999999999 );
			$option_vals = $this->get_advanced_option_vals( $product->product_id, $file_temp_num );
		}

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

		$filter_options = (object) array(
			'product' => $product,
			'inquiry_name' => $inquiry_name,
			'inquiry_email' => $inquiry_email,
			'inquiry_message' => $inquiry_message,
			'send_copy' => $send_copy,
			'option1' => $option1,
			'option2' => $option2,
			'option3' => $option3,
			'option4' => $option4,
			'option5' => $option5,
			'file_temp_num' => $file_temp_num,
			'option_vals' => $option_vals,
			'email_logo_url' => $email_logo_url,
			'store_page' => $store_page,
			'permalink_divider' => $permalink_divider,
		);

		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-Type: text/html; charset=utf-8";
		$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
		$headers[] = "Reply-To: " . $inquiry_email;
		$headers[] = "X-Mailer: PHP/".phpversion();

		ob_start();
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_inquiry_email.php' ) ) {
			include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_inquiry_email.php';	
		} else {
			include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_inquiry_email.php';
		}
		$message = $admin_message = ob_get_clean();
		$message = apply_filters( 'wpeasycart_inquiry_email_content', $message, $filter_options );
		$admin_message = apply_filters( 'wpeasycart_inquiry_email_admin_content', $admin_message, $filter_options );
		$subject = $admin_subject = wp_easycart_language( )->get_text( 'product_details', 'product_details_inquiry_email_title' ); //"New Product Inquiry";
		$subject = apply_filters( 'wpeasycart_inquiry_email_subject', $subject );
		$admin_subject = apply_filters( 'wpeasycart_inquiry_email_admin_subject', $admin_subject );

		if ( get_option( 'ec_option_use_wp_mail' ) ) {
			if ( $send_copy ) {
				wp_mail( $inquiry_email, $subject, $message, implode("\r\n", $headers) );
			}
			wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $admin_title, $admin_message, implode("\r\n", $headers) );
		} else {
			$mailer = new wpeasycart_mailer();
			if ( $send_copy ) {
				$mailer->send_order_email( $inquiry_email, $subject, $message );
			}
			$mailer->send_order_email( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $admin_title, $admin_message );
		}

		header( "location: " . $this->store_page . $this->permalink_divider . "model_number=" . $product->model_number . "&ec_store_success=inquiry_sent" );
	}

	private function get_advanced_option_vals( $product_id, $tempcart_id ) {

		$option_vals = array();
		$optionsets = (array) $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $product_id );
		$grid_quantity = 0;

		foreach( $optionsets as $optionset ) {

			$optionitems = (array) $optionset->option_items;

			if ( $optionset->option_type == "checkbox" ) {
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id] ) ) {
						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id, 
							"option_label" => wp_easycart_escape_html( $optionset->option_label ), 
							"option_name" => sanitize_text_field( $optionset->option_name ), 
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ), 
							"optionitem_id" => (int) $optionitem->optionitem_id, 
							"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id] ) ), 
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id] ) ) {
						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id] ) ), 
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);
					}
				}

			} else if ( $optionset->option_type == "grid" ) {
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id] ) && (int) $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id] > 0 ) {
						$grid_quantity = $grid_quantity + (int) $_POST['ec_option_' . $optionset->option_id . "_" . $optionitem->optionitem_id];
						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id, 
							"option_label" => wp_easycart_escape_html( $optionset->option_label ), 
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => (int) $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id], 
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_POST['ec_option_adv_' . $optionset->option_to_product_id . "_" . $optionitem->optionitem_id] ) && (int) $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id] > 0 ) {
						$grid_quantity = $grid_quantity + (int) $_POST['ec_option_adv_' . $optionset->option_to_product_id . "_" . $optionitem->optionitem_id];
						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => (int) $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id],
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);
					}
				}

			} else if ( $optionset->option_type == "combo" || $optionset->option_type == "swatch" || $optionset->option_type == "radio" ) {
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_POST['ec_option_' . (int) $optionset->option_id] ) && $optionitem->optionitem_id == (int) $_POST['ec_option_' . (int) $optionset->option_id] ) {
						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => sanitize_text_field( $optionitem->optionitem_name ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id] ) && $optionitem->optionitem_id == (int) $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id] ) {
						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => sanitize_text_field( $optionitem->optionitem_name ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);
					}
				}

			} else if ( $optionset->option_type == "file" ) {
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_FILES['ec_option_' . (int) $optionset->option_id] ) ) {
						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => sanitize_text_field( $_FILES['ec_option_' . (int) $optionset->option_id]['name'] ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_FILES['ec_option_adv_' . (int) $optionset->option_to_product_id] ) ) {
						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => sanitize_text_field( $_FILES['ec_option_adv_' . (int) $optionset->option_to_product_id]['name'] ), 
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);
					}
				}

			} else if ( $optionset->option_type == "dimensions1" || $optionset->option_type == "dimensions2" ) {
				foreach( $optionitems as $optionitem ) {

					if ( isset( $_POST['ec_option_' . (int) $optionset->option_id . '_width'] ) ) {
						$vals = array();
						$vals[] = sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id . '_width'] );

						if ( isset( $_POST['ec_option_' . (int) $optionset->option_id . '_sub_width'] ) ) {
							$vals[] = sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id . '_sub_width'] );

						}

						$vals[] = sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id . '_height'] );

						if ( isset( $_POST['ec_option_' . (int) $optionset->option_id . '_sub_height'] ) ) {
							$vals[] = sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id . '_sub_height'] );

						}

						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => json_encode( $vals ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_width'] ) ) {
						$vals = array();
						$vals[] = sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_width'] );

						if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_sub_width'] ) ) {
							$vals[] = sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_sub_width'] );

						}

						$vals[] = sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_height'] );

						if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_sub_height'] ) ) {
							$vals[] = sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . '_sub_height'] );

						}

						$option_vals[] = array( 
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => json_encode( $vals ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					}
				}

			} else {
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_POST['ec_option_' . (int) $optionset->option_id] ) && '' != $_POST['ec_option_' . (int) $optionset->option_id] ) {
						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_' . (int) $optionset->option_id] ) ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					} else if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id] ) && '' != $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id] ) {
						$option_vals[] = array(
							"option_id" => (int) $optionset->option_id,
							"option_label" => wp_easycart_escape_html( $optionset->option_label ),
							"option_name" => sanitize_text_field( $optionset->option_name ),
							"optionitem_name" => wp_easycart_escape_html( $optionitem->optionitem_name ),
							"option_type" => sanitize_text_field( $optionset->option_type ),
							"optionitem_id" => (int) $optionitem->optionitem_id,
							"optionitem_value" => stripslashes( sanitize_text_field( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id] ) ),
							"optionitem_model_number" => sanitize_text_field( $optionitem->optionitem_model_number )
						);

					}
				}
			}

			if ( $optionset->option_type == "file" ) {
				if ( isset( $_FILES['ec_option_' . (int) $optionset->option_id] ) ) {
					$this->upload_customer_file( $tempcart_id, 'ec_option_' . (int) $optionset->option_id );

				} else if ( isset( $_FILES['ec_option_adv_' . (int) $optionset->option_to_product_id] ) ) {
					$this->upload_customer_file( $tempcart_id, 'ec_option_adv_' . (int) $optionset->option_to_product_id );
				}
			}
		}
		return $option_vals;

	}

	private function get_grid_quantity( $product_id, $tempcart_id ) {

		$optionsets = (array) $GLOBALS['ec_advanced_optionsets']->get_advanced_optionsets( $product_id );
		$grid_quantity = 0;
		foreach( $optionsets as $optionset ) {

			if ( sanitize_text_field( $optionset->option_type ) == "grid" ) {
				$optionitems = (array) $this->mysqli->get_advanced_optionitems( (int) $optionset->option_id );
				foreach( $optionitems as $optionitem ) {
					if ( isset( $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id] ) && (int) $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id] > 0 ) {
						$grid_quantity = $grid_quantity + (int) $_POST['ec_option_' . (int) $optionset->option_id . "_" . (int) $optionitem->optionitem_id];

					} else if ( isset( $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id] ) && (int) $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id] > 0 ) {
						$grid_quantity = $grid_quantity + (int) $_POST['ec_option_adv_' . (int) $optionset->option_to_product_id . "_" . (int) $optionitem->optionitem_id];
					}
				}
			}
		}
		return $grid_quantity;

	}

	private function process_add_to_cart_v3() {
		$product_id = (int) $_POST['product_id'];
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-add-to-cart-' . $product_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$cart_id = $GLOBALS['ec_cart_data']->ec_cart_id;
		$product = $this->mysqli->get_product( "", $product_id );

		if ( $product->inquiry_mode ) {
			$this->send_inquiry( $product );

		} else if ( $product->is_subscription_item ) { // && !class_exists( "ec_stripe" ) ) {

		} else {

			if ( isset( $_POST['ec_quantity'] ) ) {
				$quantity = (int) $_POST['ec_quantity'];
			} else {
				$quantity = 1;
			}

			//Optional Gift Card Info
			$gift_card_message = ( isset( $_POST['ec_giftcard_message'] ) ) ? stripslashes( sanitize_textarea_field( $_POST['ec_giftcard_message'] ) ) : "";
			$gift_card_to_name = ( isset( $_POST['ec_giftcard_to_name'] ) ) ? stripslashes( sanitize_text_field( $_POST['ec_giftcard_to_name'] ) ) : "";
			$gift_card_from_name = ( isset( $_POST['ec_giftcard_from_name'] ) ) ? stripslashes( sanitize_text_field( $_POST['ec_giftcard_from_name'] ) ) : "";
			$gift_card_email = ( isset( $_POST['ec_giftcard_to_email'] ) ) ? stripslashes( sanitize_email( $_POST['ec_giftcard_to_email'] ) ) : "";
			$donation_price = ( isset( $_POST['ec_donation_amount'] ) ) ? sanitize_text_field( $_POST['ec_donation_amount'] ) : 0.000;
			$use_advanced_optionset = (int) $product->use_advanced_optionset;
			$use_both_option_types = (int) $product->use_both_option_types;

			//Product Options
			$option1 = ( isset( $_POST['ec_option1'] ) ) ? (int) $_POST['ec_option1'] : 0;
			$option2 = ( isset( $_POST['ec_option2'] ) ) ? (int) $_POST['ec_option2'] : 0;
			$option3 = ( isset( $_POST['ec_option3'] ) ) ? (int) $_POST['ec_option3'] : 0;
			$option4 = ( isset( $_POST['ec_option4'] ) ) ? (int) $_POST['ec_option4'] : 0;
			$option5 = ( isset( $_POST['ec_option5'] ) ) ? (int) $_POST['ec_option5'] : 0;

			$option_vals = array();
			if ( $use_advanced_optionset || $use_both_option_types ) {
				$option_vals = $this->get_advanced_option_vals( $product_id, $cart_id );
			}

			$tempcart_id = $this->mysqli->add_to_cart( $product_id, $cart_id, $quantity, $option1, $option2, $option3, $option4, $option5, $gift_card_message, $gift_card_to_name, $gift_card_from_name, $donation_price, count( $option_vals ), false, $gift_card_email );

			// Now insert the advanced option set tempcart table if needed
			if ( $use_advanced_optionset || $use_both_option_types ) {
				$grid_quantity = $this->get_grid_quantity( $product_id, $tempcart_id );

				for( $i=0; $i<count( $option_vals ); $i++ ) {
					$this->mysqli->add_option_to_cart( $tempcart_id, $cart_id, $option_vals[$i] );
				}

				if ( $grid_quantity > 0 ) {
					$this->mysqli->update_tempcart_grid_quantity( $tempcart_id, $grid_quantity );
				}
			}

			do_action( 'wpeasycart_item_added_to_cart', $tempcart_id, $cart_id );
			do_action( 'wpeasycart_cart_updated' );

			if( isset( $_POST['noredirect'] ) && $_POST['noredirect'] == '1' ) {
				return;
			}

			if ( get_option( 'ec_option_addtocart_return_to_product' ) ) {
				$return_url = sanitize_text_field( $_SERVER['HTTP_REFERER'] );
				$return_url = str_replace( "ec_store_success=addtocart", "", $return_url );
				$divider = "?";
				if ( substr_count( $return_url, '?' ) )
					$divider = "&";

				header( "location: " . apply_filters( 'wp_easycart_add_to_cart_return_url_product', $return_url . $divider . "ec_store_success=addtocart&model=" . $product->model_number, $tempcart_id, $product_id ) );

			} else {
				header( "location: " . apply_filters( 'wp_easycart_add_to_cart_return_url_cart', $this->cart_page, $tempcart_id, $product_id ) );

			}

		}

	}

	private function check_quantity( $product_id, $quantity ) {

		global $wpdb;
		$min_quantity = $wpdb->get_var( $wpdb->prepare( "SELECT ec_product.min_purchase_quantity FROM ec_product WHERE ec_product.product_id = %d", $product_id ) );

		if ( $min_quantity > 0 ) {
			$current_amount = $quantity;
			foreach( $this->cart->cart as $cartitem ) {
				if ( $cartitem->product_id == $product_id ) {
					$current_amount = $current_amount + $cartitem->quantity;
				}
			}

			if ( $min_quantity <= $current_amount ) {
				return true;

			} else {
				return false;

			}


		} else {
			return true;
		}

	}

	private function process_update_cartitem( $cartitem_id, $new_quantity ) {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-update-item-' . $cartitem_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$this->mysqli->update_cartitem( $cartitem_id, $GLOBALS['ec_cart_data']->ec_cart_id, $new_quantity );

		do_action( 'wpeasycart_cart_updated' );

		if ( isset( $_GET['ec_page'] ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=" . htmlspecialchars( sanitize_key( $_GET['ec_page'] ), ENT_QUOTES ) );	
		} else {
			header( "location: " . $this->cart_page );
		}
	}

	private function process_delete_cartitem( $cartitem_id ) {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-delete-item-' . $cartitem_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$this->mysqli->delete_cartitem( $cartitem_id, $GLOBALS['ec_cart_data']->ec_cart_id );

		do_action( 'wpeasycart_cart_updated' );

		if ( isset( $_GET['ec_page'] ) )
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=" . htmlspecialchars( sanitize_key( $_GET['ec_page'] ), ENT_QUOTES ) );	
		else
			header( "location: " . $this->cart_page );
	}

	private function validate_submit_order_data() {

		$data_validated = true;

		// Basic Validation
		if ( $GLOBALS['ec_cart_data']->cart_data->billing_country == "0" || $GLOBALS['ec_cart_data']->cart_data->billing_first_name == "" || $GLOBALS['ec_cart_data']->cart_data->billing_last_name == "" || $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 == "" || $GLOBALS['ec_cart_data']->cart_data->billing_city == "" || $GLOBALS['ec_cart_data']->cart_data->email == "" ) {
			$data_validated =  false;

		}

		$data_validated = apply_filters( 'wpeasycart_validate_submit_order_data', $data_validated, $GLOBALS['ec_user'] );

		return $data_validated;

	}

	private function validate_checkout_data() {

		$data_validated = true;

		// Basic Validation
		if ( $GLOBALS['ec_cart_data']->cart_data->billing_country == "0" || $GLOBALS['ec_cart_data']->cart_data->billing_first_name == "" || $GLOBALS['ec_cart_data']->cart_data->billing_last_name == "" || $GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 == "" || $GLOBALS['ec_cart_data']->cart_data->billing_city == "" || $GLOBALS['ec_cart_data']->cart_data->email == "" ) {
			$data_validated =  false;

		}

		$data_validated = apply_filters( 'wpeasycart_validate_checkout_data', $data_validated, $GLOBALS['ec_user'] );

		return $data_validated;

	}
	
	public function validate_cart_shipping() {
		global $wpdb;
		$is_cart_shipping_valid = true;
		$user_zones = $this->mysqli->get_zone_ids( $GLOBALS['ec_cart_data']->cart_data->shipping_country, $GLOBALS['ec_cart_data']->cart_data->shipping_state );
		for ( $i = 0; $i <count( $this->cart->cart ); $i++ ) {
			if ( '0' != $this->cart->cart[$i]->shipping_restriction ) {
				$zone_found = false;
				for( $j = 0; $j < count( $user_zones ); $j++ ) {
					if ( $this->cart->cart[$i]->shipping_restriction == $user_zones[$j]->zone_id ) {
						$zone_found = true;
					}
				}
				if ( ! $zone_found ) {
					$is_cart_shipping_valid = false;
				}
			}
		}
		return $is_cart_shipping_valid;
	}

	private function validate_tax_cloud() {

		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_country == "US" && get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ) {

			return $GLOBALS['ec_cart_data']->cart_data->taxcloud_address_verified;

		} else {

			return true;
		}

	}

	private function validate_tax_jar() {
		if ( $GLOBALS['ec_cart_data']->cart_data->shipping_country == "US" && function_exists( 'wpeasycart_taxjar' ) && wpeasycart_taxjar()->is_enabled() ) {
			return $GLOBALS['ec_cart_data']->cart_data->taxjar_address_verified;
		} else {
			return true;
		}
	}

	private function validate_vat_registration_number( $vat_number ) {

		// Validate with vatlayer
		if ( $vat_number != "" && get_option( 'ec_option_collect_vat_registration_number' ) && get_option( 'ec_option_validate_vat_registration_number' ) && get_option( 'ec_option_vatlayer_api_key' ) != "" ) {
			// set API Endpoint and Access Key
			$endpoint = 'validate';
			$access_key = get_option( 'ec_option_vatlayer_api_key' );

			$request = new WP_Http;
			$response = $request->request( 
				'http://apilayer.net/api/'.$endpoint.'?access_key='.$access_key.'&vat_number='.preg_replace( "/[^A-Za-z0-9]/", '', trim( $vat_number ) ), 
				array( 
					'method' => 'GET',
					'timeout' => 30
				)
			);
			if ( is_wp_error( $response ) ) {
				return false;
			}
			$json = $response['body'];

			// Decode JSON response:
			$validationResult = json_decode($json, true);

			// Access and use your preferred validation result objects
			$validationResult['valid'];
			$validationResult['query'];
			$validationResult['company_name'];
			$validationResult['company_address'];

			if ( $validationResult['valid'] == "true" ) {
				return true;
			} else {
				return false;
			}

		// No validation required
		} else {
			return true;
		}

	}

	public function print_paypal_express_button_code( $is_payment_page = false, $is_horizontal = false ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_button_code.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_button_code.php' );
		} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_paypal_button_code.php' );
		}
	}

	public function print_paypal_express_button_code_order( $is_payment_page = false, $is_horizontal = false ) {
		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_button_code_order.php' ) ) {
			include( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_cart_paypal_button_code_order.php' );
		} else {
				include( EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_cart_paypal_button_code_order.php' );
		}
	}

	public function submit_square_quick_payment_v2() {
		global $wpdb;
		$ec_db_admin = new ec_db_admin();
		$response = $this->order->submit_order( "credit_card" );
		if ( '1' == $response ) {
			return json_encode( (object) array( 'ok' => true, 'goto' => esc_url_raw( $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id ) ) );
		} else {
			return json_encode( (object) array( 'error' => $this->order->process_result ) );
		}
	}

	public function submit_square_quick_payment( $nonce, $card_type, $last_4, $exp_month, $exp_year ) {
		global $wpdb;
		$ec_db_admin = new ec_db_admin();
		$response = $this->order->submit_order( "credit_card" );
		if ( $response ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET card_holder_name = %s, payment_method = %s, creditcard_digits = %s, cc_exp_month = %s, cc_exp_year = %s WHERE order_id = %d", $GLOBALS['ec_cart_data']->cart_data->billing_first_name . ' ' . $GLOBALS['ec_cart_data']->cart_data->billing_last_name, $card_type, $last_4, $exp_month, $exp_year, $this->order->order_id ) );
			do_action( 'wpeasycart_submit_order_complete' );
			return $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id;

		} else {
			return '0';

		}
	}

	public function submit_stripe_quick_payment( $payment_id, $card_type, $last_4, $exp_month, $exp_year ) {
		global $wpdb;
		$ec_db_admin = new ec_db_admin();

		if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
			$first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			$last_name = $GLOBALS['ec_cart_data']->cart_data->billing_last_name;
			$email = $GLOBALS['ec_cart_data']->cart_data->email;

			$this->mysqli->insert_subscriber( $email, $first_name, $last_name );

			if ( $GLOBALS['ec_user']->user_id ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET is_subscriber = 1 WHERE ec_user.user_id = %d", $GLOBALS['ec_user']->user_id ) );
			}

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => $first_name,
					'lastname' => $last_name,
					'email' => $email,
					'status' => 1,
				), false );
			}
			
			do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
		}

		$this->order->submit_order( "third_party" );

		// Verify Payment Status in Case Already Successful
		$order_status = 12;
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();

		$stripe->initialize( $this->cart, $this->user, $this->shipping, $this->tax, $this->discount, $this->payment->credit_card, $this->order_totals, $this->order->order_id );

		// Maybe create customer
		if ( get_option( 'ec_option_stripe_order_create_customer' ) && $this->user->user_id != 0 && $this->user->stripe_customer_id == "" ) {
			$customer_id = $stripe->insert_quick_customer( $payment_id );
			$ec_db_admin->update_user_stripe_id( $this->user->user_id, $customer_id );

		} else if ( get_option( 'ec_option_stripe_order_create_customer' ) && $this->user->stripe_customer_id == "" ) {
			$stripe->insert_guest_customer( $payment_id, $this->order->order_id );

		} else if ( get_option( 'ec_option_stripe_order_create_customer' ) ) {
			$payment_intent = $stripe->get_payment_intent( $payment_id );
			$stripe->attach_payment_method( $payment_intent->payment_method, $this->user );

		}

		// Set Order ID and Confirm Current Payment Status
		$payment_status = $stripe->update_payment_intent_description( $payment_id, $this->order->order_id );
		if ( $payment_status && $payment_status->status == 'succeeded' ) {
			$order_status = 3;
		} else if ( $payment_status && $payment_status->status == 'requires_capture' ) {
			$order_status = 12;
		} else if ( $payment_status && $payment_status->status == 'processing' ) {
			$order_status = 12;
		} else if ( $payment_status->status == 'canceled' ) {
			$order_status = 19;
		}

		// Get Charge ID
		$stripe_charge_id = '';
		if ( isset( $payment_status->charges ) && isset( $payment_status->charges->data ) && count( $payment_status->charges->data ) > 0 ) {
			$stripe_charge_id = $payment_status->charges->data[0]->id;
		} else if ( isset( $payment_status->latest_charge ) ) {
			$stripe_charge_id = $payment_status->latest_charge;
		}

		// Update Order
		if ( '' == $stripe_charge_id ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_gateway = %s, creditcard_digits = %s, cc_exp_month = %s, cc_exp_year = %s, gateway_transaction_id = %s, payment_method = %s, orderstatus_id = %d WHERE order_id = %d", get_option( 'ec_option_payment_process_method' ), $last_4, $exp_month, $exp_year, $payment_id, $card_type, $order_status, $this->order->order_id ) );
		} else {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_gateway = %s, creditcard_digits = %s, cc_exp_month = %s, cc_exp_year = %s, stripe_charge_id = %s, gateway_transaction_id = %s, payment_method = %s, orderstatus_id = %d WHERE order_id = %d", get_option( 'ec_option_payment_process_method' ), $last_4, $exp_month, $exp_year, $stripe_charge_id, $payment_id, $card_type, $order_status, $this->order->order_id ) );
		}

		// Correct system log, third party pending is only temporary with this payment method
		$wpdb->query( $wpdb->prepare( 'DELETE FROM ec_order_log WHERE order_id = %d AND order_log_key = "order-status-update"', $this->order->order_id ) );

		// Log order status update
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-status-update" )', $this->order->order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $this->order->order_id, $order_status ) );

		// Clear tempcart
		$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$this->order->clear_session();

		// Maybe send email receipts
		if ( $order_status == 3 ) {
			$order_row = $ec_db_admin->get_order_row_admin( $this->order->order_id );
			$orderdetails = $ec_db_admin->get_order_details_admin( $this->order->order_id );

			/* Update Stock Quantity */
			foreach( $orderdetails as $orderdetail ) {
				$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
				if ( $product ) {
					if ( $product->use_optionitem_quantity_tracking )	
						$ec_db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
					$ec_db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
					$this->mysqli->update_details_stock_adjusted( $orderdetail->orderdetail_id );
				}
			}

			// Update Order Status/Send Alerts
			do_action( 'wpeasycart_order_paid', $this->order->order_id );

			// send email
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();
			$order_display->send_gift_cards();
		} else {
			do_action( 'wpeasycart_order_complete', $this->order->order_id, $order_status );
		}

		$GLOBALS['ec_cart_data']->save_session_to_db();

		return $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id;
	}

	public function submit_stripe_invoice_payment( $payment_id, $card_type, $last_4, $exp_month, $exp_year ) {
		global $wpdb;
		$ec_db_admin = new ec_db_admin();

		if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
			$first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			$last_name = $GLOBALS['ec_cart_data']->cart_data->billing_last_name;
			$email = $GLOBALS['ec_cart_data']->cart_data->email;

			$this->mysqli->insert_subscriber( $email, $first_name, $last_name );

			if ( $GLOBALS['ec_user']->user_id ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET is_subscriber = 1 WHERE ec_user.user_id = %d", $GLOBALS['ec_user']->user_id ) );
			}

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => $first_name,
					'lastname' => $last_name,
					'email' => $email,
					'status' => 1,
				), false );
			}
			
			do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
		}

		// Verify Payment Status in Case Already Successful
		$order_status = 12;
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();

		// Maybe create customer
		if ( get_option( 'ec_option_stripe_order_create_customer' ) && $this->user->user_id != 0 && $this->user->stripe_customer_id == "" ) {
			$customer_id = $stripe->insert_quick_customer( $payment_id );
			$ec_db_admin->update_user_stripe_id( $this->user->user_id, $customer_id );

		} else if ( get_option( 'ec_option_stripe_order_create_customer' ) && $this->user->stripe_customer_id == "" ) {
			$stripe->insert_guest_customer( $payment_id, (int) $_POST['invoice_id'] );

		} else if ( get_option( 'ec_option_stripe_order_create_customer' ) ) {
			$payment_intent = $stripe->get_payment_intent( $payment_id );
			$stripe->attach_payment_method( $payment_intent->payment_method, $this->user );

		}

		// Set Order ID and Confirm Current Payment Status
		$payment_status = $stripe->update_payment_intent_description( $payment_id, (int) $_POST['invoice_id'] );
		if ( $payment_status && $payment_status->status == 'succeeded' ) {
			$order_status = 3;
		} else if ( $payment_status->status == 'canceled' ) {
			$order_status = 19;
		}

		// Get Charge ID
		$stripe_charge_id = '';
		if ( isset( $payment_status->charges ) && isset( $payment_status->charges->data ) && count( $payment_status->charges->data ) > 0 ) {
			$stripe_charge_id = $payment_status->charges->data[0]->id;
		} else if ( isset( $payment_status->latest_charge ) ) {
			$stripe_charge_id = $payment_status->latest_charge;
		}

		// Update Order
		$wpdb->query( $wpdb->prepare( "UPDATE 
				ec_order 
			SET 
				billing_first_name = %s, billing_last_name = %s, billing_company_name = %s, billing_address_line_1 = %s, billing_address_line_2 = %s, 
				billing_city = %s, billing_state = %s, billing_zip = %s, billing_country = %s, billing_phone = %s, 
				shipping_first_name = %s, shipping_last_name = %s, shipping_company_name = %s, shipping_address_line_1 = %s, shipping_address_line_2 = %s, 
				shipping_city = %s, shipping_state = %s, shipping_zip = %s, shipping_country = %s, shipping_phone = %s, 
				order_gateway = %s, creditcard_digits = %s, cc_exp_month = %s, cc_exp_year = %s, stripe_charge_id = %s, 
				gateway_transaction_id = %s, payment_method = 'credit_card', orderstatus_id = %d 
			WHERE order_id = %d", 
				sanitize_text_field( $_POST['billing_address']['first_name'] ), 
				sanitize_text_field( $_POST['billing_address']['last_name'] ),
				sanitize_text_field( $_POST['billing_address']['company_name'] ),
				sanitize_text_field( $_POST['billing_address']['address1'] ),
				sanitize_text_field( $_POST['billing_address']['address2'] ),
				sanitize_text_field( $_POST['billing_address']['city'] ),
				sanitize_text_field( $_POST['billing_address']['state'] ),
				sanitize_text_field( $_POST['billing_address']['zip'] ),
				sanitize_text_field( $_POST['billing_address']['country'] ),
				sanitize_text_field( $_POST['billing_address']['phone'] ),
				sanitize_text_field( $_POST['shipping_address']['first_name'] ),
				sanitize_text_field( $_POST['shipping_address']['last_name'] ), 
				sanitize_text_field( $_POST['shipping_address']['company_name'] ),
				sanitize_text_field( $_POST['shipping_address']['address1'] ),
				sanitize_text_field( $_POST['shipping_address']['address2'] ),
				sanitize_text_field( $_POST['shipping_address']['city'] ),
				sanitize_text_field( $_POST['shipping_address']['state'] ),
				sanitize_text_field( $_POST['shipping_address']['zip'] ),
				sanitize_text_field( $_POST['shipping_address']['country'] ),
				sanitize_text_field( $_POST['shipping_address']['phone'] ),
				sanitize_text_field( get_option( 'ec_option_payment_process_method' ) ),
				$last_4, $exp_month, $exp_year, $stripe_charge_id, 
				$payment_id, $order_status, (int) $_POST['invoice_id'] 
		) );

		// Clear tempcart
		$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$this->order->clear_session();

		// Maybe send email receipts
		if ( $order_status == 3 ) {
			$order_row = $ec_db_admin->get_order_row_admin( (int) $_POST['invoice_id'] );
			$orderdetails = $ec_db_admin->get_order_details_admin( (int) $_POST['invoice_id'] );

			do_action( 'wp_easycart_invoice_paid', (int) $_POST['invoice_id'] );

			// send email
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();
			$order_display->send_gift_cards();
		}

		$GLOBALS['ec_cart_data']->save_session_to_db();

		return $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_POST['invoice_id'];
	}

	public function submit_stripe_quick_subscription_payment( $payment_id ) {
		global $wpdb;

		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
			$stripe = new ec_stripe();
		else
			$stripe = new ec_stripe_connect();

		if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
			$first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			$last_name = $GLOBALS['ec_cart_data']->cart_data->billing_last_name;
			$email = $GLOBALS['ec_cart_data']->cart_data->email;

			$this->mysqli->insert_subscriber( $email, $first_name, $last_name );

			if ( $GLOBALS['ec_user']->user_id ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET is_subscriber = 1 WHERE ec_user.user_id = %d", $GLOBALS['ec_user']->user_id ) );
			}

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => $first_name,
					'lastname' => $last_name,
					'email' => $email,
					'status' => 1,
				), false );
			}
			
			do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
		}

		$model_number = sanitize_text_field( $_POST['model_number'] );
		$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s", $model_number ), "", "", "" );
		$product = new ec_product( $products[0] );
		$this->cart->cart = array( $product );
		$subscription_cart = array();

		$subscription_row = $this->mysqli->get_subscription_row( (int) $_POST['subscription_id'] );
		$subscription = new ec_subscription( $subscription_row );

		$quantity = 1;
		if ( isset( $_POST['ec_quantity'] ) )
			$quantity = (int) $_POST['ec_quantity'];

		if ( $product->trial_period_days > 0 ) {
			$subscription->send_trial_start_email( $GLOBALS['ec_user'] );

		}

		// Get option item price adjustments
		$option_promotion_multiplier = 1;
		$option_promotion_discount = 0;
		$promotions = $GLOBALS['ec_promotions']->promotions;
		for( $i=0; $i<count( $promotions ); $i++ ) {
			if ( $product->promotion_text == $promotions[$i]->promotion_name ) {
				if ( $promotions[$i]->price1 == 0 ) {
					$option_promotion_multiplier = round( $promotions[$i]->percentage1 / 100, 2 );
				} else if ( $promotions[$i]->price1 != 0 ) {
					$option_promotion_discount = $promotions[$i]->price1;
				}
			}
		}

		// Handle Option Pricing Plans
		$option_price_adjustment = 0;
		$option_price_onetime_adjustment = 0;
		$option_weight_adjustment = 0;
		$option_weight_onetime_adjustment = 0;
		$optionitem_list = $GLOBALS['ec_options']->get_all_optionitems();

		foreach( $optionitem_list as $option_item ) {
			$found = false;
			$check_option = false;
			if ( $option_item->optionitem_id == $this->subscription_option1 || $option_item->optionitem_id == $this->subscription_option2 || $option_item->optionitem_id == $this->subscription_option3 || $option_item->optionitem_id == $this->subscription_option4 || $option_item->optionitem_id == $this->subscription_option5 ) {
				if ( $option_item->optionitem_price > 0 ) {
					$option_price_adjustment += $option_item->optionitem_price;
					$subscription_cart[] = (object) array(
						'vat_enabled' => ( $product->vat_rate != 0 ),
						'is_taxable' => $product->is_taxable,
						'item_total' => round( $option_item->optionitem_price * $quantity, 2 ),
						'item_discount' => 0,
					);
				}
				if ( $option_item->optionitem_weight > 0 ) {
					$option_weight_adjustment += $option_item->optionitem_weight;
				}
			}
		}

		if ( $this->subscription_advanced_options ) {
			foreach( $this->subscription_advanced_options as $option ) {
				$optionitem = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
				if ( $optionitem->optionitem_disallow_shipping ) {
					$product->is_shippable = false;
				}
				if ( $optionitem->optionitem_download_override_file ) {
					$product->download_file_name = $optionitem->optionitem_download_override_file;
				}
				if ( $optionitem && $optionitem->optionitem_price > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_price_adjustment += ( $optionitem->optionitem_price * (int) $option['optionitem_value'] );
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( ( $optionitem->optionitem_price * (int) $option['optionitem_value'] ) * $quantity, 2 ),
							'item_discount' => 0,
						);
					} else {
						$option_price_adjustment += $optionitem->optionitem_price;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => round( $optionitem->optionitem_price * $quantity, 2 ),
							'item_discount' => 0,
						);
					}
				} else if ( $optionitem && $optionitem->optionitem_price_onetime > 0 ) {
					$option_price_onetime_adjustment += $optionitem->optionitem_price_onetime;
					$subscription_cart[] = (object) array(
						'vat_enabled' => ( $product->vat_rate != 0 ),
						'is_taxable' => $product->is_taxable,
						'item_total' => round( $optionitem->optionitem_price_onetime, 2 ),
						'item_discount' => 0,
					);
				} else if ( $optionitem && $optionitem->optionitem_price_override > -1 ) {
					$product->price = $optionitem->optionitem_price_override;
				}
				if ( $optionitem && $optionitem->optionitem_weight > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_weight_adjustment += ( $optionitem->optionitem_weight * (int) $option['optionitem_value'] );
					} else {
						$option_weight_adjustment += $optionitem->optionitem_weight;
					}
				} else if ( $optionitem && $optionitem->optionitem_weight_onetime > 0 ) {
					$option_weight_onetime_adjustment += $optionitem->optionitem_weight_onetime;
				} else if ( $optionitem && $optionitem->optionitem_weight_override > -1 ) {
					$product->weight = $optionitem->optionitem_weight_override;
				}
			}
		}

		$subscription_cart[] = (object) array(
			'vat_enabled' => ( $product->vat_rate != 0 ),
			'is_taxable' => $product->is_taxable,
			'item_total' => round( $product->price * $quantity, 2 ),
			'item_discount' => 0,
		);

		if ( $product->trial_period_days > 0 ) {
			$product->price = 0;
		} else {
			$product->price = $product->price + $option_price_adjustment;
		}

		if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
			$ship_price_total = ( $product->price * $quantity ) + $option_price_onetime_adjustment;
			$ship_weight_total = ( $product->weight + $option_weight_adjustment ) * $quantity + $option_weight_onetime_adjustment;
			$ship_quantity = $quantity;
		} else {
			$ship_price_total = 0;
			$ship_weight_total = 0;
			$ship_quantity = 0;
		}

		$product->weight = $ship_weight_total;
		$product->quantity = $ship_quantity;
		do_action( 'wpeasycart_cart_subscription_updated', $product, $quantity );

		$shipping_method = '';
		if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
			$this->shipping = new ec_shipping( $ship_price_total, $ship_weight_total, $ship_quantity, 'RADIO', $GLOBALS['ec_user']->freeshipping, $product->length, $product->width, $product->height * $quantity, array( $product ) );
			$this->shipping->change_shipping_js_func = 'ec_cart_subscription_shipping_method_change';
			$this->cart->shippable_total_items = $quantity;
			$handling_total = $product->handling_price + ( $product->handling_price_each * $quantity );
			$shipping_total = floatval( $this->shipping->get_shipping_price( $handling_total ) );
			if ( ! get_option( 'ec_option_use_shipping' ) || $shipping_total <= 0 ) {
				$shipping_method = '';
			} else if ( $this->shipping->shipping_method == "fraktjakt" ) {
				$shipping_method = $this->shipping->get_selected_shipping_method();
			} else if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_method != "standard" ) {
				$shipping_method = $this->mysqli->get_shipping_method_name( $GLOBALS['ec_cart_data']->cart_data->shipping_method );
			} else if ( ( $this->shipping->shipping_method == "price" || $this->shipping->shipping_method == "weight" ) && $GLOBALS['ec_cart_data']->cart_data->expedited_shipping != "" ) {
				$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_express" );
			} else {
				$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_standard" );
			}
			$subscription_cart[] = (object) array(
				'vat_enabled' => ! get_option( 'ec_option_no_vat_on_shipping' ),
				'is_taxable' => get_option( 'ec_option_collect_tax_on_shipping' ),
				'item_total' => round( $shipping_total, 2 ),
				'item_discount' => 0,
			);
		} else {
			$handling_total = 0;
			$shipping_total = 0;
			$this->cart->shippable_total_items = 0;
			$this->order_totals->shipping_total = 0;
		}

		// Coupon Information
		$coupon = NULL;
		$discount_total = 0;
		$is_dollar_discount = false;
		$is_match = false;
		if ( isset( $_POST['coupon_code'] ) && $_POST['coupon_code'] != "" ) {
			$coupon_row = $GLOBALS['ec_coupons']->redeem_coupon_code( sanitize_text_field( $_POST['coupon_code'] ) );
			if ( $coupon_row ) {
				if ( $coupon_row->by_product_id ) {
					if ( $product->product_id == $coupon_row->product_id ) {
						$is_match = true;
					}
				} else if ( $coupon_row->by_manufacturer_id ) {
					if ( $product->manufacturer_id == $coupon_row->manufacturer_id ) {
						$is_match = true;
					}
				} else {
					$is_match = true;
				}

				if ( $is_match ) {
					$coupon = $coupon_row->promocode_id;
				}
			}
		}

		// IF MATCH FOUND, APPLY TO PRODUCT
		if ( $is_match ) {
			if ( $coupon_row->is_dollar_based ) {
				$is_dollar_discount = true;
				$discount_total = round( $coupon_row->promo_dollar, 2 );
			} else if ( $coupon_row->is_percentage_based ) {
				$coupon_percentage = round( $coupon_row->promo_percentage / 100, 2 );
				for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
					$subscription_cart[ $i ]->item_discount = round( $subscription_cart[ $i ]->item_total * $coupon_percentage, 2 );
					$discount_total += $subscription_cart[ $i ]->item_discount;
				}
			}
		} else if ( $option_promotion_multiplier != 1 ) {
			for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
				$subscription_cart[ $i ]->item_discount = round( $subscription_cart[ $i ]->item_total * $option_promotion_multiplier, 2 );
				$discount_total += $subscription_cart[ $i ]->item_discount;
			}
		} else if ( $option_promotion_discount > 0 ) {
			$is_dollar_discount = true;
			$discount_total = round( $option_promotion_discount, 2 );
		}
		// END MATCHING COUPON SECTION

		$this->cart->subtotal = ( $product->price * $quantity ) + $option_price_onetime_adjustment;
		if ( $is_dollar_discount ) {
			for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
				$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - round( ( $subscription_cart[$i]->item_total / ( $this->cart->subtotal + $shipping_total ) ) * $discount_total, 2 );
			}
		} else {
			for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
				$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - $subscription_cart[$i]->item_discount;
			}
		}

		if ( $product->is_taxable || $product->vat_rate ) {
			$taxable_subtotal = 0;
			$vatable_subtotal = 0;
			if ( $product->is_taxable ) {
				$taxable_subtotal = $product->price * $quantity - $discount_total;
			}
			if ( $product->vat_rate ) {
				$vatable_subtotal = $product->price * $quantity - $discount_total;
			}

			do_action( 'wpeasycart_cart_subscription_pre_tax', $product, $quantity, $shipping_total, $handling_total, $discount_total );

			if ( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ) {
				wpeasycart_taxcloud()->setup_subscription_for_tax( $product, $quantity, $discount_total );
			}
			$this->tax = new ec_tax( $product->price * $quantity, $taxable_subtotal, $vatable_subtotal, sanitize_text_field( $_POST['billing_details']['address']['state'] ), sanitize_text_field( $_POST['billing_details']['address']['country'] ), $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $product->handling_price_each * $quantity ) + $product->handling_price ), $subscription_cart, true );
		} else {
			$this->tax = new ec_tax( 0, 0, 0, sanitize_text_field( $_POST['billing_details']['address']['state'] ), sanitize_text_field( $_POST['billing_details']['address']['country'] ), $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $product->handling_price_each * $quantity ) + $product->handling_price ), $subscription_cart, true );
		}

		$this->order_totals = new ec_order_totals( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount );
		$this->order_totals->sub_total += ( $product->subscription_signup_fee * $quantity );
		$this->order_totals->shipping_total = $shipping_total;

		// Update Custom Order Total
		if ( $product->trial_period_days > 0 ) {
			$this->order_totals->grand_total = ( $product->subscription_signup_fee * $quantity ) + $shipping_total;

		} else {
			$this->order_totals->grand_total = ( ( $product->price + $product->subscription_signup_fee ) * $quantity ) + $option_price_onetime_adjustment - $discount_total + $this->order_totals->tax_total + $this->tax->hst + $this->tax->pst + $this->tax->gst + $shipping_total;
			if ( !$this->tax->vat_included ) {
				 $this->order_totals->grand_total += $this->order_totals->vat_total;
			}
		}

		$card = new ec_credit_card( sanitize_text_field( $_POST['card']['brand'] ), sanitize_text_field( $_POST['card']['name'] ), sanitize_text_field( $_POST['card']['last4'] ), sanitize_text_field( $_POST['card']['exp_month'] ), sanitize_text_field( $_POST['card']['exp_year'] ), sanitize_text_field( $_POST['card']['cvv'] ) );

		$stripe_charge_id = '';
		$stripe_payment_intent = $stripe->get_payment_intent( $_POST['paymentintent_id'] );
		if ( $stripe_payment_intent && isset( $stripe_payment_intent->latest_charge ) ) {
			$stripe_charge_id = $stripe_payment_intent->latest_charge;
			$charge = $stripe->get_charge( $stripe_charge_id );
			if ( $charge && isset( $charge->payment_method_details ) && isset( $charge->payment_method_details->type ) ) {
				$payment_method = $charge->payment_method_details->card->brand;
				$last_4 = $charge->payment_method_details->card->last4;
				$exp_month = $charge->payment_method_details->card->exp_month;
				$exp_year = $charge->payment_method_details->card->exp_year;
				$card = new ec_credit_card( $payment_method, sanitize_text_field( $_POST['card']['name'] ), $last_4, $exp_month, $exp_year, '' );
			}
		}

		$order_id = $this->mysqli->insert_subscription_order( 
			$product,
			$GLOBALS['ec_user'],
			$card,
			(int) $_POST['subscription_id'],
			$coupon,
			( isset( $_POST['order_notes'] ) ) ? strip_tags( sanitize_textarea_field( $_POST['order_notes'] ) ) : '',
			$this->subscription_option1_name,
			$this->subscription_option2_name,
			$this->subscription_option3_name,
			$this->subscription_option4_name,
			$this->subscription_option5_name,
			$this->subscription_option1_label,
			$this->subscription_option2_label,
			$this->subscription_option3_label,
			$this->subscription_option4_label,
			$this->subscription_option5_label,
			$quantity,
			$this->order_totals,
			$shipping_method,
			$this->tax,
			$discount_total,
			$stripe_charge_id,
			sanitize_text_field( $_POST['paymentintent_id'] ),
			$option_price_onetime_adjustment
		);
		$this->mysqli->update_user_default_card( $GLOBALS['ec_user'], $card );

		do_action( 'wpeasycart_subscription_first_order_inserted', $order_id );
		do_action( 'wpeasycart_order_paid', $order_id );

		$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		$order = new ec_orderdisplay( $order_row );
		$order_details = $this->mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
		$subscription->send_email_receipt( $GLOBALS['ec_user'], $order, $order_details );
		$this->mysqli->update_product_stock( $product->product_id, $quantity );

		if ( $subscription->payment_duration > 0 && $subscription->payment_duration == 1 ) {
			$stripe->cancel_subscription( $GLOBALS['ec_user'], $subscription->stripe_subscription_id );
			$this->mysqli->cancel_stripe_subscription( $subscription->stripe_subscription_id );
		}

		return $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $order_id;

	}

	public function submit_stripe_quick_subscription( $payment_id ) {
		wpeasycart_session()->handle_session();
		global $wpdb;
		$model_number = sanitize_text_field( $_POST['model_number'] );
		$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s", $model_number ), "", "", "" );
		$product = new ec_product( $products[0] );
		$this->cart->cart = array( $product );
		$subscription_cart = array();

		$quantity = 1;
		if ( isset( $_POST['ec_quantity'] ) ) {
			$quantity = (int) $_POST['ec_quantity'];
		}

		// Verify Payment Status in Case Already Successful
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
			$stripe = new ec_stripe();
		} else {
			$stripe = new ec_stripe_connect();
		}

		// Handle Option Pricing Plans
		$option_price_adjustment = 0;
		$option_price_onetime_adjustment = 0;
		$option_weight_adjustment = 0;
		$option_weight_onetime_adjustment = 0;
		$subscription_plan_options = array();
		$subscription_plan_quantities = array();
		$optionitem_list = $GLOBALS['ec_options']->get_all_optionitems();

		foreach( $optionitem_list as $option_item ) {
			$found = false;
			$check_option = false;
			if ( $option_item->optionitem_id == $this->subscription_option1 || $option_item->optionitem_id == $this->subscription_option2 || $option_item->optionitem_id == $this->subscription_option3 || $option_item->optionitem_id == $this->subscription_option4 || $option_item->optionitem_id == $this->subscription_option5 ) {
				if ( $option_item->optionitem_price > 0 ) {
					$option_plan_exists = false;
					if ( $option_item->stripe_plan_id && '' != $option_item->stripe_plan_id ) {
						$option_plan_exists = $stripe->get_plan( (object) array( 'subscription_unique_id' => $option_item->stripe_plan_id ) );
					}
					if ( 
						! $option_item->stripe_plan_id || 
						'' == $option_item->stripe_plan_id || 
						! $option_plan_exists || 
						in_array( $option_item->stripe_plan_id, $subscription_plan_options ) ||
						$option_plan_exists->amount != (int) ( $option_item->optionitem_price * 100 ) || 
						$option_plan_exists->nickname != wp_easycart_language()->convert_text( $option_item->optionitem_name )
					) {
						$stripe_plan = $stripe->insert_option_as_plan( $product, $option_item );
						if ( $stripe_plan ) {
							$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET stripe_plan_id = %d WHERE optionitem_id = %d", $stripe_plan->id, $option_item->optionitem_id ) );
							$option_item->stripe_plan_id = $stripe_plan->id;
						}
					}
					$option_price_adjustment += $option_item->optionitem_price;
					$option_weight_adjustment += $option_item->optionitem_weight;
					$subscription_plan_options[] = $option_item->stripe_plan_id;
					$subscription_plan_quantities[] = $quantity;
					$subscription_cart[] = (object) array(
						'vat_enabled' => ( $product->vat_rate != 0 ),
						'is_taxable' => $product->is_taxable,
						'item_total' => $option_item->optionitem_price * $quantity,
						'item_discount' => 0,
					);
				}
			}
		}

		if ( $this->subscription_advanced_options ) {
			foreach( $this->subscription_advanced_options as $option ) {
				$option_item = $GLOBALS['ec_options']->get_optionitem( $option['optionitem_id'] );
				if ( $option_item->optionitem_disallow_shipping ) {
					$product->is_shippable = false;
				}
				if ( $option_item && $option_item->optionitem_price > 0 ) {
					$option_plan_exists = false;
					if ( $option_item->stripe_plan_id && '' != $option_item->stripe_plan_id ) {
						$option_plan_exists = $stripe->get_plan( (object) array( 'subscription_unique_id' => $option_item->stripe_plan_id ) );
					}
					if ( 
						! $option_item->stripe_plan_id || 
						'' == $option_item->stripe_plan_id || 
						! $option_plan_exists || 
						in_array( $option_item->stripe_plan_id, $subscription_plan_options ) ||
						$option_plan_exists->amount != (int) ( $option_item->optionitem_price * 100 ) || 
						$option_plan_exists->nickname != wp_easycart_language()->convert_text( $option_item->optionitem_name )
					) {
						$stripe_plan = $stripe->insert_option_as_plan( $product, $option_item );
						if ( $stripe_plan ) {
							$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET stripe_plan_id = %d WHERE optionitem_id = %d", $stripe_plan->id, $option_item->optionitem_id ) );
							$option_item->stripe_plan_id = $stripe_plan->id;
						}
					}
					if ( 'number' == $option['option_type'] ) {
						$option_price_adjustment += ( $option_item->optionitem_price * (int) $option['optionitem_value'] );
						$subscription_plan_quantities[] = (int) $option['optionitem_value'] * $quantity;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => ( $option_item->optionitem_price * (int) $option['optionitem_value'] ) * $quantity,
							'item_discount' => 0,
						);
					} else {
						$option_price_adjustment += $option_item->optionitem_price;
						$subscription_plan_quantities[] = $quantity;
						$subscription_cart[] = (object) array(
							'vat_enabled' => ( $product->vat_rate != 0 ),
							'is_taxable' => $product->is_taxable,
							'item_total' => $option_item->optionitem_price * $quantity,
							'item_discount' => 0,
						);
					}
					$subscription_plan_options[] = $option_item->stripe_plan_id;
				} else if ( $option_item && $option_item->optionitem_price_onetime > 0 ) {
					$option_plan_exists = false;
					if ( $option_item->stripe_plan_id && '' != $option_item->stripe_plan_id ) {
						$option_plan_exists = $stripe->get_plan( (object) array( 'subscription_unique_id' => $option_item->stripe_plan_id ) );
					}
					if ( 
						! $option_item->stripe_plan_id || 
						'' == $option_item->stripe_plan_id || 
						! $option_plan_exists || 
						in_array( $option_item->stripe_plan_id, $subscription_plan_options ) ||
						$option_plan_exists->amount != (int) ( $option_item->optionitem_price_onetime * 100 ) || 
						$option_plan_exists->nickname != wp_easycart_language()->convert_text( $option_item->optionitem_name )
					) {
						$stripe_plan = $stripe->insert_option_as_plan( $product, $option_item );
						if ( $stripe_plan ) {
							$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET stripe_plan_id = %d WHERE optionitem_id = %d", $stripe_plan->id, $option_item->optionitem_id ) );
							$option_item->stripe_plan_id = $stripe_plan->id;
						}
					}
					$option_price_onetime_adjustment += $option_item->optionitem_price_onetime;
					$subscription_plan_quantities[] = 1;
					$subscription_plan_options[] = $option_item->stripe_plan_id;
					$subscription_cart[] = (object) array(
						'vat_enabled' => ( $product->vat_rate != 0 ),
						'is_taxable' => $product->is_taxable,
						'item_total' => $option_item->optionitem_price_onetime,
						'item_discount' => 0,
					);
				} else if ( $option_item && $option_item->optionitem_price_override > 0 ) {
					$option_plan_exists = false;
					if ( $option_item->stripe_plan_id && '' != $option_item->stripe_plan_id ) {
						$option_plan_exists = $stripe->get_plan( (object) array( 'subscription_unique_id' => $option_item->stripe_plan_id ) );
					}
					if ( 
						! $option_item->stripe_plan_id || 
						'' == $option_item->stripe_plan_id || 
						! $option_plan_exists || 
						in_array( $option_item->stripe_plan_id, $subscription_plan_options ) ||
						$option_plan_exists->amount != (int) ( $option_item->optionitem_price_override * 100 ) || 
						$option_plan_exists->nickname != wp_easycart_language()->convert_text( $option_item->optionitem_name )
					) {
						$stripe_plan = $stripe->insert_option_as_plan( $product, $option_item );
						if ( $stripe_plan ) {
							$wpdb->query( $wpdb->prepare( "UPDATE ec_optionitem SET stripe_plan_id = %d WHERE optionitem_id = %d", $stripe_plan->id, $option_item->optionitem_id ) );
							$option_item->stripe_plan_id = $stripe_plan->id;
						}
					}
					$product->price = $option_item->optionitem_price_override;
					$product->subscription_unique_id = $option_item->stripe_plan_id;
				}
				if ( $option_item && $option_item->optionitem_weight > 0 ) {
					if ( 'number' == $option['option_type'] ) {
						$option_weight_adjustment += ( $option_item->optionitem_weight * (int) $option['optionitem_value'] );
					} else {
						$option_weight_adjustment += $option_item->optionitem_weight;
					}
				} else if ( $option_item && $option_item->optionitem_weight_onetime > 0 ) {
					$option_weight_onetime_adjustment += $option_item->optionitem_weight_onetime;
				} else if ( $option_item && $option_item->optionitem_weight_override > 0 ) {
					$product->weight = $option_item->optionitem_weight_override;
				}
			}
		}

		$subscription_cart[] = (object) array(
			'vat_enabled' => ( $product->vat_rate != 0 ),
			'is_taxable' => $product->is_taxable,
			'item_total' => $product->price * $quantity,
			'item_discount' => 0,
		);

		$product->price = $product->price + $option_price_adjustment;

		if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
			$ship_price_total = ( $product->price + $option_price_adjustment ) * $quantity + $option_price_onetime_adjustment;
			$ship_weight_total = ( $product->weight + $option_weight_adjustment ) * $quantity + $option_price_onetime_adjustment;
			$ship_quantity = $quantity;
		} else {
			$ship_price_total = 0;
			$ship_weight_total = 0;
			$ship_quantity = 0;
		}
		
		$product->weight = $ship_weight_total;
		$product->quantity = $ship_quantity;
		do_action( 'wpeasycart_cart_subscription_updated', $product, $quantity );

		$shipping_method = '';
		if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && get_option( 'ec_option_use_shipping' ) && $product->is_shippable ) {
			$this->shipping = new ec_shipping( $ship_price_total, $ship_weight_total, $ship_quantity, 'RADIO', $GLOBALS['ec_user']->freeshipping, $product->length, $product->width, $product->height * $quantity, array( $product ) );
			$this->shipping->change_shipping_js_func = 'ec_cart_subscription_shipping_method_change';
			$this->cart->shippable_total_items = $quantity;
			$handling_total = $product->handling_price + ( $product->handling_price_each * $quantity );
			$shipping_total = floatval( $this->shipping->get_shipping_price( $handling_total ) );
			$this->order_totals->shipping_total = $shipping_total;
			if ( !get_option( 'ec_option_use_shipping' ) || $shipping_total <= 0 ) {
				$shipping_method = "";
			} else if ( $this->shipping->shipping_method == "fraktjakt" ) {
				$shipping_method = $this->shipping->get_selected_shipping_method();
			} else if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_method != "standard" ) {
				$shipping_method = $this->mysqli->get_shipping_method_name( $GLOBALS['ec_cart_data']->cart_data->shipping_method );
			} else if ( ( $this->shipping->shipping_method == "price" || $this->shipping->shipping_method == "weight" ) && $GLOBALS['ec_cart_data']->cart_data->expedited_shipping != "" ) {
				$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_express" );
			} else {
				$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_standard" );
			}
			$subscription_cart[] = (object) array(
				'vat_enabled' => ! get_option( 'ec_option_no_vat_on_shipping' ),
				'is_taxable' => get_option( 'ec_option_collect_tax_on_shipping' ),
				'item_total' => $shipping_total,
				'item_discount' => 0,
			);

		} else {
			$handling_total = 0;
			$shipping_total = 0;
			$this->cart->shippable_total_items = 0;
			$this->order_totals->shipping_total = 0;
		}

		$stripe_shipping_plan_id = false;
		if ( $product->is_shippable && $product->subscription_shipping_recurring && 0 < $shipping_total ) {
			$stripe_shipping_plan = $stripe->insert_shipping_as_plan( $product, $shipping_total );
			$stripe_shipping_plan_id = ( isset( $stripe_shipping_plan->id ) ) ? $stripe_shipping_plan->id : false;
		}

		// Coupon Information
		$coupon = NULL;
		$discount_total = 0;
		$is_match = false;
		$shipping_discount = 0;

		if ( isset( $_POST['coupon_code'] ) && $_POST['coupon_code'] != "" ) {
			$coupon_row = $GLOBALS['ec_coupons']->redeem_coupon_code( sanitize_text_field( $_POST['coupon_code'] ) ); // XSS OK. Object values sanitized before used.
			$is_match = false;
			if ( (bool) $coupon_row->by_product_id ) {
				if ( $products[0]['product_id'] == (int) $coupon_row->product_id ) {
					$is_match = true;
				}
			} else if ( (bool) $coupon_row->by_manufacturer_id ) {
				if ( $products[0]['manufacturer_id'] == (int) $coupon_row->manufacturer_id ) {
					$is_match = true;
				}
			} else {
				$is_match = true;
			}

			if ( $is_match ) {
				$coupon = sanitize_text_field( $coupon_row->promocode_id );
			}
		}
		// Coupon Check
		if ( $coupon ) {

			$coupon_exists = $stripe->get_coupon( $coupon );

			// Insert Coupon
			if ( $coupon_exists === false ) {
				$is_amount_off = false;
				if ( $coupon_row->promo_dollar > 0 ) {
					$is_amount_off = true;
				} else {
					$shipping_discount = round( $shipping_total * ( $coupon_row->promo_percentage / 100 ), 2 );
				}
				$redeem_by = NULL;
				if ( $coupon_row->expiration_date != '' ) {
					$redeem_by = strtotime( $coupon_row->expiration_date ) + 7*60*60;
				}
				$stripe_coupon = array(
					"promocode_id"		=> $coupon_row->promocode_id,
					"duration"			=> $coupon_row->duration,
					"duration_in_months"=> $coupon_row->duration_in_months,
					"is_amount_off"		=> $is_amount_off,
					"amount_off"		=> $coupon_row->promo_dollar * 100,
					"percent_off"		=> $coupon_row->promo_percentage,
					"redeem_by"			=> $redeem_by,
					"max_redemptions"	=> $coupon_row->max_redemptions
				);
				$stripe_coupon_response = $stripe->insert_coupon( $stripe_coupon );
				if ( $stripe_coupon_response === false ) {
					return array( 'error' => 'coupon_failed' );
				}
			}

		}
		// END COUPON CHECK

		// BEGIN PROMOTIONS CHECK
		if ( !$coupon && $product->has_promotion_text() ) {
			$promotion_exists = $stripe->get_coupon( preg_replace( "/[^A-Za-z0-9_\-]/", "", strtoupper( $product->promotion_text ) ) );
			$promotions = $GLOBALS['ec_promotions']->promotions;
			$applicable_promotion = false;
			for( $i=0; $i<count( $promotions ); $i++ ) {
				if ( $product->promotion_text == $promotions[$i]->promotion_name ) {
					$applicable_promotion = $promotions[$i];
				}
			}
			$promotion_code = "";
			if ( $applicable_promotion ) {
				// Insert Coupon
				$promotion_code = $coupon = preg_replace( "/[^A-Za-z0-9_\-]/", "", strtoupper( $applicable_promotion->promotion_name ) );
				// Promotion Not Added OR Coupon no Longer Matches Promotion
				if ( 
					$promotion_exists === false || 
					( $applicable_promotion->price1 > 0 && !$promotion_exists->amount_off ) || 
					( $applicable_promotion->price1 <= 0 && !$promotion_exists->percent_off ) || 
					( $applicable_promotion->price1 > 0 && (int) ( $applicable_promotion->price1 * 100 ) != $promotion_exists->amount_off ) || 
					( $applicable_promotion->price1 <= 0 && $applicable_promotion->percentage1 != $promotion_exists->percent_off )
				) {
					$is_amount_off = false;
					if ( $applicable_promotion->price1 > 0 ) {
						$is_amount_off = true;
					} else {
						$shipping_discount = round( $shipping_total * ( $applicable_promotion->percentage1 / 100 ), 2 );
					}
					$redeem_by = strtotime( $applicable_promotion->end_date ) + 7*60*60;
					$stripe_coupon = array(
						"promocode_id"		=> $promotion_code,
						"duration"			=> 'once',
						"is_amount_off"		=> $is_amount_off,
						"amount_off"		=> $applicable_promotion->price1 * 100,
						"percent_off"		=> $applicable_promotion->percentage1,
						"redeem_by"			=> $redeem_by,
						"max_redemptions"	=> 999
					);
					if ( $promotion_exists ) {
						$stripe->delete_coupon( $stripe_coupon['promocode_id'] );
					}
					$stripe_coupon_response = $stripe->insert_coupon( $stripe_coupon );
					if ( $stripe_coupon_response === false ) {
						return array( 'error' => 'coupon_failed' );
					}
				}
			}
		}
		// END PROMOTIONS CHECK

		$is_subscriber = 0;
		if ( $_POST['is_subscriber'] == 1 ) {
			$is_subscriber = 1;
		}

		// CREATE ACCOUNT IF NEEDED
		if ( isset( $_POST['create_account'] ) ) {

			if ( $this->mysqli->does_user_exist( sanitize_email( $_POST['billing_details']['email'] ) ) ) {
				return array( 'error' => array( 
					'id'		=> 'user_create_error',
					'message'	=> wp_easycart_language()->get_text( "ec_errors", "email_exists_error" )
				) );

			} else {
				$password = md5( $_POST['create_account']['password'] ); // XSS OK. Password Hashed Immediately
				$password = apply_filters( 'wpeasycart_password_hash', $password, $_POST['create_account']['password'] ); // XSS OK. Password should not be hashed.

				$billing_id = $this->mysqli->insert_address( 
					sanitize_text_field( $_POST['billing_details']['first_name'] ), 
					sanitize_text_field( $_POST['billing_details']['last_name'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['line1'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['line2'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['city'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['state'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['postal_code'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['country'] ), 
					sanitize_text_field( $_POST['billing_details']['phone'] ), 
					sanitize_text_field( $_POST['billing_details']['company_name'] ) 
				);

				$shipping_id = $this->mysqli->insert_address( 
					sanitize_text_field( $_POST['shipping_details']['first_name'] ), 
					sanitize_text_field( $_POST['shipping_details']['last_name'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['line1'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['line2'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['city'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['state'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['postal_code'] ), 
					sanitize_text_field( $_POST['shipping_details']['address']['country'] ), 
					sanitize_text_field( $_POST['shipping_details']['phone'] ), 
					sanitize_text_field( $_POST['shipping_details']['company_name'] ) 
				);

				$user_id = $this->mysqli->insert_user( 
					sanitize_email( $_POST['billing_details']['email'] ),
					$password,
					sanitize_text_field( $_POST['billing_details']['first_name'] ),
					sanitize_text_field( $_POST['billing_details']['last_name'] ),
					$billing_id,
					$shipping_id,
					"shopper",
					$is_subscriber,
					"",
					preg_replace( '[^a-zA-Z0-9\s]', '', sanitize_text_field( $_POST['vat_registration_number'] ) )
				);
				$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;

				$this->mysqli->update_address_user_id( $billing_id, $user_id );
				$this->mysqli->update_address_user_id( $shipping_id, $user_id );

				do_action( 'wpeasycart_account_added', $user_id, sanitize_email( $_POST['billing_details']['email'] ), $_POST['create_account']['password'] ); // XSS OK. Password should not be hashed.

				// Maybe insert WP user
				if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
					$user_name = 'wpeasycart_' . $user_id;
					$wp_user_id = wp_create_user( $user_name, $_POST['create_account']['password'], sanitize_email( $_POST['billing_details']['email'] ) ); // XSS OK. Password should not be hashed.
					add_user_meta( $wp_user_id, 'wpeasycart_user_id', $user_id, true );
				}

				// Send registration email if needed
				if ( get_option( 'ec_option_send_signup_email' ) ) {

					$headers   = array();
					$headers[] = "MIME-Version: 1.0";
					$headers[] = "Content-Type: text/html; charset=utf-8";
					$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
					$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
					$headers[] = "X-Mailer: PHP/" . phpversion();

					$message = wp_easycart_language()->get_text( "account_register", "account_register_email_message" ) . " " . $email;

					if ( get_option( 'ec_option_use_wp_mail' ) ) {
						wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( "account_register", "account_register_email_title" ), $message, implode("\r\n", $headers) );
					} else {
						$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
						$subject = wp_easycart_language()->get_text( "account_register", "account_register_email_title" );
						$mailer = new wpeasycart_mailer();
						$mailer->send_order_email( $admin_email, $subject, $message );
					}

				}

				$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
				$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;
				$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $_POST['billing_details']['email'] );
				$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( $_POST['billing_details']['first_name'] . ' ' . $_POST['billing_details']['last_name'] );
				$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $_POST['billing_details']['first_name'] );
				$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $_POST['billing_details']['last_name'] );

				$GLOBALS['ec_user'] = new ec_user( "" );
			}

		} else { // Customer already exists, lets update their billing address
			$GLOBALS['ec_user']->vat_registration_number = preg_replace( '/[^a-zA-Z0-9\s]/', '', sanitize_text_field( $_POST['vat_registration_number'] ) );
			$this->mysqli->update_user( $GLOBALS['ec_user']->user_id, preg_replace( '/[^a-zA-Z0-9\s]/', '', sanitize_text_field( $_POST['vat_registration_number'] ) ) );
			if ( $GLOBALS['ec_user']->billing_id == 0 ) {
				$billing_id = $this->mysqli->insert_address( 
					sanitize_text_field( $_POST['billing_details']['first_name'] ),
					sanitize_text_field( $_POST['billing_details']['last_name'] ),
					sanitize_text_field( $_POST['billing_details']['address']['line1'] ),
					sanitize_text_field( $_POST['billing_details']['address']['line2'] ),
					sanitize_text_field( $_POST['billing_details']['address']['city'] ),
					sanitize_text_field( $_POST['billing_details']['address']['state'] ),
					sanitize_text_field( $_POST['billing_details']['address']['postal_code'] ),
					sanitize_text_field( $_POST['billing_details']['address']['country'] ),
					sanitize_text_field( $_POST['billing_details']['phone'] ),
					sanitize_text_field( $_POST['billing_details']['company_name'] )
				);
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET default_billing_address_id = %d WHERE user_id = %d", $billing_id, $GLOBALS['ec_user']->user_id ) );
				$this->mysqli->update_address_user_id( $billing_id, $GLOBALS['ec_user']->user_id );

			} else {
				$this->mysqli->update_address( 
					$GLOBALS['ec_user']->billing_id, 
					sanitize_text_field( $_POST['billing_details']['first_name'] ), 
					sanitize_text_field( $_POST['billing_details']['last_name'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['line1'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['line2'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['city'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['state'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['postal_code'] ), 
					sanitize_text_field( $_POST['billing_details']['address']['country'] ), 
					sanitize_text_field( $_POST['billing_details']['phone'] ), 
					sanitize_text_field( $_POST['billing_details']['company_name'] ) 
				);
			}
			if ( $_POST['shipping_details']['first_name'] != '' ) {
				if ( $GLOBALS['ec_user']->shipping_id == 0 ) {
					$shipping_id = $this->mysqli->insert_address( 
						sanitize_text_field( $_POST['shipping_details']['first_name'] ), 
						sanitize_text_field( $_POST['shipping_details']['last_name'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['line1'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['line2'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['city'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['state'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['postal_code'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['country'] ), 
						sanitize_text_field( $_POST['shipping_details']['phone'] ), 
						sanitize_text_field( $_POST['shipping_details']['company_name'] )
					);
					$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET default_shipping_address_id = %d WHERE user_id = %d", $shipping_id, $GLOBALS['ec_user']->user_id ) );
					$this->mysqli->update_address_user_id( $shipping_id, $GLOBALS['ec_user']->user_id );

				} else {
					$this->mysqli->update_address( 
						$GLOBALS['ec_user']->shipping_id, 
						sanitize_text_field( $_POST['shipping_details']['first_name'] ), 
						sanitize_text_field( $_POST['shipping_details']['last_name'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['line1'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['line2'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['city'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['state'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['postal_code'] ), 
						sanitize_text_field( $_POST['shipping_details']['address']['country'] ), 
						sanitize_text_field( $_POST['shipping_details']['phone'] ), 
						sanitize_text_field( $_POST['shipping_details']['company_name'] )
					);
				}

			}
		}

		// Set Sessions
		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $_POST['billing_details']['first_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $_POST['billing_details']['last_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = sanitize_text_field( $_POST['billing_details']['company_name'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $_POST['billing_details']['address']['line1'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $_POST['billing_details']['address']['line2'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $_POST['billing_details']['address']['city'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $_POST['billing_details']['address']['state'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $_POST['billing_details']['address']['postal_code'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $_POST['billing_details']['address']['country'] );
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $_POST['billing_details']['phone'] );

		$GLOBALS['ec_cart_data']->cart_data->shipping_selector = ( isset( $_POST['shipping_details']['address']['first_name'] ) && strlen( sanitize_text_field( $_POST['shipping_details']['address']['first_name'] ) ) > 0) ? 1 : 0;

		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $_POST['shipping_details']['first_name'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $_POST['shipping_details']['last_name'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = sanitize_text_field( $_POST['shipping_details']['company_name'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $_POST['shipping_details']['address']['line1'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $_POST['shipping_details']['address']['line2'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $_POST['shipping_details']['address']['city'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $_POST['shipping_details']['address']['state'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $_POST['shipping_details']['address']['postal_code'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $_POST['shipping_details']['address']['country'] );
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $_POST['shipping_details']['phone'] );

		$GLOBALS['ec_cart_data']->cart_data->first_name = ( isset( $_POST['create_account']['first_name'] ) ) ? sanitize_text_field( $_POST['create_account']['first_name'] ) : $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
		$GLOBALS['ec_cart_data']->cart_data->last_name = ( isset( $_POST['create_account']['last_name'] ) ) ? sanitize_text_field( $_POST['create_account']['last_name'] ) : $GLOBALS['ec_cart_data']->cart_data->billing_last_name;

		$GLOBALS['ec_cart_data']->cart_data->order_notes = sanitize_textarea_field( $_POST['order_notes'] );
		$GLOBALS['ec_cart_data']->cart_data->email_other = sanitize_text_field( ( isset( $_POST['ec_email_other'] ) ) ? $_POST['ec_email_other'] : '' );

		$GLOBALS['ec_cart_data']->save_session_to_db();

		$GLOBALS['ec_user']->setup_billing_info_data(
			$GLOBALS['ec_cart_data']->cart_data->billing_first_name,
			$GLOBALS['ec_cart_data']->cart_data->billing_last_name,
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1,
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2,
			$GLOBALS['ec_cart_data']->cart_data->billing_city,
			$GLOBALS['ec_cart_data']->cart_data->billing_state,
			$GLOBALS['ec_cart_data']->cart_data->billing_country,
			$GLOBALS['ec_cart_data']->cart_data->billing_zip,
			$GLOBALS['ec_cart_data']->cart_data->billing_phone,
			$GLOBALS['ec_cart_data']->cart_data->billing_company_name
		);
		$GLOBALS['ec_user']->setup_shipping_info_data(
			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name,
			$GLOBALS['ec_cart_data']->cart_data->shipping_last_name,
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1,
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2,
			$GLOBALS['ec_cart_data']->cart_data->shipping_city,
			$GLOBALS['ec_cart_data']->cart_data->shipping_state,
			$GLOBALS['ec_cart_data']->cart_data->shipping_country,
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip,
			$GLOBALS['ec_cart_data']->cart_data->shipping_phone,
			$GLOBALS['ec_cart_data']->cart_data->shipping_company_name
		);

		if ( $is_subscriber ) {
			$this->mysqli->insert_subscriber( 
				sanitize_email( $GLOBALS['ec_user']->email ),
				sanitize_text_field( $_POST['billing_details']['first_name'] ), 
				sanitize_text_field( $_POST['billing_details']['last_name'] ) 
			);
			do_action( 'wpeasycart_insert_subscriber', sanitize_email( $GLOBALS['ec_user']->email ), sanitize_text_field( $_POST['billing_details']['first_name'] ), sanitize_text_field( $_POST['billing_details']['last_name'] ) );

			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => sanitize_text_field( $_POST['billing_details']['first_name'] ),
					'lastname' => sanitize_text_field( $_POST['billing_details']['last_name'] ),
					'email' => sanitize_email( $GLOBALS['ec_user']->email ),
					'status' => $is_subscriber,
				), false );
			}
		}
		// END SUBSCRIBER

		// Possibly discount the initial fee
		$initial_fee = $product->subscription_signup_fee * $quantity;
		if ( $discount_total > $product->price + $option_price_onetime_adjustment ) {
			$remaining_discount = $discount_total - $product->price + $option_price_onetime_adjustment;
			$initial_fee = $initial_fee - $remaining_discount;
		}

		$customer_id = $GLOBALS['ec_user']->stripe_customer_id;

		$this->cart->subtotal = ( ( $product->price + $option_price_adjustment ) * $quantity ) + $option_price_onetime_adjustment;
		for ( $i = 0; $i < count( $subscription_cart ); $i++ ) {
			$subscription_cart[$i]->item_total = $subscription_cart[$i]->item_total - round( ( $subscription_cart[$i]->item_total / ( $this->cart->subtotal + $shipping_total ) ) * $discount_total, 2 );
		}

		if ( $product->is_taxable || $product->vat_rate ) {
			$taxable_subtotal = 0;
			$vatable_subtotal = 0;
			if ( $product->is_taxable ) {
				$taxable_subtotal = $product->price * $quantity + $option_price_onetime_adjustment - $discount_total;
			}
			if ( $product->vat_rate ) {
				$vatable_subtotal = $product->price * $quantity + $option_price_onetime_adjustment - $discount_total;
			}

			do_action( 'wpeasycart_cart_subscription_pre_tax', $product, $quantity, $shipping_total, $handling_total, $discount_total );

			if ( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ) {
				wpeasycart_taxcloud()->setup_subscription_for_tax( $product, $quantity, $discount_total, 0, $option_price_onetime_adjustment );
			}
			if ( function_exists( 'wpeasycart_taxjar' ) && wpeasycart_taxjar()->is_enabled() ) {
				wpeasycart_taxjar()->setup_subscription_for_tax( $product, $quantity, $discount_total, 0, $option_price_onetime_adjustment );
			}
			$this->tax = new ec_tax( $product->price * $quantity + $option_price_onetime_adjustment, $taxable_subtotal, $vatable_subtotal, sanitize_text_field( $_POST['billing_details']['address']['state'] ), sanitize_text_field( $_POST['billing_details']['address']['country'] ), $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $product->handling_price_each * $quantity ) + $product->handling_price ), $subscription_cart, true );
		} else {
			$this->tax = new ec_tax( 0, 0, 0, sanitize_text_field( $_POST['billing_details']['address']['state'] ), sanitize_text_field( $_POST['billing_details']['address']['country'] ), $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $product->handling_price_each * $quantity ) + $product->handling_price ), $subscription_cart, true );
		}

		$this->order_totals = new ec_order_totals( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount );

		$need_to_update_customer_id = false;
		$customer_insert_test = false;

		$customer_balance_adj = $initial_fee;
		if ( ! $product->subscription_shipping_recurring ) {
			$customer_balance_adj += $shipping_total + $this->tax->shipping_tax_total + $this->tax->shipping_vat_total - $shipping_discount;
		}

		if ( $customer_id == "" ) {
			$customer_id = $stripe->insert_customer( $GLOBALS['ec_user'], NULL, $customer_balance_adj );
			$need_to_update_customer_id = true;

		} else {
			$found_customer = $stripe->update_customer( $GLOBALS['ec_user'], $customer_balance_adj );
			if ( !$found_customer ) { // Likely switched from test to live or to a new account, so customer id was wrong
				$customer_id = $stripe->insert_customer( $GLOBALS['ec_user'], NULL, $customer_balance_adj );
				$need_to_update_customer_id = true;
			}
		}

		if ( $need_to_update_customer_id && $customer_id ) { // Customer inserted to stripe successfully
			$this->mysqli->update_user_stripe_id( $GLOBALS['ec_user']->user_id, $customer_id );
			$GLOBALS['ec_user']->stripe_customer_id = $customer_id;
			$customer_insert_test = true;
		} else if ( $need_to_update_customer_id && !$customer_id ) {
			$customer_insert_test = false;
		} else {
			$customer_insert_test = true;
		}

		// Failed to Insert/Update Customer
		if ( ! $customer_insert_test ) { 
			return array( 'error' => 'customer_error' );
		}

		$plan_added = $product->stripe_plan_added;
		$plan_check = $stripe->get_plan( $product );

		if ( ! $product->stripe_plan_added ) { // Add plan if needed
			$plan_added = $stripe->insert_plan( $product );
			$this->mysqli->update_product_stripe_added( $product->product_id );
		} else if ( !$plan_check || $plan_check->amount != (int) ( $product->price * 100 ) ) {
			$plan_added = $stripe->insert_plan( $product );
		}

		// Failed to add plan
		if ( ! $plan_added ) {
			$plan_added = $stripe->get_plan( $product );
			if ( $plan_added ) {
				$this->mysqli->update_product_stripe_added( $product->product_id );
			} else {
				return array( 'error' => 'plan_error' );
			}
		}

		// Add customer to payment intent
		$card_response = $stripe->insert_card( $GLOBALS['ec_user'], sanitize_text_field( $_POST['stripeToken'] ) );
		if ( ! $card_response ) {
			return array( 'error' => 'payment_fail' );
		}
		$default_response = $stripe->set_default_payment_method( $card_response, $GLOBALS['ec_user'] );

		$prorate = "false";
		if ( $product->subscription_prorate ) {
			$prorate = "true";
		}
		$trial_end_date = NULL;
		if ( $product->trial_period_days > 0 ) {
			$trial_end_date = strtotime( "+" . $product->trial_period_days . " days" );
		}
		$tax_rates = $this->tax->get_stripe_tax_rates(  $product->is_taxable, ( $product->vat_rate > 0 ), ( $this->order_totals->tax_total / ( $product->price * $quantity + $option_price_onetime_adjustment - $discount_total ) ) );
		$tax_rates = apply_filters( 'wp_easycart_subscription_tax_rates_pre_insert', $tax_rates, $product, ( $product->vat_rate > 0 ), ( $this->order_totals->tax_total / ( $product->price * $quantity + $option_price_onetime_adjustment - $discount_total ) ), $sub_total, $shipping_total );
		$stripe_response = $stripe->insert_subscription( $product, $GLOBALS['ec_user'], $card_response, $coupon, $prorate, $trial_end_date, $quantity, number_format( $this->tax->get_tax_rate(), 2, '.', '' ), $subscription_plan_options, $tax_rates, $subscription_plan_quantities, $stripe_shipping_plan_id );
		if ( $stripe_response === false ) {
			return array( 'error' => 'subscription_fail' );
		}

		$subscription_id = $this->mysqli->insert_stripe_subscription( $stripe_response, $product, $GLOBALS['ec_user'], NULL, $quantity );

		return array( 
			'subscription_id' 	=> $subscription_id,
			'status'			=> $stripe_response->latest_invoice->status,
			'clientSecret'		=> $stripe_response->latest_invoice->payment_intent->client_secret,
			'paymentintent_id'	=> $stripe_response->latest_invoice->payment_intent->id,
			'stripe_charge_id'	=> $stripe_response->latest_invoice->charge
		);
	}

	public function submit_paypal_order() {

		global $wpdb;

		$this->order->submit_order( "third_party" );
		$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET orderstatus_id = 10 WHERE order_id = %d", $this->order->order_id ) );

		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log( order_id, order_log_key ) VALUES( %d, "order-status-update" )', $this->order->order_id ) );
		$order_log_id = $wpdb->insert_id;
		$wpdb->query( $wpdb->prepare( 'INSERT INTO ec_order_log_meta( order_log_id, order_id, order_log_meta_key, order_log_meta_value ) VALUES( %d, %d, "orderstatus_id", %s )', $order_log_id, $this->order->order_id, 10 ) );

		do_action( 'wpeasycart_order_paid', $order_id );
		$this->order->send_email_receipt();

		$GLOBALS['ec_cart_data']->save_session_to_db();

		return $this->order->order_id;

	}

	public function update_authorized_paypal_order( $paypal_response ) {

		if ( isset( $_GET['ec_firstpage'] ) ) {

			if ( isset( $paypal_response->payer ) )
				$payer_info = $paypal_response->payer->payer_info;
			else
				$payer_info = $paypal_response->payer_info;

			$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $payer_info->first_name );
			$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $payer_info->last_name );
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $payer_info->shipping_address->line1 );
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $payer_info->shipping_address->line2 );
			$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $payer_info->shipping_address->city );
			$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $payer_info->shipping_address->state );
			$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $payer_info->shipping_address->postal_code );
			$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $payer_info->shipping_address->country_code );
			if ( isset( $payer_info->phone ) && $payer_info->phone != "" ) {
				$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $payer_info->phone );
			}

			$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";
			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $payer_info->first_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $payer_info->last_name );
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $payer_info->shipping_address->line1 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $payer_info->shipping_address->line2 );
			$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $payer_info->shipping_address->city );
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $payer_info->shipping_address->state );
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $payer_info->shipping_address->postal_code );
			$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $payer_info->shipping_address->country_code );
			if ( isset( $payer_info->phone ) && $payer_info->phone != "" ) {
				$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $payer_info->phone );
			}

			$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $payer_info->email );
			$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( $payer_info->first_name . " " . $payer_info->last_name );
			$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $payer_info->first_name );
			$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $payer_info->last_name );

			$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
			$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );

			$GLOBALS['ec_cart_data']->save_session_to_db();
			do_action( 'wpeasycart_cart_updated' );
		}

	}

	public function submit_authorized_paypal_order() {

		global $wpdb;

		// Create Order
		$this->order->submit_order( "third_party" );

		// Execute payment
		$paypal = new ec_paypal();
		$result = $paypal->execute_order( $this->order->order_id, $this->cart, $this->order_totals, $this->tax );

		// Update Order or Remove Order
		if ( $result ) {

			$ec_db_admin = new ec_db_admin();
			$order_row = $ec_db_admin->get_order_row_admin( $this->order->order_id );
			$orderdetails = $ec_db_admin->get_order_details_admin( $this->order->order_id );

			// Clear tempcart
			$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
			$this->order->clear_session();

			$GLOBALS['ec_cart_data']->save_session_to_db();
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );	
		} else {
			$this->mysqli->remove_order( $this->order->order_id );
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_cart_error=payment_failed" );
		}
		die();

	}

	public function insert_ideal_order( $source, $payment_intent = false ) {
		global $wpdb;
		$this->order->submit_order( "ideal" );
		$order_id = $this->order->order_id;
		$order_gateway = 'stripe_connect';
		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
			$order_gateway = 'stripe';
		}
		if ( $payment_intent && isset( $payment_intent->latest_charge ) ) {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_gateway = %s, gateway_transaction_id = %s, payment_method = %s, stripe_charge_id = %s WHERE order_id = %d", $order_gateway, $source['id'] . ':' . $source['client_secret'], get_option( 'ec_option_payment_process_method' ), $payment_intent->latest_charge, $order_id ) );
		} else {
			$wpdb->query( $wpdb->prepare( "UPDATE ec_order SET order_gateway = %s, gateway_transaction_id = %s, payment_method = %s WHERE order_id = %d", $order_gateway, $source['id'] . ':' . $source['client_secret'], get_option( 'ec_option_payment_process_method' ), $order_id ) );
		}
		return $order_id;
	}

	private function process_submit_order() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-submit-order-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&cart_error=invalid_nonce" );
			die();
		}

		if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
			$first_name = $GLOBALS['ec_cart_data']->cart_data->billing_first_name;
			$last_name = $GLOBALS['ec_cart_data']->cart_data->billing_last_name;
			$email = $GLOBALS['ec_cart_data']->cart_data->email;

			$this->mysqli->insert_subscriber( $email, $first_name, $last_name );

			if ( $GLOBALS['ec_user']->user_id ) {
				global $wpdb;
				$wpdb->query( $wpdb->prepare( "UPDATE ec_user SET is_subscriber = 1 WHERE ec_user.user_id = %d", $GLOBALS['ec_user']->user_id ) );
			}

			// MyMail Hook
			if ( function_exists( 'mailster' ) ) {
				$subscriber_id = mailster('subscribers')->add(array(
					'firstname' => $first_name,
					'lastname' => $last_name,
					'email' => $email,
					'status' => 1,
				), false );
			}
			
			do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
		}

		if ( isset( $_POST['ec_cart_payment_selection'] ) && 'amazonpay' == sanitize_text_field( $_POST['ec_cart_payment_selection'] ) ) {
			global $wpdb;
			$this->order->submit_order( "amazonpay" );
			$amazonpay = new ec_amazonpay( );
			$amazonpay->process_order( $this->cart_page, $this->permalink_divider, $this->order_totals->grand_total, $this->order->order_id ); 
			
		} else if ( isset( $_POST['paypal_payment_id'] ) || isset( $_POST['paypal_order_id'] ) ) {
			global $wpdb;

			// Create Order
			$this->order->submit_order( "third_party" );

			// Execute payment
			$paypal = new ec_paypal();
			if ( isset( $_POST['paypal_order_id'] ) )
				$result = $paypal->execute_order( $this->order->order_id, $this->cart, $this->order_totals, $this->tax );

			else
				$result = $paypal->execute_payment( $this->order->order_id, $this->cart, $this->order_totals, $this->tax );

			// Update Order or Remove Order
			if ( $result ) {

				$ec_db_admin = new ec_db_admin();
				$order_row = $ec_db_admin->get_order_row_admin( $this->order->order_id );
				$orderdetails = $ec_db_admin->get_order_details_admin( $this->order->order_id );
				if ( $order_row && !isset( $_POST['paypal_order_id'] ) ) {

					/* Update Stock Quantity */
					foreach( $orderdetails as $orderdetail ) {
						$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
						if ( $product ) {
							if ( $product->use_optionitem_quantity_tracking )	
								$ec_db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
							$ec_db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
							$this->mysqli->update_details_stock_adjusted( $orderdetail->orderdetail_id );
						}
					}

					// Update Order Status/Send Alerts
					if ( $result == 'approved' ) {
						$ec_db_admin->update_order_status( $this->order->order_id, "10" );
						do_action( 'wpeasycart_order_paid', $this->order->order_id );
					}

					// send email
					$order_display = new ec_orderdisplay( $order_row, true, true );
					$order_display->send_email_receipt();
					$order_display->send_gift_cards();
				} else {
					do_action( 'wpeasycart_order_complete', $this->order->order_id, $order_status );
				}

				// Clear tempcart
				$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
				$this->order->clear_session();

				$GLOBALS['ec_cart_data']->save_session_to_db();
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );	
			} else {
				$this->mysqli->remove_order( $this->order->order_id );
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );
			}
			die();

		}

		if ( $GLOBALS['ec_cart_data']->cart_data->email == "" ) {

			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=session_expired" );

		} else if ( !$this->validate_submit_order_data() ) {

			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_address" );

		} else {

			if ( get_option( 'ec_option_skip_shipping_page' ) ) {
				$this->shipping->skip_shipping_selection_page();
			}

			if ( isset( $_POST['ec_cart_payment_selection'] ) )
				$payment_type = sanitize_text_field( $_POST['ec_cart_payment_selection'] );
			else if ( $this->is_affirm )
				$payment_type = "affirm";
			else
				$payment_type = wp_easycart_language()->get_text( "ec_success", "cart_account_free_order" );

			if ( isset( $_POST['ec_order_notes'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->order_notes = stripslashes( sanitize_textarea_field( $_POST['ec_order_notes'] ) );
			}

			/************************************** 
			Place 3Ds Payment Processing HERE
			***************************************/
			if ( get_option( 'ec_option_payment_process_method' ) == "nmi" && get_option( 'ec_option_nmi_3ds' ) == "2" ) { // 3D Secure

				$response = $this->order->submit_order( $payment_type );

				if ( $response ) {

					$gateway = new ec_cardinal();
					$gateway->initialize( $this->cart, $this->user, $this->shipping, $this->tax, 
										  $this->discount, $this->payment->credit_card, $this->order_totals, $this->order->order_id );

					$response = $gateway->secure_3d_lookup();

					if ( $response == "ERROR" ) { // Failed to Process CC at Cardinal
						$this->mysqli->remove_order( $this->order->order_id );
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );

					} else if ( $response == "NO3DS" ) {
						$this->process_nmi_no_3ds();

					} else { // NO 3DS for User, Process Normally
						$submit_return_val = $this->order->submit_order( $payment_type );
						if ( $submit_return_val == "1" ) {
							$GLOBALS['ec_cart_data']->save_session_to_db();
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );	
						} else {
							$this->mysqli->remove_order( $order_id );
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );
						}

					}

				} else { // order failed to insert
					header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );

				}

			/************************************** 
			Place Standard Payment Processing HERE
			***************************************/
			} else { // Process Non-3D Secure (V3.2.4 and higher 3Ds)

				$submit_return_val = $this->order->submit_order( $payment_type );
				do_action( 'wpeasycart_submit_order_complete' );

				if ( $this->order_totals->grand_total <= 0 ) {
					$GLOBALS['ec_cart_data']->save_session_to_db();
					header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );	

				} else if ( $payment_type == "manual_bill" ) { // Show fail message or the success landing page (including the manual bill notice).
					if ( $submit_return_val == "1" ) {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );
					} else {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&cart_error=manualbill_failed" );
					}

				} else if ( $payment_type == "affirm" ) {
					if ( $submit_return_val == "1" ) {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );
					} else {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );
					}

				} else if ( $payment_type == "third_party" ) { // Show the third party landing page
					if ( $submit_return_val == "1" ) {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=third_party&order_id=" . $this->order->order_id );
					} else {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=thirdparty_failed" );
					}

				} else { // Either show the success landing page

					if ( $submit_return_val == "1" ) {
						if ( $this->order->payment->is_3d_auth )
							$this->auth_3d_form();
						else {
							$GLOBALS['ec_cart_data']->save_session_to_db();
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $this->order->order_id );	
						}

					} else {
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=payment_failed" );
					}

				}

			}

		}

	}

	public function auth_3d_form() {
		echo "<form name=\"ec_cart_3dauth_form\" method=\"POST\" action=\"" . esc_attr( $this->order->payment->post_url ) . "\">";
		if( $this->order->payment->post_id_input_name ) {
			echo "<input type=\"hidden\" name=\"" . esc_attr( $this->order->payment->post_id_input_name ) . "\" value=\"" . esc_attr( $this->order->payment->post_id ) . "\">";
		}
		if( $this->order->payment->post_message_input_name ) {
			echo "<input type=\"hidden\" name=\"" . esc_attr( $this->order->payment->post_message_input_name ) . "\" value=\"" . esc_attr( $this->order->payment->post_message ) . "\">";
		}
		if( $this->order->payment->post_return_url_input_name ) {
			echo "<input type=\"hidden\" name=\"" . esc_attr( $this->order->payment->post_return_url_input_name ) . "\" value=\"" . esc_attr( $this->cart_page . $this->permalink_divider ) . "ec_page=3dsecure&order_id=" . esc_attr( $this->order->order_id ) . "\">";
		}
		echo "</form>";
		echo "<SCRIPT LANGUAGE=\"Javascript\">document.ec_cart_3dauth_form.submit();</SCRIPT>";
	}

	public function process_nmi_no_3ds() {

		$gateway = new ec_nmi();
		if ( isset( $_POST['ec_expiration_month'] ) && isset( $_POST['ec_expiration_year'] ) ) {
			$exp_month = sanitize_text_field( $_POST['ec_expiration_month'] );
			$exp_year = sanitize_text_field( $_POST['ec_expiration_year'] );
		} else {
			$exp_date = sanitize_text_field( $_POST['ec_cc_expiration'] );
			$exp_month = substr( $exp_date, 0, 2 );
			$exp_year = substr( $exp_date, 5 );
			if ( strlen( $exp_year ) == 2 ) {
				$exp_year = "20" . $exp_year;
			}
		}
		$credit_card = new ec_credit_card( 
			$this->get_payment_type( $this->sanatize_card_number( sanitize_text_field($_POST['ec_card_number'] ) ) ), 
			stripslashes( sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) ) . " " . stripslashes( sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) ),
			$this->sanatize_card_number( sanitize_text_field($_POST['ec_card_number'] ) ),
			$exp_month,
			$exp_year,
			sanitize_text_field( $_POST['ec_security_code'] )
		);
		$gateway->initialize( $this->cart, $this->user, $this->shipping, $this->tax, $this->discount, $credit_card, $this->order_totals, (int) $_POST['order_id'] );
		$result = $gateway->process_credit_card();

		if ( $result ) {

			$this->mysqli->update_order_status( (int) $_POST['order_id'], "6" );

			do_action( 'wpeasycart_order_paid', (int) $_POST['order_id'] );

			$db_admin = new ec_db_admin();
			$order_row = $db_admin->get_order_row_admin( (int) $_POST['order_id'] );
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();

			$this->mysqli->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
			$GLOBALS['ec_cart_data']->checkout_session_complete();

			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_POST['order_id'] );

		} else {
			$this->mysqli->remove_order( (int) $_POST['order_id'] );
			$GLOBALS['ec_cart_data']->save_session_to_db();	
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );	
		}

	}

	public function process_3ds_final() {

		$gateway = new ec_nmi();
		if ( isset( $_POST['ec_expiration_month'] ) && isset( $_POST['ec_expiration_year'] ) ) {
			$exp_month = sanitize_text_field( $_POST['ec_expiration_month'] );
			$exp_year = sanitize_text_field( $_POST['ec_expiration_year'] );
		} else {
			$exp_date = sanitize_text_field( $_POST['ec_cc_expiration'] );
			$exp_month = substr( $exp_date, 0, 2 );
			$exp_year = substr( $exp_date, 5 );
			if ( strlen( $exp_year ) == 2 ) {
				$exp_year = "20" . $exp_year;
			}
		}
		$credit_card = new ec_credit_card( 
			$this->get_payment_type( $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ) ),
			stripslashes( sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_first_name ) ) . " " . stripslashes( sanitize_text_field( $GLOBALS['ec_cart_data']->cart_data->billing_last_name ) ),
			$this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ),
			$exp_month,
			$exp_year,
			sanitize_text_field( $_POST['ec_security_code'] )
		);
		$gateway->initialize( $this->cart, $this->user, $this->shipping, $this->tax, $this->discount, $credit_card, $this->order_totals, (int) $_POST['order_id'] );
		$result = $gateway->process_3ds();

		if ( $result ) {

			$this->mysqli->update_order_status( (int) $_POST['order_id'], "6" );

			do_action( 'wpeasycart_order_paid', (int) $_POST['order_id'] );

			$db_admin = new ec_db_admin();
			$order_row = $db_admin->get_order_row_admin( (int) $_POST['order_id'] );
			$order_display = new ec_orderdisplay( $order_row, true, true );
			$order_display->send_email_receipt();

			$this->mysqli->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
			$GLOBALS['ec_cart_data']->checkout_session_complete();

			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_POST['order_id'] );

		} else {
			$this->mysqli->remove_order( (int) $_POST['order_id'] );
			$GLOBALS['ec_cart_data']->save_session_to_db();	
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );	
		}

	}

	public function process_3dsecure_response() {

		global $wpdb;
		$success = false;

		// Check if order has already been approved, fixing data error.
		$order = $wpdb->get_row( $wpdb->prepare( "SELECT ec_order.order_id FROM ec_order, ec_orderstatus WHERE ec_order.order_id = %d AND ec_order.orderstatus_id = ec_orderstatus.status_id AND ec_orderstatus.is_approved = 1", (int) $_GET['order_id'] ) );
		if ( $order ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_GET['order_id'] );
			die();
		}

		// Verify the order with the gateway
		if ( get_option( 'ec_option_payment_process_method' ) == "sagepay" ) {
			$gateway = new ec_sagepay();
		} else if ( get_option( 'ec_option_payment_process_method' ) == "realex" ) {
			$gateway = new ec_realex();
		}

		if ( isset( $gateway ) ) {
			$success = $gateway->secure_3d_auth();
			if ( $success ) {

				do_action( 'wpeasycart_order_paid', (int) $_GET['order_id'] );

				$this->order->clear_session();
				if ( $this->discount->giftcard_code )
					$this->mysqli->update_giftcard_total( $this->discount->giftcard_code, $this->discount->giftcard_discount );

				$GLOBALS['ec_cart_data']->save_session_to_db();		
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_GET['order_id'] );
				die();
			}
		}

		if ( !$success ) {
			$this->mysqli->remove_order( (int) $_GET['order_id'] );
			$GLOBALS['ec_cart_data']->save_session_to_db();	
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );
			die();
		}	
	}

	public function process_3ds_response() {

		if ( isset( $_GET['order_id'] ) ) {
			$order_id = (int) $_GET['order_id'];
			$db = new ec_db_admin();
			$order = $db->get_order_row_admin( $order_id );
			if ( $order ) {
				$gateway = new ec_cardinal();
				$response = $gateway->secure_3d_auth( $order_id, $order, $_POST );

				if ( !$response ) {
					header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );
				}

			} else {// No VALID Order ID Returned, Likely Fraud
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );

			}
		} else {// No Order ID Returned, Likely Fraud
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=3dsecure_failed" );
		}

	}

	private function process_realex_redirect() {
		// Check response, if success, send to success page. If failed, return to last page of cart
		if ( isset( $_POST['AUTHCODE'] ) && isset( $_POST['ORDER_ID'] ) && $_POST['AUTHCODE'] == "00" )
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_POST['ORDER_ID'] );
		else
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment&ec_cart_error=thirdparty_failed" );
	}

	private function process_realex_response() {
		if ( isset( $_POST['ORDER_ID'] ) ) {

			global $wpdb;
			$mysqli = new ec_db();

			$response_string = print_r( $_POST, true );

			$realex_merchant_id = get_option( 'ec_option_realex_thirdparty_merchant_id' );
			$realex_secret = get_option( 'ec_option_realex_thirdparty_secret' );
			$realex_currency = get_option( 'ec_option_realex_thirdparty_currency' );

			$timestamp = sanitize_text_field( $_POST['TIMESTAMP'] );
			$result = sanitize_text_field( $_POST['RESULT'] );
			$order_id = sanitize_text_field( $_POST['ORDER_ID'] );
			$message = sanitize_text_field( $_POST['MESSAGE'] );
			$authcode = sanitize_text_field( $_POST['AUTHCODE'] );
			$pasref = sanitize_text_field( $_POST['PASREF'] );
			$realexmd5 = sanitize_text_field( $_POST['MD5HASH'] );

			$tmp = "$timestamp.$realex_merchant_id.$order_id.$result.$message.$pasref.$authcode";

			$md5hash = md5($tmp);
			$tmp_md5 = "$md5hash.$realex_secret";
			$md5hash = md5($tmp_md5);

			$sha1hash = sha1($tmp);
			$tmp_sha1 = "$sha1hash.$realex_secret";
			$sha1hash = sha1($tmp_sha1);

			if ( $md5hash == $_POST['MD5HASH'] && $sha1hash == $_POST['SHA1HASH'] ) {
				$mysqli->insert_response( $order_id, 0, "Realex Third Party", $response_string );
				if ( $_POST['RESULT'] == '00' ) { 
					$mysqli->update_order_status( $order_id, "10" );
					do_action( 'wpeasycart_order_paid', $orderid );
					$db_admin = new ec_db_admin();
					$order_row = $db_admin->get_order_row_admin( $order_id );
					$orderdetails = $db_admin->get_order_details_admin( $order_id );
					foreach ( $orderdetails as $orderdetail ) {
						$product = $wpdb->get_row( $wpdb->prepare( "SELECT ec_product.* FROM ec_product WHERE ec_product.product_id = %d", $orderdetail->product_id ) );
						if ( $product ) {
							if ( $product->use_optionitem_quantity_tracking )	
								$db_admin->update_quantity_value( $orderdetail->quantity, $orderdetail->product_id, $orderdetail->optionitem_id_1, $orderdetail->optionitem_id_2, $orderdetail->optionitem_id_3, $orderdetail->optionitem_id_4, $orderdetail->optionitem_id_5 );
							$db_admin->update_product_stock( $orderdetail->product_id, $orderdetail->quantity );
						}
					}
					$order_display = new ec_orderdisplay( $order_row, true, true );
					$order_display->send_email_receipt();
					$order_display->send_gift_cards();
				} else if ( $_POST['AUTHCODE'] == 'refund' ) { 
					$mysqli->update_order_status( $order_id, "16" );
					do_action( 'wpeasycart_full_order_refund', $orderid );
				} else {
					$mysqli->update_order_status( $order_id, "8" );
				}
			}
		}
	}

	private function process_paymentexpress_thirdparty_response() {
		$gateway = new ec_paymentexpress_thirdparty();
		$gateway->update_order_status();
		$db = new ec_db();
		$db->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$GLOBALS['ec_cart_data']->save_session_to_db();	
		header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . (int) $_GET['order_id'] );
	}

	private function process_third_party_forward() {
		$this->payment->third_party->initialize( (int) $_GET['order_id'] );
		$this->payment->third_party->display_auto_forwarding_form();
		die();
	}

	public function process_login_user( $redirect = true ) {
		$recaptcha_valid = true;
		if ( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) ) {
			if ( ! isset( $_POST['ec_grecaptcha_response_login'] ) || '' == $_POST['ec_grecaptcha_response_login'] ) {
				if ( $redirect ) {
					header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&account_error=login_failed' );
					die();
				} else {
					return (object) array(
						'success' => false,
						'error' => 'login_failed',
						'url' => $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&account_error=login_failed',
					);
				}
			}

			$db = new ec_db_admin();
			$recaptcha_response = sanitize_text_field( $_POST['ec_grecaptcha_response_login'] );

			$data = array(
				'secret' => get_option( 'ec_option_recaptcha_secret_key' ),
				'response' => $recaptcha_response,
			);

			$request = new WP_Http;
			$response = $request->request(
				"https://www.google.com/recaptcha/api/siteverify", 
				array( 
					'method' => 'POST', 
					'body' => http_build_query( $data ),
					'timeout' => 30,
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$db->insert_response( 0, 1, 'GOOGLE RECAPTCHA CURL ERROR', $error_message );
				$response = (object) array(
					'error' => $error_message
				);
			} else {
				$response = json_decode( $response['body'] );
				$db->insert_response( 0, 0, 'Google Recaptcha Response', print_r( $response, true ) );
			}

			$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
		}

		if ( $recaptcha_valid ) {
			$email = trim( sanitize_email( $_POST['ec_cart_login_email'] ) );
			$password = $_POST['ec_cart_login_password']; // XSS OK. Password should not be sanitized.
			$password_hash = md5( $password );
			$password_hash = apply_filters( 'wpeasycart_password_hash', $password_hash, $password );

			do_action( 'wpeasycart_pre_login_attempt', $email );
			$user = $this->mysqli->get_user_login( $email, $password, $password_hash );

			if ( $user && 'pending' == $user->user_level ) {
				$GLOBALS['ec_cart_data']->save_session_to_db();
				do_action( 'wpeasycart_cart_updated' );
				if ( isset( $_POST['ec_cart_subscription'] ) ) {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&ec_cart_error=not_activated&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ) );
					} else {
						return (object) array(
							'success' => false,
							'error' => 'not_activated',
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&ec_cart_error=not_activated&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ),
						);
					}
				} else {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&ec_cart_error=not_activated' );
					} else {
						return (object) array(
							'success' => false,
							'error' => 'not_activated',
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&ec_cart_error=not_activated',
						);
					}
				}

			} else if ( 'guest' == $email ) {
				$GLOBALS['ec_cart_data']->cart_data->email = 'guest';
				$GLOBALS['ec_cart_data']->cart_data->username = 'guest';
				$GLOBALS['ec_cart_data']->save_session_to_db();
				do_action( 'wpeasycart_cart_updated' );
				if ( isset( $_POST['ec_cart_subscription'] ) ) {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ) );
					} else {
						return (object) array(
							'success' => true,
							'error' => false,
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ),
						);
					}
				} else {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info' );
					} else {
						return (object) array(
							'success' => true,
							'error' => false,
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info',
						);
					}
				}
			} else if ( $user ) {
				if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
					$wp_user = wp_signon(
						array( 
							'user_login' => $email,
							'user_password' => $_POST['ec_cart_login_password'] // XSS OK. Should not sanitize password.
						),
						false
					);
				}

				do_action( 'wpeasycart_login_success', $email );
				$GLOBALS['ec_cart_data']->cart_data->billing_first_name = sanitize_text_field( $user->billing_first_name );
				$GLOBALS['ec_cart_data']->cart_data->billing_last_name = sanitize_text_field( $user->billing_last_name );
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = sanitize_text_field( $user->billing_address_line_1 );
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = sanitize_text_field( $user->billing_address_line_2 );
				$GLOBALS['ec_cart_data']->cart_data->billing_city = sanitize_text_field( $user->billing_city );
				$GLOBALS['ec_cart_data']->cart_data->billing_state = sanitize_text_field( $user->billing_state );
				$GLOBALS['ec_cart_data']->cart_data->billing_zip = sanitize_text_field( $user->billing_zip );
				$GLOBALS['ec_cart_data']->cart_data->billing_country = sanitize_text_field( $user->billing_country );
				$GLOBALS['ec_cart_data']->cart_data->billing_phone = sanitize_text_field( $user->billing_phone );

				$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";
				$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = sanitize_text_field( $user->shipping_first_name );
				$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = sanitize_text_field( $user->shipping_last_name );
				$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = sanitize_text_field( $user->shipping_address_line_1 );
				$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = sanitize_text_field( $user->shipping_address_line_2 );
				$GLOBALS['ec_cart_data']->cart_data->shipping_city = sanitize_text_field( $user->shipping_city );
				$GLOBALS['ec_cart_data']->cart_data->shipping_state = sanitize_text_field( $user->shipping_state );
				$GLOBALS['ec_cart_data']->cart_data->shipping_zip = sanitize_text_field( $user->shipping_zip );
				$GLOBALS['ec_cart_data']->cart_data->shipping_country = sanitize_text_field( $user->shipping_country );
				$GLOBALS['ec_cart_data']->cart_data->shipping_phone = sanitize_text_field( $user->shipping_phone );
				$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
				$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

				$GLOBALS['ec_cart_data']->cart_data->user_id = (int) $user->user_id;
				$GLOBALS['ec_cart_data']->cart_data->email = sanitize_email( $email );
				$GLOBALS['ec_cart_data']->cart_data->username = sanitize_text_field( $user->first_name . " " . $user->last_name );
				$GLOBALS['ec_cart_data']->cart_data->first_name = sanitize_text_field( $user->first_name );
				$GLOBALS['ec_cart_data']->cart_data->last_name = sanitize_text_field( $user->last_name );

				$GLOBALS['ec_cart_data']->save_session_to_db();
				do_action( 'wpeasycart_cart_updated' );
				if ( isset( $GLOBALS['ec_cart_data']->cart_data->cart_subscription ) && '' != $GLOBALS['ec_cart_data']->cart_data->cart_subscription ) {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ) );
					} else {
						return (object) array(
							'success' => true,
							'error' => false,
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ),
						);
					}
				} else if ( isset( $_POST['ec_cart_model_number'] ) ) {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_model_number'] ) );
					} else {
						return (object) array(
							'success' => true,
							'error' => false,
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_model_number'] ),
						);
					}
				} else {
					if ( get_option ( 'ec_option_onepage_checkout' ) ) {
						if ( $redirect ) {
							header( 'location: ' . $this->cart_page . $this->permalink_divider . 'eccheckout=information' );
						} else {
							return (object) array(
								'success' => true,
								'error' => false,
								'url' => $this->cart_page . $this->permalink_divider . 'eccheckout=information',
							);
						}
					} else {
						if ( $redirect ) {
							header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info' );
						} else {
							return (object) array(
								'success' => true,
								'error' => false,
								'url' => $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info',
							);
						}
					}
				}
			} else {
				do_action( 'wpeasycart_login_failed', $email );
				$GLOBALS['ec_cart_data']->save_session_to_db();
				if ( isset( $_POST['ec_cart_subscription'] ) ) {
					if ( $redirect ) {
						header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ) . '&ec_cart_error=login_failed' );
					} else {
						return (object) array(
							'success' => false,
							'error' => 'login_failed',
							'url' => $this->cart_page . $this->permalink_divider . 'ec_page=subscription_info&subscription=' . sanitize_text_field( $_POST['ec_cart_subscription'] ) . '&ec_cart_error=login_failed',
						);
					}
				} else {
					if ( get_option ( 'ec_option_onepage_checkout' ) ) {
						if ( $redirect ) {
							header( 'location: ' . $this->cart_page . $this->permalink_divider . 'eccheckout=information&ec_cart_error=login_failed' );
						} else {
							return (object) array(
								'success' => false,
								'error' => 'login_failed',
								'url' => $this->cart_page . $this->permalink_divider . 'eccheckout=information&ec_cart_error=login_failed',
							);
						}
					} else {
						if ( $redirect ) {
							header( 'location: ' . $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&ec_cart_error=login_failed' );
						} else {
							return (object) array(
								'success' => false,
								'error' => 'login_failed',
								'url' => $this->cart_page . $this->permalink_divider . 'ec_page=checkout_info&ec_cart_error=login_failed',
							);
						}
					}
				}
			}
		}
	}

	private function process_logout_user() {

		$GLOBALS['ec_cart_data']->cart_data->user_id = "";
		$GLOBALS['ec_cart_data']->cart_data->email = "";
		$GLOBALS['ec_cart_data']->cart_data->username = "";
		$GLOBALS['ec_cart_data']->cart_data->first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->last_name = "";

		$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
		$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

		$GLOBALS['ec_cart_data']->cart_data->billing_first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_last_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_company_name = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_city = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_state = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_zip = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_country = "";
		$GLOBALS['ec_cart_data']->cart_data->billing_phone = "";
		$GLOBALS['ec_cart_data']->cart_data->vat_registration_number = "";

		$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";

		$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_city = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_state = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_zip = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_country = "";
		$GLOBALS['ec_cart_data']->cart_data->shipping_phone = "";

		$GLOBALS['ec_cart_data']->cart_data->first_name = "";
		$GLOBALS['ec_cart_data']->cart_data->last_name = "";

		$GLOBALS['ec_cart_data']->cart_data->create_account = "";

		$GLOBALS['ec_cart_data']->cart_data->order_notes = "";
		$GLOBALS['ec_cart_data']->cart_data->email_other = "";

		$GLOBALS['ec_cart_data']->cart_data->shipping_method = "";
		$GLOBALS['ec_cart_data']->cart_data->estimate_shipping_zip = "";
		$GLOBALS['ec_cart_data']->cart_data->estimate_shipping_country = "";

		$GLOBALS['ec_cart_data']->cart_data->stripe_paymentintent_id = "";
		$GLOBALS['ec_cart_data']->cart_data->stripe_pi_client_secret = "";
		$GLOBALS['ec_cart_data']->cart_data->amazon_session_id = "";
		$GLOBALS['ec_cart_data']->cart_data->amazon_buyer_id = "";
		$GLOBALS['ec_cart_data']->cart_data->amazon_payment_selection = "";

		$GLOBALS['ec_cart_data']->save_session_to_db();

		wp_cache_flush();

		if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
			wp_logout();
		}

		if ( isset( $_GET['subscription'] ) ) {
			header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . sanitize_text_field( $_GET['subscription'] ) );
		} else if ( !get_option( 'ec_option_skip_cart_login' ) && file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/admin_panel.php" ) ) {
			header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_login");
		} else {
			header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info");
		}
	}

	private function process_save_checkout_info() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-checkout-info-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&cart_error=invalid_nonce" );
			die();
		}

		if ( isset( $_POST['ec_login_selector'] ) ) {
			$this->process_login_user();

		} else {
			$this->process_save_checkout_info_helper();
		}

		do_action( 'wpeasycart_user_updated' );

	}

	private function process_save_checkout_info_helper() {
		$recaptcha_valid = true;
		if ( get_option( 'ec_option_enable_recaptcha' ) && get_option( 'ec_option_enable_recaptcha_cart' ) ) {

			if ( !isset( $_POST['ec_grecaptcha_response_register'] ) || $_POST['ec_grecaptcha_response_register'] == '' ) {
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&cart_error=register_invalid" );
				die();
			}

			$db = new ec_db_admin();
			$recaptcha_response = sanitize_text_field( $_POST['ec_grecaptcha_response_register'] );
			$data = array(
				"secret"	=> get_option( 'ec_option_recaptcha_secret_key' ),
				"response"	=> $recaptcha_response
			);

			$request = new WP_Http;
			$response = $request->request( 
				"https://www.google.com/recaptcha/api/siteverify", 
				array( 
					'method' => 'POST', 
					'body' => http_build_query( $data ),
					'timeout' => 30
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$db->insert_response( 0, 1, "GOOGLE RECAPTCHA CURL ERROR", $error_message );
				$response = (object) array( "error" => $error_message );
			} else {
				$response = json_decode( $response['body'] );
				$db->insert_response( 0, 0, "Google Recaptcha Response", print_r( $response, true ) );
			}

			$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
		}

		if ( $recaptcha_valid ) {

			$billing_country = $shipping_country = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_country'] ) );

			$billing_first_name = $shipping_first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_first_name'] ) );
			$billing_last_name = $shipping_last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_last_name'] ) );

			if ( isset( $_POST['ec_cart_billing_company_name'] ) ) {
				$billing_company_name = $shipping_company_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_company_name'] ) );
			} else {
				$billing_company_name = $shipping_company_name = "";
			}

			if ( isset( $_POST['ec_cart_billing_vat_registration_number'] ) ) {
				$vat_registration_number = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_vat_registration_number'] ) );
			} else {
				$vat_registration_number = "";
			}

			$billing_address = $shipping_address = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_address'] ) );
			if ( isset( $_POST['ec_cart_billing_address2'] ) ) {
				$billing_address2 = $shipping_address2 = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_address2'] ) );
			} else {
				$billing_address2 = $shipping_address2 = "";
			}

			$billing_city = $shipping_city = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_city'] ) );
			if ( isset( $_POST['ec_cart_billing_state_' . $billing_country] ) ) {
				$billing_state = $shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_state_' . $billing_country] ) );
			} else {
				$billing_state = $shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_state'] ) );
			}

			$billing_zip = $shipping_zip = trim( stripslashes( sanitize_text_field( $_POST['ec_cart_billing_zip'] ) ) );
			if ( isset( $_POST['ec_cart_billing_phone'] ) ) {
				$billing_phone = $shipping_phone = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_phone'] ) );
			} else {
				$billing_phone = "";
			}

			if ( isset( $_POST['ec_shipping_selector'] ) ) {
				$shipping_selector = sanitize_text_field( $_POST['ec_shipping_selector'] );
			} else {
				$shipping_selector = "false";
			}

			if ( $shipping_selector == 'true' && get_option( 'ec_option_use_shipping' ) && $this->shipping_address_allowed && ( $this->cart->shippable_total_items > 0 || $this->order_totals->handling_total > 0 || $this->cart->excluded_shippable_total_items > 0 ) ) {
				$shipping_country = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_country'] ) );

				$shipping_first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_first_name'] ) );
				$shipping_last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_last_name'] ) );

				if ( isset( $_POST['ec_cart_shipping_company_name'] ) ) {
					$shipping_company_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_company_name'] ) );
				} else {
					$shipping_company_name = "";
				}

				$shipping_address = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_address'] ) );
				if ( isset( $_POST['ec_cart_shipping_address2'] ) ) {
					$shipping_address2 = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_address2'] ) );
				} else {
					$shipping_address2 = "";
				}

				$shipping_city = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_city'] ) );

				if ( isset( $_POST['ec_cart_shipping_state_' . $shipping_country] ) ) {
					$shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_state_' . $shipping_country] ) );
				} else {
					$shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_state'] ) );
				}

				$shipping_zip = trim( stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_zip'] ) ) );
				if ( isset( $_POST['ec_cart_shipping_phone'] ) ) {
					$shipping_phone = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_phone'] ) );
				} else {
					$shipping_phone = "";
				}
			}

			if ( isset( $_POST['ec_order_notes'] ) ) {
				$order_notes = stripslashes( sanitize_textarea_field( $_POST['ec_order_notes'] ) );
			} else if ( $GLOBALS['ec_cart_data']->cart_data->order_notes != "" ) {
				$order_notes = sanitize_textarea_field( $GLOBALS['ec_cart_data']->cart_data->order_notes );
			} else {
				$order_notes = "";
			}

			if ( isset( $_POST['ec_contact_first_name'] ) ) {
				$first_name = stripslashes( sanitize_text_field( $_POST['ec_contact_first_name'] ) );
			} else if ( isset( $_POST['ec_cart_billing_first_name'] ) ) {
				$first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_first_name'] ) );
			} else {
				$first_name = "";
			}
			if ( isset( $_POST['ec_contact_last_name'] ) ) {
				$last_name = stripslashes( sanitize_text_field( $_POST['ec_contact_last_name'] ) );
			} else if ( isset( $_POST['ec_cart_billing_last_name'] ) ) {
				$last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_last_name'] ) );
			} else {
				$last_name = "";
			}

			if ( isset( $_POST['ec_contact_create_account'] ) )
				$create_account = sanitize_text_field( $_POST['ec_contact_create_account'] );
			else if ( isset( $_POST['ec_create_account_selector'] ) )
				$create_account = true;
			else
				$create_account = false;

			$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $billing_first_name;
			$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $billing_last_name;
			$GLOBALS['ec_cart_data']->cart_data->billing_company_name = $billing_company_name;
			$GLOBALS['ec_cart_data']->cart_data->vat_registration_number = $vat_registration_number;
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $billing_address;
			$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $billing_address2;
			$GLOBALS['ec_cart_data']->cart_data->billing_city = $billing_city;
			$GLOBALS['ec_cart_data']->cart_data->billing_state = $billing_state;
			$GLOBALS['ec_cart_data']->cart_data->billing_zip = $billing_zip;
			$GLOBALS['ec_cart_data']->cart_data->billing_country = $billing_country;
			$GLOBALS['ec_cart_data']->cart_data->billing_phone = $billing_phone;

			$GLOBALS['ec_cart_data']->cart_data->shipping_selector = $shipping_selector;

			$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = $shipping_first_name;
			$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = $shipping_last_name;
			$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = $shipping_company_name;
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $shipping_address;
			$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $shipping_address2;
			$GLOBALS['ec_cart_data']->cart_data->shipping_city = $shipping_city;
			$GLOBALS['ec_cart_data']->cart_data->shipping_state = $shipping_state;
			$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $shipping_zip;
			$GLOBALS['ec_cart_data']->cart_data->shipping_country = $shipping_country;
			$GLOBALS['ec_cart_data']->cart_data->shipping_phone = $shipping_phone;

			$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
			$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

			$GLOBALS['ec_cart_data']->cart_data->order_notes = $order_notes;
			$GLOBALS['ec_cart_data']->cart_data->email_other = sanitize_text_field( ( isset( $_POST['ec_email_other'] ) ) ? $_POST['ec_email_other'] : '' );

			$next_page = "checkout_shipping";
			if ( !get_option( 'ec_option_use_shipping' ) || $this->cart->shippable_total_items == 0 )
				$next_page = "checkout_payment";

			if ( get_option( 'ec_option_skip_shipping_page' ) || $GLOBALS['ec_user']->freeshipping )//|| $this->discount->shipping_discount == $this->discount->shipping_subtotal )
				$next_page = "checkout_payment";

			if ( isset( $_POST['ec_contact_email'] ) ) {
				$email = sanitize_email( $_POST['ec_contact_email'] );
				$GLOBALS['ec_cart_data']->cart_data->email = $email;
			}

			if ( isset( $_POST['ec_contact_email'] ) && !$create_account ) {
				$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
				$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
			} else if ( isset( $_POST['ec_contact_email'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
				$GLOBALS['ec_cart_data']->cart_data->guest_key = sanitize_text_field( $GLOBALS['ec_cart_data']->ec_cart_id );
			} else {
				$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
				$GLOBALS['ec_cart_data']->cart_data->guest_key = "";
			}

			do_action( 'wpeasycart_save_checkout_info_process' );

			$GLOBALS['ec_cart_data']->save_session_to_db();

			if ( !$this->validate_checkout_data() ) {

				header( "location: " . apply_filters( 'wp_easycart_invalid_checkout_details_url', $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_address" ) );

			} else if ( ! $this->validate_cart_shipping() ) {
				header( "location: " . apply_filters( 'wp_easycart_invalid_checkout_details_url', $this->cart_page . $this->permalink_divider . "ec_cart_error=invalid_cart_shipping" ) );

			} else {

				if ( $create_account ) {

					if ( $this->mysqli->does_user_exist( sanitize_email( $_POST['ec_contact_email'] ) ) ) {
						do_action( 'wpeasycart_cart_updated' );
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=email_exists");

					} else {
						$email = sanitize_email( $_POST['ec_contact_email'] );
						$password = md5( $_POST['ec_contact_password'] ); // XSS OK. Should not sanitize password.
						$password = apply_filters( 'wpeasycart_password_hash', $password, $_POST['ec_contact_password'] ); // XSS OK. Should not sanitize password.

						// INSERT USER
						$billing_id = $this->mysqli->insert_address( $billing_first_name, $billing_last_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $billing_company_name );
						$shipping_id = $this->mysqli->insert_address( $shipping_first_name, $shipping_last_name, $shipping_address, $shipping_address2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone, $shipping_company_name );

						$user_level = "shopper";
						if ( isset( $_POST['ec_cart_is_subscriber'] ) && '1' == $_POST['ec_cart_is_subscriber'] ) {
							$is_subscriber = true;
						} else {
							$is_subscriber = false;
						}

						$user_id = $this->mysqli->insert_user( $email, $password, $first_name, $last_name, $billing_id, $shipping_id, $user_level, $is_subscriber, "", $vat_registration_number );
						if ( $user_id != 0 ) {
							$this->mysqli->update_address_user_id( $billing_id, $user_id );
							$this->mysqli->update_address_user_id( $shipping_id, $user_id );

							// MyMail Hook
							if ( $is_subscriber ) {
								$this->mysqli->insert_subscriber( $email, $first_name, $last_name );
								if ( function_exists( 'mailster' ) ) {
									$subscriber_id = mailster('subscribers')->add(array(
										'firstname' => $first_name,
										'lastname' => $last_name,
										'email' => $email,
										'status' => 1,
									), false );
								}
								do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
							}

							// Maybe insert WP user
							if ( apply_filters( 'wp_easycart_sync_wordpress_users', false ) ) {
								$user_name = 'wpeasycart_' . $user_id;
								$wp_user_id = wp_create_user( $user_name, $_POST['ec_contact_password'], $email ); // XSS OK. Should not sanitize password.
								add_user_meta( $wp_user_id, 'wpeasycart_user_id', $user_id, true );
							}

							do_action( 'wpeasycart_account_added', $user_id, $email, $_POST['ec_contact_password'] ); // XSS OK. Should not sanitize password.

							// Send registration email if needed
							if ( get_option( 'ec_option_send_signup_email' ) ) {

								$headers   = array();
								$headers[] = "MIME-Version: 1.0";
								$headers[] = "Content-Type: text/html; charset=utf-8";
								$headers[] = "From: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
								$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_order_from_email' ) );
								$headers[] = "X-Mailer: PHP/" . phpversion();

								$message = wp_easycart_language()->get_text( "account_register", "account_register_email_message" ) . " " . $email;

								if ( get_option( 'ec_option_use_wp_mail' ) ) {
									wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), wp_easycart_language()->get_text( "account_register", "account_register_email_title" ), $message, implode("\r\n", $headers) );
								} else {
									$admin_email = stripslashes( get_option( 'ec_option_bcc_email_addresses' ) );
									$subject = wp_easycart_language()->get_text( "account_register", "account_register_email_title" );
									$mailer = new wpeasycart_mailer();
									$mailer->send_order_email( $admin_email, $subject, $message );
								}

							}

							$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
							$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;
							$GLOBALS['ec_cart_data']->cart_data->email = $email;
							$GLOBALS['ec_cart_data']->cart_data->username = $first_name . " " . $last_name;
							$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
							$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

							if ( $this->shipping->validate_address( $shipping_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country ) ) {
								$GLOBALS['ec_cart_data']->cart_data->is_guest = "";
								$GLOBALS['ec_cart_data']->cart_data->guest_key = "";

								$GLOBALS['ec_cart_data']->save_session_to_db();
								do_action( 'wpeasycart_cart_updated' );	
								if ( !$this->validate_tax_cloud() ) {
									header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_address" );

								} else if ( !$this->validate_vat_registration_number( $vat_registration_number ) ) {
									header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_vat_number" );

								} else {
									header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=" . $next_page . "&ec_cart_success=account_created");

								}

							} else {
								$GLOBALS['ec_cart_data']->save_session_to_db();
								do_action( 'wpeasycart_cart_updated' );	
								header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_success=account_created&ec_cart_error=invalid_address");
							}

						} else {
							$GLOBALS['ec_cart_data']->save_session_to_db();
							header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=email_exists");
						}

					}

				} else {

					$this->mysqli->update_user( $GLOBALS['ec_user']->user_id, $vat_registration_number );

					if ( $this->shipping->validate_address( $shipping_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country ) ) {

						if ( $GLOBALS['ec_user']->billing_id ) {
							$this->mysqli->update_address( $GLOBALS['ec_user']->billing_id, $billing_first_name, $billing_last_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $billing_company_name );

						} else {
							$this->mysqli->insert_user_address( $billing_first_name, $billing_last_name, $billing_company_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $GLOBALS['ec_user']->user_id, "billing" );
						}

						if ( $GLOBALS['ec_user']->shipping_id ) {
								$this->mysqli->update_address( $GLOBALS['ec_user']->shipping_id, $shipping_first_name, $shipping_last_name, $shipping_address, $shipping_address2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone, $shipping_company_name );

						} else {
							$this->mysqli->insert_user_address( $shipping_first_name, $shipping_last_name, $shipping_company_name, $shipping_address, $shipping_address2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone, $GLOBALS['ec_user']->user_id, "shipping" );

						}

						$GLOBALS['ec_cart_data']->save_session_to_db();

						do_action( 'wpeasycart_cart_updated' );

						do_action( 'wpeasycart_account_updated', $GLOBALS['ec_user']->user_id, true );

						if ( !$this->validate_tax_cloud() ) {
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_address" );

						} else if ( !$this->validate_vat_registration_number( $vat_registration_number ) ) {
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_vat_number" );

						} else {
							header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=" . $next_page);

						}

					} else {
						$GLOBALS['ec_cart_data']->save_session_to_db();
						do_action( 'wpeasycart_cart_updated' );
						header("location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&ec_cart_error=invalid_address");
					}

				}

			}

		} // close recaptcha check

	}

	private function process_save_checkout_shipping() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-shipping-method-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&cart_error=invalid_nonce" );
			die();
		}

		if ( isset( $_POST['ec_cart_shipping_method'] ) ) {
			$shipping_method = sanitize_text_field( $_POST['ec_cart_shipping_method'] );
		} else {
			$shipping_method = "";
		}
		if ( isset( $_POST['ec_cart_ship_express'] ) ) {
			$ship_express = sanitize_text_field( $_POST['ec_cart_ship_express'] );
		} else {
			$ship_express = "";
		}
		$GLOBALS['ec_cart_data']->cart_data->shipping_method = $shipping_method;
		$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = $ship_express;

		$GLOBALS['ec_cart_data']->save_session_to_db();

		do_action( 'wpeasycart_cart_updated' );

		if( '' == $shipping_method ) {
			$url = $this->cart_page . $this->permalink_divider . "ec_page=checkout_shipping&ec_cart_error=shipping_method";
		} else {
			$url = $this->cart_page . $this->permalink_divider . "ec_page=checkout_payment";
		}

		if ( isset( $_POST['paypal_payment_id'] ) && isset( $_POST['paypal_payer_id'] ) && isset( $_POST['paypal_payment_method'] ) ) {
			$url .= '&PID=' . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payment_id'] ) ) . '&PYID=' . preg_replace( "/[^A-Za-z0-9\-]/", '', sanitize_text_field( $_POST['paypal_payer_id'] ) ) . '&PMETH=' . preg_replace( "/[^A-Za-z0-9\_]/", '', sanitize_text_field( $_POST['paypal_payment_method'] ) );
		}
		header( "location: " . $url );
	}

	private function process_purchase_subscription() {

		$model_number = 0;
		if ( isset( $_POST['model_number'] ) )
			$model_number = sanitize_text_field( $_POST['model_number'] );

		header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number );

	}

	private function process_insert_subscription() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-insert-subscription-' . $GLOBALS['ec_cart_data']->ec_cart_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_info&cart_error=invalid_nonce" );
			die();
		}

		if ( isset( $_POST['ec_login_selector'] ) ) {
			$this->process_login_user();

		} else {
			$this->process_insert_subscription_helper();
		}

	}

	private function process_insert_subscription_helper() {
		global $wpdb;
		$model_number = sanitize_text_field( $_POST['ec_cart_model_number'] );
		$products = $this->mysqli->get_product_list( $wpdb->prepare( " WHERE product.model_number = %s", $model_number ), "", "", "" );

		$user_error = false;
		if ( isset( $_POST['ec_contact_email'] ) ) {
			$user_error = $this->mysqli->does_user_exist( sanitize_email( $_POST['ec_contact_email'] ) );
		}

		// If checkout out as new user and the email already exists, this is an error.
		if ( !$user_error ) {

			if ( count( $products > 0 ) ) {

				// Try to get a subscription for this product and email address!
				if ( isset( $_POST['ec_contact_email'] ) )	$email_test = sanitize_email( $_POST['ec_contact_email'] );
				else										$email_test = sanitize_email( $GLOBALS['ec_cart_data']->cart_data->email );

				$subscription_list = $this->mysqli->find_subscription_match( $email_test, $products[0]['product_id'] );

				// Coupon Information
				$coupon = NULL;
				$discount_total = 0;
				$is_match = false;
				if ( isset( $_POST['ec_cart_coupon_code'] ) && $_POST['ec_cart_coupon_code'] != "" ) {
					$coupon_row = $GLOBALS['ec_coupons']->redeem_coupon_code( sanitize_text_field( $_POST['ec_cart_coupon_code'] ) );
					$is_match = false;
					if ( $coupon_row->by_product_id ) {
						if ( $products[0]['product_id'] == $coupon_row->product_id ) {
							$is_match = true;
						}
					} else if ( $coupon_row->by_manufacturer_id ) {
						if ( $products[0]['manufacturer_id'] == $coupon_row->manufacturer_id ) {
							$is_match = true;
						}
					} else {
						$is_match = true;
					}

					if ( $is_match ) {
						$coupon = $coupon_row->promocode_id;
					}
				} else if ( isset( $_POST['ec_coupon_code'] ) && $_POST['ec_coupon_code'] != "" ) {
					$coupon_row = $GLOBALS['ec_coupons']->redeem_coupon_code( sanitize_text_field( $_POST['ec_coupon_code'] ) );
					$is_match = false;
					if ( $coupon_row->by_product_id ) {
						if ( $products[0]['product_id'] == $coupon_row->product_id ) {
							$is_match = true;
						}
					} else if ( $coupon_row->by_manufacturer_id ) {
						if ( $products[0]['manufacturer_id'] == $coupon_row->manufacturer_id ) {
							$is_match = true;
						}
					} else {
						$is_match = true;
					}

					if ( $is_match ) {
						$coupon = $coupon_row->promocode_id;
					}

				}
				// END COUPON FIND SECTION

				// IF MATCH FOUND, APPLY TO PRODUCT
				if ( $is_match ) {

					if ( $coupon_row->is_dollar_based ) {
						$discount_total = floatval( $coupon_row->promo_dollar );

					} else if ( $coupon_row->is_percentage_based ) {
						$discount_total = ( floatval( $products[0]['price'] ) * ( floatval( $coupon_row->promo_percentage ) / 100 ) );

					}

				}
				// END MATCHING COUPON SECTION

				// Billing Information
				$billing_country = $shipping_country = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_country'] ) );

				$billing_first_name = $shipping_first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_first_name'] ) );
				$billing_last_name = $shipping_last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_last_name'] ) );

				if ( isset( $_POST['ec_cart_billing_company_name'] ) ) {
					$billing_company_name = $shipping_company_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_company_name'] ) );
				} else {
					$billing_company_name = $shipping_company_name = "";
				}

				$billing_address = $shipping_address = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_address'] ) );
				if ( isset( $_POST['ec_cart_billing_address2'] ) ) {
					$billing_address2 = $shipping_address2 = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_address2'] ) );
				} else {
					$billing_address2 = $shipping_address2 = "";
				}

				$billing_city = $shipping_city = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_city'] ) );
				if ( isset( $_POST['ec_cart_billing_state_' . $billing_country] ) ) {
					$billing_state = $shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_state_' . $billing_country] ) );
				} else {
					$billing_state = $shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_state'] ) );
				}

				$billing_zip = $shipping_zip = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_zip'] ) );
				if ( isset( $_POST['ec_cart_billing_phone'] ) ) {
					$billing_phone = $shipping_phone = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_phone'] ) );
				} else {
					$billing_phone = "";
				}
				// END BILLING INFO

				// Shipping Information
				if ( isset( $_POST['ec_shipping_selector'] ) )
					$shipping_selector = sanitize_text_field( $_POST['ec_shipping_selector'] );
				else
					$shipping_selector = "false";

				if ( $shipping_selector == "true" ) {
					$shipping_country = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_country'] ) );

					$shipping_first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_first_name'] ) );
					$shipping_last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_last_name'] ) );

					if ( isset( $_POST['ec_cart_shipping_company_name'] ) ) {
						$shipping_company_name = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_company_name'] ) );
					} else {
						$shipping_company_name = "";
					}

					$shipping_address = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_address'] ) );
					if ( isset( $_POST['ec_cart_shipping_address2'] ) ) {
						$shipping_address2 = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_address2'] ) );
					} else {
						$shipping_address2 = "";
					}

					$shipping_city = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_city'] ) );

					if ( isset( $_POST['ec_cart_shipping_state_' . $shipping_country] ) ) {
						$shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_state_' . $shipping_country] ) );
					} else {
						$shipping_state = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_state'] ) );
					}

					$shipping_zip = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_zip'] ) );
					if ( isset( $_POST['ec_cart_shipping_phone'] ) ) {
						$shipping_phone = stripslashes( sanitize_text_field( $_POST['ec_cart_shipping_phone'] ) );
					} else {
						$shipping_phone = "";
					}
				}
				// END SHIPPING INFO

				// Order Notes
				if ( isset( $_POST['ec_order_notes'] ) ) {
					$order_notes = stripslashes( sanitize_textarea_field( $_POST['ec_order_notes'] ) );
				} else {
					$order_notes = "";
				}

				// Create Account Information
				if ( isset( $_POST['ec_contact_first_name'] ) ) {
					$first_name = stripslashes( sanitize_text_field( $_POST['ec_contact_first_name'] ) );
				} else if ( isset( $_POST['ec_cart_billing_first_name'] ) ) {
					$first_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_first_name'] ) );
				} else {
					$first_name = "";
				}
				if ( isset( $_POST['ec_contact_last_name'] ) ) {
					$last_name = stripslashes( sanitize_text_field( $_POST['ec_contact_last_name'] ) );
				} else if ( isset( $_POST['ec_cart_billing_last_name'] ) ) {
					$last_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_last_name'] ) );
				} else {
					$last_name = "";
				}

				if ( isset( $_POST['ec_contact_create_account'] ) )
					$create_account = sanitize_text_field( $_POST['ec_contact_create_account'] );
				else if ( isset( $_POST['ec_create_account_selector'] ) )
					$create_account = true;
				else
					$create_account = false;


				// CREATE ACCOUNT IF NEEDED
				if ( isset( $_POST['ec_contact_email'] ) ) {
					$email = sanitize_email( $_POST['ec_contact_email'] );
					$GLOBALS['ec_cart_data']->cart_data->email = $email;
				}

				if ( isset( $_POST['ec_contact_email'] ) && !$create_account ) {
					$GLOBALS['ec_cart_data']->cart_data->is_guest = true;
					$GLOBALS['ec_cart_data']->cart_data->guest_key = $GLOBALS['ec_cart_data']->ec_cart_id;
				} else {
					$GLOBALS['ec_cart_data']->cart_data->is_guest = false;
				}

				if ( $create_account ) {
					$email = sanitize_email( $_POST['ec_contact_email'] );
					$password = md5( $_POST['ec_contact_password'] ); // XSS OK, Password not sanitized
					$password = apply_filters( 'wpeasycart_password_hash', $password, $_POST['ec_contact_password'] ); // XSS OK, Password not sanitized

					// INSERT USER
					$billing_id = $this->mysqli->insert_address( $billing_first_name, $billing_last_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $billing_company_name );

					$shipping_id = $this->mysqli->insert_address( $shipping_first_name, $shipping_last_name, $shipping_address, $shipping_address2, $shipping_city, $shipping_state, $shipping_zip, $shipping_country, $shipping_phone, $shipping_company_name );

					$user_level = "shopper";
					if ( isset( $_POST['ec_contact_is_subscriber'] ) ) {
						$is_subscriber = true;
					} else {
						$is_subscriber = false;
					}

					$user_id = $this->mysqli->insert_user( $email, $password, $first_name, $last_name, $billing_id, $shipping_id, $user_level, $is_subscriber );
					$this->mysqli->update_address_user_id( $billing_id, $user_id );
					$this->mysqli->update_address_user_id( $shipping_id, $user_id );

					do_action( 'wpeasycart_account_added', $user_id, $email, $_POST['ec_contact_password'] ); // XSS OK, Password not sanitized

					if ( $is_subscriber ) {
						$this->mysqli->insert_subscriber( $email, $first_name, $last_name );
						// MyMail Hook
						if ( function_exists( 'mailster' ) ) {
							$subscriber_id = mailster('subscribers')->add(array(
								'firstname' => $first_name,
								'lastname' => $last_name,
								'email' => $email,
								'status' => 1,
							), false );
						}
						do_action( 'wpeasycart_insert_subscriber', $email, $first_name, $last_name );
					}

					if ( $user_id != 0 ) {

						$GLOBALS['ec_cart_data']->cart_data->user_id = $user_id;
						$GLOBALS['ec_cart_data']->cart_data->email = $email;
						$GLOBALS['ec_cart_data']->cart_data->username = $first_name . " " . $last_name;
						$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
						$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

						$GLOBALS['ec_user'] = new ec_user( "" );

					}
				} else { // Customer already exists, lets update their billing address
					$user = new ec_user( "" );
					$this->mysqli->update_address( $user->billing_id, $billing_first_name, $billing_last_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_zip, $billing_country, $billing_phone, $billing_company_name );
				}
				// END CREATE ACCOUNT

				// Set Sessions
				$GLOBALS['ec_cart_data']->cart_data->billing_first_name = $billing_first_name;
				$GLOBALS['ec_cart_data']->cart_data->billing_last_name = $billing_last_name;
				$GLOBALS['ec_cart_data']->cart_data->billing_company_name = $billing_company_name;
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = $billing_address;
				$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = $billing_address2;
				$GLOBALS['ec_cart_data']->cart_data->billing_city = $billing_city;
				$GLOBALS['ec_cart_data']->cart_data->billing_state = $billing_state;
				$GLOBALS['ec_cart_data']->cart_data->billing_zip = $billing_zip;
				$GLOBALS['ec_cart_data']->cart_data->billing_country = $billing_country;
				$GLOBALS['ec_cart_data']->cart_data->billing_phone = $billing_phone;

				$GLOBALS['ec_cart_data']->cart_data->shipping_selector = $shipping_selector;

				$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = $shipping_first_name;
				$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = $shipping_last_name;
				$GLOBALS['ec_cart_data']->cart_data->shipping_company_name = $shipping_company_name;
				$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = $shipping_address;
				$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = $shipping_address2;
				$GLOBALS['ec_cart_data']->cart_data->shipping_city = $shipping_city;
				$GLOBALS['ec_cart_data']->cart_data->shipping_state = $shipping_state;
				$GLOBALS['ec_cart_data']->cart_data->shipping_zip = $shipping_zip;
				$GLOBALS['ec_cart_data']->cart_data->shipping_country = $shipping_country;
				$GLOBALS['ec_cart_data']->cart_data->shipping_phone = $shipping_phone;

				$GLOBALS['ec_cart_data']->cart_data->first_name = $first_name;
				$GLOBALS['ec_cart_data']->cart_data->last_name = $last_name;

				$GLOBALS['ec_cart_data']->cart_data->order_notes = $order_notes;

				$GLOBALS['ec_cart_data']->save_session_to_db();

				$GLOBALS['ec_user']->setup_billing_info_data( $billing_first_name, $billing_last_name, $billing_address, $billing_address2, $billing_city, $billing_state, $billing_country, $billing_zip, $billing_phone, $billing_company_name );
				$GLOBALS['ec_user']->setup_shipping_info_data( $shipping_first_name, $shipping_last_name, $shipping_address, $shipping_address2, $shipping_city, $shipping_state, $shipping_country, $shipping_zip, $shipping_phone, $shipping_company_name );
				$product = new ec_product( $products[0] );
				$quantity = 1;
				if ( isset( $_POST['ec_quantity'] ) )
					$quantity = (int) $_POST['ec_quantity'];

				if ( count( $subscription_list ) <= 0 ) {
					// Setup for processing
					// Setup for processing
					if ( class_exists( "ec_stripe" ) || class_exists( "ec_stripe_connect" ) ) {

						if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' )
							$stripe = new ec_stripe();
						else
							$stripe = new ec_stripe_connect();

						// Coupon Check
						if ( $coupon && !$coupon_row->is_free_item_based ) {
							$coupon_exists = $stripe->get_coupon( $coupon );
							if ( $coupon_exists === false ) {
								$is_amount_off = false;
								if ( $coupon_row->promo_dollar > 0 )
									$is_amount_off = true;
								$redeem_by = NULL;
								if ( $coupon_row->expiration_date != '' ) {
									$redeem_by = strtotime( $coupon_row->expiration_date ) + 7*60*60;
								}
								$stripe_coupon = array(
									"promocode_id"		=> $coupon_row->promocode_id,
									"duration"			=> $coupon_row->duration,
									"duration_in_months"=> $coupon_row->duration_in_months,
									"is_amount_off"		=> $is_amount_off,
									"amount_off"		=> $coupon_row->promo_dollar * 100,
									"percent_off"		=> $coupon_row->promo_percentage,
									"redeem_by"			=> $redeem_by,
									"max_redemptions"	=> $coupon_row->max_redemptions
								);
								$stripe->insert_coupon( $stripe_coupon );
							}

						} else if ( $coupon_row->is_free_item_based ) {
							$coupon = "";
						}

						// Possibly discount the initial fee
						$initial_fee = $product->subscription_signup_fee;
						if ( $discount_total > $product->price ) {
							$remaining_discount = $discount_total - $product->price;
							$initial_fee = $initial_fee - $remaining_discount;
						}

						// Payment Information
						$payment_method = $this->get_payment_type( $this->sanatize_card_number( sanitize_text_field( $_POST['ec_card_number'] ) ) );
						$card_holder_name = stripslashes( sanitize_text_field( $_POST['ec_cart_billing_first_name'] ) ) . " " . stripslashes( sanitize_text_field( $_POST['ec_cart_billing_last_name'] ) );
						$card_number = sanitize_text_field( $_POST['ec_card_number'] );
						if ( isset( $_POST['ec_expiration_month'] ) && isset( $_POST['ec_expiration_year'] ) ) {
							$exp_month = sanitize_text_field( $_POST['ec_expiration_month'] );
							$exp_year = sanitize_text_field( $_POST['ec_expiration_year'] );
						} else {
							$exp_date = sanitize_text_field( $_POST['ec_cc_expiration'] );
							$exp_month = substr( $exp_date, 0, 2 );
							$exp_year = substr( $exp_date, 5 );
							if ( strlen( $exp_year ) == 2 ) {
								$exp_year = "20" . $exp_year;
							}
						}
						$security_code = sanitize_text_field( $_POST['ec_security_code'] );

						$card = new ec_credit_card( $payment_method, $card_holder_name, $card_number, $exp_month, $exp_year, $security_code );
						$customer_id = $GLOBALS['ec_user']->stripe_customer_id;

						// Tests vars
						$need_to_update_customer_id = false;
						$customer_insert_test = false;

						if ( $customer_id == "" ) {
							$customer_id = $stripe->insert_customer( $GLOBALS['ec_user'], NULL, $initial_fee );
							$need_to_update_customer_id = true;
						} else {
							$found_customer = $stripe->update_customer( $GLOBALS['ec_user'], $initial_fee );
							if ( !$found_customer ) { // Likely switched from test to live or to a new account, so customer id was wrong
								$customer_id = $stripe->insert_customer( $GLOBALS['ec_user'], NULL, $initial_fee );
								$need_to_update_customer_id = true;
							}
						}

						if ( $need_to_update_customer_id && $customer_id ) { // Customer inserted to stripe successfully
							$this->mysqli->update_user_stripe_id( $GLOBALS['ec_user']->user_id, $customer_id );
							$GLOBALS['ec_user']->stripe_customer_id = $customer_id;
							$customer_insert_test = true;
						} else if ( $need_to_update_customer_id && !$customer_id ) {
							$customer_insert_test = false;
						} else {
							$customer_insert_test = true;
						}

						if ( $customer_insert_test ) { // Customer inserted successfully (OR didn't need to be inserted)

							if ( isset( $_POST['stripeToken'] ) ) {
								$card_result = true;
							} else {
								$card_result = $stripe->insert_card( $GLOBALS['ec_user'], $card );
							}	

							if ( $card_result ) { //Card Submitted Successfully

								$plan_added = $product->stripe_plan_added;

								if ( !$product->stripe_plan_added ) { // Add plan if needed
									$plan_added = $stripe->insert_plan( $product );
									$this->mysqli->update_product_stripe_added( $product->product_id );
								}

								if ( $plan_added ) { // Plan added successfully

									if ( $product->is_shippable ) {
										$ship_price_total = $product->price * $quantity;
										$ship_weight_total = $product->weight * $quantity;
										$ship_quantity = $quantity;
									} else {
										$ship_price_total = 0;
										$ship_weight_total = 0;
										$ship_quantity = 0;
									}

									do_action( 'wpeasycart_cart_subscription_updated', $product, $quantity );

									$this->shipping = new ec_shipping( $ship_price_total, $ship_weight_total, $ship_quantity, 'RADIO', $GLOBALS['ec_user']->freeshipping, $product->length, $product->width, $product->height * $quantity, array( $product ) );
									if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && $product->is_shippable ) {
										$this->cart->shippable_total_items = $quantity;
									}

									$this->cart->subtotal = ( $product->price + $product->subscription_signup_fee ) * $quantity;

									$this->order_totals = new ec_order_totals( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount );

									if ( $product->is_taxable || $product->vat_rate ) {
										$taxable_subtotal = 0;
										$vatable_subtotal = 0;
										if ( $product->is_taxable ) {
											$taxable_subtotal = $product->price * $quantity - $discount_total;
										}
										if ( $product->vat_rate ) {
											$vatable_subtotal = $product->price * $quantity - $discount_total;
										}

										do_action( 'wpeasycart_cart_subscription_pre_tax', $product, $quantity, $shipping_total, $handling_total, $discount_total );

										if ( get_option( 'ec_option_tax_cloud_api_id' ) != "" && get_option( 'ec_option_tax_cloud_api_key' ) != "" ) {
											wpeasycart_taxcloud()->setup_subscription_for_tax( $product, $quantity, $discount_total );
										}
										if ( function_exists( 'wpeasycart_taxjar' ) && wpeasycart_taxjar()->is_enabled() ) {
											wpeasycart_taxjar()->setup_subscription_for_tax( $product, $quantity, $discount_total );
										}
										$this->tax = new ec_tax( $product->price * $quantity, $taxable_subtotal, $vatable_subtotal, $GLOBALS['ec_user']->shipping->state, $GLOBALS['ec_user']->shipping->country, $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $this->product->handling_price_each * $quantity ) + $this->product->handling_price ), $this->cart );
									} else {
										$this->tax = new ec_tax( 0, 0, 0, $GLOBALS['ec_user']->shipping->state, $GLOBALS['ec_user']->shipping->country, $GLOBALS['ec_user']->taxfree, $this->shipping->get_shipping_price( ( $this->product->handling_price_each * $quantity ) + $this->product->handling_price ), $this->cart );
									}

									$this->order_totals = new ec_order_totals( $this->cart, $GLOBALS['ec_user'], $this->shipping, $this->tax, $this->discount );

									if ( get_option( 'ec_option_collect_shipping_for_subscriptions' ) && $this->order_totals->shipping_total > 0 ) {
										$stripe->update_customer( $GLOBALS['ec_user'], $this->order_totals->shipping_total );
									}

									$prorate = "false";
									if ( $product->subscription_prorate )
										$prorate = "true";
									$trial_end_date = NULL;
									if ( $product->trial_period_days > 0 )
										$trial_end_date = strtotime( "+" . $product->trial_period_days . " days" );
									$stripe_response = $stripe->insert_subscription( $product, $GLOBALS['ec_user'], $card, $coupon, $prorate, $trial_end_date, $quantity, number_format( $this->tax->get_tax_rate(), 2, '.', '' ) );

									if ( $stripe_response ) { // Subscription added successfully

										$subscription_id = $this->mysqli->insert_stripe_subscription( $stripe_response, $product, $GLOBALS['ec_user'], $card, $quantity );
										$subscription_row = $this->mysqli->get_subscription_row( $subscription_id );
										$coupon_promocode_id = "";
										if ( isset( $coupon_row ) )
											$coupon_promocode_id = $coupon_row->promocode_id;

										$this->mysqli->update_user_default_card( $GLOBALS['ec_user'], $card );
										$subscription = new ec_subscription( $subscription_row );

										if ( $product->trial_period_days > 0 ) {

											$subscription->send_trial_start_email( $GLOBALS['ec_user'] );

										} else {
											// Get Shipping Method to Save
											$shipping_method = "";
											if ( !get_option( 'ec_option_use_shipping' ) || $this->order_totals->shipping_total <= 0 ) {
												$shipping_method = "";
											} else if ( $this->shipping->shipping_method == "fraktjakt" ) {
												$shipping_method = $this->shipping->get_selected_shipping_method();

											} else if ( $GLOBALS['ec_cart_data']->cart_data->shipping_method != "" && $GLOBALS['ec_cart_data']->cart_data->shipping_method != "standard" )
												$shipping_method = $this->mysqli->get_shipping_method_name( $GLOBALS['ec_cart_data']->cart_data->shipping_method );

											else if ( ( $this->shipping->shipping_method == "price" || $this->shipping->shipping_method == "weight" ) && $GLOBALS['ec_cart_data']->cart_data->expedited_shipping != "" )
												$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_express" );

											else
												$shipping_method = wp_easycart_language()->get_text( "cart_estimate_shipping", "cart_estimate_shipping_standard" );

											$order_id = $this->mysqli->insert_subscription_order( $product, $GLOBALS['ec_user'], $card, $subscription_id, $coupon_promocode_id, $order_notes, $this->subscription_option1_name, $this->subscription_option2_name, $this->subscription_option3_name, $this->subscription_option4_name, $this->subscription_option5_name, $this->subscription_option1_label, $this->subscription_option2_label, $this->subscription_option3_label, $this->subscription_option4_label, $this->subscription_option5_label, $quantity, $this->order_totals, $shipping_method, $this->tax, $discount_total );	
											do_action( 'wpeasycart_subscription_first_order_inserted', $order_id );
											do_action( 'wpeasycart_order_paid', $order_id );
											$order_row = $this->mysqli->get_order_row( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
											$order = new ec_orderdisplay( $order_row );
											$order_details = $this->mysqli->get_order_details( $order_id, $GLOBALS['ec_cart_data']->cart_data->user_id );
											$subscription->send_email_receipt( $GLOBALS['ec_user'], $order, $order_details );
											$this->mysqli->update_product_stock( $product->product_id, $quantity );

											if ( $subscription->payment_duration > 0 && $subscription->payment_duration == 1 ) {
												$stripe->cancel_subscription( $GLOBALS['ec_user'], $subscription->stripe_subscription_id );
												$this->mysqli->cancel_stripe_subscription( $subscription->stripe_subscription_id );
											}

										}

										// Unset Variables Entered
										$GLOBALS['ec_cart_data']->checkout_session_complete();

										if ( $product->trial_period_days > 0 ) {
											header( "location: " . $this->account_page . $this->permalink_divider . "ec_page=subscription_details&subscription_id=" . $subscription_id );

										} else {
											header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=checkout_success&order_id=" . $order_id );
										}

									} else {
										header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=subscription_failed" );	

									}// Close check for subscription insertion

								} else {
									header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=subscription_added_failed" );

								}// Close check for stripe plan insertion

							} else {
								header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=card_error" );

							}// Close check for card insertion

						} else {
							header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=user_insert_error" );

						}// Close check for customer insertion to stripe check

					} else if ( class_exists( 'ec_paypal' ) ) { // Close check for PayPal

						$coupon_promocode_id = "";
						if ( isset( $coupon_row ) )
							$coupon_promocode_id = $coupon_row->promocode_id;

						$order_id = $this->mysqli->insert_paypal_subscription_order( $product, $GLOBALS['ec_user'], $coupon_promocode_id, $order_notes, $this->subscription_option1_name, $this->subscription_option2_name, $this->subscription_option3_name, $this->subscription_option4_name, $this->subscription_option5_name, $this->subscription_option1_label, $this->subscription_option2_label, $this->subscription_option3_label, $this->subscription_option4_label, $this->subscription_option5_label, $quantity );
						$paypal = new ec_paypal();
						$paypal->display_subscription_form( $order_id, $GLOBALS['ec_user'], $product );

						// Unset Variables Entered
						$GLOBALS['ec_cart_data']->cart_data->subscription_option1 = "";
						$GLOBALS['ec_cart_data']->cart_data->subscription_option2 = "";
						$GLOBALS['ec_cart_data']->cart_data->subscription_option3 = "";
						$GLOBALS['ec_cart_data']->cart_data->subscription_option4 = "";
						$GLOBALS['ec_cart_data']->cart_data->subscription_option5 = "";

						$GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option = "";

						$GLOBALS['ec_cart_data']->cart_data->billing_first_name = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_last_name = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_address_line_1 = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_address_line_2 = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_city = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_state = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_zip = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_country = "";
						$GLOBALS['ec_cart_data']->cart_data->billing_phone = "";

						$GLOBALS['ec_cart_data']->cart_data->shipping_selector = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_first_name = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_last_name = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_1 = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_address_line_2 = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_city = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_state = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_zip = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_country = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_phone = "";

						$GLOBALS['ec_cart_data']->cart_data->use_shipping = "";
						$GLOBALS['ec_cart_data']->cart_data->shipping_method = "";
						$GLOBALS['ec_cart_data']->cart_data->expedited_shipping = ""; 

						if ( $GLOBALS['ec_cart_data']->cart_data->user_id == "" ) {
							$GLOBALS['ec_cart_data']->cart_data->email = "";
							$GLOBALS['ec_cart_data']->cart_data->first_name = "";
							$GLOBALS['ec_cart_data']->cart_data->last_name = "";
						}

						$GLOBALS['ec_cart_data']->cart_data->create_account = "";
						$GLOBALS['ec_cart_data']->cart_data->coupon_code = "";
						$GLOBALS['ec_cart_data']->cart_data->giftcard = "";
						$GLOBALS['ec_cart_data']->cart_data->order_notes = "";
						setcookie('ec_cart_id', "", time() - 300, "/" ); 

						$GLOBALS['ec_cart_data']->clear_db_session();

						global $wpdb;

						$vals = array( 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z' );
						$session_cart_id = $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)];

						$check_tempcart_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart.* FROM ec_tempcart WHERE ec_tempcart.session_id = %s", $session_cart_id ) );
						$check_tempcart_data_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart_data.* FROM ec_tempcart_data WHERE ec_tempcart_data.session_id = %s", $session_cart_id ) );
						while( $check_tempcart_id || $check_tempcart_data_id ) { // If we get a result, create new and go until we get a unique tempcart id...
							$session_cart_id = $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)] . $vals[rand(0, 25)];
							$check_tempcart_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart.* FROM ec_tempcart WHERE ec_tempcart.session_id = %s", $session_cart_id ) );
							$check_tempcart_data_id = $wpdb->get_row( $wpdb->prepare( "SELECT ec_tempcart_data.* FROM ec_tempcart_data WHERE ec_tempcart_data.session_id = %s", $session_cart_id ) );
						}
						$GLOBALS['ec_cart_id'] = $session_cart_id;
						setcookie( 'ec_cart_id', $session_cart_id, time() + ( 3600 * 24 * 1 ), "/" );

						$GLOBALS['ec_cart_data'] = new ec_cart_data( $GLOBALS['ec_cart_data']->ec_cart_id );

						die();

					} else { // Close check for paypal
						$GLOBALS['ec_cart_data']->save_session_to_db();
						header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=subscription_setup_error" );
					}

				} else {
					$GLOBALS['ec_cart_data']->save_session_to_db();
					header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=already_subscribed" );

				}// Close check for already subscribed error

			} else {

				$GLOBALS['ec_cart_data']->save_session_to_db();
				header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=subscription_not_found" );

			}// Close check for subscription existing

		} else {
			$GLOBALS['ec_cart_data']->save_session_to_db();
			header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $model_number . "&ec_cart_error=email_exists" );

		}// Close user exists error for guest checkout

	}

	private function process_send_inquiry() {
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-send-inquiry' ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$recaptcha_valid = true;
		if ( get_option( 'ec_option_enable_recaptcha' ) ) {
			$db = new ec_db_admin();
			$recaptcha_response = sanitize_text_field( $_POST['ec_grecaptcha_response_inquiry'] );

			$data = array(
				"secret"	=> get_option( 'ec_option_recaptcha_secret_key' ),
				"response"	=> $recaptcha_response
			);

			$request = new WP_Http;
			$response = $request->request( 
				"https://www.google.com/recaptcha/api/siteverify", 
				array( 
					'method' => 'POST', 
					'body' => http_build_query( $data ),
					'timeout' => 30
				)
			);
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				$db->insert_response( 0, 1, "GOOGLE RECAPTCHA CURL ERROR", $error_message );
				$response = (object) array( "error" => $error_message );
			} else {
				$response = json_decode( $response['body'] );
				$db->insert_response( 0, 0, "Google Recaptcha Response", print_r( $response, true ) );
			}

			$recaptcha_valid = ( isset( $response->success ) && $response->success ) ? true : false;
		}

		if ( $recaptcha_valid ) {
			$inquiry_email = stripslashes( sanitize_email( $_POST['ec_inquiry_email'] ) );
			$inquiry_name = stripslashes( sanitize_text_field( $_POST['ec_inquiry_name'] ) );
			$inquiry_message = stripslashes( sanitize_textarea_field( $_POST['ec_inquiry_message'] ) );
			$model_number = sanitize_text_field( $_POST['ec_inquiry_model_number'] );
			if ( isset( $_POST['ec_inquiry_send_copy'] ) ) {
				$send_copy = true;
			} else {
				$send_copy = false;
			}
			$product = $this->mysqli->get_product( $model_number );
			$file_temp_num = rand( 1000000, 999999999 );
			if ( $product->use_advanced_optionset ) {
				$option_vals = $this->get_advanced_option_vals( $product->product_id, $file_temp_num );
			} else {
				$option1 = $option2 = $option3 = $option4 = $option5 = "";
				if ( isset( $_POST['ec_option1'] ) ) {
					$option1 = $GLOBALS['ec_options']->get_optionitem( (int) $_POST['ec_option1'] );
				}
				if ( isset( $_POST['ec_option2'] ) ) {
					$option2 = $GLOBALS['ec_options']->get_optionitem( (int) $_POST['ec_option2'] );
				}
				if ( isset( $_POST['ec_option3'] ) ) {
					$option3 = $GLOBALS['ec_options']->get_optionitem( (int) $_POST['ec_option3'] );
				}
				if ( isset( $_POST['ec_option4'] ) ) {
					$option4 = $GLOBALS['ec_options']->get_optionitem( (int) $_POST['ec_option4'] );
				}
				if ( isset( $_POST['ec_option5'] ) ) {
					$option5 = $GLOBALS['ec_options']->get_optionitem( (int) $_POST['ec_option5'] );
				}
			}

			if ( $product && '' != $inquiry_email && '' != $inquiry_name && '' != $inquiry_message ) {
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

				$filter_options = (object) array(
					'product' => $product,
					'inquiry_name' => $inquiry_name,
					'inquiry_email' => $inquiry_email,
					'inquiry_message' => $inquiry_message,
					'send_copy' => $send_copy,
					'option1' => $option1,
					'option2' => $option2,
					'option3' => $option3,
					'option4' => $option4,
					'option5' => $option5,
					'file_temp_num' => $file_temp_num,
					'option_vals' => $option_vals,
					'email_logo_url' => $email_logo_url,
					'store_page' => $store_page,
					'permalink_divider' => $permalink_divider,
				);

				$headers   = array();
				$headers[] = "MIME-Version: 1.0";
				$headers[] = "Content-Type: text/html; charset=utf-8";
				$headers[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
				$headers[] = "Reply-To: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
				$headers[] = "X-Mailer: PHP/".phpversion();

				$headers2   = array();
				$headers2[] = "MIME-Version: 1.0";
				$headers2[] = "Content-Type: text/html; charset=utf-8";
				$headers2[] = "From: " . stripslashes( get_option( 'ec_option_password_from_email' ) );
				$headers2[] = "Reply-To: " . $inquiry_email;
				$headers2[] = "X-Mailer: PHP/" . phpversion();

				$has_product_options = false;

				ob_start();
				if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_inquiry_email.php' ) ) {
					include EC_PLUGIN_DATA_DIRECTORY . '/design/layout/' . get_option( 'ec_option_base_layout' ) . '/ec_inquiry_email.php';	
				} else {
					include EC_PLUGIN_DIRECTORY . '/design/layout/' . get_option( 'ec_option_latest_layout' ) . '/ec_inquiry_email.php';
				}
				$message = $admin_message = ob_get_clean();
				$message = apply_filters( 'wpeasycart_inquiry_email_content', $message, $filter_options );
				$admin_message = apply_filters( 'wpeasycart_inquiry_email_admin_content', $admin_message, $filter_options );
				$subject = $admin_subject = wp_easycart_language()->get_text( "product_details", "product_details_inquiry_title" );
				$subject = apply_filters( 'wpeasycart_inquiry_email_subject', $subject );
				$admin_subject = apply_filters( 'wpeasycart_inquiry_email_admin_subject', $admin_subject );

				$email_send_method = get_option( 'ec_option_use_wp_mail' );
				$email_send_method = apply_filters( 'wpeasycart_email_method', $email_send_method );

				if ( $email_send_method == "1" ) {
					if ( $send_copy ) {
						wp_mail( $inquiry_email, $subject, $message, implode("\r\n", $headers ) );
					}
					wp_mail( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $admin_subject, $admin_message, implode( "\r\n", $headers2 ) );
				} else if ( $email_send_method == "0" ) {
					$mailer = new wpeasycart_mailer();
					if ( $send_copy ) {
						$mailer->send_order_email( $inquiry_email, $subject, $message );
					}
					$mailer->send_order_email( stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $admin_subject, $admin_message );
				} else {
					if ( $send_copy ) {
						do_action( 'wpeasycart_custom_inquiry_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $inquiry_email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $subject, $message );
					}
					do_action( 'wpeasycart_custom_admin_inquiry_email', stripslashes( get_option( 'ec_option_order_from_email' ) ), $inquiry_email, stripslashes( get_option( 'ec_option_bcc_email_addresses' ) ), $admin_subject, $admin_message );
				}

				if ( get_option( 'ec_option_use_old_linking_style' ) ) {
					header( "location: " . $this->store_page . $this->permalink_divider . "model_number=" . $product->model_number . "&ec_store_success=inquiry_sent" );
				} else {
					header( "location: " . get_permalink( $product->post_id ) . $this->permalink_divider . "ec_store_success=inquiry_sent" );
				}
			}
		}
	}

	private function process_deconetwork_add_to_cart() {

		$this->mysqli->deconetwork_add_to_cart();
		header( "location: " . $this->cart_page );

	}

	public function process_subscribe_v3() {
		$product_id = (int) $_POST['product_id'];
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-subscribe-' . $product_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$cart_id = $GLOBALS['ec_cart_data']->ec_cart_id;
		$product = $this->mysqli->get_product( "", $product_id );
		$use_advanced_optionset = $product->use_advanced_optionset;
		$use_both_option_types = $product->use_both_option_types;
		$quantity = 1;
		if ( isset( $_POST['ec_quantity'] ) ) {
			$quantity = (int) $_POST['ec_quantity'];
		}

		$GLOBALS['ec_cart_data']->cart_data->subscription_quantity = $quantity;

		$GLOBALS['ec_cart_data']->cart_data->subscription_option1 = "";
		$GLOBALS['ec_cart_data']->cart_data->subscription_option2 = "";
		$GLOBALS['ec_cart_data']->cart_data->subscription_option3 = "";
		$GLOBALS['ec_cart_data']->cart_data->subscription_option4 = "";
		$GLOBALS['ec_cart_data']->cart_data->subscription_option5 = "";

		if ( ! $use_advanced_optionset || $use_both_option_types ) {
			if ( isset( $_POST['ec_option1'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option1 = (int) $_POST['ec_option1'];
			} else {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option1 = '';
			}

			if ( isset( $_POST['ec_option2'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option2 = (int) $_POST['ec_option2'];
			} else {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option2 = '';
			}

			if ( isset( $_POST['ec_option3'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option3 = (int) $_POST['ec_option3'];
			} else {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option3 = '';
			}

			if ( isset( $_POST['ec_option4'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option4 = (int) $_POST['ec_option4'];
			} else {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option4 = '';
			}

			if ( isset( $_POST['ec_option5'] ) ) {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option5 = (int) $_POST['ec_option5'];
			} else {
				$GLOBALS['ec_cart_data']->cart_data->subscription_option5 = '';
			}
		}

		if ( $use_advanced_optionset || $use_both_option_types ) {
			$option_vals = $this->get_advanced_option_vals( $product_id, $cart_id );
		}

		$GLOBALS['ec_cart_data']->cart_data->subscription_advanced_option = maybe_serialize( $option_vals );
		$GLOBALS['ec_cart_data']->save_session_to_db();

		header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $product->model_number );

	}

	private function process_update_subscription_quantity() {
		$product_id = (int) $_POST['product_id'];
		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['ec_cart_form_nonce'] ), 'wp-easycart-cart-subscription-update-item-' . $product_id ) ) {
			header( "location: " . $this->cart_page . $this->permalink_divider . 'cart_error=invalid_nonce' );
			die();
		}

		$product = $this->mysqli->get_product( "", $product_id );

		if ( $product ) {

			$quantity = (int) $_POST[ 'ec_quantity' ];
			$GLOBALS['ec_cart_data']->cart_data->subscription_quantity = $quantity;

		}

		$GLOBALS['ec_cart_data']->save_session_to_db();
		header( "location: " . $this->cart_page . $this->permalink_divider . "ec_page=subscription_info&subscription=" . $product->model_number  );
	}
	
	private function process_stripe_redirect_action() {
		if ( ! isset( $_GET['wpecnonce'] ) ) {
			return false;
		}

		wpeasycart_session()->handle_session();
		$session_id = $GLOBALS['ec_cart_data']->ec_cart_id;

		if ( ! wp_verify_nonce( sanitize_text_field( $_GET['wpecnonce'] ), 'wp-easycart-stripe-pi-order-complete-' . $session_id ) ) {
			die();
		}

		if ( ! isset( $_GET['payment_intent'] ) ) {
			return false;
		}

		if ( ! isset( $_GET['payment_intent_client_secret'] ) ) {
			return false;
		}

		// Get Payment Intent Info
		$payment_intent_id = ( isset( $_GET['payment_intent'] ) ) ? sanitize_text_field( $_GET['payment_intent'] ) : '';
		$payment_intent_client_secret = htmlspecialchars( sanitize_text_field( $_GET['payment_intent_client_secret'] ), ENT_QUOTES );

		if ( get_option( 'ec_option_payment_process_method' ) == 'stripe' ) {
			$stripe = new ec_stripe();
		} else {
			$stripe = new ec_stripe_connect();
		}
		$payment_intent = $stripe->get_payment_intent( $payment_intent_id );

		// Verify Payment Intent
		if ( ! $payment_intent ) {
			return false;
		}

		if ( ! in_array( $payment_intent->status, array( 'succeeded', 'processing', 'requires_capture', 'canceled' ) ) ) {
			return false;
		}

		global $wpdb;
		$order = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ec_order WHERE gateway_transaction_id = %s", $payment_intent->id . ':' . $payment_intent->client_secret ) );
		if ( $order ) {
			$order_id = $order->order_id;
		} else {
			// Create Order
			$source = array(
				'id' => $payment_intent_id,
				'client_secret' => $payment_intent_client_secret,
			);
			$order_id = $this->insert_ideal_order( $source, $payment_intent );
			$stripe->update_payment_intent_description( $payment_intent_id, $order_id );

			// Update Order Status
			global $wpdb;
			$order_status = 6;
			if ( $payment_intent->status == 'succeeded' ) {
				$order_status = 3;
			} else if ( $payment_intent->status == 'requires_capture' ) {
				$order_status = 12;
			} else if ( $payment_intent->status == 'processing' ) {
				$order_status = 12;
			} else if ( $payment_intent->status == 'canceled' ) {
				$order_status = 19;
			}
			$wpdb->get_row( $wpdb->prepare( "UPDATE ec_order SET orderstatus_id = %d WHERE order_id = %d", $order_status, (int) $order_id ) );
		}
		$ec_db_admin = new ec_db_admin();
		$ec_db_admin->clear_tempcart( $GLOBALS['ec_cart_data']->ec_cart_id );
		$GLOBALS['ec_cart_data']->checkout_session_complete();
		$GLOBALS['ec_cart_data']->save_session_to_db();

		// Return Response
		wp_redirect( esc_url_raw( $this->cart_page . $this->permalink_divider ) . 'ec_page=checkout_success&order_id=' . $order_id );
	}
	/* END PROCESS FORM SUBMISSION FUNCTIONS */

	/* Customer File Upload Function */
	private function file_upload_max_size() {
		$max_size = -1;
		if ( $max_size < 0 ) {
			$post_max_size = $this->parse_size( ini_get( 'post_max_size' ) );
			if ( $post_max_size > 0 ) {
				$max_size = $post_max_size;
			}

			// If upload_max_size is less, then reduce. Except if upload_max_size is
			// zero, which indicates no limit.
			$upload_max = $this->parse_size( ini_get( 'upload_max_filesize' ) );
			if ( $upload_max > 0 && $upload_max < $max_size ) {
				$max_size = $upload_max;
			}
		}
		return $max_size;
	}

	private function parse_size( $size ) {
		$unit = preg_replace( '/[^bkmgtpezy]/i', '', $size ); // Remove the non-unit characters from the size.
		$size = preg_replace( '/[^0-9\.]/', '', $size ); // Remove the non-numeric characters from the size.
		if ( $unit ) {
			// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
			return round( $size * pow( 1024, stripos( 'bkmgtpezy', $unit[0] ) ) );
		} else {
			return round( $size );
		}
	}

	private function upload_customer_file( $tempcart_id, $upload_field_name ) {
		if ( isset( $_FILES[ $upload_field_name ]['name'] ) && sanitize_text_field( $_FILES[ $upload_field_name ]['name'] ) != '' ) {
			$max_filesize = $this->file_upload_max_size();
			$max_filesize = apply_filters( 'wp_easycart_max_filesize_upload_limit', $max_filesize );

			$filetypes = array( 'text/plain', 'image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/x-compressed', 'application/x-zip-compressed', 'application/zip', 'multipart/x-zip', 'application/x-bzip2', 'application/x-bzip', 'application/x-bzip2', 'application/x-gzip', 'application/x-gzip', 'multipart/x-gzip' );
			$filtered_file_types = apply_filters( 'wpeasycart_allowed_file_upload_types', $filetypes );
			if ( is_array( $filtered_file_types) ) {
				$filetypes = $filtered_file_types;
			}
			if ( is_dir( EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/' ) ) {
				$upload_path =  EC_PLUGIN_DATA_DIRECTORY . '/products/uploads/';
			} else {
				$upload_path =  EC_PLUGIN_DIRECTORY . '/products/uploads/';
			}

			if ( (int) $_FILES[ $upload_field_name ]['size'] <= $max_filesize && in_array( sanitize_text_field( $_FILES[ $upload_field_name ]['type'] ), $filetypes ) ) {
				mkdir( $upload_path . $tempcart_id . '/', 0711 );
				$copy_to = $upload_path . $tempcart_id . '/' . sanitize_text_field( $_FILES[ $upload_field_name ]['name'] );
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$upload = wp_handle_upload( $_FILES[ $upload_field_name ], array( 'test_form' => false ) );
				if( isset( $upload['error'] ) || ! isset( $upload['file'] ) ) {
					return false;
				}
				copy( $upload['file'], $copy_to );
				unlink( $upload['file'] );
				return true;
			}
		}
		return false;
	}

	private function sanatize_card_number( $card_number ) {

		return preg_replace( "/[^0-9]/", "", $card_number );

	}

	private function get_payment_type( $card_number ) {

		if ( preg_match("/^5[1-5]\d{14}$/", $card_number ) )
				return "mastercard";

		else if ( preg_match( "/^4[0-9]{12}(?:[0-9]{3}|[0-9]{6})?$/", $card_number))
				return "visa";

		else if ( preg_match( "/^3[47][0-9]{13}$/", $card_number ) )
				return "amex";

		else if ( preg_match( "/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/", $card_number ) )
				return "diners";

		else if ( preg_match( "/^6(?:011\d{12}|5\d{14}|4[4-9]\d{13}|22(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d{2}|9(?:[01]\d|2[0-5]))\d{10})$/", $card_number ) )
				return "discover";

		else if ( preg_match( "/^(?:2131|1800|35\d{3})\d{11}$/", $card_number ) )
				return "jcb";

		else
				return wp_easycart_language()->get_text( 'cart_payment_information', 'cart_payment_card_type_credit_card' );

	}

	public function display_order_number_link( $order_id ) {

		if ( substr_count( $this->account_page, '?' ) )				$permalink_divider = "&";
		else														$permalink_divider = "?";

		if ( $GLOBALS['ec_cart_data']->cart_data->is_guest == "" ) {
			echo "<a href=\"" . esc_attr( $this->account_page . $permalink_divider ) . "ec_page=order_details&order_id=" . esc_attr( $order_id ) . "\">" . esc_attr( $order_id ) . "</a>";
		} else {
			echo "<a href=\"" . esc_attr( $this->account_page . $permalink_divider ) . "ec_page=order_details&order_id=" . esc_attr( $order_id ) . "&guest_key=" . esc_attr( $GLOBALS['ec_cart_data']->cart_data->guest_key ) . "\">" . esc_attr( $order_id ) . "</a>";
		}
	}

	public function get_shipping_method_name() {
		return $this->mysqli->get_shipping_method_name( $GLOBALS['ec_cart_data']->cart_data->shipping_method );
	}

	public function get_payment_image_source( $image ) {

		if ( file_exists( EC_PLUGIN_DATA_DIRECTORY . "/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/" . $image ) ) {
			return plugins_url( "/wp-easycart-data/design/theme/" . get_option( 'ec_option_base_theme' ) . "/images/" . $image, EC_PLUGIN_DATA_DIRECTORY );
		} else {
			return plugins_url( "/wp-easycart/design/theme/" . get_option( 'ec_option_latest_theme' ) . "/images/" . $image, EC_PLUGIN_DIRECTORY );
		}

	}

	private function add_affiliatewp_subscription_order( $order_id, $user, $product ) {

		if ( affiliate_wp()->tracking->was_referred() ) {

			$affiliate_id = affiliate_wp()->tracking->get_affiliate_id();
			$default_rate = affwp_get_affiliate_rate( $affiliate_id );
			$has_affiliate_rule = false;

			$affiliate_rule = $this->mysqli->get_affiliate_rule( affiliate_wp()->tracking->get_affiliate_id(), $product->product_id );
			if ( $affiliate_rule )
				$has_affiliate_rule = true;

			if ( $has_affiliate_rule ) {
				if ( $affiliate_rule->rule_type == "percentage" )
					$total_earned += ( $product->price * ( $affiliate_rule->rule_amount / 100 ) );

				else if ( $affiliate_rule->rule_type == "amount" )
					$total_earned += $affiliate_rule->rule_amount;	

			} else
				$total_earned += ( $product->price * $default_rate );

			$data = array(
				'affiliate_id' => $affiliate_id,
				'visit_id'     => affiliate_wp()->tracking->get_visit_id(),
				'amount'       => $total_earned,
				'description'  => $user->billing->first_name . " " . $user->billing->last_name,
				'reference'    => $order_id,
				'context'      => 'WP EasyCart',
			);
			$result = affiliate_wp()->referrals->add( $data );

			return $result;

		}

		return "";

	}

	public function get_selected_payment_method() {
		$default_method =  get_option( 'ec_option_default_payment_type' );
		if ( $GLOBALS['ec_cart_data']->cart_data->payment_method != '' )
			return $GLOBALS['ec_cart_data']->cart_data->payment_method;
		else if ( $default_method == "manual_bill" && $this->use_manual_payment() )
			return "manual_bill";
		else if ( $default_method == "affirm" && get_option( 'ec_option_use_affirm' ) )
			return "affirm";
		else if ( $default_method == "third_party" && $this->use_third_party() )
			return "third_party";
		else if ( $default_method == "credit_card" && $this->use_payment_gateway() )
			return "credit_card";
		else if ( $this->use_payment_gateway() )
			return "credit_card";
		else if ( $this->use_third_party() )
			return "third_party";
		else if ( get_option( 'ec_option_use_affirm' ) )
			return "affirm";
		else if ( $this->use_manual_payment() )
			return "manual_bill";
	}

	public function is_coupon_expired() {
		if ( $this->coupon_code == '' || ( $this->coupon && !$this->coupon->coupon_expired && ( $this->coupon->max_redemptions == 999 || $this->coupon->times_redeemed < $this->coupon->max_redemptions ) ) )
			return false;
		else
			return true;
	}

	public function get_coupon_expiration_note() {
		if ( $this->coupon_code == '' || ( $this->coupon && !$this->coupon->coupon_expired && ( $this->coupon->max_redemptions == 999 || $this->coupon->times_redeemed < $this->coupon->max_redemptions ) ) )
			return "";

		else if ( $this->coupon && $this->coupon->times_redeemed >= $this->coupon->max_redemptions )
			return wp_easycart_language()->get_text( 'cart_coupons', 'cart_max_exceeded_coupon' );

		else if ( $this->coupon->coupon_expired )
			return wp_easycart_language()->get_text( 'cart_coupons', 'cart_coupon_expired' );

		else
			return wp_easycart_language()->get_text( 'cart_coupons', 'cart_invalid_coupon' );

	}

	public function return_to_store_page( $url ) {
		return apply_filters( 'wp_easycart_return_store_url', ( get_option( 'ec_option_return_to_store_page_url' ) != "" ) ? get_option( 'ec_option_return_to_store_page_url' ) : $url );
	}

}
