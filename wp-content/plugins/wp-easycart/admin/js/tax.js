/* Global Tax Rate */
function ec_admin_update_global_tax_display( ){
	jQuery( '#ec_option_use_global_tax' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	if( jQuery( document.getElementById( 'ec_option_use_global_tax' ) ).is(':checked') ){
		jQuery( document.getElementById( 'ec_global_tax_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_global_tax_row' ) ).hide( );
	}
}

function ec_admin_update_global_tax_rate( ){
	jQuery( '#ec_global_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( '#ec_global_tax_rate' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
		
	var data = {
		action: 'ec_admin_ajax_update_global_tax_rate',
		ec_global_taxrate_id: ec_admin_get_value( 'ec_global_taxrate_id', 'text' ),
		ec_option_use_global_tax: ec_admin_get_value( 'ec_option_use_global_tax', 'checkbox' ),
		ec_global_tax_rate: ec_admin_get_value( 'ec_global_tax_rate', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( '#ec_option_use_global_tax' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( '#ec_option_use_global_tax' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		
		jQuery( '#ec_global_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( '#ec_global_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( '#ec_global_tax_rate' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

/* Duty Tax Rate */
function ec_admin_update_duty_tax_display( ){
	jQuery( '#ec_option_use_duty_tax' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	if( jQuery( document.getElementById( 'ec_option_use_duty_tax' ) ).is(':checked') ){
		jQuery( document.getElementById( 'ec_duty_tax_country_row' ) ).show( );
		jQuery( document.getElementById( 'ec_duty_tax_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_duty_tax_country_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_duty_tax_row' ) ).hide( );
	}
}

function ec_admin_update_duty_tax_rate_1( ){
	ec_admin_update_duty_tax_rate( 1 );
}

function ec_admin_update_duty_tax_rate_2( ){
	ec_admin_update_duty_tax_rate( 2 );
}

function ec_admin_update_duty_tax_rate_3( ){
	ec_admin_update_duty_tax_rate( 3 );
}

function ec_admin_update_duty_tax_rate( update_version ){
	if( update_version == 2 ){
		jQuery( '#ec_duty_exempt_country_code' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
		jQuery( '#ec_duty_exempt_country_code' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
	}
	
	if( update_version == 3 ){
		jQuery( '#ec_duty_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
		jQuery( '#ec_duty_tax_rate' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
	}
	
	var data = {
		action: 'ec_admin_ajax_update_duty_tax_rate',
		ec_duty_taxrate_id: ec_admin_get_value( 'ec_duty_taxrate_id', 'text' ),
		ec_option_use_duty_tax: ec_admin_get_value( 'ec_option_use_duty_tax', 'checkbox' ),
		ec_duty_exempt_country_code: ec_admin_get_value( 'ec_duty_exempt_country_code', 'select' ),
		ec_duty_tax_rate: ec_admin_get_value( 'ec_duty_tax_rate', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		if( update_version == 1 ){
			jQuery( '#ec_option_use_duty_tax' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
			jQuery( '#ec_option_use_duty_tax' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		}
		if( update_version == 2 ){
			jQuery( '#ec_duty_exempt_country_code' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
			jQuery( '#ec_duty_exempt_country_code' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
			jQuery( '#ec_duty_exempt_country_code' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
		}
		if( update_version == 3 ){
			jQuery( '#ec_duty_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
			jQuery( '#ec_duty_tax_rate' ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
			jQuery( '#ec_duty_tax_rate' ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
		}
	} } );
	
	return false;
}

/* Vat Tax Rate */
function ec_admin_update_vat_tax_display( ){
	if( jQuery( document.getElementById( 'ec_option_use_vat_tax' ) ).is( ':checked' ) ){
		jQuery( '#ec_vat_row1, #ec_vat_row2, #ec_vat_row3, #ec_vat_row4, #ec_vat_row5, #ec_vat_row6, #ec_vat_row7' ).show( );
		if( jQuery( document.getElementById( 'ec_vat_by_country' ) ).is( ':checked' ) ){
			jQuery( '#ec_admin_vat_country_rates' ).show( );
		}else{
			jQuery( '#ec_admin_vat_country_rates' ).hide( );
		}
		
		if( jQuery( document.getElementById( 'ec_option_validate_vat_registration_number' ) ).is( ':checked' ) ){
			jQuery( '#ec_vat_row8' ).show( );
		}else{
			jQuery( '#ec_vat_row8' ).hide( );
		}
		
	}else{
		jQuery( '#ec_vat_row1, #ec_vat_row2, #ec_vat_row3, #ec_vat_row4, #ec_vat_row5, #ec_vat_row6, #ec_vat_row7, #ec_vat_row8, #ec_admin_vat_country_rates' ).hide( );
		
	}
}

function ec_admin_save_vat_type( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var vat_type = '0';
	if( jQuery( document.getElementById( 'ec_option_use_vat_tax' ) ).is( ':checked' ) && jQuery( document.getElementById( 'ec_vat_by_country' ) ).is( ':checked' ) ){
		vat_type = 'tax_by_vat';
	}else if( jQuery( document.getElementById( 'ec_option_use_vat_tax' ) ).is( ':checked' ) ){
		vat_type = 'tax_by_single_vat';
	}
	
	var data = {
		action: 'ec_admin_ajax_save_vat_tax_settings',
		update_var: 'vat_type',
		val: vat_type,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
}

function ec_admin_save_vat_tax_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_vat_tax_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_vat_tax_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_vat_tax_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

/* Canada Tax */
function ec_admin_update_canada_tax_display( this_ele ){
	if( jQuery( document.getElementById( 'ec_option_enable_easy_canada_tax' ) ).is(':checked') ){
		jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( 'input[type="checkbox"]' ).prop( 'checked', true );
        jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( '.wp_easycart_admin_no_padding' ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( 'input[type="checkbox"]' ).prop( 'checked', false );
        jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( '.wp_easycart_admin_no_padding' ).hide( );
	}
    
    jQuery( document.getElementById( 'ec_option_enable_easy_canada_tax' ) ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );

	var data = {
		action: 'ec_admin_ajax_update_canada_country_tax_rate',
		ec_option_enable_easy_canada_tax: ec_admin_get_value( 'ec_option_enable_easy_canada_tax', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}

    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
        ec_admin_update_canada_tax_rate( jQuery( document.getElementById( 'ec_option_enable_easy_canada_tax' ) ) );
    } } );
    
}

/* Canada Tax */
function ec_admin_update_canada_tax_display_item( this_ele, province, user_role, type ){
    ec_admin_update_province_canada_tax_display( province, user_role, type );
    jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
    setTimeout( function( ){
        jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
        jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
        jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
    }, 700 );
}

function ec_admin_update_province_canada_tax_display( province, user_role, type ){
    
    if( type == 'enable' ){
        if( jQuery( document.getElementById( 'ec_option_collect_' + province + '_tax_' + user_role ) ).is(':checked') ){
            jQuery( document.getElementById( 'ec_admin_canada_tax_row_' + province + "_" + user_role ) ).show( );
        }else{
            jQuery( document.getElementById( 'ec_admin_canada_tax_row_' + province + "_" + user_role ) ).hide( );
        }
        
    }
    
    ec_admin_update_canada_tax_rate( jQuery( document.getElementById( 'ec_canada_tax_' + province + '_' + user_role ) ) );
}

function ec_admin_update_canada_tax_rate( loader_ele ){
	loader_ele.parent( ).find( '.wp_easycart_toggle_saving' ).show( );

	var canada_tax_vals = {};
	
	jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( 'input[type="checkbox"]:checked' ).each( function( index ){
		if( jQuery( this ).is(':checked') ){
			canada_tax_vals[jQuery( this ).attr( 'name' )] = 1;
		}
	} );
	
	jQuery( document.getElementById( 'ec_admin_use_canada_tax_section' ) ).find( 'input[type="text"]' ).each( function( index ){
		var tax_amount = jQuery( this ).val( );
		if( tax_amount >= 1 )
			tax_amount = tax_amount / 100;
		canada_tax_vals[jQuery( this ).attr( 'name' )] = tax_amount;
	} );
    
    var data = {
		action: 'ec_admin_ajax_update_canada_country_tax_rate',
		ec_option_enable_easy_canada_tax: ec_admin_get_value( 'ec_option_enable_easy_canada_tax', 'checkbox' ),
        ec_canada_tax: canada_tax_vals,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	};
    
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
        loader_ele.parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
        loader_ele.parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

/* Tax Cloud */
function ec_admin_save_tax_cloud_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_tax_cloud_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

/* TaxJar */
function ec_admin_save_tax_jar_settings_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) ) {
		val = 1;
	}
	
	if ( jQuery( document.getElementById( 'ec_option_tax_jar_enable' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_tax_jar_sandbox_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_enable_address_verification_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_address_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_city_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_state_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_zip_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_tax_jar_country_row' ) ).show();
		if ( ! jQuery( document.getElementById('ec_option_tax_jar_sandbox' ) ).is( ':checked' ) ) {
			jQuery( document.getElementById( 'ec_option_tax_jar_live_token_row' ) ).show();
			jQuery( document.getElementById( 'ec_option_tax_jar_sandbox_token_row' ) ).hide();
		} else {
			jQuery( document.getElementById( 'ec_option_tax_jar_live_token_row' ) ).hide();
			jQuery( document.getElementById( 'ec_option_tax_jar_sandbox_token_row' ) ).show();
		}
	} else {
		jQuery( document.getElementById( 'ec_option_tax_jar_sandbox_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_live_token_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_sandbox_token_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_enable_address_verification_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_address_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_city_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_state_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_zip_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_tax_jar_country_row' ) ).hide();
	}
	
	var data = {
		action: 'ec_admin_ajax_save_tax_jar_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_tax_jar_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_tax_jar_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_tax_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}
