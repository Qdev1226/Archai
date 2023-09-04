function ec_admin_save_initial_setup_options( ){
    // Adding Later
}

function ec_admin_save_initial_setup_text_setting( ){
    // Adding Later
}

function wpeasycart_admin_update_goals_view( ){
	if( jQuery( document.getElementById( 'ec_option_admin_display_sales_goal' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_sales_goal_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_sales_goal_row' ) ).hide( );
    }
}

function ec_admin_save_storepage_setup( ){
		
	jQuery( document.getElementById( "ec_admin_storepage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_storepage',
		ec_option_storepage: ec_admin_get_value( 'ec_option_storepage', 'select' ),
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_storepage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_storepage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_create_storepage( ){
	
	jQuery( document.getElementById( "ec_admin_storepage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_create_storepage',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_storepage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		// Update Store DD Box
		var opt = document.createElement( 'option' );
		opt.value = data;
		opt.innerHTML = wp_easycart_initial_setup_language['store'];
		document.getElementById( 'ec_option_storepage' ).appendChild( opt );
		document.getElementById( 'ec_option_storepage' ).value = data;
		ec_admin_hide_loader( 'ec_admin_storepage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_save_cartpage_setup( ){
		
	jQuery( document.getElementById( "ec_admin_cartpage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_cartpage',
		ec_option_cartpage: ec_admin_get_value( 'ec_option_cartpage', 'select' ),
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_cartpage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_cartpage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_create_cartpage( ){
	
	jQuery( document.getElementById( "ec_admin_cartpage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_create_cartpage',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_cartpage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		// Update Cart DD Box
		var opt = document.createElement( 'option' );
		opt.value = data;
		opt.innerHTML = wp_easycart_initial_setup_language['cart'];
		document.getElementById( 'ec_option_cartpage' ).appendChild( opt );
		document.getElementById( 'ec_option_cartpage' ).value = data;
		ec_admin_hide_loader( 'ec_admin_cartpage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_save_accountpage_setup( ){
		
	jQuery( document.getElementById( "ec_admin_accountpage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_accountpage',
		ec_option_accountpage: ec_admin_get_value( 'ec_option_accountpage', 'select' ),
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_accountpage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_accountpage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_create_accountpage( ){
	
	jQuery( document.getElementById( "ec_admin_accountpage_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_create_accountpage',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_accountpage_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		// Update Account DD Box
		var opt = document.createElement( 'option' );
		opt.value = data;
		opt.innerHTML = wp_easycart_initial_setup_language['account'];
		document.getElementById( 'ec_option_accountpage' ).appendChild( opt );
		document.getElementById( 'ec_option_accountpage' ).value = data;
		ec_admin_hide_loader( 'ec_admin_accountpage_loader' );
	} } );
	
	return false;
	
}

function ec_admin_save_goals_setup( ){
		
	jQuery( document.getElementById( "ec_admin_goal_setup" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_goals_setup',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_goals_settings_nonce', 'text' ),
		ec_option_admin_display_sales_goal: ec_admin_get_value( 'ec_option_admin_display_sales_goal', 'checkbox' ),
		ec_option_admin_sales_goal: ec_admin_get_value( 'ec_option_admin_sales_goal', 'text' ),
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_goal_setup' );
	} } );
	
	return false;
	
}

function ec_admin_save_currency_options( ){
	
	jQuery( document.getElementById( "ec_admin_currency_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_currency_options',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_currency_settings_nonce', 'text' ),
		ec_option_base_currency: ec_admin_get_value( 'ec_option_base_currency', 'text' ),
		ec_option_show_currency_code: ec_admin_get_value( 'ec_option_show_currency_code', 'checkbox' ),
		ec_option_currency: ec_admin_get_value( 'ec_option_currency', 'text' ),
		ec_option_currency_symbol_location: ec_admin_get_value( 'ec_option_currency_symbol_location', 'select' ),
		ec_option_currency_negative_location: ec_admin_get_value( 'ec_option_currency_negative_location', 'select' ),
		ec_option_currency_decimal_symbol: ec_admin_get_value( 'ec_option_currency_decimal_symbol', 'text' ),
		ec_option_currency_decimal_places: ec_admin_get_value( 'ec_option_currency_decimal_places', 'text' ),
		ec_option_currency_thousands_seperator: ec_admin_get_value( 'ec_option_currency_thousands_seperator', 'text' ),
		ec_option_exchange_rates: ec_admin_get_value( 'ec_option_exchange_rates', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_currency_loader' );
	} } );
	
	return false;
	
}