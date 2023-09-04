// JavaScript Document
function ec_admin_ipad_landscape_preview( ){
	jQuery( document.getElementById( 'ec_admin_preview_content' ) ).removeClass( 'ipad' ).removeClass( 'iphone' ).removeClass( 'landscape' ).removeClass( 'portrait' ).addClass( 'ipad' ).addClass( 'landscape' );
}

function ec_admin_ipad_portrait_preview( ){
	jQuery( document.getElementById( 'ec_admin_preview_content' ) ).removeClass( 'ipad' ).removeClass( 'iphone' ).removeClass( 'landscape' ).removeClass( 'portrait' ).addClass( 'ipad' ).addClass( 'portrait' );
}

function ec_admin_iphone_landscape_preview( ){
	jQuery( document.getElementById( 'ec_admin_preview_content' ) ).removeClass( 'ipad' ).removeClass( 'iphone' ).removeClass( 'landscape' ).removeClass( 'portrait' ).addClass( 'iphone' ).addClass( 'landscape' );
}

function ec_admin_iphone_portrait_preview( ){
	jQuery( document.getElementById( 'ec_admin_preview_content' ) ).removeClass( 'ipad' ).removeClass( 'iphone' ).removeClass( 'landscape' ).removeClass( 'portrait' ).addClass( 'iphone' ).addClass( 'portrait' );
}

function ec_admin_sort_products_open_click( ){
	// empty function for users with broken javascript
}

function ec_admin_close_video_screen( ){
	jQuery( document.getElementById( 'ec_admin_video_container' ) ).remove( );
}

function ec_product_editor_openclose( model_number ){
	
	jQuery( '.ec_product_editor' ).each( function( ){
		if( jQuery( this ).is( ':visible' ) ){
			var prev_model_number = jQuery( this ).attr( 'data-model-number' );
			if( ec_admin_changes_made( prev_model_number ) ){
				if( confirm( "Would you like to save the changes made to the last product editor? Press OK to save or CANCEL to discard." ) ){
					ec_admin_save_product_display( prev_model_number );
				}else{
					ec_admin_cancel_product_display( prev_model_number );
				}
			}else{
				ec_admin_cancel_product_display( prev_model_number );
			}
		}
	} );
	
	var editor = jQuery( document.getElementById( 'ec_product_editor_' + model_number ) );
	if( !editor.is( ':visible' ) ){
		editor.fadeIn( 220 );
	}else{
		if( ec_admin_changes_made( model_number ) ){
			if( confirm( "Would you like to save your changes? Press OK to save or CANCEL to discard." ) ){
				ec_admin_save_product_display( model_number );
			}else{
				ec_admin_cancel_product_display( model_number );
			}
		}else{
			editor.fadeOut( 220 );
			ec_admin_cancel_product_display( model_number );
		}
	}

}

function ec_admin_update_image_hover_effect( model_number ){
	
	ec_admin_change_made( model_number );
	
	var selected_val = jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).val( );
	var hover_classes = Array( '', 'ec_flip_container', 'ec_fade_container', 'ec_single_fade_container', 'ec_single_none_container', 'ec_single_grow_container', 'ec_single_shrink_container', 'ec_single_btw_container', 'ec_single_brighten_container', 'ec_slide_container', 'ec_flipbook' );
	
	jQuery( document.getElementById( 'ec_product_image_' + model_number ) ).find( '.ec_product_image_display_type ' ).removeClass( 'ec_flip_container' ).removeClass( 'ec_fade_container' ).removeClass( 'ec_single_fade_container' ).removeClass( 'ec_single_none_container' ).removeClass( 'ec_single_grow_container' ).removeClass( 'ec_single_shrink_container' ).removeClass( 'ec_single_btw_container' ).removeClass( 'ec_single_brighten_container' ).removeClass( 'ec_slide_container' ).removeClass( 'ec_flipbook' );
	jQuery( document.getElementById( 'ec_product_image_' + model_number ) ).find( '.ec_product_image_display_type ' ).addClass( hover_classes[ selected_val ] );
	
}

function ec_admin_update_image_effect_type( model_number ){
	
	ec_admin_change_made( model_number );
	
	var effect_type = jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).val( );
	jQuery( document.getElementById( 'ec_product_image_effect_' + model_number ) ).removeClass( 'ec_image_container_none' ).removeClass( 'ec_image_container_border' ).removeClass( 'ec_image_container_shadow' ).addClass( 'ec_image_container_' + effect_type );
	
}

function ec_admin_update_tag_type( model_number ){
	
	ec_admin_change_made( model_number );
	
	var selected_tag_type = jQuery( document.getElementById( "ec_product_tag_type_" + model_number ) ).val( );
	
	if( selected_tag_type == '1' )
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag1' ).show( );
	else
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag1' ).hide( );
	
	if( selected_tag_type == '2' )
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag2' ).show( );
	else
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag2' ).hide( );
	
	if( selected_tag_type == '3' )
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag3' ).show( );
	else
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag3' ).hide( );
		
	if( selected_tag_type == '4' )
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag4' ).show( );
	else
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag4' ).hide( );
		
}

function ec_admin_update_tag_text( model_number ){
	
	ec_admin_change_made( model_number );
	
	var tag_text = jQuery( document.getElementById( "ec_product_tag_text_" + model_number ) ).val( );
	
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag1' ).html( tag_text );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag2' ).find( 'span' ).html( tag_text );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag3' ).find( 'span' ).html( tag_text );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag4' ).find( 'span' ).html( tag_text );
	
}

function ec_admin_update_tag_color( model_number ){
	
	ec_admin_change_made( model_number );
	
	var tag_type = jQuery( document.getElementById( "ec_product_tag_type_" + model_number ) ).val( );
	var tag_color = jQuery( document.getElementById( "ec_product_tag_color_" + model_number ) ).val( );
	var tag_text_color = jQuery( document.getElementById( "ec_product_tag_text_color_" + model_number ) ).val( );
	
	if( tag_type == '1' )
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag1' ).attr( 'style', 'background: ' + tag_color + '!important; color: ' + tag_text_color + ";" );
	else
		jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag1' ).attr( 'style', 'background: ' + tag_color + '!important; color: ' + tag_text_color + '; display:none;' );
		
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag2' ).find( 'span' ).css( 'background-color', tag_color );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag2' ).find( 'span' ).css( 'color', tag_text_color );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag3' ).find( 'span' ).css( 'background-color', tag_color );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag3' ).find( 'span' ).css( 'color', tag_text_color );
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).parent( ).find( '.ec_tag4' ).find( 'span' ).css( 'color', tag_text_color );
	
}

function ec_admin_change_made( model_number ){
	jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).attr( 'data-changes-made', '1' );
}

function ec_admin_changes_made( model_number ){
	if( jQuery( document.getElementById( "ec_product_editor_" + model_number ) ).attr( 'data-changes-made' ) == '1' ){
		return true;
	}else{
		return false;
	}
}

function ec_admin_save_page_options( post_id, nonce ){
	
	jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).show( );
	jQuery( document.getElementById( "ec_admin_loader_bg" ) ).show( );	
	
	var data = {
		action: 'ec_ajax_save_page_options',
		post_id: post_id,
		product_type: jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ),
		use_quickview: jQuery( document.getElementById( 'ec_page_options_quick_view' ) ).val( ),
		dynamic_image_sizing: jQuery( document.getElementById( 'ec_page_option_dynamic_image_sizing' ) ).val( ),
		columns_smartphone: jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).val( ),
		image_height_smartphone: jQuery( document.getElementById( 'ec_page_options_image_height_smartphone' ) ).val( ) + 'px',
		columns_tablet: jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).val( ),
		image_height_tablet: jQuery( document.getElementById( 'ec_page_options_image_height_tablet' ) ).val( ) + 'px',
		columns_tablet_wide: jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).val( ),
		image_height_tablet_wide: jQuery( document.getElementById( 'ec_page_options_image_height_tablet_wide' ) ).val( ) + 'px',
		columns_laptop: jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).val( ),
		image_height_laptop: jQuery( document.getElementById( 'ec_page_options_image_height_laptop' ) ).val( ) + 'px',
		columns_desktop: jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( ),
		image_height_desktop: jQuery( document.getElementById( 'ec_page_options_image_height_desktop' ) ).val( ) + 'px',
		ec_option_details_main_color: jQuery( document.getElementById( 'ec_option_details_main_color' ) ).val( ),
		ec_option_details_second_color: jQuery( document.getElementById( 'ec_option_details_second_color' ) ).val( ),
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).hide( );
		jQuery( document.getElementById( "ec_admin_page_updated" ) ).show( ).delay( 1500 ).fadeOut( 'slow' );
		jQuery( document.getElementById( "ec_admin_loader_bg" ) ).fadeOut( 'slow' );
	} } );
	
}

function ec_admin_set_default_page_options( post_id, nonce ){
	
	jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).show( );
	jQuery( document.getElementById( "ec_admin_loader_bg" ) ).show( );
	
	var data = {
		action: 'ec_ajax_save_page_default_options',
		post_id: post_id,
		nonce: nonce,
		
		ec_option_default_dynamic_sizing: jQuery( document.getElementById( 'ec_page_option_dynamic_image_sizing' ) ).val( ),
		
		ec_option_details_main_color: jQuery( document.getElementById( 'ec_option_details_main_color' ) ).val( ),
		ec_option_details_second_color: jQuery( document.getElementById( 'ec_option_details_second_color' ) ).val( ),
		
		ec_option_default_product_type: jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ),
		ec_option_default_quick_view: jQuery( document.getElementById( 'ec_page_options_quick_view' ) ).val( ),
		
		ec_option_default_desktop_columns: jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( ),
		ec_option_default_desktop_image_height: jQuery( document.getElementById( 'ec_page_options_image_height_desktop' ) ).val( ) + 'px',
		
		ec_option_default_laptop_columns: jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).val( ),
		ec_option_default_laptop_image_height: jQuery( document.getElementById( 'ec_page_options_image_height_laptop' ) ).val( ) + 'px',
		
		ec_option_default_tablet_wide_columns: jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).val( ),
		ec_option_default_tablet_wide_image_height: jQuery( document.getElementById( 'ec_page_options_image_height_tablet_wide' ) ).val( ) + 'px',
		
		ec_option_default_tablet_columns: jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).val( ),
		ec_option_default_tablet_image_height: jQuery( document.getElementById( 'ec_page_options_image_height_tablet' ) ).val( ) + 'px',
		
		ec_option_default_smartphone_columns: jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).val( ),
		ec_option_default_smartphone_image_height: jQuery( document.getElementById( 'ec_page_options_image_height_smartphone' ) ).val( ) + 'px'
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).hide( );
		jQuery( document.getElementById( "ec_admin_page_updated" ) ).show( ).delay( 1500 ).fadeOut( 'slow' );
		jQuery( document.getElementById( "ec_admin_loader_bg" ) ).fadeOut( 'slow' );
	} } );
	
}

function ec_admin_save_product_display( model_number, nonce ){
	
	jQuery( document.getElementById( "ec_admin_product_updated_loader" ) ).show( );
		
	var data = {
		action: 'ec_ajax_save_product_options',
		model_number: model_number,
		image_hover_type: jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).val( ),
		image_effect_type: jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).val( ),
		tag_type: jQuery( document.getElementById( 'ec_product_tag_type_' + model_number ) ).val( ),
		tag_text: jQuery( document.getElementById( 'ec_product_tag_text_' + model_number ) ).val( ),
		tag_bg_color: jQuery( document.getElementById( 'ec_product_tag_color_' + model_number ) ).val( ),
		tag_text_color: jQuery( document.getElementById( 'ec_product_tag_text_color_' + model_number ) ).val( ),
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( "ec_admin_product_updated_loader" ) ).hide( );
		jQuery( document.getElementById( "ec_admin_product_updated" ) ).show( ).delay( 1500 ).fadeOut( 'slow' );
	} } );
	
	jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).val( ) );
	jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).val( ) );
	jQuery( document.getElementById( 'ec_product_tag_type_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_tag_type_' + model_number ) ).val( ) );
	jQuery( document.getElementById( 'ec_product_tag_text_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_tag_text_' + model_number ) ).val( ) );
	jQuery( document.getElementById( 'ec_product_tag_color_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_tag_color_' + model_number ) ).val( ) );
	jQuery( document.getElementById( 'ec_product_tag_text_color_' + model_number ) ).attr( 'data-default', jQuery( document.getElementById( 'ec_product_tag_text_color_' + model_number ) ).val( ) );
	
	jQuery( document.getElementById( 'ec_product_editor_' + model_number ) ).attr( 'data-changes-made', '0' );
	jQuery( document.getElementById( 'ec_product_editor_' + model_number ) ).fadeOut( 220 );
}

function ec_admin_cancel_product_display( model_number ){
	
	// Reset the boxes
	jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_image_hover_type_' + model_number ) ).attr( 'data-default' ) );
	jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_image_effect_type_' + model_number ) ).attr( 'data-default' ) );
	jQuery( document.getElementById( 'ec_product_tag_type_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_tag_type_' + model_number ) ).attr( 'data-default' ) );
	jQuery( document.getElementById( 'ec_product_tag_text_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_tag_text_' + model_number ) ).attr( 'data-default' ) );
	jQuery( document.getElementById( 'ec_product_tag_color_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_tag_color_' + model_number ) ).attr( 'data-default' ) );
	jQuery( document.getElementById( 'ec_product_tag_text_color_' + model_number ) ).val( jQuery( document.getElementById( 'ec_product_tag_text_color_' + model_number ) ).attr( 'data-default' ) );
	
	// Update values
	ec_admin_update_image_hover_effect( model_number );
	ec_admin_update_image_effect_type( model_number );
	ec_admin_update_tag_type( model_number );
	ec_admin_update_tag_text( model_number );
	ec_admin_update_tag_color( model_number );
	
	jQuery( document.getElementById( 'ec_product_editor_' + model_number ) ).attr( 'data-changes-made', '0' );
	jQuery( document.getElementById( 'ec_product_editor_' + model_number ) ).fadeOut( 220 );
}

function ec_admin_save_order( ids, post_id, nonce ){
	
	jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).show( );
		
	var data = {
		action: 'ec_ajax_save_product_order',
		post_id: post_id,
		product_order: JSON.stringify( ids ),
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( "ec_admin_page_updated_loader" ) ).hide( );
		jQuery( document.getElementById( "ec_admin_product_updated_loader" ) ).hide( );
		jQuery( document.getElementById( "ec_admin_page_updated" ) ).show( ).delay(1500).fadeOut( 'slow' );
		jQuery( document.getElementById( "ec_admin_loader_bg" ) ).fadeOut( 'slow' );
	} } );
}

function ec_admin_resizer_function( ){
	
	if( jQuery( document.getElementById( 'ec_current_media_size' ) ).length ){
	
		var window_width = jQuery( document.getElementById( 'ec_current_media_size' ) ).css( "max-width" ).replace( "px", "" );
		
		// Image Heights
		var new_height = jQuery( document.getElementById( 'ec_page_options_image_height_desktop' ) ).val( );
		
		if( window_width > 1139 ){
			new_height = jQuery( document.getElementById( 'ec_page_options_image_height_desktop' ) ).val( );
		}else if( window_width > 989 ){
			new_height = jQuery( document.getElementById( 'ec_page_options_image_height_laptop' ) ).val( );
		}else if( window_width > 767 ){
			new_height = jQuery( document.getElementById( 'ec_page_options_image_height_tablet_wide' ) ).val( );
		}else if( window_width > 480 ){
			new_height = jQuery( document.getElementById( 'ec_page_options_image_height_tablet' ) ).val( );
		}else{
			new_height = jQuery( document.getElementById( 'ec_page_options_image_height_smartphone' ) ).val( );
		}
		
		// Column Widths
		var columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
		if( window_width > 1140 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
		}else if( window_width > 990 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).val( );
		}else if( window_width > 768 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).val( );
		}else if( window_width > 481 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).val( );
		}else{
			columns = jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).val( );
		}
		
		var column_width = (100/columns) + '%';
		
		// Update Classes
		jQuery( '.ec_image_container_none, .ec_image_container_border, .ec_image_container_shadow, .ec_image_container_none > div, .ec_image_container_border > div, .ec_image_container_shadow > div' ).css( 'min-height', new_height + 'px' ).css( 'height', new_height + 'px' );
		jQuery( '.ec_product_li' ).css( 'width', column_width );
		
		// Update nth children...
		var product_list = jQuery( '.ec_product_li' );
		var count = 0;
		
		product_list.each( function( index ){
			
			if( jQuery( this ).hasClass( 'hidden' ) ){
				// Let's skip hidden elements.
				jQuery( this ).removeClass( 'first' );
				jQuery( this ).removeClass( 'not_first' );
				jQuery( this ).addClass( 'not_first' );
			}else{
				jQuery( this ).removeClass( 'hidden' );
				jQuery( this ).removeClass( 'first' );
				jQuery( this ).removeClass( 'not_first' );
				
				if( count % columns == 0 ){
					jQuery( this ).addClass( 'first' );
				}else{
					jQuery( this ).addClass( 'not_first' );
				}
				
				count++;
				
			}
			
		} );
		
		jQuery( document.getElementById( 'ec_products_sortable' ) ).css( 'width', ( columns * 220 ) + 'px' );
	
	}
	
}

jQuery( document ).ready( function( ){
    wp_easycart_init_live_editor( );
} );
                         
function wp_easycart_init_live_editor( ){
	
	jQuery( '.ec_slideout_editor' ).show( );
	
	jQuery( '.ec_product_admin_reorder_button' ).click( function( ){
		jQuery( document ).scrollTop( 0 );
		// Column Widths
		var window_width = jQuery( document.getElementById( 'ec_current_media_size' ) ).css( "max-width" ).replace( "px", "" );
		var columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
		if( window_width > 1140 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).val( );
		}else if( window_width > 990 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).val( );
		}else if( window_width > 768 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).val( );
		}else if( window_width > 481 ){
			columns = jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).val( );
		}else{
			columns = jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).val( );
		}
		jQuery( document.getElementById( 'ec_products_sortable' ) ).css( 'width', ( columns * 220 ) + 'px' );
		jQuery( '.ec_products_sortable_holder' ).fadeIn( 'slow' );
		jQuery( '.ec_products_sortable_bg' ).fadeIn( 'slow' );
	} );
	
	jQuery( '.ec_products_sortable_padding > ul > li > .dashicons-arrow-up-alt2' ).click( function( ){
		var before = jQuery( this ).parent( ).prev( );
		jQuery( this ).parent( ).insertBefore( before );
	} );
	
	jQuery( '.ec_products_sortable_padding > ul > li > .dashicons-arrow-down-alt2' ).click( function( ){
		var after = jQuery( this ).parent( ).next( );
		jQuery( this ).parent( ).insertAfter( after );
	} );
	
	jQuery( '.ec_products_sortable_cancel_button' ).click( function( ){
		jQuery( '.ec_products_sortable_holder' ).fadeOut( 'slow' );
		jQuery( '.ec_products_sortable_bg' ).fadeOut( 'slow' );
	} );

	jQuery( '.ec_products_sortable_save_button' ).click( function( post_id ){
		var post_id = jQuery( '.ec_products_sortable_save_button' ).attr( 'data-post-id' );
		var ids = [];
		jQuery( '.ec_product_sort_item' ).each( function( index ){
			ids.push( jQuery( this ).attr( 'data-model-number' ) );
		} );
		ec_admin_reorder_products( ids );
		ec_admin_save_order( ids, post_id, jQuery( this ).attr( 'data-nonce' ) );
		jQuery( '.ec_products_sortable_holder' ).fadeOut( 'slow' );
		jQuery( '.ec_products_sortable_bg' ).fadeOut( 'slow' );
	} );
	
	jQuery( document.getElementById( 'ec_page_editor_openclose_button' ) ).click( function( ){
		
		if( jQuery( '.ec_slideout_editor' ).hasClass( 'ec_display_editor_false' ) ){
			jQuery( '.ec_slideout_editor' ).animate( { left:'0px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_false' ).addClass( 'ec_display_editor_true' );
		}else{
			var post_id = jQuery( this ).attr( 'data-post-id' );
			if( jQuery( '.ec_slideout_editor' ).hasClass( 'ec_details_editor' ) ){
				ec_admin_save_product_details_options( );
			}else if( jQuery( '.ec_slideout_editor' ).hasClass( 'ec_cart_editor' ) ){
				ec_admin_save_cart_options(  );
			}else if( jQuery( '.ec_slideout_editor' ).hasClass( 'ec_account_editor' ) ){
				ec_admin_save_account_options(  );
			}else{
				ec_admin_save_page_options( post_id, jQuery( document.getElementById( 'ec_page_editor_openclose_button' ) ).attr( 'data-nonce' ) );
			}
			jQuery( '.ec_slideout_editor' ).animate( { left:'-290px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_true' ).addClass( 'ec_display_editor_false' );	
		}
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_product_type' ) ).change( function( ){
		
		if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '1' ){
			jQuery( '.ec_product_type2' ).removeClass( 'ec_product_type2' ).addClass( 'ec_product_type1' );
			jQuery( '.ec_product_title_type2' ).removeClass( 'ec_product_title_type2' ).addClass( 'ec_product_title_type1' );
			jQuery( '.ec_price_container_type2' ).removeClass( 'ec_price_container_type2' ).addClass( 'ec_price_container_type1' );
			jQuery( '.ec_list_price_type2' ).removeClass( 'ec_list_price_type2' ).addClass( 'ec_list_price_type1' );
			jQuery( '.ec_price_type2' ).removeClass( 'ec_price_type2' ).addClass( 'ec_price_type1' );
			jQuery( '.ec_product_stars_type2' ).removeClass( 'ec_product_stars_type2' ).addClass( 'ec_product_stars_type1' );
			
			jQuery( '.ec_product_type3' ).removeClass( 'ec_product_type3' ).addClass( 'ec_product_type1' );
			jQuery( '.ec_product_title_type3' ).removeClass( 'ec_product_title_type3' ).addClass( 'ec_product_title_type1' );
			jQuery( '.ec_price_container_type3' ).removeClass( 'ec_price_container_type3' ).addClass( 'ec_price_container_type1' );
			jQuery( '.ec_list_price_type3' ).removeClass( 'ec_list_price_type3' ).addClass( 'ec_list_price_type1' );
			jQuery( '.ec_price_type3' ).removeClass( 'ec_price_type3' ).addClass( 'ec_price_type1' );
			jQuery( '.ec_product_stars_type3' ).removeClass( 'ec_product_stars_type3' ).addClass( 'ec_product_stars_type1' );
			
			jQuery( '.ec_product_type4' ).removeClass( 'ec_product_type4' ).addClass( 'ec_product_type1' );
			jQuery( '.ec_product_title_type4' ).removeClass( 'ec_product_title_type4' ).addClass( 'ec_product_title_type1' );
			jQuery( '.ec_price_container_type4' ).removeClass( 'ec_price_container_type4' ).addClass( 'ec_price_container_type1' );
			jQuery( '.ec_list_price_type4' ).removeClass( 'ec_list_price_type4' ).addClass( 'ec_list_price_type1' );
			jQuery( '.ec_price_type4' ).removeClass( 'ec_price_type4' ).addClass( 'ec_price_type1' );
			jQuery( '.ec_product_stars_type4' ).removeClass( 'ec_product_stars_type4' ).addClass( 'ec_product_stars_type1' );
			
			jQuery( '.ec_product_type5' ).removeClass( 'ec_product_type5' ).addClass( 'ec_product_type1' );
			jQuery( '.ec_product_title_type5' ).removeClass( 'ec_product_title_type5' ).addClass( 'ec_product_title_type1' );
			jQuery( '.ec_price_container_type5' ).removeClass( 'ec_price_container_type5' ).addClass( 'ec_price_container_type1' );
			jQuery( '.ec_list_price_type5' ).removeClass( 'ec_list_price_type5' ).addClass( 'ec_list_price_type1' );
			jQuery( '.ec_price_type5' ).removeClass( 'ec_price_type5' ).addClass( 'ec_price_type1' );
			jQuery( '.ec_product_stars_type5' ).removeClass( 'ec_product_stars_type5' ).addClass( 'ec_product_stars_type1' );
			
			jQuery( '.ec_product_type6' ).removeClass( 'ec_product_type6' ).addClass( 'ec_product_type1' );
			jQuery( '.ec_product_title_type6' ).removeClass( 'ec_product_title_type6' ).addClass( 'ec_product_title_type1' );
			jQuery( '.ec_price_container_type6' ).removeClass( 'ec_price_container_type6' ).addClass( 'ec_price_container_type1' );
			jQuery( '.ec_list_price_type6' ).removeClass( 'ec_list_price_type6' ).addClass( 'ec_list_price_type1' );
			jQuery( '.ec_price_type6' ).removeClass( 'ec_price_type6' ).addClass( 'ec_price_type1' );
			jQuery( '.ec_product_stars_type6' ).removeClass( 'ec_product_stars_type6' ).addClass( 'ec_product_stars_type1' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'relative' );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', '0' );
			jQuery( '.ec_product_quickview' ).css( 'top', "125px" );
			
			jQuery( '.ec_oos_type_1' ).show( );
			jQuery( '.ec_oos_type_6' ).hide( );
					
		}else if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '2' ){
			jQuery( '.ec_product_type1' ).removeClass( 'ec_product_type1' ).addClass( 'ec_product_type2' );
			jQuery( '.ec_product_title_type1' ).removeClass( 'ec_product_title_type1' ).addClass( 'ec_product_title_type2' );
			jQuery( '.ec_price_container_type1' ).removeClass( 'ec_price_container_type1' ).addClass( 'ec_price_container_type2' );
			jQuery( '.ec_list_price_type1' ).removeClass( 'ec_list_price_type1' ).addClass( 'ec_list_price_type2' );
			jQuery( '.ec_price_type1' ).removeClass( 'ec_price_type1' ).addClass( 'ec_price_type2' );
			jQuery( '.ec_product_stars_type1' ).removeClass( 'ec_product_stars_type1' ).addClass( 'ec_product_stars_type2' );
			
			jQuery( '.ec_product_type3' ).removeClass( 'ec_product_type3' ).addClass( 'ec_product_type2' );
			jQuery( '.ec_product_title_type3' ).removeClass( 'ec_product_title_type3' ).addClass( 'ec_product_title_type2' );
			jQuery( '.ec_price_container_type3' ).removeClass( 'ec_price_container_type3' ).addClass( 'ec_price_container_type2' );
			jQuery( '.ec_list_price_type3' ).removeClass( 'ec_list_price_type3' ).addClass( 'ec_list_price_type2' );
			jQuery( '.ec_price_type3' ).removeClass( 'ec_price_type3' ).addClass( 'ec_price_type2' );
			jQuery( '.ec_product_stars_type3' ).removeClass( 'ec_product_stars_type3' ).addClass( 'ec_product_stars_type2' );
			
			jQuery( '.ec_product_type4' ).removeClass( 'ec_product_type4' ).addClass( 'ec_product_type2' );
			jQuery( '.ec_product_title_type4' ).removeClass( 'ec_product_title_type4' ).addClass( 'ec_product_title_type2' );
			jQuery( '.ec_price_container_type4' ).removeClass( 'ec_price_container_type4' ).addClass( 'ec_price_container_type2' );
			jQuery( '.ec_list_price_type4' ).removeClass( 'ec_list_price_type4' ).addClass( 'ec_list_price_type2' );
			jQuery( '.ec_price_type4' ).removeClass( 'ec_price_type4' ).addClass( 'ec_price_type2' );
			jQuery( '.ec_product_stars_type4' ).removeClass( 'ec_product_stars_type4' ).addClass( 'ec_product_stars_type2' );
			
			jQuery( '.ec_product_type5' ).removeClass( 'ec_product_type5' ).addClass( 'ec_product_type2' );
			jQuery( '.ec_product_title_type5' ).removeClass( 'ec_product_title_type5' ).addClass( 'ec_product_title_type2' );
			jQuery( '.ec_price_container_type5' ).removeClass( 'ec_price_container_type5' ).addClass( 'ec_price_container_type2' );
			jQuery( '.ec_list_price_type5' ).removeClass( 'ec_list_price_type5' ).addClass( 'ec_list_price_type2' );
			jQuery( '.ec_price_type5' ).removeClass( 'ec_price_type5' ).addClass( 'ec_price_type2' );
			jQuery( '.ec_product_stars_type5' ).removeClass( 'ec_product_stars_type5' ).addClass( 'ec_product_stars_type2' );
			
			jQuery( '.ec_product_type6' ).removeClass( 'ec_product_type6' ).addClass( 'ec_product_type2' );
			jQuery( '.ec_product_title_type6' ).removeClass( 'ec_product_title_type6' ).addClass( 'ec_product_title_type2' );
			jQuery( '.ec_price_container_type6' ).removeClass( 'ec_price_container_type6' ).addClass( 'ec_price_container_type2' );
			jQuery( '.ec_list_price_type6' ).removeClass( 'ec_list_price_type6' ).addClass( 'ec_list_price_type2' );
			jQuery( '.ec_price_type6' ).removeClass( 'ec_price_type6' ).addClass( 'ec_price_type2' );
			jQuery( '.ec_product_stars_type6' ).removeClass( 'ec_product_stars_type6' ).addClass( 'ec_product_stars_type2' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'relative' );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', '0' );
			jQuery( '.ec_product_quickview' ).css( 'top', "125px" );
			
			jQuery( '.ec_oos_type_1' ).show( );
			jQuery( '.ec_oos_type_6' ).hide( );
			
		}else if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '3' ){
			jQuery( '.ec_product_type1' ).removeClass( 'ec_product_type1' ).addClass( 'ec_product_type3' );
			jQuery( '.ec_product_title_type1' ).removeClass( 'ec_product_title_type1' ).addClass( 'ec_product_title_type3' );
			jQuery( '.ec_price_container_type1' ).removeClass( 'ec_price_container_type1' ).addClass( 'ec_price_container_type3' );
			jQuery( '.ec_list_price_type1' ).removeClass( 'ec_list_price_type1' ).addClass( 'ec_list_price_type3' );
			jQuery( '.ec_price_type1' ).removeClass( 'ec_price_type1' ).addClass( 'ec_price_type3' );
			jQuery( '.ec_product_stars_type1' ).removeClass( 'ec_product_stars_type1' ).addClass( 'ec_product_stars_type3' );
			
			jQuery( '.ec_product_type2' ).removeClass( 'ec_product_type2' ).addClass( 'ec_product_type3' );
			jQuery( '.ec_product_title_type2' ).removeClass( 'ec_product_title_type2' ).addClass( 'ec_product_title_type3' );
			jQuery( '.ec_price_container_type2' ).removeClass( 'ec_price_container_type2' ).addClass( 'ec_price_container_type3' );
			jQuery( '.ec_list_price_type2' ).removeClass( 'ec_list_price_type2' ).addClass( 'ec_list_price_type3' );
			jQuery( '.ec_price_type2' ).removeClass( 'ec_price_type2' ).addClass( 'ec_price_type3' );
			jQuery( '.ec_product_stars_type2' ).removeClass( 'ec_product_stars_type2' ).addClass( 'ec_product_stars_type3' );
			
			jQuery( '.ec_product_type4' ).removeClass( 'ec_product_type4' ).addClass( 'ec_product_type3' );
			jQuery( '.ec_product_title_type4' ).removeClass( 'ec_product_title_type4' ).addClass( 'ec_product_title_type3' );
			jQuery( '.ec_price_container_type4' ).removeClass( 'ec_price_container_type4' ).addClass( 'ec_price_container_type3' );
			jQuery( '.ec_list_price_type4' ).removeClass( 'ec_list_price_type4' ).addClass( 'ec_list_price_type3' );
			jQuery( '.ec_price_type4' ).removeClass( 'ec_price_type4' ).addClass( 'ec_price_type3' );
			jQuery( '.ec_product_stars_type4' ).removeClass( 'ec_product_stars_type4' ).addClass( 'ec_product_stars_type3' );
			
			jQuery( '.ec_product_type5' ).removeClass( 'ec_product_type5' ).addClass( 'ec_product_type3' );
			jQuery( '.ec_product_title_type5' ).removeClass( 'ec_product_title_type5' ).addClass( 'ec_product_title_type3' );
			jQuery( '.ec_price_container_type5' ).removeClass( 'ec_price_container_type5' ).addClass( 'ec_price_container_type3' );
			jQuery( '.ec_list_price_type5' ).removeClass( 'ec_list_price_type5' ).addClass( 'ec_list_price_type3' );
			jQuery( '.ec_price_type5' ).removeClass( 'ec_price_type5' ).addClass( 'ec_price_type3' );
			jQuery( '.ec_product_stars_type5' ).removeClass( 'ec_product_stars_type5' ).addClass( 'ec_product_stars_type3' );
			
			jQuery( '.ec_product_type6' ).removeClass( 'ec_product_type6' ).addClass( 'ec_product_type3' );
			jQuery( '.ec_product_title_type6' ).removeClass( 'ec_product_title_type6' ).addClass( 'ec_product_title_type3' );
			jQuery( '.ec_price_container_type6' ).removeClass( 'ec_price_container_type6' ).addClass( 'ec_price_container_type3' );
			jQuery( '.ec_list_price_type6' ).removeClass( 'ec_list_price_type6' ).addClass( 'ec_list_price_type3' );
			jQuery( '.ec_price_type6' ).removeClass( 'ec_price_type6' ).addClass( 'ec_price_type3' );
			jQuery( '.ec_product_stars_type6' ).removeClass( 'ec_product_stars_type6' ).addClass( 'ec_product_stars_type3' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'relative' );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', '0' );
			var image_height = jQuery( document.getElementById( 'ec_page_options_image_height' ) ).val( );
			jQuery( '.ec_product_quickview' ).css( 'top', ( image_height - 21 ) + "px" );
			
			jQuery( '.ec_oos_type_1' ).show( );
			jQuery( '.ec_oos_type_6' ).hide( );
			
		}else if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '4' ){
			jQuery( '.ec_product_type1' ).removeClass( 'ec_product_type1' ).addClass( 'ec_product_type4' );
			jQuery( '.ec_product_title_type1' ).removeClass( 'ec_product_title_type1' ).addClass( 'ec_product_title_type4' );
			jQuery( '.ec_price_container_type1' ).removeClass( 'ec_price_container_type1' ).addClass( 'ec_price_container_type4' );
			jQuery( '.ec_list_price_type1' ).removeClass( 'ec_list_price_type1' ).addClass( 'ec_list_price_type4' );
			jQuery( '.ec_price_type1' ).removeClass( 'ec_price_type1' ).addClass( 'ec_price_type4' );
			jQuery( '.ec_product_stars_type1' ).removeClass( 'ec_product_stars_type1' ).addClass( 'ec_product_stars_type4' );
			
			jQuery( '.ec_product_type2' ).removeClass( 'ec_product_type2' ).addClass( 'ec_product_type4' );
			jQuery( '.ec_product_title_type2' ).removeClass( 'ec_product_title_type2' ).addClass( 'ec_product_title_type4' );
			jQuery( '.ec_price_container_type2' ).removeClass( 'ec_price_container_type2' ).addClass( 'ec_price_container_type4' );
			jQuery( '.ec_list_price_type2' ).removeClass( 'ec_list_price_type2' ).addClass( 'ec_list_price_type4' );
			jQuery( '.ec_price_type2' ).removeClass( 'ec_price_type2' ).addClass( 'ec_price_type4' );
			jQuery( '.ec_product_stars_type2' ).removeClass( 'ec_product_stars_type2' ).addClass( 'ec_product_stars_type4' );
			
			jQuery( '.ec_product_type3' ).removeClass( 'ec_product_type3' ).addClass( 'ec_product_type4' );
			jQuery( '.ec_product_title_type3' ).removeClass( 'ec_product_title_type3' ).addClass( 'ec_product_title_type4' );
			jQuery( '.ec_price_container_type3' ).removeClass( 'ec_price_container_type3' ).addClass( 'ec_price_container_type4' );
			jQuery( '.ec_list_price_type3' ).removeClass( 'ec_list_price_type3' ).addClass( 'ec_list_price_type4' );
			jQuery( '.ec_price_type3' ).removeClass( 'ec_price_type3' ).addClass( 'ec_price_type4' );
			jQuery( '.ec_product_stars_type3' ).removeClass( 'ec_product_stars_type3' ).addClass( 'ec_product_stars_type4' );
			
			jQuery( '.ec_product_type5' ).removeClass( 'ec_product_type5' ).addClass( 'ec_product_type4' );
			jQuery( '.ec_product_title_type5' ).removeClass( 'ec_product_title_type5' ).addClass( 'ec_product_title_type4' );
			jQuery( '.ec_price_container_type5' ).removeClass( 'ec_price_container_type5' ).addClass( 'ec_price_container_type4' );
			jQuery( '.ec_list_price_type5' ).removeClass( 'ec_list_price_type5' ).addClass( 'ec_list_price_type4' );
			jQuery( '.ec_price_type5' ).removeClass( 'ec_price_type5' ).addClass( 'ec_price_type4' );
			jQuery( '.ec_product_stars_type5' ).removeClass( 'ec_product_stars_type5' ).addClass( 'ec_product_stars_type4' );
			
			jQuery( '.ec_product_type6' ).removeClass( 'ec_product_type6' ).addClass( 'ec_product_type4' );
			jQuery( '.ec_product_title_type6' ).removeClass( 'ec_product_title_type6' ).addClass( 'ec_product_title_type4' );
			jQuery( '.ec_price_container_type6' ).removeClass( 'ec_price_container_type6' ).addClass( 'ec_price_container_type4' );
			jQuery( '.ec_list_price_type6' ).removeClass( 'ec_list_price_type6' ).addClass( 'ec_list_price_type4' );
			jQuery( '.ec_price_type6' ).removeClass( 'ec_price_type6' ).addClass( 'ec_price_type4' );
			jQuery( '.ec_product_stars_type6' ).removeClass( 'ec_product_stars_type6' ).addClass( 'ec_product_stars_type4' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'relative' );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', '0' );
			var image_height = jQuery( document.getElementById( 'ec_page_options_image_height' ) ).val( );
			jQuery( '.ec_product_quickview' ).css( 'top', ( image_height - 21 ) + "px" );
			
			jQuery( '.ec_oos_type_1' ).show( );
			jQuery( '.ec_oos_type_6' ).hide( );
			
		}else if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '5' ){
			jQuery( '.ec_product_type1' ).removeClass( 'ec_product_type1' ).addClass( 'ec_product_type5' );
			jQuery( '.ec_product_title_type1' ).removeClass( 'ec_product_title_type1' ).addClass( 'ec_product_title_type5' );
			jQuery( '.ec_price_container_type1' ).removeClass( 'ec_price_container_type1' ).addClass( 'ec_price_container_type5' );
			jQuery( '.ec_list_price_type1' ).removeClass( 'ec_list_price_type1' ).addClass( 'ec_list_price_type5' );
			jQuery( '.ec_price_type1' ).removeClass( 'ec_price_type1' ).addClass( 'ec_price_type5' );
			jQuery( '.ec_product_stars_type1' ).removeClass( 'ec_product_stars_type1' ).addClass( 'ec_product_stars_type5' );
			
			jQuery( '.ec_product_type2' ).removeClass( 'ec_product_type2' ).addClass( 'ec_product_type5' );
			jQuery( '.ec_product_title_type2' ).removeClass( 'ec_product_title_type2' ).addClass( 'ec_product_title_type5' );
			jQuery( '.ec_price_container_type2' ).removeClass( 'ec_price_container_type2' ).addClass( 'ec_price_container_type5' );
			jQuery( '.ec_list_price_type2' ).removeClass( 'ec_list_price_type2' ).addClass( 'ec_list_price_type5' );
			jQuery( '.ec_price_type2' ).removeClass( 'ec_price_type2' ).addClass( 'ec_price_type5' );
			jQuery( '.ec_product_stars_type2' ).removeClass( 'ec_product_stars_type2' ).addClass( 'ec_product_stars_type5' );
			
			jQuery( '.ec_product_type3' ).removeClass( 'ec_product_type3' ).addClass( 'ec_product_type5' );
			jQuery( '.ec_product_title_type3' ).removeClass( 'ec_product_title_type3' ).addClass( 'ec_product_title_type5' );
			jQuery( '.ec_price_container_type3' ).removeClass( 'ec_price_container_type3' ).addClass( 'ec_price_container_type5' );
			jQuery( '.ec_list_price_type3' ).removeClass( 'ec_list_price_type3' ).addClass( 'ec_list_price_type5' );
			jQuery( '.ec_price_type3' ).removeClass( 'ec_price_type3' ).addClass( 'ec_price_type5' );
			jQuery( '.ec_product_stars_type3' ).removeClass( 'ec_product_stars_type3' ).addClass( 'ec_product_stars_type5' );
			
			jQuery( '.ec_product_type4' ).removeClass( 'ec_product_type4' ).addClass( 'ec_product_type5' );
			jQuery( '.ec_product_title_type4' ).removeClass( 'ec_product_title_type4' ).addClass( 'ec_product_title_type5' );
			jQuery( '.ec_price_container_type4' ).removeClass( 'ec_price_container_type4' ).addClass( 'ec_price_container_type5' );
			jQuery( '.ec_list_price_type4' ).removeClass( 'ec_list_price_type4' ).addClass( 'ec_list_price_type5' );
			jQuery( '.ec_price_type4' ).removeClass( 'ec_price_type4' ).addClass( 'ec_price_type5' );
			jQuery( '.ec_product_stars_type4' ).removeClass( 'ec_product_stars_type4' ).addClass( 'ec_product_stars_type5' );
			
			jQuery( '.ec_product_type6' ).removeClass( 'ec_product_type6' ).addClass( 'ec_product_type5' );
			jQuery( '.ec_product_title_type6' ).removeClass( 'ec_product_title_type6' ).addClass( 'ec_product_title_type5' );
			jQuery( '.ec_price_container_type6' ).removeClass( 'ec_price_container_type6' ).addClass( 'ec_price_container_type5' );
			jQuery( '.ec_list_price_type6' ).removeClass( 'ec_list_price_type6' ).addClass( 'ec_list_price_type5' );
			jQuery( '.ec_price_type6' ).removeClass( 'ec_price_type6' ).addClass( 'ec_price_type5' );
			jQuery( '.ec_product_stars_type6' ).removeClass( 'ec_product_stars_type6' ).addClass( 'ec_product_stars_type5' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'absolute' );
			var image_height = jQuery( document.getElementById( 'ec_page_options_image_height' ) ).val( );
			jQuery( '.ec_product_quickview' ).css( 'top', "100px" );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', 'inherit' );
			
			jQuery( '.ec_oos_type_1' ).show( );
			jQuery( '.ec_oos_type_6' ).hide( );
			
		}else if( jQuery( document.getElementById( 'ec_page_options_product_type' ) ).val( ) == '6' ){
			jQuery( '.ec_product_type1' ).removeClass( 'ec_product_type1' ).addClass( 'ec_product_type6' );
			jQuery( '.ec_product_title_type1' ).removeClass( 'ec_product_title_type1' ).addClass( 'ec_product_title_type6' );
			jQuery( '.ec_price_container_type1' ).removeClass( 'ec_price_container_type1' ).addClass( 'ec_price_container_type6' );
			jQuery( '.ec_list_price_type1' ).removeClass( 'ec_list_price_type1' ).addClass( 'ec_list_price_type6' );
			jQuery( '.ec_price_type1' ).removeClass( 'ec_price_type1' ).addClass( 'ec_price_type6' );
			jQuery( '.ec_product_stars_type1' ).removeClass( 'ec_product_stars_type1' ).addClass( 'ec_product_stars_type6' );
			
			jQuery( '.ec_product_type2' ).removeClass( 'ec_product_type2' ).addClass( 'ec_product_type6' );
			jQuery( '.ec_product_title_type2' ).removeClass( 'ec_product_title_type2' ).addClass( 'ec_product_title_type6' );
			jQuery( '.ec_price_container_type2' ).removeClass( 'ec_price_container_type2' ).addClass( 'ec_price_container_type6' );
			jQuery( '.ec_list_price_type2' ).removeClass( 'ec_list_price_type2' ).addClass( 'ec_list_price_type6' );
			jQuery( '.ec_price_type2' ).removeClass( 'ec_price_type2' ).addClass( 'ec_price_type6' );
			jQuery( '.ec_product_stars_type2' ).removeClass( 'ec_product_stars_type2' ).addClass( 'ec_product_stars_type6' );
			
			jQuery( '.ec_product_type3' ).removeClass( 'ec_product_type3' ).addClass( 'ec_product_type6' );
			jQuery( '.ec_product_title_type3' ).removeClass( 'ec_product_title_type3' ).addClass( 'ec_product_title_type6' );
			jQuery( '.ec_price_container_type3' ).removeClass( 'ec_price_container_type3' ).addClass( 'ec_price_container_type6' );
			jQuery( '.ec_list_price_type3' ).removeClass( 'ec_list_price_type3' ).addClass( 'ec_list_price_type6' );
			jQuery( '.ec_price_type3' ).removeClass( 'ec_price_type3' ).addClass( 'ec_price_type6' );
			jQuery( '.ec_product_stars_type3' ).removeClass( 'ec_product_stars_type3' ).addClass( 'ec_product_stars_type6' );
			
			jQuery( '.ec_product_type4' ).removeClass( 'ec_product_type4' ).addClass( 'ec_product_type6' );
			jQuery( '.ec_product_title_type4' ).removeClass( 'ec_product_title_type4' ).addClass( 'ec_product_title_type6' );
			jQuery( '.ec_price_container_type4' ).removeClass( 'ec_price_container_type4' ).addClass( 'ec_price_container_type6' );
			jQuery( '.ec_list_price_type4' ).removeClass( 'ec_list_price_type4' ).addClass( 'ec_list_price_type6' );
			jQuery( '.ec_price_type4' ).removeClass( 'ec_price_type4' ).addClass( 'ec_price_type6' );
			jQuery( '.ec_product_stars_type4' ).removeClass( 'ec_product_stars_type4' ).addClass( 'ec_product_stars_type6' );
			
			jQuery( '.ec_product_type5' ).removeClass( 'ec_product_type5' ).addClass( 'ec_product_type6' );
			jQuery( '.ec_product_title_type5' ).removeClass( 'ec_product_title_type5' ).addClass( 'ec_product_title_type6' );
			jQuery( '.ec_price_container_type5' ).removeClass( 'ec_price_container_type5' ).addClass( 'ec_price_container_type6' );
			jQuery( '.ec_list_price_type5' ).removeClass( 'ec_list_price_type5' ).addClass( 'ec_list_price_type6' );
			jQuery( '.ec_price_type5' ).removeClass( 'ec_price_type5' ).addClass( 'ec_price_type6' );
			jQuery( '.ec_product_stars_type5' ).removeClass( 'ec_product_stars_type5' ).addClass( 'ec_product_stars_type6' );
			
			jQuery( '.ec_product_addtocart_container' ).css( 'position', 'relative' );
			jQuery( '.ec_product_addtocart_container' ).css( 'top', "inherit" );
			
			jQuery( '.ec_oos_type_1' ).hide( );
			jQuery( '.ec_oos_type_6' ).show( );
			
		}
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_quick_view' ) ).change( function( ){
		
		if( jQuery( document.getElementById( 'ec_page_options_quick_view' ) ).val( ) == '1' ){
			jQuery( '.ec_product_quickview' ).show( );
		}else{
			jQuery( '.ec_product_quickview' ).hide( );
		}
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_columns_smartphone' ) ).change( function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_columns_tablet' ) ).change( function( ){
		
		ec_admin_resizer_function( );
	
	} );
	
	jQuery( document.getElementById( 'ec_page_options_columns_tablet_wide' ) ).change( function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_columns_laptop' ) ).change( function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_columns_desktop' ) ).change( function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_image_height_smartphone' ) ).bind( 'keyup change mouseup scroll mousewheel', function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_image_height_tablet' ) ).bind( 'keyup change mouseup scroll mousewheel', function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_image_height_tablet_wide' ) ).bind( 'keyup change mouseup scroll mousewheel', function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_image_height_laptop' ) ).bind( 'keyup change mouseup scroll mousewheel', function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_page_options_image_height_desktop' ) ).bind( 'keyup change mouseup scroll mousewheel', function( ){
		
		ec_admin_resizer_function( );
		
	} );

	jQuery( window ).resize( function( ){
		
		ec_admin_resizer_function( );
		
	} );
	
	jQuery( document.getElementById( 'ec_admin_video_container' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ec_admin_page_updated' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ec_admin_page_updated_loader' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ec_admin_product_updated' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ec_admin_product_updated_loader' ) ).appendTo( document.body );
	jQuery( document.getElementById( 'ec_admin_loader_bg' ) ).appendTo( document.body );
	jQuery( '.ec_slideout_editor' ).appendTo( document.body );
	jQuery( '.ec_products_sortable_holder' ).appendTo( document.body );
	jQuery( '.ec_products_sortable_bg' ).appendTo( document.body );

	var ec_responsive_panel_list = Array( 'smartphone', 'smartphone-width', 'tablet', 'tablet_wide', 'laptop', 'desktop' ); 
	var ec_current_responsive_panel = 'desktop';
	
	jQuery( '.ec_responsive_left' ).click( 
		function( event ){
			
			jQuery( document.getElementById( 'ec_responsive_' + ec_current_responsive_panel ) ).hide( );
			
			if( ec_current_responsive_panel == 'smartphone' ){
				ec_current_responsive_panel = 'desktop';
				jQuery( document.getElementById( 'ec_responsive_desktop' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'tablet' ){
				ec_current_responsive_panel = 'smartphone';
				jQuery( document.getElementById( 'ec_responsive_smartphone' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'tablet_wide' ){
				ec_current_responsive_panel = 'tablet';
				jQuery( document.getElementById( 'ec_responsive_tablet' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'laptop' ){
				ec_current_responsive_panel = 'tablet_wide';
				jQuery( document.getElementById( 'ec_responsive_tablet_wide' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'desktop' ){
				ec_current_responsive_panel = 'laptop';
				jQuery( document.getElementById( 'ec_responsive_laptop' ) ).show( );
			
			}
			
		}
	);
	
	jQuery( '.ec_responsive_right' ).click( 
		function( event ){
			
			jQuery( document.getElementById( 'ec_responsive_' + ec_current_responsive_panel ) ).hide( );
			
			if( ec_current_responsive_panel == 'smartphone' ){
				ec_current_responsive_panel = 'tablet';
				jQuery( document.getElementById( 'ec_responsive_tablet' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'tablet' ){
				ec_current_responsive_panel = 'tablet_wide';
				jQuery( document.getElementById( 'ec_responsive_tablet_wide' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'tablet_wide' ){
				ec_current_responsive_panel = 'laptop';
				jQuery( document.getElementById( 'ec_responsive_laptop' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'laptop' ){
				ec_current_responsive_panel = 'desktop';
				jQuery( document.getElementById( 'ec_responsive_desktop' ) ).show( );
			
			}else if( ec_current_responsive_panel == 'desktop' ){
				ec_current_responsive_panel = 'smartphone';
				jQuery( document.getElementById( 'ec_responsive_smartphone' ) ).show( );
			
			}
			
		}
	);
	
	jQuery( document.getElementById( 'ec_page_option_dynamic_image_sizing' ) ).change( function( ){
		
		if( jQuery( this ).val( ) == '1' ){
			jQuery( '.ec_non_dynamic_sizing' ).hide( );
			jQuery( '.ec_dynamic_image_height' ).addClass( 'dynamic_height_rule' );
			
		}else{
			jQuery( '.ec_non_dynamic_sizing' ).show( );
			jQuery( '.ec_dynamic_image_height' ).removeClass( 'dynamic_height_rule' );
			
		}
		
	} );

}