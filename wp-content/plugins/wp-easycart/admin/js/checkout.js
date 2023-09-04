function ec_admin_save_cart_settings_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );

	var val = 0;

	if( jQuery( this_ele ).is( ':checked' ) ) {
		val = 1;
	}

	if ( jQuery( document.getElementById( 'ec_option_onepage_checkout_row' ) ).length ) {
		if ( jQuery( document.getElementById( 'ec_option_onepage_checkout' ) ).is( ':checked' ) ) {
			jQuery( document.getElementById( 'ec_option_onepage_checkout_tabbed_row' ) ).show();
			jQuery( document.getElementById( 'ec_option_onepage_checkout_cart_first_row' ) ).show();
		} else {
			jQuery( document.getElementById( 'ec_option_onepage_checkout_tabbed_row' ) ).hide();
			jQuery( document.getElementById( 'ec_option_onepage_checkout_cart_first_row' ) ).hide();
		}
	}

	var data = {
		action: 'ec_admin_ajax_save_cart_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
	}

	jQuery.ajax( { url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ) {
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );

	return false;
}

function ec_admin_save_checkout_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_cart_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function wpeasycart_admin_update_tips_view( ){
    if( jQuery( document.getElementById( 'ec_option_enable_tips' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_tips_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_tips_row' ) ).hide( );
    }
}

function wpeasycart_admin_update_estimate_shipping_view( ){
	if( jQuery( document.getElementById( 'ec_option_use_estimate_shipping' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_estimate_shipping_zip_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_estimate_shipping_country_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_estimate_shipping_zip_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_estimate_shipping_country_row' ) ).hide( );
    }
}

function wpeasycart_admin_update_gift_card_view( ){
	if( jQuery( document.getElementById( 'ec_option_show_giftcards' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_gift_card_shipping_allowed_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_gift_card_shipping_allowed_row' ) ).hide( );
    }
}

function wpeasycart_admin_update_terms_link_view( ){
	if( jQuery( document.getElementById( 'ec_option_require_terms_agreement' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_terms_link_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_privacy_link_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_terms_link_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_privacy_link_row' ) ).hide( );
    }
}

function wpeasycart_admin_update_country_view( ){
	if( jQuery( document.getElementById( 'ec_option_use_country_dropdown' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_default_country_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_default_country_row' ) ).hide( );
    }
}

function wpeasycart_admin_update_low_stock_trigger_view( ){
    if( jQuery( document.getElementById( 'ec_option_send_low_stock_emails' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_low_stock_trigger_total_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_low_stock_trigger_total_row' ) ).hide( );
    }
}

jQuery( document ).ready( function( ){
    jQuery( document ).on( 'change', '.wpeasycart_orderstatus_id_edit', function( ){
        wpeasycart_save_orderstatus( jQuery( this ).val( ), jQuery( this ).attr( 'data-id' ) );
    } );
    jQuery( document ).on( 'click', '.wpeasycart_orderstatus_approved_edit', function( ){
        var is_approved = 0;
        if( jQuery( this ).is( ':checked' ) ){
            is_approved = 1;
        }
        wpeasycart_save_orderstatus_approved( is_approved, jQuery( this ).attr( 'data-id' ) );
    } );
} );

function wpeasycart_add_orderstatus( ){
    jQuery( document.getElementById( "ec_admin_order_statuses_settings_loader" ) ).fadeIn( 'fast' );
	
    var is_approved = 0;
    if( jQuery( document.getElementById( 'wpeasycart_orderstatus_approved_add' ) ).is( ':checked' ) )
		is_approved = 1;
    
    var data = {
		action: 'ec_admin_ajax_add_orderstatus',
		order_status: jQuery( document.getElementById( 'wpeasycart_orderstatus_add' ) ).val( ),
        is_approved: is_approved,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
    };
    
    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_statuses_settings_loader' );
        var new_row = '<tr id="wpeasycart_orderstatus_row_' + data + '">';
            new_row += '<td><input type="text" style="margin-top:0px;" id="wpeasycart_orderstatus_status_' + data + '" class="wpeasycart_orderstatus_id_edit" data-id="' + data + '" value="' + jQuery( document.getElementById( 'wpeasycart_orderstatus_add' ) ).val( ) + '" /></td>';
            new_row += '<td style="text-align:center;"><input type="checkbox" class="wpeasycart_orderstatus_approved_edit" id="wpeasycart_orderstatus_approved_' + data + '" data-id="' + data + '" value="1"';
            if( is_approved ){
                new_row += ' checked="checked"';
            }
            new_row += '/></td>'
            new_row += '<td style="text-align:right;"><input type="button" class="ec_admin_order_edit_button" onclick="wpeasycart_archieve_orderstatus( \'' + data + '\' );" value="' + wp_easycart_checkout_language['delete'] + '" /></td>';
        new_row += '</tr>';
        jQuery( document.getElementById( 'wpeasycart_orderstatus_row_add' ) ).before( new_row );
        jQuery( document.getElementById( 'wpeasycart_orderstatus_add' ) ).val( '' );
        jQuery( document.getElementById( 'wpeasycart_orderstatus_approved_add' ) ).attr( 'checked', false );
	} } );
	
	return false;
}

function wpeasycart_save_orderstatus( title, id ){
    jQuery( document.getElementById( "ec_admin_order_statuses_settings_loader" ) ).fadeIn( 'fast' );
	
    var data = {
		action: 'ec_admin_ajax_save_orderstatus',
        order_status: title,
		status_id: id,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
    };
    
    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_statuses_settings_loader' );
	} } );
	
	return false;
}

function wpeasycart_save_orderstatus_approved( is_approved, id ){
    jQuery( document.getElementById( "ec_admin_order_statuses_settings_loader" ) ).fadeIn( 'fast' );
	
    var data = {
		action: 'ec_admin_ajax_save_orderstatus_approved',
        is_approved: is_approved,
		status_id: id,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
    };
    
    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_statuses_settings_loader' );
	} } );
	
	return false;
}

function wpeasycart_archieve_orderstatus( id ){
    jQuery( document.getElementById( "ec_admin_order_statuses_settings_loader" ) ).fadeIn( 'fast' );
	
    var data = {
		action: 'ec_admin_ajax_archieve_orderstatus',
		status_id: id,
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_checkout_settings_nonce', 'text' )
    };
    
    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_order_statuses_settings_loader' );
        jQuery( document.getElementById( "wpeasycart_orderstatus_row_" + id ) ).remove( );
	} } );
	
	return false;
}