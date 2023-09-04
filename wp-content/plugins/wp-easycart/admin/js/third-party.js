function ec_admin_save_google_analytics( ){
	jQuery( document.getElementById( "ec_admin_google_analytics_loader" ) ).fadeIn( 'fast' );
	
	var ec_option_googleanalyticsid = jQuery( document.getElementById( 'ec_option_googleanalyticsid' ) ).val( );
	
	var data = {
		action: 'ec_admin_ajax_save_google_analytics',
		ec_option_googleanalyticsid: ec_option_googleanalyticsid,
		wp_easycart_nonce: ec_admin_get_value( 'wp_easycart_third_party_settings_nonce', 'text' )
	};
	
	jQuery.ajax({url: wpeasycart_admin_ajax_object.ajax_url, type: 'post', data: data, success: function(data){ 
		ec_admin_hide_loader( 'ec_admin_google_analytics_loader' );
	} } );
	
	return false;
}
