function ec_admin_show_password_update( ){
	if( jQuery( document.getElementById( 'update_password' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_update_user_password' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_update_user_password' ) ).hide( );
	}
}
function copy_to_shipping( ){
	document.getElementById( 'shipping_first_name' ).value = document.getElementById( 'billing_first_name' ).value;
	document.getElementById( 'shipping_last_name' ).value = document.getElementById( 'billing_last_name' ).value;
	document.getElementById( 'shipping_company_name' ).value = document.getElementById( 'billing_company_name' ).value;
	document.getElementById( 'shipping_address_line_1' ).value = document.getElementById( 'billing_address_line_1' ).value;
	document.getElementById( 'shipping_address_line_2' ).value = document.getElementById( 'billing_address_line_2' ).value;
	document.getElementById( 'shipping_city' ).value = document.getElementById( 'billing_city' ).value;
	document.getElementById( 'shipping_state' ).value = document.getElementById( 'billing_state' ).value;
	document.getElementById( 'shipping_zip' ).value = document.getElementById( 'billing_zip' ).value;
	document.getElementById( 'shipping_country' ).value = document.getElementById( 'billing_country' ).value;
	document.getElementById( 'shipping_phone' ).value = document.getElementById( 'billing_phone' ).value;
}
function ec_admin_check_email_exists( field ){
	var email=document.getElementById( field ).value;
	var data = {
		action: 'ec_admin_check_email_exists',
		email: email,
		user_id: jQuery( 'input[name="user_id"]' ).val( )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(response){ 
	   if(response == "OK")	
	   {
		   jQuery(document.getElementById('email_validation')).html( wp_easycart_users_language['enter-email-address'] );
		   document.getElementById( 'email_validation' ).style.display = 'none'; 
		   jQuery(document.getElementById('email')).removeClass( 'ec_admin_field_error' ); 
		   return true;	
	   } else {
		   jQuery(document.getElementById('email_validation')).html(response);
		   document.getElementById( 'email_validation' ).style.display = 'block';
		   jQuery(document.getElementById('email')).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		   return false;	
	   }
	} } );
	
	return false;
}

function ec_admin_toggle_remote_access( ){
	if( jQuery( document.getElementById( 'admin_access' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_orders_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_downloads_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscriptions_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_products_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_options_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_menus_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturers_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_categories_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_reviews_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_plans_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_users_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_giftcards_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_newsletter_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_news_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_coupons_access' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_promotions_access' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_orders_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_downloads_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscriptions_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_products_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_options_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_menus_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_manufacturers_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_categories_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_reviews_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_plans_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_users_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_giftcards_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_newsletter_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_news_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_coupons_access' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_promotions_access' ) ).hide( );
	}
}

/*importer for users */
function ec_admin_start_importer( file, status_field, nonce ){
	jQuery( document.getElementById( status_field ) ).text( wp_easycart_users_language['processing-import'] );
	jQuery( document.getElementById( status_field ) ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_import_users',
		import_file_url: ec_admin_get_value( file, 'text' ),
		wp_easycart_nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		if( data != 'success' ){
			jQuery( document.getElementById( status_field ) ).text( data );
		}else{
			jQuery( document.getElementById( status_field ) ).text( wp_easycart_users_language['import-completed'] );
		}
	} } );
	
	return false;
}