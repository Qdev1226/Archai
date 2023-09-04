function wpeasycart_admin_validate_form( ){
	var errors = 0;
	var success = false;
	
	jQuery( '.wpep-required' ).each( function( ){
		
		var validation_type = jQuery( this ).attr( 'wpec-admin-validation-type' );
		
		if( validation_type == "text" ){
			success = wpeasycart_admin_validate_text_field( jQuery( this ) );
			
		}else if( validation_type == "password" ){
			success = wpeasycart_admin_validate_password_field( jQuery( this ) );
			
		}else if( validation_type == "email" ){
			success = wpeasycart_admin_validate_email_field( jQuery( this ) );
		
		}else if( validation_type == "gift-card" ){
			success = wpeasycart_admin_validate_gift_card_field( jQuery( this ) );
			
		}else if( validation_type == "select" ){
			success = wpeasycart_admin_validate_select_field( jQuery( this ) );
			
		}else if( validation_type == "select2" ){
			success = wpeasycart_admin_validate_select2_field( jQuery( this ) );
			
		}else if( validation_type == "number" ){
			success = wpeasycart_admin_validate_number_field( jQuery( this ) );
			
		}else if( validation_type == "date" ){
			success = wpeasycart_admin_validate_date_field( jQuery( this ) );
			
		}else if( validation_type == "checkbox" ){
			success = wpeasycart_admin_validate_checkbox_field( jQuery( this ) );
			
		}else if( validation_type == "popup" ){
			success = wpeasycart_admin_validate_popup( jQuery( this ) );
			
		}else if( validation_type == "model_number" ){
			success = wpeasycart_admin_validate_model_number_field( jQuery( this ) );
			
		}else{ // default to text field
			success = wpeasycart_admin_validate_text_field( jQuery( this ) );
			
		}
		
		if( !success ){
			jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
				jQuery( this ).show( );
			} );
			errors++;
		}else{
			jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
				jQuery( this ).hide( );
			} );
		}
		
	} );
	
	jQuery( '.wpep-validate-only' ).each( function( ){
		
		if( jQuery( this ).val( ) != '' ){
			
			var validation_type = jQuery( this ).attr( 'wpec-admin-validation-type' );
			
			if( validation_type == "text" ){
				success = wpeasycart_admin_validate_text_field( jQuery( this ) );
				
			}else if( validation_type == "password" ){
				success = wpeasycart_admin_validate_password_field( jQuery( this ) );
				
			}else if( validation_type == "email" ){
				success = wpeasycart_admin_validate_email_field( jQuery( this ) );
			
			}else if( validation_type == "gift-card" ){
				success = wpeasycart_admin_validate_gift_card_field( jQuery( this ) );
				
			}else if( validation_type == "select" ){
				success = wpeasycart_admin_validate_select_field( jQuery( this ) );
				
			}else if( validation_type == "select2" ){
				success = wpeasycart_admin_validate_select2_field( jQuery( this ) );
				
			}else if( validation_type == "number" ){
				success = wpeasycart_admin_validate_number_field( jQuery( this ) );
				
			}else if( validation_type == "date" ){
				success = wpeasycart_admin_validate_date_field( jQuery( this ) );
				
			}else if( validation_type == "checkbox" ){
				success = wpeasycart_admin_validate_checkbox_field( jQuery( this ) );
				
			}else if( validation_type == "popup" ){
				success = wpeasycart_admin_validate_popup( jQuery( this ) );
				
			}else if( validation_type == "model_number" ){
				success = wpeasycart_admin_validate_model_number_field( jQuery( this ) );
				
			}else{ // default to text field
				success = wpeasycart_admin_validate_text_field( jQuery( this ) );
				
			}
			
			if( !success ){
				jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
					jQuery( this ).show( );
				} );
				errors++;
			}else{
				jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
					jQuery( this ).hide( );
				} );
			}
		}
	} );
	
	jQuery( '.wpep-wp-editor-required' ).each( function( ){
		
		success = wpeasycart_admin_validate_wp_editor( jQuery( this ) );
		
		if( !success ){
			jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
				jQuery( this ).show( );
			} );
			errors++;
		}else{
			jQuery( this ).parent( ).find( '.ec_validation_error' ).each( function( ){
				jQuery( this ).hide( );
			} );
		}
		
	} );
	if( !errors )
		jQuery( 'input.ec_page_title_button' ).val( wp_easycart_admin_validation_language['processing'] );
	
	return ( !errors );
	
}

function wpeasycart_admin_validate_text_field( field ){
	if( field.val( ) == "" ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_model_number_field( field ){
	if( field.val( ) == "" || !/^[a-zA-Z0-9-\/\_]*$/.test( field.val( ) ) ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		field.parent( ).find( '.ec_validation_error' ).show( );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		field.parent( ).find( '.ec_validation_error' ).hide( );
		return true;
	}
}

function wpeasycart_admin_validate_date_field( field ){
	
	var selected_date = new Date( field.val( ) );
    var max_date = new Date( field.attr( 'max' ) );
	
	if( field.val( ) == "" ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else if( selected_date > max_date ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_password_field( field ){
	if( document.getElementById( 'password' ).value.length < 8 && document.getElementById( 'password' ).value.length > 0 ){
		jQuery( document.getElementById( 'password' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		jQuery( document.getElementById( 'retype_password' ) ).removeClass( 'ec_admin_field_error' );
		jQuery( document.getElementById( 'retype_password_validation' ) ).hide( );
		return false;
	}else if( document.getElementById( 'retype_password' ).value != document.getElementById( 'password' ).value ){
		jQuery( document.getElementById( 'password' ) ).removeClass( 'ec_admin_field_error' );
		jQuery( document.getElementById( 'password_validation' ) ).hide( );
		jQuery( document.getElementById( 'retype_password' ) ).removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		jQuery( document.getElementById( 'password' ) ).removeClass( 'ec_admin_field_error' );
		jQuery( document.getElementById( 'retype_password' ) ).removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_email_field( field ) {
	var valid_email = String( field.val() ).toLowerCase().match( /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/ );
	if ( ! valid_email ) {
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	} else {
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_gift_card_field( field ){
	if( field.val( ).length < 12 ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_select_field( field ){
	if( field.val( ) == "" || field.val( ) == '0' ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_select2_field( field ){
	if( field.val( ) == "" || field.val( ) == '0' ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		field.parent( ).find( '.select2' ).addClass( 'ec_admin_field_error' );
		field.parent( ).find( '.select2-container > .selection > .select2-selection' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		field.parent( ).find( '.select2' ).removeClass( 'ec_admin_field_error' );
		field.parent( ).find( '.select2-container > .selection > .select2-selection' ).removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_number_field( field ){
	if( field.val( ) == "" || isNaN( field.val( ) ) ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_checkbox_field( field ){
	if( !field.is( ':checked' ) ){
		field.removeClass( 'ec_admin_field_error' ).addClass( 'ec_admin_field_error' );
		return false;
	}else{
		field.removeClass( 'ec_admin_field_error' );
		return true;
	}
}

function wpeasycart_admin_validate_popup( field ){
	// not sure yet what we will do.
}