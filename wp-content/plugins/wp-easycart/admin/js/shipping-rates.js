function toggle_shipping_method( save_new ){
	var current_selection = jQuery( document.getElementById( 'ec_option_shipping_method' ) ).val( );
	jQuery( '.ec_admin_settings_shipping_section' ).hide( );
	jQuery( document.getElementById( current_selection ) ).show( );
	if( save_new )
		ec_admin_save_shipping_selection( );
}

function ec_admin_save_shipping_selection( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_update_shipping_select',
		ec_option_shipping_method: ec_admin_get_value( 'ec_option_shipping_method', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Price Trigger */
function ec_admin_add_new_shipping_price_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_price_trigger',
		ec_admin_new_price_trigger: ec_admin_get_value( 'ec_admin_new_price_trigger', 'text' ),
		ec_admin_new_price_trigger_rate: ec_admin_get_value( 'ec_admin_new_price_trigger_rate', 'text' ),
		ec_admin_new_price_trigger_zone_id: ec_admin_get_value( 'ec_admin_new_price_trigger_zone_id', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_price_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_price_trigger' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_price_trigger_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_price_trigger_zone_id' ) ).val( '0' );
		jQuery( document.getElementById( 'insert_new_price_trigger_here' ) ).before( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_price_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_price_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_price_trigger_row_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_price_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_price_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_price_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_price_trigger_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_price_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_price_trigger_zone_id_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Weight Trigger */
function ec_admin_add_new_shipping_weight_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_weight_trigger',
		ec_admin_new_weight_trigger: ec_admin_get_value( 'ec_admin_new_weight_trigger', 'text' ),
		ec_admin_new_weight_trigger_rate: ec_admin_get_value( 'ec_admin_new_weight_trigger_rate', 'text' ),
		ec_admin_new_weight_trigger_zone_id: ec_admin_get_value( 'ec_admin_new_weight_trigger_zone_id', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_weight_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_weight_trigger' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_weight_trigger_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_weight_trigger_zone_id' ) ).val( '0' );
		jQuery( document.getElementById( 'insert_new_weight_trigger_here' ) ).before( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_weight_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_weight_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_weight_trigger_row_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_weight_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_weight_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_weight_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_weight_trigger_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_weight_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_weight_trigger_zone_id_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Quantity Trigger */
function ec_admin_add_new_shipping_quantity_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_quantity_trigger',
		ec_admin_new_quantity_trigger: ec_admin_get_value( 'ec_admin_new_quantity_trigger', 'text' ),
		ec_admin_new_quantity_trigger_rate: ec_admin_get_value( 'ec_admin_new_quantity_trigger_rate', 'text' ),
		ec_admin_new_quantity_trigger_zone_id: ec_admin_get_value( 'ec_admin_new_quantity_trigger_zone_id', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_quantity_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_quantity_trigger' ) ).val( '1' );
		jQuery( document.getElementById( 'ec_admin_new_quantity_trigger_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_quantity_trigger_zone_id' ) ).val( '0' );
		jQuery( document.getElementById( 'insert_new_quantity_trigger_here' ) ).before( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_quantity_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_quantity_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_quantity_trigger_row_' + id ) ).remove( );
		jQuery( document.getElementById( 'ec_admin_quantity_trigger_spacer_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_quantity_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_quantity_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_quantity_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_quantity_trigger_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_quantity_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_quantity_trigger_zone_id_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Percentage Rates */
function ec_admin_add_new_shipping_percentage_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_percentage_trigger',
		ec_admin_new_percentage_trigger: ec_admin_get_value( 'ec_admin_new_percentage_trigger', 'text' ),
		ec_admin_new_percentage_trigger_rate: ec_admin_get_value( 'ec_admin_new_percentage_trigger_rate', 'text' ),
		ec_admin_new_percentage_trigger_zone_id: ec_admin_get_value( 'ec_admin_new_percentage_trigger_zone_id', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_percentage_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_percentage_trigger' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_percentage_trigger_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_percentage_trigger_zone_id' ) ).val( '0' );
		jQuery( document.getElementById( 'insert_new_percentage_trigger_here' ) ).before( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_percentage_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_percentage_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_percentage_trigger_row_' + id ) ).remove( );
		jQuery( document.getElementById( 'ec_admin_percentage_trigger_spacer_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_percentage_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_percentage_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_percentage_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_percentage_trigger_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_percentage_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_percentage_trigger_zone_id_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Static Rates */
function ec_admin_add_new_shipping_method_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_method_trigger',
		ec_admin_new_method_label: ec_admin_get_value( 'ec_admin_new_method_label', 'text' ),
		ec_admin_new_method_trigger_rate: ec_admin_get_value( 'ec_admin_new_method_trigger_rate', 'text' ),
		ec_admin_new_method_trigger_zone_id: ec_admin_get_value( 'ec_admin_new_method_trigger_zone_id', 'select' ),
		ec_admin_new_method_trigger_free_shipping_at: ec_admin_get_value( 'ec_admin_new_method_trigger_free_shipping_at', 'text' ),
		ec_admin_new_method_trigger_shipping_order: ec_admin_get_value( 'ec_admin_new_method_trigger_shipping_order', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_method_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_method_label' ) ).val( '' );
		jQuery( document.getElementById( 'ec_admin_new_method_trigger_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_method_trigger_zone_id' ) ).val( '0' );
		jQuery( document.getElementById( 'ec_admin_new_method_trigger_free_shipping_at' ) ).val( '' );
		jQuery( document.getElementById( 'ec_admin_new_method_trigger_shipping_order' ) ).val( '0' );
		jQuery( document.getElementById( 'insert_new_method_trigger_here' ) ).before( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_method_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_method_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_method_trigger_row_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_method_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_method_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_method_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_method_label_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_method_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_method_trigger_zone_id_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_method_trigger_free_shipping_at_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_method_trigger_shipping_order_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

/* Shipping Live Rates */
function ec_admin_update_new_live_rate_method_display( ){
	var type = jQuery( document.getElementById( 'ec_admin_new_live_code_type' ) ).val( );
	jQuery( document.getElementById( 'is_auspost_based' ) ).hide( );
	jQuery( document.getElementById( 'is_canadapost_based' ) ).hide( );
	jQuery( document.getElementById( 'dhl_based' ) ).hide( );
	jQuery( document.getElementById( 'is_fedex_based' ) ).hide( );
	jQuery( document.getElementById( 'is_ups_based' ) ).hide( );
	jQuery( document.getElementById( 'is_usps_based' ) ).hide( );
	jQuery( document.getElementById( type ) ).show( );
}

function ec_admin_update_live_rate_method_display( shippingrate_id ){
	var type = jQuery( document.getElementById( 'ec_admin_new_live_code_type_' + shippingrate_id ) ).val( );
	jQuery( document.getElementById( 'is_auspost_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( 'is_canadapost_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( 'dhl_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( 'is_fedex_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( 'is_ups_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( 'is_usps_based_' + shippingrate_id ) ).hide( );
	jQuery( document.getElementById( type + '_' + shippingrate_id ) ).show( );
}

function ec_admin_add_new_shipping_live_trigger( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_add_live_trigger',
		ec_admin_new_live_code_type: ec_admin_get_value( 'ec_admin_new_live_code_type', 'select' ),
		ec_admin_new_live_code_is_auspost_based: ec_admin_get_value( 'ec_admin_new_live_code_is_auspost_based', 'select' ),
		ec_admin_new_live_code_is_canadapost_based: ec_admin_get_value( 'ec_admin_new_live_code_is_canadapost_based', 'select' ),
		ec_admin_new_live_code_is_dhl_based: ec_admin_get_value( 'ec_admin_new_live_code_is_dhl_based', 'select' ),
		ec_admin_new_live_code_is_fedex_based: ec_admin_get_value( 'ec_admin_new_live_code_is_fedex_based', 'select' ),
		ec_admin_new_live_code_is_ups_based: ec_admin_get_value( 'ec_admin_new_live_code_is_ups_based', 'select' ),
		ec_admin_new_live_code_is_usps_based: ec_admin_get_value( 'ec_admin_new_live_code_is_usps_based', 'select' ),
		ec_admin_new_live_label: ec_admin_get_value( 'ec_admin_new_live_label', 'text' ),
		ec_admin_new_live_override_rate: ec_admin_get_value( 'ec_admin_new_live_override_rate', 'text' ),
		ec_admin_new_live_free_shipping_threshold: ec_admin_get_value( 'ec_admin_new_live_free_shipping_threshold', 'text' ),
		ec_admin_new_live_shipping_zone: ec_admin_get_value( 'ec_admin_new_live_shipping_zone', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_no_live_triggers' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_live_code_type' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_auspost_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_canadapost_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_dhl_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_fedex_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_ups_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_usps_based' ) ).val( 0 );
		jQuery( document.getElementById( 'ec_admin_new_live_code_is_auspost_based' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_new_live_label' ) ).val( '' );
		jQuery( document.getElementById( 'ec_admin_new_live_override_rate' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_live_free_shipping_threshold' ) ).val( '0.00' );
		jQuery( document.getElementById( 'ec_admin_new_live_shipping_zone' ) ).val( '0' );
		jQuery( document.getElementById( 'live' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_delete_live_trigger( id ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_delete_live_trigger',
		shippingrate_id: id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_live_rate_' + id ) ).remove( );
		if( data == '0' ){
			jQuery( document.getElementById( 'ec_admin_no_live_triggers' ) ).show( );
		}
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_shipping_live_triggers( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_update_shipping_live_triggers',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery( '.ec_admin_live_label_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery( '.ec_admin_live_trigger_rate_input' ).each( function( index ){
		data[jQuery( this ).attr( 'name' )] = jQuery( this ).val( );
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_save_fraktjakt_options( ){
	jQuery( document.getElementById( "ec_admin_shipping_method_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_save_fraktjakt_settings',
		fraktjakt_customer_id: ec_admin_get_value( 'fraktjakt_customer_id', 'text' ),
		fraktjakt_login_key: ec_admin_get_value( 'fraktjakt_login_key', 'text' ),
		fraktjakt_conversion_rate: ec_admin_get_value( 'fraktjakt_conversion_rate', 'text' ),
		fraktjakt_test_mode: ec_admin_get_value( 'fraktjakt_test_mode', 'checkbox' ),
		fraktjakt_address: ec_admin_get_value( 'fraktjakt_address', 'text' ),
		fraktjakt_city: ec_admin_get_value( 'fraktjakt_city', 'text' ),
		fraktjakt_state: ec_admin_get_value( 'fraktjakt_state', 'text' ),
		fraktjakt_zip: ec_admin_get_value( 'fraktjakt_zip', 'text' ),
		fraktjakt_country: ec_admin_get_value( 'fraktjakt_country', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_shipping_rates_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_shipping_method_loader' );
	} } );
	
	return false;
}

function ec_admin_settings_open_live_rate( shipping_rate_id ){
	if( jQuery( document.getElementById( 'ec_admin_live_rate_content_' + shipping_rate_id ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_admin_live_rate_content_' + shipping_rate_id ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_live_rate_toggle_' + shipping_rate_id ) ).html( '<div class="dashicons-before dashicons-plus"></div>' );
	}else{
		jQuery( document.getElementById( 'ec_admin_live_rate_content_' + shipping_rate_id ) ).show( );
		jQuery( document.getElementById( 'ec_admin_live_rate_toggle_' + shipping_rate_id ) ).html( '<div class="dashicons-before dashicons-minus"></div>' );
	}
}