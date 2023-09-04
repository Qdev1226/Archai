function wpeasycart_admin_update_dynamic_sizing( ){
    if( !jQuery( document.getElementById( 'ec_option_default_dynamic_sizing' ) ).is( ':checked' ) ){
        jQuery( document.getElementById( 'ec_admin_ec_option_default_desktop_image_height_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_laptop_image_height_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_tablet_wide_image_height_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_tablet_image_height_row' ) ).show( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_smartphone_image_height_row' ) ).show( );
    }else{
        jQuery( document.getElementById( 'ec_admin_ec_option_default_desktop_image_height_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_laptop_image_height_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_tablet_wide_image_height_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_tablet_image_height_row' ) ).hide( );
        jQuery( document.getElementById( 'ec_admin_ec_option_default_smartphone_image_height_row' ) ).hide( );
    }
}

function ec_admin_save_design_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if( jQuery( this_ele ).is( ':checked' ) )
		val = 1;
		
	var data = {
		action: 'ec_admin_ajax_save_design_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_design_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_design_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );
    
    if( jQuery( '#ec_option_font_main' ).val( ) == 'custom' ){
        jQuery( '#ec_admin_ec_option_font_custom_row' ).show( );
    }else{
        jQuery( '#ec_admin_ec_option_font_custom_row' ).hide( );
    }
    
	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_design_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_design_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function ec_admin_save_design_color_setting( this_ele ){
    jQuery( this_ele ).parent( ).parent( ).parent( ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).parent( ).parent( ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_design_settings',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_design_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).parent( ).parent( ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).parent( ).parent( ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).parent( ).parent( ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );
	
	return false;
}

function wpeasycart_admin_update_product_design_view() {
	if( jQuery( document.getElementById( 'ec_option_default_product_border' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_row' ) ).show( );
		if( jQuery( document.getElementById( 'ec_option_default_product_rounded_corners' ) ).is( ':checked' ) ){
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tl_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tr_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_bl_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_br_row' ) ).show( );
		} else {
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tl_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tr_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_bl_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_br_row' ) ).hide( );
		}
	} else {
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tl_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_tr_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_bl_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_ec_option_default_product_rounded_corners_br_row' ) ).hide( );
	}
}
