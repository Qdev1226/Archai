function ec_admin_save_product_options( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	
	var val = 0;
		
	if ( jQuery( this_ele ).is( ':checked' ) ) {
		val = 1;
	}

	var data = {
		action: 'ec_admin_ajax_save_product_settings_v2',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	}
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
	} } );
	
	return false;
}
function ec_admin_save_product_text_setting( this_ele ){
	jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
	jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).hide( );

	var val = jQuery( this_ele ).val( );

	var data = {
		action: 'ec_admin_ajax_save_product_settings_v2',
		update_var: jQuery( this_ele ).attr( 'id' ),
		val: val,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	}

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).hide( );
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
		jQuery( this_ele ).parent( ).find( '.wp-easycart-admin-icon-close-check' ).delay( 900 ).fadeIn( 'slow' );
	} } );

	return false;
}

function wpeasycart_admin_update_sort_box_view( ){
	if( jQuery( document.getElementById( 'ec_option_show_sort_box' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_store_filter_default_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_0_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_1_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_2_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_3_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_4_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_5_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_6_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_filter_7_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_store_filter_default_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_0_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_1_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_2_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_3_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_4_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_5_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_6_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_filter_7_row' ) ).hide( );
	}
}

function wpeasycart_admin_update_sidebar_view() {
	if ( jQuery( document.getElementById( 'ec_option_show_store_sidebar' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_store_sidebar_position_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_filter_clear_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_include_search_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_include_categories_row' ) ).show( );
		if ( jQuery( document.getElementById( 'ec_option_store_sidebar_include_categories' ) ).is( ':checked' ) ) {
			jQuery( document.getElementById( 'ec_admin_include_categories_first_row' ) ).show( );
			jQuery( document.getElementById( 'ec_admin_store_sidebar_categories_row' ) ).show( );
		} else {
			jQuery( document.getElementById( 'ec_admin_include_categories_first_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_store_sidebar_categories_row' ) ).hide( );
		}
		jQuery( document.getElementById( 'ec_admin_sidebar_include_category_filters_row' ) ).show( );
		if ( jQuery( document.getElementById( 'ec_option_sidebar_include_category_filters' ) ).is( ':checked' ) ) {
			jQuery( document.getElementById( 'ec_admin_sidebar_category_filter_id_row' ) ).show( );
			jQuery( document.getElementById( 'ec_option_sidebar_category_filter_method_row' ) ).show( );
		} else {
			jQuery( document.getElementById( 'ec_admin_sidebar_category_filter_id_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_option_sidebar_category_filter_method_row' ) ).hide( );
		}
		jQuery( document.getElementById( 'ec_admin_sidebar_include_option_filters_row' ) ).show( );
		if ( jQuery( document.getElementById( 'ec_option_sidebar_include_option_filters' ) ).is( ':checked' ) ) {
			jQuery( document.getElementById( 'ec_admin_store_sidebar_option_filters_row' ) ).show( );
		} else {
			jQuery( document.getElementById( 'ec_admin_store_sidebar_option_filters_row' ) ).hide( );
		}
	} else {
		jQuery( document.getElementById( 'ec_admin_store_sidebar_position_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_filter_clear_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_include_search_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_include_categories_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_include_categories_first_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_categories_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_sidebar_include_category_filters_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_sidebar_category_filter_id_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_option_sidebar_category_filter_method_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_sidebar_include_option_filters_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_store_sidebar_option_filters_row' ) ).hide( );
	}
}

function wp_easycart_admin_update_cart_stock_view( ){
	if( jQuery( document.getElementById( 'ec_option_stock_removed_in_cart' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_tempcart_stock_hours_row' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_tempcart_stock_timeframe_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_tempcart_stock_hours_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_tempcart_stock_timeframe_row' ) ).hide( );
	}
}
/* ON LOAD */
/*importer for products*/
function ec_admin_start_importer( file, status_field, nonce ){
	jQuery( document.getElementById( status_field ) ).text( wp_easycart_products_language['processing'] );
	jQuery( document.getElementById( status_field ) ).fadeIn( 'fast' );
	var data = {
		action: 'ec_admin_ajax_import_products',
		import_file_url: ec_admin_get_value( file, 'text' ),
		wp_easycart_nonce: nonce
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		if( data != 'success' ){
			jQuery( document.getElementById( status_field ) ).text(data);
		} else {
			jQuery( document.getElementById( status_field ) ).text( wp_easycart_products_language['completed'] );
		}
	} } );

	return false;


}

var has_valid_model_number = true;
jQuery( document ).ready( function( ){

	// Re-ordering option items
	jQuery( 'div#advanced_options_holder' ).sortable( {
		update: function( event, ui ){
			var rows = jQuery( 'div#advanced_options_holder div.ec_admin_option_row' );
			var ids = Array( );
			var id=0;
			for( var i=0; i<rows.length; i++ ){
				ids.push( { id:jQuery( rows[i] ).attr( 'data-id' ), order: Number( i ) } );
			}

			var data = {
				product_id: jQuery( document.getElementById( 'product_id' ) ).val( ),
				sort_order: ids,
				action: 'ec_admin_ajax_save_product_advanced_option_order',
				wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
			};
			jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
				/* Now get updated HTML for Option Item Images */
				ec_admin_product_details_refresh_option_images();
			} } );
		}
	} );

	jQuery( document.getElementById( 'model_number' ) ).on( 'change', function( ){
		if( jQuery( document.getElementById( 'model_number' ) ).val( ) == "" ){
			jQuery( document.getElementById( 'model_number' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
			jQuery( document.getElementById( 'model_number_validation' ) ).show( );

		}else if( jQuery( document.getElementById( 'model_number' ) ).val( ) != jQuery( document.getElementById( 'model_number_orig' ) ).val( ) ){
			var data = {
				action: 'ec_admin_ajax_validate_model_number',
				product_id: ec_admin_get_value( 'product_id', 'hidden' ),
				model_number: ec_admin_get_value( 'model_number', 'text' ),
				wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
			};

			jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
				if( data == '0' ){
					jQuery( document.getElementById( 'model_number' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
					jQuery( document.getElementById( 'model_number_validation' ) ).show( );
					jQuery( document.getElementById( 'sku_invalid_error' ) ).show( );
					jQuery( document.getElementById( 'sku_duplicate_error' ) ).hide( );
					has_valid_model_number = false;

				}else if( data == '-1' ){
					jQuery( document.getElementById( 'model_number' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
					jQuery( document.getElementById( 'model_number_validation' ) ).show( );
					jQuery( document.getElementById( 'sku_invalid_error' ) ).hide( );
					jQuery( document.getElementById( 'sku_duplicate_error' ) ).show( );
					has_valid_model_number = false;

				}else{
					jQuery( document.getElementById( 'model_number' ) ).removeClass( 'ec_admin_field_error' );
					jQuery( document.getElementById( 'model_number_validation' ) ).hide( );
					jQuery( document.getElementById( 'sku_invalid_error' ) ).hide( );
					jQuery( document.getElementById( 'sku_duplicate_error' ) ).hide( );
					has_valid_model_number = true;
				}
			} } );
		}else{
			jQuery( document.getElementById( 'model_number' ) ).removeClass( 'ec_admin_field_error' );
			jQuery( document.getElementById( 'model_number_validation' ) ).hide( );
			has_valid_model_number = true;
		}
		return false;
	} );

	jQuery( document.getElementById( 'ec_new_product_type' ) ).on( 'change', function( ){
		if( jQuery( this ).val( ) == '5' || jQuery( this ).val( ) == '6' ){
			jQuery( document.getElementById( 'stripe_paypal_only' ) ).show( );
		}else{
			jQuery( document.getElementById( 'stripe_paypal_only' ) ).hide( );
		}
	} );

	if( jQuery( document.getElementById( 'subscription_bill_period' ) ).length ){
		jQuery( document.getElementById( 'subscription_bill_period' ) ).on( 'change', wpeasycart_verify_subscription_length );
		jQuery( document.getElementById( 'subscription_bill_length' ) ).on( 'change', wpeasycart_verify_subscription_length );
	}
} );

function wpeasycart_verify_subscription_length( ){
	if( jQuery( document.getElementById( 'subscription_bill_period' ) ).val( ) == 'Y' && Number( jQuery( document.getElementById( 'subscription_bill_length' ) ).val( ) ) > 1 ){
		jQuery( document.getElementById( 'subscription_bill_length' ) ).val( '1' );
		alert( wp_easycart_products_language['subscription-note-1'] );

	}else if( jQuery( document.getElementById( 'subscription_bill_period' ) ).val( ) == 'M' && Number( jQuery( document.getElementById( 'subscription_bill_length' ) ).val( ) ) > 12 ){
		jQuery( document.getElementById( 'subscription_bill_length' ) ).val( '12' );
		alert( wp_easycart_products_language['subscription-note-2'] );
	}
}

function ec_admin_product_details_change_logic( select_ele, option_to_product_id ){
	var option_id = select_ele.val( );
	select_ele.parent( ).find( '.logic-optionitem' ).each( function( ){
		if( jQuery( this ).attr( 'data-option-id' ) == option_id ){
			jQuery( this ).show( );
		}else{
			jQuery( this ).hide( );
		}
	} );
	ec_admin_product_details_save_logic( option_to_product_id );
}

function ec_admin_product_details_enable_logic( checkbox_ele, option_to_product_id ){

	if( checkbox_ele.is( ':checked' ) ){
		checkbox_ele.parent( ).parent( ).find( '.ec_admin_option_logic_content' ).show( );
	}else{
		checkbox_ele.parent( ).parent( ).find( '.ec_admin_option_logic_content' ).hide( );
	}

	if( checkbox_ele.is( ':checked' ) ){
		ec_admin_product_details_save_logic( option_to_product_id );

	}else{
		var data = {
			action: 'ec_admin_ajax_save_product_details_logic',
			option_to_product_id: option_to_product_id,
			enabled: 0,
			show_field: 0,
			and_rules: 0,
			rules: JSON.stringify( [] ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
		};

		jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ } } );
	}
}

function ec_admin_product_details_save_logic( option_to_product_id ){
	//var option_to_product_id = jQuery( '#ec_logic_item_' + option_to_product_id ).attr( 'data-option-to-product-id' );
	var show_field = jQuery( '#ec_logic_item_' + option_to_product_id ).find( '.logic-show' ).val( );
	var and_rules = jQuery( '#ec_logic_item_' + option_to_product_id ).find( '.logic-and' ).val( );
	var rules = [];
	jQuery( '#ec_logic_item_' + option_to_product_id ).find( '.ec_admin_option_logic_item' ).each( function( ){
		var optionitem_value = "";
		var optionitem_id = 0;
		var option_id = jQuery( this ).find( '.logic-option' ).val( );

		jQuery( this ).find( 'select.logic-optionitem' ).each( function( ){
			if( jQuery( this ).attr( 'data-option-id' ) == option_id ){
				optionitem_id = jQuery( this ).val( );
			}
		} );
		jQuery( this ).find( 'input.logic-optionitem' ).each( function( ){
			if( jQuery( this ).attr( 'data-option-id' ) == option_id ){
				optionitem_value = jQuery( this ).val( );
			}
		} );
		rules.push( { 
			'option_id': option_id,
			'operator': jQuery( this ).find( '.logic-operator' ).val( ),
			'optionitem_id': optionitem_id,
			'optionitem_value': optionitem_value
		} );
	} );
	var data = {
		action: 'ec_admin_ajax_save_product_details_logic',
		option_to_product_id: option_to_product_id,
		enabled: true,
		show_field: show_field,
		and_rules: and_rules,
		rules: JSON.stringify( rules ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ } } );
}

function ec_admin_product_details_add_logic( ele ){
	var ele_clone = jQuery( ele ).parent( ).parent( ).find( '.ec_admin_option_logic_item' ).first( ).clone( );
	var selected_option_id = jQuery( ele ).parent( ).parent( ).find( '.ec_admin_option_logic_item' ).first( ).find( '.logic-option' ).val( );
	var selected_logic_operator = jQuery( ele ).parent( ).parent( ).find( '.ec_admin_option_logic_item' ).first( ).find( '.logic-operator' ).val( );
	ele_clone.find( '.logic-option' ).val( selected_option_id );
	ele_clone.find( '.logic-operator' ).val( selected_logic_operator );
	ele_clone.find( '.logic-optionitem' ).each( function( ){ if( jQuery( this ).attr( 'data-option-id' ) == selected_option_id ){ jQuery( this ).show( ); }else{ jQuery( this ).hide( ); } } );
	jQuery( ele ).parent( ).parent( ).append( ele_clone );
}

function ec_admin_product_details_remove_logic( ele ){
	if( jQuery( ele ).parent( ).parent( ).find( '.ec_admin_option_logic_item' ).length > 1 ){
		jQuery( ele ).parent( ).remove( );
	}else{
		jQuery( ele ).parent( ).find( 'select' ).each( function( ){
			jQuery( this ).find( 'option' ).first( ).attr( 'selected', true );
		} );
	}
}

function ec_admin_disable_cart_check( ){
	if( jQuery( document.getElementById( 'ec_option_display_as_catalog' ) ).is( ':checked' ) )
		return confirm( wp_easycart_products_language['catalog-note'] );
	else
		return true;
}

/* Quick Edit Functions */
function ec_admin_save_new_quick_product( next_move ){

	var errors = false;

	if( jQuery( document.getElementById( 'ec_new_product_title' ) ).val( ) == '' ){
		jQuery( document.getElementById( 'ec_new_product_title' ) ).removeClass( 'ec_admin_slideout_error' ).addClass( 'ec_admin_slideout_error' );
		jQuery( document.getElementById( 'title_required' ) ).show( );
		errors = true;
	}else{
		jQuery( document.getElementById( 'ec_new_product_title' ) ).removeClass( 'ec_admin_slideout_error' )
		jQuery( document.getElementById( 'title_required' ) ).hide( );
	}

	if( jQuery( document.getElementById( 'ec_new_product_sku' ) ).val( ) == '' ){
		jQuery( document.getElementById( 'ec_new_product_sku' ) ).removeClass( 'ec_admin_slideout_error' ).addClass( 'ec_admin_slideout_error' );
		jQuery( document.getElementById( 'sku_required' ) ).show( );
		errors = true;
	}else{
		jQuery( document.getElementById( 'ec_new_product_sku' ) ).removeClass( 'ec_admin_slideout_error' )
		jQuery( document.getElementById( 'sku_required' ) ).hide( );
	}

	if( errors ){
		return;
	}

	jQuery( document.getElementById( "ec_admin_new_product_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_new_quick_product',
		ec_new_product_status: ec_admin_get_value( 'ec_new_product_status', 'select' ),
		ec_new_product_featured: ec_admin_get_value( 'ec_new_product_featured', 'select' ),
		ec_new_product_type: ec_admin_get_value( 'ec_new_product_type', 'select' ),
		ec_new_product_title: ec_admin_get_value( 'ec_new_product_title', 'text' ),
		ec_new_product_sku: ec_admin_get_value( 'ec_new_product_sku', 'text' ),
		ec_new_product_manufacturer: ec_admin_get_value( 'ec_new_product_manufacturer', 'select' ),
		ec_new_product_price: ec_admin_get_value( 'ec_new_product_price', 'text' ),
		ec_new_product_image: ec_admin_get_value( 'ec_new_product_image', 'text' ),
		ec_new_product_option_type: ec_admin_get_value( 'ec_new_product_options_needed', 'select' ),
		option1: ec_admin_get_value( 'ec_new_product_option1', 'select' ),
		option2: ec_admin_get_value( 'ec_new_product_option2', 'select' ),
		option3: ec_admin_get_value( 'ec_new_product_option3', 'select' ),
		option4: ec_admin_get_value( 'ec_new_product_option4', 'select' ),
		option5: ec_admin_get_value( 'ec_new_product_option5', 'select' ),
		ec_new_product_is_shippable: ec_admin_get_value( 'ec_new_product_is_shippable', 'select' ),
		ec_new_product_weight: ec_admin_get_value( 'ec_new_product_weight', 'text' ),
		ec_new_product_length: ec_admin_get_value( 'ec_new_product_length', 'text' ),
		ec_new_product_width: ec_admin_get_value( 'ec_new_product_width', 'text' ),
		ec_new_product_height: ec_admin_get_value( 'ec_new_product_height', 'text' ),
		ec_new_product_is_taxable: ec_admin_get_value( 'ec_new_product_is_taxable', 'select' ),
		ec_new_product_stock_option: ec_admin_get_value( 'ec_new_product_stock_option', 'select' ),
		ec_new_product_stock_quantity: ec_admin_get_value( 'ec_new_product_stock_quantity', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_quick_edit_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_new_product_display_loader' );

		var result = JSON.parse( data );
		if( result.error ){
			jQuery( document.getElementById( 'ec_new_product_sku' ) ).removeClass( 'ec_admin_slideout_error' ).addClass( 'ec_admin_slideout_error' );
			jQuery( document.getElementById( 'duplicate_sku' ) ).show( );
		}else{
			var product_id = result.product_id;
			jQuery( document.getElementById( 'ec_new_product_sku' ) ).removeClass( 'ec_admin_slideout_error' )
			jQuery( document.getElementById( 'duplicate_sku' ) ).hide( );
			jQuery( document.getElementById( 'ec_new_product_status' ) ).val( '1' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_featured' ) ).val( '1' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_type' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_title' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_sku' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_manufacturer' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_price' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_image' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_options_needed' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_option1' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_option2' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_option3' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_option4' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_option5' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_is_shippable' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_weight' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_length' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_width' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_height' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_product_is_taxable' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_stock_option' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_product_stock_quantity' ) ).val( '' );
			jQuery( '.ec_admin_new_product_option_row' ).hide( );

			if( next_move == 1 ){ // Create and Edit
				wp_easycart_admin_close_slideout( 'new_product_box' );
				window.location.href = "admin.php?page=wp-easycart-products&subpage=products&product_id=" + product_id + "&ec_admin_form_action=edit";
			}else if( next_move == 2 ){ // Create and Another
				// Do nothing, let user create another.
			}else{ // Create and close
				wp_easycart_admin_close_slideout( 'new_product_box' );
				location.reload( );
			}
		}
	} } );

	return false;
}

function wp_easycart_admin_new_product_type_change( ){
	if( ec_admin_get_value( 'ec_new_product_type', 'select' ) == 1 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 2 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 3 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 4 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 5 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 6 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 7 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 8 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 9 || ec_admin_get_value( 'ec_new_product_type', 'select' ) == 10 ){
		show_pro_required( );
		jQuery( document.getElementById( 'ec_new_product_type' ) ).val( '0' ).trigger( 'change' );
	}
}

function ec_admin_save_new_manufacturer( ){
	jQuery( document.getElementById( "ec_admin_new_manufacturer_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_create_new_manufacturer',
		wp_easycart_nonce: ec_admin_get_value( 'wp-easycart-manufacturer-slideout-nonce', 'text' ),
		manufacturer_name: ec_admin_get_value( 'ec_new_manufacturer_name', 'text' ),
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_new_product_manufacturer' ) ).html( data );

		ec_admin_hide_loader( 'ec_admin_new_manufacturer_display_loader' );
		wp_easycart_admin_close_slideout( 'new_manufacturer_box' );
	} } );

	return false;
}

function ec_admin_new_product_update_option_type( ){
	if( jQuery( document.getElementById( 'ec_new_product_options_needed' ) ).val( ) == '0' || jQuery( document.getElementById( 'ec_new_product_options_needed' ) ).val( ) == '2' ){
		jQuery( '.ec_admin_new_product_option_row' ).hide( );
	}else{
		jQuery( '.ec_admin_new_product_option_row' ).show( );
	}

	if( jQuery( document.getElementById( 'ec_new_product_options_needed' ) ).val( ) == '2' ){
		jQuery( document.getElementById( 'ec_new_product_advanced_options' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_new_product_advanced_options' ) ).hide( );
	}
}

function ec_admin_new_product_update_shipping_type( ){
	if( jQuery( document.getElementById( 'ec_new_product_is_shippable' ) ).val( ) == '0' ){
		jQuery( '.ec_admin_new_product_shipping_row' ).hide( );
	}else{
		jQuery( '.ec_admin_new_product_shipping_row' ).show( );
	}
}

function ec_admin_new_product_update_stock_option( ){
	if( jQuery( document.getElementById( 'ec_new_product_stock_option' ) ).val( ) == '0' ){
		jQuery( '.ec_admin_new_product_basic_stock' ).hide( );
		jQuery( '.ec_admin_new_product_optionitem_stock' ).hide( );
	}else if( jQuery( document.getElementById( 'ec_new_product_stock_option' ) ).val( ) == '1' ){
		jQuery( '.ec_admin_new_product_basic_stock' ).show( );
		jQuery( '.ec_admin_new_product_optionitem_stock' ).hide( );
	}else{
		jQuery( '.ec_admin_new_product_basic_stock' ).hide( );
		jQuery( '.ec_admin_new_product_optionitem_stock' ).show( );
	}
}

/* PRODUCT SETTINGS FUNCTIONS */
function ec_admin_sort_box_change( ){
	if( jQuery( document.getElementById( 'ec_option_show_sort_box' ) ).val( ) == "1" ){
		jQuery( document.getElementById( 'ec_admin_settings_sort_box_options' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_settings_sort_box_options' ) ).hide( );
	}
}
function ec_admin_save_product_settings( ){
	jQuery( document.getElementById( "ec_admin_product_settings_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_settings',
		ec_option_display_as_catalog: ec_admin_get_value( 'ec_option_display_as_catalog', 'checkbox' ),
		ec_option_subscription_one_only: ec_admin_get_value( 'ec_option_subscription_one_only', 'checkbox' ),
		ec_option_restrict_store: ec_admin_get_value( 'ec_option_restrict_store', 'select' ),
		ec_option_product_image_default: ec_admin_get_value( 'ec_option_product_image_default', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_settings_loader' );
	} } );

	return false;
}

function ec_admin_open_new_option( ){
	if( jQuery( document.getElementById( 'use_advanced_optionset' ) ).is( ':checked' ) ){ 
		ec_admin_open_new_advanced_option();
	}else{ 
		ec_admin_open_new_basic_option();
	}
}

function ec_admin_open_new_basic_option() {
	wp_easycart_admin_open_slideout( 'new_option_box' );
	return false;
}

function ec_admin_open_new_advanced_option() {
	wp_easycart_admin_open_slideout( 'new_adv_option_box' );
	return false;
}

function ec_admin_update_advanced_option_fields( ){
	if( jQuery( document.getElementById( 'ec_new_adv_option_type' ) ).val( ) == 'number' ){
		jQuery( document.getElementById( 'ec_new_adv_option_meta_min_row' ) ).show( );
		jQuery( document.getElementById( 'ec_new_adv_option_meta_max_row' ) ).show( );
		jQuery( document.getElementById( 'ec_new_adv_option_meta_step_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_option_meta_min_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_new_adv_option_meta_max_row' ) ).hide( );
		jQuery( document.getElementById( 'ec_new_adv_option_meta_step_row' ) ).hide( );
	}
}

function ec_admin_update_advanced_option_required_field( ){
	if( jQuery( document.getElementById( 'ec_new_adv_option_required' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_new_adv_option_error_text_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_option_error_text_row' ) ).hide( );
	}
}

function ec_admin_update_advanced_optionitem_price_fields( ){
	if( jQuery( document.getElementById( 'ec_new_adv_optionitem_price' ) ).val( ) != "0" ){
		jQuery( document.getElementById( 'ec_new_adv_optionitem_price_adjustment_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_optionitem_price_adjustment_row' ) ).hide( );
	}
}

function ec_admin_update_advanced_optionitem_weight_fields( ){
	if( jQuery( document.getElementById( 'ec_new_adv_optionitem_weight' ) ).val( ) != "0" ){
		jQuery( document.getElementById( 'ec_new_adv_optionitem_weight_adjustment_row' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_optionitem_weight_adjustment_row' ) ).hide( );
	}
}

function ec_admin_save_new_adv_optionset( ){

	var errors = false;
	if( jQuery( document.getElementById( 'ec_new_adv_option_type' ) ).val( ) == '0' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_adv_option_type' ) ).parent( ).find( '.select2-container' ) .removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_option_type' ) ).parent( ).find( '.select2-container' ) .removeClass( 'ec_admin_field_error' );
	}

	if( jQuery( document.getElementById( 'ec_new_adv_option_name' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_adv_option_name' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_option_name' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( jQuery( document.getElementById( 'ec_new_adv_option_label' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_adv_option_label' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_option_label' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( !errors ){

		jQuery( document.getElementById( "ec_admin_new_adv_optionset_display_loader" ) ).fadeIn( 'fast' );

		var data = {
			action: 'ec_admin_ajax_save_new_adv_optionset',
			ec_new_adv_option_type: ec_admin_get_value( 'ec_new_adv_option_type', 'select' ),
			ec_new_adv_option_name: ec_admin_get_value( 'ec_new_adv_option_name', 'text' ),
			ec_new_adv_option_label: ec_admin_get_value( 'ec_new_adv_option_label', 'text' ),
			ec_new_adv_option_meta_min: ec_admin_get_value( 'ec_new_adv_option_meta_min', 'text' ),
			ec_new_adv_option_meta_max: ec_admin_get_value( 'ec_new_adv_option_meta_max', 'text' ),
			ec_new_adv_option_meta_step: ec_admin_get_value( 'ec_new_adv_option_meta_step', 'text' ),
			ec_new_adv_option_required: ec_admin_get_value( 'ec_new_adv_option_required', 'checkbox' ),
			ec_new_adv_option_error_text: ec_admin_get_value( 'ec_new_adv_option_error_text', 'text' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_adv_optionset_quick_edit_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			var result = JSON.parse( data );
			var type = ec_admin_get_value( 'ec_new_adv_option_type', 'select' );
			// Update Option Sets Combos
			jQuery( '#add_new_advanced_option option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_adv_option_name', 'text' ) } ) );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_option_id' ) ).val( result.option_id );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_option_type' ) ).val( type );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_sort_order' ) ).val( '0' );

			// Reset Option Display
			jQuery( document.getElementById( 'ec_new_adv_option_type' ) ).val( 0 ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_adv_option_name' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_option_label' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_option_meta_min' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_option_meta_max' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_option_meta_step' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_option_required' ) ).prop( 'checked', false );
			jQuery( document.getElementById( 'ec_new_adv_option_error_text_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_new_adv_option_error_text' ) ).val( '' );

			if( type == 'combo' || type == 'swatch' || type == 'radio' || type == 'checkbox' ){
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_initially_selected_row' ) ).show( );
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_allows_download_row' ) ).show( );
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_no_shipping_row' ) ).show( );
			}else{
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_initially_selected_row' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_allows_download_row' ) ).hide( );
				jQuery( document.getElementById( 'ec_admin_adv_optionitem_no_shipping_row' ) ).hide( );
			}

			if ( typeof wp_easycart_pro_add_new_advanced_option_insert == 'function' ) {
				wp_easycart_pro_add_new_advanced_option_insert( result.option_id );
			}

			ec_admin_hide_loader( 'ec_admin_new_adv_optionset_display_loader' );
			wp_easycart_admin_close_slideout( 'new_adv_option_box' );
			if( type == 'combo' || type == 'swatch' || type == 'radio' || type == 'checkbox' || type == 'grid' ){
				wp_easycart_admin_open_slideout( 'new_adv_optionitem_box' );
			}
		} } );

	}

	return false;
}

function ec_admin_save_new_adv_optionitem( add_another ){

	var errors = 0;
	if( jQuery( document.getElementById( 'ec_new_adv_optionitem_name' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_adv_optionitem_name' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_adv_optionitem_name' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( !errors ){

		jQuery( document.getElementById( "ec_admin_new_adv_optionitem_display_loader" ) ).fadeIn( 'fast' );

		var data = {
			action: 'ec_admin_ajax_save_new_adv_optionitem',
			ec_new_optionitem_option_id: ec_admin_get_value( 'ec_new_adv_optionitem_option_id', 'text' ),
			ec_new_optionitem_sort_order: ec_admin_get_value( 'ec_new_adv_optionitem_sort_order', 'text' ),
			ec_new_optionitem_name: ec_admin_get_value( 'ec_new_adv_optionitem_name', 'text' ),
			ec_new_optionitem_model_number_extension: ec_admin_get_value( 'ec_new_adv_optionitem_model_number_extension', 'text' ),
			ec_admin_adv_optionitem_initially_selected: ec_admin_get_value( 'ec_admin_adv_optionitem_initially_selected', 'checkbox' ),
			ec_admin_adv_optionitem_allows_download: ec_admin_get_value( 'ec_admin_adv_optionitem_allows_download', 'checkbox' ),
			ec_admin_adv_optionitem_no_shipping: ec_admin_get_value( 'ec_admin_adv_optionitem_no_shipping', 'checkbox' ),
			ec_new_optionitem_price_adjustment_type: ec_admin_get_value( 'ec_new_adv_optionitem_price', 'select' ),
			ec_new_optionitem_price_adjustment: ec_admin_get_value( 'ec_new_adv_optionitem_price_adjustment', 'text' ),
			ec_new_optionitem_weight_adjustment_type: ec_admin_get_value( 'ec_new_adv_optionitem_weight', 'select' ),
			ec_new_optionitem_weight_adjustment: ec_admin_get_value( 'ec_new_adv_optionitem_weight_adjustment', 'text' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_adv_optionitem_quick_edit_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 

			jQuery( document.getElementById( 'ec_new_adv_optionitem_sort_order' ) ).val( Number( jQuery( document.getElementById( 'ec_new_adv_optionitem_sort_order' ) ).val( ) ) + 1 );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_name' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_model_number_extension' ) ).val( '' );
			jQuery( document.getElementById( 'ec_admin_adv_optionitem_initially_selected' ) ).prop( 'checked', false );
			jQuery( document.getElementById( 'ec_admin_adv_optionitem_allows_download' ) ).prop( 'checked', true );
			jQuery( document.getElementById( 'ec_admin_adv_optionitem_no_shipping' ) ).prop( 'checked', false );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_price' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_price_adjustment' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_price_adjustment_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_weight' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_weight_adjustment' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_adv_optionitem_weight_adjustment_row' ) ).hide( );

			ec_admin_hide_loader( 'ec_admin_new_adv_optionitem_display_loader' );
			if( !add_another ){
				jQuery( document.getElementById( 'ec_new_adv_optionitem_option_id' ) ).val( '' );
				jQuery( document.getElementById( 'ec_new_adv_optionitem_option_type' ) ).val( '' );
				jQuery( document.getElementById( 'ec_new_adv_optionitem_sort_order' ) ).val( '0' );
				wp_easycart_admin_close_slideout( 'new_adv_optionitem_box' );
			}
		} } );

	}

	return false;
}

function ec_admin_save_new_optionset( ){

	var errors = false;
	if( jQuery( document.getElementById( 'ec_new_option_type' ) ).val( ) == '0' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_option_type' ) ).parent( ).find( '.select2-container' ) .removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_option_type' ) ).parent( ).find( '.select2-container' ) .removeClass( 'ec_admin_field_error' );
	}

	if( jQuery( document.getElementById( 'ec_new_option_name' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_option_name' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_option_name' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( jQuery( document.getElementById( 'ec_new_option_label' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_option_label' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_option_label' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( !errors ){

		jQuery( document.getElementById( "ec_admin_new_optionset_display_loader" ) ).fadeIn( 'fast' );

		var data = {
			action: 'ec_admin_ajax_save_new_optionset',
			ec_new_option_type: ec_admin_get_value( 'ec_new_option_type', 'select' ),
			ec_new_option_name: ec_admin_get_value( 'ec_new_option_name', 'text' ),
			ec_new_option_label: ec_admin_get_value( 'ec_new_option_label', 'text' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_optionset_quick_edit_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			var result = JSON.parse( data );
			// Update Option Sets Combos
			if( jQuery( '#option1 option:first' ).length ){
				jQuery( '#option1 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#option2 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#option3 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#option4 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#option5 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
			}
			if( jQuery( '#ec_new_product_option1 option:first' ).length ){
				jQuery( '#ec_new_product_option1 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#ec_new_product_option2 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#ec_new_product_option3 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#ec_new_product_option4 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
				jQuery( '#ec_new_product_option5 option:first' ).after( jQuery( '<option />', { 'value': result.option_id, 'text': ec_admin_get_value( 'ec_new_option_name', 'text' ) } ) );
			}
			jQuery( document.getElementById( 'ec_new_optionitem_option_id' ) ).val( result.option_id );
			jQuery( document.getElementById( 'ec_new_optionitem_option_type' ) ).val( ec_admin_get_value( 'ec_new_option_type', 'select' ) );
			jQuery( document.getElementById( 'ec_new_optionitem_sort_order' ) ).val( '0' );
			jQuery( document.getElementById( 'ec_new_option_type' ) ).val( '0' ).trigger( 'change' );
			jQuery( document.getElementById( 'ec_new_option_name' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_option_label' ) ).val( '' );

			if ( typeof wp_easycart_pro_add_new_basic_option_insert == 'function' ) {
				wp_easycart_pro_add_new_basic_option_insert( result.option_id );
			}

			ec_admin_hide_loader( 'ec_admin_new_optionset_display_loader' );
			wp_easycart_admin_close_slideout( 'new_option_box' );
			wp_easycart_admin_open_slideout( 'new_optionitem_box' );
		} } );

	}

	return false;

}

function ec_admin_save_new_optionitem( add_another ){

	var errors = 0;
	if( jQuery( document.getElementById( 'ec_new_optionitem_name' ) ).val( ) == '' ){
		errors = true;
		jQuery( document.getElementById( 'ec_new_optionitem_name' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
	}else{
		jQuery( document.getElementById( 'ec_new_optionitem_name' ) ).removeClass( 'ec_admin_field_error' );
	}

	if( !errors ){

		jQuery( document.getElementById( "ec_admin_new_optionitem_display_loader" ) ).fadeIn( 'fast' );

		var data = {
			action: 'ec_admin_ajax_save_new_optionitem',
			ec_new_optionitem_option_id: ec_admin_get_value( 'ec_new_optionitem_option_id', 'text' ),
			ec_new_optionitem_sort_order: ec_admin_get_value( 'ec_new_optionitem_sort_order', 'text' ),
			ec_new_optionitem_name: ec_admin_get_value( 'ec_new_optionitem_name', 'text' ),
			ec_new_optionitem_model_number_extension: ec_admin_get_value( 'ec_new_optionitem_model_number_extension', 'text' ),
			ec_new_optionitem_price_adjustment: ec_admin_get_value( 'ec_new_optionitem_price_adjustment', 'text' ),
			ec_new_optionitem_weight_adjustment: ec_admin_get_value( 'ec_new_optionitem_weight_adjustment', 'text' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_optionitem_quick_edit_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 

			jQuery( document.getElementById( 'ec_new_optionitem_sort_order' ) ).val( Number( jQuery( document.getElementById( 'ec_new_optionitem_sort_order' ) ).val( ) ) + 1 );
			jQuery( document.getElementById( 'ec_new_optionitem_name' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_optionitem_model_number_extension' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_optionitem_price_adjustment' ) ).val( '' );
			jQuery( document.getElementById( 'ec_new_optionitem_weight_adjustment' ) ).val( '' );

			ec_admin_hide_loader( 'ec_admin_new_optionitem_display_loader' );
			if( !add_another ){
				jQuery( document.getElementById( 'ec_new_optionitem_option_id' ) ).val( '' );
				jQuery( document.getElementById( 'ec_new_optionitem_option_type' ) ).val( '' );
				jQuery( document.getElementById( 'ec_new_optionitem_sort_order' ) ).val( '0' );
				wp_easycart_admin_close_slideout( 'new_optionitem_box' );
			}

			if ( typeof wp_easycart_pro_refresh_options == 'function' ) {
				wp_easycart_pro_refresh_options();
			}
		} } );

	}

	return false;
}

function ec_admin_save_product_list_display_options( ){

	jQuery( document.getElementById( "ec_admin_product_list_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_list_display',
		ec_option_show_sort_box: ec_admin_get_value( 'ec_option_show_sort_box', 'checkbox' ),
		ec_option_default_store_filter: ec_admin_get_value( 'ec_option_default_store_filter', 'select' ),
		ec_option_product_filter_0: ec_admin_get_value( 'ec_option_product_filter_0', 'checkbox' ),
		ec_option_product_filter_1: ec_admin_get_value( 'ec_option_product_filter_1', 'checkbox' ),
		ec_option_product_filter_2: ec_admin_get_value( 'ec_option_product_filter_2', 'checkbox' ),
		ec_option_product_filter_3: ec_admin_get_value( 'ec_option_product_filter_3', 'checkbox' ),
		ec_option_product_filter_4: ec_admin_get_value( 'ec_option_product_filter_4', 'checkbox' ),
		ec_option_product_filter_5: ec_admin_get_value( 'ec_option_product_filter_5', 'checkbox' ),
		ec_option_product_filter_6: ec_admin_get_value( 'ec_option_product_filter_6', 'checkbox' ),
		ec_option_product_filter_7: ec_admin_get_value( 'ec_option_product_filter_7', 'checkbox' ),
		ec_option_short_description_on_product: ec_admin_get_value( 'ec_option_short_description_on_product', 'checkbox' ),
		ec_option_show_featured_categories: ec_admin_get_value( 'ec_option_show_featured_categories', 'checkbox' ),
		ec_option_enable_product_paging: ec_admin_get_value( 'ec_option_enable_product_paging', 'checkbox' ),
		ec_option_hide_out_of_stock: ec_admin_get_value( 'ec_option_hide_out_of_stock', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_list_display_loader' );
	} } );

	return false;
}

function ec_admin_save_customer_review_display_options( ){
	jQuery( document.getElementById( "ec_admin_customer_review_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_customer_review_display',
		ec_option_customer_review_require_login: ec_admin_get_value( 'ec_option_customer_review_require_login', 'checkbox' ),
		ec_option_customer_review_show_user_name: ec_admin_get_value( 'ec_option_customer_review_show_user_name', 'checkbox' ),
		ec_option_customer_review_notification: ec_admin_get_value( 'ec_option_customer_review_notification', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_customer_review_display_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_display_options( ){
	jQuery( document.getElementById( "ec_admin_product_details_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_display',
		ec_option_model_number_extension: ec_admin_get_value( 'ec_option_model_number_extension', 'text' ),
		ec_option_product_details_sizing: ec_admin_get_value( 'ec_option_product_details_sizing', 'select' ),
		ec_option_show_breadcrumbs: ec_admin_get_value( 'ec_option_show_breadcrumbs', 'checkbox' ),
		ec_option_show_magnification: ec_admin_get_value( 'ec_option_show_magnification', 'checkbox' ),
		ec_option_show_large_popup: ec_admin_get_value( 'ec_option_show_large_popup', 'checkbox' ),
		ec_option_show_model_number: ec_admin_get_value( 'ec_option_show_model_number', 'checkbox' ),
		ec_option_show_categories: ec_admin_get_value( 'ec_option_show_categories', 'checkbox' ),
		ec_option_show_manufacturer: ec_admin_get_value( 'ec_option_show_manufacturer', 'checkbox' ),
		ec_option_show_stock_quantity: ec_admin_get_value( 'ec_option_show_stock_quantity', 'checkbox' ),
		ec_option_use_facebook_icon: ec_admin_get_value( 'ec_option_use_facebook_icon', 'checkbox' ),
		ec_option_use_twitter_icon: ec_admin_get_value( 'ec_option_use_twitter_icon', 'checkbox' ),
		ec_option_use_delicious_icon: ec_admin_get_value( 'ec_option_use_delicious_icon', 'checkbox' ),
		ec_option_use_myspace_icon: ec_admin_get_value( 'ec_option_use_myspace_icon', 'checkbox' ),
		ec_option_use_linkedin_icon: ec_admin_get_value( 'ec_option_use_linkedin_icon', 'checkbox' ),
		ec_option_use_email_icon: ec_admin_get_value( 'ec_option_use_email_icon', 'checkbox' ),
		ec_option_use_digg_icon: ec_admin_get_value( 'ec_option_use_digg_icon', 'checkbox' ),
		ec_option_use_googleplus_icon: ec_admin_get_value( 'ec_option_use_googleplus_icon', 'checkbox' ),
		ec_option_use_pinterest_icon: ec_admin_get_value( 'ec_option_use_pinterest_icon', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_details_display_loader' );
	} } );

	return false;
}

function ec_admin_save_price_display_options( ){
	jQuery( document.getElementById( "ec_admin_price_display_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_price_display',
		ec_option_hide_price_seasonal: ec_admin_get_value( 'ec_option_hide_price_seasonal', 'checkbox' ),
		ec_option_hide_price_inquiry: ec_admin_get_value( 'ec_option_hide_price_inquiry', 'checkbox' ),
		ec_option_show_multiple_vat_pricing: ec_admin_get_value( 'ec_option_show_multiple_vat_pricing', 'checkbox' ),
		ec_option_tiered_price_format: ec_admin_get_value( 'ec_option_tiered_price_format', 'checkbox' ),
		ec_option_tiered_price_by_option: ec_admin_get_value( 'ec_option_tiered_price_by_option', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_price_display_options_loader' );
	} } );

	return false;
}

function ec_admin_save_inventory_options( ){
	jQuery( document.getElementById( "ec_admin_inventory_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_inventory_options',
		ec_option_stock_removed_in_cart: ec_admin_get_value( 'ec_option_stock_removed_in_cart', 'checkbox' ),
		ec_option_tempcart_stock_hours: ec_admin_get_value( 'ec_option_tempcart_stock_hours', 'text' ),
		ec_option_tempcart_stock_timeframe: ec_admin_get_value( 'ec_option_tempcart_stock_timeframe', 'select' ),
		ec_option_enable_inventory_notification: ec_admin_get_value( 'ec_option_enable_inventory_notification', 'checkbox' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_settings_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_inventory_options_loader' );
	} } );

	return false;
}

/* PRODUCT DETAILS FUNCTIONS */
function ec_admin_save_product_details_basic( ){
	if( ec_admin_save_product_details_basic_validate( ) ){
		jQuery( document.getElementById( "ec_admin_product_details_basic_loader" ) ).fadeIn( 'fast' );
		if( typeof tinymce !== 'undefined' && tinymce.editors.description && !jQuery( document.getElementById( 'description' ) ).is( ':visible' ) ){
			description = tinymce.editors.description.getContent( );
		}else{
			description = jQuery( document.getElementById( 'description' ) ).val( );
		}

		var data = {
			action: 'ec_admin_ajax_save_product_details_basic',
			product_id: ec_admin_get_value( 'product_id', 'hidden' ),
			activate_in_store: ec_admin_get_value( 'activate_in_store', 'checkbox' ),
			title: ec_admin_get_value( 'title', 'text' ),
			post_slug: ec_admin_get_value( 'post_slug', 'text' ),
			model_number: ec_admin_get_value( 'model_number', 'text' ),
			manufacturer_id: ec_admin_get_value( 'manufacturer_id', 'text' ),
			price: ec_admin_get_value( 'price', 'text' ),
			description: description,
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			var result = JSON.parse( data );
			if( jQuery( document.getElementById( 'product_id' ) ).val( ) == '0' && data != '' && data != '0' ){
				jQuery( document.getElementById( 'product_title' ) ).html( wp_easycart_products_language['edit-product'] );
				jQuery( document.getElementById( 'product_create_button' ) ).val( 'Update Product' );
				jQuery( document.getElementById( 'ec_admin_product_details_view_product_link' ) ).show( );
				jQuery( document.getElementById( 'ec_admin_product_details_add_new_button' ) ).show( );
				jQuery( document.getElementById( 'ec_admin_row_post_slug' ) ).show( );
				jQuery( document.getElementById( 'product_id' ) ).val( result.product_id );
				jQuery( '.ec_admin_flex_row' ).each( function( ){
					jQuery( this ).removeClass( 'ec_admin_hidden' );
				} );
			}
			var new_link = result.link;
			jQuery( document.getElementById( 'ec_admin_product_details_view_product_link' ) ).attr( 'href', new_link );
			jQuery( document.getElementById( 'post_slug' ) ).val( result.post_slug );
			if( ec_admin_get_value( 'activate_in_store', 'checkbox' ) == '1' ){
				jQuery( document.getElementById( 'ec_admin_product_activate_error' ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_admin_product_activate_error' ) ).show( );
			}
			ec_admin_hide_loader( 'ec_admin_product_details_basic_loader' );
		} } );
	}
	return false;
}
function ec_admin_save_product_details_basic_validate( ){
	var errors = false;
	if( !wpeasycart_admin_validate_text_field( jQuery( document.getElementById( 'title' ) ) ) )
		errors = true;
	if( !wpeasycart_admin_validate_model_number_field( jQuery( document.getElementById( 'model_number' ) ) ) || !has_valid_model_number )
		errors = true;
	if( !wpeasycart_admin_validate_select2_field( jQuery( document.getElementById( 'manufacturer_id' ) ) ) )
		errors = true;
	if( !wpeasycart_admin_validate_number_field( jQuery( document.getElementById( 'price' ) ) ) )
		errors = true;
	return !errors;
}

function advanced_options_change( field_id ){
	if( jQuery( document.getElementById( field_id ) ).is( ':checked' ) ){
		var confirm_adv_change = true;
		if( jQuery( document.getElementById( 'stock_quantity_type' ) ).val( ) == '2' ){
			confirm_adv_change = confirm( wp_easycart_products_language['advanced-option-note3'] );
		}
		if( !confirm_adv_change ){
			return false;

		}else{
			jQuery( document.getElementById( 'ec_admin_row_option1' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_row_option2' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_row_option3' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_row_option4' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_row_option5' ) ).hide( );
			jQuery( document.getElementById( 'ec_admin_row_advanced_options' ) ).show( );
			if( jQuery( document.getElementById( 'stock_quantity_type' ) ).val( ) == '2' ){
				jQuery( document.getElementById( 'stock_quantity_type' ) ).val( '1' ).trigger( 'change' );
			}
			ec_admin_save_product_details_options( );
		}
	}else{
		jQuery( document.getElementById( 'ec_admin_row_option1' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option2' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option3' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option4' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option5' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_advanced_options' ) ).hide( );
		ec_admin_save_product_details_options( );
	}
}

function optionitem_images_change( field_id ){
	if( jQuery( document.getElementById( 'use_advanced_optionset' ) ).is( ':checked' ) ){
		alert( wp_easycart_products_language['advaced-options-note4'] );
		return false;
	}else if( jQuery( document.getElementById( field_id ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_image1' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image1_preview' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image2' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image2_preview' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image3' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image3_preview' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image4' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image4_preview' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image5' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_image5_preview' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_optionitem_images' ) ).show( );
		ec_admin_save_product_details_images( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_image1' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image1_preview' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image2' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image2_preview' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image3' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image3_preview' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image4' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image4_preview' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image5' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_image5_preview' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_optionitem_images' ) ).hide( );
		ec_admin_save_product_details_images( );
	}
}

function ec_admin_product_details_option1_change( field ){
	var option1_value = jQuery( document.getElementById( field ) ).val( );
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	/* First Save the Option Data */
	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( '.ec_admin_opionitem_quantity_row' ).remove( );

		/* Now get updated HTML for Option Item Images */
		ec_admin_product_details_refresh_option_images();

		/* Now get updated HTML for Option Item Quantity Boxes */
		ec_admin_product_details_refresh_option_quantity();
	} } );

	return false;
}

function ec_admin_product_details_refresh_option_images() {
	jQuery( document.getElementById( "ec_admin_product_details_images_pro_loader" ) ).fadeIn( 'fast' );
	
	if( jQuery( document.getElementById( 'wpeasycart_product_images_pro' ) ).length ) {
		var data = {
			action: 'ec_admin_ajax_get_optionitem_images_content_pro',
			product_id: ec_admin_get_value( 'product_id', 'hidden' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'get', data: data, success: function(data){ 
			jQuery( document.getElementById( 'wpeasycart_product_images_pro' ) ).replaceWith( data );
			ec_admin_hide_loader( 'ec_admin_product_details_images_pro_loader' );
		} } );
	} else {
		var data = {
			action: 'ec_admin_ajax_get_optionitem_images_content',
			product_id: ec_admin_get_value( 'product_id', 'hidden' ),
			option_id: option1_value,
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
		};

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
			jQuery( document.getElementById( 'ec_admin_row_optionitem_images' ) ).html( data );
			ec_admin_hide_loader( 'ec_admin_product_details_images_pro_loader' );
		} } );
	}
}

function ec_admin_product_details_refresh_option_quantity() {
	var data = {
		action: 'ec_admin_ajax_get_optionitem_quantity_content',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_row_optionitem_quantity' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
	} } );
}

function ec_admin_product_details_option2_change( field ){
	var option2_value = jQuery( document.getElementById( field ) ).val( );
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	/* First Save the Option Data */
	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ /* More to do... */ } } );

	/* Now get updated HTML for Option Item Quantity Boxes */
	var data = {
		action: 'ec_admin_ajax_get_optionitem_quantity_content',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_row_optionitem_quantity' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
	} } );

	return false;
}

function ec_admin_product_details_option3_change( field ){
	var option3_value = jQuery( document.getElementById( field ) ).val( );
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	/* First Save the Option Data */
	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ /* More to do... */ } } );

	/* Now get updated HTML for Option Item Quantity Boxes */
	var data = {
		action: 'ec_admin_ajax_get_optionitem_quantity_content',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_row_optionitem_quantity' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
	} } );

	return false;
}

function ec_admin_product_details_option4_change( field ){
	var option4_value = jQuery( document.getElementById( field ) ).val( );
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	/* First Save the Option Data */
	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ /* More to do... */ } } );

	/* Now get updated HTML for Option Item Quantity Boxes */
	var data = {
		action: 'ec_admin_ajax_get_optionitem_quantity_content',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_row_optionitem_quantity' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
	} } );

	return false;
}

function ec_admin_product_details_option5_change( field ){
	var option5_value = jQuery( document.getElementById( field ) ).val( );
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	/* First Save the Option Data */
	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ /* More to do... */ } } );

	/* Now get updated HTML for Option Item Quantity Boxes */
	var data = {
		action: 'ec_admin_ajax_get_optionitem_quantity_content',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_row_optionitem_quantity' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_options( ){
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_advanced_optionset: ec_admin_get_value( 'use_advanced_optionset', 'checkbox' ),
		option1: ec_admin_get_value( 'option1', 'select' ),
		option2: ec_admin_get_value( 'option2', 'select' ),
		option3: ec_admin_get_value( 'option3', 'select' ),
		option4: ec_admin_get_value( 'option4', 'select' ),
		option5: ec_admin_get_value( 'option5', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );

		/* Now get updated HTML for Option Item Images */
		ec_admin_product_details_refresh_option_images();

		/* Now get updated HTML for Option Item Quantity Boxes */
		ec_admin_product_details_refresh_option_quantity();
	} } );

	return false;
}

function ec_admin_product_details_add_advanced_option( ){
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_add_advanced_option',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		option_id: ec_admin_get_value( 'add_new_advanced_option', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		jQuery( document.getElementById( 'advanced_options_holder' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
		jQuery( 'div#advanced_options_holder' ).sortable( 'refresh' );

		/* Now get updated HTML for Option Item Images */
		ec_admin_product_details_refresh_option_images();
	} } );

	return false;
}

function ec_admin_product_details_delete_advanced_option( option_to_product_id ){
	jQuery( document.getElementById( "ec_admin_product_details_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_delete_advanced_option',
		option_to_product_id: option_to_product_id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'advanced_options_holder' ) ).html( data );
		ec_admin_hide_loader( 'ec_admin_product_details_options_loader' );
		jQuery( 'div#advanced_options_holder' ).sortable( 'refresh' );

		/* Now get updated HTML for Option Item Images */
		ec_admin_product_details_refresh_option_images();
	} } );

	return false;
}

function ec_admin_save_product_details_images( ){
	jQuery( document.getElementById( "ec_admin_product_details_images_loader" ) ).fadeIn( 'fast' );
	var optionitem_images = Array( );
	jQuery( '#optionitems_images option' ).each( function( ) {
		optionitem_images.push( {
			optionitem_id: jQuery( this ).val( ),
			image1: jQuery( document.getElementById( 'image1_' + jQuery( this ).val( ) ) ).val( ),
			image2: jQuery( document.getElementById( 'image2_' + jQuery( this ).val( ) ) ).val( ),
			image3: jQuery( document.getElementById( 'image3_' + jQuery( this ).val( ) ) ).val( ),
			image4: jQuery( document.getElementById( 'image4_' + jQuery( this ).val( ) ) ).val( ),
			image5: jQuery( document.getElementById( 'image5_' + jQuery( this ).val( ) ) ).val( )
		} );
	} );

	var data = {
		action: 'ec_admin_ajax_save_product_details_images',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		use_optionitem_images: ec_admin_get_value( 'use_optionitem_images', 'checkbox' ),
		image1: ec_admin_get_value( 'image1', 'image' ),
		image2: ec_admin_get_value( 'image2', 'image' ),
		image3: ec_admin_get_value( 'image3', 'image' ),
		image4: ec_admin_get_value( 'image4', 'image' ),
		image5: ec_admin_get_value( 'image5', 'image' ),
		optionitem_images: optionitem_images,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_details_images_loader' );
	} } );

	return false;
}

function ec_admin_product_details_update_optionitem_images( ){
	jQuery( '#optionitem_images_holder > .ec_admin_optionitem_image_row' ).hide( );
	jQuery( document.getElementById( 'ec_admin_product_details_optionitem_image_row_' + jQuery( document.getElementById( 'optionitems_images' ) ).val( ) ) ).show( );
}

function product_details_update_menus( field ){
	if( field == 'menulevel1_id_1' ){
		jQuery( '#ec_admin_row_menulevel1_id_2 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel1_id_1' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel1_id_2 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel2.length; i++ ){
				if( menulevel2[i].parent_id == jQuery( document.getElementById( 'menulevel1_id_1' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel1_id_2 select' ).append( '<option value="' + menulevel2[i].id + '">' + menulevel2[i].text + '</option>' );
				}
			}
		}

	}else if( field == 'menulevel1_id_2' ){
		jQuery( '#ec_admin_row_menulevel1_id_3 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel1_id_2' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel1_id_3 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel3.length; i++ ){
				if( menulevel3[i].parent_id == jQuery( document.getElementById( 'menulevel1_id_2' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel1_id_3 select' ).append( '<option value="' + menulevel3[i].id + '">' + menulevel3[i].text + '</option>' );
				}
			}
		}

	}else if( field == 'menulevel2_id_1' ){
		jQuery( '#ec_admin_row_menulevel2_id_2 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel2_id_1' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel2_id_2 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel2.length; i++ ){
				if( menulevel2[i].parent_id == jQuery( document.getElementById( 'menulevel2_id_1' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel2_id_2 select' ).append( '<option value="' + menulevel2[i].id + '">' + menulevel2[i].text + '</option>' );
				}
			}
		}

	}else if( field == 'menulevel2_id_2' ){
		jQuery( '#ec_admin_row_menulevel2_id_3 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel2_id_2' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel2_id_3 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel3.length; i++ ){
				if( menulevel3[i].parent_id == jQuery( document.getElementById( 'menulevel2_id_2' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel2_id_3 select' ).append( '<option value="' + menulevel3[i].id + '">' + menulevel3[i].text + '</option>' );
				}
			}
		}

	}else if( field == 'menulevel3_id_1' ){
		jQuery( '#ec_admin_row_menulevel3_id_2 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel3_id_1' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel3_id_2 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel2.length; i++ ){
				if( menulevel2[i].parent_id == jQuery( document.getElementById( 'menulevel3_id_1' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel3_id_2 select' ).append( '<option value="' + menulevel2[i].id + '">' + menulevel2[i].text + '</option>' );
				}
			}
		}

	}else if( field == 'menulevel3_id_2' ){
		jQuery( '#ec_admin_row_menulevel3_id_3 select option' ).remove( );
		if( jQuery( document.getElementById( 'menulevel3_id_2' ) ).val( ) != '0' ){
			jQuery( '#ec_admin_row_menulevel3_id_3 select' ).append( '<option value="0">' + wp_easycart_products_language['none-selected'] + '</option>' );
			for( i=0; i<menulevel3.length; i++ ){
				if( menulevel3[i].parent_id == jQuery( document.getElementById( 'menulevel3_id_2' ) ).val( ) ){
					jQuery( '#ec_admin_row_menulevel3_id_3 select' ).append( '<option value="' + menulevel3[i].id + '">' + menulevel3[i].text + '</option>' );
				}
			}
		}

	}
}

function ec_admin_save_product_details_menus( ){
	jQuery( document.getElementById( "ec_admin_product_details_menus_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_menus',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		menulevel1_id_1: ec_admin_get_value( 'menulevel1_id_1', 'select' ),
		menulevel1_id_2: ec_admin_get_value( 'menulevel1_id_2', 'select' ),
		menulevel1_id_3: ec_admin_get_value( 'menulevel1_id_3', 'select' ),
		menulevel2_id_1: ec_admin_get_value( 'menulevel2_id_1', 'select' ),
		menulevel2_id_2: ec_admin_get_value( 'menulevel2_id_2', 'select' ),
		menulevel2_id_3: ec_admin_get_value( 'menulevel2_id_3', 'select' ),
		menulevel3_id_1: ec_admin_get_value( 'menulevel3_id_1', 'select' ),
		menulevel3_id_2: ec_admin_get_value( 'menulevel3_id_2', 'select' ),
		menulevel3_id_3: ec_admin_get_value( 'menulevel3_id_3', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_details_menus_loader' );
	} } );

	return false;
}

function ec_admin_product_details_add_category( ){
	jQuery( document.getElementById( "ec_admin_product_details_categories_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_add_category',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		category_id: ec_admin_get_value( 'add_new_category', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		if( jQuery( document.getElementById( 'ec_admin_no_categories' ) ) ){
			jQuery( document.getElementById( 'ec_admin_no_categories' ) ).remove( );
		}
		jQuery( document.getElementById( 'categories_holder' ) ).append( data );
		ec_admin_hide_loader( 'ec_admin_product_details_categories_loader' );
	} } );

	return false;
}

function ec_admin_product_details_delete_category( category_id ){
	jQuery( document.getElementById( "ec_admin_product_details_categories_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_delete_category',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		category_id: category_id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_product_details_category_row_' + category_id ) ).remove( );
		if( !jQuery( '#categories_holder > .ec_admin_category_row' ).length ){
			jQuery( document.getElementById( 'categories_holder' ) ).append( '<div id="ec_admin_no_categories">' + wp_easycart_products_language['product-not-category'] + '</div>' );
		}
		ec_admin_hide_loader( 'ec_admin_product_details_categories_loader' );
	} } );

	return false;
}

function ec_admin_product_details_quantity_type_change( field ){
	ec_admin_save_product_details_quantities( );
	if( jQuery( document.getElementById( 'stock_quantity_type' ) ).val( ) == '1' ){
		jQuery( document.getElementById( 'ec_admin_row_stock_quantity' ) ).show( );
	}else if( jQuery( document.getElementById( 'stock_quantity_type' ) ).val( ) == '2' ){
		if ( ! jQuery( '#wpeasycart_product_options_pro' ).length ) {
			alert( wp_easycart_products_language['optionitem-tracking-note'] );
			jQuery( document.getElementById( 'stock_quantity_type' ) ).val( 1 )
		}
		jQuery( document.getElementById( 'ec_admin_row_stock_quantity' ) ).hide( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_stock_quantity' ) ).hide( );
	}
}

function ec_admin_save_product_details_quantities( ){
	jQuery( document.getElementById( "ec_admin_product_details_quantities_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_quantities',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		stock_quantity_type: ec_admin_get_value( 'stock_quantity_type', 'select' ),
		stock_quantity: ec_admin_get_value( 'stock_quantity', 'number' ),
		min_purchase_quantity: ec_admin_get_value( 'min_purchase_quantity', 'number' ),
		max_purchase_quantity: ec_admin_get_value( 'max_purchase_quantity', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_product_details_quantities_loader' );
		if ( typeof wp_easycart_pro_get_updated_options == 'function' ) {
			wp_easycart_pro_get_updated_options();
		}
	} } );

	return false;
}

function ec_admin_product_details_add_optionitem_quantity( ){
	var errors = 0;
	if( jQuery( document.getElementById( 'add_new_optionitem_quantity_1' ) ).length && jQuery( document.getElementById( 'add_new_optionitem_quantity_1' ) ).val( ) == '0' ){
		jQuery( '#add_new_optionitem_quantity_1, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_1-container"]' ).addClass( 'ec_admin_field_error' );
		errors++;
	}else if( jQuery( document.getElementById( 'add_new_optionitem_quantity_1' ) ).length ){
		jQuery( '#add_new_optionitem_quantity_1, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_1-container"]' ).removeClass( 'ec_admin_field_error' );
	}
	if( jQuery( document.getElementById( 'add_new_optionitem_quantity_2' ) ).length && jQuery( document.getElementById( 'add_new_optionitem_quantity_2' ) ).val( ) == '0' ){
		jQuery( '#add_new_optionitem_quantity_2, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_2-container"]' ).addClass( 'ec_admin_field_error' );
		errors++;
	}else if( jQuery( document.getElementById( 'add_new_optionitem_quantity_2' ) ).length ){
		jQuery( '#add_new_optionitem_quantity_2, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_2-container"]' ).removeClass( 'ec_admin_field_error' );
	}
	if( jQuery( document.getElementById( 'add_new_optionitem_quantity_3' ) ).length && jQuery( document.getElementById( 'add_new_optionitem_quantity_3' ) ).val( ) == '0' ){
		jQuery( '#add_new_optionitem_quantity_3, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_3-container"]' ).addClass( 'ec_admin_field_error' );
		errors++;
	}else if( jQuery( document.getElementById( 'add_new_optionitem_quantity_3' ) ).length ){
		jQuery( '#add_new_optionitem_quantity_3, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_3-container"]' ).removeClass( 'ec_admin_field_error' );
	}
	if( jQuery( document.getElementById( 'add_new_optionitem_quantity_4' ) ).length && jQuery( document.getElementById( 'add_new_optionitem_quantity_4' ) ).val( ) == '0' ){
		jQuery( '#add_new_optionitem_quantity_4, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_4-container"]' ).addClass( 'ec_admin_field_error' );
		errors++;
	}else if( jQuery( document.getElementById( 'add_new_optionitem_quantity_4' ) ).length ){
		jQuery( '#add_new_optionitem_quantity_4, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_4-container"]' ).removeClass( 'ec_admin_field_error' );
	}
	if( jQuery( document.getElementById( 'add_new_optionitem_quantity_5' ) ).length && jQuery( document.getElementById( 'add_new_optionitem_quantity_5' ) ).val( ) == '0' ){
		jQuery( '#add_new_optionitem_quantity_5, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_5-container"]' ).addClass( 'ec_admin_field_error' );
		errors++;
	}else if( jQuery( document.getElementById( 'add_new_optionitem_quantity_5' ) ).length ){
		jQuery( '#add_new_optionitem_quantity_5, .select2-selection[aria-labelledby="select2-add_new_optionitem_quantity_5-container"]' ).removeClass( 'ec_admin_field_error' );
	}

	if( errors ){
		return false;
	}

	jQuery( document.getElementById( "ec_admin_product_details_quantities_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_add_optionitem_quantity',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		add_new_optionitem_quantity_1: ec_admin_get_value( 'add_new_optionitem_quantity_1', 'select' ),
		add_new_optionitem_quantity_2: ec_admin_get_value( 'add_new_optionitem_quantity_2', 'select' ),
		add_new_optionitem_quantity_3: ec_admin_get_value( 'add_new_optionitem_quantity_3', 'select' ),
		add_new_optionitem_quantity_4: ec_admin_get_value( 'add_new_optionitem_quantity_4', 'select' ),
		add_new_optionitem_quantity_5: ec_admin_get_value( 'add_new_optionitem_quantity_5', 'select' ),
		add_new_optionitem_quantity: ec_admin_get_value( 'add_new_optionitem_quantity', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		if( jQuery( document.getElementById( 'ec_admin_no_optionitem_quantities' ) ) ){
			jQuery( document.getElementById( 'ec_admin_no_optionitem_quantities' ) ).remove( );
		}
		jQuery( document.getElementById( 'ec_admin_product_details_optionitem_quantities_holder' ) ).append( data );
		ec_admin_hide_loader( 'ec_admin_product_details_quantities_loader' );
	} } );

	return false;
}

function ec_admin_product_details_update_optionitem_quantity( optionitemquantity_id ){
	var save_ele = jQuery( document.getElementById( 'ec_admin_product_details_optionitem_quantity_row_' + optionitemquantity_id ) ).find( '.dashicons-yes' );
	save_ele.removeClass( 'dashicons-yes' ).addClass( 'dashicons-image-rotate' );

	var data = {
		action: 'ec_admin_ajax_product_details_update_optionitem_quantity',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: optionitemquantity_id,
		quantity: ec_admin_get_value( 'optionitem_quantity_' + optionitemquantity_id, 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		var new_stock_quantity = 0;
		jQuery( '.ec_admin_opionitem_quantity_row > input' ).each( function( ){
			new_stock_quantity += Number( jQuery( this ).val( ) );
		} );
		jQuery( document.getElementById( 'stock_quantity' ) ).val( new_stock_quantity );
		save_ele.addClass( 'dashicons-yes' ).removeClass( 'dashicons-image-rotate' );
	} } );

	return false;
}

function ec_admin_product_details_delete_optionitem_quantity( optionitemquantity_id ){
	jQuery( document.getElementById( "ec_admin_product_details_quantities_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_delete_optionitem_quantity',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		optionitemquantity_id: optionitemquantity_id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_product_details_optionitem_quantity_row_' + optionitemquantity_id ) ).remove( );
		if( !jQuery( '#ec_admin_product_details_optionitem_quantities_holder > .ec_admin_opionitem_quantity_row' ).length ){
			jQuery( document.getElementById( 'ec_admin_product_details_optionitem_quantities_holder' ) ).append( '<div id="ec_admin_no_optionitem_quantities">' + wp_easycart_products_language['no-option-item-quantities'] + '</div>' );
		}
		var new_stock_quantity = 0;
		jQuery( '.ec_admin_opionitem_quantity_row > input' ).each( function( ){
			new_stock_quantity += Number( jQuery( this ).val( ) );
		} );
		jQuery( document.getElementById( 'stock_quantity' ) ).val( new_stock_quantity );
		ec_admin_hide_loader( 'ec_admin_product_details_quantities_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_pricing( ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_pricing',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		list_price: ec_admin_get_value( 'list_price', 'number' ),
		login_for_pricing: ec_admin_get_value( 'login_for_pricing', 'checkbox' ),
		login_for_pricing_user_level: ec_admin_get_value( 'login_for_pricing_user_level', 'select' ),
		login_for_pricing_label: ec_admin_get_value( 'login_for_pricing_label', 'text' ),
		show_custom_price_range: ec_admin_get_value( 'show_custom_price_range', 'checkbox' ),
		price_range_low: ec_admin_get_value( 'price_range_low', 'number' ),
		price_range_high: ec_admin_get_value( 'price_range_high', 'number' ),
		enable_price_label: ec_admin_get_value( 'enable_price_label', 'select' ),
		replace_price_label: ec_admin_get_value( 'replace_price_label', 'checkbox' ),
		custom_price_label: ec_admin_get_value( 'custom_price_label', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_product_details_add_price_tier( ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_add_price_tier',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		ec_admin_new_price_tier_quantity: ec_admin_get_value( 'ec_admin_new_price_tier_quantity', 'number' ),
		ec_admin_new_price_tier_price: ec_admin_get_value( 'ec_admin_new_price_tier_price', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		if( jQuery( document.getElementById( 'ec_admin_no_price_tiers' ) ) ){
			jQuery( document.getElementById( 'ec_admin_no_price_tiers' ) ).remove( );
		}
		jQuery( document.getElementById( 'price_tiers_holder' ) ).append( data );
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_product_details_edit_price_tier( pricetier_id ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_update_price_tier',
		pricetier_id: pricetier_id,
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		quantity: ec_admin_get_value( 'ec_admin_product_details_price_tier_row_' + pricetier_id + '_quantity', 'number' ),
		price: ec_admin_get_value( 'ec_admin_product_details_price_tier_row_' + pricetier_id + '_price', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' ),
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_product_details_delete_price_tier( pricetier_id ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_delete_price_tier',
		pricetier_id: pricetier_id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_product_details_price_tier_row_' + pricetier_id ) ).remove( );
		if( !jQuery( '#price_tiers_holder > .ec_admin_price_tier_row' ).length ){
			jQuery( document.getElementById( 'price_tiers_holder' ) ).append( '<div id="ec_admin_no_price_tiers">' + wp_easycart_products_language['no-volume-pricing'] + '</div>' );
		}
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_product_details_add_role_price( ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_add_role_price',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		add_new_role_price_role: ec_admin_get_value( 'add_new_role_price_role', 'select' ),
		ec_admin_new_role_price: ec_admin_get_value( 'ec_admin_new_role_price', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' ),
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		if( jQuery( document.getElementById( 'ec_admin_no_role_prices' ) ) ){
			jQuery( document.getElementById( 'ec_admin_no_role_prices' ) ).remove( );
		}
		jQuery( document.getElementById( 'role_prices_holder' ) ).append( data );
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_product_details_delete_role_price( roleprice_id ){
	jQuery( document.getElementById( "ec_admin_product_details_pricing_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_delete_role_price',
		roleprice_id: roleprice_id,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_product_details_role_price_row_' + roleprice_id ) ).remove( );
		if( !jQuery( '#role_prices_holder > .ec_admin_role_price_row' ).length ){
			jQuery( document.getElementById( 'role_prices_holder' ) ).append( '<div id="ec_admin_no_role_prices">' + wp_easycart_products_language['no-b2b-pricing'] + '</div>' );
		}
		ec_admin_hide_loader( 'ec_admin_product_details_pricing_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_packaging( ){
	jQuery( document.getElementById( "ec_admin_product_details_packaging_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_packaging',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		weight: ec_admin_get_value( 'weight', 'number' ),
		width: ec_admin_get_value( 'width', 'number' ),
		height: ec_admin_get_value( 'height', 'number' ),
		length: ec_admin_get_value( 'length', 'number' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_packaging_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_shipping( ){
	jQuery( document.getElementById( "ec_admin_product_details_shipping_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_shipping',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		is_shippable: ec_admin_get_value( 'is_shippable', 'checkbox' ),
		exclude_shippable_calculation: ec_admin_get_value( 'exclude_shippable_calculation', 'checkbox' ),
		ship_to_billing: ec_admin_get_value( 'ship_to_billing', 'checkbox' ),
		allow_backorders: ec_admin_get_value( 'allow_backorders', 'checkbox' ),
		backorder_fill_date: ec_admin_get_value( 'backorder_fill_date', 'text' ),
		handling_price: ec_admin_get_value( 'handling_price', 'number' ),
		handling_price_each: ec_admin_get_value( 'handling_price_each', 'number' ),
		shipping_restriction: ec_admin_get_value( 'shipping_restriction', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_shipping_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_short_description( ){
	jQuery( document.getElementById( "ec_admin_product_details_short_description_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_short_description',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		short_description: ec_admin_get_value( 'short_description', 'textarea' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_short_description_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_specifications( ){
	jQuery( document.getElementById( "ec_admin_product_details_specifications_loader" ) ).fadeIn( 'fast' );
	if( typeof tinymce !== 'undefined' && tinymce.editors.specifications && !jQuery( document.getElementById( 'specifications' ) ).is( ':visible' ) ){
		specifications = tinymce.editors.specifications.getContent( );
	}else{
		specifications = jQuery( document.getElementById( 'specifications' ) ).val( );
	}

	var data = {
		action: 'ec_admin_ajax_save_product_details_specifications',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		specifications: specifications,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_specifications_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_order_completed_note( ){
	jQuery( document.getElementById( "ec_admin_product_details_ordercompleted_loader" ) ).fadeIn( 'fast' );
	if( typeof tinymce !== 'undefined' && tinymce.editors.order_completed_note && !jQuery( document.getElementById( 'order_completed_note' ) ).is( ':visible' ) ){
		order_completed_note = tinymce.editors.order_completed_note.getContent( );
	}else{
		order_completed_note = jQuery( document.getElementById( 'order_completed_note' ) ).val( );
	}

	var data = {
		action: 'ec_admin_ajax_save_product_details_order_completed_note',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		order_completed_note: order_completed_note,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_ordercompleted_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_order_completed_email_note( ){
	jQuery( document.getElementById( "ec_admin_product_details_ordercompleted_email_loader" ) ).fadeIn( 'fast' );
	if( typeof tinymce !== 'undefined' && tinymce.editors.order_completed_email_note && !jQuery( document.getElementById( 'order_completed_email_note' ) ).is( ':visible' ) ){
		order_completed_email_note = tinymce.editors.order_completed_email_note.getContent( );
	}else{
		order_completed_email_note = jQuery( document.getElementById( 'order_completed_email_note' ) ).val( );
	}

	var data = {
		action: 'ec_admin_ajax_save_product_details_order_completed_email_note',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		order_completed_email_note: order_completed_email_note,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_ordercompleted_email_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_order_completed_details_note( ){
	jQuery( document.getElementById( "ec_admin_product_details_ordercompleted_details_loader" ) ).fadeIn( 'fast' );
	if( typeof tinymce !== 'undefined' && tinymce.editors.order_completed_details_note && !jQuery( document.getElementById( 'order_completed_details_note' ) ).is( ':visible' ) ){
		order_completed_details_note = tinymce.editors.order_completed_details_note.getContent( );
	}else{
		order_completed_details_note = jQuery( document.getElementById( 'order_completed_details_note' ) ).val( );
	}

	var data = {
		action: 'ec_admin_ajax_save_product_details_order_completed_details_note',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		order_completed_details_note: order_completed_details_note,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_ordercompleted_details_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_tags( ){
	jQuery( document.getElementById( "ec_admin_product_details_tags_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_tags',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		tag_type: ec_admin_get_value( 'tag_type', 'select' ),
		tag_text: ec_admin_get_value( 'tag_text', 'text' ),
		tag_bg_color: ec_admin_get_value( 'tag_bg_color', 'text' ),
		tag_text_color: ec_admin_get_value( 'tag_text_color', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_tags_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_featured_products( ){
	jQuery( document.getElementById( "ec_admin_product_details_featured_products_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_featured_products',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		featured_product_id_1: ec_admin_get_value( 'featured_product_id_1', 'select' ),
		featured_product_id_2: ec_admin_get_value( 'featured_product_id_2', 'select' ),
		featured_product_id_3: ec_admin_get_value( 'featured_product_id_3', 'select' ),
		featured_product_id_4: ec_admin_get_value( 'featured_product_id_4', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_featured_products_loader' );
	} } );

	return false;
}

function ec_admin_product_details_inquiry_change( field ){
	if( jQuery( document.getElementById( field ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_inquiry_url' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_inquiry_url' ) ).hide( );
		jQuery( document.getElementById( 'inquiry_url' ) ).val( '' );
	}
}

function ec_admin_save_product_details_general_options( ){
	jQuery( document.getElementById( "ec_admin_product_details_general_options_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_general_options',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		show_on_startup: ec_admin_get_value( 'show_on_startup', 'checkbox' ),
		is_special: ec_admin_get_value( 'is_special', 'checkbox' ),
		use_customer_reviews: ec_admin_get_value( 'use_customer_reviews', 'checkbox' ),
		is_donation: ec_admin_get_value( 'is_donation', 'checkbox' ),
		is_giftcard: ec_admin_get_value( 'is_giftcard', 'checkbox' ),
		inquiry_mode: ec_admin_get_value( 'inquiry_mode', 'checkbox' ),
		inquiry_url: ec_admin_get_value( 'inquiry_url', 'text' ),
		catalog_mode: ec_admin_get_value( 'catalog_mode', 'checkbox' ),
		catalog_mode_phrase: ec_admin_get_value( 'catalog_mode_phrase', 'text' ),
		role_id: ec_admin_get_value( 'role_id', 'select' ),
		sort_position: ec_admin_get_value( 'sort_position', 'text' ),
		mailerlite_group_name: ( jQuery( document.getElementById( 'mailerlite_group_name' ) ).length ) ? jQuery( document.getElementById( 'mailerlite_group_name' ) ).val() : '',
		activecampaign_group_name: ( jQuery( document.getElementById( 'activecampaign_group_name' ) ).length ) ? jQuery( document.getElementById( 'activecampaign_group_name' ) ).val() : '',
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_general_options_loader' );
		if( ec_admin_get_value( 'show_on_startup', 'checkbox' ) == '1' ){
			jQuery( document.getElementById( 'ec_admin_product_store_startup_error' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_admin_product_store_startup_error' ) ).show( );
		}
	} } );

	return false;
}

function ec_admin_save_product_details_tax( ){
	jQuery( document.getElementById( "ec_admin_product_details_tax_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_tax',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		is_taxable: ec_admin_get_value( 'is_taxable', 'checkbox' ),
		vat_rate: ec_admin_get_value( 'vat_rate', 'checkbox' ),
		TIC: ec_admin_get_value( 'TIC', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_tax_loader' );
	} } );

	return false;
}

function ec_admin_product_details_deconetwork_toggle( field ){
	if( jQuery( document.getElementById( field ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_mode' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_product_id' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_size_id' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_color_id' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_design_id' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_mode' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_product_id' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_size_id' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_color_id' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_deconetwork_design_id' ) ).hide( );
	}
}

function ec_admin_save_product_details_deconetwork( ){
	jQuery( document.getElementById( "ec_admin_product_details_deconetwork_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_deconetwork',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		is_deconetwork: ec_admin_get_value( 'is_deconetwork', 'checkbox' ),
		deconetwork_mode: ec_admin_get_value( 'deconetwork_mode', 'select' ),
		deconetwork_product_id: ec_admin_get_value( 'deconetwork_product_id', 'text' ),
		deconetwork_size_id: ec_admin_get_value( 'deconetwork_size_id', 'text' ),
		deconetwork_color_id: ec_admin_get_value( 'deconetwork_color_id', 'text' ),
		deconetwork_design_id: ec_admin_get_value( 'deconetwork_design_id', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_deconetwork_loader' );
	} } );

	return false;
}

function ec_admin_product_details_subscription_change( field ){
	if( jQuery( document.getElementById( field ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_subscription_interval' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_enable_duration' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_bill_duration' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_shipping_recurring' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_trial_period_days' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_signup_fee' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_allow_multiple_subscription_purchases' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_prorate' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_plan_id' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_membership_page' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_subscription_interval' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_enable_duration' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_bill_duration' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_shipping_recurring' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_trial_period_days' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_signup_fee' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_allow_multiple_subscription_purchases' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_prorate' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_subscription_plan_id' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_membership_page' ) ).hide( );
	}
}

function ec_admin_product_details_billing_duration_toggle( field ) {
	if ( '0' == jQuery( document.getElementById( 'enable_duration' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_subscription_bill_duration' ) ).hide();
		jQuery( document.getElementById( 'subscription_bill_duration' ) ).val( '0' );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_subscription_bill_duration' ) ).show();
	}
}

function ec_admin_save_product_details_subscription( ){
	jQuery( document.getElementById( "ec_admin_product_details_subscription_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_subscription',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		is_subscription_item: ec_admin_get_value( 'is_subscription_item', 'checkbox' ),
		subscription_bill_length: ec_admin_get_value( 'subscription_bill_length', 'select' ),
		subscription_bill_period: ec_admin_get_value( 'subscription_bill_period', 'select' ),
		subscription_bill_duration: ec_admin_get_value( 'subscription_bill_duration', 'text' ),
		subscription_shipping_recurring: ec_admin_get_value( 'subscription_shipping_recurring', 'text' ),
		trial_period_days: ec_admin_get_value( 'trial_period_days', 'text' ),
		subscription_signup_fee: ec_admin_get_value( 'subscription_signup_fee', 'text' ),
		allow_multiple_subscription_purchases: ec_admin_get_value( 'allow_multiple_subscription_purchases', 'checkbox' ),
		subscription_prorate: ec_admin_get_value( 'subscription_prorate', 'checkbox' ),
		subscription_plan_id: ec_admin_get_value( 'subscription_plan_id', 'select' ),
		membership_page: ec_admin_get_value( 'membership_page', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_subscription_loader' );
	} } );

	return false;
}

function ec_admin_save_product_details_seo( ){
	jQuery( document.getElementById( "ec_admin_product_details_seo_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_seo',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		seo_description: ec_admin_get_value( 'seo_description', 'textarea' ),
		seo_keywords: ec_admin_get_value( 'seo_keywords', 'textarea' ),
		post_excerpt: ec_admin_get_value( 'post_excerpt', 'text' ),
		featured_image: ec_admin_get_value( 'featured_image', 'text' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_seo_loader' );
	} } );

	return false;
}

function ec_admin_product_details_download_toggle( field ){
	if( jQuery( document.getElementById( field ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_is_amazon_download' ) ).show( );
		if( jQuery( document.getElementById( 'ec_admin_row_is_amazon_download' ) ).val( ) == '1' ){
			jQuery( document.getElementById( 'ec_admin_row_amazon_key' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_admin_row_amazon_key' ) ).hide( );
		}
		if( jQuery( document.getElementById( 'ec_admin_row_is_amazon_download' ) ).val( ) == '1' ){
			jQuery( document.getElementById( 'ec_admin_row_download_file_name' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_admin_row_download_file_name' ) ).show( );
		}
		jQuery( document.getElementById( 'ec_admin_row_maximum_downloads_allowed' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_download_timelimit_seconds' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_is_amazon_download' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_amazon_key' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_download_file_name' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_maximum_downloads_allowed' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_download_timelimit_seconds' ) ).hide( );
	}
}

function ec_admin_product_details_download_location_toggle( field ){
	if( jQuery( document.getElementById( field ) ).val( ) == '0' ){
		jQuery( document.getElementById( 'ec_admin_row_amazon_key' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_download_file_name' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_download_file_name_preview' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_amazon_key' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_download_file_name' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_download_file_name_preview' ) ).hide( );
	}
}

function ec_admin_save_product_details_downloads( ){
	jQuery( document.getElementById( "ec_admin_product_details_downloads_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_save_product_details_downloads',
		product_id: ec_admin_get_value( 'product_id', 'hidden' ),
		is_download: ec_admin_get_value( 'is_download', 'checkbox' ),
		is_amazon_download: ec_admin_get_value( 'is_amazon_download', 'select' ),
		amazon_key: ec_admin_get_value( 'amazon_key', 'select' ),
		download_file_name: ec_admin_get_value( 'download_file_name', 'select' ),
		maximum_downloads_allowed: ec_admin_get_value( 'maximum_downloads_allowed', 'select' ),
		download_timelimit_seconds: ec_admin_get_value( 'download_timelimit_seconds', 'select' ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		ec_admin_hide_loader( 'ec_admin_product_details_downloads_loader' );
	} } );

	return false;
}

function ec_admin_product_details_add_new_manufacturer( ){
	jQuery( document.getElementById( "ec_admin_product_details_basic_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_details_insert_manufacturer',
		wp_easycart_nonce: ec_admin_get_value( 'manufacturer_new_nonce', 'text' ),
		manufacturer_name: ec_admin_get_value( 'manufacturer_name', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		var data_decoded = JSON.parse( data );
		jQuery( document.getElementById( 'manufacturer_id' ) ).append( '<option value="' + data_decoded.manufacturer_id + '">' + data_decoded.name + '</option>' );
		jQuery( document.getElementById( 'manufacturer_id' ) ).val( data_decoded.manufacturer_id );
		jQuery( document.getElementById( 'manufacturer_name' ) ).val( '' );
		ec_admin_hide_loader( 'ec_admin_product_details_basic_loader' );
	} } );

	return false;
}

function wp_easycart_show_product_stats( element ){
	var product_id = element.parent( ).parent( ).parent( ).attr( 'data-id' );
	var views = element.attr( 'data-views' );
	var stats_html = '<div class="ec_admin_stats_container" id="ec_admin_stats_container_' + product_id + '"><div class="ec_admin_stats_inner"><div class="ec_admin_product_stat1"><strong>' + wp_easycart_products_language['total-views'] + ': </strong>' + views + '</div></div></div>';
	element.append( stats_html );
}

function wp_easycart_hide_product_stats( element ){
	element.find( '.ec_admin_stats_container' ).remove( );
}

function show_custom_price_range( ){
	if( jQuery( document.getElementById( 'show_custom_price_range' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_price_range_low' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_price_range_high' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_price_range_low' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_price_range_high' ) ).hide( );
	}
}

function ec_admin_add_notify_subscriber( product_id ){
	var product_id = jQuery( document.getElementById( 'product_id' ) ).val( );
	var email = jQuery( document.getElementById( 'ec_notify_new_email' ) ).val( );
	var data = {
		action: 'ec_ajax_admin_subscribe_to_stock_notification',
		email: email,
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( document.getElementById( 'ec_notify_new_email' ) ).val( '' );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
	} } );
}

function ec_admin_delete_notify_subscriber( product_subscriber_id, product_id ){
	var data = {
		action: 'ec_ajax_admin_delete_stock_notification_item',
		product_subscriber_id: product_subscriber_id,
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
	} } );
}

function ec_admin_subscribe_notify_subscriber( product_subscriber_id, product_id ){
	var data = {
		action: 'ec_ajax_admin_subscribe_stock_notification_item',
		product_subscriber_id: product_subscriber_id,
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
	} } );
}

function ec_admin_unsubscribe_notify_subscriber( product_subscriber_id, product_id ){
	var data = {
		action: 'ec_ajax_admin_unsubscribe_stock_notification_item',
		product_subscriber_id: product_subscriber_id,
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
	} } );
}

function ec_admin_notify_subscriber( product_subscriber_id, product_id ){
	var data = {
		action: 'ec_ajax_admin_notify_subscriber',
		product_subscriber_id: product_subscriber_id,
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
		jQuery( document.getElementById( 'ec_admin_notify_success' ) ).show( ).delay( 2500 ).fadeOut( 'slow' );
	} } );
}

function ec_admin_notify_all_subscribers( product_id ){
	var data = {
		action: 'ec_ajax_admin_notify_all_subscribers',
		product_id: product_id
	};
	jQuery( '.ec_out_of_stock_notify_loader_cover' ).show( );
	jQuery( '.ec_out_of_stock_notify_loader' ).show( );
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		jQuery( '.ec_admin_stock_notification_view > table' ).replaceWith( data );
		jQuery( '.ec_out_of_stock_notify_loader_cover' ).hide( );
		jQuery( '.ec_out_of_stock_notify_loader' ).hide( );
		jQuery( document.getElementById( 'ec_admin_notify_success' ) ).show( ).delay( 2500 ).fadeOut( 'slow' );
	} } );
}

function show_required_user_level( ){
	if( jQuery( document.getElementById( 'login_for_pricing' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_admin_row_login_for_pricing_user_level' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_login_for_pricing_label' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_admin_row_login_for_pricing_user_level' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_login_for_pricing_label' ) ).hide( );
	}
}

function show_custom_price_label() {
	if( '0' != jQuery( document.getElementById( 'enable_price_label' ) ).val() ) {
		jQuery( document.getElementById( 'ec_admin_row_replace_price_label' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_custom_price_label' ) ).show( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_replace_price_label' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_custom_price_label' ) ).hide( );
	}
}

function wp_easycart_admin_clear_product_quick_edit( ){
	jQuery( document.getElementById( 'ec_qe_product_id' ) ).val( 0 );
	jQuery( document.getElementById( 'ec_quick_editproduct_status' ) ).val( '0' ).trigger( 'change' );
	jQuery( document.getElementById( 'ec_quick_editproduct_featured' ) ).val( '0' ).trigger( 'change' );
	jQuery( document.getElementById( 'ec_quick_editproduct_title' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_sku' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_manufacturer' ) ).val( '0' ).trigger( 'change' );
	jQuery( document.getElementById( 'ec_quick_editproduct_price' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_image' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( 0 );
	jQuery( document.getElementById( 'ec_quick_editproduct_stock_quantity' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_is_shippable' ) ).val( '0' );
	jQuery( document.getElementById( 'ec_quick_editproduct_weight' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_length' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_width' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_height' ) ).val( '' );
	jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( '0' );
	jQuery( '.ec_admin_quick_edit_product_basic_stock' ).hide( );
	jQuery( '.ec_admin_quick_edit_product_optionitem_stock' ).hide( );
	jQuery( document.getElementById( 'ec_admin_quick_edit_product_shipping_row' ) ).hide( );
}

function wp_easycart_open_product_quick_edit( product_id ){
	wp_easycart_admin_clear_product_quick_edit( );
	jQuery( document.getElementById( "ec_admin_product_quick_edit_display_loader" ) ).fadeIn( 'fast' );
	wp_easycart_admin_open_slideout( 'product_quick_edit_box' );

	var data = {
		action: 'ec_admin_ajax_get_product_quick_edit',
		product_id: product_id,
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		var json_data = JSON.parse( data );
		jQuery( document.getElementById( 'ec_qe_product_id' ) ).val( json_data.product_id );
		jQuery( document.getElementById( 'ec_quick_editproduct_status' ) ).val( json_data.activate_in_store ).trigger( 'change' );
		jQuery( document.getElementById( 'ec_quick_editproduct_featured' ) ).val( json_data.show_on_startup ).trigger( 'change' );
		jQuery( document.getElementById( 'ec_quick_editproduct_title' ) ).val( json_data.title );
		jQuery( document.getElementById( 'ec_quick_editproduct_sku' ) ).val( json_data.model_number );
		jQuery( document.getElementById( 'ec_quick_editproduct_manufacturer' ) ).val( json_data.manufacturer_id ).trigger( 'change' );
		jQuery( document.getElementById( 'ec_quick_editproduct_price' ) ).val( json_data.price );
		jQuery( document.getElementById( 'ec_quick_editproduct_list_price' ) ).val( json_data.list_price );
		jQuery( document.getElementById( 'ec_quick_editproduct_image' ) ).val( json_data.image1 );
		jQuery( document.getElementById( 'ec_quick_editproduct_sort_position' ) ).val( json_data.sort_position );
		if( json_data.use_optionitem_quantity_tracking == '1' ){
			jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( 2 ).trigger( 'change' );
			jQuery( '.ec_admin_quick_edit_product_optionitem_stock' ).show( );
		}else if( json_data.show_stock_quantity == '1' ){
			jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( 1 ).trigger( 'change' );
			jQuery( '.ec_admin_quick_edit_product_basic_stock' ).show( );
		}else{
			jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( 0 ).trigger( 'change' );
		}
		jQuery( document.getElementById( 'ec_quick_editproduct_stock_quantity' ) ).val( json_data.stock_quantity );
		jQuery( document.getElementById( 'ec_quick_editproduct_is_shippable' ) ).val( json_data.is_shippable ).trigger( 'change' );
		if( json_data.is_shippable ){
			jQuery( document.getElementById( 'ec_admin_quick_edit_product_shipping_row' ) ).show( );
		}
		jQuery( document.getElementById( 'ec_quick_editproduct_weight' ) ).val( json_data.weight );
		jQuery( document.getElementById( 'ec_quick_editproduct_length' ) ).val( json_data.length );
		jQuery( document.getElementById( 'ec_quick_editproduct_width' ) ).val( json_data.width );
		jQuery( document.getElementById( 'ec_quick_editproduct_height' ) ).val( json_data.height );
		if( json_data.is_taxable && json_data.vat_rate > 0 ){
			jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( 3 ).trigger( 'change' );
		}else if( json_data.is_taxable ){
			jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( 1 ).trigger( 'change' );
		}else if( json_data.vat_rate > 0 ){
			jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( 2 ).trigger( 'change' );
		}else{
			jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( 0 ).trigger( 'change' );
		}
		jQuery( document.getElementById( "ec_admin_product_quick_edit_display_loader" ) ).fadeOut( 'fast' );
	} } );

	return false;
}

function ec_admin_update_quick_product( goto_edit ){
	jQuery( document.getElementById( "ec_admin_product_quick_edit_display_loader" ) ).fadeIn( 'fast' );

	var data = {
		action: 'ec_admin_ajax_product_quick_update',
		product_id: jQuery( document.getElementById( 'ec_qe_product_id' ) ).val( ),
		activate_in_store: jQuery( document.getElementById( 'ec_quick_editproduct_status' ) ).val( ),
		show_on_startup: jQuery( document.getElementById( 'ec_quick_editproduct_featured' ) ).val( ),
		title: jQuery( document.getElementById( 'ec_quick_editproduct_title' ) ).val( ),
		model_number: jQuery( document.getElementById( 'ec_quick_editproduct_sku' ) ).val( ),
		manufacturer_id: jQuery( document.getElementById( 'ec_quick_editproduct_manufacturer' ) ).val( ),
		price: jQuery( document.getElementById( 'ec_quick_editproduct_price' ) ).val( ),
		list_price: jQuery( document.getElementById( 'ec_quick_editproduct_list_price' ) ).val( ),
		image1: jQuery( document.getElementById( 'ec_quick_editproduct_image' ) ).val( ),
		sort_position: jQuery( document.getElementById( 'ec_quick_editproduct_sort_position' ) ).val( ),
		stock_option: jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( ),
		stock_quantity: jQuery( document.getElementById( 'ec_quick_editproduct_stock_quantity' ) ).val( ),
		is_shippable: jQuery( document.getElementById( 'ec_quick_editproduct_is_shippable' ) ).val( ),
		weight: jQuery( document.getElementById( 'ec_quick_editproduct_weight' ) ).val( ),
		length: jQuery( document.getElementById( 'ec_quick_editproduct_length' ) ).val( ),
		width: jQuery( document.getElementById( 'ec_quick_editproduct_width' ) ).val( ),
		height: jQuery( document.getElementById( 'ec_quick_editproduct_height' ) ).val( ),
		is_taxable: jQuery( document.getElementById( 'ec_quick_editproduct_is_taxable' ) ).val( ),
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_quick_edit_nonce', 'text' )
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
		var json_data = JSON.parse( data );
		var prod_columns = jQuery( 'tr[data-id="' + json_data.product_id + '"] > td' );
		jQuery( 'tr[data-id="' + json_data.product_id + '"] > td > a' ).html( json_data.title );
		for( var i=0; i<prod_columns.length; i++ ){
			if( json_data.show_stock_quantity == '1' ){
				jQuery( prod_columns[1] ).html( json_data.stock_quantity );
			}else{
				jQuery( prod_columns[1] ).html( '∞' );
			}
			jQuery( prod_columns[2] ).html( json_data.unit_price_formatted );
			jQuery( prod_columns[3] ).html( json_data.model_number );
			if( json_data.activate_in_store == '1' ){
				jQuery( prod_columns[4] ).html( 'Yes' );
				jQuery( prod_columns[6] ).find( '.deactivate-product' ).html( '<a href="admin.php?&page=wp-easycart-products&subpage=products&product_id=' + json_data.product_id + '&ec_admin_form_action=deactivate-product" aria-label="' + wp_easycart_products_language['deactivate'] + '" title="' + wp_easycart_products_language['deactivate'] + '"><div class="dashicons-before dashicons-hidden"></div></a>' );
			}else{
				jQuery( prod_columns[4] ).html( 'No' );
				jQuery( prod_columns[6] ).find( '.deactivate-product' ).html( '<a href="admin.php?&page=wp-easycart-products&subpage=products&product_id=' + json_data.product_id + '&ec_admin_form_action=deactivate-product" aria-label="' + wp_easycart_products_language['activate'] + '" title="' + wp_easycart_products_language['activate'] + '"><div class="dashicons-before dashicons-visibility"></div></a>' );
			}
		}
		if( goto_edit ){
			window.location.href = 'admin.php?page=wp-easycart-products&subpage=products&product_id=' + json_data.product_id + '&ec_admin_form_action=edit';
		}else{
			jQuery( document.getElementById( "ec_admin_product_quick_edit_display_loader" ) ).fadeOut( 'fast' );
			wp_easycart_admin_close_slideout( 'product_quick_edit_box' );
		}
	} } );
}

function ec_admin_quick_edit_product_update_shipping_type( ){
	if( jQuery( document.getElementById( 'ec_quick_editproduct_is_shippable' ) ).val( ) == '0' ){
		jQuery( '.ec_admin_quick_edit_product_shipping_row' ).hide( );
	}else{
		jQuery( '.ec_admin_quick_edit_product_shipping_row' ).show( );
	}
}

function ec_admin_quick_edit_product_update_stock_option( ){
	if( jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( ) == '0' ){
		jQuery( '.ec_admin_quick_edit_product_basic_stock' ).hide( );
		jQuery( '.ec_admin_quick_edit_product_optionitem_stock' ).hide( );
	}else if( jQuery( document.getElementById( 'ec_quick_editproduct_stock_option' ) ).val( ) == '1' ){
		jQuery( '.ec_admin_quick_edit_product_basic_stock' ).show( );
		jQuery( '.ec_admin_quick_edit_product_optionitem_stock' ).hide( );
	}else{
		jQuery( '.ec_admin_quick_edit_product_basic_stock' ).hide( );
		jQuery( '.ec_admin_quick_edit_product_optionitem_stock' ).show( );
	}
}

function ec_admin_product_image_menu_open_free() {
	jQuery( '#wpeasycart_admin_product_image_add_basic_free' ).show();
}

function ec_admin_product_image_menu_close_free() {
	jQuery( '#wpeasycart_admin_product_image_add_basic_free' ).hide();
}
