jQuery( document ).ready( function( ){
	jQuery( 'select.select2-basic' ).select2( );
    jQuery( 'select.select2-add-new' ).select2( {
        tags: true,
        createTag: function( params ){
            var term = jQuery.trim( params.term );
            if( term === '' ){
                return null;
            }
            return {
                id: '-1',
                text: term,
                newTag: true
            }
        }
    } );
    jQuery( 'select.select2-multiple' ).select2( {
        tags: true,
        createTag: function (params) {
            var term = jQuery.trim( params.term );
            if( term === '' ){
                return null;
            }
            return {
                id: term,
                text: term,
                newTag: true
            }
        }
    } );
	var wp_easycart_color_picker_timeout = 0; 
	jQuery( '.ec_color_block_input').wpColorPicker( { 
        change: function( ){ 
            var that = this; 
            clearTimeout( wp_easycart_color_picker_timeout ); 
            wp_easycart_color_picker_timeout = setTimeout( function(){ 
                jQuery( that ).parent( ).parent( ).find( '.ec_color_block_input' ).trigger( 'change' );
            }, 500 ) 
        } 
    } );
	jQuery( '.ec_admin_expand_section_header' ).on( 'click', function( ){
        var expand_icon = jQuery( this ).find( '.ec_admin_expand_section' );
        var section_id = expand_icon.attr( 'data-section' );
		if( jQuery( document.getElementById( section_id ) ).is( ':visible' ) ){
			jQuery( document.getElementById( section_id ) ).hide( );
			expand_icon.html( '<div class="dashicons-before dashicons-arrow-down-alt2"></div>' );
		}else{
			jQuery( document.getElementById( section_id ) ).show( );
			expand_icon.html( '<div class="dashicons-before dashicons-arrow-up-alt2"></div>' );
		}
    } );
	jQuery( '.ec_admin_mobile_menu' ).prependTo( document.body );
	
	jQuery( document.getElementById( 'posts-filter' ) ).on( 'submit', function( ){
		if( jQuery( document.getElementById( 'search-input' ) ).val( ) != '' ){
			jQuery( document.getElementById( 'pagenum' ) ).val( '1' );
		}
	} );
	
	/* PROCESSING CLICK ACTION */
	jQuery( '.ec_admin_process_click' ).on( 'click', function( ){
		jQuery( this ).html( wpeasycart_admin_ajax_object.wp_easycart_admin_language['processing'] );
		jQuery( this ).attr( 'disabled', 'disabled' );
	} );
	
	/* PREVENT DOUBLE FORM SUBMISSION */
	jQuery( 'form' ).on( 'submit', function( e ){
		if( jQuery( this ).attr( 'id' ) != 'wpeasycart_search_form' ){
            var $form = jQuery( this );
            if( $form.data( 'submitted' ) === true ){
                e.preventDefault( );
            }else{
                $form.data( 'submitted', true );
            }
        }
	} );
    
    /* COLORIZE ADMIN DYNAMICALLY */
    if( jQuery( '.ec_admin_color_selector' ).length ){
        var color_change_timeout = false;
        jQuery( '.ec_admin_color_selector > input' ).wpColorPicker( {
            change: function( event, ui ){
                jQuery( '.ec_admin_wizard_container > h1' ).css( 'background', ui.color.toString( ) ).css( 'border-top-color', wpeasycart_color_tint( ui.color.toString( ), 40 ) ).css( 'border-bottom-color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_default_color1-color, .ec_admin_default_color1-color, .ec_admin_wizard_current, .ec_admin_wizard_complete' ).css( 'color', ui.color.toString( ) );
                jQuery( '.ec_admin_default_color1-background, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_item:hover, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_selected, .ec_admin_top_nav_link:hover, .ec_admin_bottom_stats_bar_positive' ).css( 'background', ui.color.toString( ) );
                jQuery( '.ec_admin_default_color1-background, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_item:hover, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_selected, .ec_admin_top_nav_link:hover, .ec_admin_bottom_stats_bar_positive, a.ec_admin_wizard_next_button, input[type="submit"].ec_admin_wizard_next_button' ).css( 'background-color', ui.color.toString( ) );
                jQuery( '.ec_admin_default_color1-background-gradient' ).css( 'background-image', 'linear-gradient( to bottom, ' + ui.color.toString( ) + ', ' + wpeasycart_color_tint( ui.color.toString( ), -40 ) + ')' );
                jQuery( '.ec_admin_default_color2-background, a.ec_admin_wizard_next_button:hover, input[type="submit"].ec_admin_wizard_next_button:hover' ).css( 'background', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_default_color1-border-right, .ec_admin_default_color1-border-left, .ec_admin_top_nav_link' ).css( 'border-right-color', ui.color.toString( ) ).css( 'border-left-color', ui.color.toString( ) );
                jQuery( '.ec_admin_default_color2-border' ).css( 'border-color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_default_color3-background, .ec_admin_left_nav_subitem.ec_admin_left_nav_selected, .ec_admin_bottom_stats_bar' ).css( 'background', wpeasycart_color_tint( ui.color.toString( ), -40 ) ).css( 'border-color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_default_color1-background, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_item:hover, .ec_admin_default_color1-background-gradient.ec_admin_left_nav_selected, .ec_admin_top_nav_link:hover, .ec_admin_bottom_stats_bar_positive' ).css( 'background', ui.color.toString( ) ).css( 'background-color', ui.color.toString( ) );
                jQuery( 'a.ec_admin_wizard_quit_button' ).css( 'color', ui.color.toString( ) );
                jQuery( '.ec_admin_content_area, .ec_admin_right, .ec_admin_right_stats' ).css( 'border-bottom-color', ui.color.toString( ) ).css( 'border-left-color', ui.color.toString( ) );
                jQuery( '.ec_admin_wrap' ).css( 'border-right-color', ui.color.toString( ) );
                jQuery( '.ec_admin_left_stats' ).css( 'border-right-color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_left_nav_subitem.ec_admin_left_nav_selected' ).css( 'background-color', wpeasycart_color_tint( ui.color.toString( ), -60 ) );
                jQuery( '.ec_admin_content_area h1.wp-heading-inline, .ec_admin_content_area h1.easycart-wp-heading-inline, .ec_admin_content_area h1.wp-heading-inline a, .ec_admin_content_area h1.easycart-wp-heading-inline a, .ec_admin_settings_label' ).css( 'color', wpeasycart_color_tint( ui.color.toString( ), -60 ) );
                jQuery( '.ec_admin_content_area h1.wp-heading-inline a:hover, .ec_admin_content_area h1.easycart-wp-heading-inline a:hover' ).css( 'color', '#FFFFFF' ).css( 'background', ui.color.toString( ) ).css( 'border-color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                jQuery( '.ec_admin_content_area .wp-list-table a' ).css( 'color', wpeasycart_color_tint( ui.color.toString( ), -60 ) );
                jQuery( '.ec_admin_content_area .wp-list-table a:hover' ).css( 'color', ui.color.toString( ) );
                jQuery( '.ec_admin_stats_container, #ec_admin_row_tier_pricing, #ec_admin_row_b2b_pricing, .ec_admin_details_panel textarea, .ec_admin_settings_input input[type="checkbox"]' ).css( 'border-color', ui.color.toString( ) );
                jQuery( '.ec_admin_products_submit > input.ec_admin_products_simple_button, .ec_admin_product_details_manufacturer_column2 > input[type="button"], #ec_admin_add_new_advanced_option_row > input, #ec_admin_add_new_category_row > input, #ec_admin_add_new_price_tier_row > input[type="button"], #ec_admin_add_new_role_price_row > input[type="button"], .ec_admin_option_add_new_row input[type="button"], .ec_page_title_button:hover, .ec_admin_order_edit_button:hover, .ec_admin_settings_input > span.ec_admin_settings_simple_button, .ec_admin_settings_input > input.ec_admin_settings_simple_button, .ec_admin_settings_label > span.ec_admin_label_button > a, .ec_admin_settings_shipping_input > input, input.ec_admin_settings_simple_delete_button, .ec_admin_settings_simple_delete_button, .ec_admin_settings_live_rate_toggle div.dashicons-before, .ec_admin_settings_shipping_divider, .ec_admin_language_add > input.ec_admin_settings_simple_button, .ec_admin_language_input > input.ec_admin_settings_simple_button' ).css( 'background', ui.color.toString( ) ).css( 'background-color', ui.color.toString( ) );
                jQuery( '.ec_admin_products_submit > input.ec_admin_products_simple_button:hover, .ec_admin_settings_input > span.ec_admin_settings_simple_button:hover, .ec_admin_settings_input > input.ec_admin_settings_simple_button:hover, .ec_admin_settings_label > span.ec_admin_label_button > a:hover, .ec_admin_settings_shipping_input > input:hover, input.ec_admin_settings_simple_delete_button:hover, .ec_admin_settings_simple_delete_button:hover, .ec_admin_language_add > input.ec_admin_settings_simple_button:hover, .ec_admin_language_input > input.ec_admin_settings_simple_button:hover' ).css( 'color', wpeasycart_color_tint( ui.color.toString( ), 40 ) );
                jQuery( '.ec_admin_settings_currency_section input[type="date"], .ec_admin_settings_currency_section select, .ec_admin_settings_currency_section .select2, .ec_admin_settings_currency_section input[type="number"], .ec_admin_settings_currency_section input[type="text"], .ec_admin_settings_currency_section input[type="password"], .ec_admin_settings_currency_section input[type="date"], .ec_admin_settings_currency_section select, .ec_admin_settings_currency_section .select2, .ec_admin_settings_input select, .select2-container > .selection > .select2-selection, .ec_admin_settings_products_section input, input.ec_admin_taxcloud_field, select.ec_admin_taxcloud_field, .ec_admin_settings_tax_section input, .ec_admin_settings_tax_section select, .ec_admin_settings_live_payment_section input[type="text"], .ec_admin_settings_live_payment_section input[type="email"], .ec_admin_settings_live_payment_section input[type="password"], .ec_admin_settings_live_payment_section select, .ec_admin_live_rate_display, .ec_admin_settings_shipping_section input[type="text"], .ec_admin_settings_shipping_section input[type="number"], .ec_admin_settings_shipping_section select' ).css( 'border-color', ui.color.toString( ) ).css( 'color', wpeasycart_color_tint( ui.color.toString( ), -40 ) );
                if( color_change_timeout )
                    clearTimeout( color_change_timeout );
                color_change_timeout = setTimeout( function( ){
                    var data = {
                        ec_option_admin_color: ui.color.toString( ),
                        action: 'ec_admin_ajax_save_color_scheme',
						wp_easycart_nonce: jQuery( document.getElementById( 'ec_admin_shell_color' ) ).attr( 'data-nonce' )
                    };
                    jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data} );
                }, 2500 );
            }
        } );
    }
    
    /* EDITABLE TABLES*/
	jQuery( '.wpeasycart-editable-table-select-all' ).on( 'click', function( ){
		if( jQuery( this ).is( ':checked' ) ){
			jQuery( this ).parent( ).parent( ).parent( ).parent( ).find( '.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide) .wp-easycart-editable-table-select-item' ).prop( 'checked', true );
		}else{
			jQuery( this ).parent( ).parent( ).parent( ).parent( ).find( '.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide) .wp-easycart-editable-table-select-item' ).prop( 'checked', false );
		}
	} );
    
    var wp_easycart_admin_search_timeout;
    jQuery( '.wp-easycart-editable-table-search-bar > input' ).on( 'change keydown', function( ){
        if( wp_easycart_admin_search_timeout ){
            clearTimeout( wp_easycart_admin_search_timeout );
        }
        var $this = this;
        wp_easycart_admin_search_timeout = setTimeout( function( ){
            var search_string = jQuery( $this ).val( ).toLowerCase( ).trim( );
            jQuery( $this ).parent( ).parent( ).parent( ).find( '.wp-easycart-editable-table-row' ).each( function( ){
                var found = false;
                jQuery( this ).find( '.wp-easycart-editable-table-input, .wp-easycart-editable-table-read-only' ).each( function( ){
                    if( jQuery( this ).hasClass( 'wp-easycart-editable-table-read-only' ) ){
                        if( jQuery( this ).html( ).toLowerCase( ).indexOf( search_string ) >= 0 ){
                           found = true;
                        }

                   }else if( jQuery( this ).is( 'select' ) ){
                        if( jQuery( this ).find( 'option:selected' ).html( ).toLowerCase( ).indexOf( search_string ) >= 0 ){
                           found = true;
                        }

                   }else if( !jQuery( this ).is( ':checkbox' ) && jQuery( this ).val( ).toLowerCase( ).indexOf( search_string ) >= 0 ){
                       found = true;
                   }
                } );

                if( found ){
                    jQuery( this ).removeClass( 'wp-easycart-editable-search-hide' ).show( );
                }else{
                    jQuery( this ).removeClass( 'wp-easycart-editable-search-hide' ).addClass( 'wp-easycart-editable-search-hide' ).hide( );
                }
            } );
            wpeasycart_editable_table_renew_pagination( jQuery( '#' + jQuery( $this ).parent( ).parent( ).parent( ).attr( 'id' ) ) );
        }, 500 );
    } );
    
    jQuery( '.wp-easycart-editable-table th.sortable' ).on( 'click', function( ){
        if( !jQuery( this ).hasClass( 'selected' ) ){
            jQuery( this ).parent( ).find( 'th' ).removeClass( 'selected' ).removeClass( 'reverse' );
            jQuery( this ).addClass( 'selected' );
            wpeasycart_editable_table_sort( jQuery( this ).parent( ).parent( ).parent( ).parent( ).attr( 'id' ), 'asc' );
        
        }else if( jQuery( this ).hasClass( 'reverse' ) ){
            jQuery( this ).removeClass( 'reverse' );
            wpeasycart_editable_table_sort( jQuery( this ).parent( ).parent( ).parent( ).parent( ).attr( 'id' ), 'asc' );
            
        }else{
            jQuery( this ).addClass( 'reverse' );
            wpeasycart_editable_table_sort( jQuery( this ).parent( ).parent( ).parent( ).parent( ).attr( 'id' ), 'desc' );
            
        }
        wpeasycart_editable_table_renew_pagination( jQuery( '#' + jQuery( this ).parent( ).parent( ).parent( ).parent( ).attr( 'id' ) ) );
    } );
	
	jQuery( '.wpeasycart-editable-table-add-new' ).on( 'click', function( ){
		
		var row_ele = jQuery( this ).parent( ).parent( );
		if( !wpeasycart_editable_table_verify_row( row_ele ) ){
			return;
		}
		
		var icon_ele = jQuery( this );
		var table_name = jQuery( this ).attr( 'data-table' );
		var data_function = jQuery( this ).attr( 'data-func' );
		if ( 'show_pro_required' == data_function ) {
			show_pro_required();
			return;
		}
		var data_nonce_field = jQuery( this ).attr( 'data-nonce-field' );
		var callback_function = jQuery( this ).attr( 'data-callback' );
		
		icon_ele.removeClass( 'dashicons-plus' ).addClass( 'dashicons-image-rotate' ).addClass( 'loading' );
		row_ele.addClass( 'loading' );
		
		var data = {
			action: data_function,
			wp_easycart_nonce: jQuery( document.getElementById( data_nonce_field ) ).val()
		};
		
		jQuery( '.' + table_name + '_input_0' ).each( function( ){
			if( jQuery( this ).attr( 'type' ) == 'checkbox' ){
                if( jQuery( this ).is( ':checked' ) ){
                    data[jQuery( this ).attr( 'data-id' )] = 1;
                }else{
                    data[jQuery( this ).attr( 'data-id' )] = 0;
                }
            }else{
                data[jQuery( this ).attr( 'data-id' )] = jQuery( this ).val( );
            }
		} );

		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
			if( callback_function ){
                var fn = window[callback_function];
                if( typeof fn === 'function' ){
                    fn( data, response );
                }
            }
            
			jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row-none' ).hide( );
			
			var new_row = jQuery( '#' + table_name + ' .wp-easycart-editable-table-row-default' ).clone( ).attr( 'data-id', response );
			new_row.removeClass( 'wp-easycart-editable-table-row-default' ).addClass( 'wp-easycart-editable-table-row' );
			new_row.find( '#' + table_name + '_' ).attr( 'id', table_name + '_' + response );
			new_row.find( '#' + table_name + '_group_id_' ).attr( 'id', table_name + '_group_id_' + response );
			new_row.find( 'input.wp-easycart-editable-table-input' ).each( function( ){
				if( jQuery( this ).attr( 'type' ) == 'checkbox' ){
                    if( jQuery( '#' + table_name + '_' +  jQuery( this ).attr( 'data-id' ) + '_0' ).is( ':checked' ) ){
                        jQuery( this ).prop( 'checked', true );
                    }
                }else{
                    jQuery( this ).val( jQuery( '#' + table_name + '_' +  jQuery( this ).attr( 'data-id' ) + '_0' ).val( ) ).attr( 'id', table_name + '_' + jQuery( this ).attr( 'data-id' ) + '_' + response );
                }
			} );
			new_row.find( '.wp-easycart-editable-table-actions > a' ).each( function( ){
				jQuery( this ).attr( 'data-id', response );
			} );
			new_row.find( 'span.select2.select2-container' ).remove( );
			new_row.find( 'select.wp-easycart-editable-table-input' ).each( function( ){
                if( jQuery( this ).hasClass( 'select2-multiple' ) ){
                    var values = jQuery( '#' + table_name + '_' +  jQuery( this ).attr( 'data-id' ) + '_0' ).select2( 'data' );
                    var values_array = new Array( );
                    for( var val_i=0; val_i < values.length; val_i++ ){
                        values_array.push( values[val_i].id );
                    }
                    jQuery( this ).val( values_array ).attr( 'id', table_name + '_' + jQuery( this ).attr( 'data-id' ) + '_' + response );
                    jQuery( this ).select2( {
                        tags: true,
                        data: values,
                        createTag: function (params) {
                            var term = jQuery.trim( params.term );
                            if( term === '' ){
                                return null;
                            }
                            return {
                                id: term,
                                text: term,
                                newTag: true
                            }
                        }
                    } );
                    
                }else{
                    jQuery( this ).val( jQuery( '#' + table_name + '_' +  jQuery( this ).attr( 'data-id' ) + '_0' ).val( ) ).attr( 'id', table_name + '_' + jQuery( this ).attr( 'data-id' ) + '_' + response );
                    
                    if( jQuery( this ).find( 'option:selected' ) && jQuery( this ).find( 'option:selected' ).attr( 'data-group' ) ){
                        new_row.find( '#' + table_name + '_group_id_' + response ).html( jQuery( this ).find( 'option:selected' ).attr( 'data-group' ) );
                    }
                }
			} );
			
			jQuery( '.' + table_name + '_input_0' ).each( function( ){
				if( jQuery( this ).attr( 'type' ) == 'checkbox' ){
					jQuery( this ).prop( "checked", false );
				}else{
					jQuery( this ).val( jQuery( this ).attr( 'data-default' ) );
				}
			} );
			icon_ele.addClass( 'dashicons-plus' ).removeClass( 'dashicons-image-rotate' ).removeClass( 'loading' );
			row_ele.removeClass( 'loading' );
			
			jQuery( '#' + table_name + ' tbody' ).prepend( new_row );
            
            // Reset Search
            jQuery( '#' + table_name + ' .wp-easycart-editable-table-search-bar > input' ).val( '' );
		
			wpeasycart_editable_table_renew_pagination( jQuery( '#' + table_name ) );
		} } );

		return false;
	} );
	
	jQuery( document ).on( 'click', '.wpeasycart-editable-table-delete', function( ){
		wpeasycart_editable_table_delete( jQuery( this ) );
		return false;
	} );
	
	jQuery( document ).on( 'change', '.wp-easycart-editable-table-bulk > select', function( ){
		var table_name = jQuery( this ).attr( 'data-table-id' );
		if( jQuery( this ).val( ) != '' ){
			jQuery( '#' + table_name + ' .wp-easycart-editable-table-bulk-apply' ).removeClass( 'active' ).addClass( 'active' );
		}else{
			jQuery( '#' + table_name + ' .wp-easycart-editable-table-bulk-apply' ).removeClass( 'active' );
		}
	} );
	
	jQuery( document ).on( 'click', '.wp-easycart-editable-table-bulk-apply', function( ){
		var table_name = jQuery( this ).attr( 'data-table-id' );
		if( jQuery( this ).hasClass( 'active' ) ){
			if( jQuery( this ).parent( ).find( 'select' ).val( ) == 'delete' ){
				var delete_rows = [];
				jQuery( '#' + table_name + ' .wp-easycart-editable-table-select-item:checked' ).each( function( ){
					delete_rows.push( jQuery( this ).parent( ).parent( ).find( '.wpeasycart-editable-table-delete' ) );
				} );
                wpeasycart_editable_table_delete_bulk( delete_rows ); 
				wpeasycart_editable_table_renew_pagination( jQuery( '#' + table_name ) );
			}
            jQuery( '#' + table_name + ' .wpeasycart-editable-table-select-all' ).prop( 'checked', false );
		}
		return false;
	} );
	
	jQuery( document ).on( 'focusin', '.wp-easycart-editable-table-input', function( ){
		jQuery( this ).parent( ).find( 'button' ).addClass( 'focused' );
		jQuery( this ).addClass( 'focused' );
	} );
	
	jQuery( document ).on( 'focusout', '.wp-easycart-editable-table-input', function( ){
		jQuery( this ).parent( ).find( 'button' ).removeClass( 'focused' );
		jQuery( this ).removeClass( 'focused' );
	} );
	
	jQuery( document ).on( 'click', '.wp-easycart-editable-table-update-row', function( ){
		var row_ele = jQuery( this ).parent( ).parent( );
		wpeasycart_editable_table_update( row_ele );
		return false;
	} );
	
	jQuery( document ).on( 'change', '.wp-easycart-editable-table-row .wp-easycart-editable-table-input', function( ){
		var row_ele = jQuery( this ).parent( ).parent( );
		wpeasycart_editable_table_update( row_ele );
		return false;
	} );
	
	jQuery( '.wp-easycart-editable-table-pagination > select' ).on( 'change', function( ){
		wpeasycart_editable_table_renew_pagination( jQuery( this ).parent( ).parent( ) );
		jQuery( this ).parent( ).parent( ).find( 'table' ).removeClass( 'pagination-10' ).removeClass( 'pagination-25' ).removeClass( 'pagination-50' ).removeClass( 'pagination-100' ).addClass( 'pagination-' + jQuery( this ).val( ) );
	});
	
	jQuery( document ).on( 'click', '.wp-easycart-editable-table-pagination > ul li', function( ){
		jQuery( this ).parent( ).find( 'li' ).removeClass( 'selected' );
		jQuery( this ).addClass( 'selected' );
		wpeasycart_editable_table_update_page( jQuery( this ).parent( ).parent( ).parent( ) );
	} );
	
	var wp_easycart_admin_image_uploader_ele;
	jQuery( '.wp_easycart_admin_image_upload_button' ).on( 'click', function( ){
		
		wp_easycart_admin_image_uploader_ele = jQuery( this );
		var wp_easycart_admin_image_uploader = wp.media.frames.file_frame = wp.media( {
			title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['choose-image'],
			button: {
				text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['choose-image']
			},
			multiple: false
		} );
 
		wp_easycart_admin_image_uploader.on( 'select', function( ){
			var attachment = wp_easycart_admin_image_uploader.state( ).get( 'selection' ).first( ).toJSON( );
			jQuery( '#' + wp_easycart_admin_image_uploader_ele.attr( 'data-image-id' ) ).attr( "src", attachment.url ).show( );;
			jQuery( '#' + wp_easycart_admin_image_uploader_ele.attr( 'data-input-id' ) ).val( attachment.url ).trigger( 'change' );
			jQuery( '#' + wp_easycart_admin_image_uploader_ele.attr( 'data-delete-id' ) ).show( );
		} );
 
		wp_easycart_admin_image_uploader.open( );
 
	} );
} );

function wp_easycart_admin_remove_image( ele ){
	ele.hide( ).parent( ).find( 'img' ).hide( );
}

function wpeasycart_editable_table_sort( table_id, order ){
    var table = jQuery( '#' + table_id );
    var asc = order === 'asc';
    var tbody = table.find( 'tbody' );
    var column = table.find( 'th.sortable.selected' ).attr( 'data-column' );
    var type = table.find( 'th.sortable.selected' ).attr( 'data-type' );
    tbody.find( 'tr.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).sort( function( a, b ){
        if( type == 'percentage' || type == 'text' ){
            if( asc ){
                return jQuery( 'td[data-column="' + column + '"] input', a ).val( ).localeCompare( jQuery( 'td[data-column="' + column + '"] input', b ).val( ) );
            }else{
                return jQuery( 'td[data-column="' + column + '"] input', b ).val( ).localeCompare( jQuery( 'td[data-column="' + column + '"] input', a ).val( ) );
            }
            
        }else if( type == 'combo' ){
            if( asc ){
                return jQuery( 'td[data-column="' + column + '"] select > option:selected', a ).text( ).localeCompare( jQuery( 'td[data-column="' + column + '"] select > option:selected', b ).text( ) );
            }else{
                return jQuery( 'td[data-column="' + column + '"] select > option:selected', b ).text( ).localeCompare( jQuery( 'td[data-column="' + column + '"] select > option:selected', a ).text( ) );
            }
        
        }else{
            if( asc ){
                return jQuery( 'td[data-column="' + column + '"]', a ).text( ).localeCompare( jQuery( 'td[data-column="' + column + '"]', b ).text( ) );
            }else{
                return jQuery( 'td[data-column="' + column + '"]', b ).text( ).localeCompare( jQuery( 'td[data-column="' + column + '"]', a ).text( ) );
            }
        }
    }).appendTo(tbody);
    tbody.find( '.wp-easycart-editable-table-add-new-break' ).appendTo( tbody );
    tbody.find( '.wp-easycart-editable-table-add-new' ).appendTo( tbody );
}

function wpeasycart_editable_table_renew_pagination( table_ele ){
	var total_rows = table_ele.find( 'tbody > tr.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).length;
	var perpage = Number( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > select' ).val( ) );
	var total_pages = Math.ceil( total_rows / perpage );
	var current_page = Number( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.selected' ).attr( 'data-page' ) );
	if( current_page > total_pages ){
		current_page = 1;
	}
	table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-page-item' ).remove( );
	var first = false;
    var last = false;
    var li_html = '';
    if( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-table-pagination-first' ) ){
        first =  table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-table-pagination-first' );
    }
	for( var i=0; i<total_pages; i++ ){
		li_html += '<li data-page="' + (i+1) + '"';
		if( (i+1) == current_page ){
			li_html += ' class="selected"';
		}
		li_html += '>' + (i+1) + '</li>';
	}
    if( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-table-pagination-last' ) ){
        last = table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-table-pagination-last' ).attr( 'data-page', total_pages );
    }
	if( li_html == '' ){
		li_html = '<li class="selected" data-page="1">1</li>';
	}
	table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul' ).html( li_html );
	if( first ){
        table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul' ).prepend( first );
    }
    if( last ){
        table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul' ).append( last );
    }
    
    table_ele.find( 'select.select2-multiple' ).select2( 'destroy' );
    setTimeout( function( ){
        table_ele.find( 'select.select2-multiple' ).select2( {
            tags: true,
            createTag: function (params) {
                var term = jQuery.trim( params.term );
                if( term === '' ){
                    return null;
                }
                return {
                    id: term,
                    text: term,
                    newTag: true
                }
            }
        } );
    }, 250 );
    
    wpeasycart_editable_table_update_page( table_ele );
}

function wpeasycart_editable_table_update_page( table_ele ){
	var perpage = Number( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > select' ).val( ) );
	var page = Number( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.selected' ).attr( 'data-page' ) );
    var last_page = Number( table_ele.parent( ).find( '.wp-easycart-editable-table-pagination > ul > li.wp-easycart-editable-table-pagination-last' ).attr( 'data-page' ) );
	if( !page ){
		page = 1;
	}
	var total_rows = table_ele.find( 'tbody > tr.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).length;
	
	var show_start = perpage * (page-1);
	var show_end = show_start + perpage;
	if( show_end > total_rows ){
		show_end = total_rows;
	}
	var i=0;
	
	table_ele.find( 'tbody > tr.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).each( function( ){
		if( i >= show_start && i<show_end ){
			jQuery( this ).show( );
		}else{
			jQuery( this ).hide( );
		}
		i++;
	} );
	
	table_ele.parent( ).find( '.wp-easycart-editable-table-paging-showing' ).html( (show_start+1) + '-' + show_end );
	table_ele.parent( ).find( '.wp-easycart-editable-table-paging-total' ).html( total_rows );
    table_ele.find( 'li' ).hide( ).removeClass( 'selected' );
    table_ele.find( 'li' ).each( function( ){
        if( jQuery( this ).hasClass( 'wp-easycart-editable-table-pagination-first' ) || jQuery( this ).hasClass( 'wp-easycart-editable-table-pagination-last' ) ){
            jQuery( this ).show( );
            
        }else{
            if( page == Number( jQuery( this ).attr( 'data-page' ) ) ){
                jQuery( this ).addClass( 'selected' );
            }
            if( page == 1 ){
                if( Number( jQuery( this ).attr( 'data-page' ) ) == 1 || Number( jQuery( this ).attr( 'data-page' ) ) == 2 || Number( jQuery( this ).attr( 'data-page' ) ) == 3 ){
                    jQuery( this ).show( );
                }
            }else if( page == last_page ){
                if( Number( jQuery( this ).attr( 'data-page' ) ) == last_page || Number( jQuery( this ).attr( 'data-page' ) ) == last_page - 1 || Number( jQuery( this ).attr( 'data-page' ) ) == last_page - 2 ){
                    jQuery( this ).show( );
                }
            }else{
                if( Number( jQuery( this ).attr( 'data-page' ) ) == page -1 || Number( jQuery( this ).attr( 'data-page' ) ) == page || Number( jQuery( this ).attr( 'data-page' ) ) == page + 1 ){
                    jQuery( this ).show( );
                }
            }
        }
    } );
}

function wpeasycart_editable_table_verify_row( row_ele ){
	var has_errors = false;
	row_ele.find( '.wp-easycart-editable-table-input-required' ).each( function( ){
		if( jQuery( this ).val( ) == '' || jQuery( this ).val( ) == 0 ){
			has_errors = true;
			jQuery( this ).removeClass( 'error' ).addClass( 'error' );
		}else{
			jQuery( this ).removeClass( 'error' );
		}
	} );
	return !has_errors;
}

function wpeasycart_editable_table_update( row_ele ){
	if( !wpeasycart_editable_table_verify_row( row_ele ) ){
		return;
	}

	var icon_ele = row_ele.find( '.wp-easycart-editable-table-update-row > .dashicons' );
	var table_name = row_ele.parent( ).parent( ).parent( ).attr( 'id' );
	var data_function = jQuery( '#' + table_name + ' .wp-easycart-editable-table' ).attr( 'data-update-func' );
	if ( 'show_pro_required' == data_function ) {
		show_pro_required();
		return;
	}
	var data_nonce_field = jQuery( '#' + table_name + ' .wp-easycart-editable-table' ).attr( 'data-nonce-field' );
	var id = row_ele.attr( 'data-id' );
	
	icon_ele.removeClass( 'dashicons-yes' ).addClass( 'dashicons-image-rotate' ).addClass( 'loading' );
	row_ele.addClass( 'loading' );

	var data = {
		action: data_function,
		id: id,
		wp_easycart_nonce: jQuery( document.getElementById( data_nonce_field ) ).val()
	};
	
	row_ele.find( '.wp-easycart-editable-table-input' ).each( function( ){
        if( jQuery( this ).is( ':checkbox' ) ){
            data[jQuery( this ).attr( 'data-id' )] = 0;
            if( jQuery( this ).is( ':checked' ) ){
                data[jQuery( this ).attr( 'data-id' )] = 1;
            }
        }else{
            data[jQuery( this ).attr( 'data-id' )] = jQuery( this ).val( );
        }
	} );
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		icon_ele.removeClass( 'dashicons-image-rotate' ).addClass( 'dashicons-yes' ).removeClass( 'loading' );
		row_ele.removeClass( 'loading' );
	} } );
}

function wpeasycart_editable_table_delete_bulk( eles ){
    var table_name = eles[0].attr( 'data-table' );
	var data_function = eles[0].attr( 'data-func' );
	if ( 'show_pro_required' == data_function ) {
		show_pro_required();
		return;
	}
	var data_nonce_field = eles[0].attr( 'data-nonce-field' );
	var callback_function = eles[0].attr( 'data-callback' );
	var ids = [];
    
    for( var i=0; i<eles.length; i++ ){
        var icon_ele = eles[i].find( '.dashicons' );
        var row_ele = eles[i].parent( ).parent( );
        var id = eles[i].attr( 'data-id' );
        
        row_ele.addClass( 'loading' );
        icon_ele.removeClass( 'dashicons-trash' ).addClass( 'dashicons-image-rotate' );
        ids.push( id );
    };
    
    var data = {
		action: data_function,
		id: ids,
		wp_easycart_nonce: jQuery( document.getElementById( data_nonce_field ) ).val()
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
		if( callback_function ){
            var fn = window[callback_function];
            if( typeof fn === 'function' ){
                fn( data, response );
            }
        }
        
        for( var i=0; i<eles.length; i++ ){
            var row_ele = eles[i].parent( ).parent( );
            row_ele.remove( );
        };

		if( jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).length == 0 ){
			jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row-none' ).show( );
		}else{
			jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row-none' ).hide( );
		}
        
        // Reset Search
        jQuery( '#' + table_name + ' .wp-easycart-editable-table-search-bar > input' ).val( '' );
		
		wpeasycart_editable_table_renew_pagination( jQuery( '#' + table_name ) );
	} } );
}

function wpeasycart_editable_table_delete( ele ){
	var icon_ele = ele.find( '.dashicons' );
	var row_ele = ele.parent( ).parent( );
	var table_name = ele.attr( 'data-table' );
	var data_function = ele.attr( 'data-func' );
	if ( 'show_pro_required' == data_function ) {
		show_pro_required();
		return;
	}
	var data_nonce_field = ele.attr( 'data-nonce-field' );
	var callback_function = ele.attr( 'data-callback' );
	var id = ele.attr( 'data-id' );

	row_ele.addClass( 'loading' );
	icon_ele.removeClass( 'dashicons-trash' ).addClass( 'dashicons-image-rotate' );

	var data = {
		action: data_function,
		id: id,
		wp_easycart_nonce: jQuery( document.getElementById( data_nonce_field ) ).val()
	};

	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function( response ){ 
		if( callback_function ){
            var fn = window[callback_function];
            if( typeof fn === 'function' ){
                fn( data, response );
            }
        }
        
        row_ele.remove( );

		if( jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row:not(.wp-easycart-editable-search-hide)' ).length == 0 ){
            jQuery( '#' + table_name + ' .wp-easycart-editable-table-search-bar > input' ).val( '' ).trigger( 'change' );
		
        }else{
			jQuery( '#' + table_name ).find( '.wp-easycart-editable-table-row-none' ).hide( );
		}
		
		wpeasycart_editable_table_renew_pagination( jQuery( '#' + table_name ) );
	} } );
}

function wpeasycart_color_tint( hex, lum ){
    lum = lum/100;
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
	if (hex.length < 6) {
		hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
	}
	lum = lum || 0;
    var rgb = "#", c, i;
	for (i = 0; i < 3; i++) {
		c = parseInt(hex.substr(i*2,2), 16);
		c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
		rgb += ("00"+c).substr(c.length);
    }
    return rgb;
}

/* Sort Functions */
function save_sort_order( table, nonce ){
	var curr_page = 0;
	if( jQuery( document.getElementById( 'pagenum' ) ).length )
		curr_page = jQuery( document.getElementById( 'pagenum' ) ).val( );
	var page_length = 25;
	if( jQuery( document.getElementById( 'perpage' ) ).length )
		page_length = jQuery( document.getElementById( 'perpage' ) ).val( );
	var rows = jQuery( 'table#' + table + ' tbody tr' );
	var ids = Array( );
	var id=0;
	var start_sort = curr_page * page_length;
	for( var i=0; i<rows.length; i++ ){
		ids.push( { id:jQuery( rows[i] ).attr( 'data-id' ), order: Number( start_sort + i ) } );
	}
	
	if( table == 'ec_optionitem_table' ){
		jQuery( document.getElementById( "ec_admin_table_display_loader" ) ).fadeIn( 'fast' );
		var data = {
			option_id: jQuery( document.getElementById( 'option_id' ) ).val( ),
			sort_order: ids,
			action: 'ec_admin_ajax_save_optionitem_order',
			wp_easycart_nonce: nonce
		};
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			ec_admin_hide_loader( 'ec_admin_table_display_loader' );
		} } );
	
	}else if( table == 'ec_admin_category_list' ){
		jQuery( document.getElementById( "ec_admin_table_display_loader" ) ).fadeIn( 'fast' );
		var data = {
			parent_id: jQuery( document.getElementById( 'parent_id' ) ).val( ),
			sort_order: ids,
			action: 'ec_admin_ajax_save_category_order',
			wp_easycart_nonce: nonce
		};
		jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){
			ec_admin_hide_loader( 'ec_admin_table_display_loader' );
		} } );
	}
}

/* Slidout Functions */
function wp_easycart_admin_open_slideout( id ){
	jQuery( document.getElementById( id ) ).fadeIn( );
	setTimeout( function( ){
		jQuery( document.getElementById( id ) ).find( '.ec_admin_slideout_container_content' ).animate( {"right": '+=600'}, 300 );
	}, 200 );
}

function wp_easycart_admin_close_slideout( id ){
	jQuery( document.getElementById( id ) ).find( '.ec_admin_slideout_container_content' ).animate( {"right": '-=600'}, 300 );
	setTimeout( function( ){
		jQuery( document.getElementById( id ) ).fadeOut( );
	}, 200 );
}

/* HELP VIDEOS */
function wp_easycart_admin_open_video_help( video_id ){
    jQuery( "#wp_easycart_admin_help_video_player" ).attr( "src", 'https://www.youtube.com/embed/' + video_id + '?enablejsapi=1&widgetid=1' );
	jQuery( '.ec_admin_help_video_container' ).show( );
}
function wp_easycart_admin_close_video_help( ){
	jQuery( '.ec_admin_help_video_container' ).hide( );
}

/* IMPORTER */
function ec_admin_importer_open_close(id) {
    jQuery(document.getElementById(id)).fadeToggle("fast");
}

/* bulk action delay for 5 seconds to prevent double clicks */
function ec_bulk_disable() {
  button = document.getElementById("doaction");
  button.setAttribute("disabled","disabled");
  button.value="Processing...";
  setTimeout(ec_bulk_enable, 3000);
}

function ec_bulk_enable() {
  button.disabled = false;
  button.value="Apply";
}

/* Mobile Menu Functions */
function ec_admin_open_mobile_menu( ){
	jQuery( document.getElementById( 'ec_admin_mobile_menu_main' ) ).show( ).animate( {left:0}, 200 );
}
function ec_admin_hide_mobile_menu( ){
	jQuery( document.getElementById( 'ec_admin_mobile_menu_main' ) ).animate( {left:'-100%'}, 200 );
}
function ec_admin_show_mobile_submenu( submenu ){
	jQuery( document.getElementById( 'ec_admin_mobile_menu_' + submenu ) ).show( ).animate( {left:0}, 200 );
}
function ec_admin_hide_mobile_submenu( submenu ){
	jQuery( document.getElementById( 'ec_admin_mobile_menu_' + submenu ) ).animate( {left:'-100%'}, 200 );
}

/* GENERAL FUNCTIONS */
function ec_admin_get_value( item_id, type ){
	var item_value = 0;
	if( type == "checkbox" ){
		if( jQuery( document.getElementById( item_id ) ).is(':checked') )
			item_value = 1;
	}else{
		item_value = jQuery( document.getElementById( item_id ) ).val( );
	}
	return item_value;
}

function ec_admin_hide_loader( loader_id ){
	jQuery( document.getElementById( loader_id ) ).delay( 1200 ).fadeOut( 'slow' );
	jQuery( "#" + loader_id + " > .ec_admin_loader_animation" ).hide( );
	jQuery( "#" + loader_id + " > .ec_admin_loader_loaded" ).fadeIn( 'fast' );
	// After Animation, Reset
	setTimeout( function( ){
		jQuery( "#" + loader_id + " > .ec_admin_loader_animation" ).show( );
		jQuery( "#" + loader_id + " > .ec_admin_loader_loaded" ).hide( ); 
	}, 2000 );
}

/* MENU FUNCTIONS */
function ec_admin_show_products_submenu(currentElement ){
	
	if( !jQuery( document.getElementById( 'ec_admin_products_submenu' ) ).hasClass( 'ec_admin_left_submenu_open' ) ){
		jQuery( '.ec_admin_left_submenu_open' ).slideUp( ).removeClass( 'ec_admin_left_submenu_open' );
		jQuery( document.getElementById( 'ec_admin_products_submenu' ) ).slideDown( ).addClass( 'ec_admin_left_submenu_open' );
		jQuery( 'div').removeClass( 'ec_admin_left_nav_selected' );
		jQuery( currentElement).addClass( 'ec_admin_left_nav_selected' );
		jQuery( document.getElementById( 'toplevel_page_wp-easycart-dashboard' ) ).find( 'ul > li' ).each( function( ){
			jQuery( this ).removeClass( 'current' );
			if( jQuery( this ).find( 'a' ).html( ) == "Products" ){
				jQuery( this ).addClass( 'current' );
			}
		} );
		jQuery (document.getElementById('ec_admin_products_submenu_item')).addClass('ec_admin_left_nav_selected');
	}
}

function ec_admin_show_orders_submenu(currentElement ){
	
	if( !jQuery( document.getElementById( 'ec_admin_orders_submenu' ) ).hasClass( 'ec_admin_left_submenu_open' ) ){
		jQuery( '.ec_admin_left_submenu_open' ).slideUp( ).removeClass( 'ec_admin_left_submenu_open' );
		jQuery( document.getElementById( 'ec_admin_orders_submenu' ) ).slideDown( ).addClass( 'ec_admin_left_submenu_open' );
		jQuery( 'div').removeClass( 'ec_admin_left_nav_selected' );
		jQuery( currentElement).addClass( 'ec_admin_left_nav_selected' );
		jQuery( document.getElementById( 'toplevel_page_wp-easycart-dashboard' ) ).find( 'ul > li' ).each( function( ){
			jQuery( this ).removeClass( 'current' );
			if( jQuery( this ).find( 'a' ).html( ) == "Orders" ){
				jQuery( this ).addClass( 'current' );
			}
		} );
		jQuery (document.getElementById('ec_admin_orders_submenu_item')).addClass('ec_admin_left_nav_selected');
	}
}

function ec_admin_show_users_submenu(currentElement ){
	
	if( !jQuery( document.getElementById( 'ec_admin_users_submenu' ) ).hasClass( 'ec_admin_left_submenu_open' ) ){
		jQuery( '.ec_admin_left_submenu_open' ).slideUp( ).removeClass( 'ec_admin_left_submenu_open' );
		jQuery( document.getElementById( 'ec_admin_users_submenu' ) ).slideDown( ).addClass( 'ec_admin_left_submenu_open' );
		jQuery( 'div').removeClass( 'ec_admin_left_nav_selected' );
		jQuery( currentElement).addClass( 'ec_admin_left_nav_selected' );
		jQuery( document.getElementById( 'toplevel_page_wp-easycart-dashboard' ) ).find( 'ul > li' ).each( function( ){
			jQuery( this ).removeClass( 'current' );
			if( jQuery( this ).find( 'a' ).html( ) == "Users" ){
				jQuery( this ).addClass( 'current' );
			}
		} );
		jQuery (document.getElementById('ec_admin_accounts_submenu_item')).addClass('ec_admin_left_nav_selected');
	}
}

function ec_admin_show_rates_submenu(currentElement ){
	
	if( !jQuery( document.getElementById( 'ec_admin_rates_submenu' ) ).hasClass( 'ec_admin_left_submenu_open' ) ){
		jQuery( '.ec_admin_left_submenu_open' ).slideUp( ).removeClass( 'ec_admin_left_submenu_open' );
		jQuery( document.getElementById( 'ec_admin_rates_submenu' ) ).slideDown( ).addClass( 'ec_admin_left_submenu_open' );
		jQuery( 'div').removeClass( 'ec_admin_left_nav_selected' );
		jQuery( currentElement).addClass( 'ec_admin_left_nav_selected' );
		jQuery( document.getElementById( 'toplevel_page_wp-easycart-dashboard' ) ).find( 'ul > li' ).each( function( ){
			jQuery( this ).removeClass( 'current' );
			if( jQuery( this ).find( 'a' ).html( ) == "Rates" ){
				jQuery( this ).addClass( 'current' );
			}
		} );
		jQuery (document.getElementById('ec_admin_giftcards_submenu_item')).addClass('ec_admin_left_nav_selected');
	}
}

function ec_admin_show_registration_submenu( currentElement){
	jQuery( 'div').removeClass( 'ec_admin_left_nav_selected' );
	jQuery( currentElement).addClass( 'ec_admin_left_nav_selected' );
	jQuery( '.ec_admin_left_submenu_open' ).slideUp( ).removeClass( 'ec_admin_left_submenu_open' );

}

function ec_admin_update_submenu_item_display( hash ){
	if( hash != "" ){
		hash = hash.substring( 1 ); 
		jQuery( '.ec_admin_left_nav_subitem' ).removeClass( 'ec_admin_left_nav_selected' );
		jQuery( document.getElementById( 'ec_admin_' + hash + '_submenu_item' ) ).addClass( 'ec_admin_left_nav_selected' );
	}
}

function ec_admin_show_hide_update( id, value, show_id ){
	if( jQuery( document.getElementById( id ) ).is( ':checkbox' ) ){
		if( jQuery( document.getElementById( id ) ).is( ':checked' ) && value == '1' ){
			jQuery( document.getElementById( show_id ) ).show( );
		}else if( !jQuery( document.getElementById( id ) ).is( ':checked' ) && value == '0' ){
			jQuery( document.getElementById( show_id ) ).show( );
		}else{
			jQuery( document.getElementById( show_id ) ).hide( );
		}
	}else if( jQuery( document.getElementById( id ) ).is( 'select' ) ){
		if( jQuery( document.getElementById( id ) ).val( ) == value ){
			jQuery( document.getElementById( show_id ) ).show( );
		}else{
			jQuery( document.getElementById( show_id ) ).hide( );
		}
	}
}

function ec_admin_download_upload( field ){
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	if( file_frame ){
		file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		file_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media( {
		title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['select-file'],
		button: {
			text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['use-file'],
		},
		multiple: false
	} );
	
	file_frame.uploader.options.uploader.params.is_wpec_download = '1';

	file_frame.on( 'select', function() {
		attachment = file_frame.state( ).get( 'selection' ).first( ).toJSON( );
		jQuery( document.getElementById( field ) ).val( attachment.url );
		if( jQuery( document.getElementById( field + '_preview'  ) ).length )
			document.getElementById( field + '_preview'  ).src = attachment.url;
		wp.media.model.settings.post.id = wp_media_post_id;
	});
	
	file_frame.open( );
}

function ec_admin_file_upload( field ){
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	if( file_frame ){
		file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		file_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media( {
		title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['select-file'],
		button:{
			text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['use-file'],
		},
		multiple: false	// Set to true to allow multiple files to be selected
	} );
	
	file_frame.on( 'select', function( ){
		attachment = file_frame.state( ).get( 'selection' ).first( ).toJSON( );
		jQuery( document.getElementById( field ) ).val( attachment.url );
		if( jQuery( document.getElementById( field + '_preview'  ) ).length )
			document.getElementById( field + '_preview'  ).src = attachment.url;
		wp.media.model.settings.post.id = wp_media_post_id;
	} );
	
	file_frame.open();	
}

function ec_admin_image_upload( field ){
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	if( file_frame ){
		file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		file_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media( {
		title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['select-image'],
		button:{
			text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['use-image'],
		},
		multiple: false
	});
	
	file_frame.on( 'select', function( ){
		attachment = file_frame.state().get( 'selection' ).first( ).toJSON( );
		jQuery( document.getElementById( field ) ).val( attachment.url );
		if( jQuery( document.getElementById( field + '_preview'  ) ).length )
			document.getElementById( field + '_preview'  ).src = attachment.url;
		if( jQuery( document.getElementById( field + '_id'  ) ).length )
			jQuery( document.getElementById( field + '_id'  ) ).val( attachment.id );
		wp.media.model.settings.post.id = wp_media_post_id;
	} );
		
	file_frame.open();
	
	jQuery( '#ec_admin_row_' + field + '_preview > button' ).show( );
		
}

function ec_admin_image_upload_wp( field ){
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	if( file_frame ){
		file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		file_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media( {
		title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['select-image'],
		button:{
			text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['use-image'],
		},
		multiple: false
	});
	
	file_frame.on( 'select', function( ){
		attachment = file_frame.state().get( 'selection' ).first( ).toJSON( );
		jQuery( document.getElementById( field ) ).val( attachment.id );
		if( jQuery( document.getElementById( field + '_preview'  ) ).length )
			document.getElementById( field + '_preview'  ).src = attachment.url;
		if( jQuery( document.getElementById( field + '_id'  ) ).length )
			jQuery( document.getElementById( field + '_id'  ) ).val( attachment.id );
		wp.media.model.settings.post.id = wp_media_post_id;
	} );
		
	file_frame.open();
	
	jQuery( '#ec_admin_row_' + field + '_preview > button' ).show( );
		
}

function ec_admin_import_file_upload( field, import_button, status_field, browse_button, browse_label ){
	
	jQuery( document.getElementById(status_field)).fadeOut("fast");
	
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id;
	var set_to_post_id;

	if( file_frame ){
		file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
		file_frame.open( );
		return;
	}else{
		wp.media.model.settings.post.id = set_to_post_id;
	}
	
	file_frame = wp.media.frames.file_frame = wp.media( {
		title: wpeasycart_admin_ajax_object.wp_easycart_admin_language['select-import-file'],
		button:{
			text: wpeasycart_admin_ajax_object.wp_easycart_admin_language['use-file'],
		},
		multiple: false
	} );
	
	file_frame.on( 'select', function( ){
		attachment = file_frame.state( ).get( 'selection' ).first( ).toJSON( );
		jQuery( document.getElementById( field ) ).val( attachment.id );
		jQuery( document.getElementById( browse_button ) ).val( browse_label + ' (' + attachment.filename + ')' );
		jQuery( document.getElementById( import_button ) ).fadeIn("fast" );
		jQuery( document.getElementById( status_field ) ).fadeOut( "fast" );
		wp.media.model.settings.post.id = wp_media_post_id;
	} );
	
	file_frame.open( );	
}

function ec_admin_delete_image( id ){
	jQuery( document.getElementById( id ) ).val( '' );
	document.getElementById( id + '_preview' ).src = '';
	jQuery( '#ec_admin_row_' + id + '_preview > button' ).hide( );
}

function show_pro_required( custom_view ){
	custom_view = custom_view || 0;
	jQuery( document.body ).addClass( 'ec_admin_upsell_noscroll' );
	jQuery( document.getElementById( 'ec_admin_upsell_popup' ) ).show( );
	jQuery( document.getElementById( 'wp_easycart_trial_upsell' ) ).show( );
	jQuery( '.ec_admin_upsell_popup_extras' ).hide( );
	if( custom_view && jQuery( document.getElementById( 'ec_admin_upsell_popup_' + custom_view ) ).length ){
		jQuery( document.getElementById( 'ec_admin_upsell_popup_' + custom_view ) ).show( );
		jQuery( document.getElementById( 'wp_easycart_trial_upsell' ) ).hide( );
	}
	return false;
}

function show_pro_required_optionitem_images( custom_view_unused ) {
	if ( ! jQuery( document.getElementById( 'use_optionitem_images' ) ).is( ':checked' ) ) {
		jQuery( '.wp-easycart-admin-product-details-images-locked' ).removeClass( 'wp-easycart-admin-product-details-images-locked' ).hide();
		jQuery( '#ec_admin_row_image1' ).show();
		jQuery( '#ec_admin_row_image2' ).show();
		var data = {
			action: 'ec_admin_ajax_save_product_details_is_optionitem_images',
			product_id: ec_admin_get_value( 'product_id', 'hidden' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
		};
		jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
		return;
	} else {
		jQuery( document.body ).addClass( 'ec_admin_upsell_noscroll' );
		jQuery( document.getElementById( 'ec_admin_upsell_popup' ) ).show( );
		jQuery( document.getElementById( 'wp_easycart_trial_upsell' ) ).show( );
		jQuery( '.ec_admin_upsell_popup_extras' ).hide( );
		return false;
	}
}

function show_pro_required_advanced_options( custom_view_unused ) {
	if ( ! jQuery( document.getElementById( 'use_advanced_optionset' ) ).is( ':checked' ) ) {
		jQuery( '#ec_admin_row_option1' ).show();
		jQuery( '#ec_admin_row_option2' ).show();
		jQuery( '#ec_admin_row_option3' ).show();
		jQuery( '#ec_admin_row_option4' ).show();
		jQuery( '#ec_admin_row_option5' ).show();
		var data = {
			action: 'ec_admin_ajax_save_product_details_is_advanced_options',
			product_id: ec_admin_get_value( 'product_id', 'hidden' ),
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_product_details_nonce', 'text' )
		};
		jQuery.ajax( {url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data } );
		return;
	} else {
		jQuery( document.body ).addClass( 'ec_admin_upsell_noscroll' );
		jQuery( document.getElementById( 'ec_admin_upsell_popup' ) ).show( );
		jQuery( document.getElementById( 'wp_easycart_trial_upsell' ) ).show( );
		jQuery( '.ec_admin_upsell_popup_extras' ).hide( );
		return false;
	}
}

function hide_pro_required( ){
	jQuery( document.body ).removeClass( 'ec_admin_upsell_noscroll' );
	jQuery( document.getElementById( 'ec_admin_upsell_popup' ) ).hide( );
}

/* Wizard */
function wp_easycart_update_wizard_update_tax( ){
	wp_easycart_update_wizard_locale( );
	wp_easycart_update_wizard_show_tax( );
}

function wp_easycart_update_wizard_show_tax( ){
	var val = jQuery( document.getElementById( 'wp_easycart_locale' ) ).val( );
	if( jQuery( document.getElementById( 'wp_easycart_sales_tax' ) ).is( ':checked' ) && jQuery( '.wp_easycart_wizard_tax_' + val ).length ){
		jQuery( document.getElementById( 'wp_easycart_wizard_tax_info' ) ).show( );
	}else{
		jQuery( document.getElementById( 'wp_easycart_wizard_tax_info' ) ).hide( );
	}
}

function wp_easycart_update_wizard_locale( ){
	jQuery( '.wp_easycart_wizard_tax_row' ).hide( );
	var val = jQuery( document.getElementById( 'wp_easycart_locale' ) ).val( );
	jQuery( '.wp_easycart_wizard_tax_' + val ).show( );
	if( jQuery( '#wp_easycart_locale > option:selected' ).attr( 'data-currency' ) != '' ){
		jQuery( document.getElementById( 'wp_easycart_currency' ) ).val( jQuery( '#wp_easycart_locale > option:selected' ).attr( 'data-currency' ) ).trigger('change');
	}
}

function wp_easycart_wizard_use_paypal( ){
	if( jQuery( document.getElementById( 'wp_easycart_paypal_standard' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'wp_easycart_paypal_standard' ) ).prop( 'checked', false );
	}else{
		jQuery( document.getElementById( 'wp_easycart_paypal_standard' ) ).prop( 'checked', true );
	}
}

function wp_easycart_wizard_use_stripe( ){
	if( !jQuery( document.getElementById( 'wp_easycart_use_stripe' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'wp_easycart_use_stripe' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'wp_easycart_use_square' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'use_stripe_content' ) ).show( );
		jQuery( document.getElementById( 'use_square_content' ) ).hide( );
		return true;
	}else{
		jQuery( document.getElementById( 'wp_easycart_use_stripe' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'use_stripe_content' ) ).hide( );
		return false;
	}
}

function wp_easycart_wizard_use_square( ){
	if( !jQuery( document.getElementById( 'wp_easycart_use_square' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'wp_easycart_use_stripe' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'wp_easycart_use_square' ) ).prop( 'checked', true );
		jQuery( document.getElementById( 'use_square_content' ) ).show( );
		jQuery( document.getElementById( 'use_stripe_content' ) ).hide( );
		return true;
	}else{
		jQuery( document.getElementById( 'wp_easycart_use_square' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'use_square_content' ) ).hide( );
		return false;
	}
}

function wp_easycart_allow_tracking( nonce ){
	var data = {
		action: 'ec_admin_ajax_allow_tracking',
		wp_easycart_nonce: nonce
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ } } );
}

function wp_easycart_deny_tracking( nonce ){
	var data = {
		action: 'ec_admin_ajax_deny_tracking',
		wp_easycart_nonce: nonce
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ } } );
}

function wp_easycart_admin_close_review( nonce ){
	jQuery( '.wp-easycart-admin-review-us-box' ).fadeOut( 'slow' );
	var data = {
		action: 'ec_admin_ajax_close_review_us',
		wp_easycart_nonce: nonce
	};
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ } } );
}

function ec_admin_install_demo_data( ){
	
	jQuery( document.getElementById( "ec_admin_demo_data_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_install_demo_data',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_demo_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		// Start Saved Animation
		jQuery( document.getElementById( "ec_admin_demo_data_loader" ) ).delay( 1200 ).fadeOut( 'slow' );
		jQuery( "#ec_admin_demo_data_loader > .ec_admin_loader_animation" ).hide( );
		jQuery( "#ec_admin_demo_data_loader > .ec_admin_loader_loaded" ).fadeIn( 'fast' );
		// After Animation, Reset
		setTimeout( function( ){
			jQuery( "#ec_admin_demo_data_loader > .ec_admin_loader_animation" ).show( );
			jQuery( "#ec_admin_demo_data_loader > .ec_admin_loader_loaded" ).hide( );
			jQuery( "#install_demo_data" ).hide( );
			jQuery( "#uninstall_demo_data" ).show( );
		}, 1200 );
		ec_admin_hide_loader( 'ec_admin_demo_data_loader' );
		
		if( jQuery( document.getElementById( 'easycart_wizard_demo_data' ) ).length ){
			jQuery( document.getElementById( 'easycart_wizard_demo_data' ) ).hide( );
			jQuery( document.getElementById( 'easycart_wizard_demo_data_done' ) ).show( );
		}
	} } );
	
	return false;
	
}

function ec_admin_uninstall_demo_data( ){
	
	jQuery( document.getElementById( "ec_admin_uninstall_demo_data_loader" ) ).fadeIn( 'fast' );
	
	var data = {
		action: 'ec_admin_ajax_uninstall_demo_data',
        wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_demo_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		setTimeout( function( ){
			jQuery( "#install_demo_data" ).show( );
			jQuery( "#uninstall_demo_data" ).hide( );
		}, 1200 );
		ec_admin_hide_loader( 'ec_admin_uninstall_demo_data_loader' );
	} } );
	
	return false;
	
}

function wp_easycart_open_quick_edit( type, id ){
	if( type == 'order' ){
		wp_easycart_open_order_quick_edit( id );
	}else if( type == 'product' ){
		wp_easycart_open_product_quick_edit( id );
	}
}