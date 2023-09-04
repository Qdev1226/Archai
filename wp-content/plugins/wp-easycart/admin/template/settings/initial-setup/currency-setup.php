<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<?php wp_easycart_admin( )->preloader->print_preloader( "ec_admin_currency_loader" ); ?>
    
    <div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-admin-generic"></div>
		<span><?php esc_attr_e( 'Currency', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'initial-setup', 'currency' ) ); ?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
     	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'initial-setup', 'currency');?>
	</div>
    
    <div class="ec_admin_settings_input wp_easycart_admin_no_padding">
		
        <?php wp_easycart_admin_verification( )->print_nonce_field( 'wp_easycart_currency_settings_nonce', 'wp-easycart-initial-setup-currency-setup' ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_currency_code', 'ec_admin_save_initial_setup_options', get_option( 'ec_option_show_currency_code' ), __( 'Currency Code: Display', 'wp-easycart' ), __( 'Enabling this shows your 3-digit code in the display, e.g. USD $40.00.', 'wp-easycart' ) ); ?>
        
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_base_currency', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_base_currency' ), __( 'Currency: Code', 'wp-easycart' ), __( 'Enter a valid 3-digit currency code, this is used for the currency switching widget and default currency display.', 'wp-easycart' ), 'USD', 'ec_admin_currency_code_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_currency', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_currency' ), __( 'Currency: Symbol', 'wp-easycart' ), __( 'Enter your currency symbol here, default is $.', 'wp-easycart' ), '$', 'ec_admin_currency_symbol_row', true, false, false ); ?> 
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_currency_symbol_location', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_currency_symbol_location' ), __( 'Currency: Location', 'wp-easycart' ), __( 'This allows you to set the currency symbol to the left or right of the amount.', 'wp-easycart' ), array(
			(object) array(
				'value'	=> 1,
				'label'	=> __( 'Left', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 0,
				'label'	=> __( 'Right', 'wp-easycart' )
			)
		), 'ec_admin_currency_location_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_currency_negative_location', 'ec_admin_save_initial_setup_options', get_option( 'ec_option_currency_negative_location' ), __( 'Currency: Negative Location', 'wp-easycart' ), __( 'This allows you to set the negative symbol before or after the currency symbol.', 'wp-easycart' ), array(
			(object) array(
				'value'	=> 1,
				'label'	=> __( 'Before', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 0,
				'label'	=> __( 'After', 'wp-easycart' )
			)
		), 'ec_admin_currency_location_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_currency_decimal_symbol', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_currency_decimal_symbol' ), __( 'Currency: Fractional Symbol', 'wp-easycart' ), __( 'Enter the fractional symbol divider, USA uses a ".", some other countries use ",".', 'wp-easycart' ), '.', 'ec_admin_currency_decimal_symbol_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_currency_decimal_places', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_currency_decimal_places' ), __( 'Currency: Fractional Length', 'wp-easycart' ), __( 'Enter the fractional length, most are to the hundreds or 2.', 'wp-easycart' ), '2', 'ec_admin_currency_decimal_places_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_currency_thousands_seperator', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_currency_thousands_seperator' ), __( 'Currency: Group Symbol', 'wp-easycart' ), __( 'Enter the group symbol divider, USA uses a ",", some other countries use a ".".', 'wp-easycart' ), '.', 'ec_admin_currency_thousands_seperator_row', true, false, false ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_text( 'ec_option_exchange_rates', 'ec_admin_save_initial_setup_text_setting', get_option( 'ec_option_exchange_rates' ), __( 'Currency: Exchange Rates', 'wp-easycart' ), __( 'Enter a comma separated list in the format of EUR=.73,JPY=101.0,GBP=.64, where you list all but the base currency', 'wp-easycart' ), '.', 'ec_admin_exchange_rates_row', true, false, false ); ?>

    </div>
    
    <div class="ec_admin_settings_input">
        <input type="submit" class="ec_admin_settings_simple_button" onclick="return ec_admin_save_currency_options( );" value="<?php esc_attr_e( 'Save Options', 'wp-easycart' ); ?>" />
    </div>
</div>