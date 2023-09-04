jQuery(document).ready( function() {

	// Re-ordering option items
	jQuery( 'table#ec_optionitem_table tbody' ).sortable( );

	//execute this for the option set page (add new or update)
	if(document.getElementsByName( 'ec_admin_form_action')[0].value == 'add-new-option' || document.getElementsByName( 'ec_admin_form_action')[0].value == 'update-option' ) {
		if(document.getElementById( 'option_type').value == 'basic-combo' || document.getElementById( 'option_type').value == 'basic-swatch') {
			jQuery(document.getElementById('ec_admin_row_option_error_text')).hide();
			jQuery(document.getElementById('ec_admin_row_option_required')).hide();

		} else {
			jQuery(document.getElementById('ec_admin_row_option_error_text')).show();
			jQuery(document.getElementById('ec_admin_row_option_required')).show();
		}
	}

	//execute this for the option item page (add new)
	if( jQuery( document.getElementById( 'option_type' ) ).val( ) != 'basic-combo' && jQuery( document.getElementById( 'option_type' ) ).val( ) != 'basic-swatch' && ( document.getElementsByName( 'ec_admin_form_action')[0].value == 'add-new-optionitem' || document.getElementsByName( 'ec_admin_form_action')[0].value == 'update-optionitem' ) ) {

		//price
		jQuery(document.getElementById('ec_admin_row_optionitem_price')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).hide();

		if(document.getElementById( 'ec_optionitem_price').value == '0'){
			jQuery(document.getElementById('ec_admin_row_optionitem_price')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).hide();

		} else if(document.getElementById( 'ec_optionitem_price').value == 'basic_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'one_time_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'override_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'multiplier_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'per_character_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).show();
		}

		//weight
		jQuery(document.getElementById('ec_admin_row_optionitem_weight')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).hide();

		if(document.getElementById( 'ec_optionitem_weight').value == '0'){
			jQuery(document.getElementById('ec_admin_row_optionitem_weight')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).hide();

		} else if(document.getElementById( 'ec_optionitem_weight').value == 'basic_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'one_time_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'override_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'multiplier_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).show();
		}

		//execute this for the option item page (update)
		if(document.getElementsByName( 'ec_admin_form_action')[0].value == 'update-optionitem' ) {
			//price
			if(document.getElementById('optionitem_price').value != 0.00) {
				document.getElementById( 'ec_optionitem_price').value = 'basic_price';
				jQuery(document.getElementById('ec_admin_row_optionitem_price')).show();
			}
			if(document.getElementById('optionitem_price_onetime').value != 0.00) {
				document.getElementById( 'ec_optionitem_price').value = 'one_time_price';
				jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).show();
			} 

			if(document.getElementById('optionitem_price_override').value != -1.00 ) {
				document.getElementById( 'ec_optionitem_price').value =  'override_price';
				jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).show();
			}
			if(document.getElementById('optionitem_price_multiplier').value != 0.00) {
				document.getElementById( 'ec_optionitem_price').value =  'multiplier_price';
				jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).show();
			}
			if(document.getElementById('optionitem_price_per_character').value != 0.00) {
				document.getElementById( 'ec_optionitem_price').value =  'per_character_price';
				jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).show();
			}

			//weight
			if(document.getElementById('optionitem_weight').value != 0.00) {
				document.getElementById( 'ec_optionitem_weight').value = 'basic_weight';
				jQuery(document.getElementById('ec_admin_row_optionitem_weight')).show();
			}
			if(document.getElementById('optionitem_weight_onetime').value != 0.00) {
				document.getElementById( 'ec_optionitem_weight').value = 'one_time_weight';
				jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).show();
			} 

			if(document.getElementById('optionitem_weight_override').value != -1.00 ) {
				document.getElementById( 'ec_optionitem_weight').value =  'override_weight';
				jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).show();
			}
			if(document.getElementById('optionitem_weight_multiplier').value != 0.00) {
				document.getElementById( 'ec_optionitem_weight').value =  'multiplier_weight';
				jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).show();
			}

		}



	}


});

function setSelectedValue(selectObj, valueToSet) {
	for (var i = 0; i < selectObj.options.length; i++) {
		if (selectObj.options[i].value == valueToSet) {
			selectObj.options[i].selected = true;
			return;
		}
	}
}

function ec_admin_optionitem_price_adjustment() {
		jQuery(document.getElementById('ec_admin_row_optionitem_price')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).hide();

		document.getElementById('optionitem_price').value = 0.00;
		document.getElementById('optionitem_price_onetime').value = 0.00;
		document.getElementById('optionitem_price_override').value = -1.00;
		document.getElementById('optionitem_price_multiplier').value = 0.00;
		document.getElementById('optionitem_price_per_character').value = 0.00;

		if(document.getElementById( 'ec_optionitem_price').value == '0'){
			jQuery(document.getElementById('ec_admin_row_optionitem_price')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).hide();

		} else if(document.getElementById( 'ec_optionitem_price').value == 'basic_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'one_time_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_onetime')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'override_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_override')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'multiplier_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_multiplier')).show();
		} else if(document.getElementById( 'ec_optionitem_price').value == 'per_character_price') {
			jQuery(document.getElementById('ec_admin_row_optionitem_price_per_character')).show();
		}
}

function ec_admin_optionitem_weight_adjustment() {

		jQuery(document.getElementById('ec_admin_row_optionitem_weight')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).hide();
		jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).hide();

		document.getElementById('optionitem_weight').value = 0.00;
		document.getElementById('optionitem_weight_onetime').value = 0.00;
		document.getElementById('optionitem_weight_override').value = -1.00;
		document.getElementById('optionitem_weight_multiplier').value = 0.00;

		if(document.getElementById( 'ec_optionitem_weight').value == '0'){
			jQuery(document.getElementById('ec_admin_row_optionitem_weight')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).hide();
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).hide();

		} else if(document.getElementById( 'ec_optionitem_weight').value == 'basic_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'one_time_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_onetime')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'override_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_override')).show();
		} else if(document.getElementById( 'ec_optionitem_weight').value == 'multiplier_weight') {
			jQuery(document.getElementById('ec_admin_row_optionitem_weight_multiplier')).show();
		}
}

function ec_admin_option_type_change(field) {
	if( document.getElementById( 'option_type').value == 'upgrade_required' ){
		show_pro_required( );
		jQuery( document.getElementById( 'option_type') ).val( '0' );

	}else if( document.getElementById( 'option_type').value == 'basic-combo' || document.getElementById( 'option_type').value == 'basic-swatch' ){
		jQuery( document.getElementById( 'ec_admin_row_option_meta_min' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_max' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_step' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_error_text' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_required' ) ).hide( );

	}else if( document.getElementById( 'option_type').value == 'number' ){
		jQuery( document.getElementById( 'ec_admin_row_option_meta_min' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_max' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_step' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option_error_text' ) ).show( );
		jQuery( document.getElementById( 'ec_admin_row_option_required' ) ).show( );

	}else{
		jQuery( document.getElementById( 'ec_admin_row_option_meta_min' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_max' ) ).hide( );
		jQuery( document.getElementById( 'ec_admin_row_option_meta_step' ) ).hide( );
		jQuery(document.getElementById('ec_admin_row_option_error_text')).show();
		jQuery(document.getElementById('ec_admin_row_option_required')).show();
	}

	if( document.getElementById( 'option_type').value == 'basic-swatch' || document.getElementById( 'option_type').value == 'swatch' ){
		jQuery( document.getElementById( 'ec_admin_row_option_meta_swatch_size' ) ).show( );
	} else {
		jQuery( document.getElementById( 'ec_admin_row_option_meta_swatch_size' ) ).hide( );
	}
}
