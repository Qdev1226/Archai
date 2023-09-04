function ec_admin_save_additional_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_additional_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_miscellaneous_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_additional_text_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_additional_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_miscellaneous_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_ajax_clear_stats( ){
	
	jQuery( document.getElementById( "ec_admin_miscellaneous_additional_options_loader" ) ).fadeIn( 'fast' );
		
	var data = {
		action: 'ec_admin_ajax_clear_stats',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_miscellaneous_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_miscellaneous_additional_options_loader' );
	} } );
	
	return false;
	
}

function ec_admin_search_options_update( ){
    if( jQuery( document.getElementById( 'ec_option_use_live_search' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_option_search_title_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_model_number_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_manufacturer_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_description_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_short_description_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_menu_row' ) ).show( );
        jQuery( document.getElementById( 'ec_option_search_by_or_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_option_search_title_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_model_number_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_manufacturer_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_description_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_short_description_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_menu_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_option_search_by_or_row' ) ).hide( );
    }
}