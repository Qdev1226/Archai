function ec_admin_save_account_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_account_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_account_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_account_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_account_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_account_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function wpeasycart_admin_update_account_view( ){
    if( jQuery( document.getElementById( 'ec_option_enable_recaptcha' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_option_enable_recaptcha_help' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_site_key_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_secret_key_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_cart_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_option_enable_recaptcha_help' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_site_key_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_secret_key_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_recaptcha_cart_row' ) ).hide( );
    }
}