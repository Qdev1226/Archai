// JavaScript Document
jQuery(document).ready(function($) {
	if($(".ec_admin_details_panel").length > 0) {
		jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
		jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		
		if(document.getElementById( 'is_less_than' ).checked == true) {
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).show();
		}else if(document.getElementById( 'is_greater_than' ).checked == true) {
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).show();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		} else if(document.getElementById( 'is_greater_than' ).checked == false && document.getElementById( 'is_less_than' ).checked == false) {
			document.getElementById( 'is_between' ).checked = true; 
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).show();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).show();
		}
	}
});

function ec_admin_pricepoint_type_change( checkbox ){
	console.log(checkbox);
	document.getElementById( 'low_point' ).value = '';
	document.getElementById( 'high_point' ).value = '';
	if(checkbox == 'is_less_than') {
		if (document.getElementById( checkbox ).checked == true) {
			document.getElementById( 'is_less_than' ).checked = true; 
			document.getElementById( 'is_between' ).checked = false; 
			document.getElementById( 'is_greater_than' ).checked = false; 
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).show();
		} else {
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		}
	} else if (checkbox == 'is_between') {
		
		if (document.getElementById( checkbox ).checked == true) {
			document.getElementById( 'is_less_than' ).checked = false; 
			document.getElementById( 'is_between' ).checked = true; 
			document.getElementById( 'is_greater_than' ).checked = false; 
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).show();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).show();
		} else {
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		}
		
	} else if (checkbox == 'is_greater_than') {
		if (document.getElementById( checkbox ).checked == true) {
			document.getElementById( 'is_less_than' ).checked = false; 
			document.getElementById( 'is_between' ).checked = false; 
			document.getElementById( 'is_greater_than' ).checked = true; 
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).show();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		} else {
			jQuery( document.getElementById( 'ec_admin_row_low_point' ) ).hide();
			jQuery( document.getElementById( 'ec_admin_row_high_point' ) ).hide();
		}
		
	}
}