jQuery( document ).ready( function() {
	jQuery( '.wp-easycart-inventory-update' ).on( 'change', function() {
		var this_ele = jQuery( this );
		jQuery( this_ele ).parent().find( '.wp-easycart-admin-icon-close-check' ).hide();
		jQuery( this_ele ).parent( ).find( '.wp_easycart_toggle_saving' ).show( );
		var quantity = Number( jQuery( this_ele ).val() );
		var data = {
			action: 'ec_admin_ajax_update_inventory_item',
			product_id: jQuery( this_ele ).attr( 'data-product-id' ),
			quantity_id: jQuery( this_ele ).attr( 'data-id' ),
			quantity: quantity,
			wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_inventory_nonce', 'text' )
		}
		jQuery.ajax( {
			url: wpeasycart_admin_ajax_object.ajax_url,
			type: 'post',
			data: data,
			success: function( data ) {
				console.log( 'saved' );
				jQuery( this_ele ).parent().find( '.wp_easycart_toggle_saving' ).hide( );
				jQuery( this_ele ).parent().find( '.wp_easycart_toggle_saved' ).fadeIn( ).delay( 500 ).fadeOut( 'slow' );
				jQuery( this_ele ).parent().find( '.wp-easycart-admin-icon-close-check' ).delay( 1000 ).fadeIn( 'fast' );
			}
		} );
		return false;
	} );
} );
