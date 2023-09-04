// JavaScript Document
jQuery( document ).ready( function( ){
	jQuery( '.ec_flipbook_left' ).click( 
		function( event ){
			var current_image = jQuery( event.target ).parent( ).find( 'img.ec_flipbook_image' ).attr( 'src' );
			var image_list_string = jQuery( event.target ).parent( ).data( 'image-list' );
			var image_list = image_list_string.split( ',' );
			var prev = image_list[image_list.length - 1]; 
			for( var i=0; i<image_list.length; i++ ){ 
				if( image_list[i] == current_image ){ 
					break; 
				}else{ 
					prev = image_list[i]; 
				} 
			}
			jQuery( event.target ).parent( ).find( 'img.ec_flipbook_image' ).attr( 'src', prev );
		}
	);
	jQuery( '.ec_flipbook_right' ).click( 
		function( event ){
			var current_image = jQuery( event.target ).parent( ).find( 'img.ec_flipbook_image' ).attr( 'src' );
			var image_list_string = jQuery( event.target ).parent( ).data( 'image-list' );
			var image_list = image_list_string.split( ',' );
			var prev = image_list[0]; 
			for( var i=image_list.length-1; i>-1; i-- ){ 
				if( image_list[i] == current_image ){ 
					break; 
				}else{ 
					prev = image_list[i]; 
				} 
			}
			jQuery( event.target ).parent( ).find( 'img.ec_flipbook_image' ).attr( 'src', prev );
		}
	);
    jQuery( '.ec_product_shortcode .owl-carousel' ).each( function( ){
        jQuery( this ).on({
            'initialized.owl.carousel': function( ){
                jQuery( this ).find( '.wp-easycart-carousel-item' ).show( );
                jQuery( this ).parent( ).find( '.wpec-product-slider-loader' ).hide( );
            }

        }).owlCarousel( JSON.parse( jQuery( this ).attr( 'data-owl-options' ) ) );
    } );
	wpeasycart_cart_billing_country_update( );
	wpeasycart_cart_shipping_country_update( );
	wpeasycart_account_billing_country_update( );
	wpeasycart_account_shipping_country_update( );
	jQuery( document.getElementById( 'ec_cart_billing_country' ) ).change( function( ){ wpeasycart_cart_billing_country_update( ); } );
	jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).change( function( ){ wpeasycart_cart_shipping_country_update( ); } );
	jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).change( function( ){ wpeasycart_account_billing_country_update( ); } );
	jQuery( document.getElementById( 'ec_account_shipping_information_country' ) ).change( function( ){ wpeasycart_account_shipping_country_update( ); } );
	if( jQuery( '.ec_menu_mini_cart' ).length ){
		jQuery( document.getElementById( 'ec_card_number' ) ).keydown( function( ){
			ec_show_cc_type( ec_get_card_type( jQuery( document.getElementById( 'ec_card_number' ) ).val( ) ) )
		} );
		// Load cart menu, updates over possible cached value
		var data = {
			action: 'ec_ajax_get_dynamic_cart_menu',
			language: wpeasycart_ajax_object.current_language,
			nonce: jQuery( '.ec_menu_mini_cart' ).attr( 'data-nonce' )
		};
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( '.ec_menu_mini_cart' ).html( data );
		} } );
	}
	if( wpeasycart_isTouchDevice( ) ){
		jQuery( '.ec_product_quickview' ).hide( );
	}
    if( jQuery( document.getElementById( 'ec_card_number' ) ).length ){
	   jQuery( document.getElementById( 'ec_card_number' ) ).payment( 'formatCardNumber' );
	}
    if( jQuery( document.getElementById( 'ec_cc_expiration' ) ).length ){
	   jQuery( document.getElementById( 'ec_cc_expiration' ) ).payment( 'formatCardExpiry' );
    }
    if( jQuery( document.getElementById( 'ec_security_code' ) ).length ){
	   jQuery( document.getElementById( 'ec_security_code' ) ).payment( 'formatCardCVC' );
    }
    
	if( jQuery( '.ec_is_datepicker' ).length ){
        jQuery( '.ec_is_datepicker' ).datepicker( );
    }
	
    if( jQuery( '.ec_details_customer_review_list' ).length ){
        jQuery( '.ec_details_customer_review_list' ).each( function( ){
            var reviews = jQuery( this ).find( 'li' );
            var product_id = jQuery( this ).attr( 'data-product-id' );
            var reviews_per_page = jQuery( document.getElementById( 'ec_details_reviews_per_page_' + product_id ) ).val( );
            if( reviews.length > reviews_per_page ){
                var paging = '<div class="ec_details_customer_review_paging" id="ec_details_customer_review_paging_' + product_id + '">';
                for( var i=0; i<Math.ceil( reviews.length / reviews_per_page ); i++ ){
                    paging += '<button onclick="ec_customer_review_paging( ' + (i+1) + ', ' + product_id + ' );">' + ( i + 1 ) + '</button>';
                }
                paging += '</div>';
                jQuery( this ).after( paging );
                ec_customer_review_paging(1, product_id);
            }
        } );
	}
	if( jQuery( '.ec_product_sidebar_filter_item' ).length ){
		jQuery( '.ec_product_sidebar_filter_item' ).on( 'click', function( ){
			if( jQuery( this ).hasClass( 'selected' ) ){
				jQuery( this ).removeClass( 'selected' );
			}else{
				jQuery( this ).addClass( 'selected' );
			}
			var filter_ids = [];
			jQuery( '.ec_product_sidebar_filter_item.selected' ).each( function( ){
				filter_ids.push( jQuery( this ).attr( 'data-optionitemid' ) );
			} );
			var category_ids = [];
			jQuery( '.ec_product_sidebar_filter_item.selected' ).each( function( ){
				filter_ids.push( jQuery( this ).attr( 'data-categoryid' ) );
			} );
			jQuery( this ).parent( ).parent( ).parent( ).parent( ).find( '.ec_product_page_content' ).html( '</div><style>@keyframes rotation{0%{transform:rotate(0deg);}100%{ transform:rotate(359deg); } }</style><div class="wpec-product-slider-loader" style="font-size:14px; text-align:center; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; box-sizing:border-box; width:350px; top:200px; left:50%; position:absolute; margin-left:-165px; margin-top:-80px; cursor:pointer; text-align:center; z-index:99;"><div><div style="height:30px; width:30px; display:inline-block; box-sizing:content-box; opacity:1; filter:alpha(opacity=100); -webkit-animation:rotation .7s infinite linear; -moz-animation:rotation .7s infinite linear;-o-animation: rotation .7s infinite linear; animation:rotation .7s infinite linear; border-left:8px solid rgba(0, 0, 0, .2); border-right:8px solid rgba(0, 0, 0, .2); border-bottom:8px solid rgba(0, 0, 0, .2); border-top:8px solid #fff; border-radius:100%;"></div></div></div>' );
			var data = {
				action: 'ec_ajax_update_product_page_content',
				filter_ids: filter_ids,
				category_ids: category_ids,
				nonce: jQuery( '.ec_product_sidebar_filter_item' ).attr( 'data-nonce' )
			};
		} );
	}
	if( jQuery( '.ec_product_page_with_sidebar.ec_product_page_sidebar_left, .ec_product_page_sidebar_right > .ec_product_page_sidebar' ).length ){
		jQuery( '.ec_product_page_sidebar_left > .ec_product_page_sidebar, .ec_product_page_sidebar_slide-right > .ec_product_page_sidebar' ).clone().appendTo( document.body ).addClass( 'ec_product_page_sidebar_mobile_only' ).addClass( 'ec_product_page_sidebar_slide-left' ).find( '.ec_product_sidebar_filter_item' ).removeClass( 'ec_product_sidebar_filter_item' ).addClass( 'ec_product_sidebar_filter_item_mobile' );
		jQuery( '.ec_product_sidebar_filter_item_mobile' ).on( 'click', function() {
			if ( jQuery( this ).hasClass( 'selected' ) ) {
				jQuery( this ).removeClass( 'selected' );
			} else {
				jQuery( this ).addClass( 'selected' );
			}
			jQuery( '.ec_product_page_content' ).html( '<div style="min-height:400px; width:100%; float:left;"><style>@keyframes rotation{0%{transform:rotate(0deg);}100%{ transform:rotate(359deg); } }</style><div class="wpec-product-slider-loader" style="font-size:14px; text-align:center; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; box-sizing:border-box; width:350px; top:200px; left:50%; position:absolute; margin-left:-165px; margin-top:-80px; cursor:pointer; text-align:center; z-index:99;"><div><div style="height:30px; width:30px; display:inline-block; box-sizing:content-box; opacity:1; filter:alpha(opacity=100); -webkit-animation:rotation .7s infinite linear; -moz-animation:rotation .7s infinite linear;-o-animation: rotation .7s infinite linear; animation:rotation .7s infinite linear; border-left:8px solid rgba(0, 0, 0, .2); border-right:8px solid rgba(0, 0, 0, .2); border-bottom:8px solid rgba(0, 0, 0, .2); border-top:8px solid #fff; border-radius:100%;"></div></div></div></div>' );
			jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left', '-500px' );
			return true;
		} );
		jQuery( '.ec_product_page_sidebar_slide_bg' ).appendTo( document.body );
		jQuery( '.ec_product_page_filters_toggle' ).on( 'click', function( ){
			if( jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left' ) == '0px' ){
				jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left', '-500px' ).fadeOut();
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			}else{
				jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left', '0px' ).fadeIn( 'fast' );
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeIn( 'fast' );
			}
		} );
		jQuery( '.ec_product_page_sidebar_slide_bg, .ec_product_sidebar_close_mobile_only' ).on( 'click', function( ){
			jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left', '-500px' );
			return false;
		} );

	} else if( jQuery( '.ec_product_page_with_sidebar.ec_product_page_sidebar_slide-left, .ec_product_page_sidebar_slide-right > .ec_product_page_sidebar' ).length ){
		jQuery( '.ec_product_page_sidebar_slide-left > .ec_product_page_sidebar, .ec_product_page_sidebar_slide-right > .ec_product_page_sidebar' ).appendTo( document.body ).show( );
		jQuery( '.ec_product_page_sidebar_slide_bg' ).appendTo( document.body );
		jQuery( '.ec_product_page_sidebar_slide-left .ec_product_page_filters_toggle' ).on( 'click', function( ){
			if( jQuery( '.ec_product_page_sidebar' ).css( 'left' ) == '0px' ){
				jQuery( '.ec_product_page_sidebar' ).css( 'left', '-500px' );
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			}else{
				jQuery( '.ec_product_page_sidebar' ).css( 'left', '0px' );
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeIn( 'fast' );
			}
		} );
		jQuery( '.ec_product_page_sidebar_slide-right .ec_product_page_filters_toggle' ).on( 'click', function( ){
			if( jQuery( '.ec_product_page_sidebar' ).css( 'right' ) == '0px' ){
				jQuery( '.ec_product_page_sidebar' ).css( 'right', '-500px' );
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			}else{
				jQuery( '.ec_product_page_sidebar' ).css( 'right', '0px' );
				jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeIn( 'fast' );
			}
		} );
		jQuery( '.ec_product_page_sidebar_slide_bg, .ec_product_sidebar_close_mobile_only' ).on( 'click', function() {
			jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			jQuery( '.ec_product_page_sidebar_slide-left' ).css( 'left', '-500px' );
			jQuery( '.ec_product_page_sidebar_slide-right' ).css( 'right', '-500px' );
			return false;
		} );
		jQuery( '.ec_product_page_sidebar.ec_product_page_sidebar_slide-left .ec_product_sidebar_filter_item, .ec_product_page_sidebar.ec_product_page_sidebar_slide-right .ec_product_sidebar_filter_item, .ec_product_page_sidebar.ec_product_page_sidebar_slide-left .ec_product_sidebar_link_item, .ec_product_page_sidebar.ec_product_page_sidebar_slide-right .ec_product_sidebar_link_item' ).on( 'click', function( ){
			jQuery( '.ec_product_page_content' ).html( '<div style="min-height:400px; width:100%; float:left;"><style>@keyframes rotation{0%{transform:rotate(0deg);}100%{ transform:rotate(359deg); } }</style><div class="wpec-product-slider-loader" style="font-size:14px; text-align:center; -webkit-box-sizing:border-box; -moz-box-sizing:border-box; -ms-box-sizing:border-box; box-sizing:border-box; width:350px; top:200px; left:50%; position:absolute; margin-left:-165px; margin-top:-80px; cursor:pointer; text-align:center; z-index:99;"><div><div style="height:30px; width:30px; display:inline-block; box-sizing:content-box; opacity:1; filter:alpha(opacity=100); -webkit-animation:rotation .7s infinite linear; -moz-animation:rotation .7s infinite linear;-o-animation: rotation .7s infinite linear; animation:rotation .7s infinite linear; border-left:8px solid rgba(0, 0, 0, .2); border-right:8px solid rgba(0, 0, 0, .2); border-bottom:8px solid rgba(0, 0, 0, .2); border-top:8px solid #fff; border-radius:100%;"></div></div></div></div>' );
			jQuery( '.ec_product_page_sidebar_slide_bg' ).fadeOut( 'fast' );
			jQuery( '.ec_product_page_sidebar_mobile_only' ).css( 'left', '-500px' );
		} );
	}
	if ( jQuery( '.ec_product_sidebar_group_title' ).length ) {
		jQuery( '.ec_product_sidebar_group_title' ).on( 'click', function() {
			jQuery( this ).parent( ).find( '.ec_product_sidebar_group_link_list, .ec_product_sidebar_group_filter_list' ).toggle( );
		} );
	}
	jQuery( '.ec_add_to_cart_form_ajax' ).submit( function( e ) {
		e.preventDefault();
		var input_eles = jQuery( this ).find( 'input, select' );
		var input_submit = jQuery( this ).find( 'input[type="submit"]' );
		jQuery( input_submit ).addClass( 'loading' ).parent().append( '<span class="dashicons dashicons-update-alt"></span>' );
		var data = {
			action: 'ec_ajax_add_to_cart_complete',
			noredirect: 1,
			nonce: jQuery( '.ec_add_to_cart_form_ajax' ).attr( 'data-nonce' )
		};
		for ( var i=0; i<input_eles.length; i++ ) {
			if( ! jQuery( input_eles[i] ).hasClass( '.ec_minus' ) && ! jQuery( input_eles[i] ).hasClass( '.ec_plus' ) ) {
				data[ input_eles[i].name ] = jQuery( input_eles[i] ).val();
			}
		}
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( result ){ 
			var json_data = JSON.parse( result );
			jQuery( ".ec_cart_items_total" ).html( json_data[0].total_items );
			jQuery( ".ec_cart_price_total" ).html( json_data[0].total_price );
			
			if( json_data[0].total_items == 1 ){
				jQuery( ".ec_menu_cart_singular_text" ).show( );
				jQuery( ".ec_menu_cart_plural_text" ).hide( );
			}else{
				jQuery( ".ec_menu_cart_singular_text" ).hide( );
				jQuery( ".ec_menu_cart_plural_text" ).show( );
			}
			
			if( json_data[0].total_items == 0 ){
				jQuery( ".ec_cart_price_total" ).hide( );
			}else{
				jQuery( ".ec_cart_price_total" ).show( );
			}
			
			if( jQuery( '.ec_cart_widget_minicart_product_padding' ).length ){
				
				jQuery( '.ec_cart_widget_minicart_product_padding' ).append( '<div class="ec_cart_widget_minicart_product_title" id="ec_cart_widget_row_' + json_data[0].cartitem_id + '">' + json_data[0].title + ' x 1 @ ' + json_data[0].price + '</div>' );
				
			}
			
			jQuery( input_submit ).parent().find( '.dashicons-update-alt' ).remove();
			jQuery( input_submit ).parent().append( '<span class="dashicons dashicons-saved"></span>' );
			jQuery( input_submit ).removeClass( 'loading' ).addClass( 'added' );
			setTimeout(function() {
				jQuery( input_submit ).removeClass( 'added' ).parent().find( '.dashicons-saved' ).remove();
			}, 2000 );
		} } );
	} );
	
	if( jQuery( '.ec_details_thumbnail' ).length ) {
		jQuery( '.ec_details_thumbnail' ).on( 'click', function() {
			var product_id = jQuery( this ).attr( 'data-product-id' );
			var rand_id = jQuery( this ).attr( 'data-rand-id' );
			var src = '';
			if ( jQuery( this ).find( 'img' ).attr( 'data-large-src' ) ) {
				src = jQuery( this ).find( 'img' ).attr( 'data-large-src' );
			} else if ( jQuery( this ).find( 'img' ).attr( 'data-src' ) ) {
				src = jQuery( this ).find( 'img' ).attr( 'data-src' );
			} else {
				src = jQuery( this ).find( 'img' ).attr( 'src' );
			}
			jQuery( '.ec_details_thumbnails_' + product_id + '_' + rand_id + ' > .ec_details_thumbnail' ).removeClass( 'ec_active' );
			jQuery( this ).addClass( 'ec_active' );
			if( jQuery( this ).hasClass( 'ec_details_thumbnail_video' ) ) {
				var video_src = jQuery( this ).find( 'a' ).attr( 'href' );
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).hide();
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				if( jQuery( this ).hasClass( 'videoType' ) ) {
					jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><video controls><source src="' + video_src + '" /></video></div>' );
				} else {
					jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><iframe src="' + video_src + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>' );
				}
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src );
				jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).addClass( 'inactive' );
				jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'background', 'url( "' + src + '" ) no-repeat' );
			} else {
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).show();
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src );
				jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).removeClass( 'inactive' );
				jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'background', 'url( "' + src + '" ) no-repeat' );
			}
			return false;
		} );
	}
	if( jQuery( '.ec_details_large_popup_thumbnail' ).length ) {
		jQuery( '.ec_details_large_popup_thumbnail' ).on( 'click', function() {
			var product_id = jQuery( this ).attr( 'data-product-id' );
			var rand_id = jQuery( this ).attr( 'data-rand-id' );
			var src = ( jQuery( this ).find( 'img' ).attr( 'data-large-src' ) ) ? jQuery( this ).find( 'img' ).attr( 'data-large-src' ) : jQuery( this ).find( 'img' ).attr( 'src' );
			jQuery( '.ec_details_large_popup_thumbnails_' + product_id + '_' + rand_id + ' > .ec_details_large_popup_thumbnail' ).removeClass( 'ec_active' );
			jQuery( this ).addClass( 'ec_active' );
			if ( jQuery( this ).hasClass( 'ec_details_thumbnail_video' ) ) {
				var video_src = jQuery( this ).find( 'a' ).attr( 'href' );
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).hide();
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).hide();
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				
				if( jQuery( this ).hasClass( 'videoType' ) ) {
					jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><video controls><source src="' + video_src + '" /></video></div>' );
					jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><video controls><source src="' + video_src + '" /></video></div>' );
				
				} else {
					jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><iframe src="' + video_src + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>' );
					jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).append( '<div class="wp-easycart-video-box"><iframe src="' + video_src + '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>' );
				}
				jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).addClass( 'inactive' );
				jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'background', 'url( "' + src + '" ) no-repeat' );
			} else {
				jQuery( '.ec_details_thumbnails_' + product_id + '_' + rand_id + ' > .ec_details_thumbnail' ).removeClass( 'ec_active' );
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).show();
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( 'img' ).attr( 'data-src', src ).attr( 'src', src ).show();
				jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				jQuery( '.ec_details_large_popup_main_' + product_id + '_' + rand_id ).find( '.wp-easycart-video-box' ).remove();
				jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'background', 'url( "' + src + '" ) no-repeat' );
			}
			return false;
		});
	}
	if ( jQuery( '.ec_details_main_image.mag_enabled' ).length ) {
		var img = new Image( );
		var img_width = 400;
		var img_height = 400;
		jQuery( '.ec_details_main_image' ).mousemove( function( e ){
			var parentOffset = jQuery( this ).parent( ).offset( ); 
			var mouse_x = e.pageX - parentOffset.left;
			var mouse_y = e.pageY - parentOffset.top;
			var div_width = jQuery( this ).width( );
			var div_height = jQuery( this ).height( );
			var x_val = '-' + ( ( img_width - div_width ) * ( mouse_x / div_width ) ) + 'px';
			var y_val = '-' + ( ( img_height - div_height ) * ( mouse_y / div_height ) ) + 'px';
			var product_id = jQuery( this ).attr( 'data-product-id' );
			var rand_id = jQuery( this ).attr( 'data-rand-id' );
			jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'background-position', x_val + ' ' + y_val );	
		} );
		jQuery( '.ec_details_main_image' ).hover(
			function() {
				var product_id = jQuery( this ).attr( 'data-product-id' );
				var rand_id = jQuery( this ).attr( 'data-rand-id' );
				img = new Image( );
				img.onload = function( ) {
					img_width = this.width;
					img_height = this.height;
					jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).css( 'width', jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).width( ) + 'px' ).css( 'height', jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).height( ) + 'px' );
					jQuery( '.ec_details_magbox_image_' + product_id + '_' + rand_id ).css( 'width', jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).width( ) + 'px' ).css( 'height', jQuery( '.ec_details_main_image_' + product_id + '_' + rand_id ).height( ) + 'px' );
				}
				img.src = jQuery( this ).find( 'img' ).attr( 'src' );
				jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).fadeIn( 'fast' ); 
			}, 
			function() {
				var product_id = jQuery( this ).attr( 'data-product-id' );
				var rand_id = jQuery( this ).attr( 'data-rand-id' );
				jQuery( '.ec_details_magbox_' + product_id + '_' + rand_id ).fadeOut( 'fast' ); 
			} 
		);
	}
	if ( jQuery( '.ec_minus' ).length ) {
		jQuery( '.ec_minus' ).on( 'click', function() {
			var product_id = jQuery( this ).parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().attr( 'data-rand-id' );
			var min_quantity = jQuery( this ).parent().attr( 'data-min-purchase-quantity' );
			var use_advanced_optionset = jQuery( this ).parent().attr( 'data-use-advanced-optionset' );
			ec_minus_quantity( product_id + '_' + rand_id, min_quantity );
			if ( '1' == use_advanced_optionset ) {
				ec_details_advanced_adjust_price( product_id, rand_id );
			} else {
				ec_details_base_adjust_price( product_id, rand_id );
			}
		} );
	}
	if ( jQuery( '.ec_plus' ).length ) {
		jQuery( '.ec_plus' ).on( 'click', function() {
			var product_id = jQuery( this ).parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().attr( 'data-rand-id' );
			var show_stock_quantity = jQuery( this ).parent().attr( 'data-show-stock-quantity' );
			var max_quantity = jQuery( this ).parent().attr( 'data-min-purchase-quantity' );
			var use_advanced_optionset = jQuery( this ).parent().attr( 'data-use-advanced-optionset' );
			ec_plus_quantity( product_id + '_' + rand_id, show_stock_quantity, max_quantity );
			if ( '1' == use_advanced_optionset ) {
				ec_details_advanced_adjust_price( product_id, rand_id );
			} else {
				ec_details_base_adjust_price( product_id, rand_id );
			}
		} );
	}
	if ( jQuery( '.ec_quantity' ).length ) {
		jQuery( '.ec_quantity' ).on( 'change', function() {
			var product_id = jQuery( this ).parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().attr( 'data-rand-id' );
			var use_advanced_optionset = jQuery( this ).parent().attr( 'data-use-advanced-optionset' );
			if ( '1' == use_advanced_optionset ) {
				ec_details_advanced_adjust_price( product_id, rand_id );
			} else {
				ec_details_base_adjust_price( product_id, rand_id );
			}
		} );
	}
	if ( jQuery( '.ec_donation_amount' ).length ) {
		jQuery( '.ec_donation_amount' ).on( 'change', function() {
			var product_id = jQuery( this ).attr( 'data-product-id' );
			var rand_id = jQuery( this ).attr( 'data-rand-id' );
			var use_advanced_optionset = jQuery( this ).attr( 'data-use-advanced-optionset' );
			if ( '1' == use_advanced_optionset ) {
				ec_details_advanced_adjust_price( product_id, rand_id );
			} else {
				ec_details_base_adjust_price( product_id, rand_id );
			}
		} );
	}
	jQuery( '.ec_details_checkbox_row > input, .ec_details_radio_row > input' ).click( function( ){
		var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_combo > .ec_details_option_data > select' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var product_option_id = jQuery( this ).parent().parent().attr( 'data-product-option-id' );
		var is_required = jQuery( this ).parent().parent().attr( 'data-option-required' );
		var val = jQuery( this ).val();
		if ( val == '0' && is_required == '1' ) {
			jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + product_option_id + '_' + product_id + '_' + rand_id ) ).show( );
		} else {
			jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + product_option_id + '_' + product_id + '_' + rand_id ) ).hide( );
		}
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_date > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_file > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_text > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_number > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_textarea > .ec_details_option_data > textarea' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_grid_row > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_swatches > li.ec_advanced' ).click( function( ){
		var optionitem_id = jQuery( this ).attr( 'data-optionitem-id' );
		var product_option_id = jQuery( this ).attr( 'data-product-option-id' );
		var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
		jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + "_" + product_id + '_' + rand_id ) ).val( optionitem_id );
		jQuery( '.ec_details_swatches > li.ec_option_adv_' + product_option_id + "_" + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
		jQuery( this ).addClass( 'ec_selected' );
		jQuery( document.getElementById( 'ec_details_option_row_error_' + product_option_id + "_" + product_id + '_' + rand_id ) ).hide( );
		if ( jQuery( this ).find( 'img' ).length ) {
			jQuery( this ).parent( ).parent( ).parent( ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
		} else {
			jQuery( this ).parent( ).parent( ).parent( ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
		}
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_dimensions1 > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_dimensions2 > .ec_details_option_data > input' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_option_row.ec_option_type_dimensions2 > .ec_details_option_data > select' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		ec_details_advanced_conditional_logic( product_id, rand_id );
		ec_details_advanced_adjust_price( product_id, rand_id );
	} );
	if ( jQuery( '.ec_details_swatches > li.ec_option1' ).length ) {
		jQuery( '.ec_details_swatches > li.ec_option1' ).each( function() {
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_1 = jQuery( this ).attr( 'data-optionitem-id' );
			ec_option1_init_swatches( product_id, rand_id, jQuery( this ), optionitem_id_1 );
		} );
	}
	if ( jQuery( '.ec_details_combo.ec_option1' ).length ) {
		jQuery( '.ec_details_combo.ec_option1' ).each( function() {
			var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
			ec_option1_init_combo( product_id, rand_id );
		} );
	}
	jQuery( '.ec_details_swatches > li.ec_option1' ).click( function( ){
		if( jQuery( this ).hasClass( 'ec_active' ) ){
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_1 = jQuery( this ).attr( 'data-optionitem-id' );
			var quantity = jQuery( this ).attr( 'data-optionitem-quantity' );
			var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
			if ( '1' == use_optionitem_quantity_tracking ) {
				ec_option1_selected( product_id, rand_id, optionitem_id_1, quantity );
			} else {
				ec_option1_updated( product_id, rand_id, optionitem_id_1 );
			}
			ec_option1_image_change( product_id, rand_id, optionitem_id_1, quantity );
			jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
			jQuery( this ).addClass( 'ec_selected' );
			jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
			jQuery( document.getElementById( 'ec_option1_' + product_id + '_' + rand_id ) ).val( optionitem_id_1 );
			if ( jQuery( this ).find( 'img' ).length ) {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
			} else {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
			}
			ec_details_base_adjust_price( product_id, rand_id );
		}
	} );
	jQuery( '.ec_details_swatches > li.ec_option2' ).click( function( ){
		if( jQuery( this ).hasClass( 'ec_active' ) ){
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = jQuery( this ).attr( 'data-optionitem-id' );
			var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
			if ( '1' == use_optionitem_quantity_tracking ) {
				var quantity = jQuery( this ).attr( 'data-optionitem-quantity' );
				option2_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, quantity );
			} else {
				ec_option2_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2 );
			}
			jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
			jQuery( this ).addClass( 'ec_selected' );
			jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
			jQuery( document.getElementById( 'ec_option2_' + product_id + '_' + rand_id ) ).val( optionitem_id_2 );
			if ( jQuery( this ).find( 'img' ).length ) {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
			} else {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
			}
			ec_details_base_adjust_price( product_id, rand_id );
		}
	} );
	jQuery( '.ec_details_swatches > li.ec_option3' ).click( function( ){
		if( jQuery( this ).hasClass( 'ec_active' ) ){
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_2 = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_2 = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_3 = jQuery( this ).attr( 'data-optionitem-id' );
			var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
			if ( '1' == use_optionitem_quantity_tracking ) {
				var quantity = jQuery( this ).attr( 'data-optionitem-quantity' );
				option3_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, quantity );
			} else {
				ec_option3_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3 );
			}
			jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
			jQuery( this ).addClass( 'ec_selected' );
			jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
			jQuery( document.getElementById( 'ec_option3_' + product_id + '_' + rand_id ) ).val( optionitem_id_3 );
			if ( jQuery( this ).find( 'img' ).length ) {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
			} else {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
			}
			ec_details_base_adjust_price( product_id, rand_id );
		}
	} );
	jQuery( '.ec_details_swatches > li.ec_option4' ).click( function( ){
		if( jQuery( this ).hasClass( 'ec_active' ) ){
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_2 = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_2 = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_3 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_3 = jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_3 = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_4 = jQuery( this ).attr( 'data-optionitem-id' );
			var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
			if ( '1' == use_optionitem_quantity_tracking ) {
				var quantity = jQuery( this ).attr( 'data-optionitem-quantity' );
				option4_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, quantity );
			} else {
				ec_option4_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4 );
			}
			jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
			jQuery( this ).addClass( 'ec_selected' );
			jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
			jQuery( document.getElementById( 'ec_option4_' + product_id + '_' + rand_id ) ).val( optionitem_id_4 );
			if ( jQuery( this ).find( 'img' ).length ) {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
			} else {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
			}
			ec_details_base_adjust_price( product_id, rand_id );
		}
	} );
	jQuery( '.ec_details_swatches > li.ec_option5' ).click( function( ){
		if( jQuery( this ).hasClass( 'ec_active' ) ){
			var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
			var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
			var optionitem_id_5 = jQuery( this ).attr( 'data-optionitem-id' );
			jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' );
			var quantity = jQuery( this ).attr( 'data-optionitem-quantity' );
			jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
			if ( 'inf' != quantity ) {
				jQuery( '.ec_details_stock_total' ).show();
			} else {
				jQuery( '.ec_details_stock_total' ).hide();
			}
			jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).attr( 'max', quantity );
			if( Number( jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( ) ) > Number( quantity ) ){
				jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( quantity );
			}
			jQuery( this ).addClass( 'ec_selected' );
			jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
			jQuery( document.getElementById( 'ec_option5_' + product_id + '_' + rand_id ) ).val( optionitem_id_5 );
			if ( jQuery( this ).find( 'img' ).length ) {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).find( 'img' ).attr( 'title' ) );
			} else {
				jQuery( this ).parent( ).parent( ).find( '.ec_details_option_label' ).find( '.ec_details_option_label_selected' ).html( jQuery( this ).attr( 'title' ) );
			}
			ec_details_base_adjust_price( product_id, rand_id );
		}
	} );
	jQuery( '.ec_details_radio_row.ec_optionitem_images > input' ).click( function( ){
		var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
		var optionitem_id_1 = jQuery( this ).val( );
		ec_option1_image_change( product_id, rand_id, optionitem_id_1, 1 );
	} );
	jQuery( '.ec_option_type_combo .ec_optionitem_images' ).click( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var optionitem_id_1 = jQuery( this ).val( );
		ec_option1_image_change( product_id, rand_id, optionitem_id_1, 1 );
	} );
	jQuery( '.ec_option_type_combo .ec_optionitem_images' ).on( 'change', function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var optionitem_id_1 = jQuery( this ).val( );
		ec_option1_image_change( product_id, rand_id, optionitem_id_1, 1 );
	} );
	jQuery( '.ec_option_type_swatch li.ec_optionitem_images' ).click( function( ){
		var product_id = jQuery( this ).parent().parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().parent().attr( 'data-rand-id' );
		var optionitem_id_1 = jQuery( this ).attr( 'data-optionitem-id' );
		ec_option1_image_change( product_id, rand_id, optionitem_id_1, 1 );
	} );
	jQuery( '.ec_details_combo.ec_option1' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
		var quantity = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-quantity' );
		var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
		if ( '1' == use_optionitem_quantity_tracking ) {
			if( optionitem_id_1 != '0' ){
				ec_option1_selected( product_id, rand_id, optionitem_id_1, quantity );
			}else{
				jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
				jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
				jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
				jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
				jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
				if ( 'inf' != quantity ) {
					jQuery( '.ec_details_stock_total' ).show();
				} else {
					jQuery( '.ec_details_stock_total' ).hide();
				}
			}
		} else {
			ec_option1_updated( product_id, rand_id, optionitem_id_1 );
		}
		ec_option1_image_change( product_id, rand_id, optionitem_id_1, quantity );
		jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
		ec_details_base_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_combo.ec_option2' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
		if ( '1' == use_optionitem_quantity_tracking ) {
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( );
			var quantity = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-quantity' );
			if( optionitem_id_2 != '0' ){
				option2_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, quantity );
			}else{
				jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
				if ( 'inf' != quantity ) {
					jQuery( '.ec_details_stock_total' ).show();
				} else {
					jQuery( '.ec_details_stock_total' ).hide();
				}
			}
		} else {
			ec_option2_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2 );
		}
		jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
		ec_details_base_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_combo.ec_option3' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
		if ( '1' == use_optionitem_quantity_tracking ) {
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_2 = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_2 = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_3 = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( );
			var quantity = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-quantity' );
			if( optionitem_id_3 != '0' ){
				option3_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, quantity );
			}else{
				jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
				if ( 'inf' != quantity ) {
					jQuery( '.ec_details_stock_total' ).show();
				} else {
					jQuery( '.ec_details_stock_total' ).hide();
				}
			}
		} else {
			ec_option3_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3 );
		}
		jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
		ec_details_base_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_combo.ec_option4' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
		if ( '1' == use_optionitem_quantity_tracking ) {
			var optionitem_id_1 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_1 = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_1 = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_2 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_2 = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_2 = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_3 = 0;
			if( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
				optionitem_id_3 = jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' );
			}else{
				optionitem_id_3 = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( );
			}
			var optionitem_id_4 = jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val( );
			var quantity = jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-quantity' );
			if( optionitem_id_4 != '0' ){
				option4_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, quantity );
			}else{
				jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
				if ( 'inf' != quantity ) {
					jQuery( '.ec_details_stock_total' ).show();
				} else {
					jQuery( '.ec_details_stock_total' ).hide();
				}
			}
		} else {
			ec_option4_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4 );
		}
		jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
		ec_details_base_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_combo.ec_option5' ).change( function( ){
		var product_id = jQuery( this ).parent().parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().parent().attr( 'data-rand-id' );
		var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
		if ( '1' == use_optionitem_quantity_tracking ) {
			var quantity = jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-quantity' );
			jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
			if ( 'inf' != quantity ) {
				jQuery( '.ec_details_stock_total' ).show();
			} else {
				jQuery( '.ec_details_stock_total' ).hide();
			}
		}
		jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
		ec_details_base_adjust_price( product_id, rand_id );
	} );
	jQuery( '.ec_details_tab' ).click( function( ){
		var product_id = jQuery( this ).parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().attr( 'data-rand-id' );
		jQuery( '.ec_details_tab_' + product_id + '_' + rand_id ).removeClass( 'ec_active' );
		jQuery( this ).addClass( 'ec_active' );
		jQuery( '.ec_details_extra_area_' + product_id + '_' + rand_id ).children( 'div' ).each( function( ){
			jQuery( this ).hide()
		} );
		if( jQuery( this ).hasClass( 'ec_description' ) ) {
			jQuery( '.ec_details_description_tab_' + product_id + '_' + rand_id ).show( );
		} else if( jQuery( this ).hasClass( 'ec_specifications' ) ) {
			jQuery( '.ec_details_specifications_tab_' + product_id + '_' + rand_id ).show( );
		} else if( jQuery( this ).hasClass( 'ec_customer_reviews' ) ) {
			jQuery( '.ec_details_customer_reviews_tab_' + product_id + '_' + rand_id ).show( );
		} else {
			jQuery( '.ec_details_' + jQuery( this ).attr( 'data-tab-id' ) + '_tab' ).show( );
		}
		jQuery( this ).parent().toggleClass( 'ec_is_open' );
	} );
	jQuery( '.ec_details_review_input' ).hover( function() {
		var product_id = jQuery( this ).parent().attr( 'data-product-id' );
		var rand_id = jQuery( this ).parent().attr( 'data-rand-id' );
		var score = jQuery( this ).attr( 'data-review-score' );
		jQuery( '.ec_details_review_input_' + product_id + '_' + rand_id ).removeClass( 'ec_product_details_star_on' ).addClass( 'ec_product_details_star_off' );
		for( var i=0; i<score; i++ ){
			jQuery( document.getElementById( 'ec_details_review_star' + (i+1) + '_' + product_id + '_' + rand_id ) ).removeClass( 'ec_product_details_star_off' ).addClass( 'ec_product_details_star_on' );
		}
	} );
	if( jQuery( '.ec_product_details_page' ).length ) {
		jQuery( '.ec_product_details_page' ).each( function() {
			var product_id = jQuery( this ).attr( 'data-product-id' );
			var rand_id = jQuery( this ).attr( 'data-rand-id' );
			ec_details_base_adjust_price( product_id, rand_id );
			ec_details_advanced_adjust_price( product_id, rand_id );
			ec_details_advanced_conditional_logic( product_id, rand_id );
		} );
		jQuery( '.ec_product_openclose' ).hide( );
	}
	if ( jQuery( '.ec_details_inquiry_popup' ).length ) {
		jQuery( '.ec_details_inquiry_popup' ).appendTo( document.body );
	}
	if ( jQuery( '.ec_details_large_popup' ).length ) {
		jQuery( '.ec_details_large_popup' ).appendTo( document.body );
	}
	if ( jQuery( document.getElementById('ec_cart_login_loader' ) ).length ) {
		jQuery( document.getElementById( 'ec_cart_login_loader' ) ).appendTo( document.body );
	}
	if ( jQuery( document.getElementById('ec_cart_create_account_loader' ) ).length ) {
		jQuery( document.getElementById( 'ec_cart_create_account_loader' ) ).appendTo( document.body );
	}
	if ( jQuery( document.getElementById('ec_cart_address_loader' ) ).length ) {
		jQuery( document.getElementById( 'ec_cart_address_loader' ) ).appendTo( document.body );
	}
	if ( jQuery( document.getElementById('ec_cart_subscription_shipping_methods_loader' ) ).length ) {
		jQuery( document.getElementById( 'ec_cart_subscription_shipping_methods_loader' ) ).appendTo( document.body );
	}
	var shippingAddressTimer;
	var billingAddressTimer;
	var contactEmailTimer;
	jQuery( document ).on( 'change keyup', 'input.ec_cart_auto_validate_v2', function() {
		if ( jQuery( this ).hasClass( 'ec_cart_shipping_input_text' ) ) {
			if ( ec_validate_address_block( 'ec_cart_shipping' ) ) {
				if ( jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).length ) {
					clearTimeout( shippingAddressTimer );
					shippingAddressTimer = setTimeout( function() {
						wp_easycart_goto_shipping_v2();
						if ( jQuery( document.getElementById( 'ec_shipping_order_error' ) ).length ) {
							jQuery( document.getElementById( 'ec_shipping_order_error' ) ).hide();
						}
					}, 800 );
				}
			}
		} else if ( jQuery( this ).hasClass( 'ec_cart_contact_information_input_text' ) ) {
			if ( ec_validate_email_block( 'ec_contact' ) ) {
				clearTimeout( contactEmailTimer );
				contactEmailTimer = setTimeout( function() {
					jQuery( document.getElementById( 'ec_email_order2_error' ) ).hide();
					wp_easycart_update_contact_email_v2();
				}, 800 );
			}
			if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length && ec_validate_create_account( 'ec_contact' ) ) {
				jQuery( document.getElementById( 'ec_create_account_order_error' ) ).hide();
			}
		} else {
			if ( ec_validate_address_block( 'ec_cart_billing' ) ) {
				clearTimeout( billingAddressTimer );
				billingAddressTimer = setTimeout( function() {
					var shipping_selector = ( ! jQuery( '#billing_address_type_different' ).length || ( jQuery( '#billing_address_type_different' ).length && jQuery( '#billing_address_type_different' ).is( ':checked' ) ) ) ? '1' : '0';
					ec_update_billing_address_display( shipping_selector, jQuery( document.getElementById( 'wp_easycart_update_billing_nonce' ) ).val() );
					jQuery( document.getElementById( 'ec_billing_order_error' ) ).hide();
				}, 800 );
			}
		}
	} );
	jQuery( document ).on( 'change', 'select.ec_cart_auto_validate_v2', function() {
		if ( jQuery( this ).hasClass( 'ec_cart_shipping_input_text' ) ) {
			if ( ec_validate_address_block( 'ec_cart_shipping' ) ) {
				if ( jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).length ) {
					clearTimeout( shippingAddressTimer );
					shippingAddressTimer = setTimeout( function() {
						wp_easycart_goto_shipping_v2();
						if ( jQuery( document.getElementById( 'ec_shipping_order_error' ) ).length ) {
							jQuery( document.getElementById( 'ec_shipping_order_error' ) ).hide();
						}
					}, 800 );
				}
			}
		} else {
			if ( ec_validate_address_block( 'ec_cart_billing' ) ) {
				clearTimeout( billingAddressTimer );
				billingAddressTimer = setTimeout( function() {
					var shipping_selector = ( ! jQuery( '#billing_address_type_different' ).length || ( jQuery( '#billing_address_type_different' ).length && jQuery( '#billing_address_type_different' ).is( ':checked' ) ) ) ? '1' : '0';
					ec_update_billing_address_display( shipping_selector, jQuery( document.getElementById( 'wp_easycart_update_billing_nonce' ) ).val() );
					jQuery( document.getElementById( 'ec_billing_order_error' ) ).hide();
				}, 800 );
			}
		}
	} );
} );
var wpeasycart_login_recaptcha;
var wpeasycart_register_recaptcha;
var wpeasycart_product_stock_recaptcha;
var wpeasycart_inquiry_recaptcha;
var wpeasycart_recaptcha_onload = function ( ){
	if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
		var wpeasycart_login_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_login_recaptcha_callback
		});
	}
	if( jQuery( document.getElementById( 'ec_account_login_widget_recaptcha' ) ).length ){
		var wpeasycart_login_widget_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_login_widget_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_login_widget_recaptcha_callback
		});
	}
	if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
		var wpeasycart_register_recaptcha = grecaptcha.render( document.getElementById( 'ec_account_register_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_register_recaptcha_callback
		});
	}
	if( jQuery( document.getElementById( 'ec_product_details_recaptcha' ) ).length ){
		var wpeasycart_register_recaptcha = grecaptcha.render( document.getElementById( 'ec_product_details_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_product_details_recaptcha_callback
		});
	}
	if( jQuery( document.getElementById( 'ec_product_details_inquiry_recaptcha' ) ).length ){
		var wpeasycart_inquiry_recaptcha = grecaptcha.render( document.getElementById( 'ec_product_details_inquiry_recaptcha' ), {
			'sitekey' : jQuery( document.getElementById( 'ec_grecaptcha_site_key' ) ).val( ),
			'callback' : wpeasycart_inquiry_recaptcha_callback
		});
	}
}
function ec_customer_review_paging( page, product_id ){
    var review_ele = jQuery( '.ec_details_customer_review_list[data-product-id="' + product_id + '"]' );
	var reviews = review_ele.find( 'li' );
	var reviews_per_page = jQuery( document.getElementById( 'ec_details_reviews_per_page_' + product_id ) ).val( );
	
	if( reviews.length > reviews_per_page ){
		var i=0;
		reviews.each( function( ){
			if( i < (reviews_per_page * page ) && i >= (reviews_per_page * (page-1) ) ){
				jQuery( this ).show( );
			}else{
				jQuery( this ).hide( );
			}
			i++;
		} );
		i=0;
		review_ele.find( '#ec_details_customer_review_paging_' + product_id + ' > button' ).removeClass( 'selected' ).each( function( ){
			if( ( i+1 ) == page ){
				jQuery( this ).addClass( 'selected' );
			}
			i++;
		} );
	}
}
function wpeasycart_login_recaptcha_callback( response ){
	jQuery( document.getElementById( 'ec_grecaptcha_response_login' ) ).val( response );
	if( response.length ){
		jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', 'none' );
	}else{
		jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', '1px solid red' );
	}
}
function wpeasycart_login_widget_recaptcha_callback( response ){
	jQuery( document.getElementById( 'ec_grecaptcha_response_login_widget' ) ).val( response );
	if( response.length ){
		jQuery( '#ec_account_login_widget_recaptcha > div' ).css( 'border', 'none' );
	}else{
		jQuery( '#ec_account_login_widget_recaptcha > div' ).css( 'border', '1px solid red' );
	}
}
function wpeasycart_register_recaptcha_callback( response ){
	jQuery( document.getElementById( 'ec_grecaptcha_response_register' ) ).val( response );
	if( response.length ){
		jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', 'none' );
	}else{
		jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', '1px solid red' );
	}
}
function wpeasycart_product_details_recaptcha_callback( response ){
	jQuery( document.getElementById( 'ec_grecaptcha_response_product_details' ) ).val( response );
	if( response.length ){
		jQuery( '#ec_product_details_recaptcha > div' ).css( 'border', 'none' );
	}else{
		jQuery( '#ec_product_details_recaptcha > div' ).css( 'border', '1px solid red' );
	}
}
function wpeasycart_inquiry_recaptcha_callback( response ){
	jQuery( document.getElementById( 'ec_grecaptcha_response_inquiry' ) ).val( response );
	if( response.length ){
		jQuery( '#ec_product_details_inquiry_recaptcha > div' ).css( 'border', 'none' );
	}else{
		jQuery( '#ec_product_details_inquiry_recaptcha > div' ).css( 'border', '1px solid red' );
	}
}
function wpeasycart_cart_billing_country_update( ){
	if( document.getElementById( 'ec_cart_billing_country' ) ){
		var selected_country = jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val( );
		if( ec_is_state_required( selected_country ) )
			jQuery( document.getElementById( 'ec_billing_state_required' ) ).show( );
		else
			jQuery( document.getElementById( 'ec_billing_state_required' ) ).hide( );
		
		if( document.getElementById( 'ec_cart_billing_state_' + selected_country ) ){
			jQuery( '.ec_billing_state_dropdown, #ec_cart_billing_state' ).hide( );
			jQuery( document.getElementById( 'ec_cart_billing_state_' + selected_country ) ).show( );
		}else{
			jQuery( '.ec_billing_state_dropdown' ).hide( );
			jQuery( document.getElementById( 'ec_cart_billing_state' ) ).show( );
		}
	}
}
function wpeasycart_cart_shipping_country_update( ){
	if( document.getElementById( 'ec_cart_shipping_country' ) ){
		var selected_country = jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val( );
		if( ec_is_state_required( selected_country ) )
			jQuery( document.getElementById( 'ec_shipping_state_required' ) ).show( );
		else
			jQuery( document.getElementById( 'ec_shipping_state_required' ) ).hide( );
		
		if( document.getElementById( 'ec_cart_shipping_state_' + selected_country ) ){
			jQuery( '.ec_shipping_state_dropdown, #ec_cart_shipping_state' ).hide( );
			jQuery( document.getElementById( 'ec_cart_shipping_state_' + selected_country ) ).show( );
		}else{
			jQuery( '.ec_shipping_state_dropdown' ).hide( );
			jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).show( );
		}
	}
}
function wpeasycart_account_billing_country_update( ){
	if( document.getElementById( 'ec_account_billing_information_country' ) ){
		var selected_country = jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).val( );
		if( ec_is_state_required( selected_country ) )
			jQuery( document.getElementById( 'ec_billing_state_required' ) ).show( );
		else
			jQuery( document.getElementById( 'ec_billing_state_required' ) ).hide( );
		
		if( document.getElementById( 'ec_account_billing_information_state_' + selected_country ) ){
			jQuery( '.ec_billing_state_dropdown, #ec_account_billing_information_state' ).hide( );
			jQuery( document.getElementById( 'ec_account_billing_information_state_' + selected_country ) ).show( );
		}else{
			jQuery( '.ec_billing_state_dropdown' ).hide( );
			jQuery( document.getElementById( 'ec_account_billing_information_state' ) ).show( );
		}
	}
}
function wpeasycart_account_shipping_country_update( ){
	if( document.getElementById( 'ec_account_shipping_information_country' ) ){
		var selected_country = jQuery( document.getElementById( 'ec_account_shipping_information_country' ) ).val( );
		if( ec_is_state_required( selected_country ) )
			jQuery( document.getElementById( 'ec_shipping_state_required' ) ).show( );
		else
			jQuery( document.getElementById( 'ec_shipping_state_required' ) ).hide( );
		
		if( document.getElementById( 'ec_account_shipping_information_state_' + selected_country ) ){
			jQuery( '.ec_shipping_state_dropdown, #ec_account_shipping_information_state' ).hide( );
			jQuery( document.getElementById( 'ec_account_shipping_information_state_' + selected_country ) ).show( );
		}else{
			jQuery( '.ec_shipping_state_dropdown' ).hide( );
			jQuery( document.getElementById( 'ec_account_shipping_information_state' ) ).show( );
		}
	}
}
function wpeasycart_isTouchDevice() {
      return 'ontouchstart' in window || !!(navigator.msMaxTouchPoints);
}
function ec_product_show_quick_view_link( modelnum ){
	jQuery( document.getElementById( 'ec_product_quickview_container_' + modelnum ) ).fadeIn(100);	
}
function ec_product_hide_quick_view_link( modelnum ){
	jQuery( document.getElementById( 'ec_product_quickview_container_' + modelnum ) ).fadeOut(100);	
}
function change_product_sort( menu_id, menu_name, submenu_id, submenu_name, subsubmenu_id, subsubmenu_name, manufacturer_id, pricepoint_id, currentpage_selected, perpage, URL, divider, filter_option = '' ){
	var url_string = URL + divider + "filternum=" + document.getElementById('sortfield').value;
	if( subsubmenu_id != 0 ){
		url_string = url_string + "&subsubmenuid=" + subsubmenu_id;
		if( subsubmenu_name != 0 )
			url_string = url_string + "&subsubmenu=" + subsubmenu_name;
	}else if( submenu_id != 0 ){
		url_string = url_string + "&submenuid=" + submenu_id;
		if( submenu_name != 0 )
			url_string = url_string + "&submenu=" + submenu_name;	
	}else if( menu_id != 0 ){
		url_string = url_string + "&menuid=" + menu_id;
		if( menu_name != 0 )
			url_string = url_string + "&menu=" + menu_name;
	}
	if( manufacturer_id > 0 )
		url_string = url_string + "&manufacturer=" + manufacturer_id;
	if( pricepoint_id > 0 )
		url_string = url_string + "&pricepoint=" + pricepoint_id;
	if( currentpage_selected )
		url_string = url_string + "&pagenum=" + currentpage_selected;
	if( perpage )
		url_string = url_string + "&perpage=" + perpage; 
    if( filter_option )
        url_string = url_string + "&filter_option=" + filter_option;
	window.location = url_string;
}
function ec_add_to_cart_redirect( product_id, model_number, quantity, use_quantity_tracking, min_quantity, max_quantity, nonce, quantity_ele = false ) {
	return true;
}
function ec_add_to_cart( product_id, model_number, quantity, use_quantity_tracking, min_quantity, max_quantity, nonce, quantity_ele = false ) {
	if( !use_quantity_tracking || ( !isNaN( quantity ) && quantity > 0 && quantity >= min_quantity && quantity <= max_quantity ) ){
		if ( quantity_ele && jQuery( '#' + quantity_ele ).length ) {
			if ( Number( jQuery( '#' + quantity_ele ).val() ) >= min_quantity && Number( jQuery( '#' + quantity_ele ).val() ) <= max_quantity ) {
				quantity = Number( jQuery( '#' + quantity_ele ).val() );
			}
		}

		ec_product_hide_quick_view_link( model_number );
		jQuery( document.getElementById( 'ec_addtocart_quantity_exceeded_error_' + model_number ) ).hide( );
		jQuery( document.getElementById( 'ec_addtocart_quantity_minimum_error_' + model_number ) ).hide( );
		
		jQuery( document.getElementById( "ec_product_loader_" + model_number ) ).show( );
		var data = {
			action: 'ec_ajax_add_to_cart',
			product_id: product_id,
			model_number: model_number,
			quantity: quantity,
			nonce: nonce
		};
		
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( "ec_product_loader_" + model_number ) ).hide( );
			jQuery( document.getElementById( "ec_product_added_" + model_number ) ).show( ).delay( 2500 ).fadeOut( 'slow' );
			
			if ( jQuery( document.getElementById( "ec_added_to_cart_" + product_id ) ).length ) {
				jQuery( document.getElementById( "ec_add_to_cart_" + product_id ) ).css( 'display', 'none' );
				jQuery( document.getElementById( "ec_added_to_cart_" + product_id ) ).css( 'display', 'inline-block' );
			}

			if ( jQuery( document.getElementById( "ec_added_to_cart_type6_" + product_id  ) ).length ) {
				jQuery( document.getElementById( "ec_add_to_cart_" + product_id ) ).css( 'display', 'none' );
				jQuery( document.getElementById( "ec_added_to_cart_type6_" + product_id ) ).css( 'display', 'inline-block' );
			}

			jQuery( '.ec_product_added_to_cart' ).fadeIn( 'slow' );
			jQuery( ".ec_cart_items_total" ).html( json_data[0].total_items );
			jQuery( ".ec_cart_price_total" ).html( json_data[0].total_price );
			
			if( json_data[0].total_items == 1 ){
				jQuery( ".ec_menu_cart_singular_text" ).show( );
				jQuery( ".ec_menu_cart_plural_text" ).hide( );
			}else{
				jQuery( ".ec_menu_cart_singular_text" ).hide( );
				jQuery( ".ec_menu_cart_plural_text" ).show( );
			}
			
			if( json_data[0].total_items == 0 ){
				jQuery( ".ec_cart_price_total" ).hide( );
			}else{
				jQuery( ".ec_cart_price_total" ).show( );
			}
			
			if( jQuery( '.ec_cart_widget_minicart_product_padding' ).length ){
				jQuery( '.ec_cart_widget_minicart_product_padding' ).html('');
				for( var i=0; i<json_data.length; i++ ) {
					jQuery( '.ec_cart_widget_minicart_product_padding' ).append( '<div class="ec_cart_widget_minicart_product_title" id="ec_cart_widget_row_' + json_data[i].cartitem_id + '"><a href="' + json_data[i].link + '">' + json_data[i].title + '</a> x <span>' + json_data[i].quantity + '</span> @ <span>' + json_data[i].price + '</span></div>' );
				}
			}
			
		} } );
		
	}else{
		if( !isNaN( quantity ) && ( quantity < min_quantity || quantity < 1 ) ){
			jQuery( document.getElementById( 'ec_addtocart_quantity_minimum_error_' + model_number ) ).show( );
			jQuery( document.getElementById( 'ec_addtocart_quantity_exceeded_error_' + model_number ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_addtocart_quantity_exceeded_error_' + model_number ) ).show( );
			jQuery( document.getElementById( 'ec_addtocart_quantity_minimum_error_' + model_number ) ).hide( );
		}
	}
	
}

function ec_minus_quantity( product_id, min_quantity ){	
	var currval = jQuery( document.getElementById( 'ec_quantity_' + product_id ) ).val( );
	currval = Number( currval ) - 1;
	if( currval <= 0 ){
		currval = 1;
	}
	if( currval < min_quantity ){
		currval = min_quantity;
	}
	jQuery( document.getElementById( 'ec_quantity_' + product_id ) ).val( currval );
}

function ec_plus_quantity( product_id, track_quantity, max_quantity ){
	if( jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id ) ).length && jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id ) ).val( ) != 10000000 ){
		max_quantity = ( 'inf' == jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id ) ).html( ) ) ? 10000000 : Number( jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id ) ).html( ) );
	}
	if( max_quantity > 0 && max_quantity != 10000000 ){
		jQuery( document.getElementById( 'ec_quantity_' + product_id ) ).attr( 'max', max_quantity );
	}
	var currval = Number( jQuery( document.getElementById( 'ec_quantity_' + product_id ) ).val( ) );
	if( currval < Number( max_quantity ) ){
		currval = currval + 1;
	}else if( max_quantity != 10000000 ){
		currval = Number( max_quantity );
	}else{
		currval = currval + 1;
	}
	jQuery( document.getElementById( 'ec_quantity_' + product_id ) ).val( currval );
}

function ec_cartitem_delete( cartitem_id, model_number, nonce ){
	var data = {
		action: 'ec_ajax_cartitem_delete',
		ec_v3_24: 'true',
		cartitem_id: cartitem_id,
		nonce: nonce
	}
	
	jQuery( document.getElementById( 'ec_cartitem_delete_' + cartitem_id ) ).hide( );
	jQuery( document.getElementById( 'ec_cartitem_deleting_' + cartitem_id ) ).show( );
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_cartitem_row_' + cartitem_id ) ).remove( );
		jQuery( '.ec_cartitem_' + cartitem_id ).remove();
		jQuery( document.getElementById( 'ec_cartitem_min_error_' + cartitem_id ) ).remove( );
		jQuery( document.getElementById( 'ec_cartitem_max_error_' + cartitem_id ) ).remove( );
		jQuery( document.getElementById( 'ec_cart_widget_row_' + cartitem_id ) ).remove( );
		
		// Get Response Data
		var response_obj = JSON.parse( data );
		
		// Update Cart
		ec_update_cart( response_obj );
		
	} } );
}

function ec_cartitem_update( cartitem_id, model_number, nonce ){
	var data = {
		action: 'ec_ajax_cartitem_update',
		ec_v3_24: 'true',
		cartitem_id: cartitem_id,
		quantity: jQuery( document.getElementById( 'ec_quantity_' + model_number ) ).val( ),
		nonce: nonce
	};
	
	jQuery( document.getElementById( 'ec_cartitem_updating_' + cartitem_id ) ).show( );
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
		
		jQuery( document.getElementById( 'ec_cartitem_updating_' + cartitem_id ) ).hide( );
		
		// Get Response Data
		var response_obj = JSON.parse( data );
		
		// Update Cart
		ec_update_cart( response_obj );
		
	} } );
}

function ec_apply_coupon( nonce, is_mobile = false ){
	jQuery( document.getElementById( 'ec_apply_coupon' ) ).hide( );
	if ( jQuery( '#ec_applying_coupon_v2' ).length ) {
		jQuery( '#ec_applying_coupon_v2' ).addClass( 'ec_cart_button_working_active' );
		jQuery( '#ec_applying_coupon_v2_mobile' ).addClass( 'ec_cart_button_working_active' );
		jQuery( document.getElementById( 'ec_apply_coupon_mobile' ) ).hide( );
	} else {
		jQuery( document.getElementById( 'ec_applying_coupon' ) ).show( );
	}
	
	var coupon_code = ( is_mobile ) ? jQuery( document.getElementById( 'ec_coupon_code_mobile' ) ).val() : jQuery( document.getElementById( 'ec_coupon_code' ) ).val( );
	if ( is_mobile ) {
		jQuery( document.getElementById( 'ec_coupon_code' ) ).val( coupon_code );
	} else {
		if ( jQuery( document.getElementById( 'ec_coupon_code_mobile' ) ).length ) {
			jQuery( document.getElementById( 'ec_coupon_code_mobile' ) ).val( coupon_code )
		}
	}
	
	var data = {
		action: 'ec_ajax_redeem_coupon_code',
		ec_v3_24: 'true',
		couponcode: coupon_code,
		nonce: nonce
	};
	
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ 
			jQuery( document.getElementById( 'ec_apply_coupon' ) ).show( );
			if ( jQuery( '#ec_applying_coupon_v2' ).length ) {
				jQuery( '#ec_applying_coupon_v2' ).removeClass( 'ec_cart_button_working_active' );
				jQuery( '#ec_applying_coupon_v2_mobile' ).removeClass( 'ec_cart_button_working_active' );
				jQuery( document.getElementById( 'ec_apply_coupon_mobile' ) ).show( );
			} else {
				jQuery( document.getElementById( 'ec_applying_coupon' ) ).hide( );
			}
			
			// Get Response Data
			var response_obj = JSON.parse( data );
			
			// Update Cart
			ec_update_cart( response_obj );
			
			// Update Coupon Info
			if( response_obj.is_coupon_valid ){
				jQuery( document.getElementById( 'ec_coupon_error' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_success' ) ).html( response_obj.coupon_message ).show( );
				if ( jQuery( '#ec_applying_coupon_v2' ).length ) {
					jQuery( document.getElementById( 'ec_coupon_error_mobile' ) ).hide( );
					jQuery( document.getElementById( 'ec_coupon_success_mobile' ) ).html( response_obj.coupon_message ).show( );
				}
			}else{
				jQuery( document.getElementById( 'ec_coupon_success' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_error' ) ).html( response_obj.coupon_message ).show( );
				if ( jQuery( '#ec_applying_coupon_v2' ).length ) {
					jQuery( document.getElementById( 'ec_coupon_success_mobile' ) ).hide( );
					jQuery( document.getElementById( 'ec_coupon_error_mobile' ) ).html( response_obj.coupon_message ).show( );
				}
			}
		} 
	} );
}

function subscription_create_account( nonce ) {
	var email_complete = ec_validate_email_block( 'ec_contact' );
	var create_account_complete = ec_validate_create_account( 'ec_contact' );

	if ( email_complete && create_account_complete ) {
		jQuery( document.getElementById( 'ec_cart_create_account_loader' ) ).show();

		var email = jQuery( document.getElementById( 'ec_contact_email' ) ).val();
		var first_name = ( jQuery( document.getElementById( 'ec_contact_first_name' ) ).length ) ? jQuery( document.getElementById( 'ec_contact_first_name' ) ).val() : '';
		var last_name = ( jQuery( document.getElementById( 'ec_contact_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_contact_last_name' ) ).val() : '';
		var password = jQuery( document.getElementById( 'ec_contact_password' ) ).val();

		var data = {
			action: 'ec_ajax_subscription_create_account',
			nonce: nonce,
			ec_contact_email: email,
			ec_contact_first_name: first_name,
			ec_contact_last_name: last_name,
			ec_contact_password: password
		};

		jQuery.ajax( {
			url: wpeasycart_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( response ){
				var data_arr = JSON.parse( response );
				if ( data_arr['error'] ) {
					jQuery( document.getElementById( 'ec_subscription_email_exists' ) ).show();
				} else {
					jQuery( document.getElementById( 'ec_subscription_email_exists' ) ).hide();
					jQuery( document.getElementById( 'ec_user_contact_form' ) ).hide();
					jQuery( document.getElementById( 'ec_user_logged_out_header' ) ).hide();
					jQuery( document.getElementById( 'ec_cart_user_logged_in_name' ) ).html( data_arr['name'] );
					jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val( data_arr['first_name'] );
					jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val( data_arr['last_name'] );
					jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).val( data_arr['first_name'] );
					jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).val( data_arr['last_name'] );
					jQuery( document.getElementById( 'ec_user_logged_in_form' ) ).show();
					jQuery( document.getElementById( 'ec_cart_billing_form' ) ).show();
					jQuery( document.getElementById( 'ec_cart_billing_locked' ) ).hide();
					if ( jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).hide();
					}
					jQuery( document.getElementById( 'ec_cart_subscription_end_form' ) ).hide();
				}
				jQuery( document.getElementById( 'ec_cart_create_account_loader' ) ).hide();
			} 
		} );
	}
}

function update_subscription_totals( product_id, nonce ){
	var billing_complete = ec_validate_address_block( 'ec_cart_billing' );
	var shipping_complete = true;

	if( jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) ) {
		shipping_complete = ec_validate_address_block( 'ec_cart_shipping' );
	}

	if ( billing_complete && shipping_complete ) {
		jQuery( document.getElementById( 'ec_cart_address_loader' ) ).show();

		var shipping_selector = 0;
		if( jQuery( document.getElementById( 'ec_shipping_selector' ) ).length && jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) ){
			shipping_selector = 1;
		}

		var vat_registration_number = '';
		if( jQuery( document.getElementById( 'ec_cart_billing_vat_registration_number' ) ).length ){
			vat_registration_number = jQuery( document.getElementById( 'ec_cart_billing_vat_registration_number' ) ).val( );
		}

		var billing_first_name = ( jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val() : '';
		var billing_last_name = ( jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val() : '';
		var billing_company_name = ( jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).val() : '';
		var billing_address_line_1 = ( jQuery( document.getElementById( 'ec_cart_billing_address' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_address' ) ).val() : '';
		var billing_address_line_2 = ( jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).val() : '';
		var billing_city = ( jQuery( document.getElementById( 'ec_cart_billing_city' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_city' ) ).val() : '';
		var billing_state = jQuery( document.getElementById( 'ec_cart_billing_state' ) ).val();
		var billing_zip = ( jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).val() : '';
		var billing_country = jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val();
		if( '0' != billing_country && jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).length ){
			billing_state = jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).val();
		}
		var billing_phone = ( jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).val() : '';

		var shipping_first_name = billing_first_name;
		var shipping_last_name = billing_last_name;
		var shipping_company_name = billing_company_name;
		var shipping_address_line_1 = billing_address_line_1;
		var shipping_address_line_2 = billing_address_line_2;
		var shipping_city = billing_city;
		var shipping_state = billing_state;
		var shipping_zip = billing_zip;
		var shipping_country = billing_country;
		var shipping_phone = billing_phone;

		if ( shipping_selector ) {
			shipping_state = jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).val();
			shipping_country = jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val();
			if( '0' != shipping_country && jQuery( document.getElementById( 'ec_cart_shipping_state_' + shipping_country ) ).length ){
				shipping_state = jQuery( document.getElementById( 'ec_cart_shipping_state_' + shipping_country ) ).val();
			}
			shipping_first_name = ( jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).val() : '';
			shipping_last_name = ( jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).val() : '';
			shipping_company_name = ( jQuery( document.getElementById( 'ec_cart_shipping_company_name' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_company_name' ) ).val() : '';
			shipping_address_line_1 = ( jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).val() : '';
			shipping_address_line_2 = ( jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).val() : '';
			shipping_city = ( jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).val() : '';
			shipping_zip = ( jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).val() : '';
			shipping_phone = ( jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).length ) ? jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).val() : '';

		}

		var data = {
			action: 'ec_ajax_update_subscription_tax',
			product_id: product_id,
			shipping_selector: shipping_selector,
			nonce: nonce,

			vat_registration_number: vat_registration_number,

			billing_first_name: billing_first_name,
			billing_last_name: billing_last_name,
			billing_company_name: billing_company_name,
			billing_country: billing_country,
			billing_address: billing_address_line_1,
			billing_address2: billing_address_line_2,
			billing_city: billing_city,
			billing_state: billing_state,
			billing_zip: billing_zip,
			billing_phone: billing_phone,

			shipping_first_name: shipping_first_name,
			shipping_last_name: shipping_last_name,
			shipping_company_name: shipping_company_name,
			shipping_country: shipping_country,
			shipping_address: shipping_address_line_1,
			shipping_address2: shipping_address_line_2,
			shipping_city: shipping_city,
			shipping_state: shipping_state,
			shipping_zip: shipping_zip,
			shipping_phone: shipping_phone
		};

		jQuery.ajax( {
			url: wpeasycart_ajax_object.ajax_url, 
			type: 'post', 
			data: data, 
			success: function( response ){
				var data_arr = JSON.parse( response );
				wpeasycart_subscription_cart_update_totals( data_arr );
				jQuery( document.getElementById( 'ec_cart_billing_form' ) ).hide();
				jQuery( document.getElementById( 'ec_cart_billing_address_display' ) ).html( data_arr['billing_address_display'] );
				jQuery( document.getElementById( 'ec_cart_billing_locked' ) ).show();
				if ( jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).length ) {
					jQuery( document.getElementById( 'ec_cart_shipping_address_display' ) ).html( data_arr['shipping_address_display'] );
					jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).show();
				}
				jQuery( document.getElementById( 'ec_cart_subscription_end_form' ) ).show();
				jQuery( document.getElementById( 'ec_cart_address_loader' ) ).hide();
			} 
		} );
	}
}

function wpeasycart_subscription_cart_update_totals( data_arr ) {
	jQuery( document.getElementById( 'ec_cart_discount' ) ).html( data_arr['discount_total'] );
	jQuery( document.getElementById( 'ec_cart_discount_mobile' ) ).html( data_arr['discount_total'] );
	if( jQuery( document.getElementById( 'ec_cart_tax' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_tax' ) ).html( data_arr['tax_total'] );
		jQuery( document.getElementById( 'ec_cart_tax_mobile' ) ).html( data_arr['tax_total'] );
	}
	if( jQuery( document.getElementById( 'ec_cart_vat' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_vat' ) ).html( data_arr['vat_total'] );
		jQuery( document.getElementById( 'ec_cart_vat_mobile' ) ).html( data_arr['vat_total'] );
	}
	if( jQuery( document.getElementById( 'ec_cart_vat_rate' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_vat_rate' ) ).html( data_arr['vat_rate_formatted'] );
	}
	if( jQuery( document.getElementById( 'ec_cart_vat_rate_mobile' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_vat_rate_mobile' ) ).html( data_arr['vat_rate_formatted'] );
	}
	if( jQuery( document.getElementById( 'ec_cart_hst' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_hst' ) ).html( data_arr['hst_total'] );
		jQuery( document.getElementById( 'ec_cart_hst_mobile' ) ).html( data_arr['hst_total'] );
		jQuery( document.getElementById( 'ec_cart_hst_rate' ) ).html( data_arr['hst_rate'] );
		jQuery( document.getElementById( 'ec_cart_hst_rate_mobile' ) ).html( data_arr['hst_rate'] );
		if( data_arr['hst_rate'] > 0 ){
			jQuery( document.getElementById( 'ec_cart_hst_row' ) ).show( );
			jQuery( document.getElementById( 'ec_cart_hst_row_mobile' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_hst_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_hst_row_mobile' ) ).hide( );
		}
	}

	if( jQuery( document.getElementById( 'ec_cart_pst' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_pst' ) ).html( data_arr['pst_total'] );
		jQuery( document.getElementById( 'ec_cart_pst_mobile' ) ).html( data_arr['pst_total'] );
		jQuery( document.getElementById( 'ec_cart_pst_rate' ) ).html( data_arr['pst_rate'] );
		jQuery( document.getElementById( 'ec_cart_pst_rate_mobile' ) ).html( data_arr['pst_rate'] );
		if( data_arr['pst_rate'] > 0 ){
			jQuery( document.getElementById( 'ec_cart_pst_row' ) ).show( );
			jQuery( document.getElementById( 'ec_cart_pst_row_mobile' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_pst_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_pst_row_mobile' ) ).hide( );
		}
	}

	if( jQuery( document.getElementById( 'ec_cart_gst' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_gst' ) ).html( data_arr['gst_total'] );
		jQuery( document.getElementById( 'ec_cart_gst_mobile' ) ).html( data_arr['gst_total'] );
		jQuery( document.getElementById( 'ec_cart_gst_rate' ) ).html( data_arr['gst_rate'] );
		jQuery( document.getElementById( 'ec_cart_gst_rate_mobile' ) ).html( data_arr['gst_rate'] );
		if( data_arr['gst_rate'] > 0 ){
			jQuery( document.getElementById( 'ec_cart_gst_row' ) ).show( );
			jQuery( document.getElementById( 'ec_cart_gst_row_mobile' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_gst_row' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_gst_row_mobile' ) ).hide( );
		}
	}

	if( jQuery( document.getElementById( 'ec_cart_shipping' ) ).length ){
		jQuery( document.getElementById( 'ec_cart_shipping' ) ).html( data_arr['shipping_total'] );
		jQuery( document.getElementById( 'ec_cart_shipping_mobile' ) ).html( data_arr['shipping_total'] );
	}

	if ( jQuery( document.getElementById( 'ec_cart_subscription_shipping_methods' ) ).length ) {
		jQuery( document.getElementById( 'ec_cart_subscription_shipping_methods' ) ).html( data_arr['shipping_methods'] );
	}

	jQuery( document.getElementById( 'ec_cart_total' ) ).html( data_arr['grand_total'] );
	jQuery( document.getElementById( 'ec_cart_total_mobile' ) ).html( data_arr['grand_total'] );

	if( Number( data_arr['has_tax'] ) == 1 ){ 
		jQuery( '#ec_cart_tax_row' ).show( ); 
		jQuery( '#ec_cart_tax_row_mobile' ).show( ); 
	}else{ 
		jQuery( '#ec_cart_tax_row' ).hide( );
		jQuery( '#ec_cart_tax_row_mobile' ).hide( );
	}
	if( Number( data_arr['has_vat'] ) == 1 ){
		jQuery( '#ec_cart_vat_row' ).show( );
		jQuery( '#ec_cart_vat_row_mobile' ).show( );
	}else{
		jQuery( '#ec_cart_vat_row' ).hide( );
		jQuery( '#ec_cart_vat_row_mobile' ).hide( );
	}
}

function ec_apply_subscription_coupon( product_id, manufacturer_id, nonce, is_mobile = false ){
	
	jQuery( document.getElementById( 'ec_apply_coupon' ) ).hide( );
	jQuery( document.getElementById( 'ec_applying_coupon' ) ).show( );
	jQuery( document.getElementById( 'ec_apply_coupon_mobile' ) ).hide( );
	jQuery( document.getElementById( 'ec_applying_coupon_mobile' ) ).show( );
	
	var coupon_code = ( is_mobile ) ? jQuery( document.getElementById( 'ec_coupon_code_mobile' ) ).val() : jQuery( document.getElementById( 'ec_coupon_code' ) ).val();
	jQuery( document.getElementById( 'ec_coupon_code_mobile' ) ).val( coupon_code );
	jQuery( document.getElementById( 'ec_coupon_code' ) ).val(  coupon_code);
	
	var data = {
		action: 'ec_ajax_redeem_subscription_coupon_code',
		product_id: product_id,
		manufacturer_id: manufacturer_id,
		couponcode: coupon_code,
		nonce: nonce
	};
	
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ 
			jQuery( document.getElementById( 'ec_apply_coupon' ) ).show( );
			jQuery( document.getElementById( 'ec_applying_coupon' ) ).hide( );
			jQuery( document.getElementById( 'ec_apply_coupon_mobile' ) ).show( );
			jQuery( document.getElementById( 'ec_applying_coupon_mobile' ) ).hide( );

			var data_arr = JSON.parse( data );

			if( data_arr['coupon_status'] == "valid" ){
				jQuery( document.getElementById( 'ec_coupon_error' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_success' ) ).html( data_arr['coupon_message'] ).show( );
				jQuery( document.getElementById( 'ec_coupon_error_mobile' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_success_mobile' ) ).html( data_arr['coupon_message'] ).show( );
			}else{
				jQuery( document.getElementById( 'ec_coupon_success' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_error' ) ).html( data_arr['coupon_message'] ).show( );
				jQuery( document.getElementById( 'ec_coupon_success_mobile' ) ).hide( );
				jQuery( document.getElementById( 'ec_coupon_error_mobile' ) ).html( data_arr['coupon_message'] ).show( );
			}

			wpeasycart_subscription_cart_update_totals( data_arr );
		}
	} );
}

function ec_apply_gift_card( nonce, is_mobile = false ){
	jQuery( document.getElementById( 'ec_apply_gift_card' ) ).hide( );
	if ( jQuery( '#ec_applying_gift_card_v2' ).length ) {
		jQuery( '#ec_applying_gift_card_v2' ).addClass( 'ec_cart_button_working_active' );
		jQuery( '#ec_applying_gift_card_v2_mobile' ).addClass( 'ec_cart_button_working_active' );
		jQuery( document.getElementById( 'ec_apply_gift_card_mobile' ) ).hide();
	} else {
		jQuery( document.getElementById( 'ec_applying_gift_card' ) ).show( );
	}
	
	var gift_card = ( is_mobile ) ? jQuery( document.getElementById( 'ec_gift_card_mobile' ) ).val() : jQuery( document.getElementById( 'ec_gift_card' ) ).val();
	if ( is_mobile ) {
		jQuery( document.getElementById( 'ec_gift_card' ) ).val( gift_card );
	} else {
		if ( jQuery( document.getElementById( 'ec_gift_card_mobile' ) ).length ) {
			jQuery( document.getElementById( 'ec_gift_card_mobile' ) ).val( gift_card );
		}
	}
	
	var data = {
		action: 'ec_ajax_redeem_gift_card',
		ec_v3_24: 'true',
		giftcard: gift_card,
		nonce: nonce
	};
	
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ 
			jQuery( document.getElementById( 'ec_apply_gift_card' ) ).show( );
			if ( jQuery( '#ec_applying_gift_card_v2' ).length ) {
				jQuery( '#ec_applying_gift_card_v2' ).removeClass( 'ec_cart_button_working_active' );
				jQuery( '#ec_applying_gift_card_v2_mobile' ).removeClass( 'ec_cart_button_working_active' );
				jQuery( document.getElementById( 'ec_apply_gift_card_mobile' ) ).show( );
			} else {
				jQuery( document.getElementById( 'ec_applying_gift_card' ) ).hide( );
			}

			// Get Response Data
			var response_obj = JSON.parse( data );
			
			// Update Cart
			ec_update_cart( response_obj );
			
			// Update Gift Card Info
			if( response_obj.is_giftcard_valid ){
				jQuery( document.getElementById( 'ec_gift_card_error' ) ).hide( );
				jQuery( document.getElementById( 'ec_gift_card_success' ) ).html( response_obj.giftcard_message ).show( );
				if ( jQuery( '#ec_applying_gift_card_v2' ).length ) {
					jQuery( document.getElementById( 'ec_gift_card_error_mobile' ) ).hide( );
					jQuery( document.getElementById( 'ec_gift_card_success_mobile' ) ).html( response_obj.giftcard_message ).show( );
				}
			}else{
				jQuery( document.getElementById( 'ec_gift_card_success' ) ).hide( );
				jQuery( document.getElementById( 'ec_gift_card_error' ) ).html( response_obj.giftcard_message ).show( );
				if ( jQuery( '#ec_applying_gift_card_v2' ).length ) {
					jQuery( document.getElementById( 'ec_gift_card_success_mobile' ) ).hide( );
					jQuery( document.getElementById( 'ec_gift_card_error_mobile' ) ).html( response_obj.giftcard_message ).show( );
				}
			}
		} 
	} );
}

function ec_estimate_shipping( nonce ){
	
	jQuery( document.getElementById( 'ec_estimate_shipping' ) ).hide( );
	jQuery( document.getElementById( 'ec_estimating_shipping' ) ).show( );
	
	var data = {
		action: 'ec_ajax_estimate_shipping',
		ec_v3_24: 'true',
		zipcode: jQuery( document.getElementById( 'ec_estimate_zip' ) ).val( ),
		country: jQuery( document.getElementById( 'ec_estimate_country' ) ).val( ),
		nonce: nonce
	};
	
	jQuery.ajax({
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ 
			jQuery( document.getElementById( 'ec_estimate_shipping' ) ).show( );
			jQuery( document.getElementById( 'ec_estimating_shipping' ) ).hide( );
			
			// Get Response Data
			var response_obj = JSON.parse( data );
			
			// Update Cart
			ec_update_cart( response_obj );
			
			// Show the Shipping Row if Hidden
			jQuery( document.getElementById( 'ec_cart_shipping_row' ) ).show( );
		} 
	} );
}

function ec_update_cart( response_obj ){
	
	if( response_obj.cart.length == 0 ){
		ec_reload_cart( );
		
	}else{
		// Update Cart Data
		for( var i=0; i<response_obj.cart.length; i++ ){
			jQuery( document.getElementById( 'ec_quantity_' + response_obj.cart[i].id ) ).val( response_obj.cart[i].quantity );
			jQuery( document.getElementById( 'ec_cartitem_price_' + response_obj.cart[i].id ) ).html( response_obj.cart[i].unit_price );
			jQuery( document.getElementById( 'ec_cartitem_total_' + response_obj.cart[i].id ) ).html( response_obj.cart[i].total_price );

			jQuery( '.ec_cartitem_' + response_obj.cart[i].id + ' > .ec_cart_price_row_total_v2' ).html( response_obj.cart[i].total_price );

			if( response_obj.cart[i].allow_backorders == "1" && response_obj.cart[i].use_optionitem_quantity_tracking == "1" && Number( response_obj.cart[i].quantity ) > Number( response_obj.cart[i].optionitem_stock_quantity ) ){
				jQuery( document.getElementById( 'ec_cartitem_backorder_' + response_obj.cart[i].id ) ).show( );
			
			}else if( response_obj.cart[i].allow_backorders == "1" && response_obj.cart[i].use_optionitem_quantity_tracking == "1" && Number( response_obj.cart[i].quantity ) <= Number( response_obj.cart[i].optionitem_stock_quantity ) ){
				jQuery( document.getElementById( 'ec_cartitem_backorder_' + response_obj.cart[i].id ) ).hide( );
			
			}else if( response_obj.cart[i].allow_backorders == "1" && response_obj.cart[i].use_optionitem_quantity_tracking == "0" && Number( response_obj.cart[i].quantity ) > Number( response_obj.cart[i].stock_quantity ) ){
				jQuery( document.getElementById( 'ec_cartitem_backorder_' + response_obj.cart[i].id ) ).show( );
			
			}else if( response_obj.cart[i].allow_backorders == "1" && response_obj.cart[i].use_optionitem_quantity_tracking == "0" && Number( response_obj.cart[i].quantity ) <= Number( response_obj.cart[i].stock_quantity ) ){
				jQuery( document.getElementById( 'ec_cartitem_backorder_' + response_obj.cart[i].id ) ).hide( );
			
			}
			if ( jQuery( document.getElementById( 'ec_cartitem_shipping_restriction_' + response_obj.cart[i].id ) ).length && '1' == response_obj.cart[i].shipping_restricted ) {
				jQuery( document.getElementById( 'ec_cartitem_shipping_restriction_' + response_obj.cart[i].id ) ).show( );
			} else if ( jQuery( document.getElementById( 'ec_cartitem_shipping_restriction_' + response_obj.cart[i].id ) ).length ) {
				jQuery( document.getElementById( 'ec_cartitem_shipping_restriction_' + response_obj.cart[i].id ) ).hide( );
			}
		}
		
		// Update Cart Totals
		jQuery( document.getElementById( 'ec_cart_subtotal' ) ).html( response_obj.order_totals.sub_total );
		if ( jQuery( '.ec_cart_table_subtotal_amount' ).length ) {
			jQuery( '.ec_cart_table_subtotal_amount' ).html( response_obj.order_totals.sub_total );
		}
		jQuery( document.getElementById( 'ec_cart_tax' ) ).html( response_obj.order_totals.tax_total );
		if( response_obj.order_totals.has_tax == '1' ){
			jQuery( document.getElementById( 'ec_cart_tax' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_tax' ) ).parent( ).hide( );
		}
		jQuery( document.getElementById( 'ec_cart_shipping' ) ).html( response_obj.order_totals.shipping_total );
		jQuery( document.getElementById( 'ec_cart_duty' ) ).html( response_obj.order_totals.duty_total );
		if( response_obj.order_totals.has_duty == '1' ){
			jQuery( document.getElementById( 'ec_cart_duty' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_duty' ) ).parent( ).hide( );
		}
		jQuery( document.getElementById( 'ec_cart_vat' ) ).html( response_obj.order_totals.vat_total );
		if( response_obj.order_totals.has_vat == '1' ){
			jQuery( document.getElementById( 'ec_cart_vat' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_vat' ) ).parent( ).hide( );
		}
        if( jQuery( document.getElementById( 'ec_cart_vat_rate' ) ).length ){
            jQuery( document.getElementById( 'ec_cart_vat_rate' ) ).html( response_obj.order_totals.vat_rate_formatted );
        }
		jQuery( document.getElementById( 'ec_cart_gst' ) ).html( response_obj.order_totals.gst_total );
		if( response_obj.order_totals.has_gst == '1' ){
			jQuery( document.getElementById( 'ec_cart_gst' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_gst' ) ).parent( ).hide( );
		}
		jQuery( document.getElementById( 'ec_cart_hst' ) ).html( response_obj.order_totals.hst_total );
		if( response_obj.order_totals.has_hst == '1' ){
			jQuery( document.getElementById( 'ec_cart_hst' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_hst' ) ).parent( ).hide( );
		}
		jQuery( document.getElementById( 'ec_cart_pst' ) ).html( response_obj.order_totals.pst_total );
		if( response_obj.order_totals.has_pst == '1' ){
			jQuery( document.getElementById( 'ec_cart_pst' ) ).parent( ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_pst' ) ).parent( ).hide( );
		}
        if( jQuery( document.getElementById( 'ec_cart_tip' ) ).length ){
			jQuery( document.getElementById( 'ec_cart_tip' ) ).html( response_obj.order_totals.tip_total );
		}
		if ( response_obj.order_totals.fees ) {
			for ( var j=0; j < response_obj.order_totals.fees.length; j++ ) {
				if( jQuery( document.getElementById( 'ec_cart_fee_' + response_obj.order_totals.fees[j].fee_id ) ).length ){
					jQuery( document.getElementById( 'ec_cart_fee_' + response_obj.order_totals.fees[j].fee_id ) ).html( response_obj.order_totals.fees[j].fee_total );
				}
			}
		}
		jQuery( document.getElementById( 'ec_cart_discount' ) ).html( response_obj.order_totals.discount_total );
		jQuery( document.getElementById( 'ec_cart_total' ) ).html( response_obj.order_totals.grand_total );
		
		jQuery( ".ec_cart_items_total" ).html( response_obj.items_total );
		jQuery( ".ec_cart_price_total" ).html( response_obj.order_totals.grand_total );
		
		// Hide/Show Discount
		if( response_obj.has_discount == '1' ){
			jQuery( '.ec_no_discount' ).show( );
			jQuery( '.ec_has_discount' ).show( );
		}else{
			jQuery( '.ec_no_discount' ).hide( );
			jQuery( '.ec_has_discount' ).hide( );
		}
		
		// Hide/Show Backorder
		if( response_obj.has_backorder ){
			jQuery( document.getElementById( 'ec_cart_backorder_message' ) ).show( );
		}else{
			jQuery( document.getElementById( 'ec_cart_backorder_message' ) ).hide( );
		}
        
        // Hide/Show Minimum
        if( jQuery( '.ec_minimum_purchase_box' ).length ){
            if( Number( response_obj.order_totals.sub_total_amt ) < Number( jQuery( '.ec_minimum_purchase_box' ).attr( 'data-min-cart' ) ) ){
                jQuery( '.ec_minimum_purchase_box' ).show( );
                if( jQuery( document.getElementById( 'sq-walletbox' ) ).length ){
                    jQuery( document.getElementById( 'sq-walletbox' ) ).hide( );
                }
            }else{
                jQuery( '.ec_minimum_purchase_box' ).hide( );
                if( jQuery( document.getElementById( 'sq-walletbox' ) ).length ){
                    jQuery( document.getElementById( 'sq-walletbox' ) ).show( );
                }
            }
        }
        
        // Stripe Update Wallet
        if( jQuery( document.getElementById( 'ec-stripe-wallet-button' ) ).length ){
            jQuery( document.getElementById( 'ec-stripe-wallet-button' ) ).replaceWith( response_obj.stripe_wallet );
        }
	
		// PayPal Express Update
		if( response_obj.paypal_express_button ){
			jQuery( document.getElementById( 'paypal-button-container' ) ).find( '.paypal-button' ).remove( );
			jQuery( document.getElementById( 'paypal-button-container' ) ).append( response_obj.paypal_express_button );
		}
	}
}

function ec_reload_cart( ){
	location.reload( );
}

function ec_open_login_click( ){
	jQuery( document.getElementById( 'ec_alt_login' ) ).slideToggle(300);
	
	return false;
}

function ec_update_shipping_view( ){
	if( jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_shipping_form' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_shipping_form' ) ).hide( );
	}
}

function ec_cart_toggle_login( ){
	if( jQuery( document.getElementById( 'ec_login_selector' ) ).is( ':checked' ) ){
		jQuery( document.getElementById( 'ec_user_login_form' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_user_login_form' ) ).hide( );
	}
}

function ec_cart_toggle_login_v2() {
	if ( jQuery( document.getElementById( 'ec_user_login_form' ) ).is( ':visible' ) ) {
		jQuery( document.getElementById( 'ec_user_login_form' ) ).hide( );
		jQuery( document.getElementById( 'ec_user_contact_form' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_link' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_cancel_link' ) ).hide( );
		jQuery( document.getElementById( 'ec_login_selector' ) ).prop( 'checked', false );
		if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
			jQuery( document.getElementById( 'ec_user_create_form' ) ).show();
		}
	} else {
		jQuery( document.getElementById( 'ec_user_login_form' ) ).show( );
		jQuery( document.getElementById( 'ec_user_contact_form' ) ).hide( );
		jQuery( document.getElementById( 'ec_user_login_link' ) ).hide( );
		jQuery( document.getElementById( 'ec_user_login_cancel_link' ) ).show( );
		jQuery( document.getElementById( 'ec_login_selector' ) ).prop( 'checked', true );
		if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
			jQuery( document.getElementById( 'ec_user_create_form' ) ).hide();
		}
	}
	return false;
}

function ec_cart_validate_login_v2( nonce ) {
	if ( ec_validate_cart_login() ) {
		jQuery( document.getElementById( 'ec_user_login_form' ) ).append( '<div id="ec_cart_login_form_loader"><style>@keyframes rotation{0% { transform:rotate(0deg); }100%{ transform:rotate(359deg); }}</style><div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'><div><div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div></div></div></div>' );
		var data = {
			action: 'ec_ajax_cart_login_v2',
			ec_cart_login_email: jQuery( document.getElementById( 'ec_cart_login_email' ) ).val(),
			ec_cart_login_password: jQuery( document.getElementById( 'ec_cart_login_password' ) ).val(),
			wpeasycart_nonce: nonce
		};
		jQuery.ajax( {
			url: wpeasycart_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				var response_obj = JSON.parse( data );
				jQuery( document.getElementById( 'ec_cart_login_invalid_error' ) ).hide();
				jQuery( document.getElementById( 'ec_cart_not_activated_error' ) ).hide();
				if ( response_obj.error ) {
					jQuery( document.getElementById( 'ec_cart_login_form_loader' ) ).remove();
					if ( 'not_activated' == response_obj ) {
						jQuery( document.getElementById( 'ec_cart_not_activated_error' ) ).show();
					} else {
						jQuery( document.getElementById( 'ec_cart_login_invalid_error' ) ).show();
					}
				} else {
					window.location.href = response_obj.url;
				}
			}
		} );
		return false;
	} else {
		return false;
	}
}

function ec_cart_logout_v2() {
	jQuery( document.getElementById( 'ec_cart_logged_in_section' ) ).append( '<style>@keyframes rotation{0% { transform:rotate(0deg); }100%{ transform:rotate(359deg); }}</style><div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'><div><div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div></div></div>' );
}

function ec_cart_toggle_address_edit( ) {
	if ( jQuery( document.getElementById( 'ec_cart_billing_form' ) ).is( ':visible' ) ) {
		jQuery( document.getElementById( 'ec_cart_billing_form' ) ).hide();
		jQuery( document.getElementById( 'ec_cart_billing_locked' ) ).show();
		if ( jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).show();
		}
		jQuery( document.getElementById( 'ec_cart_subscription_end_form' ) ).show();
	} else {
		jQuery( document.getElementById( 'ec_cart_billing_form' ) ).show();
		jQuery( document.getElementById( 'ec_cart_billing_locked' ) ).hide();
		if ( jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_shipping_locked' ) ).hide();
		}
		jQuery( document.getElementById( 'ec_cart_subscription_end_form' ) ).hide();
	}
}

function wp_easycart_update_contact_email_v2() {
	var data = {
		action: 'ec_ajax_update_contact_email',
		ec_create_account: ( ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) ? 1 : 0 ),
		ec_contact_email: jQuery( document.getElementById( 'ec_contact_email' ) ).val(),
		wpeasycart_checkout_nonce: jQuery( document.getElementById( 'wpeasycart_checkout_nonce' ) ).val()
	};
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ) {
			var response_obj = JSON.parse( data );
			if ( response_obj.error && 'user_create_error' == response_obj.error && jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
				jQuery( document.getElementById( 'ec_create_account_email_error' ) ).show();
			} else {
				jQuery( document.getElementById( 'ec_create_account_email_error' ) ).hide();
			}
		},
	} );
}

function wp_easycart_goto_page_v2( page, nonce, add_state = true ) {
	if ( 'shipping' == page && jQuery( '#wpeasycart_shipping_page_link' ).hasClass( 'wpeasycart-deactivated-link' ) ) {
		return false;
	}
	if ( 'payment' == page && jQuery( '#wpeasycart_payment_page_link' ).hasClass( 'wpeasycart-deactivated-link' ) ) {
		return false;
	}

	var valid_pages = [ 'cart', 'information', 'shipping', 'payment' ];
	if ( ! valid_pages.includes( page ) ) {
		page = 'information';
	}
	jQuery( '.ec_cart_left' ).append( '<div id="ec_cart_goto_page_loader"><style>@keyframes rotation{0% { transform:rotate(0deg); }100%{ transform:rotate(359deg); }}</style><div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'><div><div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div></div></div></div>' );

	if ( add_state ) {
		wp_easycart_goto_push_state( page );
	}

	var data = {
		action: 'ec_ajax_goto_page_v2',
		page: page,
		wpeasycart_checkout_nonce: nonce,
	};
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ) {
			var response_obj = JSON.parse( data );
			ec_update_cart( response_obj.cart_data );
			if ( jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).length ) {
				jQuery( document.getElementById( 'ec_cart_goto_page_loader' ) ).remove();
				if ( 'cart' == page ) {
					jQuery( '.ec_cart_left' ).addClass( 'ec_cart_full' );
					jQuery( '.ec_cart_right' ).hide();
					if ( jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).show();
						jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).html( response_obj.html_content );
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).hide();
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).hide();
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).hide();
					}
				} else {
					jQuery( '.ec_cart_left' ).removeClass( 'ec_cart_full' );
					jQuery( '.ec_cart_right' ).show();
					if ( jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_cart' ) ).hide();
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).show();
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).show();
					}
					if ( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).length ) {
						jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).show();
					}
				}
			} else {
				jQuery( '.ec_cart_left' ).html( response_obj.html_content );
				if ( 'cart' == page ) {
					jQuery( '.ec_cart_left' ).addClass( 'ec_cart_full' );
					jQuery( '.ec_cart_right' ).hide();
				} else {
					jQuery( '.ec_cart_left' ).removeClass( 'ec_cart_full' );
					jQuery( '.ec_cart_right' ).show();
				}
				if ( '1' == response_obj.shipping_allowed ) {
					jQuery( '#wpeasycart_shipping_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
				}
				if ( '1' == response_obj.payment_allowed ) {
					jQuery( '#wpeasycart_payment_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
				}
			}
		}
	} );
	return false;
}

function wp_easycart_goto_push_state( page ) {
	var url = window.location.href;
	var var_key = 'eccheckout';
	var re = new RegExp( "([?&])" + var_key + "=.*?(&|$)", "i");
	var separator = url.indexOf( '?' ) !== -1 ? '&' : '?';
	if ( url.match( re ) ) {
		url = url.replace( re, '$1' + var_key + "=" + page + '$2');
	} else {
		url = url + separator + var_key + "=" + page;
	}
	window.history.pushState( { eccheckout: page }, document.title, url );
}

function wp_easycart_goto_shipping_v2( should_scroll = false ) {
	var has_errors = false;
	var scrolled_top = false;
	var has_shipping_error = false;
	if ( jQuery( document.getElementById( 'ec_contact_email' ) ).length && ! ec_validate_email_block( 'ec_contact' ) ) {
		jQuery( document.getElementById( 'ec_email_order2_error' ) ).show();
		has_errors = true;
		if ( should_scroll && ! scrolled_top ) {
			jQuery( [ document.documentElement, document.body ] ).animate( {
				scrollTop: jQuery( document.getElementById( 'ec_cart_contact_header' ) ).offset().top
			}, 750 );
			scrolled_top = true;
		}
	} else {
		jQuery( document.getElementById( 'ec_email_order2_error' ) ).hide();
	}

	if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
		jQuery( document.getElementById( 'ec_user_login_form' ) ).hide( );
		jQuery( document.getElementById( 'ec_user_contact_form' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_link' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_cancel_link' ) ).hide( );
		jQuery( document.getElementById( 'ec_login_selector' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'ec_user_create_form' ) ).show();
		if ( ! ec_validate_create_account( 'ec_contact' ) ) {
			jQuery( document.getElementById( 'ec_create_account_order_error' ) ).show();
			has_errors = true;
			if ( should_scroll && ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_create_account_header' ) ).offset().top
				}, 750 );
				scrolled_top = true;
			}
		} else {
			jQuery( document.getElementById( 'ec_create_account_order_error' ) ).hide();
		}
	}

	if ( jQuery( document.getElementById( 'ec_shipping_complete' ) ).length ) {
		if ( '0' == jQuery( document.getElementById( 'ec_shipping_complete' ) ).val() ) {
			jQuery( '#ec_shipping_order_error' ).show();
			has_errors = true;
			has_shipping_error = true;
		} else {
			jQuery( '#ec_shipping_order_error' ).hide();
		}
	} else if ( jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).length && ! ec_validate_address_block( 'ec_cart_shipping' ) ) {
		jQuery( '#ec_shipping_order_error' ).show();
		has_errors = true;
		has_shipping_error = true;
	} else if ( ! jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).length && jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).length && ! ec_validate_address_block( 'ec_cart_billing' ) ) {
		jQuery( '#ec_shipping_order_error' ).show();
		has_errors = true;
		has_shipping_error = true;
	} else if ( jQuery( document.getElementById( 'ec_shipping_address_line_1' ) ).length && '' == jQuery( document.getElementById( 'ec_shipping_address_line_1' ) ).val() || '' == jQuery( document.getElementById( 'ec_shipping_name' ) ).val() ) {
		jQuery( '#ec_shipping_order_error' ).show();
		has_errors = true;
		has_shipping_error = true;
	} else {
		jQuery( '#ec_shipping_order_error' ).hide();
	}
	if ( should_scroll && has_shipping_error && ! scrolled_top ) {
		jQuery( [ document.documentElement, document.body ] ).animate( {
			scrollTop: jQuery( document.getElementById( 'ec_cart_shipping_header' ) ).offset().top
		}, 750 );
		scrolled_top = true;
	}
	
	if ( has_errors ) {
		return false;
	}

	if( jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).length ) {
		var header_html = jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).find( '.ec_cart_header' );
		jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).html( '<div id="ec_cart_shipping_loader"><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div></div>' ).prepend( header_html );
		if ( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_locked_panel' ).length ) {
			var payment_header_html = jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_header' );
			jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).html( '<div id="ec_cart_shipping_loader"><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div></div>' ).prepend( payment_header_html );
		}

	} else if( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).length ) {
		var header_html = jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_header' );
		if ( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_locked_panel' ).length ) {
			jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).html( '<div id="ec_cart_shipping_loader"><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div><div class="ec_cart_shipping_animate_pulse"><div class="ec_cart_shipping_rounded_loader"></div><div class="ec_cart_shipping_animate_line_container"><div class="ec_cart_shipping_animate_line_top"></div><div class="ec_cart_shipping_animate_line_bottom"></div></div></div></div>' ).prepend( header_html );
		}
	} else {
		jQuery( '.ec_cart_left' ).append( '<div id="ec_cart_shipping_loader"><style>@keyframes rotation{0% { transform:rotate(0deg); }100%{ transform:rotate(359deg); }}</style><div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'><div><div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div></div></div></div>' );
	}

	if ( jQuery( document.getElementById( 'ec_shipping_complete' ) ).length || jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).length ) {
		var shipping_country = ( jQuery( document.getElementById( 'ec_shipping_country' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_country' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).val();
		var shipping_state = ( jQuery( document.getElementById( 'ec_shipping_state' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_state' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_state' ) ).val();
		if ( jQuery( document.getElementById( 'ec_cart_shipping_state_' + shipping_country ) ).length ) {
			shipping_state = jQuery( document.getElementById( 'ec_cart_shipping_state_' + shipping_country ) ).val();
		}

		var data = {
			action: 'ec_ajax_save_checkout_info',
			ec_cart_is_subscriber: ( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ) ? '1' : '0',
			ec_shipping_selector: jQuery( document.getElementById( 'ec_shipping_selector' ) ).val(),
			ec_shipping_address_line_1: ( jQuery( document.getElementById( 'ec_shipping_address_line_1' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_address_line_1' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_address' ) ).val(),
			ec_shipping_address_line_2: ( jQuery( document.getElementById( 'ec_shipping_address_line_2' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_address_line_2' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_address2' ) ).val(),
			ec_shipping_city: ( jQuery( document.getElementById( 'ec_shipping_city' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_city' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_city' ) ).val(),
			ec_shipping_state: shipping_state,
			ec_shipping_zip: ( jQuery( document.getElementById( 'ec_shipping_zip' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_zip' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_zip' ) ).val(),
			ec_shipping_country: shipping_country,
			ec_shipping_phone: ( jQuery( document.getElementById( 'ec_shipping_phone' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_phone' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_phone' ) ).val(),
			ec_shipping_name: ( jQuery( document.getElementById( 'ec_shipping_name' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_first_name' ) ).val(),
			ec_shipping_last_name: ( jQuery( document.getElementById( 'ec_shipping_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_last_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_last_name' ) ).val(),
			ec_shipping_company_name: ( jQuery( document.getElementById( 'ec_shipping_company_name' ) ).length ) ? jQuery( document.getElementById( 'ec_shipping_company_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_shipping_company_name' ) ).val(),
			ec_contact_email: jQuery( document.getElementById( 'ec_contact_email' ) ).val(),
			ec_order_notes: jQuery( document.getElementById( 'ec_order_notes' ) ).val(),
			wpeasycart_checkout_nonce: jQuery( document.getElementById( 'wpeasycart_checkout_nonce' ) ).val(),
		};
		wp_easycart_goto_push_state( 'shipping' );
	} else {
		var billing_country = ( jQuery( document.getElementById( 'ec_billing_country' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_country' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val();
		var billing_state = ( jQuery( document.getElementById( 'ec_billing_state' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_state' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_state' ) ).val();
		if ( jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).length ) {
			billing_state = jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).val();
		}

		var data = {
			action: 'ec_ajax_save_checkout_info',
			ec_cart_is_subscriber: ( jQuery( document.getElementById( 'ec_cart_is_subscriber' ) ).is( ':checked' ) ) ? '1' : '0',
			ec_billing_address_line_1: ( jQuery( document.getElementById( 'ec_billing_address_line_1' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_address_line_1' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_address' ) ).val(),
			ec_billing_address_line_2: ( jQuery( document.getElementById( 'ec_billing_address_line_2' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_address_line_2' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).val(),
			ec_billing_city: ( jQuery( document.getElementById( 'ec_billing_city' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_city' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_city' ) ).val(),
			ec_billing_state: billing_state,
			ec_billing_zip: ( jQuery( document.getElementById( 'ec_billing_zip' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_zip' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).val(),
			ec_billing_country: billing_country,
			ec_billing_phone: ( jQuery( document.getElementById( 'ec_billing_phone' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_phone' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).val(),
			ec_billing_name: ( jQuery( document.getElementById( 'ec_billing_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val(),
			ec_billing_last_name: ( jQuery( document.getElementById( 'ec_billing_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_last_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val(),
			ec_billing_company_name: ( jQuery( document.getElementById( 'ec_billing_company_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_company_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).val(),
			ec_contact_email: jQuery( document.getElementById( 'ec_contact_email' ) ).val(),
			ec_order_notes: jQuery( document.getElementById( 'ec_order_notes' ) ).val(),
			wpeasycart_checkout_nonce: jQuery( document.getElementById( 'wpeasycart_checkout_nonce' ) ).val(),
		};
		wp_easycart_goto_push_state( 'payment' );
	}

	if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
		data.ec_create_account = 1;
		if ( jQuery( document.getElementById( 'ec_contact_first_name' ) ).length ) {
			data.ec_contact_first_name = jQuery( document.getElementById( 'ec_contact_first_name' ) ).val();
		}
		if ( jQuery( document.getElementById( 'ec_contact_last_name' ) ).length ) {
			data.ec_contact_last_name = jQuery( document.getElementById( 'ec_contact_last_name' ) ).val();
		}
		data.ec_contact_password = jQuery( document.getElementById( 'ec_contact_password' ) ).val();
	}

	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ) {
			var response_obj = JSON.parse( data );
			jQuery( document.getElementById( 'ec_cart_shipping_loader' ) ).remove();
			if ( response_obj.error && 'user_create_error' == response_obj.error ) {
				jQuery( document.getElementById( 'ec_create_account_email_error' ) ).show();
			} else {
				jQuery( document.getElementById( 'ec_create_account_email_error' ) ).hide();
			}
			ec_update_cart( response_obj.cart_data );
			if( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).length ) {
				if ( jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).length ) {
					jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).html( response_obj.shipping_content );
					if ( jQuery( 'input[name=ec_cart_shipping_method]:checked' ).length ) {
						if ( jQuery( document.getElementById( 'ec_shipping_method_order_error' ) ).length ) {
							jQuery( document.getElementById( 'ec_shipping_method_order_error' ) ).hide();
						}
					}
				}
				if ( jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_locked_panel' ).length || jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).find( '.ec_cart_shipping_animate_pulse' ).length ) {
					jQuery( document.getElementById( 'ec_cart_onepage_payment' ) ).html( response_obj.payment_content );
				}
			} else {
				if ( ! response_obj.error ) {
					jQuery( '.ec_cart_left' ).html( response_obj.html_content );
					if ( '1' == response_obj.shipping_allowed ) {
						jQuery( '#wpeasycart_shipping_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
					}
					if ( '1' == response_obj.payment_allowed ) {
						jQuery( '#wpeasycart_payment_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
					}
				}
			}
		}
	} );
}

function wp_easycart_goto_payment_v2() {
	jQuery( '.ec_cart_left' ).append( '<style>@keyframes rotation{0% { transform:rotate(0deg); }100%{ transform:rotate(359deg); }}</style><div style=\'font-family: "HelveticaNeue", "HelveticaNeue-Light", "Helvetica Neue Light", helvetica, arial, sans-serif; font-size: 14px; text-align: center; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; -ms-box-sizing: border-box; box-sizing: border-box; width: 350px; top: 50%; left: 50%; position: absolute; margin-left: -165px; margin-top: -80px; cursor: pointer; text-align: center;\'><div><div style="height: 30px; width: 30px; display: inline-block; box-sizing: content-box; opacity: 1; filter: alpha(opacity=100); -webkit-animation: rotation .7s infinite linear; -moz-animation: rotation .7s infinite linear; -o-animation: rotation .7s infinite linear; animation: rotation .7s infinite linear; border-left: 8px solid rgba(0, 0, 0, .2); border-right: 8px solid rgba(0, 0, 0, .2); border-bottom: 8px solid rgba(0, 0, 0, .2); border-top: 8px solid #fff; border-radius: 100%;"></div></div></div>' );
	
	var ship_express = 0;
	var shipping_method = jQuery( '.ec_cart_shipping_method_row > input:checked' ).val();
	if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) { 
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).is( ':checked' ) ) {
			ship_express = 1;
		}
	}

	if ( 'shipexpress' == shipping_method ) {
		shipping_method = 'standard';
		if ( jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).length ) { 
			jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).prop( 'checked', false );
		}
		jQuery( document.getElementById( 'ec_cart_shipping_method' ) ).prop( 'checked', 'checked' );

	} else if( 'free' == shipping_method ) {
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_ship_express' ) ).prop( 'checked', false );
		}
	}

	wp_easycart_goto_push_state( 'payment' );
	var data = {
		action: 'ec_ajax_save_shipping_method',
		shipping_method: shipping_method,
		ship_express: ship_express,
		wpeasycart_checkout_nonce: jQuery( document.getElementById( 'wpeasycart_checkout_nonce' ) ).val(),
	};
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data,
		success: function( data ) {
			var response_obj = JSON.parse( data );
			jQuery( '.ec_cart_left' ).html( response_obj.html_content );
			if ( '1' == response_obj.shipping_allowed ) {
				jQuery( '#wpeasycart_shipping_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
			}
			if ( '1' == response_obj.payment_allowed ) {
				jQuery( '#wpeasycart_payment_page_link' ).removeClass( 'wpeasycart-deactivated-link' );
			}
		}
	} );
}

function ec_toggle_create_account( ){
	if( jQuery( document.getElementById( 'ec_user_create_form' ) ).is( ':visible' ) ){
		jQuery( document.getElementById( 'ec_user_create_form' ) ).hide( );
	}else{
		jQuery( document.getElementById( 'ec_user_create_form' ) ).show( );
	}
}

function ec_update_payment_display( nonce ) {
	jQuery( '.ec_cart_payment_table_row ' ).removeClass( 'ec_payment_row_selected' );
	
	var payment_method = "manual_bill";
	
	jQuery( document.getElementById( 'ec_apple_pay_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_manual_payment_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_amazonpay_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_affirm_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_third_party_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_credit_card_form' ) ).hide( );
	jQuery( document.getElementById( 'ec_ideal_form' ) ).hide( );
	
	if( jQuery( document.getElementById( 'ec_payment_apple' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_apple' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_apple_pay_form' ) ).show( );

		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
		
		
		payment_method = "apple_pay";
	
	}else if( jQuery( document.getElementById( 'ec_payment_manual' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_manual' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_manual_payment_form' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
		
		
		payment_method = "manual_bill";
	
	}else if( jQuery( document.getElementById( 'ec_payment_affirm' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_affirm' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_affirm_form' ) ).show( );

		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
			
		payment_method = "affirm";
	
	}else if( jQuery( document.getElementById( 'ec_payment_third_party' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_third_party' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_third_party_form' ) ).show( );

		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length ){
			
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).show( );
			
			if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
				jQuery( document.getElementById( 'ec_terms_row' ) ).hide( );
			
			if( jQuery( document.getElementById( 'ec_terms_error' ) ).length )
				jQuery( document.getElementById( 'ec_terms_error' ) ).hide( );
			
			if( jQuery( document.getElementById( 'ec_submit_order_error' ) ).length )
				jQuery( document.getElementById( 'ec_submit_order_error' ) ).hide( );
			
			if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
				jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).hide( );
			
			if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
				jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).hide( );
		}
		
		payment_method = "third_party";
	
	}else if( jQuery( document.getElementById( 'ec_payment_credit_card' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_credit_card' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_credit_card_form' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
			
		payment_method = "credit_card";
	
	}else if( jQuery( document.getElementById( 'ec_payment_ideal' ) ).is( ':checked' ) ){
		jQuery( '#ec_payment_ideal' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_ideal_form' ) ).show( );

		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
			
		payment_method = "ideal";

	}else if( jQuery( document.getElementById( 'ec_amazonpay' ) ).is( ':checked' ) ){
		jQuery( '#ec_amazonpay' ).parent().parent().addClass( 'ec_payment_row_selected' );

		jQuery( document.getElementById( 'ec_amazonpay_form' ) ).show( );

		if( jQuery( document.getElementById( 'ec_terms_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).length )
			jQuery( document.getElementById( 'ec_terms_agreement_row' ) ).show( );
		
		if( jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_order_row' ) ).show( );
			
		if( jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).length )
			jQuery( document.getElementById( 'wpeasycart_submit_paypal_order_row' ) ).hide( );
			
		payment_method = "amazonpay";
	}
	
	var data = {
		action: 'ec_ajax_update_payment_method',
		payment_method: payment_method,
		nonce: nonce
	};
	
	jQuery.ajax({
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ }
	} );
	
}

function ec_update_billing_address_display( billing_address_type, nonce ) {
	jQuery( '.ec_cart_billing_table_row' ).removeClass( 'ec_billing_row_selected' );
	jQuery( '.ec_cart_billing_table_column > input[value="' + billing_address_type + '"]' ).parent().parent().addClass( 'ec_billing_row_selected' );
	
	if ( '0' == billing_address_type ) {
		jQuery( '.ec_cart_billing_table_address' ).hide();
		if ( jQuery( document.getElementById( 'ec_shipping_selector' ) ).length ) {
			jQuery( document.getElementById( 'ec_shipping_selector' ) ).val( '0' );
		}
		jQuery( document.getElementById( 'ec_billing_order_error' ) ).hide();
	} else {
		jQuery( '.ec_cart_billing_table_address' ).show();
		if ( jQuery( document.getElementById( 'ec_shipping_selector' ) ).length ) {
			jQuery( document.getElementById( 'ec_shipping_selector' ) ).val( '1' );
		}
	}

	var billing_country = ( ( jQuery( document.getElementById( 'ec_billing_country' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_country' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_country' ) ).val() );
	var billing_state = '';
	if ( jQuery( document.getElementById( 'ec_billing_state' ) ).length ) {
		billing_state = jQuery( document.getElementById( 'ec_billing_state' ) ).val();
	} else if ( jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).length ) {
		billing_state = jQuery( document.getElementById( 'ec_cart_billing_state_' + billing_country ) ).val();
	} else {
		billing_state = jQuery( document.getElementById( 'ec_cart_billing_state' ) ).val()
	}
	var data = {
		action: 'ec_ajax_update_billing_address_type',
		billing_address_type: billing_address_type,
		ec_billing_address_line_1: ( ( jQuery( document.getElementById( 'ec_billing_address_line_1' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_address_line_1' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_address' ) ).val() ),
		ec_billing_address_line_2: ( ( jQuery( document.getElementById( 'ec_billing_address_line_2' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_address_line_2' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_address2' ) ).val() ),
		ec_billing_city: ( ( jQuery( document.getElementById( 'ec_billing_city' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_city' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_city' ) ).val() ),
		ec_billing_state: billing_state,
		ec_billing_zip: ( ( jQuery( document.getElementById( 'ec_billing_zip' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_zip' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_zip' ) ).val() ),
		ec_billing_country: billing_country,
		ec_billing_phone: ( ( jQuery( document.getElementById( 'ec_billing_phone' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_phone' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_phone' ) ).val() ),
		ec_billing_name: ( ( jQuery( document.getElementById( 'ec_billing_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_first_name' ) ).val() ),
		ec_billing_last_name: ( ( jQuery( document.getElementById( 'ec_billing_last_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_last_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_last_name' ) ).val() ),
		ec_billing_company_name: ( ( jQuery( document.getElementById( 'ec_billing_company_name' ) ).length ) ? jQuery( document.getElementById( 'ec_billing_company_name' ) ).val() : jQuery( document.getElementById( 'ec_cart_billing_company_name' ) ).val() ),
		nonce: nonce
	};

	jQuery.ajax({
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data,
	} );
}

function ec_show_cc_type( type ){
	
	if( jQuery( document.getElementById( 'ec_card_visa' ) ) ){
		if( type == "visa" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_visa' ) ).show( );
			jQuery( document.getElementById( 'ec_card_visa_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_visa' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_visa_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_discover' ) ) ){
		if( type == "discover" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_discover' ) ).show( );
			jQuery( document.getElementById( 'ec_card_discover_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_discover' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_discover_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_mastercard' ) ) ){
		if( type == "mastercard" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_mastercard' ) ).show( );
			jQuery( document.getElementById( 'ec_card_mastercard_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_mastercard' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_mastercard_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_amex' ) ) ){
		if( type == "amex" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_amex' ) ).show( );
			jQuery( document.getElementById( 'ec_card_amex_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_amex' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_amex_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_jcb' ) ) ){
		if( type == "jcb" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_jcb' ) ).show( );
			jQuery( document.getElementById( 'ec_card_jcb_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_jcb' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_jcb_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_diners' ) ) ){
		if( type == "diners" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_diners' ) ).show( );
			jQuery( document.getElementById( 'ec_card_diners_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_diners' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_diners_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_laser' ) ) ){
		if( type == "laser" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_laser' ) ).show( );
			jQuery( document.getElementById( 'ec_card_laser_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_laser' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_laser_inactive' ) ).show( );
		}
	}
	
	if( jQuery( document.getElementById( 'ec_card_maestro' ) ) ){
		if( type == "maestro" || type == "all" ){
			jQuery( document.getElementById( 'ec_card_maestro' ) ).show( );
			jQuery( document.getElementById( 'ec_card_maestro_inactive' ) ).hide( );
		}else{
			jQuery( document.getElementById( 'ec_card_maestro' ) ).hide( );
			jQuery( document.getElementById( 'ec_card_maestro_inactive' ) ).show( );
		}
	}
	
}

function wpeasycart_bluecheck_verify( ){
	try {
		
        BlueCheckService.showModal();
        return false;
		
    } catch(e) {        
        console.error('[BlueCheckService::customValidation]', e);
        BlueCheckService.BcWebLogger('Error: ' + (e.message || e));
        return false;
    }
}

function ec_validate_cart_details( ){
	
	var login_complete = true;
	var billing_complete = ec_validate_address_block( 'ec_cart_billing' );
	var shipping_complete = true;
	var email_complete = true;
	var create_account_complete = true;
	var terms_complete = true;
    var recaptcha_complete = true;
	var bluecheck_complete = true;
	
	if( jQuery( document.getElementById( 'ec_login_selector' ) ).is( ':checked' ) )
		login_complete = ec_validate_cart_login( );
	
	if( jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) )
		shipping_complete = ec_validate_address_block( 'ec_cart_shipping' );
	
	if( jQuery( document.getElementById( 'ec_contact_email' ) ).length )
		email_complete = ec_validate_email_block( 'ec_contact' );
	
	if( jQuery( document.getElementById( 'ec_create_account_selector' ) ).is( ':checked' ) || ( jQuery( document.getElementById( 'ec_create_account_selector' ) ).is(':hidden' ) && jQuery( document.getElementById( 'ec_create_account_selector' ) ).val( ) == "create_account" ) ) {
		create_account_complete = ec_validate_create_account( 'ec_contact' );
		terms_complete = ec_validate_terms_section();
	}

    if( document.getElementById( 'ec_account_register_recaptcha' ) ){
        var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_register' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', '1px solid red' );
			recaptcha_complete = false;
		}else{
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', 'none' );
		}
    }
		
	if( document.getElementById( 'bcvTrigger' ) ){
		bluecheck_complete = wpeasycart_bluecheck_verify( );
		if( !bluecheck_complete )
			return false;
	}
		
	if( login_complete && billing_complete && shipping_complete && email_complete && create_account_complete && terms_complete && recaptcha_complete ){
		ec_hide_error( 'ec_checkout' );
		ec_hide_error( 'ec_checkout2' );
		jQuery( '.ec_checkout_details_submit' ).parent().addClass( 'wp-easycart-running' );
		return true;
	}else{
		ec_show_error( 'ec_checkout' );
		ec_show_error( 'ec_checkout2' );
		return false;
	}
	
}

function ec_validate_paypal_express_submit_order( ){
	var terms_complete = ec_validate_terms( );
	if( terms_complete ){
		jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
		jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
		ec_hide_error( 'ec_submit_order' );
		return true;
	}else{
		jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
		jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
		ec_show_error( 'ec_submit_order' );
		return false;
	}
}

function ec_validate_submit_order( ){
	var email_complete = true;
	var create_account_complete = true;
	var shipping_info_complete = true;
	var shipping_method_complete = true;
	var billing_info_complete = true;
	var scrolled_top = false;
	if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
		wp_easycart_update_contact_email_v2();
	}
	if ( jQuery( document.getElementById( 'ec_contact_email_complete' ) ).length ) {
		if ( '0' == jQuery( document.getElementById( 'ec_contact_email_complete' ) ).val() ) {
			email_complete = false;
			if ( ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).offset().top
				}, 750 );
				scrolled_top = true;
			}
		}
	} else if ( jQuery( document.getElementById( 'ec_contact_email' ) ).length ) {
		if ( ! ec_validate_email_block( 'ec_contact' ) || ( jQuery( document.getElementById( 'ec_create_account_email_error' ) ).length && jQuery( document.getElementById( 'ec_create_account_email_error' ) ).is( ':visible' ) ) ) {
			email_complete = false;
			if ( ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_contact_header' ) ).offset().top
				}, 750 );
				scrolled_top = true;
			}
		}
	}
	if ( jQuery( document.getElementById( 'ec_user_create_form' ) ).length ) {
		jQuery( document.getElementById( 'ec_user_login_form' ) ).hide( );
		jQuery( document.getElementById( 'ec_user_contact_form' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_link' ) ).show( );
		jQuery( document.getElementById( 'ec_user_login_cancel_link' ) ).hide( );
		jQuery( document.getElementById( 'ec_login_selector' ) ).prop( 'checked', false );
		jQuery( document.getElementById( 'ec_user_create_form' ) ).show();
		if ( ! ec_validate_create_account( 'ec_contact' ) ) {
			jQuery( document.getElementById( 'ec_create_account_order_error' ) ).show();
			create_account_complete = false;
			if ( ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_create_account_header' ) ).offset().top
				}, 750 );
				scrolled_top = true;
			}
		} else {
			jQuery( document.getElementById( 'ec_create_account_order_error' ) ).hide();
		}
	}
	if ( jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).length ) {
		if ( '0' == jQuery( document.getElementById( 'ec_shipping_complete' ) ).val() ) {
			shipping_info_complete = false;
			if ( ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).offset().top
				}, 750 );
				scrolled_top = true;
			}
		} else if ( jQuery( document.getElementById( 'ec_cart_shipping_country' ) ).length ) {
			if ( ! ec_validate_address_block( 'ec_cart_shipping' ) ) {
				shipping_info_complete = false;
				if ( ! scrolled_top ) {
					jQuery( [ document.documentElement, document.body ] ).animate( {
						scrollTop: jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).offset().top
					}, 750 );
					scrolled_top = true;
				}
			}
		} else if ( jQuery( document.getElementById( 'ec_cart_billing_country' ) ).length ) {
			if ( ! ec_validate_address_block( 'ec_cart_billing' ) ) {
				billing_info_complete = false;
				if ( ! scrolled_top ) {
					jQuery( [ document.documentElement, document.body ] ).animate( {
						scrollTop: jQuery( document.getElementById( 'ec_cart_onepage_info' ) ).offset().top
					}, 750 );
					scrolled_top = true;
				}
			}
		}
	}
	if ( jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).length ) {
		if ( ! jQuery( 'input[name=ec_cart_shipping_method]:checked' ).length ) {
			shipping_method_complete = false;
			if ( ! scrolled_top ) {
				jQuery( [ document.documentElement, document.body ] ).animate( {
					scrollTop: jQuery( document.getElementById( 'ec_cart_onepage_shipping' ) ).offset().top - 50
				}, 750 );
				scrolled_top = true;
			}
		}
	}
	if ( jQuery( document.getElementById( 'billing_address_type_different' ) ).length && jQuery( document.getElementById( 'billing_address_type_different' ) ).is( ':checked' ) ) {
		if ( jQuery( document.getElementById( 'ec_billing_complete' ) ).length && '0' == jQuery( document.getElementById( 'ec_billing_complete' ) ).val() ) {
			billing_info_complete = false;
		} else if ( jQuery( document.getElementById( 'ec_cart_billing_country' ) ).length && ! ec_validate_address_block( 'ec_cart_billing' ) ) {
			billing_info_complete = false;
		}
	}

	var payment_method_complete = ec_validate_payment_method( );
	var terms_complete = ec_validate_terms( );

	if ( jQuery( document.getElementById( 'ec_email_order1_error' ) ).length ) {
		if ( email_complete ) {
			ec_hide_error( 'ec_email_order1' );
			ec_hide_error( 'ec_email_order2' );
		} else {
			ec_show_error( 'ec_email_order1' );
			ec_show_error( 'ec_email_order2' );
		}
	}

	if ( jQuery( document.getElementById( 'ec_shipping_order_error' ) ).length ) {
		if ( shipping_info_complete ) {
			ec_hide_error( 'ec_shipping_order' );
		} else {
			ec_show_error( 'ec_shipping_order' );
		}
	}

	if ( jQuery( document.getElementById( 'ec_shipping_method_order_error' ) ).length ) {
		if ( shipping_method_complete ) {
			ec_hide_error( 'ec_shipping_method_order' );
		} else {
			ec_show_error( 'ec_shipping_method_order' );
		}
	}

	if ( billing_info_complete ) {
		ec_hide_error( 'ec_billing_order' );
	} else {
		ec_show_error( 'ec_billing_order' );
	}

	if ( payment_method_complete && terms_complete && email_complete && shipping_info_complete && shipping_method_complete && billing_info_complete ) {
		if( !document.getElementById( 'ec_stripe_card_row' ) && !document.getElementById( 'wpec_braintree_dropin' ) ){
			jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
			ec_hide_error( 'ec_submit_order' );
			if( jQuery( document.getElementById( 'ec_card_number' ) ).length )
				jQuery( document.getElementById( 'ec_card_number' ) ).val( jQuery( document.getElementById( 'ec_card_number' ) ).val( ).replace( /\s+/g, '' ) );
		}
		return true;
	}else{
		jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
		jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
		ec_show_error( 'ec_submit_order' );
		return false;
	}
	
}

function ec_validate_submit_invoice( ){
	
	var payment_method_complete = ec_validate_payment_method( );
	var terms_complete = ec_validate_terms( );
	var billing_complete = ec_validate_address_block( 'ec_cart_billing' );
	
	if( payment_method_complete && terms_complete && billing_complete ){
		if( !document.getElementById( 'ec_stripe_card_row' ) && !document.getElementById( 'wpec_braintree_dropin' ) ){
			jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
			ec_hide_error( 'ec_submit_order' );
			if( jQuery( document.getElementById( 'ec_card_number' ) ).length )
				jQuery( document.getElementById( 'ec_card_number' ) ).val( jQuery( document.getElementById( 'ec_card_number' ) ).val( ).replace( /\s+/g, '' ) );
		}
		return true;
	}else{
		jQuery( document.getElementById( 'ec_cart_submit_order' ) ).show( );
		jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).hide( );
		ec_show_error( 'ec_submit_order' );
		return false;
	}
	
}

function ec_validate_submit_subscription( ){
	var login_complete = true;
	var billing_complete = ec_validate_address_block( 'ec_cart_billing' );
	var shipping_complete = true;
	var email_complete = true;
	var create_account_complete = true;
	var payment_method_complete = ec_validate_payment_method( );
	var terms_complete = ec_validate_terms( );
	
	if ( jQuery( document.getElementById( 'ec_user_login_form' ) ).is( ':visible' ) ) {
		login_complete = ec_validate_cart_login( );
	}

	if ( jQuery( document.getElementById( 'ec_user_contact_form' ) ).is( ':visible' ) ) {
		email_complete = ec_validate_email_block( 'ec_contact' );
	}
	
	if ( jQuery( document.getElementById( 'ec_user_contact_form' ) ).is( ':visible' ) ) {
		create_account_complete = ec_validate_create_account( 'ec_contact' );
	}

	if ( jQuery( document.getElementById( 'ec_shipping_selector' ) ).is( ':checked' ) ) {
		shipping_complete = ec_validate_address_block( 'ec_cart_shipping' );
	}
		
	if( login_complete && billing_complete && shipping_complete && email_complete && create_account_complete && payment_method_complete && terms_complete ){
		if( !document.getElementById( 'ec_stripe_card_row' ) && !document.getElementById( 'wpec_braintree_dropin' ) ){
			ec_hide_error( 'ec_checkout' );
			jQuery( document.getElementById( 'ec_cart_submit_order' ) ).hide( );
			jQuery( document.getElementById( 'ec_cart_submit_order_working' ) ).show( );
		}
		return true;
	}else{
		ec_show_error( 'ec_checkout' );
		return false;
	}
	
}

function ec_validate_cart_login( ){
	
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_cart_login_email' ) ).val( ).trim( );
	var password = jQuery( document.getElementById( 'ec_cart_login_password' ) ).val( );
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( 'ec_cart_login_email' );
	}else{
		ec_hide_error( 'ec_cart_login_email' );
	}
	
	if( !ec_validate_text( password ) ){
		errors = true;
		ec_show_error( 'ec_cart_login_password' );
	}else{
		ec_hide_error( 'ec_cart_login_password' );
	}
    
    if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_login' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', '1px solid red' );
			errors = true;
		}else{
			jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	return ( !errors );
	
}

function ec_validate_address_block( prefix ){
	
	var errors = false;
	var country = jQuery( document.getElementById( '' + prefix + '_country' ) ).val( );
	var first_name = jQuery( document.getElementById( '' + prefix + '_first_name' ) ).val( );
	var last_name = jQuery( document.getElementById( '' + prefix + '_last_name' ) ).val( );
	var city = jQuery( document.getElementById( '' + prefix + '_city' ) ).val( );
	var address = jQuery( document.getElementById( '' + prefix + '_address' ) ).val( );
	if( jQuery( document.getElementById( '' + prefix + '_state_' + country ) ) )
		var state = jQuery( document.getElementById( '' + prefix + '_state_' + country ) ).val( );
	else
		var state = jQuery( document.getElementById( '' + prefix + '_state' ) ).val( );
	var zip = jQuery( document.getElementById( '' + prefix + '_zip' ) ).val( );
	var phone = jQuery( document.getElementById( '' + prefix + '_phone' ) ).val( );
	
	if( !ec_validate_select( country ) ){
		errors = true;
		ec_show_error( prefix + '_country' );
	}else{
		ec_hide_error( prefix + '_country' );
	}
	
	if( !ec_validate_text( first_name ) ){
		errors = true;
		ec_show_error( prefix + '_first_name' );
	}else{
		ec_hide_error( prefix + '_first_name' );
	}
	
	if( !ec_validate_text( last_name ) ){
		errors = true;
		ec_show_error( prefix + '_last_name' );
	}else{
		ec_hide_error( prefix + '_last_name' );
	}
	
	if( !ec_validate_text( city ) ){
		errors = true;
		ec_show_error( prefix + '_city' );
	}else{
		ec_hide_error( prefix + '_city' );
	}
	
	if( !ec_validate_text( address ) ){
		errors = true;
		ec_show_error( prefix + '_address' );
	}else{
		ec_hide_error( prefix + '_address' );
	}
	
	if( jQuery( document.getElementById( '' + prefix + '_state_' + country ) ).length ){
		if( !ec_validate_select( state ) ){
			errors = true;
			ec_show_error( prefix + '_state' );
		}else{
			ec_hide_error( prefix + '_state' );
		}
	}else{
		ec_hide_error( prefix + '_state' );
	}
	
	if( !ec_validate_zip_code( zip, country ) ){
		errors = true;
		ec_show_error( prefix + '_zip' );
	}else{
		ec_hide_error( prefix + '_zip' );
	}
	
	if( jQuery( document.getElementById( '' + prefix + '_phone_error' ) ).length ){
        if( jQuery( document.getElementById( '' + prefix + '_phone' ) ).length && !ec_validate_text( phone ) ){
            errors = true;
            ec_show_error( prefix + '_phone' );
        }else{
            ec_hide_error( prefix + '_phone' );
        }
    }
	
	return ( !errors );
	
}

function ec_validate_email_block( prefix ){
	
	var errors = false;
	var email = jQuery( document.getElementById( '' + prefix + '_email' ) ).val( ).trim();
	var retype_email = "";
	if( jQuery( document.getElementById( '' + prefix + '_email_retype' ) ).length ) {
		retype_email = jQuery( document.getElementById( '' + prefix + '_email_retype' ) ).val( ).trim();
	} else if ( jQuery( document.getElementById( '' + prefix + '_retype_email' ) ).length ) {
		retype_email = jQuery( document.getElementById( '' + prefix + '_retype_email' ) ).val( ).trim();
	} else {
		retype_email = email;
	}
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( prefix + '_email' );
	}else{
		ec_hide_error( prefix + '_email' );
	}
	
	if( !ec_validate_match( email, retype_email) ){
		errors = true;
		ec_show_error( prefix + '_email_retype' );
	}else{
		ec_hide_error( prefix + '_email_retype' );
	}
	
	return ( !errors );
	
}

function ec_validate_create_account( prefix ){
	
	var errors = false;
	var first_name = ( jQuery( document.getElementById( '' + prefix + '_first_name' ) ).length ) ? jQuery( document.getElementById( '' + prefix + '_first_name' ) ).val() : '';
	var last_name = ( jQuery( document.getElementById( '' + prefix + '_last_name' ) ).length ) ? jQuery( document.getElementById( '' + prefix + '_last_name' ) ).val() : '';
	var password = jQuery( document.getElementById( '' + prefix + '_password' ) ).val( );
	var retype_password = '';
	
	if ( jQuery( document.getElementById( '' + prefix + '_password_retype' ) ).length ) {
		retype_password = jQuery( document.getElementById( '' + prefix + '_password_retype' ) ).val( );
	} else {
		retype_password = password;
	}
	
	if( jQuery( document.getElementById( '' + prefix + '_first_name' ) ).length && ! ec_validate_text( first_name ) ){
		errors = true;
		ec_show_error( prefix + '_first_name' );
	}else{
		ec_hide_error( prefix + '_first_name' );
	}
	
	if( jQuery( document.getElementById( '' + prefix + '_last_name' ) ).length && ! ec_validate_text( last_name ) ){
		errors = true;
		ec_show_error( prefix + '_last_name' );
	}else{
		ec_hide_error( prefix + '_last_name' );
	}
	
	if ( ! ec_validate_password( password, retype_password ) ) {
		errors = true;
		ec_show_error( prefix + '_password' );
	} else {
		ec_hide_error( prefix + '_password' );
	}
	
	if( jQuery( document.getElementById( prefix + '_password_retype' ) ).length && ! ec_validate_match( password, retype_password ) ){
		errors = true;
		ec_show_error( prefix + '_password_retype' );
	}else{
		ec_hide_error( prefix + '_password_retype' );
	}
	
	return ( !errors );
	
}

function ec_validate_terms_section() {
	var errors = false;
	if ( jQuery( document.getElementById( 'ec_terms_agree' ) ).length ) {
		if ( ! ec_validate_terms() ) {
			errors = true;
		}
	}
	return ( ! errors );
}

function ec_validate_payment_method( ){
	
	var errors = false;
	var payment_method = "credit_card";
	if( jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).length ){
		ec_hide_error( 'ec_payment_method' );
		payment_method = jQuery( 'input:radio[name=ec_cart_payment_selection]:checked' ).val( );
	}else if( jQuery( 'input:radio[name=ec_cart_payment_selection]' ).length ){
		ec_show_error( 'ec_payment_method' );
		return false;
	}else{ // free order or no payment methods
		ec_hide_error( 'ec_payment_method' );
		return true;
	}
	
	var card_holder_name = "-1";
	if( document.getElementById( 'ec_card_holder_name' ) ){
		card_holder_name = jQuery( document.getElementById( 'ec_card_holder_name' ) ).val( );
	}
	
	if( payment_method == "affirm" ){
		ec_checkout_with_affirm( );
		ec_hide_error( 'ec_submit_order' );
		return false;
		
	}else if( payment_method == "credit_card" && jQuery( document.getElementById( 'wp-easycart-square-card-container' ) ).length ) {
		return true;

	}else if( payment_method == "credit_card" && ( document.getElementById( 'ec_stripe_card_row' ) || document.getElementById( 'wpec_braintree_dropin' ) ) ){ 
		return true;

	}else if( payment_method == "credit_card" && document.getElementById( 'cardpointe_token' ) ){
		if( jQuery( document.getElementById( 'cardpointe_token' ) ).val( ) == '' ){
			return false;
		}else{
			return true;
		}

	}else if( payment_method == "credit_card" ){
		
		var cardType = jQuery.payment.cardType( jQuery( document.getElementById( 'ec_card_number' ) ).val( ) );
		
		if( card_holder_name != "-1" && card_holder_name == "" ){
			errors = true;
			ec_show_error( 'ec_card_holder_name' );
		}else{
			ec_hide_error( 'ec_card_holder_name' );
		}
		
		if( !jQuery.payment.validateCardNumber( jQuery( document.getElementById( 'ec_card_number' ) ).val( ) ) ){
			errors = true;
			ec_show_error( 'ec_card_number' );
		}else{
			ec_hide_error( 'ec_card_number' );
		}
		
		if( !jQuery.payment.validateCardExpiry( jQuery( document.getElementById( 'ec_cc_expiration' ) ).payment( 'cardExpiryVal' ) ) ){
			errors = true;
			ec_show_error( 'ec_expiration_date' );
		}else{
			ec_hide_error( 'ec_expiration_date' );
		}
		
		if( !jQuery.payment.validateCardCVC( jQuery( document.getElementById( 'ec_security_code' ) ).val( ), cardType) ){
			errors = true;
			ec_show_error( 'ec_security_code' );
		}else{
			ec_hide_error( 'ec_security_code' );
		}
		
	}
	
	return ( !errors );
	
}

function ec_validate_terms( ){
	
	var errors = false;
	
	if( jQuery( document.getElementById( 'ec_terms_agree' ) ).is( ':checked' ) || jQuery( document.getElementById( 'ec_terms_agree' ) ).val( ) == '2' ){
		ec_hide_error( 'ec_terms' );
	}else{
		errors = true;
		ec_show_error( 'ec_terms' );
	}
	
	return ( !errors );
	
}

function ec_validate_email( email ){
	
	return /^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test( email );

}

function ec_validate_password( pw ){
	
	if( pw && pw.length > 5 )
		return true;
	else
		return false;
	
}

function ec_validate_text( str ){
	
	if( str && str.length > 0 )
		return true;
	else
		return false;
	
}

function ec_validate_select( val ){
	
	if( val && val != 0 )
		return true;
	else
		return false;
	
}

function ec_validate_match( val1, val2 ){
	
	if( val1 == val2 )
		return true;
	else
		return false;
	
}

function ec_validate_zip_code( zip, country ){
	
	zip = zip.trim( );
	
	if( country == "US" )
		return /(^\d{5}$)|(^\d{5}-\d{4}$)/.test( zip );
	else if( country == "AU" )
		return /^([0-9]{4})$/.test( zip );
	else if( country == "CA" ){
		var regex = new RegExp( /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i );
    	return regex.test( zip );
	}else if( country == "GB" ){
		var postcode = zip.replace(/\s/g, "");
		var regex = /^(([A-Za-z]{2}[0-9][A-Za-z] ?[0-9][A-Za-z]{2})|([A-Za-z][0-9][A-Za-z] ?[0-9][A-Za-z]{2})|([A-Za-z][0-9] ?[0-9][A-Za-z]{2})|([A-Za-z][0-9]{2} ?[0-9][A-Za-z]{2})|([A-Za-z]{2}[0-9] ?[0-9][A-Za-z]{2})|([A-Za-z]{2}[0-9]{2} ?[0-9][A-Za-z]{2}))$/i;
		return regex.test( postcode );
	}else
		return ec_validate_text( zip );
	
}

function ec_is_state_required( country ){
	if( country == "AU" || country == "BR" || country == "CA" || country == "CN" || country == "GB" || country == "IN" || country == "JP" || country == "US" )
		return true;
	else
		return false; 
}

function ec_get_card_type( card_number ){
	
	var num = card_number;
	
	num = num.replace(/[^\d]/g,'');
	
	if( num.match( /^5[1-5]\d{14}$/ ) )														return "mastercard";
	else if( num.match( /^4\d{15}/ ) || num.match( /^4\d{12}/ ) )							return "visa";
	else if( num.match( /(^3[47])((\d{11}$)|(\d{13}$))/ ) )									return "amex";
	else if( num.match( /^6(?:011\d{12}|5\d{14}|4[4-9]\d{13}|22(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d{2}|9(?:[01]\d|2[0-5]))\d{10})$/ ) )									
																							return "discover";
	else if( num.match( /^(?:5[0678]\d\d|6304|6390|67\d\d)\d{8,15}$/ ) )					return "maestro";
	else if( num.match( /(^(352)[8-9](\d{11}$|\d{12}$))|(^(35)[3-8](\d{12}$|\d{13}$))/ ) )	return "jcb";
	else if( num.match( /(^(30)[0-5]\d{11}$)|(^(36)\d{12}$)|(^(38[0-8])\d{11}$)/ ) )		return "diners";
	else																					return "all";
		
}

function ec_validate_credit_card( card_number ){
	
	var card_type = ec_get_card_type( card_number );
	
	if( card_type == "visa" || card_type == "delta" || card_type == "uke" ){
		if( /^4[0-9]{12}(?:[0-9]{3}|[0-9]{6})?$/.test( card_number ) )								return true;
		else 																						return false;
	
	}else if( card_type == "discover" ){
		if( /^6(?:011\d{12}|5\d{14}|4[4-9]\d{13}|22(?:1(?:2[6-9]|[3-9]\d)|[2-8]\d{2}|9(?:[01]\d|2[0-5]))\d{10})$/.test( card_number ) )	
																									return true;
		else																						return false;
	
	}else if( card_type == "mastercard" || card_type == "mcdebit" ){
		if( /^5[1-5]\d{14}$/.test( card_number ) )													return true;
		else																						return false;
	
	}else if( card_type == "amex" ){
		if( /^3[47][0-9]{13}$/.test( card_number ) )												return true;
		else																						return false;
	
	}else if( card_type == "diners" ){
		if( /^3(?:0[0-5]|[68][0-9])[0-9]{11}$/.test( card_number ) )								return true;
		else																						return false;
	
	}else if( card_type == "jcb" ){
		if( /^(?:2131|1800|35\d{3})\d{11}$/.test( card_number ) )											return true;
		else																						return false;
	
	}else if( card_type == "maestro" ){
		if( /(^(5[0678]\d{11,18}$))|(^(6[^0357])\d{11,18}$)|(^(3)\d{13,20}$)/.test( card_number ) )	return true;
		else																						return false;	
	}
}

function ec_validate_security_code( security_code ){
	
	if( /^[0-9]{3,4}$/.test( security_code ) )													return true;
	else																						return false;

}

function ec_show_error( error_field ){
	jQuery( document.getElementById( '' + error_field + '_error' ) ).show( );
}

function ec_hide_error( error_field ){
	jQuery( document.getElementById( '' + error_field + '_error' ) ).hide( );
}

function ec_cart_subscription_shipping_method_change( shipping_method, price, nonce ){
	jQuery( document.getElementById( 'ec_cart_subscription_shipping_methods_loader' ) ).show();

	var ship_express = 0;
	if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) { 
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).is( ':checked' ) ) {
			ship_express = 1;
		}
	}

	if ( 'shipexpress' == shipping_method ) {
		shipping_method = 'standard';
		if ( jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).length ) { 
			jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).prop( 'checked', false );
		}
		jQuery( document.getElementById( 'ec_cart_shipping_method' ) ).prop( 'checked', 'checked' );

	} else if( 'free' == shipping_method ) {
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_ship_express' ) ).prop( 'checked', false );
		}
	}

	var data = {
		action: 'ec_ajax_update_subscription_shipping_method',
		product_id: jQuery( document.getElementById( 'product_id' ) ).val(),
		shipping_method: shipping_method,
		ship_express: ship_express,
		nonce: nonce
	};
	
	jQuery.ajax({
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( response ){
			var data_arr = JSON.parse( response );
			wpeasycart_subscription_cart_update_totals( data_arr );
			jQuery( document.getElementById( 'ec_cart_subscription_shipping_methods_loader' ) ).hide();
		}
	} );
	
}

function ec_cart_shipping_method_change( shipping_method, price, nonce ){
	jQuery( '.ec_cart_shipping_method_row' ).removeClass( 'ec_method_selected' );
	jQuery( '.ec_cart_shipping_method_row > input[value="' + shipping_method + '"]' ).parent().addClass( 'ec_method_selected' );
	jQuery( '.ec_cart_price_row.ec_cart_price_row_shipping_total, .ec_cart_price_row.ec_order_total' ).addClass( 'ec_cart_price_row_loading' );

	var ship_express = 0;
	if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) { 
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).is( ':checked' ) ) {
			ship_express = 1;
		}
	}

	if ( 'shipexpress' == shipping_method ) {
		shipping_method = 'standard';
		if ( jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).length ) { 
			jQuery( document.getElementById( 'ec_cart_shipping_method_free' ) ).prop( 'checked', false );
		}
		jQuery( document.getElementById( 'ec_cart_shipping_method' ) ).prop( 'checked', 'checked' );

	} else if( 'free' == shipping_method ) {
		if ( jQuery( document.getElementById( 'ec_cart_ship_express' ) ).length ) {
			jQuery( document.getElementById( 'ec_cart_ship_express' ) ).prop( 'checked', false );
		}
	}

	var data = {
		action: 'ec_ajax_update_shipping_method',
		shipping_method: shipping_method,
		ship_express: ship_express,
		nonce: nonce
	};
	
	jQuery.ajax({
		url: wpeasycart_ajax_object.ajax_url, 
		type: 'post', 
		data: data, 
		success: function( data ){ 
			var data_arr = data.split( '***' );
			jQuery( document.getElementById( 'ec_cart_shipping' ) ).html( data_arr[0] );
			jQuery( document.getElementById( 'ec_cart_total' ) ).html( data_arr[1] );
			jQuery( '.ec_cart_price_row.ec_cart_price_row_shipping_total, .ec_cart_price_row.ec_order_total' ).removeClass( 'ec_cart_price_row_loading' );
		}
	} );
	
}

jQuery( document ).ready( function( $ ){
    $( ".ec_menu_vertical" ).accordion({
        accordion:true,
        speed: 500,
        closedSign: '[+]',
        openedSign: '[-]'
    });
});
(function(jQuery){
    jQuery.fn.extend({
    accordion: function(options) {
        
		var defaults = {
			accordion: 'true',
			speed: 300,
			closedSign: '[+]',
			openedSign: '[-]'
		};
		var opts = jQuery.extend(defaults, options);
 		var jQuerythis = jQuery(this);
 		jQuerythis.find("li").each(function() {
 			if(jQuery(this).find("ul").size() != 0){
 				jQuery(this).find("a:first").append("<span>"+ opts.closedSign +"</span>");
 				if(jQuery(this).find("a:first").attr('href') == "#"){
 		  			jQuery(this).find("a:first").click(function(){return false;});
 		  		}
 			}
 		});
 		jQuerythis.find("li.active").each(function() {
 			jQuery(this).parents("ul").slideDown(opts.speed);
 			jQuery(this).parents("ul").parent("li").find("span:first").html(opts.openedSign);
 		});
  		jQuerythis.find("li a").click(function() {
  			if(jQuery(this).parent().find("ul").size() != 0){
  				if(opts.accordion){
  					if(!jQuery(this).parent().find("ul").is(':visible')){
  						parents = jQuery(this).parent().parents("ul");
  						visible = jQuerythis.find("ul:visible");
  						visible.each(function(visibleIndex){
  							var close = true;
  							parents.each(function(parentIndex){
  								if(parents[parentIndex] == visible[visibleIndex]){
  									close = false;
  									return false;
  								}
  							});
  							if(close){
  								if(jQuery(this).parent().find("ul") != visible[visibleIndex]){
  									jQuery(visible[visibleIndex]).slideUp(opts.speed, function(){
  										jQuery(this).parent("li").find("span:first").html(opts.closedSign);
  									});
  									
  								}
  							}
  						});
  					}
  				}
  				if(jQuery(this).parent().find("ul:first").is(":visible")){
  					jQuery(this).parent().find("ul:first").slideUp(opts.speed, function(){
  						jQuery(this).parent("li").find("span:first").delay(opts.speed).html(opts.closedSign);
  					});
  					
  					
  				}else{
  					jQuery(this).parent().find("ul:first").slideDown(opts.speed, function(){
  						jQuery(this).parent("li").find("span:first").delay(opts.speed).html(opts.openedSign);
  					});
  				}
  			}
  		});
    }
});
})(jQuery);

function ec_cart_widget_click( ){
	if( !jQuery('.ec_cart_widget_minicart_wrap').is(':visible') ) 
		jQuery('.ec_cart_widget_minicart_wrap').fadeIn( 200 );
	else
		jQuery('.ec_cart_widget_minicart_wrap').fadeOut( 100 );
}

function ec_cart_widget_mouseover( ){
	if( !jQuery('.ec_cart_widget_minicart_wrap').is(':visible') ){
		jQuery('.ec_cart_widget_minicart_wrap').fadeIn( 200 );
		jQuery('.ec_cart_widget_minicart_bg').css( "display", "block" );
	}
}

function ec_cart_widget_mouseout( ){
	if( jQuery('.ec_cart_widget_minicart_wrap').is(':visible') ) {
		jQuery('.ec_cart_widget_minicart_wrap').fadeOut( 100 );
		jQuery('.ec_cart_widget_minicart_bg').css( "display", "none" );
	}
}

var wpeasycart_last_search = "";
function ec_live_search_update( nonce ){
	
	var code = event.which || event.keyCode;
	var search_val = jQuery( '.ec_search_input' ).val( );
	
	if( code != 16 && code != 17 && code != 18 && code != 20 && code != 37 && code != 38 && code != 39 && code != 40 && wpeasycart_last_search != search_val ){
		
		wpeasycart_last_search = search_val;
		
		var data = {
			action: 'ec_ajax_live_search',
			search_val: search_val,
			nonce: nonce
		};
		
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){
			if( wpeasycart_last_search == search_val ){
				data = JSON.parse( data );
				jQuery( document.getElementById( 'ec_search_suggestions' ) ).empty( );
				for( var i=0; i<data.length; i++ ){
					jQuery( document.getElementById( 'ec_search_suggestions' ) ).append( "<option value='" + data[i].title + "'>" );
				}
			}
		} } );
		
	}
	
}

function ec_account_forgot_password_button_click( ){
	
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_account_forgot_password_email' ) ).val( );
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( 'ec_account_forgot_password_email' );
	}else{
		ec_hide_error( 'ec_account_forgot_password_email' );
	}
	
	return( !errors );
	
}

function ec_account_register_button_click2( ){
	var top_half = ec_account_register_button_click( );
	var bottom_half = true;
	
	if( jQuery( document.getElementById( 'ec_account_billing_information_country' ) ).length )
		bottom_half = ec_account_billing_information_update_click( );
	
	var extra_notes_validated = ec_account_register_validate_notes( );
	
	var recaptcha_validated = true;
	if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_register' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', '1px solid red' );
			recaptcha_validated = false;
		}else{
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	if( top_half && bottom_half && extra_notes_validated && recaptcha_validated ){
		return true;
	}else{
		return false;
	}
}

function ec_account_register_button_click( ){
	var email_validated = ec_validate_email_block( 'ec_account_register' );
	var contact_validated = ec_validate_create_account( 'ec_account_register' );
	var terms_validated = ec_validate_terms_section();
	
	var recaptcha_validated = true;
	if( jQuery( document.getElementById( 'ec_account_register_recaptcha' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_register' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', '1px solid red' );
			recaptcha_validated = false;
		}else{
			jQuery( '#ec_account_register_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	if( email_validated && contact_validated && terms_validated && recaptcha_validated )
		return true;
	else
		return false;
	
}

function ec_account_billing_information_update_click( ){
	var address_validated = ec_validate_address_block( 'ec_account_billing_information' );
	
	if( address_validated )
		return true;
	else
		return false;
	
}

function ec_account_shipping_information_update_click( ){
	var address_validated = ec_validate_address_block( 'ec_account_shipping_information' );
	
	if( address_validated )
		return true;
	else
		return false;
	
}

function ec_account_personal_information_update_click( ){
	
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_account_personal_information_email' ) ).val( );
	
	if( jQuery( document.getElementById( 'ec_account_personal_information_first_name' ) ).length && !ec_validate_text( jQuery( document.getElementById( 'ec_account_personal_information_first_name' ) ).val( ) ) ){
		errors = true;
		ec_show_error( 'ec_account_personal_information_first_name' );
	}else{
		ec_hide_error( 'ec_account_personal_information_first_name' );
	}
	
	if( jQuery( document.getElementById( 'ec_account_personal_information_last_name' ) ).length && !ec_validate_text( jQuery( document.getElementById( 'ec_account_personal_information_last_name' ) ).val( ) ) ){
		errors = true;
		ec_show_error( 'ec_account_personal_information_last_name' );
	}else{
		ec_hide_error( 'ec_account_personal_information_last_name' );
	}
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( 'ec_account_personal_information_email' );
	}else{
		ec_hide_error( 'ec_account_personal_information_email' );
	}
	
	return( !errors );
}

function ec_account_password_button_click( ){
	
	var errors = false;
	var current_password = jQuery( document.getElementById( 'ec_account_password_current_password' ) ).val( );
	var new_password = jQuery( document.getElementById( 'ec_account_password_new_password' ) ).val( );
	var retype_password = jQuery( document.getElementById( 'ec_account_password_retype_new_password' ) ).val( );
	
	if( !ec_validate_password( current_password ) ){
		errors = true;
		ec_show_error( 'ec_account_password_current_password' );
	}else{
		ec_hide_error( 'ec_account_password_current_password' );
	}
	
	if( !ec_validate_password( new_password ) ){
		errors = true;
		ec_show_error( 'ec_account_password_new_password' );
	}else{
		ec_hide_error( 'ec_account_password_new_password' );
	}
	
	if( !ec_validate_match( new_password, retype_password ) ){
		errors = true;
		ec_show_error( 'ec_account_password_retype_new_password' );
	}else{
		ec_hide_error( 'ec_account_password_retype_new_password' );
	}
	
	return( !errors );
	
}

function ec_account_register_validate_notes( ){
	if( !jQuery( document.getElementById( 'ec_account_register_user_notes' ) ).length || ( jQuery( document.getElementById( 'ec_account_register_user_notes' ) ).length && jQuery( document.getElementById( 'ec_account_register_user_notes' ) ).val( ) != "" ) ){
		ec_hide_error( 'ec_account_register_user_notes' );
		return true;
	}else{
		ec_show_error( 'ec_account_register_user_notes' );
		return false;
	}
}

function ec_account_login_button_click( ){
	
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_account_login_email' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_account_login_password' ) ).val( );
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( 'ec_account_login_email' );
	}else{
		ec_hide_error( 'ec_account_login_email' );
	}
	
	if( !ec_validate_text( password ) ){
		errors = true;
		ec_show_error( 'ec_account_login_password' );
	}else{
		ec_hide_error( 'ec_account_login_password' );
	}
	
	if( jQuery( document.getElementById( 'ec_account_login_recaptcha' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_login' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', '1px solid red' );
			errors = true;
		}else{
			jQuery( '#ec_account_login_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	return ( !errors );

}

function ec_account_login_widget_button_click( ){
	
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_account_login_widget_email' ) ).val( );
	var password = jQuery( document.getElementById( 'ec_account_login_widget_password' ) ).val( );
	
	if( !ec_validate_email( email ) ){
		errors = true;
		ec_show_error( 'ec_account_login_widget_email' );
	}else{
		ec_hide_error( 'ec_account_login_widget_email' );
	}
	
	if( !ec_validate_text( password ) ){
		errors = true;
		ec_show_error( 'ec_account_login_widget_password' );
	}else{
		ec_hide_error( 'ec_account_login_widget_password' );
	}
	
	if( jQuery( document.getElementById( 'ec_account_login_widget_recaptcha' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_login_widget' ) ).val( );
		if( ! recaptcha_response.length ){
			jQuery( '#ec_account_login_widget_recaptcha > div' ).css( 'border', '1px solid red' );
			errors = true;
		}else{
			jQuery( '#ec_account_login_widget_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	return ( !errors );

}

function ec_close_popup_newsletter( nonce ){
	
	jQuery( '.ec_newsletter_container' ).fadeOut( 'slow' );
	
	var data = {
		action: 'ec_ajax_close_newsletter',
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ } } );
	
}

function ec_submit_newsletter_signup( nonce ){
	
	jQuery( '.ec_newsletter_pre_submit' ).hide( );
	jQuery( '.ec_newsletter_post_submit' ).show( );
		
	var email_address = jQuery( document.getElementById( 'ec_newsletter_email' ) ).val( );
	var newsletter_name = "";
	if( document.getElementById( 'ec_newsletter_name' ) )
		newsletter_name = jQuery( document.getElementById( 'ec_newsletter_name' ) ).val( );
	
	var data = {
		action: 'ec_ajax_submit_newsletter_signup',
		email_address: email_address,
		newsletter_name: newsletter_name,
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
	} } );
	
}

function ec_submit_newsletter_signup_widget( nonce ){
	
	jQuery( '.ec_newsletter_pre_submit' ).hide( );
	jQuery( '.ec_newsletter_post_submit' ).show( );
		
	var email_address = jQuery( '#ec_newsletter_email_widget' ).val( );
	var newsletter_name = "";
	if( document.getElementById( 'ec_newsletter_name_widget' ) )
		newsletter_name = jQuery( document.getElementById( 'ec_newsletter_name_widget' ) ).val( );
	
	var data = {
		action: 'ec_ajax_submit_newsletter_signup',
		email_address: email_address,
		newsletter_name: newsletter_name,
		nonce: nonce
	};
	
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
	} } );
	
}

function update_download_count( orderdetail_id ){
	
	if( jQuery( document.getElementById( 'ec_download_count_' + orderdetail_id ) ).length ){
		var count = Number( jQuery( document.getElementById( 'ec_download_count_' + orderdetail_id ) ).html( ) );
		var max_count = Number( jQuery( document.getElementById( 'ec_download_count_max_' + orderdetail_id ) ).html( ) );
		if( count < max_count ){
			count++;
			jQuery( document.getElementById( 'ec_download_count_' + orderdetail_id ) ).html( count );
		}
	}
	
}

function show_billing_info( ){
	jQuery( document.getElementById( 'ec_account_subscription_billing_information' ) ).slideToggle(600);
	return false;
}

function ec_check_update_subscription_info( ){
		
	if( jQuery( document.getElementById( 'ec_account_subscription_billing_information' ) ).is(":visible") ){
		
		var address_validated = ec_validate_address_block( 'ec_account_billing_information' );
		var payment_method_complete = ec_validate_payment_method( );
		var terms_complete = ec_validate_terms( );
		
		if( address_validated && payment_method_complete && terms_complete )
			return true;
		else
			return false;
			
	}else{
		return true;
	}
}

function ec_cancel_subscription_check( confirm_text ){
	return confirm( confirm_text );
}

function ec_details_show_inquiry_form( product_id ){
	jQuery( '.ec_details_inquiry_popup_' + product_id ).fadeIn( 'fast' );
	return false;
}

function ec_details_hide_inquiry_popup( product_id ){
	jQuery( '.ec_details_inquiry_popup_' + product_id ).fadeOut( 'fast' );
}

function ec_details_show_image_popup( model_number ){
	jQuery( document.getElementById( 'ec_details_large_popup_' + model_number ) ).show( );
	jQuery( 'html' ).css( 'overflow', 'hidden' );
}

function ec_details_hide_large_popup( model_number ){
	jQuery( document.getElementById( 'ec_details_large_popup_' + model_number ) ).hide( );
	jQuery( 'html' ).css( 'overflow', 'scroll' );
}

function ec_create_ideal_order_redirect( source, nonce ){
	var redirect = source.redirect.url;
	var data = {
		action: 'ec_ajax_create_stripe_ideal_order',
		source: source,
		nonce: nonce
	};
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){
		window.location.href = redirect;
	} } );
}

function ec_stripe_check_order_status( source, nonce, count = 1 ) {
	var data = {
		action: 'ec_ajax_stripe_check_order_status',
		source: source,
		nonce: nonce,
		count: count,
	};
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){
		var response_obj = JSON.parse( data );
		if ( 'requires_confirmation' == response_obj.status ) {
			ec_stripe_check_order_status( source, nonce, count++ );
		} else {
			window.location.href = response_obj.redirect;
		}
	} } );
}

function ec_notify_submit( product_id, rand_id, nonce ){
	var errors = false;
	var email = jQuery( document.getElementById( 'ec_email_notify_' + product_id + '_' + rand_id ) ).val( );
	if( !ec_validate_email( email ) ){
		errors = true;
		jQuery( document.getElementById( 'ec_email_notify_' + product_id + '_' + rand_id + '_error' ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_email_notify_' + product_id + '_' + rand_id + '_error' ) ).hide( );
	}
	
	var recaptcha_response = false;
	if( jQuery( document.getElementById( 'ec_grecaptcha_response_product_details' ) ).length ){
		recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_product_details' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_product_details_recaptcha > div' ).css( 'border', '1px solid red' );
			errors = true;
		}else{
			jQuery( '#ec_product_details_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	
	if( !errors ){
		jQuery( document.getElementById( 'ec_product_details_stock_notify_' + product_id + '_' + rand_id + '_loader_cover' ) ).show( );
		jQuery( document.getElementById( 'ec_product_details_stock_notify_' + product_id + '_' + rand_id + '_loader' ) ).show( );
		var data = {
			action: 'ec_ajax_subscribe_to_stock_notification',
			email: email,
			product_id: product_id,
			recaptcha_response: recaptcha_response,
			nonce: nonce
		};
		jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){
			jQuery( document.getElementById( 'ec_product_details_stock_notify_complete_' + product_id + '_' + rand_id ) ).show( );
			jQuery( document.getElementById( 'ec_product_details_stock_notify_' + product_id + '_' + rand_id ) ).hide( );
		jQuery( document.getElementById( 'ec_product_details_stock_notify_' + product_id + '_' + rand_id + '_loader_cover' ) ).hide( );
		jQuery( document.getElementById( 'ec_product_details_stock_notify_' + product_id + '_' + rand_id + '_loader' ) ).hide( );
		} } );
	}
	
}

function wpeasycart_load_cart( cart_page, success_code, error_code, language = 'NONE', nonce ){
	if( language == 'NONE' ){
	   language = wpeasycart_ajax_object.current_language;
	}
    var data = {
        action: 'ec_ajax_get_dynamic_cart_page',
        cart_page: cart_page,
        success_code: success_code,
        error_code: error_code,
        language: language,
		nonce: nonce
    };
    jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
        jQuery( document.getElementById( 'wpeasycart_cart_holder' ) ).replaceWith( data );
    } } );
}

function wpeasycart_load_account( account_page, page_id, success_code, error_code, language = 'NONE', nonce ){
	if( language == 'NONE' ){
	   language = wpeasycart_ajax_object.current_language;
	}
    var data = {
        action: 'ec_ajax_get_dynamic_account_page',
        account_page: account_page,
        page_id: page_id,
        success_code: success_code,
        error_code: error_code,
        language: language,
		nonce: nonce
    };
    jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
        jQuery( document.getElementById( 'wpeasycart_account_holder' ) ).replaceWith( data );
    } } );
}

function wpeasycart_update_tip( tip_rate, nonce ){
    jQuery( '.ec_cart_tip_item' ).removeClass( 'ec_tip_selected' );
    var tip_amount = Number( jQuery( document.getElementById( 'ec_cart_tip_custom' ) ).val( ) );
    jQuery( document.getElementById( 'ec_apply_tip_button' ) ).hide( );
    jQuery( document.getElementById( 'ec_applying_tip' ) ).show( );
    var data = {
        action: 'ec_ajax_update_tip_amount',
        tip_rate: tip_rate,
        tip_amount: tip_amount,
        language: wpeasycart_ajax_object.current_language,
		nonce: nonce
    };
    jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
        var response_obj = JSON.parse( data );
		ec_update_cart( response_obj );
        jQuery( document.getElementById( 'ec_apply_tip_button' ) ).show( );
        jQuery( document.getElementById( 'ec_applying_tip' ) ).hide( );
    } } );
}

function wp_easycart_text_notification_subscribe( wpeasycart_text_iti ) {
	var isValid = wpeasycart_text_iti.isValidNumber();
	if( ! isValid ) {
		jQuery( '#text_notification_error' ).show();
		jQuery( document.getElementById( 'text_notification_success' ) ).hide();
		return;
	} else {
		jQuery( '#text_notification_error' ).hide();
	}
	jQuery( document.getElementById( 'text_notification_loader' ) ).show();
	var text_number = wpeasycart_text_iti.getNumber();
	var order_id = jQuery( document.getElementById( 'text_order_id' ) ).val( );
	var data = {
		action: 'ec_ajax_subscribe_text_notification',
		text_number: text_number,
		order_id: order_id
	};
	jQuery.ajax( 
		{
			url: wpeasycart_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ){
				jQuery( document.getElementById( 'text_notification_loader' ) ).hide();
				jQuery( document.getElementById( 'text_notification_success' ) ).fadeIn( 'fast' );
				jQuery( document.getElementById( 'text_phone_number' ) ).val( '' )
			}
		}
	);
}
function ec_details_advanced_adjust_price( product_id, rand_id ) {
	if( jQuery( document.getElementById( 'ec_base_price_' + product_id + '_' + rand_id ) ).length == 0 ) {
		return;
	}
	var base_price = Number( jQuery( document.getElementById( 'ec_base_option_price_' + product_id + '_' + rand_id ) ).val() );
	if( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).length ) {
		base_price = Number( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).val() );
	}
	
	// Get a quantity in case we need to use in calculating price
	var current_quantity = 1;
	if( jQuery( document.getElementById( 'ec_quantity_' + product_id + '_' + rand_id ) ).length > 0 ){
		current_quantity = jQuery( document.getElementById( 'ec_quantity_' + product_id + '_' + rand_id ) ).val( );
	}
	if( jQuery( '.ec_details_grid_row > input' ).length > 0 ){
		current_quantity = 0;
		jQuery( '.ec_details_grid_row > input' ).each( function( ){
			current_quantity = current_quantity + Number( jQuery( this ).val( ) );
		} );
	}
	var tier_quantities = window['tier_quantities_' + product_id + '_' + rand_id];
	var tier_prices = window['tier_prices_' + product_id + '_' + rand_id];
	for( var i=0; i<tier_quantities.length; i++ ){
		if( tier_quantities[i] <= current_quantity ) {
			base_price = Number( tier_prices[i] );
		}
	}
	var vat_added = jQuery( document.getElementById( 'vat_added_' + product_id + '_' + rand_id ) ).val();
	var vat_rate_multiplier = jQuery( document.getElementById( 'vat_rate_multiplier_' + product_id + '_' + rand_id ) ).val();
	var override_price = -1;
	var price_multiplier = 1;
	// Checkbox Price Adjustments
	var checkbox_adj = 0;
	var checkbox_add = 0;
	// Combox Price Adjustments
	var combo_adj = 0;
	var combo_add = 0;
	// Date Price Adjustments
	var date_adj = 0;
	var date_add = 0;
	// File Price Adjustments
	var file_adj = 0;
	var file_add = 0;
	// Swatch Price Adjustments
	var swatch_adj = 0;
	var swatch_add = 0;
	// Grid Price Adjustments
	var grid_quantity = 0;
	var grid_adj = 0;
	var grid_add = 0;
	var grid_override = 0;
	// Radio Price Adjustments
	var radio_adj = 0;
	var radio_add = 0;
	// Text Price Adjustments
	var text_adj = 0;
	var text_add = 0;
	// Number Price Adjustments
	var number_adj = 0;
	var number_add = 0;
	// Textarea Price Adjustments
	var textarea_adj = 0;
	var textarea_add = 0;
	// Dimensions Price Adjustments
	var has_sq_footage = false;
	var sq_footage = 1;
	jQuery( '.ec_details_grid_row:visible > input' ).each( function() {
		if( jQuery( this ).val() > 0 ) {
			grid_quantity += Number( jQuery( this ).val( ) );
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				grid_adj += ( ( Number( jQuery( this ).attr( 'data-optionitem-price' ) ) ) * ( Number( jQuery( this ).val( ) ) ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				grid_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				grid_override += ( Number( jQuery( this ).attr( 'data-optionitem-price-override' ) ) - base_price ) * ( ( Number( jQuery( this ).val( ) ) ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
		}
	} );
	jQuery( '.ec_details_checkbox_row:visible > input:checked' ).each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
			if( grid_quantity )
				checkbox_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
			else
				checkbox_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
			checkbox_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
			override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
			price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_combo:visible > .ec_details_option_data > select option:selected' ).each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
			if( grid_quantity )
				combo_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
			else
				combo_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
			combo_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
			override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
			price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_date:visible > .ec_details_option_data > input' ).each( function( ){
		if( jQuery( this ).val( ) != "" ){
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				if( grid_quantity )
					date_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
				else
					date_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				date_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_file:visible > .ec_details_option_data > input' ).each( function( ){
		if( jQuery( this ).val( ) != "" ){
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				if( grid_quantity )
					file_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
				else
					file_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				file_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
		}
	} );
	jQuery( '.ec_details_swatch.ec_advanced.ec_selected:visible' ).each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
			if( grid_quantity )
				swatch_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
			else
				swatch_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
			swatch_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
			override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
			price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
		}
	} );
	jQuery( '.ec_details_radio_row:visible > input:checked' ).each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
			if( grid_quantity )
				radio_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
			else
				radio_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
			radio_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
			override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
		}
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
			price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_text:visible > .ec_details_option_data > input' ).each( function( ){
		if( jQuery( this ).val( ) != "" ){
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				if( grid_quantity )
					text_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
				else
					text_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				text_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-per-character' ) > 0 ){
				var num_characters = Number(  jQuery( this ).val( ).replace( / /g, '' ).length );
				var price_per_char = Number( jQuery( this ).attr( 'data-optionitem-price-per-character' ) );
				text_adj = text_adj + ( num_characters * price_per_char );
			}
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_number:visible > .ec_details_option_data > input' ).each( function( ){
		if( jQuery( this ).val( ) != "" && jQuery( this ).val( ) != "0" ){
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				if( grid_quantity > 0 )
					number_adj += Number( jQuery( this ).val( ) ) * Number( jQuery( this ).attr( 'data-optionitem-price' ) ) * grid_quantity;
				else
					number_adj += Number( jQuery( this ).val( ) ) * Number( jQuery( this ).attr( 'data-optionitem-price' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				number_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).val( ) ) * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
		}
	} );
	jQuery( '.ec_details_option_row.ec_option_type_textarea:visible > .ec_details_option_data > textarea' ).each( function( ){
		if( jQuery( this ).val( ) != "" ){
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price' ) != 0 ){
				textarea_adj += Number( jQuery( this ).attr( 'data-optionitem-price' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-onetime' ) != 0 ){
				textarea_add += Number( jQuery( this ).attr( 'data-optionitem-price-onetime' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-override' ) >= 0 ){
				override_price = Number( jQuery( this ).attr( 'data-optionitem-price-override' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-multiplier' ) > 0 ){
				price_multiplier = price_multiplier * Number( jQuery( this ).attr( 'data-optionitem-price-multiplier' ) );
			}
			if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-optionitem-price-per-character' ) > 0 ){
				var num_characters = Number(  jQuery( this ).val( ).replace( / /g, '' ).length );
				var price_per_char = Number( jQuery( this ).attr( 'data-optionitem-price-per-character' ) );
				textarea_adj = textarea_adj + ( num_characters * price_per_char );
			}
		}
	} );
	jQuery( '.ec_dimensions_width' ).each( function( ){
		if( Number( jQuery( this ).attr( 'data-product-id' ) ) == Number( product_id ) && Number( jQuery( this ).attr( 'data-rand-id' ) == rand_id ) ){
			has_sq_footage = true;
			var product_option_id = jQuery( this ).attr( 'data-product-option-id' );
			var width = jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_width' ) ).val( );
			var sub_width = 0;
			var is_metric = ( '1' == jQuery( this ).attr( 'data-is-metric' ) ) ? 1 : 0;
			if( jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_sub_width' ) ).length ) {
				var sub_width = jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_sub_width' ) ).val( );
			}
			var height = jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_height' ) ).val( );
			var sub_height = 0;
			if( jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_sub_height' ) ).length ) {
				var sub_height = jQuery( document.getElementById( 'ec_option_adv_' + product_option_id + '_' + product_id + '_' + rand_id + '_sub_height' ) ).val( );
			}
			if( '' != width && '' != height ) {
				sq_footage = ec_details_get_sq_footage( is_metric, width, sub_width, height, sub_height ) * Number( jQuery( document.getElementById( 'ec_quantity_' + product_id + '_' + rand_id ) ).val( ) );
			}
		}
	} );
	if( grid_quantity > 0 ) {
		base_price = base_price * grid_quantity;
	}
	var new_price = ( base_price + Number( checkbox_adj ) + Number( combo_adj ) + Number( date_adj ) + Number( file_adj ) + Number( swatch_adj ) + Number( grid_adj ) + Number( grid_override ) + Number( radio_adj ) + Number( text_adj ) + Number( number_adj ) + Number( textarea_adj ) );
	var new_price_sqft = new_price * sq_footage;
	var override_price_final = override_price + Number( checkbox_adj ) + Number( combo_adj ) + Number( date_adj ) + Number( file_adj ) + Number( swatch_adj ) + Number( grid_adj ) + Number( grid_override ) + Number( radio_adj ) + Number( text_adj ) + Number( number_adj ) + Number( textarea_adj );
	var override_price_sqft = override_price_final * sq_footage;
	var order_price = Number( checkbox_add ) + Number( combo_add ) + Number( date_add ) + Number( file_add ) + Number( swatch_add ) + Number( grid_add ) + Number( radio_add ) + Number( text_add ) + Number( number_add ) + Number( textarea_add );
	if( override_price > -1 ){
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, override_price_sqft ) );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){ jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) ); } );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){ 
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) );
		} );
		if ( '1' == vat_added ) {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ) {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function() {
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final ) );
			} );
		}
	} else {
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, new_price_sqft ) );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
		} );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
		} );
		if ( '1' == vat_added ) {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
		}
	}
	if ( price_multiplier != 1 && override_price > -1 ) {
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, override_price_sqft * Number( price_multiplier ) ) );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
		} );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
		} );
		if ( '1' == vat_added ) {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price_final * Number( price_multiplier ) ) );
			} );
		}
	} else if( price_multiplier != 1 ){
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
		} );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
		} );
		if( '1' == vat_added ){
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * Number( price_multiplier ) ) );
			} );
		}
	}
	if( order_price != 0 ){
		jQuery( '.ec_details_added_price_' + product_id + '_' + rand_id ).show( );
		jQuery( document.getElementById( 'ec_added_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, order_price ) );
	}else{
		jQuery( '.ec_details_added_price_' + product_id + '_' + rand_id ).hide( );
	}
}

function ec_details_base_adjust_price( product_id, rand_id ) {
	if( jQuery( document.getElementById( 'ec_default_price_' + product_id + '_' + rand_id ) ).length == 0 ) {
		return;
	}
	var base_price = Number( jQuery( document.getElementById( 'ec_default_price_' + product_id + '_' + rand_id ) ).val() );
	if( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).length ) {
		base_price = Number( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).val() );
	}
	var current_quantity = jQuery( document.getElementById( 'ec_quantity_' + product_id + '_' + rand_id ) ).val( );
	var tier_quantities = window['tier_quantities_' + product_id + '_' + rand_id];
	var tier_prices = window['tier_prices_' + product_id + '_' + rand_id];
	if ( tier_quantities ) {
		for ( var i=0; i<tier_quantities.length; i++ ) {
			if ( tier_quantities[i] <= current_quantity ) {
				base_price = Number( tier_prices[i] );
			}
		}
	}
	var vat_added = jQuery( document.getElementById( 'vat_added_' + product_id + '_' + rand_id ) ).val();
	var vat_rate_multiplier = jQuery( document.getElementById( 'vat_rate_multiplier_' + product_id + '_' + rand_id ) ).val();
	var option1_price_adj = 0;
	var option2_price_adj = 0;
	var option3_price_adj = 0;
	var option4_price_adj = 0;
	var option5_price_adj = 0;
	var option1_price_add = 0;
	var option2_price_add = 0;
	var option3_price_add = 0;
	var option4_price_add = 0;
	var option5_price_add = 0;
	var option1_price_override = -1;
	var option2_price_override = -1;
	var option3_price_override = -1;
	var option4_price_override = -1;
	var option5_price_override = -1;
	var total_basic_options = jQuery( '.ec_details_options_basic .ec_details_option_row' ).length;
	var basic_options_selected = 0;
	var basic_options_string = '';
	if ( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ) { 
		basic_options_selected++;
		basic_options_string += jQuery( '#ec_option1_' + product_id + '_' + rand_id ).val();
	} else if ( jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).length && jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val() != '0') {
		basic_options_selected++;
		basic_options_string += jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).val();
	} else {
		basic_options_string += '0';
	}
	if ( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ) { 
		basic_options_selected++;
		basic_options_string += jQuery( '#ec_option2_' + product_id + '_' + rand_id ).val();
	} else if ( jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).length && jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val() != '0') {
		basic_options_selected++;
		basic_options_string += jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val();
	} else {
		basic_options_string += '0';
	}
	if ( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).length ) { 
		basic_options_selected++;
		basic_options_string += jQuery( '#ec_option3_' + product_id + '_' + rand_id ).val();
	} else if ( jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).length && jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val() != '0') {
		basic_options_selected++;
		basic_options_string += jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val();
	} else {
		basic_options_string += '0';
	}
	if ( jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).length ) { 
		basic_options_selected++;
		basic_options_string += jQuery( '#ec_option4_' + product_id + '_' + rand_id ).val();
	} else if ( jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).length && jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val() != '0') {
		basic_options_selected++;
		basic_options_string += jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val();
	} else {
		basic_options_string += '0';
	}
	if ( jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).length ) { 
		basic_options_selected++;
		basic_options_string += jQuery( '#ec_option5_' + product_id + '_' + rand_id ).val();
	} else if ( jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).length && jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val() != '0') {
		basic_options_selected++;
		basic_options_string += jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val();
	} else {
		basic_options_string += '0';
	}
	if ( total_basic_options == basic_options_selected && window['varitation_data_' + product_id + '_' + rand_id][basic_options_string] && window['varitation_data_' + product_id + '_' + rand_id][basic_options_string].sku != '' ) {
		jQuery( '.ec_details_model_number_sku_' + product_id + '_' + rand_id ).html( window['varitation_data_' + product_id + '_' + rand_id][basic_options_string].sku );
	} else {
		jQuery( '.ec_details_model_number_sku_' + product_id + '_' + rand_id ).html( jQuery( document.getElementById( 'ec_default_sku_' + product_id + '_' + rand_id ) ).val() );
	}
	if ( total_basic_options == basic_options_selected && window['varitation_data_' + product_id + '_' + rand_id][basic_options_string] && window['varitation_data_' + product_id + '_' + rand_id][basic_options_string].price != -1 ) {
		base_price = window['varitation_data_' + product_id + '_' + rand_id][basic_options_string].price;
		Number( jQuery( document.getElementById( 'ec_base_price_' + product_id + '_' + rand_id ) ).html( base_price ) );
	} else {
		// Option 1 Price Adjustment
		if( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
			option1_price_adj = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price' );
			option1_price_add = jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-onetime' );
			option1_price_override = Number( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-override' ) );
			option1_price_multiplier = Number( jQuery( '.ec_details_swatches > li.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}else if( jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).length ){
			option1_price_adj = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price' );
			option1_price_add = jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-onetime' );
			option1_price_override = Number( jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-override' ) );
			option1_price_multiplier = Number( jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}
		// Option 2 Price Adjustment
		if( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
			option2_price_adj = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price' );
			option2_price_add = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-onetime' );
			option2_price_override = Number( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-override' ) );
			option2_price_multiplier = Number( jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}else if( jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).length ){
			option2_price_adj = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price' );
			option2_price_add = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-onetime' );
			option2_price_override = Number( jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-override' ) );
			option2_price_multiplier = Number( jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}
		// Option 3 Price Adjustment
		if( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
			option3_price_adj = jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price' );
			option3_price_add = jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-onetime' );
			option3_price_override = Number( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-override' ) );
			option3_price_multiplier = Number( jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}else if( jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).length ){
			option3_price_adj = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price' );
			option3_price_add = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-onetime' );
			option3_price_override = Number( jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-override' ) );
			option3_price_multiplier = Number( jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}
		// Option 4 Price Adjustment
		if( jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
			option4_price_adj = jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price' );
			option4_price_add = jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-onetime' );
			option4_price_override = Number( jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-override' ) );
			option4_price_multiplier = Number( jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}else if( jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).length ){
			option4_price_adj = jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price' );
			option4_price_add = jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-onetime' );
			option4_price_override = Number( jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-override' ) );
			option4_price_multiplier = Number( jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}
		// Option 5 Price Adjustment
		if( jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).length ){
			option5_price_adj = jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price' );
			option5_price_add = jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-onetime' );
			option5_price_override = Number( jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-override' ) );
			option5_price_multiplier = Number( jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}else if( jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).length ){
			option5_price_adj = jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price' );
			option5_price_add = jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-onetime' );
			option5_price_override = Number( jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-override' ) );
			option5_price_multiplier = Number( jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option:selected' ).attr( 'data-optionitem-price-multiplier' ) );
		}
	}
	var num_decimals = jQuery( document.getElementById( 'num_decimals_' + product_id + '_' + rand_id ) ).val();
	var decimal_symbol = jQuery( document.getElementById( 'decimal_symbol_' + product_id + '_' + rand_id ) ).val();
	var grouping_symbol = jQuery( document.getElementById( 'grouping_symbol_' + product_id + '_' + rand_id ) ).val();
	var new_price = base_price + Number( option1_price_adj ) + Number( option2_price_adj ) + Number( option3_price_adj ) + Number( option4_price_adj ) + Number( option5_price_adj );
	var order_price = Number( option1_price_add ) + Number( option2_price_add ) + Number( option3_price_add ) + Number( option4_price_add ) + Number( option5_price_add );
	var override_price = -1;
	if( option1_price_override > -1 ) {
		override_price = option1_price_override;
	} else if( option2_price_override > -1 ) {
		override_price = option2_price_override;
	} else if( option3_price_override > -1 ) {
		override_price = option3_price_override;
	} else if( option4_price_override > -1 ) {
		override_price = option4_price_override;
	} else if( option5_price_override > -1 ) {
		override_price = option5_price_override;
	}
	if( override_price > -1 ){
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) );
		jQuery( document.getElementById( 'ec_base_option_price_' + product_id + '_' + rand_id ) ).val( override_price );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){ jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) ); } );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){ jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) ); } );
		if ( '1' == vat_added ) {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, override_price ) );
			} );
		}
	}else{
		jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
		jQuery( document.getElementById( 'ec_base_option_price_' + product_id + '_' + rand_id ) ).val( new_price );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
		} );
		jQuery( '.ec_details_price.ec_details_single_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
			jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
		} );
		if ( '1' == vat_added ) {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price * vat_rate_multiplier ) );
			} );
		} else {
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_sale_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
			jQuery( '.ec_details_price.ec_details_no_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price / vat_rate_multiplier ) );
			} );
			jQuery( '.ec_details_price.ec_details_vat_price > .ec_product_price_' + product_id + '_' + rand_id ).each( function( ){
				jQuery( this ).html( ec_details_format_money_v2( product_id, rand_id, new_price ) );
			} );
		}
	}
	if( order_price != 0 ){
		jQuery( '.ec_details_added_price_' + product_id + '_' + rand_id ).show( );
		jQuery( document.getElementById( 'ec_added_price_' + product_id + '_' + rand_id ) ).html( ec_details_format_money_v2( product_id, rand_id, order_price ) );
	}else{
		jQuery( '.ec_details_added_price_' + product_id + '_' + rand_id ).hide( );
	}
	ec_details_advanced_adjust_price( product_id, rand_id );
}
function ec_details_advanced_conditional_logic( product_id, rand_id ) {
	var ec_advanced_logic_rules = window['ec_advanced_logic_rules_' + product_id + '_' + rand_id];
	for( var i=0; i<ec_advanced_logic_rules.length; i++ ){
		if( ec_advanced_logic_rules[i].rules.length > 0 ){
			if( ec_advanced_logic_rules[i].show_field ){
				jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hide( );
			}else{
				jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).show( );
			}
			if( ec_advanced_logic_rules[i].and_rules == 'AND' ){
				var rules_match = true;
				for( var j=0; j<ec_advanced_logic_rules[i].rules.length; j++ ){
					jQuery( '.ec_details_option_row' ).each( function( ){
						if( jQuery( this ).attr( 'data-product-option-id' ) == ec_advanced_logic_rules[i].rules[j].option_id ){
							var val = 0;
							var selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
							if ( jQuery( this ).hasClass( 'ec_option_type_checkbox' ) ) {
								if ( jQuery( this ).attr( 'data-product-option-id' ) == ec_advanced_logic_rules[i].rules[j].option_id ) {
									jQuery( this ).find( 'input' ).each( function( ){ 
										if ( jQuery( this ).attr( 'data-optionitem-id' ) == ec_advanced_logic_rules[i].rules[j].optionitem_id && jQuery( this ).is( ':checked' ) ) {
											val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
										}
									} );
								}

							}else if( jQuery( this ).hasClass( 'ec_option_type_radio' ) ){
								jQuery( this ).find( 'input' ).each( function( ){ 
									if( jQuery( this ).val( ) == ec_advanced_logic_rules[i].rules[j].optionitem_id ){
										if( jQuery( this ).is( ':checked' ) ){
											val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
										}
									}
								} );

							}else if( jQuery( this ).hasClass( 'ec_option_type_swatch' ) ){
								jQuery( this ).find( '.ec_details_swatch.ec_selected' ).each( function( ){
									if( jQuery( this ).attr( 'data-optionitem-id' ) == ec_advanced_logic_rules[i].rules[j].optionitem_id ){
										val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
									}
								} );

							}else if( jQuery( this ).hasClass( 'ec_option_type_combo' ) ){
								var val = jQuery( this ).find( 'select' ).val( );

							}else if( jQuery( this ).hasClass( 'ec_option_type_textarea' ) ){
								var val = jQuery( this ).find( 'textarea' ).val( );
								selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_value;

							}else{
								var val = jQuery( this ).find( 'input' ).val( );
								selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_value;
							}
							if( ec_advanced_logic_rules[i].rules[j].operator == '=' ){
								if( val != selected_val ){
									rules_match = false;
								}
							}else if( ec_advanced_logic_rules[i].rules[j].operator == '!=' ){
								if( val == selected_val ){
									rules_match = false;
								}
							}
						}
					} );
				}
			}else{
				var rules_match = false;
				for( var j=0; j<ec_advanced_logic_rules[i].rules.length; j++ ){
					jQuery( '.ec_details_option_row' ).each( function( ){
						if( jQuery( this ).attr( 'data-product-option-id' ) == ec_advanced_logic_rules[i].rules[j].option_id ){
							var val = 0;
							var selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
							if ( jQuery( this ).hasClass( 'ec_option_type_checkbox' ) ) {
								if ( jQuery( this ).attr( 'data-product-option-id' ) == ec_advanced_logic_rules[i].rules[j].option_id ) {
									jQuery( this ).find( 'input' ).each( function( ){ 
										if ( jQuery( this ).attr( 'data-optionitem-id' ) == ec_advanced_logic_rules[i].rules[j].optionitem_id && jQuery( this ).is( ':checked' ) ) {
											val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
										}
									} );
								}

							}else if( jQuery( this ).hasClass( 'ec_option_type_radio' ) ){
								jQuery( this ).find( 'input' ).each( function( ){ 
									if( jQuery( this ).val( ) == ec_advanced_logic_rules[i].rules[j].optionitem_id ){
										if( jQuery( this ).is( ':checked' ) ){
											val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
										}
									}
								} );

							}else if( jQuery( this ).hasClass( 'ec_option_type_swatch' ) ){
								jQuery( this ).find( '.ec_details_swatch.ec_selected' ).each( function( ){
									if( jQuery( this ).attr( 'data-optionitem-id' ) == ec_advanced_logic_rules[i].rules[j].optionitem_id ){
										val = ec_advanced_logic_rules[i].rules[j].optionitem_id;
									}
								} );

							}else if( jQuery( this ).hasClass( 'ec_option_type_combo' ) ){
								var val = jQuery( this ).find( 'select' ).val( );

							}else if( jQuery( this ).hasClass( 'ec_option_type_textarea' ) ){
								var val = jQuery( this ).find( 'textarea' ).val( );
								selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_value;

							}else{
								var val = jQuery( this ).find( 'input' ).val( );
								selected_val = ec_advanced_logic_rules[i].rules[j].optionitem_value;
							}
							if( ec_advanced_logic_rules[i].rules[j].operator == '=' ){
								if( val == selected_val ){
									rules_match = true;
								}
							}else if( ec_advanced_logic_rules[i].rules[j].operator == '!=' ){
								if( val != selected_val ){
									rules_match = true;
								}
							}
						}
					} );
				}
			}
			if( rules_match ){
				if( ec_advanced_logic_rules[i].show_field ){
					jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).show( );
				}else{
					jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hide( );
					if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_checkbox' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).attr( 'checked', false ); } );
					}else if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_radio' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).prop( 'checked', false ); } );
					}else if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_swatch' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( '.ec_details_swatch' ).each( function( ){ jQuery( this ).removeClass( 'ec_selected' ); } );
						jQuery( document.getElementById( 'ec_option_adv_' + ec_advanced_logic_rules[i].id + '_' + product_id + '_' + rand_id ) ).val( '0' );
					}else{
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).val( '' ); } );
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'textarea' ).each( function( ){ jQuery( this ).val( '' ); } );
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'select' ).each( function( ){ jQuery( this ).val( 0 ); } );
					}
				}
			}else{
				if( ec_advanced_logic_rules[i].show_field ){
					if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_checkbox' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).attr( 'checked', false ); } );
					}else if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_radio' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).prop( 'checked', false ); } );
					}else if( jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).hasClass( 'ec_option_type_swatch' ) ){
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( '.ec_details_swatch' ).each( function( ){ jQuery( this ).removeClass( 'ec_selected' ); } );
						jQuery( document.getElementById( 'ec_option_adv_' + ec_advanced_logic_rules[i].id + '_' + product_id + '_' + rand_id ) ).val( '0' );
					}else{
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'input' ).each( function( ){ jQuery( this ).val( '' ); } );
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'textarea' ).each( function( ){ jQuery( this ).val( '' ); } );
						jQuery( '.ec_details_option_row[data-product-option-id="' + ec_advanced_logic_rules[i].id + '"]' ).find( 'select' ).each( function( ){ jQuery( this ).val( 0 ); } );
					}
				}
			}
		}
	}
}

function ec_option1_image_change( product_id, rand_id, optionitem_id_1, quantity ){
	var use_optionitem_images = jQuery( document.getElementById( 'use_optionitem_images_' + product_id + '_' + rand_id ) ).val();
	if( '1' == use_optionitem_images ){
		if( optionitem_id_1 != 0 ){
			jQuery( '.ec_details_thumbnails_' + product_id + '_' + rand_id ).addClass( 'ec_inactive' );
			jQuery( '.ec_details_large_popup_thumbnails_' + product_id + '_' + rand_id ).addClass( 'ec_inactive' );
			jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).find( '.ec_details_thumbnail' ).first( ).trigger( 'click' );
			if( !jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).hasClass( 'ec_no_thumbnails' ) ){
				jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).removeClass( 'ec_inactive' );
				if( jQuery( document.getElementById( 'ec_details_large_popup_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ) ){
					jQuery( document.getElementById( 'ec_details_large_popup_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).removeClass( 'ec_inactive' );
				}
			}
		}
	}
}
function ec_option1_init_combo( product_id, rand_id ){
	if (
		! jQuery( 'select.ec_option2_' + product_id + '_' + rand_id ).length &&
		! jQuery( 'li.ec_option2_' + product_id + '_' + rand_id ).length
	) {
		jQuery( 'select.ec_option1_' + product_id + '_' + rand_id + ' > option' ).each( function() {
			var optionitem_id_1 = jQuery( this ).attr( 'value' );
			if (
				window['varitation_data_' + product_id + '_' + rand_id ] &&
				window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000']
			) {
				var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
				if (
					window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].enabled &&
					(
						'0' == use_optionitem_quantity_tracking ||
						! window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].tracking || 
						window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].quantity > 0
					)
				) {
					jQuery( this ).show().attr( 'disabled', false );
				} else {
					jQuery( this ).hide().attr( 'disabled', true );
				}
			} else {
				jQuery( this ).show().attr( 'disabled', false );
			}
		} );
	}
}
function ec_option1_init_swatches( product_id, rand_id, swatch, optionitem_id_1 ){
	if (
		! jQuery( 'select.ec_option2_' + product_id + '_' + rand_id ).length &&
		! jQuery( 'li.ec_option2_' + product_id + '_' + rand_id ).length
	) {
		if ( 
			window['varitation_data_' + product_id + '_' + rand_id ] && 
			window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000']
		) {
			var use_optionitem_quantity_tracking = jQuery( document.getElementById( 'use_optionitem_quantity_tracking_' + product_id + '_' + rand_id ) ).val();
			if (
				window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].enabled &&
				(
					'0' == use_optionitem_quantity_tracking ||
					! window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].tracking ||
					window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].quantity > 0
				)
			) {
				swatch.addClass( 'ec_active' ).removeClass( 'ec_selected' );
			} else if (
				! window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'].enabled
			) {
				swatch.hide().removeClass( 'ec_active' ).removeClass( 'ec_selected' );
			} else {
				swatch.removeClass( 'ec_active' ).removeClass( 'ec_selected' );
			}
		} else {
			swatch.addClass( 'ec_active' ).removeClass( 'ec_selected' );
		}
	}
}
function ec_option1_updated( product_id, rand_id, optionitem_id_1 ) {
	if ( jQuery( 'select.ec_option2_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'select.ec_option2_' + product_id + '_' + rand_id + ' > option' ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'value' ) + '000'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'value' ) + '000'].enabled ) {
					jQuery( this ).show().attr( 'disabled', false );
				} else {
					jQuery( this ).hide().attr( 'disabled', true );
				}
			} else {
				jQuery( this ).show().attr( 'disabled', false );
			}
		} );
	} else if ( jQuery( 'li.ec_option2_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'li.ec_option2_' + product_id + '_' + rand_id ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'data-optionitem-id' ) + '000'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'data-optionitem-id' ) + '000'].enabled ) {
					jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
				} else {
					jQuery( this ).removeClass( 'ec_active' ).removeClass( 'ec_selected' );
				}
			} else {
				jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
			}
		} );
	}
}
function ec_option1_selected( product_id, rand_id, optionitem_id_1, quantity ){
	var variation = false;
	if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'] ) {
		variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + '0000'];
	}
	if( variation ) {
		quantity = ( variation.tracking ) ? variation.quantity : 'inf';
		quantity = ( variation.enabled ) ? quantity : 0;
	}

	var use_optionitem_images = jQuery( document.getElementById( 'use_optionitem_images_' + product_id + '_' + rand_id ) ).val();
	if( '1' == use_optionitem_images ){
		jQuery( '.ec_details_thumbnails_' + product_id + '_' + rand_id ).addClass( 'ec_inactive' );
		jQuery( '.ec_details_large_popup_thumbnails_' + product_id + '_' + rand_id ).addClass( 'ec_inactive' );
		jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).find( '.ec_details_thumbnail' ).first( ).trigger( 'click' );
		if( !jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).hasClass( 'ec_no_thumbnails' ) ){
			jQuery( document.getElementById( 'ec_details_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).removeClass( 'ec_inactive' );
			jQuery( document.getElementById( 'ec_details_large_popup_thumbnails_' + optionitem_id_1 + '_' + product_id + '_' + rand_id ) ).removeClass( 'ec_inactive' );
		}
	}
	jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
	if ( 'inf' != quantity ) {
		jQuery( '.ec_details_stock_total' ).show();
	} else {
		jQuery( '.ec_details_stock_total' ).hide();
	}
	jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).attr( 'max', quantity );
	if( Number( jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( ) ) > Number( quantity ) ){
		 jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( quantity );
	}
	jQuery( document.getElementById( 'ec_option_loading_2_' + product_id + '_' + rand_id ) ).show( );
	var next_options = jQuery( '.ec_details_swatches > li.ec_option2_' + product_id + '_' + rand_id );
	if( next_options.length ){
		next_options.each( function( ){
			jQuery( this ).show().removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_2_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if( json_data.length > i && json_data[i].optionitem_id == jQuery( this ).attr( 'data-optionitem-id' ) ){
					if( json_data[i].optionitem_id == jQuery( this ).attr( 'data-optionitem-id' ) ){
						jQuery( this ).attr( 'data-optionitem-quantity', json_data[i].quantity );
						if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
							jQuery( this ).show().addClass( 'ec_active' );
						}
						i++;
						var subitem_variation = false;
						if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'data-optionitem-id' ) + '000'] ) {
							subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).attr( 'data-optionitem-id' ) + '000'];
						}
						if( subitem_variation ) {
							if ( ! subitem_variation.enabled ) {
								jQuery( this ).hide();
							}
						}
					}
				}
			} );
		} } );
	}else{
		next_options = jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id + ' option' );
		next_options.each( function( ){
			jQuery( this ).removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).prop( 'disabled', false ).removeClass( 'ec_inactive' );
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_2_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if ( i >= json_data.length ) {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );

				} else if( jQuery( this ).val( ) == json_data[i].optionitem_id ) {
					jQuery( this ).attr( 'data-optionitem-quantity', ( ( '1' == json_data[i].tracking ) ? json_data[i].quantity : 'inf' ) );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().attr( 'disabled', false );
					} else {
						jQuery( this ).hide().attr( 'disabled', true );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).val() + '000'] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + jQuery( this ).val() + '000'];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide().prop( 'disabled', true );
						}
					}

				}else{
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );
				}
			} );
		} } );
	}
}
function ec_option2_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2 ) {
	if ( jQuery( 'select.ec_option3_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'select.ec_option3_' + product_id + '_' + rand_id + ' > option' ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'value' ) + '00'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'value' ) + '00'].enabled ) {
					jQuery( this ).show().attr( 'disabled', false );
				} else {
					jQuery( this ).hide().attr( 'disabled', true );
				}
			} else {
				jQuery( this ).show().attr( 'disabled', false );
			}
		} );
	} else if ( jQuery( 'li.ec_option3_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'li.ec_option3_' + product_id + '_' + rand_id ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'data-optionitem-id' ) + '00'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'data-optionitem-id' ) + '00'].enabled ) {
					jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
				} else {
					jQuery( this ).removeClass( 'ec_active' ).removeClass( 'ec_selected' );
				}
			} else {
				jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
			}
		} );
	}
}
function option2_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, quantity ){	
	var variation = false;
	if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + '000'] ) {
		variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + '000'];
	}
	if( variation ) {
		quantity = ( variation.tracking ) ? variation.quantity : 'inf';
		quantity = ( variation.enabled ) ? quantity : 0;
	}

	jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
	jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
	if ( 'inf' != quantity ) {
		jQuery( '.ec_details_stock_total' ).show();
	} else {
		jQuery( '.ec_details_stock_total' ).hide();
	}
	jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).attr( 'max', quantity );
	if( Number( jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( ) ) > Number( quantity ) ){
		 jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( quantity );
	}
	var next_options = jQuery( '.ec_details_swatches > li.ec_option3_' + product_id + '_' + rand_id );
	jQuery( document.getElementById( 'ec_option_loading_3_' + product_id + '_' + rand_id ) ).show( );
	if( next_options.length ){
		next_options.each( function( ){
			jQuery( this ).show().removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_3_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if( json_data.length > i && json_data[i].optionitem_id == jQuery( this ).attr( 'data-optionitem-id' ) ){
					jQuery( this ).attr( 'data-optionitem-quantity', json_data[i].quantity );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().addClass( 'ec_active' );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'data-optionitem-id' ) + '00'] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).attr( 'data-optionitem-id' ) + '00'];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide();
						}
					}
				}
			} );
		} } );
	}else{
		next_options = jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id + ' option' );
		next_options.each( function( ){
			jQuery( this ).removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).prop( 'disabled', false ).removeClass( 'ec_inactive' );
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_3_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if ( i >= json_data.length ) {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );

				} else if( jQuery( this ).val( ) == json_data[i].optionitem_id ) {
					jQuery( this ).attr( 'data-optionitem-quantity', ( ( '1' == json_data[i].tracking ) ? json_data[i].quantity : 'inf' ) );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().attr( 'disabled', false );
					} else {
						jQuery( this ).hide().attr( 'disabled', true );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).val() + '00'] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + jQuery( this ).val() + '00'];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide().prop( 'disabled', true );
						}
					}

				} else {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );
				}
			} );
		} } );
	}
}
function ec_option3_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3 ) {
	if ( jQuery( 'select.ec_option4_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'select.ec_option4_' + product_id + '_' + rand_id + ' > option' ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'value' ) + '0'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'value' ) + '0'].enabled ) {
					jQuery( this ).show().attr( 'disabled', false );
				} else {
					jQuery( this ).hide().attr( 'disabled', true );
				}
			} else {
				jQuery( this ).show().attr( 'disabled', false );
			}
		} );
	} else if ( jQuery( 'li.ec_option4_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'li.ec_option4_' + product_id + '_' + rand_id ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'data-optionitem-id' ) + '0'] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'data-optionitem-id' ) + '0'].enabled ) {
					jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
				} else {
					jQuery( this ).removeClass( 'ec_active' ).removeClass( 'ec_selected' );
				}
			} else {
				jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
			}
		} );
	}
}
function option3_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, quantity ){	
	var variation = false;
	if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + '00'] ) {
		variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + '00'];
	}
	if( variation ) {
		quantity = ( variation.tracking ) ? variation.quantity : 'inf';
		quantity = ( variation.enabled ) ? quantity : 0;
	}

	jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
	jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
	if ( 'inf' != quantity ) {
		jQuery( '.ec_details_stock_total' ).show();
	} else {
		jQuery( '.ec_details_stock_total' ).hide();
	}
	jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).attr( 'max', quantity );
	if( Number( jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( ) ) > Number( quantity ) ){
		 jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( quantity );
	}
	var next_options = jQuery( '.ec_details_swatches > li.ec_option4_' + product_id + '_' + rand_id );
	jQuery( document.getElementById( 'ec_option_loading_4_' + product_id + '_' + rand_id ) ).show( );
	if( next_options.length ){
		next_options.each( function( ){
			jQuery( this ).show().removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			optionitem_id_3: optionitem_id_3,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_4_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if( json_data.length > i && json_data[i].optionitem_id == jQuery( this ).attr( 'data-optionitem-id' ) ){
					jQuery( this ).attr( 'data-optionitem-quantity', json_data[i].quantity );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().addClass( 'ec_active' );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'data-optionitem-id' ) + '0'] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).attr( 'data-optionitem-id' ) + '0'];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide();
						}
					}
				}
			} );
		} } );
	}else{
		next_options = jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id + ' option' );
		next_options.each( function( ){
			jQuery( this ).removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			optionitem_id_3: optionitem_id_3,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).prop( 'disabled', false ).removeClass( 'ec_inactive' );
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_4_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if ( i >= json_data.length ) {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );

				}else if( jQuery( this ).val( ) == json_data[i].optionitem_id ){
					jQuery( this ).attr( 'data-optionitem-quantity', ( ( '1' == json_data[i].tracking ) ? json_data[i].quantity : 'inf' ) );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().attr( 'disabled', false );
					} else {
						jQuery( this ).hide().attr( 'disabled', true );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).val() + '0'] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + jQuery( this ).val() + '0'];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide().prop( 'disabled', true );
						}
					}

				}else{
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );
				}
			} );
		} } );
	}
}
function ec_option4_updated( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4 ) {
	if ( jQuery( 'select.ec_option5_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'select.ec_option5_' + product_id + '_' + rand_id + ' > option' ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'value' )] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'value' )].enabled ) {
					jQuery( this ).show().attr( 'disabled', false );
				} else {
					jQuery( this ).hide().attr( 'disabled', true );
				}
			} else {
				jQuery( this ).show().attr( 'disabled', false );
			}
		} );
	} else if ( jQuery( 'li.ec_option5_' + product_id + '_' + rand_id ).length ) {
		jQuery ( 'li.ec_option5_' + product_id + '_' + rand_id ).each( function() {
			if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'data-optionitem-id' )] ) {
				if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'data-optionitem-id' )].enabled ) {
					jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
				} else {
					jQuery( this ).removeClass( 'ec_active' ).removeClass( 'ec_selected' );
				}
			} else {
				jQuery( this ).addClass( 'ec_active' ).removeClass( 'ec_selected' );
			}
		} );
	}
}
function option4_selected( product_id, rand_id, optionitem_id_1, optionitem_id_2, optionitem_id_3, optionitem_id_4, quantity ){	
	var variation = false;
	if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + '0'] ) {
		variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + '0'];
	}
	if( variation ) {
		quantity = ( variation.tracking ) ? variation.quantity : 'inf';
		quantity = ( variation.enabled ) ? quantity : 0;
	}

	jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id ).removeClass( 'ec_selected' ).removeClass( 'ec_active' );
	jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).val( '0' ).prop( 'disabled', true ).addClass( 'ec_inactive' );
	jQuery( '.ec_option4.ec_details_option_row_error' ).hide( );
	jQuery( document.getElementById( 'ec_details_stock_quantity_' + product_id + '_' + rand_id ) ).html( quantity );
	if ( 'inf' != quantity ) {
		jQuery( '.ec_details_stock_total' ).show();
	} else {
		jQuery( '.ec_details_stock_total' ).hide();
	}
	jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).attr( 'max', quantity );
	if( Number( jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( ) ) > Number( quantity ) ){
		 jQuery( document.getElementById('ec_quantity_' + product_id + '_' + rand_id ) ).val( quantity );
	}
	var next_options = jQuery( '.ec_details_swatches > li.ec_option5_' + product_id + '_' + rand_id );
	jQuery( document.getElementById( 'ec_option_loading_5_' + product_id + '_' + rand_id ) ).show( );
	if( next_options.length ){
		next_options.each( function( ){
			jQuery( this ).show().removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			optionitem_id_3: optionitem_id_3,
			optionitem_id_4: optionitem_id_4,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_5_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if( json_data.length > i && json_data[i].optionitem_id == jQuery( this ).attr( 'data-optionitem-id' ) ){
					jQuery( this ).attr( 'data-optionitem-quantity', json_data[i].quantity );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().addClass( 'ec_active' );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'data-optionitem-id' ) ] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).attr( 'data-optionitem-id' ) ];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide();
						}
					}
				}
			} );
		} } );
	}else{
		next_options = jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id + ' option' );
		next_options.each( function( ){
			jQuery( this ).removeClass( 'ec_active' );
		} );
		var data = {
			action: 'ec_ajax_get_optionitem_quantities',
			optionitem_id_1: optionitem_id_1,
			optionitem_id_2: optionitem_id_2,
			optionitem_id_3: optionitem_id_3,
			optionitem_id_4: optionitem_id_4,
			product_id: product_id,
			nonce: jQuery( document.getElementById( 'product_details_nonce_' + product_id + '_' + rand_id ) ).val()
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).prop( 'disabled', false ).removeClass( 'ec_inactive' );
			var json_data = JSON.parse( data );
			jQuery( document.getElementById( 'ec_option_loading_5_' + product_id + '_' + rand_id ) ).hide( );
			var i=0;
			next_options.each( function( ){
				if ( i >= json_data.length ) {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );

				} else if( jQuery( this ).val( ) == json_data[i].optionitem_id ) {
					jQuery( this ).attr( 'data-optionitem-quantity', ( ( '1' == json_data[i].tracking ) ? json_data[i].quantity : 'inf' ) );
					if( json_data[i].quantity > 0 || '0' == json_data[i].tracking ){
						jQuery( this ).show().attr( 'disabled', false );
					} else {
						jQuery( this ).hide().attr( 'disabled', true );
					}
					i++;
					var subitem_variation = false;
					if ( window['varitation_data_' + product_id + '_' + rand_id ] && window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).val() ] ) {
						subitem_variation = window['varitation_data_' + product_id + '_' + rand_id][optionitem_id_1 + optionitem_id_2 + optionitem_id_3 + optionitem_id_4 + jQuery( this ).val() ];
					}
					if( subitem_variation ) {
						if ( ! subitem_variation.enabled ) {
							jQuery( this ).hide().prop( 'disabled', true );
						}
					}

				} else {
					jQuery( this ).attr( 'data-optionitem-quantity', 0 ).hide().attr( 'disabled', true );
				}
			} );
		} } );
	}
}

function ec_details_submit_inquiry( product_id, rand_id ){
	var errors = 0;
	if( document.getElementById( 'ec_inquiry_name_' + product_id + '_' + rand_id ) ){
		if( jQuery( document.getElementById( 'ec_inquiry_name_' + product_id + '_' + rand_id ) ).val( ) == "" || !ec_validate_email( jQuery( document.getElementById( 'ec_inquiry_email_' + product_id + '_' + rand_id ) ).val( ) ) || jQuery( document.getElementById( 'ec_inquiry_message_' + product_id + '_' + rand_id ) ).val( ) == "" ){
			jQuery( document.getElementById( 'ec_details_inquiry_error_' + product_id + '_' + rand_id ) ).show( );
			errors++;
		}else{
			jQuery( document.getElementById( 'ec_details_inquiry_error_' + product_id + '_' + rand_id ) ).hide( );
		}
	}
	if( jQuery( document.getElementById( 'ec_grecaptcha_response_inquiry' ) ).length ){
		var recaptcha_response = jQuery( document.getElementById( 'ec_grecaptcha_response_inquiry' ) ).val( );
		if( !recaptcha_response.length ){
			jQuery( '#ec_product_details_inquiry_recaptcha > div' ).css( 'border', '1px solid red' );
			errors++;
		}else{
			jQuery( '#ec_product_details_inquiry_recaptcha > div' ).css( 'border', 'none' );
		}
	}
	if( ! ec_details_add_to_cart( product_id, rand_id ) ){
		errors++;
	}
	if( errors > 0 ) {
		return false;
	} else {
		jQuery( document.getElementById( 'ec_inquiry_loader_' + product_id + '_' + rand_id ) ).show( );
		jQuery( document.getElementById( 'ec_inquiry_loader_bg_' + product_id + '_' + rand_id ) ).show( );
		return true;
	}
}

function ec_details_add_to_cart( product_id, rand_id ) {
	var errors = 0; 
	// Basic Option 1 Error Check
	if( jQuery( '.ec_details_swatch.ec_option1_' + product_id + '_' + rand_id ).length && !jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' ) ){ 
		jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else if( jQuery( '.ec_details_combo.ec_option1_' + product_id + '_' + rand_id ).length && ( jQuery( '.ec_details_combo.ec_option1.ec_option1_' + product_id + '_' + rand_id ).val( ) == null || jQuery( '.ec_details_combo.ec_option1.ec_option1_' + product_id + '_' + rand_id ).val( ) == '0' ) ){ 
		jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else{ 
		jQuery( '.ec_option1_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( );
	}
	// Basic Option 2 Error Check
	if( jQuery( '.ec_details_swatch.ec_option2_' + product_id + '_' + rand_id ).length && !jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' ) ){ 
		jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else if( jQuery( '.ec_details_combo.ec_option2_' + product_id + '_' + rand_id ).length && ( jQuery( '.ec_details_combo.ec_option2.ec_option2_' + product_id + '_' + rand_id ).val( ) == null || jQuery( '.ec_details_combo.ec_option2.ec_option2_' + product_id + '_' + rand_id ).val( ) == '0' ) ){ 
		jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else{ 
		jQuery( '.ec_option2_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( ); 
	}
	// Basic Option 3 Error Check
	if( jQuery( '.ec_details_swatch.ec_option3_' + product_id + '_' + rand_id ).length && !jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' ) ){ 
		jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( );
		errors++; 
	}else if( jQuery( '.ec_details_combo.ec_option3_' + product_id + '_' + rand_id ).length && ( jQuery( '.ec_details_combo.ec_option3.ec_option3_' + product_id + '_' + rand_id ).val( ) == null || jQuery( '.ec_details_combo.ec_option3.ec_option3_' + product_id + '_' + rand_id ).val( ) == '0' ) ){ 
		jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else{ 
		jQuery( '.ec_option3_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( ); 
	}
	// Basic Option 4 Error Check
	if( jQuery( '.ec_details_swatch.ec_option4_' + product_id + '_' + rand_id ).length && !jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' ) ){ 
		jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else if( jQuery( '.ec_details_combo.ec_option4_' + product_id + '_' + rand_id ).length && ( jQuery( '.ec_details_combo.ec_option4.ec_option4_' + product_id + '_' + rand_id ).val( ) == null || jQuery( '.ec_details_combo.ec_option4.ec_option4_' + product_id + '_' + rand_id ).val( ) == '0' ) ){ 
		jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else{ 
		jQuery( '.ec_option4_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( ); 
	}
	// Basic Option 5 Error Check
	if( jQuery( '.ec_details_swatch.ec_option5_' + product_id + '_' + rand_id ).length && !jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_selected' ).attr( 'data-optionitem-id' ) ){ 
		jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else if( jQuery( '.ec_details_combo.ec_option5_' + product_id + '_' + rand_id ).length && ( jQuery( '.ec_details_combo.ec_option5.ec_option5_' + product_id + '_' + rand_id ).val( ) == null || jQuery( '.ec_details_combo.ec_option5.ec_option5_' + product_id + '_' + rand_id ).val( ) == '0' ) ){ 
		jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).show( ); 
		errors++; 
	}else{ 
		jQuery( '.ec_option5_' + product_id + '_' + rand_id + '.ec_details_option_row_error' ).hide( ); 
	}
	// --------Advanced Option Checks---------- //
	// Select Box Check
	var advanced_select_rows = jQuery( '.ec_details_option_row.ec_option_type_combo:visible' );
	advanced_select_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) == '0' ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}
		}
	} );
	// Check Box Check
	var advanced_checkbox_rows = jQuery( '.ec_details_option_row.ec_option_type_checkbox:visible' );
	advanced_checkbox_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			var selected_checkboxes = jQuery( "input.ec_option_adv_" + jQuery( this ).attr( 'data-product-option-id' ) + ":checked" );
			if( selected_checkboxes.length ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Date Box Check
	var advanced_date_rows = jQuery( '.ec_details_option_row.ec_option_type_date:visible' );
	advanced_date_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) == "" ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}
		}
	} );
	// File Upload Check
	var advanced_file_rows = jQuery( '.ec_details_option_row.ec_option_type_file:visible' );
	advanced_file_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Swatch Check
	var advanced_swatch_rows = jQuery( '.ec_details_option_row.ec_option_type_swatch:visible' );
	advanced_swatch_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			var advanced_selected_swatches = jQuery( ".ec_details_swatch.ec_option_adv_" + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id + ".ec_selected" );
			if( advanced_selected_swatches.length ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Radio Button Check
	var advanced_radio_rows = jQuery( '.ec_details_option_row.ec_option_type_radio:visible' );
	advanced_radio_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			var selected_radios = jQuery( "input[name='ec_option_adv_" + jQuery( this ).attr( 'data-product-option-id' ) + "']:checked" );
			if( selected_radios.length ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Text Box Check
	var advanced_text_rows = jQuery( '.ec_details_option_row.ec_option_type_text:visible' );
	advanced_text_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) != "" ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Text Area Check
	var advanced_textarea_rows = jQuery( '.ec_details_option_row.ec_option_type_textarea:visible' );
	advanced_textarea_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) != "" && jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) != 0 ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Number Box Check
	var advanced_text_rows = jQuery( '.ec_details_option_row.ec_option_type_number:visible' );
	advanced_text_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).val( ) != "" ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Quantity Grid Check
	var advanced_grid_rows = jQuery( '.ec_details_option_row.ec_option_type_grid:visible' );
	advanced_grid_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required
			var grid_items = jQuery( this ).find( ".ec_details_option_data > .ec_details_grid_row > input" );
			var total_grid_quantity = 0;
			grid_items.each( 
				function( ){ 
					total_grid_quantity += jQuery( this ).val( ); 
				} 
			);
			if( total_grid_quantity > 0 ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}
		}
	} );
	// Dimensions Type 1 Check
	var advanced_dimensions_rows = jQuery( '.ec_details_option_row.ec_option_type_dimensions1:visible' );
	advanced_dimensions_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			// Test Width + Height
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id + '_width' ) ).val( ) != "" && jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id + '_height' ) ).val( ) != "" ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}

		}
	} );
	// Dimensions Type 2 Check
	var advanced_dimensions_rows = jQuery( '.ec_details_option_row.ec_option_type_dimensions2:visible' );
	advanced_dimensions_rows.each( function( ){
		if( jQuery( this ).attr( 'data-product-id' ) == product_id && jQuery( this ).attr( 'data-option-required' ) == '1' ){ // Option is Required	
			// Test Width + Height
			if( jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id + '_width' ) ).val( ) != "" && jQuery( document.getElementById( 'ec_option_adv_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id + '_height' ) ).val( ) != "" ){
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).hide( );
			}else{
				jQuery( document.getElementById( 'ec_details_adv_option_row_error_' + jQuery( this ).attr( 'data-product-option-id' ) + '_' + product_id + '_' + rand_id ) ).show( );
				errors++;
			}

		}
	} );
	// -------END Advanced Option Checks------- //
	// -------START GIFT CARD CHECK ----------- //
	var gift_card_errors = 0;
	if( document.getElementById( 'ec_giftcard_to_name_' + product_id + '_' + rand_id ) && jQuery( document.getElementById( 'ec_giftcard_to_name_' + product_id + '_' + rand_id ) ).val( ) == "" ){
		errors++;
		gift_card_errors++;
	}
	if( document.getElementById( 'ec_giftcard_to_email_' + product_id + '_' + rand_id ) && jQuery( document.getElementById( 'ec_giftcard_to_email_' + product_id + '_' + rand_id ) ).val( ) == "" ){
		errors++;
		gift_card_errors++;
	}
	if( document.getElementById( 'ec_giftcard_from_name_' + product_id + '_' + rand_id ) && jQuery( document.getElementById( 'ec_giftcard_from_name_' + product_id + '_' + rand_id ) ).val( ) == "" ){
		errors++;
		gift_card_errors++;
	}
	if( document.getElementById( 'ec_giftcard_message_' + product_id + '_' + rand_id ) && jQuery( document.getElementById( 'ec_giftcard_message_' + product_id + '_' + rand_id ) ).val( ) == "" ){
		errors++;
		gift_card_errors++;
	}
	if( gift_card_errors ){
		jQuery( document.getElementById( 'ec_details_giftcard_error_' + product_id + '_' + rand_id ) ).show( );
	}else{
		jQuery( document.getElementById( 'ec_details_giftcard_error_' + product_id + '_' + rand_id ) ).hide( );
	}
	// -------END GIFT CARD CHECK   ----------- //
	// -------START DONATION CHECK  ----------- //
	if( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ){
		var price = jQuery( document.getElementById( 'price_' + product_id + '_' + rand_id ) ).val();
		if( isNaN( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).val( ) ) || Number( jQuery( document.getElementById( 'ec_donation_amount_' + product_id + '_' + rand_id ) ).val( ) ) < price ){
			jQuery( document.getElementById( 'ec_details_donation_error_' + product_id + '_' + rand_id ) ).show( );
			errors++;
		}else{
			jQuery( document.getElementById( 'ec_details_donation_error_' + product_id + '_' + rand_id ) ).hide( );
		}
	}
	// -------END DONATION CHECK    ----------- //
	// -------START INQUIRY CHECK  ----------- //
	if( document.getElementById( 'ec_inquiry_name' ) ){
		if( jQuery( document.getElementById( 'ec_inquiry_name' ) ).val( ) == "" || jQuery( document.getElementById( 'ec_inquiry_email' ) ).val( ) == "" || jQuery( document.getElementById( 'ec_inquiry_message' ) ).val( ) == "" ){
			jQuery( document.getElementById( 'ec_details_inquiry_error' ) ).show( );
			errors++;
		}else{
			jQuery( document.getElementById( 'ec_details_inquiry_error' ) ).hide( );
		}
	}
	// -------END INQUIRY CHECK    ----------- //
	// Stock Quantity Check
	var entered_quantity = Number( jQuery( document.getElementById( 'ec_quantity_' + product_id + '_' + rand_id ) ).val( ) );
	var allowed_quantity = 9999999999999;
	if( jQuery( document.getElementById( 'ec_details_stock_quantity' ) ).length ){
		allowed_quantity = Number( jQuery( document.getElementById( 'ec_details_stock_quantity' ) ).html( ) );
	}
	// Backorder Check
	if( allowed_quantity <= 0 && jQuery( document.getElementById( 'ec_back_order_info_' + product_id + '_' + rand_id ) ).length ){
		allowed_quantity = 1000000;
	}
	// Check Stock Quantity
	if( entered_quantity > allowed_quantity ){
		jQuery( document.getElementById( 'ec_addtocart_quantity_exceeded_error_' + product_id + '_' + rand_id ) ).show( );
		errors++;
	}else{
		jQuery( document.getElementById( 'ec_addtocart_quantity_exceeded_error_' + product_id + '_' + rand_id ) ).hide( );
	}
	// Minimum Quantity Check
	var min_quantity = jQuery( document.getElementById( 'min_quantity_' + product_id + '_' + rand_id ) ).val();
	if( entered_quantity < min_quantity ){
		jQuery( document.getElementById( 'ec_addtocart_quantity_minimum_error_' + product_id + '_' + rand_id ) ).show( );
		errors++;
	}else{
		jQuery( document.getElementById( 'ec_addtocart_quantity_minimum_error_' + product_id + '_' + rand_id ) ).hide( );
	}
	// Maximum Quantity Check
	var max_quantity = jQuery( document.getElementById( 'max_quantity_' + product_id + '_' + rand_id ) ).val();
	if( max_quantity > 0 && entered_quantity > max_quantity ){
		jQuery( document.getElementById( 'ec_addtocart_quantity_maximum_error_' + product_id + '_' + rand_id ) ).show( );
		errors++;
	}else{
		jQuery( document.getElementById( 'ec_addtocart_quantity_maximum_error_' + product_id + '_' + rand_id ) ).hide( );
	}
	errors = window['wp_easycart_add_to_cart_js_validation_end_' + product_id + '_' + rand_id]( errors );
	if( errors > 0 ) {
		return false;
	} else {
		if ( typeof gtag !== "undefined" ) {
			ec_ga4_add_to_cart( jQuery( '.ec_details_model_number_sku_' + product_id + '_' + rand_id ).html(), jQuery( '.ec_details_title_' + product_id + '_' + rand_id ).html(), entered_quantity, jQuery( document.getElementById( 'ec_final_price_' + product_id + '_' + rand_id ) ).html().replace( /[^\d\.\,]/g, '' ), jQuery( document.getElementById( 'currency_code_' + product_id + '_' + rand_id ) ).val() );
		}
		return true;
	}
}

function ec_submit_product_review( product_id, rand_id, nonce ){
	var review_title = jQuery( document.getElementById( 'ec_review_title_' + product_id + '_' + rand_id ) ).val( );
	var review_score = 0;
	for( var i=1; i<=5; i++ ){
		if( jQuery( document.getElementById( 'ec_details_review_star' + i + '_' + product_id + '_' + rand_id ) ).hasClass( 'ec_product_details_star_on' ) ){
			review_score++;
		}
	}
	var review_message = jQuery( document.getElementById( 'ec_review_message_' + product_id + '_' + rand_id ) ).val( );
	if( review_title != "" && review_score != 0 && review_message != "" ){
		// Submit a filled out review
		jQuery( document.getElementById( 'ec_details_customer_review_loader_' + product_id + '_' + rand_id ) ).show( );
		jQuery( document.getElementById( 'ec_details_review_error_' + product_id + '_' + rand_id ) ).hide( );
		var data = {
			action: 'ec_ajax_insert_customer_review',
			review_title: review_title,
			review_score: review_score,
			review_message: review_message,
			product_id: product_id,
			nonce: nonce
		};
		jQuery.ajax( { url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function( data ){ 
			jQuery( document.getElementById( 'ec_details_customer_review_loader_' + product_id + '_' + rand_id ) ).hide( ); 
			jQuery( document.getElementById( 'ec_details_submit_review_button_row_' + product_id + '_' + rand_id ) ).hide( );
			jQuery( document.getElementById( 'ec_details_review_submitted_button_row_' + product_id + '_' + rand_id ) ).show( );
			jQuery( document.getElementById( 'ec_details_customer_review_success_' + product_id + '_' + rand_id ) ).show( ).delay( 1500 ).fadeOut( 'slow' ); 
		} } );
	}else{
		// Something is missing, display error.
		jQuery( document.getElementById( 'ec_details_review_error_' + product_id + '_' + rand_id ) ).show( );
	}
}

function ec_details_get_sq_footage( is_metric, width, sub_width, height, sub_height ){
	var sub_width_decimal = ec_details_get_sub_dimension_decimal( sub_width );
	var sub_height_decimal = ec_details_get_sub_dimension_decimal( sub_height );
	if( '1' == is_metric ) {
		width = Number( Number( width ) + sub_width_decimal ) / 1000;
		height = Number( Number( height ) + sub_height_decimal ) / 1000;
	} else {
		width = Number( Number( width ) + sub_width_decimal ) / 12;
		height = Number( Number( height ) + sub_height_decimal ) / 12;
	}
	return width * height;
}

function ec_details_get_sub_dimension_decimal( sub_dimension ) {
	if( sub_dimension == "0" ){
		return 0;
	}else if( sub_dimension == "1/16" ){
		return .0625;
	}else if( sub_dimension == "1/8" ){
		return .1250;
	}else if( sub_dimension == "3/16" ){
		return .1875;
	}else if( sub_dimension == "1/4" ){
		return .2500;
	}else if( sub_dimension == "5/16" ){
		return .3125;
	}else if( sub_dimension == "3/8" ){
		return .3750;
	}else if( sub_dimension == "7/16" ){
		return .4375;
	}else if( sub_dimension == "1/2" ){
		return .5000;
	}else if( sub_dimension == "9/16" ){
		return .5625;
	}else if( sub_dimension == "5/8" ){
		return .6250;
	}else if( sub_dimension == "11/16" ){
		return .6875;
	}else if( sub_dimension == "3/4" ){
		return .7500;
	}else if( sub_dimension == "13/16" ){
		return .8125;
	}else if( sub_dimension == "7/8" ){
		return .8750;
	}else if( sub_dimension == "15/16" ){
		return .9375;
	}else{
		return 0;
	}
}

function ec_details_format_money_v2( product_id, rand_id, price ) {
	var currency_symbol = jQuery( document.getElementById( 'currency_symbol_' + product_id + '_' + rand_id ) ).val();
	var num_decimals = Number( jQuery( document.getElementById( 'num_decimals_' + product_id + '_' + rand_id ) ).val() );
	var decimal_symbol = jQuery( document.getElementById( 'decimal_symbol_' + product_id + '_' + rand_id ) ).val();
	var grouping_symbol = jQuery( document.getElementById( 'grouping_symbol_' + product_id + '_' + rand_id ) ).val();
	var conversion_rate = Number( jQuery( document.getElementById( 'conversion_rate_' + product_id + '_' + rand_id ) ).val() );
	var symbol_location = Number( jQuery( document.getElementById( 'symbol_location_' + product_id + '_' + rand_id ) ).val() );
	var currency_code = jQuery( document.getElementById( 'currency_code_' + product_id + '_' + rand_id ) ).val();
	var show_currency_code = Number( jQuery( document.getElementById( 'show_currency_code_' + product_id + '_' + rand_id ) ).val() );
	price = ec_pricing_round( price * Number( conversion_rate ), num_decimals );
	var n = price,
		num_decimals = isNaN(num_decimals = Math.abs(num_decimals)) ? 2 : num_decimals,
		decimal_symbol = decimal_symbol == undefined ? "." : decimal_symbol,
		grouping_symbol = grouping_symbol == undefined ? "," : grouping_symbol,
		i = parseInt(n = price.toFixed( num_decimals ) ) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
	var formatted = (j ? i.substr(0, j) + grouping_symbol : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + grouping_symbol) + (num_decimals ? decimal_symbol + Math.abs(n - i).toFixed(num_decimals).slice(2) : "");
	if( symbol_location ){
		formatted = currency_symbol + formatted;
	}else{
		formatted = formatted + currency_symbol;
	}
	if( show_currency_code == '1' ){
		formatted = currency_code + ' ' +  formatted;
	}
	return formatted;
}
function ec_pricing_round( number, places ) {
	var multiplier = Math.pow(10, places+2); // get two extra digits
	var fixed = Math.floor(number*multiplier); // convert to integer
	fixed += 50; // round down on anything less than x.xxx50
	fixed = Math.floor(fixed/100); // chop off last 2 digits
	return fixed/Math.pow(10, places);
}
function ec_admin_save_product_details_options( ){
	jQuery( "#ec_admin_page_updated_loader" ).show( );
	jQuery( "#ec_admin_loader_bg" ).show( );
	var data = {
		action: 'ec_ajax_save_product_details_options',
		ec_option_details_main_color: jQuery( document.getElementById( 'ec_option_details_main_color' ) ).val( ),
		ec_option_details_second_color: jQuery( document.getElementById( 'ec_option_details_second_color' ) ).val( ),
		ec_option_details_columns_desktop: jQuery( document.getElementById( 'ec_option_details_columns_desktop' ) ).val( ),
		ec_option_details_columns_laptop: jQuery( document.getElementById( 'ec_option_details_columns_laptop' ) ).val( ),
		ec_option_details_columns_tablet_wide: jQuery( document.getElementById( 'ec_option_details_columns_tablet_wide' ) ).val( ),
		ec_option_details_columns_tablet: jQuery( document.getElementById( 'ec_option_details_columns_tablet' ) ).val( ),
		ec_option_details_columns_smartphone: jQuery( document.getElementById( 'ec_option_details_columns_smartphone' ) ).val( ),
		ec_option_use_dark_bg: jQuery( document.getElementById( 'ec_option_use_dark_bg' ) ).val( ),
		nonce: jQuery( '#product_details_save_options_nonce' ).val()
	}
	jQuery.ajax({url: wpeasycart_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		jQuery( document.getElementById( 'ec_admin_page_updated_loader' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_page_updated' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_loader_bg' ) ).fadeOut( 'slow' );
		location.reload( );
	} } );
	jQuery( document.getElementById( 'ec_page_editor' ) ).animate( { left:'-290px' }, {queue:false, duration:220} ).removeClass( 'ec_display_editor_true' ).addClass( 'ec_display_editor_false' );
}
function ec_admin_show_description_editor( product_id, rand_id ) {
	jQuery( '.ec_details_description_content_' + product_id + '_' + rand_id ).hide( );
	jQuery( '.ec_details_description_editor_' + product_id + '_' + rand_id ).show( );
}
function ec_admin_save_description_editor( model_number, product_id, rand_id ) {
	var new_html = ( jQuery( document.getElementById( 'desc_' + model_number ) ).is( ':visible' ) ) ? jQuery( document.getElementById( 'desc_' + model_number ) ).val() : tinymce.get( 'desc_' + model_number ).getContent();
	jQuery( '.ec_details_description_editor_' + product_id + '_' + rand_id ).hide( );
	jQuery( '.ec_details_description_content_' + product_id + '_' + rand_id ).html( new_html ).show( );
	var data = {
		action: 'ec_ajax_ec_update_product_description',
		description: new_html,
		product_id: product_id,
		nonce: jQuery( document.getElementById( 'product_details_update_product_description_nonce_' + product_id + '_' + rand_id ) ).val()
	};
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data
	} );
}
function ec_admin_show_specifications_editor( product_id, rand_id ) {
	jQuery( '.ec_details_specifications_content_' + product_id + '_' + rand_id ).hide( );
	jQuery( '.ec_details_specifications_editor_' + product_id + '_' + rand_id ).show( );
}
function ec_admin_save_specifications_editor( model_number, product_id, rand_id ){
	var new_html = ( jQuery( document.getElementById( 'specs_' + model_number ) ).is( ':visible' ) ) ? jQuery( document.getElementById( 'specs_' + model_number ) ).val() : tinymce.get( 'specs_' + model_number ).getContent();
	jQuery( '.ec_details_specifications_editor_' + product_id + '_' + rand_id ).hide( );
	jQuery( '.ec_details_specifications_content_' + product_id + '_' + rand_id ).html( new_html ).show( );
	var data = {
		action: 'ec_ajax_ec_update_product_specifications',
		specifications: new_html,
		product_id: product_id,
		nonce: jQuery( document.getElementById( 'product_details_update_product_specs_nonce_' + product_id + '_' + rand_id ) ).val()
	};
	jQuery.ajax( {
		url: wpeasycart_ajax_object.ajax_url,
		type: 'post',
		data: data
	} );
}

if ( '' != wpeasycart_ajax_object.ga4_id || '' != wpeasycart_ajax_object.ga4_conv_id ) {
	var newScript = document.createElement( 'script' );
	newScript.type = 'text/javascript';
	newScript.setAttribute( 'async', 'true' );
	if ( '' != wpeasycart_ajax_object.ga4_id ) {
		newScript.setAttribute( 'src', 'https://www.googletagmanager.com/gtag/js?id=' + wpeasycart_ajax_object.ga4_id );
	} else {
		newScript.setAttribute( 'src', 'https://www.googletagmanager.com/gtag/js?id=' + wpeasycart_ajax_object.ga4_conv_id );
	}
	jQuery( 'head' ).append( newScript );
	window.dataLayer = window.dataLayer || [];
	function gtag(){ dataLayer.push( arguments ); }
	gtag( 'js', new Date() );
	if ( '' != wpeasycart_ajax_object.ga4_id ) {
		gtag( 'config', wpeasycart_ajax_object.ga4_id ); // , {'debug_mode': false} );
	}
	if ( '' != wpeasycart_ajax_object.ga4_conv_id ) {
		gtag( 'config', wpeasycart_ajax_object.ga4_conv_id ); // , {'debug_mode': false} );
	}
}

function ec_ga4_remove_from_cart( model_number, title, quantity, price, currency, manufacturer_name, is_tag_manager = false ) {
	if ( is_tag_manager ) {
		dataLayer.push( { ecommerce: null } );  // Clear the previous ecommerce object.
		dataLayer.push( {
			event: 'remove_from_cart',
			ecommerce: {
				currency: currency,
				value: Number( price ) * Number( quantity ),
				items: [ {
					item_id: model_number,
					item_name: title,
					index: 0,
					price: price,
					item_brand: manufacturer_name,
					quantity: quantity
				} ]
			}
		} );
	} else {
		gtag( 'event', 'remove_from_cart', {
			currency: currency,
			value: Number( price ) * Number( quantity ),
			items: [ {
				item_id: model_number,
				item_name: title,
				index: 0,
				price: price,
				item_brand: manufacturer_name,
				quantity: quantity
			} ]
		} );
	}
}

function ec_ga4_add_to_cart( model_number, title, quantity, price, currency, manufacturer_name, is_tag_manager = false ) {
	if ( is_tag_manager ) {
		dataLayer.push( { ecommerce: null } );  // Clear the previous ecommerce object.
		dataLayer.push( {
			event: 'add_to_cart',
			ecommerce: {
				currency: currency,
				value: Number( price ) * Number( quantity ),
				items: [ {
					item_id: model_number,
					item_name: title,
					index: 0,
					price: price,
					item_brand: manufacturer_name,
					quantity: quantity
				} ]
			}
		} );
	} else {
		gtag( 'event', 'add_to_cart', {
			currency: currency,
			value: Number( price ) * Number( quantity ),
			items: [ {
				item_id: model_number,
				item_name: title,
				index: 0,
				price: price,
				item_brand: manufacturer_name,
				quantity: quantity
			} ]
		} );
	}
}

function wp_easycart_cart_checkout_click() {
	jQuery( '.ec_cart_button_checkout' ).addClass( 'wp-easycart-running' );
}

function wp_easycart_cart_shipping_next() {
	jQuery( '.ec_cart_button_shipping_next' ).parent().addClass( 'wp-easycart-running' );
}

function wpeasycart_mobile_summary() {
	if ( jQuery( '.ec_cart_mobile_summary_content' ).is( ':visible' ) ) {
		jQuery( '.ec_cart_mobile_summary_content' ).hide();
		jQuery( '.dashicons-arrow-down-alt2' ).show();
		jQuery( '.dashicons-arrow-up-alt2' ).hide();
	} else {
		jQuery( '.ec_cart_mobile_summary_content' ).show();
		jQuery( '.dashicons-arrow-down-alt2' ).hide();
		jQuery( '.dashicons-arrow-up-alt2' ).show();
	}
}
