jQuery( document ).ready( function( ){
    jQuery( '#wp_easycart_country_ship_table input[type="checkbox"]' ).on( 'change', function( ){
        var selected_country = jQuery( this ).parent( ).parent( ).find( '.wp-easycart-editable-table-read-only' ).html( );
        var is_enabled = false;
        if( jQuery( this ).is( ':checked' ) ){
            is_enabled = true;
        }
        jQuery( '#wp_easycart_state_ship_table input[type="checkbox"]' ).each( function( ){
            var country = jQuery( this ).parent( ).parent( ).find( 'td:nth-child(2) > div' ).html( );
            if( selected_country == country ){
                jQuery( this ).prop( "checked", is_enabled );
            }
        } );
    } );
} );

function ec_admin_add_shipping_zone( data, response ){
    jQuery( '.wp-easycart-zone-list' ).append( '<option value="' + response + '">' + data.zone_name + '</option>' );
}

function ec_admin_delete_shipping_zone( data, zone_id ){
    jQuery( '.wp-easycart-zone-list > option[value="' + zone_id + '"]:selected' ).parent( ).parent( ).parent( ).remove( );
    jQuery( '.wp-easycart-zone-list > option[value="' + zone_id + '"]' ).remove( );
}

function ec_admin_save_shipping_options( this_ele ){
    jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );

	if ( jQuery( document.getElementById( 'ec_option_packing_slip_show_billing' ) ).is( ':checked' ) || jQuery( document.getElementById( 'ec_option_packing_slip_show_shipping' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_packing_slip_show_phone_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_packing_slip_show_phone_row' ) ).hide();
	}

	if ( jQuery( document.getElementById( 'ec_option_packing_slip_show_pricing' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_packing_slip_show_subtotal_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_tiptotal_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_shippingtotal_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_discounttotal_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_taxtotal_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_grandtotal_row' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_option_packing_slip_show_subtotal_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_tiptotal_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_shippingtotal_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_discounttotal_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_taxtotal_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_packing_slip_show_grandtotal_row' ) ).hide();
	}
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) ) {
		val = 1;
	}
	
	ec_admin_enable_disable_shipping();
		
	var data = {
		action: 'ec_admin_ajax_save_shipping_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_enable_disable_shipping( ) {
	if ( jQuery( document.getElementById( 'ec_option_use_shipping' ) ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'ec_option_hide_shipping_rate_page1_row' ) ).show();
		jQuery( document.getElementById( 'ec_admin_shipping_handling_rate_row' ) ).show();
		jQuery( document.getElementById( 'ec_admin_shipping_expedite_rate_row' ) ).show();
		jQuery( document.getElementById( 'ec_admin_metric_unit_display_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_add_local_pickup_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_collect_tax_on_shipping_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_show_delivery_days_live_shipping_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_collect_shipping_for_subscriptions_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_ship_items_seperately_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_static_ship_items_seperately_row' ) ).show();
		jQuery( document.getElementById( 'ec_admin_save_shipping_options_row' ) ).show();
		jQuery( document.getElementById( 'ec_option_live_override_always_row' ) ).show();
		jQuery( '.wpeasycart_shipping_settings_section_disabled' ).removeClass( 'wpeasycart_shipping_settings_section_disabled' ).addClass( 'wpeasycart_shipping_settings_section_enabled' );
		jQuery( '.wpeasycart_shipping_settings_section_disabled_enabled' ).removeClass( 'wpeasycart_shipping_settings_section_disabled_enabled' ).addClass( 'wpeasycart_shipping_settings_section_disabled_disabled' );
	} else {
		jQuery( document.getElementById( 'ec_option_hide_shipping_rate_page1_row' ) ).hide();
		jQuery( document.getElementById( 'ec_admin_shipping_handling_rate_row' ) ).hide();
		jQuery( document.getElementById( 'ec_admin_shipping_expedite_rate_row' ) ).hide();
		jQuery( document.getElementById( 'ec_admin_metric_unit_display_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_add_local_pickup_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_collect_tax_on_shipping_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_show_delivery_days_live_shipping_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_collect_shipping_for_subscriptions_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_ship_items_seperately_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_static_ship_items_seperately_row' ) ).hide();
		jQuery( document.getElementById( 'ec_admin_save_shipping_options_row' ) ).hide();
		jQuery( document.getElementById( 'ec_option_live_override_always_row' ) ).hide();
		jQuery( '.wpeasycart_shipping_settings_section_enabled' ).removeClass( 'wpeasycart_shipping_settings_section_enabled' ).addClass( 'wpeasycart_shipping_settings_section_disabled' );
		jQuery( '.wpeasycart_shipping_settings_section_disabled_disabled' ).removeClass( 'wpeasycart_shipping_settings_section_disabled_disabled' ).addClass( 'wpeasycart_shipping_settings_section_disabled_enabled' );
	}
}

function ec_admin_save_shipping_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_shipping_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}