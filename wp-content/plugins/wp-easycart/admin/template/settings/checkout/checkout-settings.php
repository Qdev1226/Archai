<div class="ec_admin_list_line_item ec_admin_demo_data_line">
            
	<div class="ec_admin_settings_label">
		<div class="dashicons-before dashicons-cart"></div>
		<span><?php esc_attr_e( 'Payment Page Options', 'wp-easycart' ); ?></span>
		<a href="<?php echo esc_url( wp_easycart_admin( )->helpsystem->print_docs_url( 'settings', 'checkout', 'settings' ) );?>" target="_blank" class="ec_help_icon_link">
			<div class="dashicons-before ec_help_icon dashicons-info"></div> <?php esc_attr_e( 'Help', 'wp-easycart' ); ?>
		</a>
    	<?php wp_easycart_admin( )->helpsystem->print_vids_url('settings', 'checkout', 'settings');?>
	</div>
	
    <div class="ec_admin_settings_input ec_admin_settings_live_payment_section">
		
        <?php wp_easycart_admin( )->load_toggle_group( 'ec_option_show_card_holder_name', 'ec_admin_save_cart_settings_options', get_option( 'ec_option_show_card_holder_name' ), __( 'Collect Card Holder Name', 'wp-easycart' ), __( 'Enable for greater payments compliance by requiring the customer to enter their card holder name.', 'wp-easycart' ) ); ?>
		
		<?php wp_easycart_admin( )->load_toggle_group_select( 'ec_option_default_payment_type', 'ec_admin_save_checkout_text_setting', get_option( 'ec_option_default_payment_type' ), __( 'Payment: Default Selection', 'wp-easycart' ), __( 'Choose the default selected method, most common is Credit Card.', 'wp-easycart' ), array(
			(object) array(
				'value'	=> 'manual_bill',
				'label'	=> __( 'Manual Billing', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'third_party',
				'label'	=> __( 'Third Party', 'wp-easycart' )
			),
			(object) array(
				'value'	=> 'credit_card',
				'label'	=> __( 'Credit Card', 'wp-easycart' )
			)
		), 'ec_admin_default_payment_type_row', true, false ); ?>
		
    </div>
</div>