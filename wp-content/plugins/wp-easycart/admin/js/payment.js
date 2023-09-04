jQuery( document ).ready( function( ){
	if( jQuery( '#ec_option_stripe_currency' ).length ) {
		jQuery( '#ec_option_stripe_currency, #ec_option_stripe_company_country' ).on( 'change', function() {
			var selected_currency = jQuery( '#ec_option_stripe_currency' ).val();
			var account_currency = jQuery( '#stripe_account_currency_note' ).attr( 'data-currency' );
			if ( selected_currency == account_currency ) {
				jQuery( '#stripe_account_currency_note' ).hide();
			} else {
				jQuery( '#stripe_account_currency_note' ).show();
			}
			var selected_country = jQuery( '#ec_option_stripe_company_country' ).val();
			var account_country = jQuery( '#stripe_account_country_note' ).attr( 'data-country' );
			if ( selected_country == account_country ) {
				jQuery( '#stripe_account_country_note' ).hide();
			} else {
				jQuery( '#stripe_account_country_note' ).show();
			}
		} );
	}
} );
function toggle_direct_deposit( ){	
	ec_admin_save_direct_deposit_options( );
}

function direct_deposit_show_advanced( ){
	if( !jQuery( document.getElementById( 'ec_direct_deposit_options' ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_direct_deposit_options' ) ).show( );
		jQuery( document.getElementById( 'direct_deposit_advanced_link' ) ).html( 'Advanced Options &#9650;' );
	}else{
		jQuery( document.getElementById( 'ec_direct_deposit_options' ) ).hide( );
		jQuery( document.getElementById( 'direct_deposit_advanced_link' ) ).html( 'Advanced Options &#9660;' );
	}
	return false;
}

function ec_admin_save_direct_deposit_options( ){
	jQuery( document.getElementById( "ec_admin_direct_deposit_display_loader" ) ).fadeIn( 'fast' );

	var ec_option_use_direct_deposit = 0;
	if( jQuery( document.getElementById( 'ec_option_use_direct_deposit' ) ).is( ':checked' ) )
		ec_option_use_direct_deposit = 1;
	var title = jQuery( document.getElementById( 'ec_option_manual_payment_title' ) ).val( );
	var file_name = jQuery( document.getElementById( 'manual_bill_file_name' ) ).val( );
	var key_section = jQuery( document.getElementById( 'manual_bill_key_section' ) ).val( );
	var ec_option_direct_deposit_message = jQuery( document.getElementById( 'ec_option_direct_deposit_message' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_direct_deposit',
		ec_option_use_direct_deposit: ec_option_use_direct_deposit,
		ec_language_field: {cart_payment_information_manual_payment: title},
		isupdate: 1,
		file_name: file_name,
		key_section: key_section,
		ec_option_direct_deposit_message: ec_option_direct_deposit_message,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_direct_deposit_display_loader' );
	} } );

	return false;
}

function toggle_third_party( ){
	var current_selection = jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).val( );
	if( current_selection == "0" || current_selection == "custom_thirdparty" ){
		jQuery( document.getElementById( 'ec_admin_third_party_none' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_third_party_none' ) ).hide( );
	}
	if( current_selection != "0" ){
		jQuery( document.getElementById( 'ec_option_paypal_enable_live' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_paypal_enable_sandbox' ) ).attr( 'checked', false );
	}
	jQuery( '.ec_admin_settings_third_party_section' ).hide( );
	jQuery( document.getElementById( current_selection ) ).show( );
}

function ec_admin_save_third_party_selection( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var selected_third_party = jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_third_party_selection',
		ec_option_payment_third_party: selected_third_party,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_2checkout_thirdparty_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var sid_id = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_sid' ) ).val( );
	var secret = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_secret_word' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_currency_code' ) ).val( );
	var lang = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_lang' ) ).val( );
	var step = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_purchase_step' ) ).val( );
	var mode = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_sandbox_mode' ) ).val( );
	var demo = jQuery( document.getElementById( 'ec_option_2checkout_thirdparty_demo_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_2checkout_thirdparty',
		ec_option_payment_third_party: '2checkout_thirdparty',
		ec_option_2checkout_thirdparty_sid: sid_id,
		ec_option_2checkout_thirdparty_secret_word: secret,
		ec_option_2checkout_thirdparty_currency_code: currency,
		ec_option_2checkout_thirdparty_lang: lang,
		ec_option_2checkout_thirdparty_purchase_step: step,
		ec_option_2checkout_thirdparty_sandbox_mode: mode,
		ec_option_2checkout_thirdparty_demo_mode: demo
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_cashfree_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var ec_option_cashfree_app_id = jQuery( document.getElementById( 'ec_option_cashfree_app_id' ) ).val( );
	var ec_option_cashfree_secret = jQuery( document.getElementById( 'ec_option_cashfree_secret' ) ).val( );
	var ec_option_cashfree_currency = jQuery( document.getElementById( 'ec_option_cashfree_currency' ) ).val( );
	var ec_option_cashfree_testmode = jQuery( document.getElementById( 'ec_option_cashfree_testmode' ) ).val( );

	var data = {
		action: 'ec_admin_ajax_save_cashfree',
		ec_option_payment_third_party: 'cashfree',
		ec_option_cashfree_app_id: ec_option_cashfree_app_id,
		ec_option_cashfree_secret: ec_option_cashfree_secret,
		ec_option_cashfree_currency: ec_option_cashfree_currency,
		ec_option_cashfree_testmode: ec_option_cashfree_testmode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_dwolla_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var account_id = jQuery( document.getElementById( 'ec_option_dwolla_thirdparty_account_id' ) ).val( );
	var key = jQuery( document.getElementById( 'ec_option_dwolla_thirdparty_key' ) ).val( );
	var secret = jQuery( document.getElementById( 'ec_option_dwolla_thirdparty_secret' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_dwolla_thirdparty_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_dwolla',
		ec_option_payment_third_party: 'dwolla_thirdparty',
		ec_option_dwolla_thirdparty_account_id: account_id,
		ec_option_dwolla_thirdparty_key: key,
		ec_option_dwolla_thirdparty_secret: secret,
		ec_option_dwolla_thirdparty_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_nets_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_nets_merchant_id' ) ).val( );
	var shared_secret = jQuery( document.getElementById( 'ec_option_nets_token' ) ).val( );
	var currency_code = jQuery( document.getElementById( 'ec_option_nets_currency' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_nets_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_nets',
		ec_option_payment_third_party: 'nets',
		ec_option_nets_merchant_id: merchant_id,
		ec_option_nets_token: shared_secret,
		ec_option_nets_currency: currency_code,
		ec_option_nets_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_payfast_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_payfast_merchant_id' ) ).val( );
	var merchant_key = jQuery( document.getElementById( 'ec_option_payfast_merchant_key' ) ).val( );
	var passphrase = jQuery( document.getElementById( 'ec_option_payfast_passphrase' ) ).val( );
	var sandbox = jQuery( document.getElementById( 'ec_option_payfast_sandbox' ) ).val( );

	var data = {
		action: 'ec_admin_ajax_save_payfast',
		ec_option_payment_third_party: 'payfast_thirdparty',
		ec_option_payfast_merchant_id: merchant_id,
		ec_option_payfast_merchant_key: merchant_key,
		ec_option_payfast_passphrase: passphrase,
		ec_option_payfast_sandbox: sandbox
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_payfort_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_payfort_merchant_id' ) ).val( );
	var access_code = jQuery( document.getElementById( 'ec_option_payfort_access_code' ) ).val( );
	var sha_type = jQuery( document.getElementById( 'ec_option_payfort_sha_type' ) ).val( );
	var request_phrase = jQuery( document.getElementById( 'ec_option_payfort_request_phrase' ) ).val( );
	var response_phrase = jQuery( document.getElementById( 'ec_option_payfort_response_phrase' ) ).val( );
	var language = jQuery( document.getElementById( 'ec_option_payfort_language' ) ).val( );
	var currency_code = jQuery( document.getElementById( 'ec_option_payfort_currency_code' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_payfort_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_payfort',
		ec_option_payment_third_party: 'payfort',
		ec_option_payfort_merchant_id: merchant_id,
		ec_option_payfort_access_code: access_code,
		ec_option_payfort_sha_type: sha_type,
		ec_option_payfort_request_phrase: request_phrase,
		ec_option_payfort_response_phrase: response_phrase,
		ec_option_payfort_language: language,
		ec_option_payfort_currency_code: currency_code,
		ec_option_payfort_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_paymentexpress_thirdparty_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var username = jQuery( document.getElementById( 'ec_option_payment_express_thirdparty_username' ) ).val( );
	var key = jQuery( document.getElementById( 'ec_option_payment_express_thirdparty_key' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_payment_express_thirdparty_currency' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_paymentexpress_thirdparty',
		ec_option_payment_third_party: 'paymentexpress_thirdparty',
		ec_option_payment_express_thirdparty_username: username,
		ec_option_payment_express_thirdparty_key: key,
		ec_option_payment_express_thirdparty_currency: currency
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function paypal_on_off( ){
	if( jQuery( document.getElementById( 'use_paypal' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_toggle_row' ).addClass( 'selected' );
		jQuery( '.ec_admin_paypal_toggle_on' ).show( );
		jQuery( document.getElementById( 'ec_admin_paypal_express_row' ) ).show( );
		jQuery( document.getElementById( 'paypal_advanced_toggle' ) ).show( );
		jQuery( '.ec_admin_paypal_or' ).hide( );
		jQuery( '.ec_paypal_choose_other' ).hide( );
		jQuery( '.ec_admin_paypal_toggle_row > img' ).hide( );
	}else{ 
		jQuery( '.ec_admin_paypal_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_express_row' ) ).hide( );
		jQuery( document.getElementById( 'paypal_advanced_toggle' ) ).hide( );
		jQuery( '.ec_admin_paypal_or' ).show( );
		jQuery( '.ec_paypal_choose_other' ).show( );
		jQuery( '.ec_admin_paypal_toggle_row > img' ).show( );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).prop( 'checked', false );
		jQuery( '.ec_admin_paypal_express_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).removeClass( 'selected' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).prop( 'checked', false );
	}
	ec_admin_save_paypal_options( );
}

function paypal_live_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_live' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_express_toggle_row' ).addClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).show( );
		jQuery( '.ec_admin_paypal_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).show( );
		jQuery( document.getElementById( 'use_paypal' ) ).attr( 'checked', true );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).attr( 'checked', true );
		jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( '0' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_sandbox' ) ).attr( 'checked', false );
		if( jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).length ){
			jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).val( '0' );
			toggle_third_party( );
		}

	}else{ 
		jQuery( '.ec_admin_paypal_express_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( '.ec_admin_paypal_toggle_on' ).show( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).removeClass( 'selected' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'use_paypal' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).attr( 'checked', false );
	}
	ec_admin_save_paypal_options( );
}

function paypal_sandbox_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_sandbox' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_express_toggle_row' ).addClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).show( );
		jQuery( '.ec_admin_paypal_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).show( );
		jQuery( document.getElementById( 'use_paypal' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_live' ) ).attr( 'checked', false );
		if( jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).length ){
			jQuery( document.getElementById( 'ec_option_payment_third_party' ) ).val( '0' );
			toggle_third_party( );
		}

	}else{ 
		jQuery( '.ec_admin_paypal_express_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( '.ec_admin_paypal_toggle_on' ).show( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).removeClass( 'selected' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'use_paypal' ) ).val( '0' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).val( '0' );
	}
	ec_admin_save_paypal_options( );
}

/* PRO Version */
function pro_paypal_on_off( ){
	if( jQuery( document.getElementById( 'use_paypal' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_toggle_row' ).addClass( 'selected' );
		jQuery( '.ec_admin_paypal_toggle_on' ).show( );
		jQuery( document.getElementById( 'ec_admin_paypal_express_row' ) ).show( );
		jQuery( document.getElementById( 'paypal_advanced_toggle' ) ).show( );
		jQuery( '.ec_admin_paypal_or' ).hide( );
		jQuery( '.ec_paypal_choose_other' ).hide( );
		jQuery( '.ec_admin_paypal_toggle_row > img' ).hide( );
	}else{ 
		jQuery( '.ec_admin_paypal_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_express_row' ) ).hide( );
		jQuery( document.getElementById( 'paypal_advanced_toggle' ) ).hide( );
		jQuery( '.ec_admin_paypal_or' ).show( );
		jQuery( '.ec_paypal_choose_other' ).show( );
		jQuery( '.ec_admin_paypal_toggle_row > img' ).show( );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).prop( 'checked', false );
		jQuery( '.ec_admin_paypal_express_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).removeClass( 'selected' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).prop( 'checked', false );
	}
	ec_admin_pro_save_paypal_options( );
}

function pro_paypal_express_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_express_toggle_row' ).addClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).show( );
		jQuery( '.ec_admin_paypal_toggle_on' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).show( );
	}else{ 
		jQuery( '.ec_admin_paypal_express_toggle_row' ).removeClass( 'selected' );
		jQuery( '.ec_admin_paypal_express_toggle_on' ).hide( );
		jQuery( '.ec_admin_paypal_toggle_on' ).show( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_credit_row' ) ).removeClass( 'selected' );
		jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).prop( 'checked', false );
	}
	ec_admin_pro_save_paypal_options( );
}

function pro_paypal_credit_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).is( ':checked' ) ){
		jQuery( '.ec_admin_paypal_credit_toggle_row' ).addClass( 'selected' );
	}else{ 
		jQuery( '.ec_admin_paypal_credit_toggle_row' ).removeClass( 'selected' );
	}
	ec_admin_pro_save_paypal_options( );
}

function ec_admin_pro_save_paypal_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var email = jQuery( document.getElementById( 'ec_option_paypal_email' ) ).val( );

	var third_party_selected = '0';
	if( jQuery( document.getElementById( 'use_paypal' ) ).is( ':checked' ) )
		third_party_selected = 'paypal';

	var paypal_paynow = '0';
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_pay_now' ) ).is( ':checked' ) )
		paypal_paynow = '1';

	var paypal_credit = '0';
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).is( ':checked' ) )
		paypal_credit = '1';

	var paypal_credit_sandbox = "";
	if( jQuery( document.getElementById( 'ec_option_paypal_sandbox_app_id' ) ).length )
		paypal_credit_sandbox = jQuery( document.getElementById( 'ec_option_paypal_sandbox_app_id' ) ).val( );
	var paypal_sandbox_secret = "";
	if( jQuery( document.getElementById( 'ec_option_paypal_sandbox_secret' ) ).length )
		paypal_sandbox_secret = jQuery( document.getElementById( 'ec_option_paypal_sandbox_secret' ) ).val( );

	var paypal_credit_production = "";
	if( jQuery( document.getElementById( 'ec_option_paypal_production_app_id' ) ).length )
		paypal_credit_production = jQuery( document.getElementById( 'ec_option_paypal_production_app_id' ) ).val( );
	var paypal_production_secret = "";
	if( jQuery( document.getElementById( 'ec_option_paypal_production_secret' ) ).length )
		paypal_production_secret = jQuery( document.getElementById( 'ec_option_paypal_production_secret' ) ).val( );

	var currency = jQuery( document.getElementById( 'ec_option_paypal_currency_code' ) ).val( );
	var currency_widget = jQuery( document.getElementById( 'ec_option_paypal_use_selected_currency' ) ).val( );
	var language_code = jQuery( document.getElementById( 'ec_option_paypal_lc' ) ).val( );
	var character_set = jQuery( document.getElementById( 'ec_option_paypal_charset' ) ).val( );
	var weight_unit = jQuery( document.getElementById( 'ec_option_paypal_weight_unit' ) ).val( );
	var sandbox_mode = jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( );
	var verified_address = jQuery( document.getElementById( 'ec_option_paypal_collect_shipping' ) ).val( );

	var button_color = jQuery( document.getElementById( 'ec_option_paypal_button_color' ) ).val( );
	var button_shape = jQuery( document.getElementById( 'ec_option_paypal_button_shape' ) ).val( );
	var page1_checkout = jQuery( document.getElementById( 'ec_option_paypal_express_page1_checkout' ) ).val( );

	var cid_sandbox = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_sandbox' ) ).val( );
	var cid_production = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_production' ) ).val( );

	if( sandbox_mode == '1' ){
		jQuery( document.getElementById( 'paypal_express_sandbox' ) ).show( );
		jQuery( document.getElementById( 'paypal_express_production' ) ).hide( );
	}else{
		jQuery( document.getElementById( 'paypal_express_sandbox' ) ).hide( );
		jQuery( document.getElementById( 'paypal_express_production' ) ).show( );
	}

	var data = {
		action: 'ec_admin_ajax_save_pro_paypal',
		ec_option_payment_third_party: third_party_selected,
		ec_option_paypal_email: email,
		ec_option_paypal_enable_pay_now: paypal_paynow,
		ec_option_paypal_enable_credit: paypal_credit,
		ec_option_paypal_sandbox_app_id: paypal_credit_sandbox,
		ec_option_paypal_sandbox_secret: paypal_sandbox_secret,
		ec_option_paypal_production_app_id: paypal_credit_production,
		ec_option_paypal_production_secret: paypal_production_secret,
		ec_option_paypal_currency_code: currency,
		ec_option_paypal_use_selected_currency: currency_widget,
		ec_option_paypal_lc: language_code,
		ec_option_paypal_charset: character_set,
		ec_option_paypal_weight_unit: weight_unit,
		ec_option_paypal_use_sandbox: sandbox_mode,
		ec_option_paypal_collect_shipping: verified_address,
		ec_option_paypal_button_color: button_color,
		ec_option_paypal_button_shape: button_shape,
		ec_option_paypal_express_page1_checkout: page1_checkout,
		ec_option_paypal_marketing_solution_cid_sandbox: cid_sandbox,
		ec_option_paypal_marketing_solution_cid_production: cid_production,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_paypal_sandbox_update( );
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}
/* PRO Version */

function paypal_show_advanced( ){
	if( !jQuery( '.ec_admin_paypal_advanced_toggle_on' ).is( ':visible' ) ){
		jQuery( '.ec_admin_paypal_advanced_toggle_on' ).show( );
		jQuery( document.getElementById( 'paypal_advanced_link' ) ).html( wp_easycart_payment_language['advanced-options'] + ' &#9650;' );
	}else{
		jQuery( '.ec_admin_paypal_advanced_toggle_on' ).hide( );
		jQuery( document.getElementById( 'paypal_advanced_link' ) ).html( wp_easycart_payment_language['advanced-options'] + ' &#9660;' );
	}
	return false;
}

function ec_paypal_sandbox_update( ){
	if( jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( ) == '1' ){
		jQuery( '.ec_admin_paypal_sandbox_express' ).show( );
		jQuery( '.ec_admin_paypal_production_express' ).hide( );

		if( jQuery( document.getElementById( 'ec_option_paypal_sandbox_merchant_id' ) ).val( ) != '' ){
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).hide( );
			jQuery( '.ec_admin_paypal_express_credentials' ).hide( );
			jQuery( '.ec_admin_paypal_authorize_button' ).show( );

		}else if( jQuery( document.getElementById( 'ec_option_paypal_sandbox_app_id' ) ).val( ) != '' ){
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).hide( );
			jQuery( '.ec_admin_paypal_authorize_button' ).hide( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).show( );
			jQuery( '#ec_admin_paypal_express_credentials_toggle > a' ).html( wp_easycart_payment_language['one-click-setup'] );
			jQuery( '.ec_admin_paypal_express_credentials' ).show( );
			jQuery( '.ec_admin_paypal_express_credentials_sandbox' ).show( );
			jQuery( '.ec_admin_paypal_express_credentials_production' ).hide( );

		}else{
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).show( );
		}

		/* PayPal Marketing Options */
		if( typeof wpec_paypal_marketing_options !== 'undefined' ){
			wpec_paypal_marketing_options.env = 'sandbox';
			wpec_paypal_marketing_options.cid = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_sandbox' ) ).val( );
			jQuery( document.getElementById( 'paypal-muse-button-container' ) ).html( '' );
			jQuery( document.getElementById( 'ec_paypal_marketing_disconnect_production' ) ).hide( );
			if( jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_sandbox' ) ).val( ) != '' ){
				jQuery( document.getElementById( 'ec_paypal_marketing_disconnect_sandbox' ) ).show( );
			}
			MUSEButton( 'paypal-muse-button-container', wpec_paypal_marketing_options );
		}

	}else{
		jQuery( '.ec_admin_paypal_sandbox_express' ).hide( );
		jQuery( '.ec_admin_paypal_production_express' ).show( );

		if( jQuery( document.getElementById( 'ec_option_paypal_production_merchant_id' ) ).val( ) != '' ){
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).hide( );
			jQuery( '.ec_admin_paypal_express_credentials' ).hide( );

		}else if( jQuery( document.getElementById( 'ec_option_paypal_production_app_id' ) ).val( ) != '' ){
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).hide( );
			jQuery( '.ec_admin_paypal_authorize_button' ).hide( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).show( );
			jQuery( '#ec_admin_paypal_express_credentials_toggle > a' ).html( wp_easycart_payment_language['one-click-setup'] );
			jQuery( '.ec_admin_paypal_express_credentials' ).show( );
			jQuery( '.ec_admin_paypal_express_credentials_sandbox' ).hide( );
			jQuery( '.ec_admin_paypal_express_credentials_production' ).show( );

		}else{
			jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_paypal_express_credentials_toggle' ) ).show( );
			jQuery( '#ec_admin_paypal_express_credentials_toggle > a' ).html( wp_easycart_payment_language['manual-api-input'] );
			jQuery( '.ec_admin_paypal_express_credentials' ).hide( );
		}

		/* PayPal Marketing Options */
		if( typeof wpec_paypal_marketing_options !== 'undefined' ){
			wpec_paypal_marketing_options.env = 'production';
			wpec_paypal_marketing_options.cid = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_production' ) ).val( );
			jQuery( document.getElementById( 'paypal-muse-button-container' ) ).html( '' );
			jQuery( document.getElementById( 'ec_paypal_marketing_disconnect_sandbox' ) ).hide( );
			if( jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_production' ) ).val( ) != '' ){
				jQuery( document.getElementById( 'ec_paypal_marketing_disconnect_production' ) ).show( );
			}
			MUSEButton( 'paypal-muse-button-container', wpec_paypal_marketing_options );
		}

	}

}

function ec_admin_show_express_credentials( ){
	if( jQuery( '.ec_admin_paypal_express_credentials' ).is( ':visible' ) ){
		jQuery( '#ec_admin_paypal_express_credentials_toggle > a' ).html( wp_easycart_payment_language['manual-api-input'] );
		jQuery( '.ec_admin_paypal_express_credentials' ).hide( );
		jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).show( );

		if( jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( ) == '1' ){
			jQuery( '.ec_admin_paypal_sandbox_express' ).show( );
			jQuery( '.ec_admin_paypal_production_express' ).hide( );
		}else{
			jQuery( '.ec_admin_paypal_sandbox_express' ).hide( );
			jQuery( '.ec_admin_paypal_production_express' ).show( );
		}

	}else{
		jQuery( '.ec_admin_paypal_express_credentials' ).show( );
		jQuery( '#ec_admin_paypal_express_credentials_toggle > a' ).html( wp_easycart_payment_language['one-click-setup'] );
		jQuery( document.getElementById( 'ec_admin_paypal_express_onboard' ) ).hide( );
		jQuery( '.ec_admin_paypal_sandbox_express' ).hide( );
		jQuery( '.ec_admin_paypal_production_express' ).hide( );

		if( jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( ) == '1' ){
			jQuery( '.ec_admin_paypal_express_credentials_sandbox' ).show( );
			jQuery( '.ec_admin_paypal_express_credentials_production' ).hide( );
		}else{
			jQuery( '.ec_admin_paypal_express_credentials_sandbox' ).hide( );
			jQuery( '.ec_admin_paypal_express_credentials_production' ).show( );
		}

	}
	return false;
}

function ec_admin_save_paypal_options( ){
	jQuery( document.getElementById( "ec_admin_paypal_display_loader" ) ).fadeIn( 'fast' );
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var email = jQuery( document.getElementById( 'ec_option_paypal_email' ) ).val( );

	var third_party_selected = '';
	var paypal_paynow = '0';
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_sandbox' ) ).is( ':checked' ) || jQuery( document.getElementById( 'ec_option_paypal_enable_live' ) ).is( ':checked' ) ){
		third_party_selected = 'paypal';
		paypal_paynow = '1';
	}

	var paypal_credit = '0';
	if( jQuery( document.getElementById( 'ec_option_paypal_enable_credit' ) ).val( ) == '1' )
		paypal_credit = '1';

	var currency = jQuery( document.getElementById( 'ec_option_paypal_currency_code' ) ).val( );
	var currency_widget = jQuery( document.getElementById( 'ec_option_paypal_use_selected_currency' ) ).val( );
	var use_venmo = jQuery( document.getElementById( 'ec_option_paypal_use_venmo' ) ).val( );
	var language_code = jQuery( document.getElementById( 'ec_option_paypal_lc' ) ).val( );
	var character_set = jQuery( document.getElementById( 'ec_option_paypal_charset' ) ).val( );
	var weight_unit = jQuery( document.getElementById( 'ec_option_paypal_weight_unit' ) ).val( );
	var sandbox_mode = jQuery( document.getElementById( 'ec_option_paypal_use_sandbox' ) ).val( );
	var verified_address = jQuery( document.getElementById( 'ec_option_paypal_collect_shipping' ) ).val( );
	var page1_checkout = 1;
	if( jQuery( document.getElementById( 'ec_option_paypal_express_page1_checkout' ) ).length ){
		page1_checkout = jQuery( document.getElementById( 'ec_option_paypal_express_page1_checkout' ) ).val( );
	}

	var button_color = jQuery( document.getElementById( 'ec_option_paypal_button_color' ) ).val( );
	var button_shape = jQuery( document.getElementById( 'ec_option_paypal_button_shape' ) ).val( );

	var cid_sandbox = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_sandbox' ) ).val( );
	var cid_production = jQuery( document.getElementById( 'ec_option_paypal_marketing_solution_cid_production' ) ).val( );

	if( sandbox_mode == '1' ){
		jQuery( document.getElementById( 'paypal_express_sandbox' ) ).show( );
		jQuery( document.getElementById( 'paypal_express_production' ) ).hide( );
	}else{
		jQuery( document.getElementById( 'paypal_express_sandbox' ) ).hide( );
		jQuery( document.getElementById( 'paypal_express_production' ) ).show( );
	}

	var data = {
		action: 'ec_admin_ajax_save_paypal',
		ec_option_payment_third_party: third_party_selected,
		ec_option_paypal_enable_pay_now: paypal_paynow,
		ec_option_paypal_enable_credit: paypal_credit,
		ec_option_paypal_currency_code: currency,
		ec_option_paypal_use_venmo: use_venmo,
		ec_option_paypal_use_selected_currency: currency_widget,
		ec_option_paypal_lc: language_code,
		ec_option_paypal_charset: character_set,
		ec_option_paypal_weight_unit: weight_unit,
		ec_option_paypal_use_sandbox: sandbox_mode,
		ec_option_paypal_collect_shipping: verified_address,
		ec_option_paypal_button_color: button_color,
		ec_option_paypal_button_shape: button_shape,
		ec_option_paypal_express_page1_checkout: page1_checkout,
		ec_option_paypal_marketing_solution_cid_sandbox: cid_sandbox,
		ec_option_paypal_marketing_solution_cid_production: cid_production,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_paypal_sandbox_update( );
		ec_admin_hide_loader( 'ec_admin_paypal_display_loader' );
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_realex_thirdparty_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_realex_thirdparty_merchant_id' ) ).val( );
	var secret = jQuery( document.getElementById( 'ec_option_realex_thirdparty_secret' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_realex_thirdparty_currency' ) ).val( );
	var account = jQuery( document.getElementById( 'ec_option_realex_thirdparty_account' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_realex_thirdparty',
		ec_option_payment_third_party: 'realex_thirdparty',
		ec_option_realex_thirdparty_merchant_id: merchant_id,
		ec_option_realex_thirdparty_secret: secret,
		ec_option_realex_thirdparty_account: account,
		ec_option_realex_thirdparty_currency: currency
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_redsys_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_code = jQuery( document.getElementById( 'ec_option_redsys_merchant_code' ) ).val( );
	var terminal = jQuery( document.getElementById( 'ec_option_redsys_terminal' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_redsys_currency' ) ).val( );
	var secret_key = jQuery( document.getElementById( 'ec_option_redsys_key' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_redsys_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_redsys',
		ec_option_payment_third_party: 'redsys',
		ec_option_redsys_merchant_code: merchant_code,
		ec_option_redsys_terminal: terminal,
		ec_option_redsys_currency: currency,
		ec_option_redsys_key: secret_key,
		ec_option_redsys_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_sagepay_paynow_za_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var service_key = jQuery( document.getElementById( 'ec_option_sagepay_paynow_za_service_key' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_sagepay_paynow_za',
		ec_option_payment_third_party: 'sagepay_paynow_za',
		ec_option_sagepay_paynow_za_service_key: service_key
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function ec_admin_save_skrill_options( ){
	jQuery( document.getElementById( "ec_admin_third_party_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_skrill_merchant_id' ) ).val( );
	var company_name = jQuery( document.getElementById( 'ec_option_skrill_company_name' ) ).val( );
	var email = jQuery( document.getElementById( 'ec_option_skrill_email' ) ).val( );
	var language = jQuery( document.getElementById( 'ec_option_skrill_language' ) ).val( );
	var currency_code = jQuery( document.getElementById( 'ec_option_skrill_currency_code' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_skrill',
		ec_option_payment_third_party: 'skrill',
		ec_option_skrill_merchant_id: merchant_id,
		ec_option_skrill_company_name: company_name,
		ec_option_skrill_email: email,
		ec_option_skrill_language: language,
		ec_option_skrill_currency_code: currency_code
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_third_party_display_loader' );
	} } );

	return false;
}

function toggle_live_gateways( ){
	var current_selection = jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).val( );
	if( current_selection == "0" || current_selection == "custom" ){
		jQuery( document.getElementById( 'ec_admin_live_gateway_none' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_live_gateway_none' ) ).hide( );
	}
	if( current_selection != "0" ){
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_live' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_square_enable' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_sandbox' ) ).attr( 'checked', false );
	}
	jQuery( '.ec_admin_settings_live_payment_section' ).hide( );
	jQuery( document.getElementById( current_selection ) ).show( );
}

function ec_admin_save_live_gateway_selection( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var selected_live_payment = jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_live_gateway_selection',
		ec_option_payment_process_method: selected_live_payment
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_amazonpay_options( ){
	jQuery( document.getElementById( "ec_admin_amazonpay_display_loader" ) ).fadeIn( 'fast' );
	var enabled = 0;
	if( jQuery( document.getElementById( 'ec_option_amazonpay_enable' ) ).is( ':checked' ) ) {
		enabled = 1;
	}
	if ( enabled ) {
		jQuery( document.getElementById( 'wpeasycart_amazonpay_settings' ) ).show();
	} else {
		jQuery( document.getElementById( 'wpeasycart_amazonpay_settings' ) ).hide();
	}
	var store_id = jQuery( document.getElementById( 'ec_option_amazonpay_store_id' ) ).val( );
	var merchant_id = jQuery( document.getElementById( 'ec_option_amazonpay_merchant_id' ) ).val( );
	var public_key = jQuery( document.getElementById( 'ec_option_amazonpay_public_key' ) ).val( );
	var private_key = jQuery( document.getElementById( 'ec_option_amazonpay_private_key' ) ).val( );
	var region = jQuery( document.getElementById( 'ec_option_amazonpay_region' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_amazonpay_currency' ) ).val( );
	var language = jQuery( document.getElementById( 'ec_option_amazonpay_language' ) ).val( );
	var sandbox = jQuery( document.getElementById( 'ec_option_amazonpay_is_sandbox' ) ).val( );
	var button_color = jQuery( document.getElementById( 'ec_option_amazonpay_pay_button_color' ) ).val( );
	var hide_early = jQuery( document.getElementById( 'ec_option_amazonpay_hide_early_buttons' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_amazonpay',
		ec_option_amazonpay_enable: enabled,
		ec_option_amazonpay_store_id: store_id,
		ec_option_amazonpay_merchant_id: merchant_id,
		ec_option_amazonpay_public_key: public_key,
		ec_option_amazonpay_private_key: private_key,
		ec_option_amazonpay_region: region,
		ec_option_amazonpay_currency: currency,
		ec_option_amazonpay_language: language,
		ec_option_amazonpay_is_sandbox: sandbox,
		ec_option_amazonpay_pay_button_color: button_color,
		ec_option_amazonpay_hide_early_buttons: hide_early
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_amazonpay_display_loader' );
	} } );

	return false;
}

function ec_admin_save_authorize_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var login_id = jQuery( document.getElementById( 'ec_option_authorize_login_id' ) ).val( );
	var transaction_key = jQuery( document.getElementById( 'ec_option_authorize_trans_key' ) ).val( );
	var currency_code = jQuery( document.getElementById( 'ec_option_authorize_currency_code' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_authorize_test_mode' ) ).val( );
	var developer_account = jQuery( document.getElementById( 'ec_option_authorize_developer_account' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_authorize',
		ec_option_payment_process_method: 'authorize',
		ec_option_authorize_login_id: login_id,
		ec_option_authorize_trans_key: transaction_key,
		ec_option_authorize_currency_code: currency_code,
		ec_option_authorize_test_mode: test_mode,
		ec_option_authorize_developer_account: developer_account
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_beanstream_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_beanstream_merchant_id' ) ).val( );
	var passcode = jQuery( document.getElementById( 'ec_option_beanstream_api_passcode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_beanstream',
		ec_option_payment_process_method: 'beanstream',
		ec_option_beanstream_merchant_id: merchant_id,
		ec_option_beanstream_api_passcode: passcode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_braintree_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_braintree_merchant_id' ) ).val( );
	var merchant_account_id = jQuery( document.getElementById( 'ec_option_braintree_merchant_account_id' ) ).val( );
	var public_key = jQuery( document.getElementById( 'ec_option_braintree_public_key' ) ).val( );
	var private_key = jQuery( document.getElementById( 'ec_option_braintree_private_key' ) ).val( );
	var environment = jQuery( document.getElementById( 'ec_option_braintree_environment' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_braintree',
		ec_option_payment_process_method: 'braintree',
		ec_option_braintree_merchant_id: merchant_id,
		ec_option_braintree_merchant_account_id: merchant_account_id,
		ec_option_braintree_public_key: public_key,
		ec_option_braintree_private_key: private_key,
		ec_option_braintree_environment: environment
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_cardpointe_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var ec_option_cardpointe_site = jQuery( document.getElementById( 'ec_option_cardpointe_site' ) ).val( );
	var ec_option_cardpointe_merch = jQuery( document.getElementById( 'ec_option_cardpointe_merch' ) ).val( );
	var ec_option_cardpointe_username = jQuery( document.getElementById( 'ec_option_cardpointe_username' ) ).val( );
	var ec_option_cardpointe_password = jQuery( document.getElementById( 'ec_option_cardpointe_password' ) ).val( );
	var ec_option_cardpointe_currency = jQuery( document.getElementById( 'ec_option_cardpointe_currency' ) ).val( );
	var ec_option_cardpointe_shipfromzip = jQuery( document.getElementById( 'ec_option_cardpointe_shipfromzip' ) ).val( );

	var data = {
		action: 'ec_admin_ajax_save_cardpointe',
		ec_option_payment_process_method: 'cardpointe',
		ec_option_cardpointe_site: ec_option_cardpointe_site,
		ec_option_cardpointe_merch: ec_option_cardpointe_merch,
		ec_option_cardpointe_username: ec_option_cardpointe_username,
		ec_option_cardpointe_password: ec_option_cardpointe_password,
		ec_option_cardpointe_currency: ec_option_cardpointe_currency,
		ec_option_cardpointe_shipfromzip: ec_option_cardpointe_shipfromzip
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_chronopay_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var currency = jQuery( document.getElementById( 'ec_option_chronopay_currency' ) ).val( );
	var product_id = jQuery( document.getElementById( 'ec_option_chronopay_product_id' ) ).val( );
	var shared_secret = jQuery( document.getElementById( 'ec_option_chronopay_shared_secret' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_chronopay',
		ec_option_payment_process_method: 'chronopay',
		ec_option_chronopay_currency: currency,
		ec_option_chronopay_product_id: product_id,
		ec_option_chronopay_shared_secret: shared_secret
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_virtualmerchant_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_virtualmerchant_ssl_merchant_id' ) ).val( );
	var user_id = jQuery( document.getElementById( 'ec_option_virtualmerchant_ssl_user_id' ) ).val( );
	var pin = jQuery( document.getElementById( 'ec_option_virtualmerchant_ssl_pin' ) ).val( );
	var demo_account = jQuery( document.getElementById( 'ec_option_virtualmerchant_demo_account' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_virtualmerchant',
		ec_option_payment_process_method: 'virtualmerchant',
		ec_option_virtualmerchant_ssl_merchant_id: merchant_id,
		ec_option_virtualmerchant_ssl_user_id: user_id,
		ec_option_virtualmerchant_ssl_pin: pin,
		ec_option_virtualmerchant_demo_account: demo_account
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_eway_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var use_rapid_pay = jQuery( document.getElementById( 'ec_option_eway_use_rapid_pay' ) ).val( );
	var eway_api_key = jQuery( document.getElementById( 'ec_option_eway_api_key' ) ).val( );
	var eway_api_password = jQuery( document.getElementById( 'ec_option_eway_api_password' ) ).val( );
	var eway_client_key = jQuery( document.getElementById( 'ec_option_eway_client_key' ) ).val( );
	var customer_id = jQuery( document.getElementById( 'ec_option_eway_customer_id' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_eway_test_mode' ) ).val( );
	var process_test_mode = jQuery( document.getElementById( 'ec_option_eway_test_mode_success' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_eway',
		ec_option_payment_process_method: 'eway',
		ec_option_eway_use_rapid_pay: use_rapid_pay,
		ec_option_eway_api_key: eway_api_key,
		ec_option_eway_api_password: eway_api_password,
		ec_option_eway_client_key: eway_client_key,
		ec_option_eway_customer_id: customer_id,
		ec_option_eway_test_mode: test_mode,
		ec_option_eway_test_mode_success: process_test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_firstdata_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var gateway_id = jQuery( document.getElementById( 'ec_option_firstdatae4_exact_id' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_firstdatae4_password' ) ).val( );
	var keyid = jQuery( document.getElementById( 'ec_option_firstdatae4_key_id' ) ).val( );
	var key = jQuery( document.getElementById( 'ec_option_firstdatae4_key' ) ).val( );
	var language = jQuery( document.getElementById( 'ec_option_firstdatae4_language' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_firstdatae4_currency' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_firstdatae4_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_firstdata',
		ec_option_payment_process_method: 'firstdata',
		ec_option_firstdatae4_exact_id: gateway_id,
		ec_option_firstdatae4_password: password,
		ec_option_firstdatae4_key_id: keyid,
		ec_option_firstdatae4_key: key,
		ec_option_firstdatae4_language: language,
		ec_option_firstdatae4_currency: currency,
		ec_option_firstdatae4_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_goemerchant_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var center_id = jQuery( document.getElementById( 'ec_option_goemerchant_trans_center_id' ) ).val( );
	var gateway_id = jQuery( document.getElementById( 'ec_option_goemerchant_gateway_id' ) ).val( );
	var processor_id = jQuery( document.getElementById( 'ec_option_goemerchant_processor_id' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_goemerchant',
		ec_option_payment_process_method: 'goemerchant',
		ec_option_goemerchant_trans_center_id: center_id,
		ec_option_goemerchant_gateway_id: gateway_id,
		ec_option_goemerchant_processor_id: processor_id
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_intuit_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var oauth_version = jQuery( document.getElementById( 'ec_option_intuit_oauth_version' ) ).val( );
	var app_token = jQuery( document.getElementById( 'ec_option_intuit_app_token' ) ).val( );
	var consumer_key = jQuery( document.getElementById( 'ec_option_intuit_consumer_key' ) ).val( );
	var consumer_secret = jQuery( document.getElementById( 'ec_option_intuit_consumer_secret' ) ).val( );
	var client_id = jQuery( document.getElementById( 'ec_option_intuit_client_id' ) ).val( );
	var client_secret = jQuery( document.getElementById( 'ec_option_intuit_client_secret' ) ).val( );
	var currency_code = jQuery( document.getElementById( 'ec_option_intuit_currency' ) ).val( );
	var sandbox_mode = jQuery( document.getElementById( 'ec_option_intuit_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_intuit',
		ec_option_payment_process_method: 'intuit',
		ec_option_intuit_oauth_version: oauth_version,
		ec_option_intuit_app_token: app_token,
		ec_option_intuit_consumer_key: consumer_key,
		ec_option_intuit_consumer_secret: consumer_secret,
		ec_option_intuit_client_id: client_id,
		ec_option_intuit_client_secret: client_secret,
		ec_option_intuit_currency: currency_code,
		ec_option_intuit_test_mode: sandbox_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
		jQuery( document.getElementById( 'ec_admin_intuit_note' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_intuit_note_refresh' ) ).show( );
	} } );

	return false;
}

function ec_admin_save_migs_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var signature = jQuery( document.getElementById( 'ec_option_migs_signature' ) ).val( );
	var access_code = jQuery( document.getElementById( 'ec_option_migs_access_code' ) ).val( );
	var merchant_id = jQuery( document.getElementById( 'ec_option_migs_merchant_id' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_migs',
		ec_option_payment_process_method: 'migs',
		ec_option_migs_signature: signature,
		ec_option_migs_access_code: access_code,
		ec_option_migs_merchant_id: merchant_id
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_moneris_ca_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var store_id = jQuery( document.getElementById( 'ec_option_moneris_ca_store_id' ) ).val( );
	var api_token = jQuery( document.getElementById( 'ec_option_moneris_ca_api_token' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_moneris_ca_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_moneris_ca',
		ec_option_payment_process_method: 'moneris_ca',
		ec_option_moneris_ca_store_id: store_id,
		ec_option_moneris_ca_api_token: api_token,
		ec_option_moneris_ca_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_moneris_us_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var store_id = jQuery( document.getElementById( 'ec_option_moneris_us_store_id' ) ).val( );
	var api_token = jQuery( document.getElementById( 'ec_option_moneris_us_api_token' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_moneris_us_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_moneris_us',
		ec_option_payment_process_method: 'moneris_us',
		ec_option_moneris_us_store_id: store_id,
		ec_option_moneris_us_api_token: api_token,
		ec_option_moneris_us_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_update_nmi_cardinal_view( ){
	if( jQuery( document.getElementById( 'ec_option_nmi_3ds' ) ).val( ) == "0" ){
		jQuery( '.ec_admin_nmi_cardinal_setting' ).hide( );
	}else{
		jQuery( '.ec_admin_nmi_cardinal_setting' ).show( );
	}
}

function ec_admin_save_nmi_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var processing_method = jQuery( document.getElementById( 'ec_option_nmi_3ds' ) ).val( );
	var nmi_api_key = jQuery( document.getElementById( 'ec_option_nmi_api_key' ) ).val( );
	var nmi_username = jQuery( document.getElementById( 'ec_option_nmi_username' ) ).val( );
	var nmi_password = jQuery( document.getElementById( 'ec_option_nmi_password' ) ).val( );
	var postal_code = jQuery( document.getElementById( 'ec_option_nmi_ship_from_zip' ) ).val( );
	var nmi_currency = jQuery( document.getElementById( 'ec_option_nmi_currency' ) ).val( );
	var nmi_processor_id = jQuery( document.getElementById( 'ec_option_nmi_processor_id' ) ).val( );
	var commodity_code = jQuery( document.getElementById( 'ec_option_nmi_commodity_code' ) ).val( );
	var cardinal_processor_id = jQuery( document.getElementById( 'ec_option_cardinal_processor_id' ) ).val( );
	var cardinal_merchant_id = jQuery( document.getElementById( 'ec_option_cardinal_merchant_id' ) ).val( );
	var cardinal_password = jQuery( document.getElementById( 'ec_option_cardinal_password' ) ).val( );
	var cardinal_currency = jQuery( document.getElementById( 'ec_option_cardinal_currency' ) ).val( );
	var cardinal_test_mode = jQuery( document.getElementById( 'ec_option_cardinal_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_nmi',
		ec_option_payment_process_method: 'nmi',
		ec_option_nmi_3ds: processing_method,
		ec_option_nmi_api_key: nmi_api_key,
		ec_option_nmi_username: nmi_username,
		ec_option_nmi_password: nmi_password,
		ec_option_nmi_ship_from_zip: postal_code,
		ec_option_nmi_currency: nmi_currency,
		ec_option_nmi_processor_id: nmi_processor_id,
		ec_option_nmi_commodity_code: commodity_code,
		ec_option_cardinal_processor_id: cardinal_processor_id,
		ec_option_cardinal_merchant_id: cardinal_merchant_id,
		ec_option_cardinal_password: cardinal_password,
		ec_option_cardinal_currency: cardinal_currency,
		ec_option_cardinal_test_mode: cardinal_test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_payline_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var username = jQuery( document.getElementById( 'ec_option_payline_username' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_payline_password' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_payline_currency' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_payline',
		ec_option_payment_process_method: 'payline',
		ec_option_payline_username: username,
		ec_option_payline_password: password,
		ec_option_payline_currency: currency
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_paymentexpress_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var username = jQuery( document.getElementById( 'ec_option_payment_express_username' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_payment_express_password' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_payment_express_currency' ) ).val( );
	var developer_account = jQuery( document.getElementById( 'ec_option_payment_express_developer_account' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_paymentexpress',
		ec_option_payment_process_method: 'paymentexpress',
		ec_option_payment_express_username: username,
		ec_option_payment_express_password: password,
		ec_option_payment_express_currency: currency,
		ec_option_payment_express_developer_account: developer_account
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_paypal_pro_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var partner = jQuery( document.getElementById( 'ec_option_paypal_pro_partner' ) ).val( );
	var username = jQuery( document.getElementById( 'ec_option_paypal_pro_user' ) ).val( );
	var vendor = jQuery( document.getElementById( 'ec_option_paypal_pro_vendor' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_paypal_pro_password' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_paypal_pro_currency' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_paypal_pro_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_paypal_pro',
		ec_option_payment_process_method: 'paypal_pro',
		ec_option_paypal_pro_partner: partner,
		ec_option_paypal_pro_user: username,
		ec_option_paypal_pro_vendor: vendor,
		ec_option_paypal_pro_password: password,
		ec_option_paypal_pro_currency: currency,
		ec_option_paypal_pro_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_paypal_payments_pro_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var user = jQuery( document.getElementById( 'ec_option_paypal_payments_pro_user' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_paypal_payments_pro_password' ) ).val( );
	var signature = jQuery( document.getElementById( 'ec_option_paypal_payments_pro_signature' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_paypal_payments_pro_currency' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_paypal_payments_pro_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_paypal_payments_pro',
		ec_option_payment_process_method: 'paypal_payments_pro',
		ec_option_paypal_payments_pro_user: user,
		ec_option_paypal_payments_pro_password: password,
		ec_option_paypal_payments_pro_signature: signature,
		ec_option_paypal_payments_pro_currency: currency,
		ec_option_paypal_payments_pro_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_paypoint_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_paypoint_merchant_id' ) ).val( );
	var vpn_password = jQuery( document.getElementById( 'ec_option_paypoint_vpn_password' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_paypoint_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_paypoint',
		ec_option_payment_process_method: 'paypoint',
		ec_option_paypoint_merchant_id: merchant_id,
		ec_option_paypoint_vpn_password: vpn_password,
		ec_option_paypoint_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_realex_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_realex_merchant_id' ) ).val( );
	var secret = jQuery( document.getElementById( 'ec_option_realex_secret' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_realex_currency' ) ).val( );
	var secure = jQuery( document.getElementById( 'ec_option_realex_3dsecure' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_realex_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_realex',
		ec_option_payment_process_method: 'realex',
		ec_option_realex_merchant_id: merchant_id,
		ec_option_realex_secret: secret,
		ec_option_realex_currency: currency,
		ec_option_realex_3dsecure: secure,
		ec_option_realex_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_sagepay_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var vendor = jQuery( document.getElementById( 'ec_option_sagepay_vendor' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_sagepay_currency' ) ).val( );
	var simulator = jQuery( document.getElementById( 'ec_option_sagepay_simulator' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_sagepay_testmode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_sagepay',
		ec_option_payment_process_method: 'sagepay',
		ec_option_sagepay_vendor: vendor,
		ec_option_sagepay_currency: currency,
		ec_option_sagepay_simulator: simulator,
		ec_option_sagepay_testmode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_sagepayus_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var user_id = jQuery( document.getElementById( 'ec_option_sagepayus_mid' ) ).val( );
	var user_key = jQuery( document.getElementById( 'ec_option_sagepayus_mkey' ) ).val( );
	var application_id = jQuery( document.getElementById( 'ec_option_sagepayus_application_id' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_sagepayus',
		ec_option_payment_process_method: 'sagepayus',
		ec_option_sagepayus_mid: user_id,
		ec_option_sagepayus_mkey: user_key,
		ec_option_sagepayus_application_id: application_id
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_securenet_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_securenet_id' ) ).val( );
	var secure_key = jQuery( document.getElementById( 'ec_option_securenet_secure_key' ) ).val( );
	var sandbox_mode = jQuery( document.getElementById( 'ec_option_securenet_use_sandbox' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_securenet',
		ec_option_payment_process_method: 'securenet',
		ec_option_securenet_id: merchant_id,
		ec_option_securenet_secure_key: secure_key,
		ec_option_securenet_use_sandbox: sandbox_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_save_securepay_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var merchant_id = jQuery( document.getElementById( 'ec_option_securepay_merchant_id' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_option_securepay_password' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_securepay_currency' ) ).val( );
	var test_mode = jQuery( document.getElementById( 'ec_option_securepay_test_mode' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_securepay',
		ec_option_payment_process_method: 'securepay',
		ec_option_securepay_merchant_id: merchant_id,
		ec_option_securepay_password: password,
		ec_option_securepay_currency: currency,
		ec_option_securepay_test_mode: test_mode
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function ec_admin_copy_stripe_webhook( ){
	var copyText = document.getElementById( 'stripe_webhook_url' );
	copyText.select( );
	document.execCommand( 'Copy' );
	jQuery( document.getElementById( 'stripe_webhook_copied' ) ).fadeIn( 'slow' ).delay( 1500 ).fadeOut( 'slow' );
}

function stripe_live_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_stripe_connect_enable_live' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'use_stripe_connect' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_stripe_connect_use_sandbox' ) ).val( '0' );
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_sandbox' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_square_enable' ) ).attr( 'checked', false );

	}else{ 
		jQuery( document.getElementById( 'use_stripe_connect' ) ).val( '0' );
	}
	if( jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).length ){
		jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).val( '0' );
		toggle_live_gateways( );
	}
	ec_admin_save_stripe_connect_options( );
}

function stripe_sandbox_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_stripe_connect_enable_sandbox' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'use_stripe_connect' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_stripe_connect_use_sandbox' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_live' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_square_enable' ) ).attr( 'checked', false );

	}else{ 
		jQuery( document.getElementById( 'use_stripe_connect' ) ).val( '0' );
	}
	if( jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).length ){
		jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).val( '0' );
		toggle_live_gateways( );
	}
	ec_admin_save_stripe_connect_options( );
}

function stripe_connect_show_advanced( ){
	if( !jQuery( document.getElementById( 'ec_stripe_connect_options' ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_stripe_connect_options' ) ).show( );
		jQuery( document.getElementById( 'stripe_connect_advanced_link' ) ).html( 'Advanced Options &#9650;' );
	}else{
		jQuery( document.getElementById( 'ec_stripe_connect_options' ) ).hide( );
		jQuery( document.getElementById( 'stripe_connect_advanced_link' ) ).html( 'Advanced Options &#9660;' );
	}
	return false;
}

function ec_admin_update_stripe_connect_display( currency, country ) {
	jQuery( '.ec_admin_stripe_section, .ec_admin_stripe_settings_row' ).each( function() {
		if ( jQuery( this ).attr( 'data-currencies' ).split( ',' ).includes( currency ) && jQuery( this ).attr( 'data-countries' ).split( ',' ).includes( country ) ) {
			jQuery( this ).show();
		} else {
			jQuery( this ).hide();
			jQuery( this ).find( 'select' ).each( function() {
				jQuery( this ).val( '0' );
			} );
		}
		jQuery( this ).find( '.ec_status_error' ).hide();
		if ( jQuery( this ).attr( 'data-country-currency' ) ) {
			var country_to_currency = JSON.parse( jQuery( this ).attr( 'data-country-currency' ) );
			if ( ! country_to_currency[country] || ! country_to_currency[country].includes( currency ) ) {
				jQuery( this ).find( '.ec_status_error' ).show();
				jQuery( this ).find( 'select' ).each( function() {
					jQuery( this ).val( '0' );
				} );
			}
		}
	} );
}

function ec_admin_update_stripe_connect_option( this_ele ) {
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	var data = {
		action: 'ec_admin_ajax_save_stripe_connect_option',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: jQuery( this_ele ).val(),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_stripe_connect_options() {
	jQuery( document.getElementById( "ec_admin_stripe_display_loader" ) ).fadeIn( 'fast' );
	var currency = jQuery( document.getElementById( 'ec_option_stripe_currency' ) ).val( );
	var country = jQuery( document.getElementById( 'ec_option_stripe_company_country' ) ).val( );
	var payment_theme = jQuery( document.getElementById( 'ec_option_stripe_payment_theme' ) ).val( );
	var payment_layout = jQuery( document.getElementById( 'ec_option_stripe_payment_layout' ) ).val( );
	var address_autocomplete = jQuery( document.getElementById( 'ec_option_stripe_address_autocomplete' ) ).val( );

	ec_admin_update_stripe_connect_display( currency, country );

	var enable_affirm = jQuery( document.getElementById( 'ec_option_stripe_affirm' ) ).val( );
	var enable_afterpay = jQuery( document.getElementById( 'ec_option_stripe_afterpay' ) ).val( );
	var enable_klarna = jQuery( document.getElementById( 'ec_option_stripe_klarna' ) ).val( );

	var wallet_first = jQuery( document.getElementById( 'ec_option_stripe_disable_wallet_first' ) ).val( );
	var apple_pay = jQuery( document.getElementById( 'ec_option_stripe_enable_apple_pay' ) ).val( );
	var enable_alipay = jQuery( document.getElementById( 'ec_option_stripe_alipay' ) ).val( );
	var enable_grabpay = jQuery( document.getElementById( 'ec_option_stripe_alipay' ) ).val( );
	var enable_wechat = jQuery( document.getElementById( 'ec_option_stripe_wechat' ) ).val( );
	var enable_link = jQuery( document.getElementById( 'ec_option_stripe_link' ) ).val( );

	var enable_bancontact = jQuery( document.getElementById( 'ec_option_stripe_bancontact' ) ).val( );
	var enable_blik = jQuery( document.getElementById( 'ec_option_stripe_blik' ) ).val( );
	var enable_eps = jQuery( document.getElementById( 'ec_option_stripe_eps' ) ).val( );
	var enable_fpx = jQuery( document.getElementById( 'ec_option_stripe_fpx' ) ).val( );
	var enable_giropay = jQuery( document.getElementById( 'ec_option_stripe_giropay' ) ).val( );
	var enable_ideal = jQuery( document.getElementById( 'ec_option_stripe_enable_ideal' ) ).val( );
	var enable_p24 = jQuery( document.getElementById( 'ec_option_stripe_p24' ) ).val( );
	var enable_sofort = jQuery( document.getElementById( 'ec_option_stripe_sofort' ) ).val( );

	var enable_bacs = jQuery( document.getElementById( 'ec_option_stripe_bacs' ) ).val( );
	var enable_becs = jQuery( document.getElementById( 'ec_option_stripe_becs' ) ).val( );
	var enable_sepa = jQuery( document.getElementById( 'ec_option_stripe_sepa' ) ).val( );

	var enable_pix = jQuery( document.getElementById( 'ec_option_stripe_pix' ) ).val( );
	var enable_paynow = jQuery( document.getElementById( 'ec_option_stripe_paynow' ) ).val( );
	var enable_promptpay = jQuery( document.getElementById( 'ec_option_stripe_promptpay' ) ).val( );

	var enable_boleto = jQuery( document.getElementById( 'ec_option_stripe_boleto' ) ).val( );
	var enable_konbini = jQuery( document.getElementById( 'ec_option_stripe_konbini' ) ).val( );
	var enable_oxxo = jQuery( document.getElementById( 'ec_option_stripe_oxxo' ) ).val( );

	var payment_method = '';

	if ( '1' == jQuery( document.getElementById( 'ec_option_stripe_enable_apple_pay' ) ).val( ) ) {
		jQuery( document.getElementById( 'stripe_wallet_first' ) ).show( );
	} else {
		jQuery( document.getElementById( 'stripe_wallet_first' ) ).hide( );
	}

	if ( jQuery( document.getElementById( 'use_stripe_connect' ) ).val( ) == '1' ) {
		payment_method = 'stripe_connect';
	}

	var data = {
		action: 'ec_admin_ajax_save_stripe_connect',
		ec_option_payment_process_method: payment_method,
		ec_option_stripe_connect_use_sandbox: jQuery( document.getElementById( 'ec_option_stripe_connect_use_sandbox' ) ).val( ),
		ec_option_stripe_currency: currency,
		ec_option_stripe_company_country: country,
		ec_option_stripe_payment_theme: payment_theme,
		ec_option_stripe_payment_layout: payment_layout,
		ec_option_stripe_address_autocomplete: address_autocomplete,

		ec_option_stripe_affirm: enable_affirm,
		ec_option_stripe_afterpay: enable_afterpay,
		ec_option_stripe_klarna: enable_klarna,

		ec_option_stripe_enable_apple_pay: apple_pay,
		ec_option_stripe_disable_wallet_first: wallet_first,
		ec_option_stripe_alipay: enable_alipay,
		ec_option_stripe_grabpay: enable_grabpay,
		ec_option_stripe_wechat: enable_wechat,
		ec_option_stripe_link: enable_link,

		ec_option_stripe_bancontact: enable_bancontact,
		ec_option_stripe_blik: enable_blik,
		ec_option_stripe_eps: enable_eps,
		ec_option_stripe_fpx: enable_fpx,
		ec_option_stripe_giropay: enable_giropay,
		ec_option_stripe_enable_ideal: enable_ideal,
		ec_option_stripe_p24: enable_p24,
		ec_option_stripe_sofort: enable_sofort,

		ec_option_stripe_bacs: enable_bacs,
		ec_option_stripe_becs: enable_becs,
		ec_option_stripe_sepa: enable_sepa,

		ec_option_stripe_pix: enable_pix,
		ec_option_stripe_paynow: enable_paynow,
		ec_option_stripe_promptpay: enable_promptpay,

		ec_option_stripe_boleto: enable_boleto,
		ec_option_stripe_konbini: enable_konbini,
		ec_option_stripe_oxxo: enable_oxxo,

		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_stripe_display_loader' );
	} } );

	return false;
}

function ec_admin_save_stripe_options( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var public_api_key = jQuery( document.getElementById( 'ec_option_stripe_public_api_key' ) ).val( );	
	var api_key = jQuery( document.getElementById( 'ec_option_stripe_api_key' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_stripe_currency_v1' ) ).val( );
	var country = jQuery( document.getElementById( 'ec_option_stripe_company_country_v1' ) ).val( );
	var apple_pay = jQuery( document.getElementById( 'ec_option_stripe_enable_apple_pay_v1' ) ).val( );
	var wallet_first = jQuery( document.getElementById( 'ec_option_stripe_disable_wallet_first_v1' ) ).val( );
	var enable_ideal = 0;

	if ( '1' == jQuery( document.getElementById( 'ec_option_stripe_enable_apple_pay_v1' ) ).val( ) ) {
		jQuery( document.getElementById( 'stripe_wallet_first_v1' ) ).show( );
	} else {
		jQuery( document.getElementById( 'stripe_wallet_first_v1' ) ).hide( );
	}

	if( currency == 'EUR' ){
		jQuery( document.getElementById( 'stripe_use_ideal_v1' ) ).show( );
		enable_ideal = jQuery( document.getElementById( 'ec_option_stripe_enable_ideal_v1' ) ).val( );
	}else{
		jQuery( document.getElementById( 'stripe_use_ideal_v1' ) ).hide( );
	}

	var stripe_order_create_customer = 0;
	if( jQuery( document.getElementById( 'ec_option_stripe_order_create_customer' ) ).is( ':checked' ) )
		stripe_order_create_customer = 1;
	var data = {
		action: 'ec_admin_ajax_save_stripe',
		ec_option_payment_process_method: 'stripe',
		ec_option_stripe_public_api_key: public_api_key,
		ec_option_stripe_api_key: api_key,
		ec_option_stripe_currency: currency,
		ec_option_stripe_company_country: country,
		ec_option_stripe_enable_apple_pay: apple_pay,
		ec_option_stripe_disable_wallet_first: wallet_first,
		ec_option_stripe_enable_ideal: enable_ideal,
		ec_option_stripe_order_create_customer: stripe_order_create_customer
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function square_on_off( ){
	if( jQuery( document.getElementById( 'ec_option_square_enable' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'use_square' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_live' ) ).attr( 'checked', false );
		jQuery( document.getElementById( 'ec_option_stripe_connect_enable_sandbox' ) ).attr( 'checked', false );

	}else{ 
		jQuery( document.getElementById( 'use_square' ) ).val( '0' );
	}
	if( jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).length ){
		jQuery( document.getElementById( 'ec_option_payment_process_method' ) ).val( '0' );
		toggle_live_gateways( );
	}
	ec_admin_save_square_options( );
}

function square_sync_on_off() {
	jQuery( document.getElementById( "ec_admin_square_display_loader" ) ).fadeIn( 'fast' );
	var enable_square_sync = 0;
	if ( jQuery( document.getElementById( 'ec_option_square_auto_sync' ) ).is( ':checked' ) ) {
		enable_square_sync = 1;
	}
	var data = {
		action: 'ec_admin_ajax_save_square_sync',
		ec_option_square_auto_sync: enable_square_sync,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_square_display_loader' );
	} } );
}

function square_product_sync_on_off() {
	jQuery( document.getElementById( "ec_admin_square_display_loader" ) ).fadeIn( 'fast' );
	var enable_square_product_sync = 0;
	if ( jQuery( document.getElementById( 'ec_option_square_auto_product_sync' ) ).is( ':checked' ) ) {
		enable_square_product_sync = 1;
	}
	var data = {
		action: 'ec_admin_ajax_save_square_product_sync',
		ec_option_square_auto_product_sync: enable_square_product_sync,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_square_display_loader' );
	} } );
}

function ec_admin_save_square_options( ){
	jQuery( document.getElementById( "ec_admin_square_display_loader" ) ).fadeIn( 'fast' );

	var payment_method = '0';
	if( jQuery( document.getElementById( 'ec_option_square_enable' ) ).is( ':checked' ) || jQuery( document.getElementById( 'ec_option_square_enable_sandbox' ) ).is( ':checked' ) )
		payment_method = 'square';

	var location_id = jQuery( document.getElementById( 'ec_option_square_location_id' ) ).val( );
	var country_code = jQuery( '#ec_option_square_location_id > option:selected' ).attr( 'data-country' );
	var digital_wallets = jQuery( document.getElementById( 'ec_option_square_digital_wallet' ) ).val( );
	var merchant_name = jQuery( document.getElementById( 'ec_option_square_merchant_name' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_square_free',
		payment_method: payment_method,
		ec_option_square_location_id: location_id,
		ec_option_square_location_country: country_code,
		ec_option_square_digital_wallet: digital_wallets,
		ec_option_square_merchant_name: merchant_name,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_payment_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_square_display_loader' );
	} } );

	return false;
}

function ec_admin_save_square_options_pro( ){
	jQuery( document.getElementById( "ec_admin_live_gateway_display_loader" ) ).fadeIn( 'fast' );

	var app_id = jQuery( document.getElementById( 'ec_option_square_application_id' ) ).val( );
	var access_token = jQuery( document.getElementById( 'ec_option_square_access_token' ) ).val( );
	var location_id = jQuery( document.getElementById( 'ec_option_square_location_id' ) ).val( );
	var currency = jQuery( document.getElementById( 'ec_option_square_currency' ) ).val( );
	var country_code = jQuery( '#ec_option_square_location_id > option:selected' ).attr( 'data-country' );
	var digital_wallets = jQuery( document.getElementById( 'ec_option_square_digital_wallet' ) ).val( );
	var merchant_name = jQuery( document.getElementById( 'ec_option_square_merchant_name' ) ).val( );
	var data = {
		action: 'ec_admin_ajax_save_square_pro',
		ec_option_payment_process_method: 'square',
		ec_option_square_application_id: app_id,
		ec_option_square_access_token: access_token,
		ec_option_square_location_id: location_id,
		ec_option_square_currency: currency,
		ec_option_square_location_country: country_code,
		ec_option_square_digital_wallet: digital_wallets,
		ec_option_square_merchant_name: merchant_name
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_live_gateway_display_loader' );
	} } );

	return false;
}

function square_show_advanced( ){
	if( !jQuery( document.getElementById( 'ec_square_options' ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_square_options' ) ).show( );
		jQuery( document.getElementById( 'square_advanced_link' ) ).html( 'Advanced Options &#9650;' );
	}else{
		jQuery( document.getElementById( 'ec_square_options' ) ).hide( );
		jQuery( document.getElementById( 'square_advanced_link' ) ).html( 'Advanced Options &#9660;' );
	}
	return false;
}

function ec_admin_save_accepted_cards( ){
	jQuery( document.getElementById( "ec_admin_accepted_cards_display_loader" ) ).fadeIn( 'fast' );

	var visa = 0;
	var delta = 0;
	var electron = 0;
	var discover = 0;
	var mastercard = 0;
	var mastercard_debit = 0;
	var american_express = 0;
	var jcb = 0;
	var diners = 0;
	var laser = 0;
	var maestro = 0; 

	if( jQuery( document.getElementById( 'ec_option_use_visa' ) ).is( ':checked' ) )
		visa = 1;
	if( jQuery( document.getElementById( 'ec_option_use_delta' ) ).is( ':checked' ) )
		delta = 1;
	if( jQuery( document.getElementById( 'ec_option_use_uke' ) ).is( ':checked' ) )
		electron = 1;
	if( jQuery( document.getElementById( 'ec_option_use_discover' ) ).is( ':checked' ) )
		discover = 1;
	if( jQuery( document.getElementById( 'ec_option_use_mastercard' ) ).is( ':checked' ) )
		mastercard = 1;
	if( jQuery( document.getElementById( 'ec_option_use_mcdebit' ) ).is( ':checked' ) )
		mastercard_debit = 1;
	if( jQuery( document.getElementById( 'ec_option_use_amex' ) ).is( ':checked' ) )
		american_express = 1;
	if( jQuery( document.getElementById( 'ec_option_use_jcb' ) ).is( ':checked' ) )
		jcb = 1;
	if( jQuery( document.getElementById( 'ec_option_use_diners' ) ).is( ':checked' ) )
		diners = 1;
	if( jQuery( document.getElementById( 'ec_option_use_laser' ) ).is( ':checked' ) )
		laser = 1;
	if( jQuery( document.getElementById( 'ec_option_use_maestro' ) ).is( ':checked' ) )
		maestro = 1;

	var data = {
		action: 'ec_admin_ajax_save_accepted_cards',
		ec_option_use_visa: visa,
		ec_option_use_delta: delta,
		ec_option_use_uke: electron,
		ec_option_use_discover: discover,
		ec_option_use_mastercard: mastercard,
		ec_option_use_mcdebit: mastercard_debit,
		ec_option_use_amex: american_express,
		ec_option_use_jcb: jcb,
		ec_option_use_diners: diners,
		ec_option_use_laser: laser,
		ec_option_use_maestro: maestro
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_accepted_cards_display_loader' );
	} } );

	return false;
}