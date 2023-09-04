function ec_admin_save_email_option( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_email_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_email_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_email_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_email_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_email_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_email_language_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );
	
	var data = {
		action: 'ec_admin_ajax_save_email_language_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		file_name: jQuery( document.getElementById( 'wp_easycart_admin_file_name' ) ).val( ),
		key_section: jQuery( document.getElementById( 'wp_easycart_admin_key_section' ) ).val( ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_email_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function wpeasycart_admin_update_email_view( ){
    jQuery( document.getElementById( 'ec_admin_ec_option_order_use_smtp_row' ) ).hide( );
    jQuery( document.getElementById( 'ec_admin_ec_option_password_use_smtp_row' ) ).hide( );

	jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_encryption_type_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_host_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_port_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_username_display_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_password_display_row' ) ).hide( );
	
	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_encryption_type_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_host_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_port_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_username_row' ) ).hide( );
	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_password_row' ) ).hide( );
		
	if( !jQuery( document.getElementById( 'ec_option_use_wp_mail' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_ec_option_order_use_smtp_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_password_use_smtp_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_option_order_use_smtp' ) ).is( ':checked' ) ){
			jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_encryption_type_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_host_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_port_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_username_display_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_order_from_smtp_password_display_row' ) ).show( );
		}
		
		if( jQuery( document.getElementById( 'ec_option_password_use_smtp' ) ).is( ':checked' ) ){
			jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_encryption_type_row' ) ).show( );
        	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_host_row' ) ).show( );
        	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_port_row' ) ).show( );
        	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_username_row' ) ).show( );
        	jQuery( document.getElementById( 'ec_admin_ec_option_password_from_smtp_password_row' ) ).show( );
		}
		
    }
}

function ec_admin_resend_email_order( ){
	jQuery( document.getElementById( "ec_admin_order_receipt_loader" ) ).fadeIn( 'fast' );

	var val = jQuery( document.getElementById( 'ec_order_id' ) ).val( );

	var data = {
		action: 'ec_admin_ajax_send_test_email',
		ec_order_id: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_email_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_receipt_loader' );
		jQuery( document.getElementById( 'ec_order_id' ) ).val( '' );
	} } );
	
	return false;
}