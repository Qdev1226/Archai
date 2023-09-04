function wpeasycart_start_square_import( ){
	if ( jQuery( '#wpeasycart_square_import_products' ).is( ':checked' ) || jQuery( '#wpeasycart_square_import_inventory' ).is( ':checked' ) ) {
		jQuery( document.getElementById( 'wpeasycart_square_import_something_required' ) ).hide();
		jQuery( document.getElementById( 'wpeasycart_square_import_progress_bar' ) ).show( );
		jQuery( document.getElementById( 'wpeasycart_square_processing_button' ) ).show( );
		jQuery( document.getElementById( 'wpeasycart_square_start_button' ) ).hide( );
		jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '20%' );
		if ( jQuery( '#wpeasycart_square_import_products' ).is( ':checked' ) ) {
			wpeasycart_continue_square_modifier_items_import( '', 0 );
		} else if ( jQuery( '#wpeasycart_square_import_inventory' ).is( ':checked' ) ) {
			jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '40%' );
			wpeasycart_continue_square_categories_sync( '', 0 );
		}
	} else {
		jQuery( document.getElementById( 'wpeasycart_square_import_something_required' ) ).show();
	}
}

function wpeasycart_continue_square_categories_sync( cursor, curr_count ){
	jQuery( document.getElementById( 'wpeasycart_square_inventory_sync_progress_bar' ) ).show( );
	var data = {
		action: 'ec_admin_ajax_square_categories_import',
		cursor: cursor,
		curr_count: curr_count,
		wp_easycart_nonce: ec_admin_get_value( 'wpec_cart_importer_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
		var result_arr = JSON.parse( result );
		if( result_arr.has_more ){
			jQuery( '#wpeasycart_square_inventory_sync_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['categories-synced'] );
			wpeasycart_continue_square_categories_sync( result_arr.cursor, result_arr.curr_count );
		}else{
			jQuery( '#wpeasycart_square_inventory_sync_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['all-categories-imported'] );
			jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '70%' );
			wpeasycart_continue_square_import( '', 0 );
		}
	} } );
}

function wpeasycart_continue_square_inventory_sync( cursor, curr_count ){
	jQuery( document.getElementById( 'wpeasycart_square_inventory_sync_progress_bar' ) ).show( );
	var data = {
		action: 'ec_admin_ajax_square_sync_inventory',
		cursor: cursor,
		curr_count: curr_count,
		wp_easycart_nonce: ec_admin_get_value( 'wpec_cart_importer_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
		var result_arr = JSON.parse( result );
		if( result_arr.has_more ){
			jQuery( '#wpeasycart_square_inventory_sync_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['inventory-items-synced'] );
			wpeasycart_continue_square_inventory_sync( result_arr.cursor, result_arr.curr_count );
		}else{
			jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['inventory-items-synced'] );
			jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '100%' ).addClass( 'done' );
			jQuery( document.getElementById( 'wpeasycart_square_processing_button' ) ).hide( );
			jQuery( document.getElementById( 'wpeasycart_square_start_button' ) ).show( );
		}
	} } );
}

function wpeasycart_continue_square_modifier_items_import( cursor, curr_count ){
    jQuery( document.getElementById( 'wpeasycart_square_import_progress_bar' ) ).show( );
    var data = {
		action: 'ec_admin_ajax_square_modifier_items_import',
        cursor: cursor,
        curr_count: curr_count,
        sync_modifiers: ec_admin_get_value( 'wpeasycart_square_sync_matches', 'checkbox' ),
        wp_easycart_nonce: ec_admin_get_value( 'wpec_cart_importer_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
		if( result_arr.has_more ){
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['modifier-items-imported'] );
            wpeasycart_continue_square_modifier_items_import( result_arr.cursor, result_arr.curr_count );
        }else{
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-modifiers-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '70%' );
            wpeasycart_continue_square_categories_sync( '', 0 );
        }
	} } );
}

function wpeasycart_continue_square_modifier_import( cursor, curr_count ){ /* Not Needed right now */
    jQuery( document.getElementById( 'wpeasycart_square_import_progress_bar' ) ).show( );
    var data = {
		action: 'ec_admin_ajax_square_modifier_import',
        cursor: cursor,
        curr_count: curr_count,
        sync_modifiers: ec_admin_get_value( 'wpeasycart_square_sync_matches', 'checkbox' ),
        wp_easycart_nonce: ec_admin_get_value( 'wpec_cart_importer_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
		if( result_arr.has_more ){
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['modifiers-imported'] );
            wpeasycart_continue_square_modifier_import( result_arr.cursor, result_arr.curr_count );
        }else{
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-modifier-items-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '65%' );
            wpeasycart_continue_square_import( '', 0 );
        }
	} } );
}

function wpeasycart_continue_square_import( cursor, curr_count ){
    jQuery( document.getElementById( 'wpeasycart_square_import_progress_bar' ) ).show( );
    var data = {
		action: 'ec_admin_ajax_square_import',
        cursor: cursor,
        curr_count: curr_count,
        sync_products: ec_admin_get_value( 'wpeasycart_square_sync_matches', 'checkbox' ),
		sync_inventory: ec_admin_get_value( 'wpeasycart_square_import_inventory', 'checkbox' ),
        wp_easycart_nonce: ec_admin_get_value( 'wpec_cart_importer_nonce', 'text' )
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
		if( result_arr.has_more ){
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['items-imported'] );
            wpeasycart_continue_square_import( result_arr.cursor, result_arr.curr_count );
        }else{
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-products-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '100%' ).addClass( 'done' );
    		jQuery( document.getElementById( 'wpeasycart_square_processing_button' ) ).hide( );
			jQuery( document.getElementById( 'wpeasycart_square_start_button' ) ).show( );
        }
	} } );
}

function wpeasycart_start_shopify_import( ){
    jQuery( document.getElementById( 'wpeasycart_shopify_processing_button' ) ).show( );
    jQuery( document.getElementById( 'wpeasycart_shopify_start_button' ) ).hide( );
    jQuery( document.getElementById( 'wpeasycart_shopify_import_progress_bar' ) ).show( );
    jQuery( document.getElementById( 'wpeasycart_shopify_import_errors' ) ).hide( );
    jQuery( document.getElementById( 'wpeasycart_shopify_input_fields' ) ).hide( );
    jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '23%' );
    wpeasycart_continue_shopify_products_import( '', 0 );
}

function wpeasycart_continue_shopify_products_import( cursor, curr_count ){
    var data = {
		action: 'ec_admin_ajax_shopify_import_products',
        cursor: cursor,
        curr_count: curr_count,
        wpeasycart_shopify_api_key: jQuery( document.getElementById( 'wpeasycart_shopify_api_key' ) ).val( ),
        wpeasycart_shopify_api_password: jQuery( document.getElementById( 'wpeasycart_shopify_api_password' ) ).val( ),
        wpeasycart_shopify_domain: jQuery( document.getElementById( 'wpeasycart_shopify_domain' ) ).val( )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
        if( result_arr.has_errors ){
            jQuery( document.getElementById( 'wpeasycart_shopify_processing_button' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_start_button' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_progress_bar' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_errors' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_input_fields' ) ).show( );
            
        }else if( result_arr.has_more ){
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['items-imported'] );
            wpeasycart_continue_shopify_products_import( result_arr.cursor, result_arr.curr_count );
        
        }else{
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-products-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '65%' );
            wpeasycart_continue_shopify_categories_import( '', 0 );
            
        }
	} } );
}

function wpeasycart_continue_shopify_categories_import( cursor, curr_count ){
    var data = {
		action: 'ec_admin_ajax_shopify_import_categories',
        cursor: cursor,
        curr_count: curr_count,
        wpeasycart_shopify_api_key: jQuery( document.getElementById( 'wpeasycart_shopify_api_key' ) ).val( ),
        wpeasycart_shopify_api_password: jQuery( document.getElementById( 'wpeasycart_shopify_api_password' ) ).val( ),
        wpeasycart_shopify_domain: jQuery( document.getElementById( 'wpeasycart_shopify_domain' ) ).val( )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
        if( result_arr.has_errors ){
            jQuery( document.getElementById( 'wpeasycart_shopify_processing_button' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_start_button' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_progress_bar' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_errors' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_input_fields' ) ).show( );
            
        }else if( result_arr.has_more ){
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['categories-imported'] );
            wpeasycart_continue_shopify_categories_import( result_arr.cursor, result_arr.curr_count );
        
        }else{
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-categories-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '85%' );
            wpeasycart_continue_shopify_users_import( '', 0 );
            
        }
	} } );
}

function wpeasycart_continue_shopify_users_import( cursor, curr_count ){
    jQuery( document.getElementById( 'wpeasycart_shopify_import_progress_bar' ) ).show( );
    var data = {
		action: 'ec_admin_ajax_shopify_import_users',
        cursor: cursor,
        curr_count: curr_count,
        wpeasycart_shopify_api_key: jQuery( document.getElementById( 'wpeasycart_shopify_api_key' ) ).val( ),
        wpeasycart_shopify_api_password: jQuery( document.getElementById( 'wpeasycart_shopify_api_password' ) ).val( ),
        wpeasycart_shopify_domain: jQuery( document.getElementById( 'wpeasycart_shopify_domain' ) ).val( )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(result){
        var result_arr = JSON.parse( result );
		if( result_arr.has_errors ){
            jQuery( document.getElementById( 'wpeasycart_shopify_processing_button' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_start_button' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_progress_bar' ) ).hide( );
            jQuery( document.getElementById( 'wpeasycart_shopify_import_errors' ) ).show( );
            jQuery( document.getElementById( 'wpeasycart_shopify_input_fields' ) ).show( );
            
        }else if( result_arr.has_more ){
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( result_arr.curr_count + ' ' + wp_easycart_cart_importer_language['customers-imported'] );
            wpeasycart_continue_shopify_users_import( result_arr.cursor, result_arr.curr_count );
        
        }else{
            jQuery( '#wpeasycart_shopify_import_progress_bar .ec_admin_process_status > span' ).html( wp_easycart_cart_importer_language['all-customers-imported'] );
            jQuery( '#wpeasycart_square_import_progress_bar .ec_admin_progress_bar > div' ).width( '100%' ).addClass( 'done' );
        }
	} } );
}